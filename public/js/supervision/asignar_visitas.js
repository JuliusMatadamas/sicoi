let entorno = document.querySelector("#entorno");
let idUsuario = document.querySelector("#id_usuario");
let registros = document.querySelector("#registros");
let jwtTooltip = document.querySelector("#tooltip-jwt");

let form = document.querySelector("#form-asignar_visita");
let csrf = document.querySelector("#csrf");
let idVenta = document.querySelector("#id_venta");
let cliente = document.querySelector("#cliente");
let tipoDeVenta = document.querySelector("#tipo_de_venta");
let fechaProgramada = document.querySelector("#fecha_programada");
let codigoPostal = document.querySelector("#codigo_postal");
let colonia = document.querySelector("#colonia");
let calle = document.querySelector("#calle");
let idTecnico = document.querySelector("#id_tecnico");
let idTecnicoFeedback = document.querySelector("#id_tecnico-feedback");
let idTecnicoTooltip = document.querySelector("#id_tecnico-tooltip");
let idTecnicoValid = false;

const dt = new JSTable("#tabla-ventas", {
    serverSide: true,
    deferLoading: registros.value,
    ajax: entorno.value + '/api/ventas_por_asignar'
});

dt.on("fetchData", function (serverData) {
    jwtTooltip.style.display = 'none';
    if (serverData.length == 0) {
        jwtTooltip.style.display = 'block';
        jwtTooltip.innerHTML = 'El token ha caducado, necesita iniciar sesión nuevamente.';
    } else {
        jwtTooltip.style.display = 'none';
    }
});

idTecnico.addEventListener("change", e => {
    idTecnico.classList.remove('is-valid', 'is-invalid');
    idTecnicoFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    idTecnicoFeedback.innerHTML = `&nbsp;`;
    idTecnicoTooltip.style.display = 'none';
    idTecnicoTooltip.innerHTML = `&nbsp;`;

    if (parseInt(idTecnico.value) === 0) {
        idTecnico.classList.remove('is-valid', 'is-invalid');
        idTecnicoFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        idTecnicoFeedback.innerHTML = `&nbsp;`;
        idTecnicoValid = false;
        return;
    }

    idTecnico.classList.add('is-valid');
    idTecnicoFeedback.classList.add('valid-feedback');
    idTecnicoFeedback.innerHTML = `Parece coorecto.`;
    idTecnicoValid = true;
});

function asignacion(idv, cte, col, cal, cp, tdv, fp) {
    idTecnico.value = 0;
    idTecnico.classList.remove('is-valid', 'is-invalid');
    idTecnicoFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    idTecnicoFeedback.innerHTML = `&nbsp;`;
    idTecnicoTooltip.style.display = 'none';
    idTecnicoTooltip.innerHTML = `&nbsp;`;
    idTecnicoValid = false;
    idVenta.value = parseInt(idv);
    cliente.value = cte;
    colonia.value = col;
    calle.value = cal;
    codigoPostal.value = cp;
    tipoDeVenta.value = tdv;
    fechaProgramada.value = fp;
}

function cancelacion() {
    idVenta.value = '';
    cliente.value = '';
    colonia.value = '';
    calle.value = '';
    codigoPostal.value = '';
    tipoDeVenta.value = '';
    fechaProgramada.value = '';
    idTecnico.value = 0;
    idTecnico.classList.remove('is-valid', 'is-invalid');
    idTecnicoFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    idTecnicoFeedback.innerHTML = `&nbsp;`;
    idTecnicoTooltip.style.display = 'none';
    idTecnicoTooltip.innerHTML = `&nbsp;`;
    idTecnicoValid = false;
}

function submitForm() {
    if (idVenta.value === '') {
        idTecnicoTooltip.style.display = 'block';
        idTecnicoTooltip.innerHTML = `¡Debes seleccionar la venta a la que se va a asignar el técnico que realizará la visita!`;
        return;
    }

    if (!idTecnicoValid) {
        idTecnico.focus();
        idTecnico.classList.add('is-invalid');
        idTecnicoFeedback.classList.add('invalid-feedback');
        idTecnicoFeedback.innerHTML = `¡Seleccione el técnico que realizará la visita!`;
        idTecnicoValid = false;
        return;
    }

    document.querySelector("#modal-loading").style.display = 'flex';

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function (token) {
        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('id_usuario', idUsuario.value);
        formData.append('id_venta', idVenta.value);
        formData.append('id_tecnico', idTecnico.value);
        formData.append('csrf', csrf.value);
        formData.append('token', token);


        xhttp.onreadystatechange = function () {
            if (xhttp.readyState === 4) {
                if (xhttp.status === 200) {
                    let response = JSON.parse(xhttp.responseText);
                    dt.update();
                    document.querySelector("#modal-loading").style.display = 'none';
                    document.querySelector("#modal-info").style.display = 'flex';
                    document.querySelector("#modal-info-message").innerHTML = `${response.message}`;
                    cancelacion();
                    return;
                }

                if (xhttp.status === 400) {
                    let response = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-loading").style.display = 'none';
                    document.querySelector("#modal-error").style.display = 'flex';
                    document.querySelector("#modal-error-message").innerHTML = `${response.message}`;
                }
            }
        }
        xhttp.open('post', form.getAttribute('action'), true);
        xhttp.send(formData);
    });
}
