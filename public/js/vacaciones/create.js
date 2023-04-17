let form = document.querySelector("#form-vacaciones");
let csrf = document.querySelector("#csrf");
let idUsuario = document.querySelector("#id_usuario");
let age = document.querySelector("#age");
let btnSubmit = document.querySelector("#btn-submit");

if (form) {
    form.addEventListener("submit", e => {
        e.preventDefault();
        let vacaciones = [];
        let fechas = document.querySelectorAll('input[type="date"]');

        fechas.forEach(f => {
            if (f.value !== ""){
                if (vacaciones.includes(f.value)){
                    document.querySelector("#modal-vacaciones_message").style.display = 'flex';
                    document.querySelector("#vacaciones_message-header").innerHTML = `¡Atención!`;
                    document.querySelector("#vacaciones_message-body").innerHTML = `La fecha ${f.value} ya ha sido seleccionada, elija otra fecha.`;
                    return;
                } else {
                    vacaciones.push(f.value);
                }
            }
        });

        if (vacaciones.length == 0) {
            document.querySelector("#modal-vacaciones_message").style.display = 'flex';
            document.querySelector("#vacaciones_message-header").innerHTML = `¡Atención!`;
            document.querySelector("#vacaciones_message-body").innerHTML = `No se han seleccionado fechas a tomar de vacaciones, seleccione al menos una fecha.`;
            return;
        }

        // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
        grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
            action: 'submit'
        }).then(function (token) {

            btnSubmit.disabled = true;
            document.querySelector("#modal-loading").style.display = 'flex';

            let xhttp = new XMLHttpRequest();
            let formData = new FormData();
            formData.append('csrf', csrf.value);
            formData.append('id_usuario', idUsuario.value);
            formData.append('age', age.value);
            formData.append('vacaciones', vacaciones);
            formData.append('token', token);


            xhttp.onreadystatechange = function () {
                if (xhttp.readyState === 4 && xhttp.status === 200) {
                    btnSubmit.disabled = false;
                    document.querySelector("#modal-loading").style.display = 'none';
                    let response = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-info").style.display = 'flex';
                    document.querySelector("#modal-info-message").innerHTML = `${response["message"]}`;

                    document.querySelector("#modal-info").addEventListener("click", e => {
                        window.location.reload();
                    });
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
    });
}