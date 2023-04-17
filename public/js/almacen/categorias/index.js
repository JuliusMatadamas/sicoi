let id = 0;
let action = "create";
let categoriaValid = false;
const btnCancelar = document.querySelector("#btn-cancelar");
const btnSubmit = document.querySelector("#btn-submit");
const entorno = document.querySelector("#entorno");
const categoria = document.querySelector("#categoria");
const categoriaFeedback = document.querySelector("#categoria-feedback");
const categoriaTooltip = document.querySelector("#categoria-tooltip");
const csrf = document.querySelector("#csrf");
const idUsuario = document.querySelector("#id_usuario");
const jwtTooltip = document.querySelector("#tooltip-jwt");

const dt = new JSTable("#tabla-categorias", {
    serverSide: true,
    deferLoading: registros.value,
    ajax: entorno.value + '/api/categorias'
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


categoria.addEventListener("keyup", e => {
    categoria.classList.remove("is-valid", "is-invalid");
    categoriaFeedback.classList.remove("valid-feedback", "invalid-feedback");
    categoriaFeedback.innerHTML = `&nbsp;`;
    categoriaTooltip.style.display = 'none';
    categoriaTooltip.innerHTML = `&nbsp;`;

    if (categoria.value.trim().length === 0) {
        categoria.classList.remove("is-valid", "is-invalid");
        categoriaFeedback.classList.remove("valid-feedback", "invalid-feedback");
        categoriaFeedback.innerHTML = `&nbsp;`;
        categoriaValid = false;
        return;
    }

    if (categoria.value.trim().length < 7) {
        categoria.classList.add("is-invalid");
        categoriaFeedback.classList.add("invalid-feedback");
        categoriaFeedback.innerHTML = `Te faltan ${7 - categoria.value.trim().length} caracteres.`;
        categoriaValid = false;
        return;
    }

    if (categoria.value.trim().length > 45) {
        categoria.classList.add("is-invalid");
        categoriaFeedback.classList.add("invalid-feedback");
        categoriaFeedback.innerHTML = `Te sobran ${categoria.value.trim().length - 7} caracteres.`;
        categoriaValid = false;
        return;
    }

    categoria.classList.add("is-valid");
    categoriaFeedback.classList.add("valid-feedback");
    categoriaFeedback.innerHTML = `Parece correcto.`;
    categoriaValid = true;
});

btnSubmit.addEventListener("click", () => {
    if (!categoriaValid) {
        categoria.focus();
        categoriaTooltip.style.display = "block";
        categoriaTooltip.innerHTML = `Completa este campo como se solicita.`;
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
        formData.append('categoria', categoria.value.trim());


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
        xhttp.open('post', `${entorno.value}/almacen/categoria`, true);
        xhttp.send(formData);
    });
});

function setDelete(i,c) {
    id = i;
    action = "delete";
    document.querySelector("#modal-confirm_categoria").style.display = "flex";
    document.querySelector("#modal-confirm_categoria-message").innerHTML = `¿Seguro que deseas eliminar la categoría ${c}?`;
}

function cancelDeleteCategoria() {
    id = 0;
    action = "create";
}

function deleteCategoria() {
    document.querySelector("#modal-confirm_categoria").style.display = "none";
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


        xhttp.onreadystatechange = function () {
            if (xhttp.readyState) {
                if (xhttp.status === 200) {
                    document.querySelector("#modal-loading").style.display = "none";
                    let response = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-info").style.display = "flex";
                    document.querySelector("#modal-info-message").innerHTML = `${response.message}`;
                    document.querySelector("#modal-info button").addEventListener("click", () => {
                        window.location.reload();
                    });
                }
                if (xhttp.status === 400) {
                    document.querySelector("#modal-loading").style.display = "none";
                    let response = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-error").style.display = "flex";
                    document.querySelector("#modal-error-message").innerHTML = `${response.message}`;
                }
            }
        }
        xhttp.open('post', `${entorno.value}/almacen/categoria`, true);
        xhttp.send(formData);
    });
}

function setEdit(i,c) {
    id = i;
    action = "update";
    categoria.value = c;
    btnSubmit.value = `Actualizar`;
    document.querySelector("#btn-cancelar").style.display = "block";
}

btnCancelar.addEventListener("click", () => {
    id = 0;
    action = "create";
    categoria.value = '';
    categoria.classList.remove("is-valid", "is-invalid");
    categoriaFeedback.classList.remove("valid-feedback", "invalid-feedback");
    categoriaFeedback.innerHTML = `&nbsp;`;
    categoriaTooltip.style.display = 'none';
    categoriaTooltip.innerHTML = `&nbsp;`;
    btnSubmit.value = `Guardar`;
    document.querySelector("#btn-cancelar").style.display = "none";
});