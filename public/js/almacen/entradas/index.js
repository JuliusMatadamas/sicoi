const entorno = document.querySelector("#entorno");
const csrf = document.querySelector("#csrf");
const idUsuario = document.querySelector("#id_usuario");
const coe = document.querySelector("#container-organizaciones_externas");
const coeH = coe.children[0];
const coeB = coe.children[1];
const coi = document.querySelector("#container-organizaciones_internas");
const coiH = coi.children[0];
const coiB = coi.children[1];
let foeIdInventarioOrigen = document.querySelector("#foe-id_inventario_origen");
let foeIdInventarioOrigenFeedback = document.querySelector("#foe-id_inventario_origen-feedback");
let foeIdInventarioOrigenTooltip = document.querySelector("#foe-id_inventario_origen-tooltip");
let foeIdInventarioOrigenValid = false;
let foeFechaOrigen = document.querySelector("#foe-fecha_origen");
let foeFechaOrigenFeedback = document.querySelector("#foe-fecha_origen-feedback");
let foeFechaOrigenTooltip = document.querySelector("#foe-fecha_origen-tooltip");
let foeFechaOrigenValid = false;
let foeIdEstadoDeInventarioDestino = document.querySelector("#foe-id_estado_de_inventario_destino");
let foeIdEstadoDeInventarioDestinoFeedback = document.querySelector("#foe-id_estado_de_inventario_destino-feedback");
let foeIdEstadoDeInventarioDestinoTooltip = document.querySelector("#foe-id_estado_de_inventario_destino-tooltip");
let foeIdEstadoDeInventarioDestinoValid = false;
let foeFechaDestino = document.querySelector("#foe-fecha_destino");
let foeFechaDestinoFeedback = document.querySelector("#foe-fecha_destino-feedback");
let foeFechaDestinoTooltip = document.querySelector("#foe-fecha_destino-tooltip");
let foeFechaDestinoValid = false;
let foeIdCategoria = document.querySelector("#foe-id_categoria");
let foeIdCategoriaFeedback = document.querySelector("#foe-id_categoria-feedback");
let foeIdCategoriaTooltip = document.querySelector("#foe-id_categoria-tooltip");
let foeIdProducto = document.querySelector("#foe-id_producto");
let foeIdProductoFeedback = document.querySelector("#foe-id_producto-feedback");
let foeIdProductoTooltip = document.querySelector("#foe-id_producto-tooltip");
let foeNumeroDeSerie = document.querySelector("#foe-numero_de_serie");
let foeNumeroDeSerieFeedback = document.querySelector("#foe-numero_de_serie-feedback");
let foeNumeroDeSerieTooltip = document.querySelector("#foe-numero_de_serie-tooltip");
let foeCantidad = document.querySelector("#foe-cantidad");
let foeCantidadFeedback = document.querySelector("#foe-cantidad-feedback");
let foeCantidadTooltip = document.querySelector("#foe-cantidad-tooltip");
let foeCantidadValid = false;
let foeBtnAdd = document.querySelector("#foe-btn_add");
let foeBtnSubmit = document.querySelector("#foe-btn_submit");
let foeItems = {};
let foeItemsAux = {};
let foeTablaProductosBody = document.querySelector("#foe-tabla_productos tbody");
let foeListTooltip = document.querySelector("#foe_list-tooltip");

coeH.addEventListener("click", () => {
    if (coeB.clientHeight === 0) {
        coiB.style.padding = "0 0 0 0";
        coiB.style.height = "0";
        coeB.style.height = "auto";
        coeB.style.padding = "25px 25px 50px 25px";
    } else {
        coeB.style.padding = "0 0 0 0";
        coeB.style.height = "0";
    }
});

coiH.addEventListener("click", () => {
    if (coiB.clientHeight === 0) {
        coeB.style.padding = "0 0 0 0";
        coeB.style.height = "0";
        coiB.style.height = "auto";
        coiB.style.padding = "25px 25px 50px 25px";
    } else {
        coiB.style.padding = "0 0 0 0";
        coiB.style.height = "0";
    }
});

foeIdInventarioOrigen.addEventListener("change", () => {
    foeIdInventarioOrigen.classList.remove("is-valid", "is-invalid");
    foeIdInventarioOrigenFeedback.classList.remove("valid-feedback", "invalid-feedback");
    foeIdInventarioOrigenFeedback.innerHTML = "&nbsp;";
    foeIdInventarioOrigenTooltip.style.display = "none";
    foeIdInventarioOrigenTooltip.innerHTML = "&nbsp;";

    if (parseInt(foeIdInventarioOrigen.value) === 0) {
        foeIdInventarioOrigen.classList.remove("is-valid", "is-invalid");
        foeIdInventarioOrigenFeedback.classList.remove("valid-feedback", "invalid-feedback");
        foeIdInventarioOrigenFeedback.innerHTML = "&nbsp;";
        foeIdInventarioOrigenValid = false;
        return;
    }

    foeIdInventarioOrigen.classList.add("is-valid");
    foeIdInventarioOrigenFeedback.classList.add("valid-feedback");
    foeIdInventarioOrigenFeedback.innerHTML = "Parece correcto";
    foeIdInventarioOrigenValid = true;
});

["change", "click", "focus", "keyup"].forEach(e => {
    foeFechaOrigen.addEventListener(e, () => {
        foeFechaOrigen.classList.remove("is-valid", "is-invalid");
        foeFechaOrigenFeedback.classList.remove("valid-feedback", "invalid-feedback");
        foeFechaOrigenFeedback.innerHTML = "&nbsp;";
        foeFechaOrigenTooltip.style.display = "none";
        foeFechaOrigenTooltip.innerHTML = "&nbsp;";

        if (foeFechaOrigen.value.trim().length === 0) {
            foeFechaOrigen.classList.remove("is-valid", "is-invalid");
            foeFechaOrigenFeedback.classList.remove("valid-feedback", "invalid-feedback");
            foeFechaOrigenFeedback.innerHTML = "&nbsp;";
            foeFechaOrigenTooltip.style.display = "none";
            foeFechaOrigenTooltip.innerHTML = "&nbsp;";
            foeFechaOrigenValid = false;
            return
        }

        if (!fechaAnterior(foeFechaOrigen.value.trim())) {
            foeFechaOrigen.classList.add("is-invalid");
            foeFechaOrigenFeedback.classList.add("invalid-feedback");
            foeFechaOrigenFeedback.innerHTML = "¡La fecha no puede ser igual o posterior a la fecha actual!";
            foeFechaOrigenValid = false;
            return
        }

        foeFechaOrigen.classList.add("is-valid");
        foeFechaOrigenFeedback.classList.add("valid-feedback");
        foeFechaOrigenFeedback.innerHTML = "Parece correcto";
        foeFechaOrigenValid = true;
    });
});

foeIdEstadoDeInventarioDestino.addEventListener("change", () => {
    foeIdEstadoDeInventarioDestino.classList.remove("is-valid", "is-invalid");
    foeIdEstadoDeInventarioDestinoFeedback.classList.remove("valid-feedback", "invalid-feedback");
    foeIdEstadoDeInventarioDestinoFeedback.innerHTML = "&nbsp;";
    foeIdEstadoDeInventarioDestinoTooltip.style.display = "none";
    foeIdEstadoDeInventarioDestinoTooltip.innerHTML = "&nbsp;";

    if (parseInt(foeIdEstadoDeInventarioDestino.value.trim()) === 0) {
        foeIdEstadoDeInventarioDestino.classList.remove("is-valid", "is-invalid");
        foeIdEstadoDeInventarioDestinoFeedback.classList.remove("valid-feedback", "invalid-feedback");
        foeIdEstadoDeInventarioDestinoFeedback.innerHTML = "&nbsp;";
        foeIdEstadoDeInventarioDestinoValid = false;
        return;
    }

    foeIdEstadoDeInventarioDestino.classList.add("is-valid");
    foeIdEstadoDeInventarioDestinoFeedback.classList.add("valid-feedback");
    foeIdEstadoDeInventarioDestinoFeedback.innerHTML = "Parece correcto";
    foeIdEstadoDeInventarioDestinoValid = true;
});

["change", "click", "focus", "keyup"].forEach(e => {
    foeFechaDestino.addEventListener(e, () => {
        foeFechaDestino.classList.remove("is-valid", "is-invalid");
        foeFechaDestinoFeedback.classList.remove("valid-feedback", "invalid-feedback");
        foeFechaDestinoFeedback.innerHTML = "&nbsp;";
        foeFechaDestinoTooltip.style.display = "none";
        foeFechaDestinoTooltip.innerHTML = "&nbsp;";

        if (foeFechaDestino.value.trim().length === 0) {
            foeFechaDestino.classList.remove("is-valid", "is-invalid");
            foeFechaDestinoFeedback.classList.remove("valid-feedback", "invalid-feedback");
            foeFechaDestinoFeedback.innerHTML = "&nbsp;";
            foeFechaDestinoTooltip.style.display = "none";
            foeFechaDestinoTooltip.innerHTML = "&nbsp;";
            foeFechaDestinoValid = false;
            return
        }

        if (fechaIgualAnterior(foeFechaDestino.value.trim())) {
            foeFechaDestino.classList.add("is-invalid");
            foeFechaDestinoFeedback.classList.add("invalid-feedback");
            foeFechaDestinoFeedback.innerHTML = "La fecha de recepción no puede ser igual o anterior a la fecha de envío.";
            foeFechaDestinoValid = false;
            return
        }

        if (fechaPosterior(foeFechaDestino.value.trim())) {
            foeFechaDestino.classList.add("is-invalid");
            foeFechaDestinoFeedback.classList.add("invalid-feedback");
            foeFechaDestinoFeedback.innerHTML = "La fecha de recepción no puede ser posterior a la fecha actual.";
            foeFechaDestinoValid = false;
            return
        }

        foeFechaDestino.classList.add("is-valid");
        foeFechaDestinoFeedback.classList.add("valid-feedback");
        foeFechaDestinoFeedback.innerHTML = "Parece correcta.";
        foeFechaDestinoValid = true;
    });
});

foeIdCategoria.addEventListener("change", () => {
    foeIdCategoria.classList.remove("is-valid", "is-invalid");
    foeIdCategoriaFeedback.classList.remove("valid-feedback", "invalid-feedback");
    foeIdCategoriaFeedback.innerHTML = "&nbsp;";
    foeIdCategoriaTooltip.style.display = "none";
    foeIdCategoriaTooltip.innerHTML = "&nbsp;";

    foeNumeroDeSerie.disabled = true;
    foeNumeroDeSerie.value = 'Espere...';
    foeNumeroDeSerie.classList.remove("is-valid", "is-invalid");
    foeNumeroDeSerieFeedback.classList.remove("valid-feedback", "invalid-feedback");
    foeNumeroDeSerieTooltip.style.display = "none";
    foeNumeroDeSerieTooltip.innerHTML = "&nbsp;";

    foeCantidad.disabled = true;
    foeCantidad.value = 'Espere...';


    if (parseInt(foeIdCategoria.value) === 0) {
        foeBtnAdd.disabled = true;

        foeIdCategoria.classList.remove("is-valid", "is-invalid");
        foeIdCategoriaFeedback.classList.remove("valid-feedback", "invalid-feedback");
        foeIdCategoriaFeedback.innerHTML = "&nbsp;";

        foeIdProducto.innerHTML = '';
        foeIdProducto.disabled = true;

        foeNumeroDeSerie.value = '';
        foeNumeroDeSerie.disabled = true;
        foeNumeroDeSerie.placeholder = "";

        foeCantidad.value = '';
        foeCantidad.disabled = true;
    } else {
        foeIdCategoria.classList.add("is-valid");
        foeIdCategoriaFeedback.classList.add("valid-feedback");
        foeIdCategoriaFeedback.innerHTML = "Parece correcto";

        // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
        grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
            action: 'submit'
        }).then(function (token) {

            let xhttp = new XMLHttpRequest();
            let formData = new FormData();
            formData.append('id_categoria', foeIdCategoria.value);
            formData.append('csrf', csrf.value);
            formData.append('id_usuario', idUsuario.value);
            formData.append('token', token);


            xhttp.onreadystatechange = function () {
                if (xhttp.readyState) {
                    if (xhttp.status === 200) {
                        foeBtnAdd.disabled = false;

                        foeIdCategoria.classList.remove("is-valid", "is-invalid");
                        foeIdCategoriaFeedback.classList.remove("valid-feedback", "invalid-feedback");
                        foeIdCategoriaFeedback.innerHTML = "&nbsp;";

                        foeIdProducto.disabled = false;

                        if (parseInt(foeIdCategoria.value) === 3) {
                            foeNumeroDeSerie.value = '';
                            foeNumeroDeSerie.placeholder = "Ingrese entre 5 y 20 caracteres.";
                            foeNumeroDeSerie.disabled = false;
                            foeCantidad.value = '';
                            foeCantidad.disabled = true;
                        } else {
                            foeCantidad.value = '';
                            foeCantidad.disabled = false;
                            foeNumeroDeSerie.value = '';
                            foeNumeroDeSerie.placeholder = "";
                            foeNumeroDeSerie.disabled = true;
                        }

                        let options = '';
                        let response = JSON.parse(xhttp.responseText);
                        response.data.forEach(item => {
                            options += `<option value="${item.id}">${item.nombre}</option>`;
                        });
                        foeIdProducto.innerHTML = options;
                    }

                    if (xhttp.status === 400) {
                        foeBtnAdd.disabled = true;

                        foeIdCategoria.classList.remove("is-valid", "is-invalid");
                        foeIdCategoriaFeedback.classList.remove("valid-feedback", "invalid-feedback");
                        foeIdCategoriaFeedback.innerHTML = "&nbsp;";

                        foeIdProducto.value = '';
                        foeIdProducto.disabled = true;
                        foeNumeroDeSerie.value = '';
                        foeNumeroDeSerie.disabled = true;
                        foeNumeroDeSerie.placeholder = "";
                        foeCantidad.value = '';
                        foeCantidad.disabled = true;
                        console.log(xhttp.responseText);
                    }
                }
            }
            xhttp.open('post', entorno.value + '/api/productos_por_categoria', true);
            xhttp.send(formData);
        });
    }
});

foeNumeroDeSerie.addEventListener("click", () => {
    foeNumeroDeSerie.classList.remove("is-valid", "is-invalid");
    foeNumeroDeSerieFeedback.classList.remove("valid-feedback", "invalid-feedback");
    foeNumeroDeSerieFeedback.innerHTML = "&nbsp;";
    foeNumeroDeSerieTooltip.style.display = "none";
    foeNumeroDeSerieTooltip.innerHTML = "&nbsp;";

    if (foeNumeroDeSerie.value.trim().length === 0) {
        foeNumeroDeSerie.classList.remove("is-valid", "is-invalid");
        foeNumeroDeSerieFeedback.classList.remove("valid-feedback", "invalid-feedback");
        foeNumeroDeSerieFeedback.innerHTML = "&nbsp;";
        return;
    }

    if (foeNumeroDeSerie.value.trim().length < 5) {
        foeNumeroDeSerie.classList.add("is-invalid");
        foeNumeroDeSerieFeedback.classList.add("invalid-feedback");
        foeNumeroDeSerieFeedback.innerHTML = `Te faltan ${5 - foeNumeroDeSerie.value.trim().length} caracteres.`;
        return;
    }

    if (foeNumeroDeSerie.value.trim().length > 20) {
        foeNumeroDeSerie.classList.add("is-invalid");
        foeNumeroDeSerieFeedback.classList.add("invalid-feedback");
        foeNumeroDeSerieFeedback.innerHTML = `Te sobran ${foeNumeroDeSerie.value.trim().length - 20} caracteres.`;
        return;
    }

    foeNumeroDeSerie.classList.add("is-valid");
    foeNumeroDeSerieFeedback.classList.add("valid-feedback");
    foeNumeroDeSerieFeedback.innerHTML = `Parece correcto.`;
});

foeCantidad.addEventListener("keydown", e => {
    if (e.keyCode < 48 || e.keyCode > 57) {
        if (e.keyCode < 96 || e.keyCode > 105) {
            if (e.keyCode !== 8) {
                e.preventDefault();
                return false;
            }
        }
    }
});

foeCantidad.addEventListener("keyup", e => {
    foeCantidad.classList.remove("is-valid", "is-invalid");
    foeCantidadFeedback.classList.remove("valid-feedback", "invalid-feedback");
    foeCantidadFeedback.innerHTML = "";
    foeCantidadTooltip.style.display = "none";
    foeCantidadTooltip.innerHTML = "&nbsp;";

    if (foeCantidad.value.trim().length === 0) {
        foeCantidad.classList.remove("is-valid", "is-invalid");
        foeCantidadFeedback.classList.remove("valid-feedback", "invalid-feedback");
        foeCantidadFeedback.innerHTML = "";
        foeCantidadValid = false;
        return;
    }

    if (!soloNumeros(foeCantidad.value.trim())) {
        foeCantidad.classList.add("is-invalid");
        foeCantidadFeedback.classList.add("invalid-feedback");
        foeCantidadFeedback.innerHTML = `¡Ingresa solo números!`;
        foeCantidadValid = false;
        return;
    }

    foeCantidad.classList.add("is-valid");
    foeCantidadFeedback.classList.add("valid-feedback");
    foeCantidadFeedback.innerHTML = `Parece correcto`;
    foeCantidadValid = true;
});

foeBtnAdd.addEventListener("click", () => {
    if (parseInt(foeIdCategoria.value) === 0) {
        foeIdCategoria.focus();
        foeIdCategoria.classList.add("is-invalid");
        foeIdCategoriaFeedback.classList.add("invalid-feedback");
        foeIdCategoriaFeedback.innerHTML = "¡Debes seleccionar primero la categoría del producto!";
        return;
    }

    if (parseInt(foeIdCategoria.value) === 3) {
        if (foeNumeroDeSerie.value.trim().length < 5 || foeNumeroDeSerie.value.trim().length > 20) {
            foeNumeroDeSerie.focus();
            foeNumeroDeSerie.classList.add("is-invalid");
            foeNumeroDeSerieFeedback.classList.add("invalid-feedback");
            foeNumeroDeSerieFeedback.innerHTML = "¡El número de serie debe tener entre 5 y 20 caracteres!";
            return;
        }

        Object.keys(foeItems).length === 0 ? idx = 0 : idx = Object.keys(foeItems).length;

        if (Object.keys(foeItems).length !== 0) {
            for (let i = 0; i < Object.keys(foeItems).length; i++) {
                if (foeItems[i].id_producto === parseInt(foeIdProducto.value)) {
                    if (foeItems[i].numero_de_serie === foeNumeroDeSerie.value.trim()) {
                        foeNumeroDeSerieTooltip.style.display = 'block';
                        foeNumeroDeSerieTooltip.innerHTML = 'Esta serie ya se encuentra en la lista.';
                        return;
                    }
                }
            }
        }

        let item = {};

        item.id_producto = parseInt(foeIdProducto.value);
        item.producto = foeIdProducto.options[foeIdProducto.selectedIndex].text;
        item.numero_de_serie = foeNumeroDeSerie.value.trim();
        item.cantidad = 1;

        foeItems[idx] = item;

        foeNumeroDeSerie.classList.remove("is-valid", "is-invalid");
        foeNumeroDeSerieFeedback.classList.remove("valid-feedback", "invalid-feedback");
        foeNumeroDeSerieFeedback.innerHTML = "&nbsp;";
        foeNumeroDeSerie.value = '';
        foeNumeroDeSerie.focus();
    } else {
        if (!foeCantidadValid) {
            foeCantidad.focus();
            foeCantidadTooltip.style.display = 'block';
            foeCantidadTooltip.innerHTML = 'Debes ingresar solo números en este campo.';
            return;
        }

        Object.keys(foeItems).length === 0 ? idx = 0 : idx = Object.keys(foeItems).length;

        let item = {};

        item.id_producto = parseInt(foeIdProducto.value);
        item.producto = foeIdProducto.options[foeIdProducto.selectedIndex].text;
        item.numero_de_serie = "-";
        item.cantidad = foeCantidad.value;

        foeItems[idx] = item;

        foeCantidad.classList.remove("is-valid", "is-invalid");
        foeCantidadFeedback.classList.remove("valid-feedback", "invalid-feedback");
        foeCantidadFeedback.innerHTML = "&nbsp;";
        foeCantidad.value = '';
        foeCantidad.focus();
    }

    addItemsToTableFoe();
});

function addItemsToTableFoe() {
    foeListTooltip.style.display = 'none';
    foeListTooltip.innerHTML = "&nbsp;";
    foeTablaProductosBody.innerHTML = "";

    for (let i = 0; i < Object.keys(foeItems).length; i++) {
        let tr = document.createElement("tr");

        let td01 = document.createElement("td");
        let input01 = document.createElement("input");
        input01.classList.add("input-table");
        input01.value = foeItems[i].producto;
        td01.appendChild(input01);
        tr.appendChild(td01);

        let td02 = document.createElement("td");
        let input02 = document.createElement("input");
        input02.classList.add("input-table");
        input02.value = foeItems[i].numero_de_serie;
        td02.appendChild(input02);
        tr.appendChild(td02);

        let td03 = document.createElement("td");
        let input03 = document.createElement("input");
        input03.classList.add("input-table");
        input03.value = foeItems[i].cantidad;
        td03.appendChild(input03);
        tr.appendChild(td03);

        let td04 = document.createElement("td");
        let div04 = document.createElement("div");
        div04.setAttribute("id", "foe-item_" + i);
        div04.setAttribute("onclick", `removeFoeItem(${i})`);
        div04.classList.add("action-table");
        let i04 = document.createElement("i");
        i04.classList.add("fa-regular");
        i04.classList.add("fa-trash-can");
        div04.appendChild(i04);
        let span04 = document.createElement("span");
        span04.innerHTML = " Remover";
        div04.appendChild(span04);
        td04.appendChild(div04);
        tr.appendChild(td04);

        foeTablaProductosBody.appendChild(tr);
    }
}

function removeFoeItem(idX) {
    for (let i = 0; i < Object.keys(foeItems).length; i++) {
        if (i !== idX) {
            Object.keys(foeItemsAux).length === 0 ? idY = 0 : idY = Object.keys(foeItemsAux).length;
            let item = {};
            item = foeItems[i];
            foeItemsAux[idY] = item;
        }
    }
    foeItems = foeItemsAux;
    addItemsToTableFoe();
}

function fechaAnterior(fecha) {
    let fechaActual = new Date();
    let fechaAyer = new Date(fechaActual.getFullYear(), fechaActual.getMonth(), fechaActual.getDate() - 1);
    let fechaComparar = new Date(fecha);

    return fechaComparar < fechaAyer;
}

function fechaIgualAnterior(fecha) {
    let fechaOrigen = new Date(foeFechaOrigen.value.trim());
    let fechaComparar = new Date(fecha);
    return fechaComparar <= fechaOrigen;
}

function fechaPosterior(fecha) {
    let fechaActual = new Date();
    let fechaComparar = new Date(fecha);
    return fechaComparar > fechaActual;
}


foeBtnSubmit.addEventListener("click", e => {
    e.preventDefault();

    if (!foeIdInventarioOrigenValid) {
        foeIdInventarioOrigen.focus();
        foeIdInventarioOrigenTooltip.style.display = "block";
        foeIdInventarioOrigenTooltip.innerHTML = "Se debe seleccionar la organización de origen.";
        return;
    }

    if (!foeFechaOrigenValid) {
        foeFechaOrigen.focus();
        foeFechaOrigenTooltip.style.display = "block";
        foeFechaOrigenTooltip.innerHTML = "La fecha de envío del origen debe cumplir con lo requerido.";
        return;
    }

    if (!foeIdEstadoDeInventarioDestinoValid) {
        foeIdEstadoDeInventarioDestino.focus();
        foeIdEstadoDeInventarioDestinoTooltip.style.display = "block";
        foeIdEstadoDeInventarioDestinoTooltip.innerHTML = "Se debe seleccionar el estado destino del inventario a recibir.";
        return;
    }

    if (!foeFechaDestinoValid) {
        foeFechaDestino.focus();
        foeFechaDestinoTooltip.style.display = "block";
        foeFechaDestinoTooltip.innerHTML = "La fecha de recepción debe cumplir con lo requerido.";
        return;
    }

    if (Object.keys(foeItems).length === 0) {
        foeTablaProductosBody.focus();
        foeListTooltip.style.display = "block";
        foeListTooltip.innerHTML = "¡No se han agregado items a la lista!";
        return;
    }

    document.querySelector("#modal-loading").style.display = "flex";

    for (let i = 0; i < Object.keys(foeItems).length; i++) {
        foeItems[i].id_usuario = parseInt(idUsuario.value);
        foeItems[i].id_inventario_origen = foeIdInventarioOrigen.value;
        foeItems[i].id_estado_de_inventario_origen = parseInt(document.querySelector("#foe-id_estado_de_inventario_origen").value);
        foeItems[i].fecha_origen = foeFechaOrigen.value.trim();
        foeItems[i].id_inventario_destino = document.querySelector("#foe-id_inventario_destino").value;
        foeItems[i].id_estado_de_inventario_destino = parseInt(foeIdEstadoDeInventarioDestino.value);
        foeItems[i].fecha_destino = foeFechaDestino.value.trim();
    }

    let itemsToSend = "";
    let items = Object.entries(foeItems);
    items.map(([key, value]) => {
        let item = Object.entries(value);
        item.map(([k, v]) => {
            itemsToSend += `${k}:${v},`
        });
        itemsToSend = itemsToSend.slice(0, -1);
        itemsToSend += `|`;
    });
    itemsToSend = itemsToSend.slice(0, -1);

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function (token) {

        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('items', itemsToSend);
        formData.append('csrf', csrf.value);
        formData.append('id_usuario', idUsuario.value);
        formData.append('token', token);


        xhttp.onreadystatechange = function () {
            if (xhttp.readyState) {
                if (xhttp.status === 200) {
                    document.querySelector("#modal-loading").style.display = "none";
                    let response = JSON.parse(xhttp.responseText);
                    console.log(response);
                    document.querySelector("#modal-info").style.display = "flex";
                    document.querySelector("#modal-info-message").innerHTML = `${response.message}`;
                    Object.keys(foeItems).forEach((prop) => {
                        delete foeItems[prop];
                    });
                    Object.keys(foeItemsAux).forEach((prop) => {
                        delete foeItemsAux[prop];
                    });
                    foeTablaProductosBody.innerHTML = "";
                }

                if (xhttp.status === 400) {
                    document.querySelector("#modal-loading").style.display = "none";
                    let response = JSON.parse(xhttp.responseText);
                    console.log(response);
                    document.querySelector("#modal-error").style.display = "flex";
                    document.querySelector("#modal-error-message").innerHTML = `${response.message}`;
                }
            }
        }
        xhttp.open('post', entorno.value + '/almacen/entradas', true);
        xhttp.send(formData);
    });
});


