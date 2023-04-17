let entorno = document.querySelector("#entorno");
let form = document.querySelector("#form-validate_incapacidad");
let idIncapacidad = document.querySelector("#id_incapacidad");
let idUsuario = document.querySelector("#id_usuario");
let csrf = document.querySelector("#csrf");
let validacion = document.querySelector("#validacion");
let validacionFeedback = document.querySelector("#validacion-feedback");
let observaciones = document.querySelector("#observaciones");
let observacionesFeedback = document.querySelector("#observaciones-feedback");
let jwtTooltip = document.querySelector("#tooltip-jwt");
let btnSubmit = document.querySelector('button[type="submit"]');

let dt = new JSTable("#tabla-incapacidades_por_validar", {
    serverSide: true,
    deferLoading: registros.value,
    ajax: entorno.value + '/api/incapacidades/por_validar'
});

dt.on("fetchData", function(serverData){
    jwtTooltip.style.display = 'none';

    if (serverData.length == 0) {
        jwtTooltip.style.display = 'block';
        jwtTooltip.innerHTML = 'El token ha caducado, necesita iniciar sesión nuevamente.';
    } else {
        jwtTooltip.style.display = 'none';
    }
});

function cargarIncapacidad(data) {
    let info = data.split('|');
    document.querySelector("#id_incapacidad").value = info[0];
    document.querySelector("#empleado").value = info[1];
    document.querySelector("#inicio").value = info[2];
    document.querySelector("#termino").value = info[3];

    let els = info[4].split('.');
    let ext = els.pop();
    let path = document.querySelector("#entorno").value + info[4];

    if (ext == "pdf") {
        document.querySelector("#vista-comprobante").innerHTML = `<object id="obj-result" type="application/pdf" data="${path}" class="obj-preview"></object>`;
    } else {
        document.querySelector("#vista-comprobante").innerHTML = `<img src="${path}" class="img-preview">`;
    }

    document.querySelector("#form-validate_incapacidad").style.display = 'block';
}

observaciones.addEventListener('keyup', e => {
    observaciones.classList.remove('is-valid', 'is-invalid');
    observacionesFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    observacionesFeedback.innerHTML = '&nbsp;';

    if (observaciones.value.trim().length == 0) {
        observaciones.classList.remove('is-valid', 'is-invalid');
        observacionesFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        observacionesFeedback.innerHTML = '&nbsp;';
        return;
    }

    if (observaciones.value.trim().length < 20) {
        observaciones.classList.add('is-invalid');
        observacionesFeedback.classList.add('invalid-feedback');
        observacionesFeedback.innerHTML = `Te faltan ${20 - observaciones.value.trim().length} caracteres.`;
        return;
    }

    observaciones.classList.add('is-valid');
    observacionesFeedback.classList.add('valid-feedback');
    observacionesFeedback.innerHTML = `Parece correcto`;
});

form.addEventListener("submit", e => {
    e.preventDefault();

    if (validacion.value == 0) {
        validacion.focus();
        validacion.classList.add('is-invalid');
        validacionFeedback.classList.add('invalid-feedback');
        validacionFeedback.innerHTML = 'Debes seleccionar una opción.';
        return;
    }

    if (validacion.value == 2 && observaciones.value.trim().length < 20) {
        observaciones.focus();
        observaciones.classList.add('is-invalid');
        observacionesFeedback.classList.add('invalid-feedback');
        observacionesFeedback.innerHTML = 'Debes ingresar al menos 20 caracteres en las observaciones.';
        return;
    }

    validacion.classList.remove('is-valid', 'is-invalid');
    validacionFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    validacionFeedback.innerHTML = '&nbsp;';
    document.querySelector("#modal-loading").style.display = 'flex';
    btnSubmit.disabled = true;

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function (token) {
        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('id_incapacidad', idIncapacidad.value);
        formData.append('id_usuario', idUsuario.value);
        formData.append('csrf', csrf.value);
        formData.append('validacion', validacion.value);
        formData.append('observaciones', observaciones.value);
        formData.append('token', token);

        xhttp.onreadystatechange = function () {
            if (xhttp.readyState === 4 && xhttp.status === 200) {
                let response = JSON.parse(xhttp.responseText);
                document.querySelector("#modal-loading").style.display = 'none';
                document.querySelector("#modal-info").style.display = 'flex';
                document.querySelector("#modal-info-message").innerHTML = `${response["message"]}`;
                document.querySelector("#modal-info button").addEventListener('click', () => {
                    window.location.reload();
                });
            } else {
                if (xhttp.status === 400) {
                    let response = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-loading").style.display = 'none';
                    document.querySelector("#modal-error").style.display = 'flex';
                    document.querySelector("#modal-error-message").innerHTML = `${response["message"]}`;
                }
            }
        }
        xhttp.open('POST', form.getAttribute('action'), true);
        xhttp.send(formData);
    });
});