let entorno = document.querySelector("#entorno");
let csrf = document.querySelector("#csrf");
let idUsuario = document.querySelector("#id_usuario");
let idCliente = document.querySelector("#id_cliente");
let preguntar = true;
let decodificadoresDisponibles = JSON.parse(document.querySelector("#decodificadores").value);

const validarSalidaCliente = (input, idcategoria, idproducto, disponible) => {
    let inputValid = document.querySelector("#valid_" + idcategoria + "_" + idproducto);
    let feedback = document.querySelector("#feedback_" + idproducto);

    input.classList.remove("is-valid", "is-invalid");
    feedback.classList.remove("valid-feedback", "invalid-feedback");
    feedback.innerHTML = "&nbsp;";

    if (input.value.trim().length === 0) {
        input.classList.remove("is-valid", "is-invalid");
        feedback.classList.remove("valid-feedback", "invalid-feedback");
        feedback.innerHTML = "&nbsp;";
        inputValid.value = 0;
        if (idcategoria === 3) {
            removerSelectSerie();
        }
        return;
    }

    if (!soloNumeros(input.value)) {
        input.classList.add("is-invalid");
        feedback.classList.add("invalid-feedback");
        feedback.innerHTML = "Ingresa solo números enteros en este campo.";
        inputValid.value = 0;
        if (idcategoria === 3) {
            removerSelectSerie();
        }
        return;
    }

    let salida = parseInt(input.value);

    if (salida <= 0) {
        input.classList.add("is-invalid");
        feedback.classList.add("invalid-feedback");
        feedback.innerHTML = "Ingresa una cantidad mayor a cero.";
        inputValid.value = 0;
        if (idcategoria === 3) {
            removerSelectSerie();
        }
        return;
    }

    if (salida > disponible) {
        input.classList.add("is-invalid");
        feedback.classList.add("invalid-feedback");
        feedback.innerHTML = "No puedes exceder el disponible de este producto.";
        inputValid.value = 0;
        if (idcategoria === 3) {
            removerSelectSerie();
        }
        return;
    }

    inputValid.value = 1;
    input.classList.add("is-valid");

    if (idcategoria === 3) {
        mostrarSelectSerie(parseInt(input.value));
    }
}

const mostrarSelectSerie = (cantidad) => {
    let options = `<option value="0">Seleccione</option>`;
    decodificadoresDisponibles.forEach(d => {
        options += `<option value="${d.numero_de_serie}">${d.nombre} - ${d.numero_de_serie}</option>`;
    });

    let selects = '';
    for (let i = 0; i < cantidad; i++) {
        selects += `<div class="row"><div class="col-12 col-md-3 col-lg-2 col-xl-2 col-label"><input type="text" class="input-label" disabled value="Seleccione la serie:"></div><div class="col-12 col-md-9 col-lg-10 col-xl-10 col-input input-select"><select class="form-control input-inventario select-serie">${options}</select></div></div>`;
    }

    document.querySelector("#series-decodificadores").innerHTML = selects;
}

const removerSelectSerie = () => {
    document.querySelector("#series-decodificadores").innerHTML = '';
}

const evaluarSalidasAlCliente = () => {
    let inputs = document.querySelectorAll(".input-cantidad");
    let salidas = [];

    inputs.forEach(input => {
        let id = input.getAttribute("id");
        let els = id.split("|");
        let idCategoria = parseInt(els[0]);
        let idProducto = parseInt(els[1]);
        let valid = Boolean(parseInt(document.querySelector("#valid_" + idCategoria + "_" + idProducto).value));

        if (valid) {
            if (idCategoria !== 3) {
                if (salidas.length === 0) {
                    let salida = {"id_producto": idProducto, "numero_de_serie": "-", "cantidad": parseInt(input.value)};
                    salidas.push(salida);
                } else {
                    let objIndex = salidas.findIndex((obj => obj.id_producto === idProducto));

                    if (objIndex !== -1) {
                        salidas[objIndex].cantidad = parseInt(input.value);
                    } else {
                        let salida = {
                            "id_producto": idProducto,
                            "numero_de_serie": "-",
                            "cantidad": parseInt(input.value)
                        };
                        salidas.push(salida);
                    }
                }
            } else {
                let selects = document.querySelectorAll(".select-serie");
                let seleccionados = [];
                for (let i = 0; i < selects.length; i++) {
                    if (selects[i].value !== "0") {
                        if (seleccionados.includes(selects[i].value)) {
                            selects[i].focus();
                            alert(`La serie '${selects[i].value}' esta repetida.`);
                            return;
                        } else {
                            seleccionados.push(selects[i].value);
                        }
                    }
                }

                if (seleccionados.length === 0) {
                    selects[0].focus();
                    alert(`Se debe seleccionar la(s) serie(s) a entregar al cliente.`);
                    return;
                }

                if (seleccionados.length < parseInt(input.value)) {
                    alert(`Te falta agregar ${parseInt(input.value) - seleccionados.length} series.`);
                    return;
                }

                if (salidas.length > 0) {
                    for (let i = 0; i < salidas.length; i++) {
                        let objIndex = salidas.findIndex((obj => obj.id_producto === idProducto));
                        if (objIndex !== -1) {
                            salidas.splice(objIndex, 1);
                        }
                    }
                }

                for (let i = 0; i < selects.length; i++) {
                    let salida = {"id_producto": idProducto, "numero_de_serie": selects[i].value, "cantidad": 1};
                    salidas.push(salida);
                }
            }
        }
    });

    if (salidas.length === 0) {
        alert(`Debes ingresar las cantidades de los artículos empleados en la visita.`);
        return;
    }

    let salidasToString = "";
    for (let i = 0; i < salidas.length; i++) {
        let entries = Object.entries(salidas[i]);
        entries.map(([key, value]) => {
            salidasToString += `${key}:${value},`;
        });
        salidasToString = salidasToString.slice(0, -1);
        salidasToString += "|";
    }
    salidasToString = salidasToString.slice(0, -1);

    document.querySelector("#modal-loading").style.display = "flex";

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function (token) {

        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('salidas', salidasToString);
        formData.append('id_cliente', idCliente.value);
        formData.append('id_usuario', idUsuario.value);
        formData.append('csrf', csrf.value);
        formData.append('token', token);


        xhttp.onreadystatechange = function () {
            if (xhttp.readyState) {
                if (xhttp.status === 200) {
                    document.querySelector("#modal-loading").style.display = "none";
                    let response = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-info").style.display = 'flex';
                    document.querySelector("#modal-info-message").innerHTML = `${response.message}`;
                    document.querySelector("#modal-info button").addEventListener("click", () => {
                        window.location = entorno.value + '/tecnicos/visitas_asignadas';
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
        xhttp.open('post', entorno.value + "/tecnicos/registrar_salidas", true);
        xhttp.send(formData);
    });
}
