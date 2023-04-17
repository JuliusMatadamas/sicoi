let entorno = document.querySelector("#entorno");
let form = document.querySelector("#form-venta");
let csrf = document.querySelector("#csrf");
let idUsuario = document.querySelector("#id_usuario");
let action = document.querySelector("#action");
let idVenta = document.querySelector("#id_venta");
let idTipoDeVenta = document.querySelector("#id_tipo_de_venta");
let idTipoDeVentaFeedback = document.querySelector("#id_tipo_de_venta-feedback");
let idTipoDeVentaTooltip = document.querySelector("#id_tipo_de_venta-tooltip");
let idTipoDeVentaValid = true;
let fechaProgramada = document.querySelector("#fecha_programada");
let fechaProgramadaFeedback = document.querySelector("#fecha_programada-feedback");
let fechaProgramadaTooltip = document.querySelector("#fecha_programada-tooltip");
let fechaProgramadaValid = true;
let observaciones = document.querySelector("#observaciones");
let observacionesFeedback = document.querySelector("#observaciones-feedback");
let observacionesTooltip = document.querySelector("#observaciones-tooltip");
let observacionesValid = false;
let btnEliminar = document.querySelector("#btn-eliminar");
let btnCancelar = document.querySelector("#btn-cancelar");
let btnSubmit = document.querySelector('button[type="submit"]');

btnEliminar.addEventListener("click", () => {
    document.querySelector("#modal-confirm_delete").style.display = 'flex';
});

btnCancelar.addEventListener("click", () => {
    window.location = entorno.value + '/ventas/consultar';
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
        formData.append('action', action.value);
        formData.append('id_venta', idVenta.value);
        formData.append('id_tipo_de_venta', idTipoDeVenta.value);
        formData.append('fecha_programada', fechaProgramada.value);
        formData.append('observaciones', observaciones.value.trim());


        xhttp.onreadystatechange = function () {
            if (xhttp.readyState) {
                if (xhttp.status === 200) {
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

function eliminarVenta() {
    document.querySelector("#modal-confirm_delete").style.display = 'none';
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
        formData.append('action', 'delete');
        formData.append('id_venta', idVenta.value);
        formData.append('id_tipo_de_venta', idTipoDeVenta.value);
        formData.append('fecha_programada', fechaProgramada.value);
        formData.append('observaciones', observaciones.value.trim());


        xhttp.onreadystatechange = function () {
            if (xhttp.readyState) {
                if (xhttp.status === 200) {
                    let res = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-loading").style.display = 'none';
                    document.querySelector("#modal-info").style.display = 'flex';
                    document.querySelector("#modal-info-message").innerHTML = `${res.message}`;
                    document.querySelector("#modal-info button").addEventListener("click", () => {
                        window.location = entorno.value + '/ventas/consultar';
                    });
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
}