let id = 0;
let action = "create";
let organizacion = document.querySelector("#organizacion");
let organizacionFeedback = document.querySelector("#organizacion-feedback");
let organizacionTooltip = document.querySelector("#organizacion-tooltip");
let organizacionValid = false;
let descripcion = document.querySelector("#descripcion");
let descripcionFeedback = document.querySelector("#descripcion-feedback");
let descripcionTooltip = document.querySelector("#descripcion-tooltip");
let descripcionValid = false;
let btnCancelar = document.querySelector("#btn-cancelar");
let btnSubmit = document.querySelector("#btn-submit");
const entorno = document.querySelector("#entorno");
const idUsuario = document.querySelector("#id_usuario");
const csrf = document.querySelector("#csrf");
const registros = document.querySelector("#registros");
const jwtTooltip = document.querySelector("#tooltip-jwt");


const dt = new JSTable("#tabla-organizaciones", {
    serverSide: true,
    deferLoading: registros.value,
    ajax: entorno.value + '/api/organizaciones'
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

organizacion.addEventListener("keyup", () => {
    organizacion.classList.remove("is-valid", "is-invalid");
    organizacionFeedback.classList.remove("valid-feedback", "invalid-feedback");
    organizacionFeedback.innerHTML = `&nbsp;`;
    organizacionTooltip.style.display = 'none';
    organizacionTooltip.innerHTML = `&nbsp;`;

    if (organizacion.value.trim().length === 0) {
        organizacion.classList.remove("is-valid", "is-invalid");
        organizacionFeedback.classList.remove("valid-feedback", "invalid-feedback");
        organizacionFeedback.innerHTML = `&nbsp;`;
        organizacionValid = false;
        return;
    }

    if (organizacion.value.trim().length < 3) {
        organizacion.classList.add("is-invalid");
        organizacionFeedback.classList.add("invalid-feedback");
        organizacionFeedback.innerHTML = `Te faltan ${5 - organizacion.value.trim().length} caracte(res)`;
        organizacionValid = false;
        return;
    }

    if (organizacion.value.trim().length > 10) {
        organizacion.classList.add("is-invalid");
        organizacionFeedback.classList.add("invalid-feedback");
        organizacionFeedback.innerHTML = `Te sobran ${organizacion.value.trim().length - 45} caracte(res)`;
        organizacionValid = false;
        return;
    }

    organizacion.classList.add("is-valid");
    organizacionFeedback.classList.add("valid-feedback");
    organizacionFeedback.innerHTML = `Parece correcto`;
    organizacionValid = true;
});

descripcion.addEventListener("keyup", () => {
    descripcion.classList.remove("is-valid", "is-invalid");
    descripcionFeedback.classList.remove("valid-feedback", "invalid-feedback");
    descripcionFeedback.innerHTML = `&nbsp;`;
    descripcionTooltip.style.display = 'none';
    descripcionTooltip.innerHTML = `&nbsp;`;

    if (descripcion.value.trim().length === 0) {
        descripcion.classList.remove("is-valid", "is-invalid");
        descripcionFeedback.classList.remove("valid-feedback", "invalid-feedback");
        descripcionFeedback.innerHTML = `&nbsp;`;
        descripcionValid = false;
        return;
    }

    if (descripcion.value.trim().length < 5) {
        descripcion.classList.add("is-invalid");
        descripcionFeedback.classList.add("invalid-feedback");
        descripcionFeedback.innerHTML = `Te faltan ${5 - descripcion.value.trim().length} caracte(res)`;
        descripcionValid = false;
        return;
    }

    if (descripcion.value.trim().length > 255) {
        descripcion.classList.add("is-invalid");
        descripcionFeedback.classList.add("invalid-feedback");
        descripcionFeedback.innerHTML = `Te sobran ${descripcion.value.trim().length - 45} caracte(res)`;
        descripcionValid = false;
        return;
    }

    descripcion.classList.add("is-valid");
    descripcionFeedback.classList.add("valid-feedback");
    descripcionFeedback.innerHTML = `Parece correcto`;
    descripcionValid = true;
});

btnSubmit.addEventListener("click", () => {
    if (!organizacionValid) {
        organizacionTooltip.style.display = "block";
        organizacionTooltip.innerHTML = `Completa este campo como se solicita.`;
        organizacion.focus();
        return;
    }

    if (!descripcionValid) {
        descripcionTooltip.style.display = "block";
        descripcionTooltip.innerHTML = `Completa este campo como se solicita.`;
        descripcion.focus();
        return;
    }

    btnCancelar.disabled = true;
    btnSubmit.disabled = true;
    document.querySelector("#modal-loading").style.display = "flex";

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function (token) {

        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('id_usuario', idUsuario.value);
        formData.append('token', token);
        formData.append('csrf', csrf.value);
        formData.append('action', action);
        formData.append('id', id);
        formData.append('organizacion', organizacion.value.trim());
        formData.append('descripcion', descripcion.value.trim());

        xhttp.onreadystatechange = function () {
            if (xhttp.readyState) {
                if (xhttp.status === 200) {
                    document.querySelector("#modal-loading").style.display = "none";
                    btnCancelar.disabled = false;
                    btnSubmit.disabled = false;
                    let response = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-info").style.display = "flex";
                    document.querySelector("#modal-info-message").innerHTML = `${response.message}`;
                    document.querySelector("#modal-info button").addEventListener("click", () => {
                        window.location.reload();
                    });
                }
                if (xhttp.status === 400) {
                    document.querySelector("#modal-loading").style.display = "none";
                    btnCancelar.disabled = false;
                    btnSubmit.disabled = false;
                    let response = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-error").style.display = "flex";
                    document.querySelector("#modal-error-message").innerHTML = `${response.message}`;
                }
            }
        }
        xhttp.open('post', `${entorno.value}/almacen/organizaciones`, true);
        xhttp.send(formData);
    });
});

btnCancelar.addEventListener("click", () => {
    cancelDeleteOrganizacion();
});

function cancelDeleteOrganizacion() {
    id = 0;
    action = "create";
    organizacion.value = "";
    organizacionValid = false;
    descripcion.value = "";
    descripcionValid = false;
    btnCancelar.style.display = 'none';
    btnSubmit.value = "Guardar";
}

function setEdit(io, o, d) {
    id = io;
    action = "update";
    organizacion.value = o;
    organizacionValid = true;
    descripcion.value = d;
    descripcionValid = true;
    btnCancelar.style.display = 'block';
    btnSubmit.value = "Actualizar";
}

function setDelete(io, o, d) {
    document.querySelector("#modal-confirm_organizacion").style.display = "flex";
    document.querySelector("#modal-confirm_organizacion-message").innerHTML = `¿Seguro que deseas eliminar la organización ${o}(${d})?`;
    document.querySelector("#modal-confirm_organizacion-btn").addEventListener("click", () => {
        document.querySelector("#modal-confirm_organizacion").style.display = "none";
        document.querySelector("#modal-loading").style.display = "flex";

        // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
        grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
            action: 'submit'
        }).then(function (token) {

            let xhttp = new XMLHttpRequest();
            let formData = new FormData();
            formData.append('id_usuario', idUsuario.value);
            formData.append('token', token);
            formData.append('csrf', csrf.value);
            formData.append('action', "delete");
            formData.append('id', io);
            formData.append('organizacion', o);
            formData.append('descripcion', d);

            xhttp.onreadystatechange = function () {
                if (xhttp.readyState) {
                    if (xhttp.status === 200) {
                        document.querySelector("#modal-loading").style.display = "none";
                        btnCancelar.disabled = false;
                        btnSubmit.disabled = false;
                        let response = JSON.parse(xhttp.responseText);
                        document.querySelector("#modal-info").style.display = "flex";
                        document.querySelector("#modal-info-message").innerHTML = `${response.message}`;
                        document.querySelector("#modal-info button").addEventListener("click", () => {
                            window.location.reload();
                        });
                    }
                    if (xhttp.status === 400) {
                        document.querySelector("#modal-loading").style.display = "none";
                        btnCancelar.disabled = false;
                        btnSubmit.disabled = false;
                        let response = JSON.parse(xhttp.responseText);
                        document.querySelector("#modal-error").style.display = "flex";
                        document.querySelector("#modal-error-message").innerHTML = `${response.message}`;
                    }
                }
            }
            xhttp.open('post', `${entorno.value}/almacen/organizaciones`, true);
            xhttp.send(formData);
        });
    });
}

