let entorno = document.querySelector("#entorno");
let registros = document.querySelector("#registros");
let jwtTooltip = document.querySelector("#tooltip-jwt");

const dt = new JSTable("#tabla-visitas", {
    serverSide: true,
    deferLoading: registros.value,
    ajax: entorno.value + '/api/visitas_asignadas'
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
