let entorno = document.querySelector("#entorno");
let registros = document.querySelector("#registros");
let jwtTooltip = document.querySelector("#tooltip-jwt");
let formEditPermiso = document.querySelector("#form-update_permiso");
let id = document.querySelector("#id");
let dt = new JSTable("#tabla-permisos", {
    serverSide: true,
    deferLoading: registros.value,
    ajax: entorno.value + '/api/permisos'
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

const editarPermiso = (i) => {
    window.location = `${entorno.value}/info/permisos/editar?id=${i}`;
};

const confirmarEliminacionDePermiso = (i) => {
    document.querySelector("#idPermisoPorEliminar").value = i;
    document.querySelector("#modal-confirm_permiso").style.display = 'flex';
}

const eliminarPermiso = () => {
    document.querySelector("#modal-confirm_permiso").style.display = 'none';
    document.querySelector("#modal-loading").style.display = 'flex';
    let csrf = document.querySelector("#csrf");
    let idUsuario = document.querySelector("#idUsuario");
    let idPermiso = document.querySelector("#idPermisoPorEliminar");

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function (token) {

        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('csrf', csrf.value);
        formData.append('id_usuario', idUsuario.value);
        formData.append('id_permiso', idPermiso.value);
        formData.append('token', token);


        xhttp.onreadystatechange = function () {
            if (xhttp.readyState === 4 && xhttp.status === 200) {
                dt.update();
                document.querySelector("#modal-loading").style.display = 'none';
                let response = JSON.parse(xhttp.responseText);
                document.querySelector("#modal-info").style.display = 'flex';
                document.querySelector("#modal-info-message").innerHTML = `${response["message"]}`;
            } else {
                if (xhttp.status === 400) {
                    document.querySelector("#modal-loading").style.display = 'none';
                    let response = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-error").style.display = 'flex';
                    document.querySelector("#modal-error-message").innerHTML = `${response["message"]}`;
                }
            }
        }
        xhttp.open('POST', entorno.value + '/info/permisos/eliminar', true);
        xhttp.send(formData);
    });
}
