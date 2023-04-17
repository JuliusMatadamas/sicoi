let entorno = document.querySelector("#entorno");
let form = document.querySelector("#form-venta");
let csrf = document.querySelector("#csrf");
let idUsuario = document.querySelector("#id_usuario");
let idCliente = document.querySelector("#id_cliente");
let idClienteFeedback = document.querySelector("#id_cliente-feedback");
let idClienteTooltip = document.querySelector("#id_cliente-tooltip");
let idClienteValid = parseInt(document.querySelector("#id_cliente_valid").value);
let idTipoDeVenta = document.querySelector("#id_tipo_de_venta");
let idTipoDeVentaFeedback = document.querySelector("#id_tipo_de_venta-feedback");
let idTipoDeVentaTooltip = document.querySelector("#id_tipo_de_venta-tooltip");
let idTipoDeVentaValid = false;
let fechaProgramada = document.querySelector("#fecha_programada");
let fechaProgramadaFeedback = document.querySelector("#fecha_programada-feedback");
let fechaProgramadaTooltip = document.querySelector("#fecha_programada-tooltip");
let fechaProgramadaValid = false;
let observaciones = document.querySelector("#observaciones");
let observacionesFeedback = document.querySelector("#observaciones-feedback");
let observacionesTooltip = document.querySelector("#observaciones-tooltip");
let observacionesValid = false;
let btnCancelar = document.querySelector("#btn-cancelar");
let btnSubmit = document.querySelector('button[type="submit"]');

btnCancelar.addEventListener("click", () => {
    cancelarVenta();
});

idCliente.addEventListener("change", () => {
    idCliente.classList.remove('is-valid', 'is-invalid');
    idClienteFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    idClienteFeedback.innerHTML = `&nbsp;`;
    idClienteTooltip.style.display = 'none';
    idClienteTooltip.innerHTML = `&nbsp;`;

    if (idCliente.value == '0') {
        idCliente.classList.remove('is-valid', 'is-invalid');
        idClienteFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        idClienteFeedback.innerHTML = `&nbsp;`;
        idClienteTooltip.style.display = 'none';
        idClienteTooltip.innerHTML = `&nbsp;`;
        idClienteValid = false;
        return;
    }

    idCliente.classList.add('is-valid');
    idClienteFeedback.classList.add('valid-feedback');
    idClienteFeedback.innerHTML = `Parece correcto`;
    idClienteTooltip.style.display = 'none';
    idClienteTooltip.innerHTML = `&nbsp;`;
    idClienteValid = true;
});

idTipoDeVenta.addEventListener("change", () => {
    idTipoDeVenta.classList.remove('is-valid', 'is-invalid');
    idTipoDeVentaFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    idTipoDeVentaFeedback.innerHTML = `&nbsp;`;
    idTipoDeVentaTooltip.style.display = 'none';
    idTipoDeVentaTooltip.innerHTML = `&nbsp;`;

    if (parseInt(idTipoDeVenta.value) === 0) {
        idTipoDeVenta.classList.remove('is-valid', 'is-invalid');
        idTipoDeVentaFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        idTipoDeVentaFeedback.innerHTML = `&nbsp;`;
        idTipoDeVentaTooltip.style.display = 'none';
        idTipoDeVentaTooltip.innerHTML = `&nbsp;`;
        idTipoDeVentaValid = false;
        return;
    }

    idTipoDeVenta.classList.add('is-valid');
    idTipoDeVentaFeedback.classList.add('valid-feedback');
    idTipoDeVentaFeedback.innerHTML = `Parece correcto`;
    idTipoDeVentaTooltip.style.display = 'none';
    idTipoDeVentaTooltip.innerHTML = `&nbsp;`;
    idTipoDeVentaValid = true;
});

fechaProgramada.addEventListener("keyup", () => {
    validarFecha();
});
fechaProgramada.addEventListener("change", () => {
    validarFecha();
});
function validarFecha() {
    fechaProgramada.classList.remove('is-valid', 'is-invalid');
    fechaProgramadaFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    fechaProgramadaFeedback.innerHTML = "&nbsp;";
    fechaProgramadaTooltip.style.display = "none";
    fechaProgramadaTooltip.innerHTML = "&nbsp;";

    if (fechaProgramada.value.trim().length === 0) {
        fechaProgramada.classList.remove('is-valid', 'is-invalid');
        fechaProgramadaFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        fechaProgramadaFeedback.innerHTML = "&nbsp;";
        fechaProgramadaTooltip.style.display = "none";
        fechaProgramadaTooltip.innerHTML = "&nbsp;";
        fechaProgramadaValid = false;
    } else {
        if (!esFechaValida(fechaProgramada.value.trim())) {
            fechaProgramada.classList.add('is-invalid');
            fechaProgramadaFeedback.classList.add('invalid-feedback');
            fechaProgramadaFeedback.innerHTML = "La fecha ingresada no es valida.";
            fechaProgramadaTooltip.style.display = "none";
            fechaProgramadaTooltip.innerHTML = "&nbsp;";
            fechaProgramadaValid = false;
        } else {
            const str = fechaProgramada.value;
            const [annio, mes, dia] = str.split('-');
            const date1 = new Date(+annio, +mes-1, +dia);
            const date2 = new Date();
            if ((date2.getTime() - date1.getTime())/(1000*3600*24) >= 1) {
                fechaProgramada.classList.add('is-invalid');
                fechaProgramadaFeedback.classList.add('invalid-feedback');
                fechaProgramadaFeedback.innerHTML = "La fecha ingresada no puede ser anterior a la fecha actual.";
                fechaProgramadaTooltip.style.display = "none";
                fechaProgramadaTooltip.innerHTML = "&nbsp;";
                fechaProgramadaValid = false;
            } else {
                fechaProgramada.classList.add('is-valid');
                fechaProgramadaFeedback.classList.add('valid-feedback');
                fechaProgramadaFeedback.innerHTML = "Parece correcta";
                fechaProgramadaTooltip.style.display = "none";
                fechaProgramadaTooltip.innerHTML = "&nbsp;";
                fechaProgramadaValid = true;
            }
        }
    }
}

observaciones.addEventListener("keyup", e => {
    observaciones.classList.remove('is-valid', 'is-invalid');
    observacionesFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    observacionesFeedback.innerHTML = "&nbsp;";
    observacionesTooltip.style.display = "none";
    observacionesTooltip.innerHTML = "&nbsp;";

    if (observaciones.value.trim().length === 0) {
        observaciones.classList.remove('is-valid', 'is-invalid');
        observacionesFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        observacionesFeedback.innerHTML = "&nbsp;";
        observacionesTooltip.style.display = "none";
        observacionesTooltip.innerHTML = "&nbsp;";
        observacionesValid = false;
        return;
    }

    if (observaciones.value.trim().length < 10) {
        observaciones.classList.add('is-invalid');
        observacionesFeedback.classList.add('invalid-feedback');
        observacionesFeedback.innerHTML = `Te faltan ${2 - observaciones.value.trim().length} caracteres.`;
        observacionesTooltip.style.display = "none";
        observacionesTooltip.innerHTML = "&nbsp;";
        observacionesValid = false;
        return;
    }

    if (observaciones.value.trim().length > 255) {
        observaciones.classList.add('is-invalid');
        observacionesFeedback.classList.add('invalid-feedback');
        observacionesFeedback.innerHTML = `Te sobran ${observaciones.value.trim().length - 255} caracteres.`;
        observacionesTooltip.style.display = "none";
        observacionesTooltip.innerHTML = "&nbsp;";
        observacionesValid = false;
        return;
    }

    observaciones.classList.add('is-valid');
    observacionesFeedback.classList.add('valid-feedback');
    observacionesFeedback.innerHTML = `Parece correcto`;
    observacionesTooltip.style.display = "none";
    observacionesTooltip.innerHTML = "&nbsp;";
    observacionesValid = true;
});


form.addEventListener("submit", e => {
    e.preventDefault();

    if (!idClienteValid) {
        idCliente.focus();
        idClienteTooltip.style.display = 'block';
        idClienteTooltip.innerHTML = `Selecciona un cliente de la lista.`;
        return;
    }

    if (!idTipoDeVentaValid) {
        idTipoDeVenta.focus();
        idTipoDeVentaTooltip.style.display = 'block';
        idTipoDeVentaTooltip.innerHTML = `Selecciona una opción de venta.`;
        return;
    }

    if (!fechaProgramadaValid) {
        fechaProgramada.focus();
        fechaProgramadaTooltip.style.display = 'block';
        fechaProgramadaTooltip.innerHTML = `Ingresa una fecha válida.`;
        return;
    }

    if (!observacionesValid) {
        observaciones.focus();
        observacionesTooltip.style.display = 'block';
        observacionesTooltip.innerHTML = `Completa este campo como se solicita.`;
        return;
    }

    btnSubmit.disabled = true;
    document.querySelector("#modal-loading").style.display = 'flex';

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function (token) {

        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('id_usuario', idUsuario.value);
        formData.append('token', token);
        formData.append('csrf', csrf.value);
        formData.append('id_cliente', idCliente.value);
        formData.append('id_tipo_de_venta', idTipoDeVenta.value);
        formData.append('fecha_programada', fechaProgramada.value);
        formData.append('observaciones', observaciones.value.trim());


        xhttp.onreadystatechange = function () {
            if (xhttp.readyState) {
                if (xhttp.status === 200) {
                    cancelarVenta();
                    let res = JSON.parse(xhttp.responseText);
                    btnSubmit.disabled = false;
                    document.querySelector("#modal-loading").style.display = 'none';
                    document.querySelector("#modal-info").style.display = 'flex';
                    document.querySelector("#modal-info-message").innerHTML = `${res.message}`;
                }
                if (xhttp.status === 400) {
                    let res = JSON.parse(xhttp.responseText);
                    btnSubmit.disabled = false;
                    document.querySelector("#modal-loading").style.display = 'none';
                    document.querySelector("#modal-error").style.display = 'flex';
                    document.querySelector("#modal-error-message").innerHTML = `<p class="text-center">${res.message}</p>`;
                }
            }
        }
        xhttp.open('post', form.getAttribute('action'), true);
        xhttp.send(formData);
    });
});

function cancelarVenta() {
    if (idCliente.tagName === "SELECT") {
        idCliente.value = 0;
        idClienteValid = false;
    } else {
        idClienteValid = parseInt(document.querySelector("#id_cliente_valid").value);
    }
    idCliente.classList.remove('is-valid', 'is-invalid');
    idClienteFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    idClienteFeedback.innerHTML = `&nbsp;`;
    idClienteTooltip.style.display = 'none';
    idClienteTooltip.innerHTML = `&nbsp;`;

    idTipoDeVenta.value = 0;
    idTipoDeVenta.classList.remove('is-valid', 'is-invalid');
    idTipoDeVentaFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    idTipoDeVentaFeedback.innerHTML = `&nbsp;`;
    idTipoDeVentaTooltip.style.display = 'none';
    idTipoDeVentaTooltip.innerHTML = `&nbsp;`;
    idTipoDeVentaValid = false;

    fechaProgramada.value = '';
    fechaProgramada.classList.remove('is-valid', 'is-invalid');
    fechaProgramadaFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    fechaProgramadaFeedback.innerHTML = "&nbsp;";
    fechaProgramadaTooltip.style.display = "none";
    fechaProgramadaTooltip.innerHTML = "&nbsp;";
    fechaProgramadaValid = false;

    observaciones.value = '';
    observaciones.classList.remove('is-valid', 'is-invalid');
    observacionesFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    observacionesFeedback.innerHTML = "&nbsp;";
    observacionesTooltip.style.display = "none";
    observacionesTooltip.innerHTML = "&nbsp;";
    observacionesValid = false;
}