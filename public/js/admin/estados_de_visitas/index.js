let form = document.querySelector("#form-estados_de_visitas");
let action = document.querySelector("#action");
let csrf = document.querySelector("#csrf");
let idUsuario = document.querySelector("#id_usuario");
let id = document.querySelector("#id");
let estadoDeVisitaLabel = document.querySelector("#estado_de_visita-label");
let estadoDeVisita = document.querySelector("#estado_de_visita");
let estadoDeVisitaFeedback = document.querySelector("#estado_de_visita-feedback");
let estadoDeVisitaTooltip = document.querySelector("#estado_de_visita-tooltip");
let btnSubmit = document.querySelector("#btn-submit");
let btnCancelar = document.querySelector("#btn-cancelar");
let jwtTooltip = document.querySelector("#tooltip-jwt");

let dt = new JSTable("#tabla-estados_de_visitas", {
    serverSide: true,
    deferLoading: registros.value,
    ajax: entorno.value + '/api/estados_de_visitas'
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
    estadoDeVisitaLabel.innerHTML = 'Edite el estado de la visita';
    estadoDeVisita.value = e;
    id.value = i;
    btnSubmit.value = 'Actualizar';
    btnCancelar.style.display = 'block';
}

function confirmarEliminacionDeEstadoDeVisita(i)
{
    action.value = 'delete';
    id.value = i;
    document.querySelector("#modal-confirm_estado_de_visita").style.display = 'flex';
}

function deleteEstadoDeVisita()
{
    // OCULTAR EL MODAL DE CONFIRMACIÓN
    document.querySelector("#modal-confirm_estado_de_visita").style.display = 'none';
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
    estadoDeVisitaLabel.innerHTML = 'Ingrese el estado de la inventario';
    estadoDeVisita.value = '';
    id.value = '';
    btnSubmit.value = 'Guardar';
    btnCancelar.style.display = 'none';
});

estadoDeVisita.addEventListener('keyup', () => {
    estadoDeVisitaTooltip.innerHTML = "&nbsp;";
    estadoDeVisitaTooltip.style.display = "none";

    if ( estadoDeVisita.value.trim().length > 0)
    {
        if (!soloLetrasEspaciosAcentos(estadoDeVisita.value))
        {
            estadoDeVisitaFeedback.innerHTML = '&nbsp;';
            estadoDeVisitaFeedback.classList.remove('valid-feedback');
            estadoDeVisita.classList.remove('is-valid');

            estadoDeVisitaFeedback.innerHTML = '¡Debes ingresar sólo letras!';
            estadoDeVisitaFeedback.classList.add('invalid-feedback');
            estadoDeVisita.classList.add('is-invalid');
        }
        else
        {
            estadoDeVisitaFeedback.innerHTML = '&nbsp;';
            estadoDeVisitaFeedback.classList.remove('invalid-feedback');
            estadoDeVisita.classList.remove('is-invalid');

            if (estadoDeVisita.value.trim().length >= 3)
            {
                estadoDeVisitaFeedback.innerHTML = 'Parece correcto';
                estadoDeVisitaFeedback.classList.add('valid-feedback');
                estadoDeVisita.classList.add('is-valid');
            }
        }
    }
});

btnSubmit.addEventListener('click', () => {
    // VALIDAR QUE EL CAMPO ESTADO DE VISITA TENGA LA LONGITUD MÍNIMA DE CARACTERES
    if (estadoDeVisita.value.trim().length < 7)
    {
        estadoDeVisitaTooltip.innerHTML = "Debes completar la información de este campo como se solicita.";
        estadoDeVisitaTooltip.style.display = "block";
        estadoDeVisita.focus();
        return;
    }

    // VALIDAR QUE EL CAMPO ESTADO DE VISITA SOLO CONTENGA LETRAS
    if (!soloLetrasEspaciosAcentos(estadoDeVisita.value.trim()))
    {
        estadoDeVisitaTooltip.innerHTML = "Debes completar la información de este campo como se solicita.";
        estadoDeVisitaTooltip.style.display = "block";
        estadoDeVisita.focus();
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
        formData.append('estado_de_visita', estadoDeVisita.value);
        formData.append('token', token);

        xhttp.onreadystatechange = function() {
            if (xhttp.readyState === 4 && xhttp.status === 200)
            {
                // DEFINIR LA ACCIÓN A REALIZAR COMO  CREATE
                action.value = 'create';
                // MODIFICAL EL TEXTO DEL LABEL
                estadoDeVisitaLabel.innerHTML = 'Ingrese el estado de la visita';
                // VACIAR EL CAMPO ESTADO DE LA VISITA
                estadoDeVisita.value = '';
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
