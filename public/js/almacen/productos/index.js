let id = 0;
let action = "create";
let idCategoria = document.querySelector("#id_categoria");
let idCategoriaFeedback = document.querySelector("#id_categoria-feedback");
let idCategoriaTooltip = document.querySelector("#id_categoria-tooltip");
let idCategoriaValid = false;
let producto = document.querySelector("#producto");
let productoFeedback = document.querySelector("#producto-feedback");
let productoTooltip = document.querySelector("#producto-tooltip");
let productoValid = false;
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


const dt = new JSTable("#tabla-productos", {
    serverSide: true,
    deferLoading: registros.value,
    ajax: entorno.value + '/api/productos'
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

idCategoria.addEventListener("change", () => {
    idCategoria.classList.remove("is-valid", "is-invalid");
    idCategoriaFeedback.classList.remove("valid-feedback", "invalid-feedback");
    idCategoriaFeedback.innerHTML = `&nbsp;`;
    idCategoriaTooltip.style.display = 'none';
    idCategoriaTooltip.innerHTML = `&nbsp;`;

    if (parseInt(idCategoria.value) === 0) {
        idCategoria.classList.remove("is-valid", "is-invalid");
        idCategoriaFeedback.classList.remove("valid-feedback", "invalid-feedback");
        idCategoriaFeedback.innerHTML = `&nbsp;`;
        idCategoriaValid = false;
        return;
    }

    idCategoria.classList.add("is-valid");
    idCategoriaFeedback.classList.add("valid-feedback");
    idCategoriaFeedback.innerHTML = `Parece correcto.`;
    idCategoriaValid = true;
});

producto.addEventListener("keyup", () => {
    producto.classList.remove("is-valid", "is-invalid");
    productoFeedback.classList.remove("valid-feedback", "invalid-feedback");
    productoFeedback.innerHTML = `&nbsp;`;
    productoTooltip.style.display = 'none';
    productoTooltip.innerHTML = `&nbsp;`;

    if (producto.value.trim().length === 0) {
        producto.classList.remove("is-valid", "is-invalid");
        productoFeedback.classList.remove("valid-feedback", "invalid-feedback");
        productoFeedback.innerHTML = `&nbsp;`;
        productoValid = false;
        return;
    }

    if (producto.value.trim().length < 3) {
        producto.classList.add("is-invalid");
        productoFeedback.classList.add("invalid-feedback");
        productoFeedback.innerHTML = `Te faltan ${3 - producto.value.trim().length} caracte(res)`;
        productoValid = false;
        return;
    }

    if (producto.value.trim().length > 45) {
        producto.classList.add("is-invalid");
        productoFeedback.classList.add("invalid-feedback");
        productoFeedback.innerHTML = `Te sobran ${producto.value.trim().length - 45} caracte(res)`;
        productoValid = false;
        return;
    }

    producto.classList.add("is-valid");
    productoFeedback.classList.add("valid-feedback");
    productoFeedback.innerHTML = `Parece correcto`;
    productoValid = true;
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
    if (!idCategoriaValid) {
        idCategoriaTooltip.style.display = "block";
        idCategoriaTooltip.innerHTML = `Selecciona una opción de este campo.`;
        idCategoria.focus();
        return;
    }

    if (!productoValid) {
        productoTooltip.style.display = "block";
        productoTooltip.innerHTML = `Completa este campo como se solicita.`;
        producto.focus();
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
        formData.append('nombre', producto.value.trim().replace(',', ' '));
        formData.append('descripcion', descripcion.value.trim());
        formData.append('id_categoria', idCategoria.value);

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
        xhttp.open('post', `${entorno.value}/almacen/productos`, true);
        xhttp.send(formData);
    });
});

btnCancelar.addEventListener("click", () => {
    cancelDeleteProducto();
});

function cancelDeleteProducto() {
    id = 0;
    action = "create";
    idCategoria.value = 0;
    idCategoriaValid = false;
    producto.value = "";
    productoValid = false;
    descripcion.value = "";
    descripcionValid = false;
    btnCancelar.style.display = 'none';
    btnSubmit.value = "Guardar";
}

function setEdit(ip, ic, n, d) {
    id = ip;
    action = "update";
    idCategoria.value = ic;
    idCategoriaValid = true;
    producto.value = n;
    productoValid = true;
    descripcion.value = d;
    descripcionValid = true;
    btnCancelar.style.display = 'block';
    btnSubmit.value = "Actualizar";
}

function setDelete(ip, ic, n, d) {
    document.querySelector("#modal-confirm_producto").style.display = "flex";
    document.querySelector("#modal-confirm_producto-message").innerHTML = `¿Seguro que deseas eliminar el producto ${n}(${d})?`;
    document.querySelector("#modal-confirm_producto-btn").addEventListener("click", () => {
        document.querySelector("#modal-confirm_producto").style.display = "none";
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
            formData.append('id', ip);
            formData.append('nombre', n);
            formData.append('descripcion', d);
            formData.append('id_categoria', ic);

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
            xhttp.open('post', `${entorno.value}/almacen/productos`, true);
            xhttp.send(formData);
        });
    });
}

