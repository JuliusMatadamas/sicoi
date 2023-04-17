let d = new Date();
let month = d.getMonth() + 1;
if ( month < 10 ) month = '0' + month;
let currentDate = `${d.getFullYear()}-${month}-${d.getDate()}`;

let form = document.querySelector("#form-create_permiso");
let csrf = document.querySelector("#csrf");
let idUsuario = document.querySelector("#id_usuario");
let fechaInicio = document.querySelector("#fecha_inicio");
let fechaInicioTooltip = document.querySelector("#tooltip-fecha_inicio");
let horaInicio = document.querySelector("#hora_inicio");
let fechaTermino = document.querySelector("#fecha_termino");
let fechaTerminoTooltip = document.querySelector("#tooltip-fecha_termino");
let horaTermino = document.querySelector("#hora_termino");
let motivo = document.querySelector("#motivo");
let motivoFeedback = document.querySelector("#feedback-motivo");
let motivoTooltip = document.querySelector("#tooltip-motivo");
let btnSubmit = document.querySelector("#btn-submit");

// Asignar la fecha actual a los campos de fecha
fechaInicio.value = currentDate;
fechaTermino.value = currentDate;

// Escucha de evento 'change' en el campo 'fecha_inicio'
fechaInicio.addEventListener('change', evt => {
    fechaInicioTooltip.style.display = 'none';
    fechaTerminoTooltip.style.display = 'none';
});

// Escucha de evento 'change' en el campo 'hora_inicio'
horaInicio.addEventListener('change', evt => {
    fechaInicioTooltip.style.display = 'none';
    fechaTerminoTooltip.style.display = 'none';
});

// Escucha de evento 'change' en el campo 'fecha_termino'
fechaTermino.addEventListener('change', evt => {
    fechaInicioTooltip.style.display = 'none';
    fechaTerminoTooltip.style.display = 'none';
});

// Escucha de evento 'change' en el campo 'hora_termino'
horaTermino.addEventListener('change', evt => {
    fechaInicioTooltip.style.display = 'none';
    fechaTerminoTooltip.style.display = 'none';
});

// Escucha de eventos en el campo 'motivo'
motivo.addEventListener('keyup', listenMotivo, false);
motivo.addEventListener('change', listenMotivo, false);

form.addEventListener("submit", e => {
    e.preventDefault();

    if (fechaInicio.value.length == 0) {
        fechaInicioTooltip.style.display = 'block';
        fechaInicioTooltip.innerHTML = 'Se debe ingresar la fecha de inicio del permiso solicitado.';
        return;
    }

    if (fechaTermino.value.length == 0) {
        fechaTerminoTooltip.style.display = 'block';
        fechaTerminoTooltip.innerHTML = 'Se debe ingresar la fecha de término del permiso solicitado.';
        return;
    }

    let partesFechaInicio = fechaInicio.value.split('-');
    let partesHoraInicio = horaInicio.value.split(':');
    let fInicio = new Date( parseInt(partesFechaInicio[0]), parseInt(partesFechaInicio[1]) - 1, parseInt(partesFechaInicio[2]), parseInt(partesHoraInicio[0]), parseInt(partesHoraInicio[1]), 0 );

    let partesFechaTermino = fechaTermino.value.split('-');
    let partesHoraTermino = horaTermino.value.split(':');
    let fTermino = new Date( parseInt(partesFechaTermino[0]), parseInt(partesFechaTermino[1]) - 1, parseInt(partesFechaTermino[2]), parseInt(partesHoraTermino[0]), parseInt(partesHoraTermino[1]), 0 );

    let diff = fTermino.getTime() - fInicio.getTime();

    if (diff <= 0) {
        fechaTerminoTooltip.style.display = 'block';
        fechaTerminoTooltip.innerHTML = 'La fecha y hora de término del permiso no pueden ser igual o anteriores a la fecha y hora de inicio.';
        return;
    }

    if (motivo.value.length < 20) {
        motivoTooltip.style.display = 'block';
        motivoTooltip.innerHTML = 'Este campo no cumple con lo requerido.';
        return;
    }

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function (token) {

        btnSubmit.disabled = true;
        document.querySelector("#modal-loading").style.display = 'flex';

        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('csrf', csrf.value);
        formData.append('id_usuario', idUsuario.value);
        formData.append('fecha_inicio', fechaInicio.value);
        formData.append('hora_inicio', horaInicio.value);
        formData.append('fecha_termino', fechaTermino.value);
        formData.append('hora_termino', horaTermino.value);
        formData.append('motivo', motivo.value);
        formData.append('token', token);


        xhttp.onreadystatechange = function () {
            if (xhttp.readyState === 4 && xhttp.status === 200) {
                btnSubmit.disabled = false;
                document.querySelector("#modal-loading").style.display = 'none';
                let response = JSON.parse(xhttp.responseText);
                fechaInicio.value = currentDate;
                horaInicio.selectedIndex = "0";
                fechaTermino.value = currentDate;
                horaTermino.selectedIndex = "0";
                motivo.value = '';
                document.querySelector("#modal-info").style.display = 'flex';
                document.querySelector("#modal-info-message").innerHTML = `${response["message"]}`;
            } else {
                if (xhttp.status === 400) {
                    btnSubmit.disabled = false;
                    document.querySelector("#modal-loading").style.display = 'none';
                    let response = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-error").style.display = 'flex';
                    document.querySelector("#modal-error-message").innerHTML = `${response["message"]}`;
                }
            }
        }
        xhttp.open('POST', form.getAttribute('action'), true);
        xhttp.send(formData);
    });
});

function listenMotivo(evt) {
    motivo.classList.remove('is-valid', 'is-invalid');
    motivoFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    motivoTooltip.style.display = 'none';

    if (motivo.value.length == 0) {
        motivo.classList.remove('is-valid', 'is-invalid');
        motivoFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        motivoFeedback.innerHTML = '&nbsp;';
        return;
    }

    if (motivo.value.length < 20) {
        motivo.classList.add('is-invalid');
        motivoFeedback.classList.add('invalid-feedback');
        motivoFeedback.innerHTML = `Te faltan ${20 - motivo.value.length} caracteres`;
        return;
    }

    if (motivo.value.length >= 20) {
        motivo.classList.add('is-valid');
        motivoFeedback.classList.add('valid-feedback');
        motivoFeedback.innerHTML = `Parece correcto.`;
    }
}