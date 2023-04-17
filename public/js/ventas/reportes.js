let entorno = document.querySelector("#entorno");
let csrf = document.querySelector("#csrf");
let idUsuario = document.querySelector("#id_usuario");
let btnMostrar = document.querySelector("#btn-mostrar");
let tableContainer = document.querySelector("#table-container");
let dataTable = document.querySelector("#tabla-reportes");
let tBodyReport = document.querySelector("#tBodyReport");
let tBodyToExport = document.querySelector("#tBodyToExport");
let infoTableToExport;

/**
 * Function para marcar los items del select multiple
 * @param e
 */
function addToList(e) {
    let eId = parseInt(e.getAttribute("id"));
    let idParent = e.parentElement.parentElement.getAttribute("id");
    let items = document.querySelectorAll(`#${idParent} .item`);

    if (eId === 0) {
        e.classList.toggle("checked");
        if (e.classList.contains("checked")) {
            items.forEach(item => {
                if (parseInt(item.getAttribute("id")) !== 0){
                    item.classList.add("checked");
                }
            });
        } else {
            items.forEach(item => {
                if (parseInt(item.getAttribute("id")) !== 0){
                    item.classList.remove("checked");
                }
            });
        }
    } else {
        e.classList.toggle("checked");

        if (e.classList.contains("checked")) {
            if (document.querySelectorAll(`#${idParent} .checked`).length === items.length - 1) {
                items.forEach(item => {
                    item.classList.add("checked");
                });
            }
        } else {
            items.forEach(item => {
                if (parseInt(item.getAttribute("id")) === 0){
                    item.classList.remove("checked");
                }
            });
        }
    }

    if (document.querySelectorAll(`#${idParent} .checked`).length === 0) {
        document.querySelector(`#${idParent} .btn-text`).innerText = "Seleccione";
    } else {
        if (document.querySelectorAll(`#${idParent} .checked`).length === items.length) {
            document.querySelector(`#${idParent} .btn-text`).innerText = "Todos seleccionados";
        } else {
            document.querySelector(`#${idParent} .btn-text`).innerText = `${document.querySelectorAll(`#${idParent} .checked`).length} Seleccionado(s)`;
        }
    }
}

btnMostrar.addEventListener("click", () => {
    let itemsVendedores = document.querySelectorAll("#vendedores .item");
    let idsVendedores = [];
    itemsVendedores.forEach(item => {
        if (!isNaN(parseInt(item.children[0].getAttribute("id")))) {
            if (item.classList.contains("checked")) {
                idsVendedores.push(parseInt(item.children[0].getAttribute("id")));
            }
        }
    });

    let itemsTiposDeVentas = document.querySelectorAll("#tipos_de_ventas .item");
    let idsTiposDeVentas = [];
    itemsTiposDeVentas.forEach(item => {
        if (!isNaN(parseInt(item.children[0].getAttribute("id")))) {
            if (item.classList.contains("checked")) {
                idsTiposDeVentas.push(parseInt(item.children[0].getAttribute("id")));
            }
        }
    });

    let fechaInicio = document.querySelector("#fecha_inicio");
    let fechaInicioDefault = document.querySelector("#fecha_inicio_default");
    let fechaTermino = document.querySelector("#fecha_termino");
    let fechaTerminoDefault = document.querySelector("#fecha_termino_default");

    if (fechaInicio.value.trim().length === 0) {
        fechaInicio.value = fechaInicioDefault.value;
    }

    if (!esFechaValida(fechaInicio.value)){
        fechaInicio.value = fechaInicioDefault.value;
    }

    let d1 = new Date(fechaInicio.value);
    let d2 = new Date(fechaInicioDefault.value);

    if(Date.parse(d1) < Date.parse(d2)) {
        fechaInicio.value = fechaInicioDefault.value;
    }

    if (fechaTermino.value.trim().length === 0) {
        fechaTermino.value = fechaTerminoDefault.value;
    }

    if (!esFechaValida(fechaTermino.value)){
        fechaTermino.value = fechaTerminoDefault.value;
    }

    d1 = new Date(fechaTermino.value);
    d2 = new Date(fechaTerminoDefault.value);

    if(Date.parse(d1) > Date.parse(d2)) {
        fechaTermino.value = fechaTerminoDefault.value;
    }

    d1 = new Date(fechaInicio.value);
    d2 = new Date(fechaTermino.value);

    if(Date.parse(d1) > Date.parse(d2)) {
        fechaInicio.value = fechaTermino.value;
    }

    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function (token) {

        tableContainer.style.display = "none";
        document.querySelector("#error-container").style.display = 'none';
        document.querySelector("#modal-loading").style.display = 'flex';

        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('csrf', csrf.value);
        formData.append('id_usuario', idUsuario.value);
        formData.append('ids_vendedores', idsVendedores.join());
        formData.append('ids_tipos_de_ventas', idsTiposDeVentas.join());
        formData.append('fecha_inicio', fechaInicio.value);
        formData.append('fecha_termino', fechaTermino.value);
        formData.append('token', token);


        xhttp.onreadystatechange = function () {
            if (xhttp.readyState === 4) {
                if (xhttp.status === 200) {
                    document.querySelector("#modal-loading").style.display = 'none';
                    let response = JSON.parse(xhttp.responseText);
                    let ventas = JSON.parse(response.data);
                    infoTableToExport = ventas;
                    let cont = 1;
                    while(tBodyReport.hasChildNodes()) {
                        tBodyReport.removeChild(tBodyReport.firstChild);
                    }
                    ventas.forEach(venta => {
                        let tr = document.createElement("tr");
                        let td1 = document.createElement("td");
                        td1.innerHTML = cont;
                        tr.appendChild(td1);
                        let td2 = document.createElement("td");
                        td2.innerHTML = venta.vendedor;
                        tr.appendChild(td2);
                        let td3 = document.createElement("td");
                        td3.innerHTML = venta.cliente;
                        tr.appendChild(td3);
                        let td4 = document.createElement("td");
                        td4.innerHTML = venta.fecha_venta;
                        tr.appendChild(td4);
                        let td5 = document.createElement("td");
                        td5.innerHTML = venta.tipo_de_venta;
                        tr.appendChild(td5);
                        let td6 = document.createElement("td");
                        td6.innerHTML = venta.fecha_programada;
                        tr.appendChild(td6);
                        let td7 = document.createElement("td");
                        td7.innerHTML = venta.fecha_visita;
                        tr.appendChild(td7);
                        let td8 = document.createElement("td");
                        td8.innerHTML = venta.hora_visita;
                        tr.appendChild(td8);
                        let td9 = document.createElement("td");
                        td9.innerHTML = venta.estado_de_visita;
                        tr.appendChild(td9);
                        tBodyReport.appendChild(tr);
                        cont++;
                    });
                    tableContainer.style.display = "block";
                    new JSTable(dataTable, {
                    });
                }

                if (xhttp.status === 400) {
                    document.querySelector("#modal-loading").style.display = 'none';
                    document.querySelector("#error-container").style.display = 'flex';
                    let response = JSON.parse(xhttp.responseText);
                    document.querySelector("#error-message").innerHTML = response.message;
                }
            }
        }
        xhttp.open('POST', entorno.value + '/api/ventas/reporte', true);
        xhttp.send(formData);
    });
});

function exportTableToExcel() {
    let table = document.querySelector("#tabla-to-export");
    let cont = 1;
    while(tBodyToExport.hasChildNodes()) {
        tBodyToExport.removeChild(tBodyToExport.firstChild);
    }
    infoTableToExport.forEach(venta => {
        let tr = document.createElement("tr");
        let td1 = document.createElement("td");
        td1.innerHTML = cont;
        tr.appendChild(td1);
        let td2 = document.createElement("td");
        td2.innerHTML = venta.vendedor;
        tr.appendChild(td2);
        let td3 = document.createElement("td");
        td3.innerHTML = venta.cliente;
        tr.appendChild(td3);
        let td4 = document.createElement("td");
        td4.innerHTML = venta.fecha_venta;
        tr.appendChild(td4);
        let td5 = document.createElement("td");
        td5.innerHTML = venta.tipo_de_venta;
        tr.appendChild(td5);
        let td6 = document.createElement("td");
        td6.innerHTML = venta.fecha_programada;
        tr.appendChild(td6);
        let td7 = document.createElement("td");
        td7.innerHTML = venta.fecha_visita;
        tr.appendChild(td7);
        let td8 = document.createElement("td");
        td8.innerHTML = venta.hora_visita;
        tr.appendChild(td8);
        let td9 = document.createElement("td");
        td9.innerHTML = venta.estado_de_visita;
        tr.appendChild(td9);
        tBodyToExport.appendChild(tr);
        cont++;
    });
    TableToExcel.convert(table, {name: `reporte_ventas.xlsx`, sheet: { name: 'reporte_ventas'} });
}
