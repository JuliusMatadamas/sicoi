let entorno = document.querySelector("#entorno");
let jwtTooltip = document.querySelector("#tooltip-jwt");

const dt = new JSTable("#tabla-clientes", {
    serverSide: true,
    deferLoading: registros.value,
    ajax: entorno.value + '/api/clientes'
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
