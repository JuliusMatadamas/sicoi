let form = document.querySelector("#form-estados_de_inventarios");
let action = document.querySelector("#action");
let csrf = document.querySelector("#csrf");
let idUsuario = document.querySelector("#id_usuario");
let id = document.querySelector("#id");
let estadoDeInventarioLabel = document.querySelector("#estado_de_inventario-label");
let estadoDeInventario = document.querySelector("#estado_de_inventario");
let estadoDeInventarioFeedback = document.querySelector("#estado_de_inventario-feedback");
let estadoDeInventarioTooltip = document.querySelector("#estado_de_inventario-tooltip");
let btnSubmit = document.querySelector("#btn-submit");
let btnCancelar = document.querySelector("#btn-cancelar");
let jwtTooltip = document.querySelector("#tooltip-jwt");

let dt = new JSTable("#tabla-estados_de_inventarios", {
    serverSide: true,
    deferLoading: registros.value,
    ajax: entorno.value + '/api/estados_de_inventarios'
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

function setEdit(i,e)
{
    action.value = 'update';
    estadoDeInventarioLabel.innerHTML = 'Edite el estado del inventario';
    estadoDeInventario.value = e;
    id.value = i;
    btnSubmit.value = 'Actualizar';
    btnCancelar.style.display = 'block';
}

function confirmarEliminacionDeEstadoDeInventario(i)
{
    action.value = 'delete';
    id.value = i;
    document.querySelector("#modal-confirm_estado_de_inventario").style.display = 'flex';
}

function deleteEstadoDeInventario()
{
    // OCULTAR EL MODAL DE CONFIRMACIÓN
    document.querySelector("#modal-confirm_estado_de_inventario").style.display = 'none';
    // MOSTRAR EL MODAL DE LOADING
    document.querySelector("#modal-loading").style.display = "flex";

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function(token) {
        let url = form.getAttribute("action");

        // Enviar datos vía AJAX
        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('action', action.value);
        formData.append('csrf', csrf.value);
        formData.append('id_usuario', idUsuario.value);
        formData.append('id', id.value);
        formData.append('token', token);

        xhttp.onreadystatechange = function() {
            if (xhttp.readyState === 4 && xhttp.status === 200)
            {
                // ACTUALIZAR LA TABLA DE DATOS
                dt.update();
                // OCULTAR MODAL DE LOADING
                document.querySelector("#modal-loading").style.display = "none";
                // MOSTRAR MODAL DE INFO
                document.querySelector("#modal-info").style.display = "flex";
                // MOSTRAR EL MENSAJE
                let respuesta = JSON.parse(xhttp.responseText);
                document.querySelector("#modal-info-message").innerHTML = respuesta["message"];

                document.querySelector("#modal-info button").addEventListener("click", () => {
                    window.location.reload();
                });
            }
            else
            {
                if (xhttp.status === 400)
                {
                    // OCULTAR MODAL DE LOADING
                    document.querySelector("#modal-loading").style.display = "none";
                    // MOSTRAR MODAL DE ERROR
                    document.querySelector("#modal-error").style.display = "flex";
                    // MOSTRAR EL MENSAJE
                    let respuesta = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-error-message").innerHTML = respuesta["message"];
                }
            }
        }
        xhttp.open('post', url, true);
        xhttp.send(formData);
    });
}

btnCancelar.addEventListener("click", () => {
    action.value = 'create';
    estadoDeInventarioLabel.innerHTML = 'Ingrese el estado del inventario';
    estadoDeInventario.value = '';
    id.value = '';
    btnSubmit.value = 'Guardar';
    btnCancelar.style.display = 'none';
});

estadoDeInventario.addEventListener('keyup', () => {
    estadoDeInventarioTooltip.innerHTML = "&nbsp;";
    estadoDeInventarioTooltip.style.display = "none";

    if ( estadoDeInventario.value.trim().length > 0)
    {
        if (!soloLetrasEspaciosAcentos(estadoDeInventario.value))
        {
            estadoDeInventarioFeedback.innerHTML = '&nbsp;';
            estadoDeInventarioFeedback.classList.remove('valid-feedback');
            estadoDeInventario.classList.remove('is-valid');

            estadoDeInventarioFeedback.innerHTML = '¡Debes ingresar sólo letras!';
            estadoDeInventarioFeedback.classList.add('invalid-feedback');
            estadoDeInventario.classList.add('is-invalid');
        }
        else
        {
            estadoDeInventarioFeedback.innerHTML = '&nbsp;';
            estadoDeInventarioFeedback.classList.remove('invalid-feedback');
            estadoDeInventario.classList.remove('is-invalid');

            if (estadoDeInventario.value.trim().length >= 3)
            {
                estadoDeInventarioFeedback.innerHTML = 'Parece correcto';
                estadoDeInventarioFeedback.classList.add('valid-feedback');
                estadoDeInventario.classList.add('is-valid');
            }
        }
    }
});

btnSubmit.addEventListener('click', () => {
    // VALIDAR QUE EL CAMPO ESTADO DE INVENTARIO TENGA LA LONGITUD MÍNIMA DE CARACTERES
    if (estadoDeInventario.value.trim().length < 9)
    {
        estadoDeInventarioTooltip.innerHTML = "Debes completar la información de este campo como se solicita.";
        estadoDeInventarioTooltip.style.display = "block";
        estadoDeInventario.focus();
        return;
    }

    // VALIDAR QUE EL CAMPO ESTADO DE INVENTARIO SOLO CONTENGA LETRAS
    if (!soloLetrasEspaciosAcentos(estadoDeInventario.value.trim()))
    {
        estadoDeInventarioTooltip.innerHTML = "Debes completar la información de este campo como se solicita.";
        estadoDeInventarioTooltip.style.display = "block";
        estadoDeInventario.focus();
        return;
    }

    // DESHABILITAR EL BOTÓN DE SUBMIT DEL FORMULARIO
    btnSubmit.disabled = true;
    // MOSTRAR EL MODAL DE LOADING
    document.querySelector("#modal-loading").style.display = "flex";

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function(token) {
        let url = form.getAttribute("action");

        // Enviar datos vía AJAX
        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('action', action.value);
        formData.append('csrf', csrf.value);
        formData.append('id_usuario', idUsuario.value);
        formData.append('id', id.value);
        formData.append('estado_de_inventario', estadoDeInventario.value);
        formData.append('token', token);

        xhttp.onreadystatechange = function() {
            if (xhttp.readyState === 4 && xhttp.status === 200)
            {
                // DEFINIR LA ACCIÓN A REALIZAR COMO  CREATE
                action.value = 'create';
                // MODIFICAL EL TEXTO DEL LABEL
                estadoDeInventarioLabel.innerHTML = 'Ingrese el estado del inventario';
                // VACIAR EL CAMPO ESTADO DEL INVENTARIO
                estadoDeInventario.value = '';
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
                document.querySelector("#modal-info-message").innerHTML = respuesta["message"];

                document.querySelector("#modal-info button").addEventListener("click", () => {
                    window.location.reload();
                });
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
                    document.querySelector("#modal-error-message").innerHTML = respuesta["message"];
                }
            }
        }
        xhttp.open('post', url, true);
        xhttp.send(formData);
    });
});
