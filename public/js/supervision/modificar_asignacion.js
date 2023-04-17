let form = document.querySelector("#form-asignar_visita");
let idUsuario = document.querySelector("#id_usuario");
let idVenta = document.querySelector("#id_venta");
let idTecnico = document.querySelector("#id_tecnico");

function submitForm() {
    document.querySelector("#modal-loading").style.display = 'flex';

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function (token) {
        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('id_usuario', idUsuario.value);
        formData.append('id_venta', idVenta.value);
        formData.append('id_tecnico', idTecnico.value);
        formData.append('csrf', csrf.value);
        formData.append('token', token);


        xhttp.onreadystatechange = function () {
            if (xhttp.readyState === 4) {
                if (xhttp.status === 200) {
                    let response = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-loading").style.display = 'none';
                    document.querySelector("#modal-info").style.display = 'flex';
                    document.querySelector("#modal-info-message").innerHTML = `${response.message}`;
                    cancelacion();
                    return;
                }

                if (xhttp.status === 400) {
                    let response = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-loading").style.display = 'none';
                    document.querySelector("#modal-error").style.display = 'flex';
                    document.querySelector("#modal-error-message").innerHTML = `${response.message}`;
                }
            }
        }
        xhttp.open('post', form.getAttribute('action'), true);
        xhttp.send(formData);
    });
}