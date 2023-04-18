let idUsuario = document.querySelector("#id_usuario");
let entorno = document.querySelector("#entorno");
let csrf = document.querySelector("#csrf");
let tableContainer = document.querySelector("#table-container");
let numeroDeSerie = document.querySelector("#numero_de_serie");
let numeroDeSerieFeedback = document.querySelector("#numero_de_serie-feedback");
let tBodyToExport = document.querySelector("#tBodyToExport");
let infoTableToExport;

numeroDeSerie.addEventListener("keyup", () => {
    numeroDeSerie.classList.remove("is-valid", "is-invalid");
    numeroDeSerieFeedback.classList.remove("valid-feedback", "invalid-feedback");
    numeroDeSerieFeedback.innerHTML = "&nbsp;";

    if (numeroDeSerie.value.trim().length === 0) {
        numeroDeSerie.classList.remove("is-valid", "is-invalid");
        numeroDeSerieFeedback.classList.remove("valid-feedback", "invalid-feedback");
        numeroDeSerieFeedback.innerHTML = "&nbsp;";
        return;
    }

    numeroDeSerie.classList.add("is-valid");
    numeroDeSerieFeedback.classList.add("valid-feedback");
    numeroDeSerieFeedback.innerHTML = "Parece correcto.";
});

// FUNCIÓN PARA OBTENER LA INFORMACIÓN POR ORGANIZACIÓN
const reportePorOrganizacion = () => {
    let idOrganizacion = document.querySelector("#id_organizacion");

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
        formData.append('id_organizacion', idOrganizacion.value);
        formData.append('token', token);


        xhttp.onreadystatechange = function () {
            if (xhttp.readyState === 4) {
                if (xhttp.status === 200) {
                    document.querySelector("#modal-loading").style.display = 'none';
                    let response = JSON.parse(xhttp.responseText);
                    infoTableToExport = response;
                    let cont = 1;

                    tableContainer.innerHTML = '';
                    let hr = document.createElement("hr");
                    tableContainer.appendChild(hr);
                    let br = document.createElement("br");
                    tableContainer.appendChild(br);
                    let div = document.createElement("div");
                    div.setAttribute("id", "export-button");
                    div.setAttribute("class", "text-end");
                    let button = document.createElement("button");
                    button.setAttribute("class", "btn btn-success");
                    button.setAttribute("onclick", "exportTableToExcel()");
                    let i = document.createElement("i");
                    i.setAttribute("class", "fa-regular fa-file-excel");
                    button.appendChild(i);
                    let span = document.createElement("span");
                    span.innerText = "Exportar a Excel";
                    button.appendChild(span);
                    div.appendChild(button);
                    tableContainer.appendChild(div);

                    let dataTable = document.createElement("table");
                    dataTable.setAttribute("id", "tabla-reportes");
                    dataTable.setAttribute("class", "table table-light table-bordered table-striped table-hover");

                    let tHead = document.createElement("thead");

                    let tr = document.createElement("tr");
                    let th1 = document.createElement("th");
                    th1.innerHTML = "#";
                    tr.appendChild(th1);
                    let th2 = document.createElement("th");
                    th2.innerHTML = "Inventario Origen";
                    tr.appendChild(th2);
                    let th3 = document.createElement("th");
                    th3.innerHTML = "Estado Origen";
                    tr.appendChild(th3);
                    let th4 = document.createElement("th");
                    th4.innerHTML = "Fecha Origen";
                    tr.appendChild(th4);
                    let th5 = document.createElement("th");
                    th5.innerHTML = "Inventario Destino";
                    tr.appendChild(th5);
                    let th6 = document.createElement("th");
                    th6.innerHTML = "Estado Destino";
                    tr.appendChild(th6);
                    let th7 = document.createElement("th");
                    th7.innerHTML = "Fecha Destino";
                    tr.appendChild(th7);
                    let th8 = document.createElement("th");
                    th8.innerHTML = "Categoria";
                    tr.appendChild(th8);
                    let th9 = document.createElement("th");
                    th9.innerHTML = "Producto";
                    tr.appendChild(th9);
                    let th10 = document.createElement("th");
                    th10.innerHTML = "N° Serie";
                    tr.appendChild(th10);
                    let th11 = document.createElement("th");
                    th11.innerHTML = "Cantidad";
                    tr.appendChild(th11);

                    tHead.appendChild(tr);
                    dataTable.appendChild(tHead);

                    let tBodyReport = document.createElement("tbody");
                    response.forEach(resp => {
                        let tr = document.createElement("tr");
                        let td1 = document.createElement("td");
                        td1.innerHTML = cont;
                        tr.appendChild(td1);
                        let td2 = document.createElement("td");
                        td2.innerHTML = resp.inventario_origen;
                        tr.appendChild(td2);
                        let td3 = document.createElement("td");
                        td3.innerHTML = resp.estado_de_inventario_origen;
                        tr.appendChild(td3);
                        let td4 = document.createElement("td");
                        td4.innerHTML = resp.fecha_origen;
                        tr.appendChild(td4);
                        let td5 = document.createElement("td");
                        td5.innerHTML = resp.inventario_destino;
                        tr.appendChild(td5);
                        let td6 = document.createElement("td");
                        td6.innerHTML = resp.estado_de_inventario_destino;
                        tr.appendChild(td6);
                        let td7 = document.createElement("td");
                        td7.innerHTML = resp.fecha_destino;
                        tr.appendChild(td7);
                        let td8 = document.createElement("td");
                        td8.innerHTML = resp.categoria;
                        tr.appendChild(td8);
                        let td9 = document.createElement("td");
                        td9.innerHTML = resp.nombre_producto;
                        tr.appendChild(td9);
                        let td10 = document.createElement("td");
                        td10.innerHTML = resp.numero_de_serie;
                        tr.appendChild(td10);
                        let td11 = document.createElement("td");
                        td11.innerHTML = resp.cantidad;
                        tr.appendChild(td11);
                        tBodyReport.appendChild(tr);
                        cont++;
                    });
                    dataTable.appendChild(tBodyReport);
                    tableContainer.appendChild(dataTable);
                    tableContainer.style.display = "block";
                    new JSTable(dataTable, {});
                }

                if (xhttp.status === 400) {
                    document.querySelector("#modal-loading").style.display = 'none';
                    document.querySelector("#error-container").style.display = 'flex';
                    let response = JSON.parse(xhttp.responseText);
                    document.querySelector("#error-message").innerHTML = response.message;
                }
            }
        }
        xhttp.open('POST', entorno.value + '/api/almacen/reporte_por_organizacion', true);
        xhttp.send(formData);
    });
};

function exportTableToExcel() {
    let table = document.querySelector("#tabla-to-export");
    let cont = 1;
    while (tBodyToExport.hasChildNodes()) {
        tBodyToExport.removeChild(tBodyToExport.firstChild);
    }
    infoTableToExport.forEach(resp => {
        let tr = document.createElement("tr");
        let td1 = document.createElement("td");
        td1.innerHTML = cont;
        tr.appendChild(td1);
        let td2 = document.createElement("td");
        td2.innerHTML = resp.inventario_origen;
        tr.appendChild(td2);
        let td3 = document.createElement("td");
        td3.innerHTML = resp.estado_de_inventario_origen;
        tr.appendChild(td3);
        let td4 = document.createElement("td");
        td4.innerHTML = resp.fecha_origen;
        tr.appendChild(td4);
        let td5 = document.createElement("td");
        td5.innerHTML = resp.inventario_destino;
        tr.appendChild(td5);
        let td6 = document.createElement("td");
        td6.innerHTML = resp.estado_de_inventario_destino;
        tr.appendChild(td6);
        let td7 = document.createElement("td");
        td7.innerHTML = resp.fecha_destino;
        tr.appendChild(td7);
        let td8 = document.createElement("td");
        td8.innerHTML = resp.categoria;
        tr.appendChild(td8);
        let td9 = document.createElement("td");
        td9.innerHTML = resp.nombre_producto;
        tr.appendChild(td9);
        let td10 = document.createElement("td");
        td10.innerHTML = resp.numero_de_serie;
        tr.appendChild(td10);
        let td11 = document.createElement("td");
        td11.innerHTML = resp.cantidad;
        tr.appendChild(td11);
        tBodyToExport.appendChild(tr);
        cont++;
    });
    TableToExcel.convert(table, {name: `reporte_almacen.xlsx`, sheet: {name: 'reporte_almacen'}});
}

// FUNCIÓN PARA OBTENER LA INFORMACIÓN POR SERIE
const reportePorSerie = () => {
    if (numeroDeSerie.value.trim().length === 0) {
        numeroDeSerie.classList.add("is-invalid");
        numeroDeSerieFeedback.classList.add("invalid-feedback");
        numeroDeSerieFeedback.innerHTML = "Ingresa el número de serie";
        return;
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
        formData.append('numero_de_serie', numeroDeSerie.value);
        formData.append('token', token);


        xhttp.onreadystatechange = function () {
            if (xhttp.readyState === 4) {
                if (xhttp.status === 200) {
                    document.querySelector("#modal-loading").style.display = 'none';
                    let response = JSON.parse(xhttp.responseText);
                    infoTableToExport = response;
                    let cont = 1;

                    tableContainer.innerHTML = '';
                    let hr = document.createElement("hr");
                    tableContainer.appendChild(hr);
                    let br = document.createElement("br");
                    tableContainer.appendChild(br);
                    let div = document.createElement("div");
                    div.setAttribute("id", "export-button");
                    div.setAttribute("class", "text-end");
                    let button = document.createElement("button");
                    button.setAttribute("class", "btn btn-success");
                    button.setAttribute("onclick", "exportTableToExcel()");
                    let i = document.createElement("i");
                    i.setAttribute("class", "fa-regular fa-file-excel");
                    button.appendChild(i);
                    let span = document.createElement("span");
                    span.innerText = "Exportar a Excel";
                    button.appendChild(span);
                    div.appendChild(button);
                    tableContainer.appendChild(div);

                    let dataTable = document.createElement("table");
                    dataTable.setAttribute("id", "tabla-reportes");
                    dataTable.setAttribute("class", "table table-light table-bordered table-striped table-hover");

                    let tHead = document.createElement("thead");

                    let tr = document.createElement("tr");
                    let th1 = document.createElement("th");
                    th1.innerHTML = "#";
                    tr.appendChild(th1);
                    let th2 = document.createElement("th");
                    th2.innerHTML = "Inventario Origen";
                    tr.appendChild(th2);
                    let th3 = document.createElement("th");
                    th3.innerHTML = "Estado Origen";
                    tr.appendChild(th3);
                    let th4 = document.createElement("th");
                    th4.innerHTML = "Fecha Origen";
                    tr.appendChild(th4);
                    let th5 = document.createElement("th");
                    th5.innerHTML = "Inventario Destino";
                    tr.appendChild(th5);
                    let th6 = document.createElement("th");
                    th6.innerHTML = "Estado Destino";
                    tr.appendChild(th6);
                    let th7 = document.createElement("th");
                    th7.innerHTML = "Fecha Destino";
                    tr.appendChild(th7);
                    let th8 = document.createElement("th");
                    th8.innerHTML = "Categoria";
                    tr.appendChild(th8);
                    let th9 = document.createElement("th");
                    th9.innerHTML = "Producto";
                    tr.appendChild(th9);
                    let th10 = document.createElement("th");
                    th10.innerHTML = "N° Serie";
                    tr.appendChild(th10);
                    let th11 = document.createElement("th");
                    th11.innerHTML = "Cantidad";
                    tr.appendChild(th11);

                    tHead.appendChild(tr);
                    dataTable.appendChild(tHead);

                    let tBodyReport = document.createElement("tbody");
                    response.forEach(resp => {
                        let tr = document.createElement("tr");
                        let td1 = document.createElement("td");
                        td1.innerHTML = cont;
                        tr.appendChild(td1);
                        let td2 = document.createElement("td");
                        td2.innerHTML = resp.inventario_origen;
                        tr.appendChild(td2);
                        let td3 = document.createElement("td");
                        td3.innerHTML = resp.estado_de_inventario_origen;
                        tr.appendChild(td3);
                        let td4 = document.createElement("td");
                        td4.innerHTML = resp.fecha_origen;
                        tr.appendChild(td4);
                        let td5 = document.createElement("td");
                        td5.innerHTML = resp.inventario_destino;
                        tr.appendChild(td5);
                        let td6 = document.createElement("td");
                        td6.innerHTML = resp.estado_de_inventario_destino;
                        tr.appendChild(td6);
                        let td7 = document.createElement("td");
                        td7.innerHTML = resp.fecha_destino;
                        tr.appendChild(td7);
                        let td8 = document.createElement("td");
                        td8.innerHTML = resp.categoria;
                        tr.appendChild(td8);
                        let td9 = document.createElement("td");
                        td9.innerHTML = resp.nombre_producto;
                        tr.appendChild(td9);
                        let td10 = document.createElement("td");
                        td10.innerHTML = resp.numero_de_serie;
                        tr.appendChild(td10);
                        let td11 = document.createElement("td");
                        td11.innerHTML = resp.cantidad;
                        tr.appendChild(td11);
                        tBodyReport.appendChild(tr);
                        cont++;
                    });
                    dataTable.appendChild(tBodyReport);
                    tableContainer.appendChild(dataTable);
                    tableContainer.style.display = "block";
                    new JSTable(dataTable, {});
                }

                if (xhttp.status === 400) {
                    document.querySelector("#modal-loading").style.display = 'none';
                    document.querySelector("#error-container").style.display = 'flex';
                    let response = JSON.parse(xhttp.responseText);
                    document.querySelector("#error-message").innerHTML = response.message;
                }
            }
        }
        xhttp.open('POST', entorno.value + '/api/almacen/reporte_por_serie', true);
        xhttp.send(formData);
    });
};
