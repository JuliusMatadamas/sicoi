let form = document.querySelector("#form-update_vacacion");
let csrf = document.querySelector("#csrf");
let idUsuario = document.querySelector("#id_usuario");
let id = document.querySelector("#id");
let fecha = document.querySelector("#fecha");
let fechaTooltip = document.querySelector("#tooltip-fecha");
let btnSubmit = document.querySelector("button");

['click','change','focus'].forEach(evt => {
    fecha.addEventListener(evt, e => {
        fechaTooltip.style.display = 'none';
        fechaTooltip.innerHTML = "&nbsp;";
    });
});

form.addEventListener("submit", e => {
    e.preventDefault();

    if (fecha.value.trim().length == 0) {
        fechaTooltip.style.display = 'block';
        fechaTooltip.innerHTML = "Se debe ingresar una fecha.";
    } else {
        // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
        grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
            action: 'submit'
        }).then(function (token) {

            btnSubmit.disabled = true;
            document.querySelector("#modal-loading").style.display = 'flex';

            let xhttp = new XMLHttpRequest();
            let formData = new FormData();
            formData.append('csrf', csrf.value);
            formData.append('id', id.value);
            formData.append('id_usuario', idUsuario.value);
            formData.append('fecha', fecha.value);
            formData.append('token', token);


            xhttp.onreadystatechange = function () {
                if (xhttp.readyState === 4 && xhttp.status === 200) {
                    btnSubmit.disabled = false;
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
            xhttp.open('POST', form.getAttribute('action'), true);
            xhttp.send(formData);
        });
    }
});

