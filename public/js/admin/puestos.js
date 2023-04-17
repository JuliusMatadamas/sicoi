let url = document.querySelector("#url");
let regs = document.querySelector("#registros");
let formPuesto = document.querySelector("#form-puesto");
let action = document.querySelector("#action");
let csrf = document.querySelector("#csrf");
let id = document.querySelector("#id");
let labelPuesto = document.querySelector("#label-puesto");
let puesto = document.querySelector("#puesto");
let puestoFeedback = document.querySelector("#puesto-feedback");
let tooltipPuesto = document.querySelector("#tooltip-puesto");
let btnSubmit = document.querySelector("#btn-submit");
let btnCancelar = document.querySelector("#btn-cancelar");
let dt = new JSTable("#tabla-puestos", {
    serverSide: true,
    deferLoading: regs.value,
    ajax: url.value  + '/api/administracion/puestos'
});

function setEdit(i,p)
{
    console.log(i);
    action.value = 'update';
    labelPuesto.innerHTML = 'Edite el puesto';
    puesto.value = p;
    id.value = i;
    btnSubmit.value = 'Actualizar';
    btnCancelar.style.display = 'block';
}

btnCancelar.addEventListener("click", () => {
    action.value = 'create';
    labelPuesto.innerHTML = 'Ingrese el puesto';
    puesto.value = '';
    id.value = '';
    btnSubmit.value = 'Guardar';
    btnCancelar.style.display = 'none';
})

puesto.addEventListener('keyup', () => {
    tooltipPuesto.innerHTML = "&nbsp;";
    tooltipPuesto.style.display = "none";

    if (puesto.value.trim().length > 0)
    {
        if (!soloLetrasEspaciosAcentos(puesto.value))
        {
            puestoFeedback.innerHTML = '&nbsp;';
            puestoFeedback.classList.remove('valid-feedback');
            puesto.classList.remove('is-valid');

            puestoFeedback.innerHTML = '¡Debes ingresar sólo letras!';
            puestoFeedback.classList.add('invalid-feedback');
            puesto.classList.add('is-invalid');
        }
        else
        {
            puestoFeedback.innerHTML = '&nbsp;';
            puestoFeedback.classList.remove('invalid-feedback');
            puesto.classList.remove('is-invalid');

            if (puesto.value.trim().length >= 7)
            {
                puestoFeedback.innerHTML = 'Parece correcto';
                puestoFeedback.classList.add('valid-feedback');
                puesto.classList.add('is-valid');
            }
        }
    }
})

btnSubmit.addEventListener('click', () => {
    // VALIDAR QUE EL CAMPO PUESTO TENGA LA LONGITUD MÍNIMA DE CARACTERES
    if (puesto.value.trim().length < 7)
    {
        tooltipPuesto.innerHTML = "Debes completar la información de este campo como se solicita.";
        tooltipPuesto.style.display = "block";
        puesto.focus();
        return;
    }

    // VALIDAR QUE EL CAMPO PUESTO SOLO CONTENGA LETRAS
    if (!soloLetrasEspaciosAcentos(puesto.value.trim()))
    {
        tooltipPuesto.innerHTML = "Debes completar la información de este campo como se solicita.";
        tooltipPuesto.style.display = "block";
        puesto.focus();
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
        let url = formPuesto.getAttribute("action");

        // Enviar datos vía AJAX
        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('action', action.value);
        formData.append('csrf', csrf.value);
        formData.append('id', id.value);
        formData.append('puesto', puesto.value);
        formData.append('token', token);

        xhttp.onreadystatechange = function() {
            if (xhttp.readyState === 4 && xhttp.status === 200)
            {
                // DEFINIR LA ACCIÓN A REALIZAR COMO  CREATE
                action.value = 'create';
                // MODIFICAL EL TEXTO DEL LABEL
                labelPuesto.innerHTML = 'Ingrese el puesto';
                // VACIAR EL CAMPO PUESTO
                puesto.value = '';
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
})

function mostrarConfirmacion(id, puesto)
{
    document.querySelector("#modal-confirm").style.display = 'flex';
    document.querySelector("#modal-confirm-message").innerHTML = '¿Seguro que desea eliminar el puesto: ' + puesto + '?';
    document.querySelector("#btn-confirmar").addEventListener('click', () => {
        // SE OCULTA EL MODAL DE CONFIRMACIÓN
        document.querySelector("#modal-confirm").style.display = 'none';
        // SE MUESTRA EL MODAL DE LOADING
        document.querySelector("#modal-loading").style.display = "flex";

        // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
        grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
            action: 'submit'
        }).then(function(token) {

            // Enviar datos vía AJAX
            let xhttp = new XMLHttpRequest();
            let formData = new FormData();
            formData.append('csrf', csrf.value);
            formData.append('id', id);
            formData.append('token', token);

            xhttp.onreadystatechange = function() {
                if (xhttp.readyState === 4 && xhttp.status === 200)
                {
                    console.log(xhttp.responseText);
                }
                else
                {
                    if (xhttp.status === 400)
                    {
                        console.log(xhttp.responseText)
                    }
                }
            }
            xhttp.open('post', url.value + '/administracion/puestos/delete', true);
            xhttp.send(formData);
        });
    })
}