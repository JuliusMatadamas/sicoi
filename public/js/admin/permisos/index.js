let entorno = document.querySelector("#entorno");
let registros = document.querySelector("#registros");
let form = document.querySelector("#form-validate_permiso");
let idPermiso = document.querySelector("#id_permiso");
let idUsuario = document.querySelector("#id_usuario");
let csrf = document.querySelector("#csrf");
let empleado = document.querySelector("#empleado");
let fechaInicio = document.querySelector("#fecha_inicio");
let horaInicio = document.querySelector("#hora_inicio");
let fechaTermino = document.querySelector("#fecha_termino");
let horaTermino = document.querySelector("#hora_termino");
let motivo = document.querySelector("#motivo");
let validacion = document.querySelector("#validacion");
let validacionFeedback = document.querySelector("#validacion-feedback");
let observaciones = document.querySelector("#observaciones");
let observacionesFeedback = document.querySelector("#observaciones-feedback");
let jwtTooltip = document.querySelector("#tooltip-jwt");
let btnSubmit = document.querySelector('button[type="submit"]');

let dt = new JSTable("#tabla-permisos_por_validar", {
    serverSide: true,
    deferLoading: registros.value,
    ajax: entorno.value + '/api/permisos/por_validar'
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

function cargarPermiso(data){
    let permiso = data.split('|');
    idPermiso.value = permiso[0];
    empleado.value = permiso[1];
    fechaInicio.value = permiso[2];
    horaInicio.value = permiso[3];
    fechaTermino.value = permiso[4];
    horaTermino.value = permiso[5];
    motivo.value = permiso[6];
    form.style.display = 'block';
}

validacion.addEventListener("change", e => {
    validacion.classList.remove('is-valid', 'is-invalid');
    validacionFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    validacionFeedback.innerHTML = `&nbsp;`;

    if (parseInt(validacion.value) === 0) {
        validacion.classList.remove('is-valid', 'is-invalid');
        validacionFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        validacionFeedback.innerHTML = `&nbsp;`;
        return;
    }

    if (parseInt(validacion.value) !== 0) {
        validacion.classList.add('is-valid');
        validacionFeedback.classList.add('valid-feedback');
        validacionFeedback.innerHTML = `Ok`;
    }
});

observaciones.addEventListener("keyup", e => {
    observaciones.classList.remove('is-valid', 'is-invalid');
    observacionesFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    observacionesFeedback.innerHTML = `&nbsp;`;

    if (observaciones.value.trim().length === 0) {
        observaciones.classList.remove('is-valid', 'is-invalid');
        observacionesFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        observacionesFeedback.innerHTML = `&nbsp;`;
    }

    if (observaciones.value.trim().length !== 0) {
        if (validacion.value === 0) {
            validacion.classList.add('is-invalid');
            validacionFeedback.classList.add('invalid-feedback');
            validacionFeedback.innerHTML = `Seleccione una opción.`;
        } else {
            if (observaciones.value.trim().length < 20) {
                observaciones.classList.add('is-invalid');
                observacionesFeedback.classList.add('invalid-feedback');
                observacionesFeedback.innerHTML = `Te faltan ${20 - observaciones.value.trim().length} caracteres.`;
            } else {
                observaciones.classList.add('is-valid');
                observacionesFeedback.classList.add('valid-feedback');
                observacionesFeedback.innerHTML = `Parece correcto.`;
            }
        }
    }
});

form.addEventListener("submit", e => {
    e.preventDefault();

    if (parseInt(validacion.value) === 0) {
        validacion.focus();
        validacion.classList.add('is-invalid');
        validacionFeedback.classList.add('invalid-feedback');
        validacionFeedback.innerHTML = `Se debe seleccionar una opción.`;
        return;
    }

    if (parseInt(validacion.value) === 2) {
        if (observaciones.value.trim().length < 20) {
            observaciones.classList.add('is-invalid');
            observacionesFeedback.classList.add('invalid-feedback');
            observacionesFeedback.innerHTML = `Se debe llenar las observaciones con al menos 20 caracteres.`;
            return;
        }
    }

    btnSubmit.disabled = true;
    document.querySelector("#modal-loading").style.display = 'flex';

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function (token) {
        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('csrf', csrf.value);
        formData.append('id_usuario', idUsuario.value);
        formData.append('id_permiso', idPermiso.value);
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