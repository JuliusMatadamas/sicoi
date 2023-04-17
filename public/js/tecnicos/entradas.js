let entorno = document.querySelector("#entorno");
let csrf = document.querySelector("#csrf");
let idUsuario = document.querySelector("#id_usuario");
let containerEntradas = document.querySelector("#container-entradas");

document.addEventListener("DOMContentLoaded", () => {
    obtenerEntradas();
});

function obtenerEntradas() {
    containerEntradas.innerHTML = `<div class="row">
    <div class="col-12">
        <h3>Espera mientras se verifica si items por dar entrada en tu inventario...</h3>
    </div>
</div>`;

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    let xhttp = new XMLHttpRequest();
    let formData = new FormData();
    formData.append('csrf', csrf.value);
    formData.append('id_usuario', idUsuario.value);


    xhttp.onreadystatechange = function () {
        if (xhttp.readyState) {
            if (xhttp.status === 200) {
                let response = JSON.parse(xhttp.responseText);
                if (response.length === 0) {
                    containerEntradas.innerHTML = `<div class="row">
    <div class="col-12">
        <h3>No se encontraron items por ingresar a tu inventario.</h3>
    </div>
</div>`;
                } else {
                    let items = "";
                    response.forEach(item => {
                        items += `<tr><td><input type="text" class="input-table" value="${item.inventario_origen}"></td><td><input type="text" class="input-table" value="${item.fecha_origen}"></td><td><input type="text" class="input-table" value="${item.categoria}"></td><td><input type="text" class="input-table" value="${item.nombre}"></td><td><input type="text" class="input-table" value="${item.descripcion}"></td><td><input type="text" class="input-table" value="${item.numero_de_serie}"></td><td><input type="text" class="input-table" value="${item.cantidad}"></td><td class="td-actions"><input type="checkbox" value="${item.id}"></td></tr>`;
                    });
                    containerEntradas.innerHTML = `<div class="row">
    <div class="col-12">
        <h3>Tienes los siguientes items por ingresar a tu inventario.</h3>
    </div>
</div>  
<div class="tooltip">
    <table id="tabla-entradas" class="table table-light table-bordered table-striped table-hover" onclick="removeTooltip()">
        <thead>
            <tr>
                <th>Origen</th>
                <th>Fecha de origen</th>
                <th>Categoría</th>
                <th>Producto</th>
                <th>Descripción</th>
                <th>N° de serie</th>
                <th>Cantidad</th>
                <th>Recibir</th>
            </tr>
        </thead>
        <tbody>
        ${items}
        </tbody>
    </table>
    <div id="tabla-tooltip" class="tooltip-box">&nbsp;</div>
</div>
<br>
<div class="row">
    <div class="col-12 text-end">
        <input type="button" class="btn btn-dark w-xl-25 w-lg-25 w-md-50 w-sm-100 w-xs-100" value="Recibir" onclick="recibir()">
    </div>
</div>
`;
                }
            }

            if (xhttp.status === 400) {
                containerEntradas.innerHTML = `<div class="row">
    <div class="col-12">
        <h3 class="text-danger">Ocurrió un error y no se pudo obtener la información del servidor.</h3>
    </div>
</div>`;
            }
        }
    }
    xhttp.open('post', entorno.value + '/api/tecnicos/obtener_entradas', true);
    xhttp.send(formData);
}

function removeTooltip() {
    document.getElementById("tabla-tooltip").style.display = "none";
    document.getElementById("tabla-tooltip").innerHTML = "&nbsp;";
}

function recibir() {
    let checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
    if (checkboxes.length === 0) {
        document.getElementById("tabla-tooltip").style.display = "block";
        document.getElementById("tabla-tooltip").innerHTML = "Marca al menos un item a recibir.";
        return;
    }

    document.querySelector("#modal-loading").style.display = 'flex';
    let items = [];
    checkboxes.forEach(checkbox => {
        items.push(checkbox.getAttribute("value"));
    });
    let ids = items.join("|");

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function (token) {

        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('ids', ids);
        formData.append('csrf', csrf.value);
        formData.append('id_usuario', idUsuario.value);
        formData.append('token', token);


        xhttp.onreadystatechange = function () {
            if (xhttp.readyState === 4) {
                if (xhttp.status === 200) {
                    let response = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-loading").style.display = 'none';
                    document.querySelector("#modal-info").style.display = 'flex';
                    document.querySelector("#modal-info-message").innerHTML = `${response.message}`;
                    obtenerEntradas();
                }

                if (xhttp.status === 400) {
                    let response = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-loading").style.display = 'none';
                    document.querySelector("#modal-error").style.display = 'flex';
                    document.querySelector("#modal-error-message").innerHTML = `<p class="text-center">${response.message}</p>`;
                    obtenerEntradas();
                }
            }
        }
        xhttp.open('post', entorno.value + '/tecnicos/entradas', true);
        xhttp.send(formData);
    });
}