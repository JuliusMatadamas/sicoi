const entorno = document.querySelector("#entorno");
const csrf = document.querySelector("#csrf");
const idUsuario = document.querySelector("#id_usuario");
const coe = document.querySelector("#container-organizaciones_externas");
const coeH = coe.children[0];
const coeB = coe.children[1];
const coi = document.querySelector("#container-organizaciones_internas");
const coiH = coi.children[0];
const coiB = coi.children[1];
let foiIdInventarioOrigen = "P9Ixj1Dfa1jrlNydFnkblI5yQW7fPurdpMpHzxKydeAw6pbHbWI0jsNxQEN8ugU6vZy3WZ7kbgieID75JIYdzk34of7JBNCnlQA2";
let foiIdInventarioDestino = document.querySelector("#foi-id_inventario_destino");
let foiIdInventarioDestinoFeedback = document.querySelector("#foi-id_inventario_destino-feedback");
let foiIdInventarioDestinoTooltip = document.querySelector("#foi-id_inventario_destino-tooltip");
let foiIdInventarioDestinoValid = false;
let foiIdEstadoDeInventarioDestino = document.querySelector("#foi-id_estado_de_inventario_destino");
let foiIdEstadoDeInventarioDestinoFeedback = document.querySelector("#foi-id_estado_de_inventario_destino-feedback");
let foiIdEstadoDeInventarioDestinoTooltip = document.querySelector("#foi-id_estado_de_inventario_destino-tooltip");
let foiIdEstadoDeInventarioDestinoValid = false;
let foiFechaOrigen = document.querySelector("#foi-fecha_origen");
let foiIdCategoria = document.querySelector("#foi-id_categoria");
let foiIdCategoriaFeedback = document.querySelector("#foi-id_categoria-feedback");
let foiIdCategoriaTooltip = document.querySelector("#foi-id_categoria-tooltip");
let foiIdCategoriaValid = false;
let foiIdProducto = document.querySelector("#foi-id_producto");
let foiIdProductoFeedback = document.querySelector("#foi-id_producto-feedback");
let foiIdProductoTooltip = document.querySelector("#foi-id_producto-tooltip");
let foiIdProductoValid = false;
let foiNumeroDeSerie = document.querySelector("#foi-numero_de_serie");
let foiNumeroDeSerieFeedback = document.querySelector("#foi-numero_de_serie-feedback");
let foiNumeroDeSerieTooltip = document.querySelector("#foi-numero_de_serie-tooltip");
let foiNumeroDeSerieValid = false;
let foiCantidad = document.querySelector("#foi-cantidad");
let foiCantidadFeedback = document.querySelector("#foi-cantidad-feedback");
let foiCantidadTooltip = document.querySelector("#foi-cantidad-tooltip");
let foiCantidadValid = false;
let foiListTooltip = document.querySelector("#foi_list-tooltip");
let productosDisponibles = {};
let foiBtnAdd = document.querySelector("#foi-btn_add");
let foiBtnSubmit = document.querySelector("#foi-btn_submit");
let foiItems = {};
let foiItemsAux = {};
let foiTablaProductosBody = document.querySelector("#foi-tabla_productos tbody");


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

foiIdInventarioDestino.addEventListener("change", () => {
    foiIdInventarioDestino.classList.remove("is-valid", "is-invalid");
    foiIdInventarioDestinoFeedback.classList.remove("valid-feedback", "invalid-feedback");
    foiIdInventarioDestinoFeedback.innerHTML = "&nbsp;";
    foiIdInventarioDestinoTooltip.style.display = "none";
    foiIdInventarioDestinoTooltip.innerHTML = "&nbsp;";
    foiListTooltip.style.display = "none";
    foiListTooltip.innerHTML = "&nbsp;";


    if (foiIdInventarioDestino.value === "0") {
        foiIdInventarioDestino.classList.remove("is-valid", "is-invalid");
        foiIdInventarioDestinoFeedback.classList.remove("valid-feedback", "invalid-feedback");
        foiIdInventarioDestinoFeedback.innerHTML = "&nbsp;";
        foiIdInventarioDestinoValid = false;
        return;
    }

    foiIdInventarioDestino.classList.add("is-valid");
    foiIdInventarioDestinoFeedback.classList.add("valid-feedback");
    foiIdInventarioDestinoFeedback.innerHTML = "Parece correcto";
    foiIdInventarioDestinoValid = true;
});

foiIdEstadoDeInventarioDestino.addEventListener("change", () => {
    foiIdEstadoDeInventarioDestino.classList.remove("is-valid", "is-invalid");
    foiIdEstadoDeInventarioDestinoFeedback.classList.remove("valid-feedback", "invalid-feedback");
    foiIdEstadoDeInventarioDestinoFeedback.innerHTML = "&nbsp;";
    foiIdEstadoDeInventarioDestinoTooltip.style.display = "none";
    foiIdEstadoDeInventarioDestinoTooltip.innerHTML = "&nbsp;";
    foiListTooltip.style.display = "none";
    foiListTooltip.innerHTML = "&nbsp;";


    if (foiIdEstadoDeInventarioDestino.value === "0") {
        foiIdEstadoDeInventarioDestino.classList.remove("is-valid", "is-invalid");
        foiIdEstadoDeInventarioDestinoFeedback.classList.remove("valid-feedback", "invalid-feedback");
        foiIdEstadoDeInventarioDestinoFeedback.innerHTML = "&nbsp;";
        foiIdEstadoDeInventarioDestinoValid = false;
        return;
    }

    foiIdEstadoDeInventarioDestino.classList.add("is-valid");
    foiIdEstadoDeInventarioDestinoFeedback.classList.add("valid-feedback");
    foiIdEstadoDeInventarioDestinoFeedback.innerHTML = "Parece correcto";
    foiIdEstadoDeInventarioDestinoValid = true;
});

foiFechaOrigen.value = asignarFechaActual();

foiIdCategoria.addEventListener("change", () => {
    foiIdCategoria.classList.remove("is-valid", "is-invalid");
    foiIdCategoriaFeedback.classList.remove("valid-feedback", "invalid-feedback");
    foiIdCategoriaFeedback.innerHTML = "&nbsp;";
    foiIdCategoriaTooltip.style.display = "none";
    foiIdCategoriaTooltip.innerHTML = "&nbsp;";
    foiIdCategoriaValid = false;
    foiListTooltip.style.display = "none";
    foiListTooltip.innerHTML = "&nbsp;";


    deshabilitarCampos();

    if (parseInt(foiIdCategoria.value) === 0) {
        foiIdCategoria.classList.remove("is-valid", "is-invalid");
        foiIdCategoriaFeedback.classList.remove("valid-feedback", "invalid-feedback");
        foiIdCategoriaFeedback.innerHTML = "&nbsp;";
        foiIdCategoriaValid = false;
        return;
    }

    foiIdCategoria.classList.add("is-valid");
    foiIdCategoriaFeedback.classList.add("valid-feedback");
    foiIdCategoriaFeedback.innerHTML = "Parece correcto";
    foiIdCategoriaValid = true;

    cargarProdutosDeCategoria();
});

foiIdProducto.addEventListener("change", () => {
    foiIdProducto.classList.remove("is-valid", "is-invalid");
    foiIdProductoFeedback.classList.remove("valid-feedback", "invalid-feedback");
    foiIdProductoFeedback.innerHTML = "&nbsp;";
    foiIdProductoTooltip.style.display = "none";
    foiIdProductoTooltip.innerHTML = "&nbsp;";
    foiListTooltip.style.display = "none";
    foiListTooltip.innerHTML = "&nbsp;";


    if (parseInt(foiIdProducto.value) === 0) {
        foiIdProducto.classList.remove("is-valid", "is-invalid");
        foiIdProductoFeedback.classList.remove("valid-feedback", "invalid-feedback");
        foiIdProductoFeedback.innerHTML = "&nbsp;";
        foiIdProductoValid = false;
        return;
    }

    foiIdProducto.classList.add("is-valid");
    foiIdProductoFeedback.classList.add("valid-feedback");
    foiIdProductoFeedback.innerHTML = `Parece correcto`;
    foiIdProductoValid = true;
});

foiNumeroDeSerie.addEventListener("keyup", () => {
    foiNumeroDeSerie.classList.remove("is-valid", "is-invalid");
    foiNumeroDeSerieFeedback.classList.remove("valid-feedback", "invalid-feedback");
    foiNumeroDeSerieFeedback.innerHTML = "&nbsp;";
    foiNumeroDeSerieTooltip.style.display = "none";
    foiNumeroDeSerieTooltip.innerHTML = "&nbsp;";
    foiListTooltip.style.display = "none";
    foiListTooltip.innerHTML = "&nbsp;";


    if (foiNumeroDeSerie.value.trim().length === 0) {
        foiNumeroDeSerie.classList.remove("is-valid", "is-invalid");
        foiNumeroDeSerieFeedback.classList.remove("valid-feedback", "invalid-feedback");
        foiNumeroDeSerieFeedback.innerHTML = "&nbsp;";
        foiNumeroDeSerieValid = false;
        return;
    }

    if (foiNumeroDeSerie.value.trim().length < 5) {
        foiNumeroDeSerie.classList.add("is-invalid");
        foiNumeroDeSerieFeedback.classList.add("invalid-feedback");
        foiNumeroDeSerieFeedback.innerHTML = `Te faltan ${5 - foiNumeroDeSerie.value.trim().length} caracteres`;
        foiNumeroDeSerieValid = false;
        return;
    }

    if (foiNumeroDeSerie.value.trim().length > 20) {
        foiNumeroDeSerie.classList.add("is-invalid");
        foiNumeroDeSerieFeedback.classList.add("invalid-feedback");
        foiNumeroDeSerieFeedback.innerHTML = `Te sobran ${foiNumeroDeSerie.value.trim().length - 20} caracteres`;
        foiNumeroDeSerieValid = false;
        return;
    }

    foiNumeroDeSerie.classList.add("is-valid");
    foiNumeroDeSerieFeedback.classList.add("valid-feedback");
    foiNumeroDeSerieFeedback.innerHTML = `Parece correcto`;
    foiNumeroDeSerieValid = true;
});

["change", "click", "keyup"].forEach(e => {
    foiCantidad.addEventListener(e, () => {
        foiCantidad.classList.remove("is-valid", "is-invalid");
        foiCantidadFeedback.classList.remove("valid-feedback", "invalid-feedback");
        foiCantidadFeedback.innerHTML = "&nbsp;";
        foiCantidadTooltip.style.display = "none";
        foiCantidadTooltip.innerHTML = "&nbsp;";
        foiCantidadValid = false;
        foiListTooltip.style.display = "none";
        foiListTooltip.innerHTML = "&nbsp;";


        if (foiCantidad.value.trim().length === 0) {
            foiCantidad.classList.remove("is-valid", "is-invalid");
            foiCantidadFeedback.classList.remove("is-valid", "is-invalid");
            foiCantidadFeedback.innerHTML = "&nbsp;";
            foiCantidadValid = false;
            return;
        }

        if (!soloNumeros(foiCantidad.value.trim())) {
            foiCantidad.classList.add("is-invalid");
            foiCantidadFeedback.classList.add("invalid-feedback");
            foiCantidadFeedback.innerHTML = "¡Ingresa solo números!";
            foiCantidadValid = false;
            return;
        }

        let total = productosDisponibles.find(hallarProducto).total;

        if (parseInt(foiCantidad.value) > total) {
            foiCantidad.classList.add("is-invalid");
            foiCantidadFeedback.classList.add("invalid-feedback");
            foiCantidadFeedback.innerHTML = `El disponible de este producto es de ${total} pzas.`;
            foiCantidadValid = false;
            return;
        }

        foiCantidad.classList.add("is-valid");
        foiCantidadFeedback.classList.add("valid-feedback");
        foiCantidadFeedback.innerHTML = `Parece correcto`;
        foiCantidadValid = true;
    });
});

foiBtnAdd.addEventListener("click", () => {
    foiListTooltip.style.display = "none";
    foiListTooltip.innerHTML = "&nbsp;";

    if (foiIdCategoriaValid) {
        if (parseInt(foiIdCategoria.value) === 3) {
            if (!foiNumeroDeSerieValid) {
                foiNumeroDeSerie.focus();
                foiNumeroDeSerieTooltip.style.display = 'block';
                foiNumeroDeSerieTooltip.innerHTML = 'Este campo no cumple con lo requerido.';
                return;
            }

            for (let i = 0; i < Object.keys(foiItems).length; i++) {
                if (foiItems[i].numero_de_serie === foiNumeroDeSerie.value.trim()) {
                    foiNumeroDeSerieTooltip.style.display = 'block';
                    foiNumeroDeSerieTooltip.innerHTML = `Esta serie ya fue agregada a la lista.`;
                    return;
                }
            }

            // VALIDAR NÚMERO DE SERIE
            foiBtnAdd.disabled = true;
            foiBtnSubmit.disabled = true;

            // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
            // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
            grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
                action: 'submit'
            }).then(function (token) {

                let xhttp = new XMLHttpRequest();
                let formData = new FormData();
                formData.append('csrf', csrf.value);
                formData.append('token', token);
                formData.append('id_usuario', idUsuario.value);
                formData.append('numero_de_serie', foiNumeroDeSerie.value);

                xhttp.onreadystatechange = function () {
                    if (xhttp.readyState) {
                        if (xhttp.status === 200) {
                            let response = JSON.parse(xhttp.responseText);

                            foiBtnAdd.disabled = false;
                            foiBtnSubmit.disabled = false;

                            Object.keys(foiItems).length === 0 ? idx = 0 : idx = Object.keys(foiItems).length;

                            let item = {};

                            item.id_producto = parseInt(foiIdProducto.value);
                            item.producto = foiIdProducto.options[foiIdProducto.selectedIndex].text;
                            item.numero_de_serie = foiNumeroDeSerie.value.trim();
                            item.cantidad = 1;

                            if (item.numero_de_serie.trim().length !== 0) {
                                foiItems[idx] = item;
                            }

                            foiNumeroDeSerie.classList.remove("is-valid", "is-invalid");
                            foiNumeroDeSerieFeedback.classList.remove("valid-feedback", "invalid-feedback");
                            foiNumeroDeSerieFeedback.innerHTML = `&nbsp;`;
                            foiNumeroDeSerie.value = '';
                            foiNumeroDeSerie.focus();
                            foiNumeroDeSerieValid = false;

                            addItemsToTableFoi();
                        }

                        if (xhttp.status === 400) {
                            let response = JSON.parse(xhttp.responseText);

                            foiBtnAdd.disabled = false;
                            foiBtnSubmit.disabled = false;
                            foiNumeroDeSerie.focus();
                            foiNumeroDeSerieValid = false;
                            foiNumeroDeSerieTooltip.style.display = 'block';
                            foiNumeroDeSerieTooltip.innerHTML = `${response.message}`;
                        }
                    }
                }
                xhttp.open('post', entorno.value + '/api/validar_serie_salida', true);
                xhttp.send(formData);
            });
        } else {
            if (!foiCantidadValid) {
                foiCantidad.focus();
                foiCantidadTooltip.style.display = 'block';
                foiCantidadTooltip.innerHTML = 'Debes ingresar solo números en este campo sin exceder el disponible.';
                return;
            }

            Object.keys(foiItems).length === 0 ? idx = 0 : idx = Object.keys(foiItems).length;

            if (Object.keys(foiItems).length !== 0) {
                for (let i = 0; i < Object.keys(foiItems).length; i++) {
                    if (foiItems[i].id_producto === parseInt(foiIdProducto.value)) {
                        foiIdProductoTooltip.style.display = "block";
                        foiIdProductoTooltip.innerHTML = "Este producto ya fue agregado a la lista.";
                        return;
                    }
                }
            }

            let item = {};

            item.id_producto = parseInt(foiIdProducto.value);
            item.producto = foiIdProducto.options[foiIdProducto.selectedIndex].text;
            item.numero_de_serie = "-";
            item.cantidad = foiCantidad.value;

            if (item.numero_de_serie.trim().length !== 0) {
                foiItems[idx] = item;
            }

            foiCantidad.classList.remove("is-valid", "is-invalid");
            foiCantidadFeedback.classList.remove("valid-feedback", "invalid-feedback");
            foiCantidadFeedback.innerHTML = "&nbsp;";
            foiCantidad.value = '';
            foiCantidad.focus();
            foiCantidadValid = false;

            addItemsToTableFoi();
        }
    }
});

foiBtnSubmit.addEventListener("click", e => {
    e.preventDefault();

    if (!foiIdInventarioDestinoValid) {
        foiIdInventarioDestino.focus();
        foiIdInventarioDestinoTooltip.style.display = "block";
        foiIdInventarioDestinoTooltip.innerHTML = "Se debe seleccionar una opción de este campo.";
        return;
    }

    if (!foiIdEstadoDeInventarioDestinoValid) {
        foiIdEstadoDeInventarioDestino.focus();
        foiIdEstadoDeInventarioDestinoTooltip.style.display = 'block';
        foiIdEstadoDeInventarioDestinoTooltip.innerHTML = 'Se debe seleccionar una opción de este campo.';
        return;
    }

    if (!foiIdCategoriaValid) {
        foiIdCategoria.focus();
        foiIdCategoriaTooltip.style.display = 'block';
        foiIdCategoriaTooltip.innerHTML = 'Se debe seleccionar una opción de este campo.';
        return;
    }

    if (Object.keys(foiItems).length === 0) {
        foiListTooltip.style.display = 'block';
        foiListTooltip.innerHTML = 'Se debe ingresar por lo menos un producto a la lista.';
        return;
    }

    document.querySelector("#modal-loading").style.display = "flex";

    for (let i = 0; i < Object.keys(foiItems).length; i++) {
        foiItems[i].id_usuario = parseInt(idUsuario.value);
        foiItems[i].id_inventario_origen = foiIdInventarioOrigen;
        foiItems[i].id_estado_de_inventario_origen = 1;
        foiItems[i].fecha_origen = foiFechaOrigen.value;
        foiItems[i].id_inventario_destino = foiIdInventarioDestino.value;
        foiItems[i].id_estado_de_inventario_destino = foiIdEstadoDeInventarioDestino.value;
    }

    let itemsToSend = "";
    let items = Object.entries(foiItems);
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
                    document.querySelector("#modal-info").style.display = "flex";
                    document.querySelector("#modal-info-message").innerHTML = `${response.message}`;
                    document.querySelector("#modal-info #modal-btn").addEventListener("click", () => {
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
        xhttp.open('post', entorno.value + '/almacen/salidas', true);
        xhttp.send(formData);
    });
});

function addItemsToTableFoi() {
    foiListTooltip.style.display = 'none';
    foiListTooltip.innerHTML = "&nbsp;";
    foiTablaProductosBody.innerHTML = "";

    for (let i = 0; i < Object.keys(foiItems).length; i++) {
        if (foiItems[i].numero_de_serie.trim().length !== 0) {
            let tr = document.createElement("tr");

            let td01 = document.createElement("td");
            let input01 = document.createElement("input");
            input01.classList.add("input-table");
            input01.value = foiItems[i].producto;
            td01.appendChild(input01);
            tr.appendChild(td01);

            let td02 = document.createElement("td");
            let input02 = document.createElement("input");
            input02.classList.add("input-table");
            input02.value = foiItems[i].numero_de_serie;
            td02.appendChild(input02);
            tr.appendChild(td02);

            let td03 = document.createElement("td");
            let input03 = document.createElement("input");
            input03.classList.add("input-table");
            input03.value = foiItems[i].cantidad;
            td03.appendChild(input03);
            tr.appendChild(td03);

            let td04 = document.createElement("td");
            let div04 = document.createElement("div");
            div04.setAttribute("id", "foe-item_" + i);
            div04.setAttribute("onclick", `removeFoiItem(${i})`);
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

            foiTablaProductosBody.appendChild(tr);
        }
    }
}

function removeFoiItem(idX) {
    for (let i = 0; i < Object.keys(foiItems).length; i++) {
        if (i !== idX) {
            Object.keys(foiItemsAux).length === 0 ? idY = 0 : idY = Object.keys(foiItemsAux).length;
            let item = {};
            item = foiItems[i];
            foiItemsAux[idY] = item;
        }
    }
    foiItems = foiItemsAux;
    addItemsToTableFoi();
}


function hallarProducto(producto) {
    return parseInt(producto.id) === parseInt(foiIdProducto.value);
}

function deshabilitarCampos() {
    foiIdProducto.innerHTML = ``;
    foiIdProducto.disabled = true;
    foiIdProductoTooltip.style.display = "none";
    foiIdProductoTooltip.innerHTML = "&nbsp;";
    foiIdProductoValid = false;

    foiNumeroDeSerie.value = "";
    foiCantidad.placeholder = "";
    foiNumeroDeSerie.disabled = true;
    foiNumeroDeSerieTooltip.style.display = "none";
    foiNumeroDeSerieTooltip.innerHTML = "&nbsp";
    foiNumeroDeSerieValid = false;

    foiCantidad.value = "";
    foiCantidad.placeholder = "";
    foiCantidad.disabled = true;
    foiCantidadTooltip.style.display = "none";
    foiCantidadTooltip.innerHTML = "&nbsp";
    foiCantidadValid = false;

    foiBtnAdd.disabled = true;
}

function cargarProdutosDeCategoria() {
    foiIdProducto.innerHTML = `<option>Espere...</option>`;
    foiNumeroDeSerie.value = "Espere...";
    foiCantidad.value = "Espere...";

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function (token) {

        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('id_categoria', foiIdCategoria.value);
        formData.append('csrf', csrf.value);
        formData.append('id_usuario', idUsuario.value);
        formData.append('token', token);


        xhttp.onreadystatechange = function () {
            if (xhttp.readyState) {
                if (xhttp.status === 200) {
                    productosDisponibles = JSON.parse(xhttp.responseText).data;
                    foiIdProducto.disabled = false;
                    let options = "";
                    productosDisponibles.forEach(e => {
                        options += `<option value="${e.id}">${e.nombre}</option>`;
                    });
                    foiIdProducto.innerHTML = options;

                    if (parseInt(foiIdCategoria.value) === 3) {
                        foiNumeroDeSerie.value = '';
                        foiNumeroDeSerie.placeholder = `Entre 5 y 20 caracteres`;
                        foiNumeroDeSerie.disabled = false;
                        foiCantidad.value = '';
                        foiCantidad.placeholder = "";
                    } else {
                        foiCantidad.value = '';
                        foiCantidad.placeholder = "Solo números";
                        foiCantidad.disabled = false;
                        foiNumeroDeSerie.value = '';
                        foiNumeroDeSerie.placeholder = ``;
                    }

                    foiBtnAdd.disabled = false;
                }

                if (xhttp.status === 400) {
                    console.log(xhttp.responseText);

                    foiIdProducto.innerHTML = ``;
                    foiIdProducto.disabled = true;

                    foiNumeroDeSerie.value = "";
                    foiNumeroDeSerie.disabled = true;

                    foiCantidad.value = "";
                    foiCantidad.disabled = true;

                    foiBtnAdd.disabled = true;
                }
            }
        }
        xhttp.open('post', entorno.value + '/api/productos_por_categoria_salidas', true);
        xhttp.send(formData);
    });
}
