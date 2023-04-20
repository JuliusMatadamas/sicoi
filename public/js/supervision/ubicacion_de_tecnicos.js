let entorno = document.querySelector("#entorno");
let csrf = document.querySelector("#csrf");
let idUsuario = document.querySelector("#id_usuario");
let fecha = document.querySelector("#fecha");
let image = entorno.value + '/public/img/icon.png';
let btnObtenerUbicaciones = document.querySelector("#btn-obtenerUbicaciones");
let tecnicos = [];
let registros = [];

btnObtenerUbicaciones.addEventListener("click", () => {
    document.querySelector("#modal-loading").style.display = "flex";

    let idTecnico = document.querySelector("#id_tecnico");
    let fecha = document.querySelector("#fecha");

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function (token) {

        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('id_usuario', idUsuario.value);
        formData.append('token', token);
        formData.append('csrf', csrf.value);
        formData.append('id_tecnico', idTecnico.value);
        formData.append('fecha', fecha.value);

        xhttp.onreadystatechange = function () {
            if (xhttp.readyState) {
                if (xhttp.status === 200) {
                    document.querySelector("#modal-loading").style.display = "none";
                    let response = JSON.parse(xhttp.responseText);
                    let data = response.data;
                    registros = response.data;

                    tecnicos = [];
                    data.forEach(d => {
                        if (typeof tecnicos[d.id_tecnico] === "undefined") {
                            tecnicos[d.id_tecnico] = {details: [], coords: []};
                            tecnicos[d.id_tecnico].coords.push({
                                lat: parseFloat(d.latitud),
                                lng: parseFloat(d.longitud)
                            });
                            tecnicos[d.id_tecnico].details.push({
                                id_venta: d.id_venta,
                                cliente: d.cliente,
                                tipo_de_venta: d.tipo_de_venta,
                                fecha_programada: d.fecha_programada,
                                fecha_visita: d.fecha_visita,
                                estado_de_visita: d.estado_de_visita,
                                hora_visita: d.hora_visita,
                                tecnico: d.tecnico
                            });
                        } else {
                            tecnicos[d.id_tecnico].coords.push({
                                lat: parseFloat(d.latitud),
                                lng: parseFloat(d.longitud)
                            });
                            tecnicos[d.id_tecnico].details.push({
                                id_venta: d.id_venta,
                                cliente: d.cliente,
                                tipo_de_venta: d.tipo_de_venta,
                                fecha_programada: d.fecha_programada,
                                fecha_visita: d.fecha_visita,
                                estado_de_visita: d.estado_de_visita,
                                hora_visita: d.hora_visita,
                                tecnico: d.tecnico
                            });
                        }
                    });

                    initMap();
                    setDataTable();
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
        xhttp.open('post', `${entorno.value}/api/supervision/ubicacion_de_tecnicos`, true);
        xhttp.send(formData);
    });
});


function initMap() {
    const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 13,
        center: {lat: 25.86, lng: -97.5},
        mapTypeId: "roadmap",
    });

    let details = [];
    let resultados = [];
    tecnicos.forEach(tecnico => {
        details.push(tecnico.details);
        resultados.push(tecnico.coords);
    });

    let strokeColors = ['#002b67', '#67002BFF', '#670000FF', '#E55A00FF', '#325E00FF', '#018152FF'];
    for (let i = 0; i < resultados.length; i++) {
        const detalles = details[i];
        const coordenadasDeUbicaciones = resultados[i];
        const rastroDeUbicaciones = new google.maps.Polyline({
            path: coordenadasDeUbicaciones,
            geodesic: true,
            strokeColor: strokeColors[i],
            strokeOpacity: 1.0,
            strokeWeight: 2,
        });

        let n = 0;
        coordenadasDeUbicaciones.forEach(coords => {
            new google.maps.Marker({
                position: coords,
                map,
                title: `${detalles[n].cliente}`,
                label: `${detalles[n].id_venta}`,
                icon: image,
            });
            n++;
        });

        rastroDeUbicaciones.setMap(map);
    }
}

function setDataTable() {
    let tBody = document.querySelector("#tabla-ubicaciones tbody");
    tBody.innerHTML = "";
    let n = 0;
    registros.forEach(reg => {
        let tr = document.createElement("tr");

        let td00 = document.createElement("td");
        let i00 = document.createElement("input");
        i00.setAttribute("class", "input-table");
        i00.setAttribute("value", reg.id_venta);
        td00.appendChild(i00);
        tr.appendChild(td00)

        let td01 = document.createElement("td");
        let i01 = document.createElement("input");
        i01.setAttribute("class", "input-table");
        i01.setAttribute("value", reg.cliente);
        td01.appendChild(i01);
        tr.appendChild(td01);

        let td02 = document.createElement("td");
        let i02 = document.createElement("input");
        i02.setAttribute("class", "input-table");
        i02.setAttribute("value", reg.tipo_de_venta);
        td02.appendChild(i02);
        tr.appendChild(td02);

        let td03 = document.createElement("td");
        let i03 = document.createElement("input");
        i03.setAttribute("class", "input-table");
        i03.setAttribute("value", reg.fecha_programada);
        td03.appendChild(i03);
        tr.appendChild(td03);

        let td04 = document.createElement("td");
        let i04 = document.createElement("input");
        i04.setAttribute("class", "input-table");
        i04.setAttribute("value", reg.fecha_visita);
        td04.appendChild(i04);
        tr.appendChild(td04);

        let td05 = document.createElement("td");
        let i05 = document.createElement("input");
        i05.setAttribute("class", "input-table");
        i05.setAttribute("value", reg.estado_de_visita);
        td05.appendChild(i05);
        tr.appendChild(td05);

        let td06 = document.createElement("td");
        let i06 = document.createElement("input");
        i06.setAttribute("class", "input-table");
        i06.setAttribute("value", reg.hora_visita);
        td06.appendChild(i06);
        tr.appendChild(td06);

        let td07 = document.createElement("td");
        let i07 = document.createElement("input");
        i07.setAttribute("class", "input-table");
        i07.setAttribute("value", reg.tecnico);
        td07.appendChild(i07);
        tr.appendChild(td07);

        tBody.appendChild(tr);
        n++;
    });
}

window.initMap = initMap;