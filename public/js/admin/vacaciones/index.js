let form = document.querySelector("#form-validate_vacaciones");
let csrf = document.querySelector("#csrf");
let idUsuario = document.querySelector("#id_usuario");
let items = document.querySelectorAll("select");
let alertValidacion = document.querySelector(".alert-validation");
let btnSubmit = document.querySelector('button[type="submit"]');
let validates = '';

items.forEach(e => {
    e.addEventListener("change", () => {
        alertValidacion.style.display = 'none';
        alertValidacion.innerHTML = `&nbsp;`;
    });
});

form.addEventListener("submit", e => {
    e.preventDefault();

    items.forEach(e => {
        if (parseInt(e.value) !== 0) {
            validates += `${e.id},${e.value}|`;
        }
    });
    validates = validates.substring(0,validates.length-1);

    if (validates.length === 0) {
        alertValidacion.style.display = 'block';
        alertValidacion.innerHTML = `No haz validado ninguna fecha solicitada de vacaciones.`;
        return;
    }

    btnSubmit.disabled = true;
    document.querySelector("#modal-loading").style.display = "flex";

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function(token) {
        // Enviar datos vía AJAX
        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('csrf', csrf.value);
        formData.append('token', token);
        formData.append('id_usuario', idUsuario.value);
        formData.append('validaciones', validates);

        xhttp.onreadystatechange = function() {
            if (xhttp.readyState === 4 && xhttp.status === 200)
            {
                // HABILITAR EL BOTÓN DE SUBMIT DEL FORMULARIO
                btnSubmit.disabled = false;
                // OCULTAR MODAL DE LOADING
                document.querySelector("#modal-loading").style.display = "none";
                // MOSTRAR MODAL DE INFO
                document.querySelector("#modal-info").style.display = "flex";
                // MOSTRAR EL MENSAJE
                let respuesta = JSON.parse(xhttp.responseText);
                document.querySelector("#modal-info-message").innerHTML = respuesta["message"];
                document.querySelector("#modal-info button").addEventListener("click", () => {
                    window.location.reload();
                });
            }
            else
            {
                if (xhttp.status === 400)
                {
                    // HABILITAR EL BOTÓN DE SUBMIT DEL FORMULARIO
                    btnSubmit.disabled = false;
                    // OCULTAR MODAL DE LOADING
                    document.querySelector("#modal-loading").style.display = "none";
                    // MOSTRAR MODAL DE ERROR
                    document.querySelector("#modal-error").style.display = "flex";
                    // MOSTRAR EL MENSAJE
                    let respuesta = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-error-message").innerHTML = respuesta["message"];
                }
            }
        }
        xhttp.open('post', form.getAttribute('action'), true);
        xhttp.send(formData);
    });
});