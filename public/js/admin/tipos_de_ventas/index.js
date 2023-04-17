let entorno = document.querySelector("#entorno");
let registros = document.querySelector("#registros");
let form = document.querySelector("#form-tipos_de_ventas");
let csrf = document.querySelector("#csrf");
let idUsuario = document.querySelector("#id_usuario");
let idTipoDeVenta = document.querySelector("#id_tipo_de_venta");
let action = document.querySelector("#action");
let labelTipoDeVenta = document.querySelector("#label-tipo_de_venta");
let tipoDeVenta = document.querySelector("#tipo_de_venta");
let tipoDeVentaFeedback = document.querySelector("#tipo_de_venta-feedback");
let tipoDeVentaTooltip = document.querySelector("#tipo_de_venta-tooltip");
let tipoDeVentaValid = false;
let btnSubmit = document.querySelector("#btn-submit");
let btnCancelar = document.querySelector("#btn-cancelar");
let jwtTooltip = document.querySelector("#tooltip-jwt");

let dt = new JSTable("#tabla-tipos_de_ventas", {
    serverSide: true,
    deferLoading: registros.value,
    ajax: entorno.value + '/api/tipos_de_ventas'
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

tipoDeVenta.addEventListener("keyup", e => {
    tipoDeVenta.classList.remove("is-valid", "is-invalid");
    tipoDeVentaFeedback.classList.remove("valid-feedback", "invalid-feedback");
    tipoDeVentaFeedback.innerHTML = `&nbsp;`;
    tipoDeVentaTooltip.style.display = 'none';

    if (tipoDeVenta.value.trim().length === 0) {
        tipoDeVenta.classList.remove("is-valid", "is-invalid");
        tipoDeVentaFeedback.classList.remove("valid-feedback", "invalid-feedback");
        tipoDeVentaFeedback.innerHTML = `&nbsp;`;
        tipoDeVentaValid = false;
        return;
    }

    if (!soloLetrasEspaciosAcentos(tipoDeVenta.value)) {
        tipoDeVenta.classList.add("is-invalid");
        tipoDeVentaFeedback.classList.add("invalid-feedback");
        tipoDeVentaFeedback.innerHTML = `Solo debes ingresar solo letras, espacios y/o acentos.`;
        tipoDeVentaValid = false;
        return;
    }

    if (tipoDeVenta.value.trim().length < 10) {
        tipoDeVenta.classList.add("is-invalid");
        tipoDeVentaFeedback.classList.add("invalid-feedback");
        tipoDeVentaFeedback.innerHTML = `Te faltan ${10 - tipoDeVenta.value.trim().length} caracteres.`;
        tipoDeVentaValid = false;
        return;
    }

    if (tipoDeVenta.value.trim().length > 100) {
        tipoDeVenta.classList.add("is-invalid");
        tipoDeVentaFeedback.classList.add("invalid-feedback");
        tipoDeVentaFeedback.innerHTML = `Te sobra(n) ${tipoDeVenta.value.trim().length - 100} caracter(es).`;
        tipoDeVentaValid = false;
        return;
    }

    tipoDeVenta.classList.add("is-valid");
    tipoDeVentaFeedback.classList.add("valid-feedback");
    tipoDeVentaFeedback.innerHTML = `Parece correcto.`;
    tipoDeVentaValid = true;
});

btnSubmit.addEventListener("click", e => {
    if (!tipoDeVentaValid) {
        tipoDeVenta.focus();
        tipoDeVentaTooltip.style.display = 'block';
        tipoDeVentaTooltip.innerHTML = `Llena esta campo como se solicita.`;
        return;
    }

    btnSubmit.disabled = true;
    btnCancelar.disabled = true;
    document.querySelector("#modal-loading").style.display = "flex";

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function(token) {

        // Enviar datos vía AJAX
        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('csrf', csrf.value);
        formData.append('id_usuario', idUsuario.value);
        formData.append('id', idTipoDeVenta.value);
        formData.append('action', action.value);
        formData.append('tipo', tipoDeVenta.value);
        formData.append('token', token);

        xhttp.onreadystatechange = function() {
            if (xhttp.readyState === 4 && xhttp.status === 200)
            {
                // DEFINIR LA ACCIÓN A REALIZAR COMO  CREATE
                action.value = 'create';
                // MODIFICAL EL TEXTO DEL LABEL
                labelTipoDeVenta.innerHTML = 'Ingrese el tipo de venta:';
                // VACIAR EL CAMPO TIPO DE VENTA
                tipoDeVenta.value = '';
                // ACTUALIZAR LA TABLA DE DATOS
                dt.update();
                // HABILITAR EL BOTÓN DE SUBMIT DEL FORMULARIO
                btnSubmit.disabled = false;
                btnSubmit.value = 'Guardar';
                // OCULTAR EL BOTON DE CANCELAR
                btnCancelar.style.display = 'none';
                // OCULTAR MODAL DE LOADING
                document.querySelector("#modal-loading").style.display = "none";
                // MOSTRAR MODAL DE INFO
                document.querySelector("#modal-info").style.display = "flex";
                // MOSTRAR EL MENSAJE
                let respuesta = JSON.parse(xhttp.responseText);
                document.querySelector("#modal-info-message").innerHTML = respuesta.message;

                /*document.querySelector("#modal-info button").addEventListener("click", () => {
                    window.location.reload();
                });*/
            }
            else
            {
                if (xhttp.status === 400)
                {
                    // HABILITAR EL BOTÓN DE SUBMIT DEL FORMULARIO
                    btnSubmit.disabled = false;
                    // OCULTAR MODAL DE LOADING
                    document.querySelector("#modal-loading").style.display = "none";
                    // MOSTRAR MODAL DE ERROR
                    document.querySelector("#modal-error").style.display = "flex";
                    // MOSTRAR EL MENSAJE
                    let respuesta = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-error-message").innerHTML = respuesta.message;
                }
            }
        }
        xhttp.open('post', form.getAttribute('action'), true);
        xhttp.send(formData);
    });
});

btnCancelar.addEventListener("click", e => {
    idTipoDeVenta.value = '';
    action.value = 'create';
    tipoDeVenta.value = '';
    btnSubmit.value = 'Guardar';
    btnCancelar.style.display = 'none';
});

function setEditTipo(params){
    let data = params.split('|');
    idTipoDeVenta.value = data[0];
    action.value = 'update';
    tipoDeVenta.value = data[1];
    btnSubmit.value = 'Actualizar';
    btnCancelar.style.display = 'block';
}

function confirmDeleteTipo(params){
    let data = params.split('|');
    idTipoDeVenta.value = data[0];
    document.querySelector("#modal-confirm_delete_tipo").style.display = 'flex';
    document.querySelector("#tipo_de_venta_detalle").innerHTML = data[1];
}

function cancelDelete(){
    idTipoDeVenta.value = '';
}

function deleteTipo(){
    document.querySelector("#modal-confirm_delete_tipo").style.display = 'none';
    btnSubmit.disabled = true;
    document.querySelector("#modal-loading").style.display = "flex";

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function(token) {

        // Enviar datos vía AJAX
        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('csrf', csrf.value);
        formData.append('id_usuario', idUsuario.value);
        formData.append('id', idTipoDeVenta.value);
        formData.append('action', 'delete');
        formData.append('token', token);

        xhttp.onreadystatechange = function() {
            if (xhttp.readyState === 4 && xhttp.status === 200)
            {
                // ACTUALIZAR LA TABLA DE DATOS
                dt.update();
                // RESETEAR EL CAMPO TIPO DE VENTA
                tipoDeVenta.value = '';
                // HABILITAR EL BOTÓN DE SUBMIT DEL FORMULARIO
                btnSubmit.disabled = false;
                btnSubmit.value = "Guardar";
                // OCULTAR EL BOTON DE CANCELAR
                btnCancelar.style.display = 'none';
                // OCULTAR MODAL DE LOADING
                document.querySelector("#modal-loading").style.display = "none";
                // MOSTRAR MODAL DE INFO
                document.querySelector("#modal-info").style.display = "flex";
                // MOSTRAR EL MENSAJE
                let respuesta = JSON.parse(xhttp.responseText);
                document.querySelector("#modal-info-message").innerHTML = respuesta.message;
            }
            else
            {
                if (xhttp.status === 400)
                {
                    // HABILITAR EL BOTÓN DE SUBMIT DEL FORMULARIO
                    btnSubmit.disabled = false;
                    // OCULTAR MODAL DE LOADING
                    document.querySelector("#modal-loading").style.display = "none";
                    // MOSTRAR MODAL DE ERROR
                    document.querySelector("#modal-error").style.display = "flex";
                    // MOSTRAR EL MENSAJE
                    let respuesta = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-error-message").innerHTML = respuesta.message;
                }
            }
        }
        xhttp.open('post', form.getAttribute('action'), true);
        xhttp.send(formData);
    });
}