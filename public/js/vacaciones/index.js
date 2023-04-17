let entorno = document.querySelector("#entorno");
let csrf = document.querySelector("#csrf");
let idUsuario = document.querySelector("#idUsuario");
let jwtTooltip = document.querySelector("#tooltip-jwt");

const dt = new JSTable("#tabla-vacaciones", {
    serverSide: true,
    deferLoading: registros.value,
    ajax: entorno.value + '/api/vacaciones'
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

const confirmarEliminacionDeVacacion = i => {
    document.querySelector("#idVacacionPorEliminar").value = i;
    document.querySelector("#modal-confirm_vacacion").style.display = 'flex';
}

const eliminarVacacion = () => {
    document.querySelector("#modal-confirm_vacacion").style.display = 'none';
    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function (token) {

        document.querySelector("#modal-loading").style.display = 'flex';

        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('csrf', csrf.value);
        formData.append('id_usuario', idUsuario.value);
        formData.append('id_vacacion', document.querySelector("#idVacacionPorEliminar").value);
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
                    btnSubmit.disabled = false;
                    document.querySelector("#modal-loading").style.display = 'none';
                    let response = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-error").style.display = 'flex';
                    document.querySelector("#modal-error-message").innerHTML = `${response["message"]}`;
                }
            }
        }
        xhttp.open('POST', entorno.value + '/info/vacaciones/delete', true);
        xhttp.send(formData);
    });
};

const editarVacacion = i => {
    window.location = `${entorno.value}/info/vacaciones/edit?id=${i}`;
}