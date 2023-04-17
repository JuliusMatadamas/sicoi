let formReset = document.querySelector("#form-reset");
let btnSubmit = document.querySelector('button[type="submit"]');
let email = document.querySelector("#email");
let emailFeedback = document.querySelector("#feedback-email");
let emailTooltip = document.querySelector("#tooltip-email");
let emailValid = false;

email.addEventListener("keyup", e => {
    email.classList.remove('is-valid', 'is-invalid');
    emailFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    emailFeedback.innerHTML = '&nbsp;';
    emailTooltip.style.display = 'none';

    if (email.value.trim().length === 0) {
        email.classList.remove('is-valid', 'is-invalid');
        emailFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        emailFeedback.innerHTML = '&nbsp;';
        emailValid = false;
    } else {
        if (!isValidEmail(email.value.trim())) {
            email.classList.add('is-invalid');
            emailFeedback.classList.add('invalid-feedback');
            emailFeedback.innerHTML = '¡Ingresa una dirección de correo electrónico válida!';
            emailValid = false;
        } else {
            email.classList.add('is-valid');
            emailFeedback.classList.add('valid-feedback');
            emailFeedback.innerHTML = 'Parece correcta';
            emailValid = true;
        }
    }
});

formReset.addEventListener("submit", e => {
    e.preventDefault();

    if (!emailValid) {
        emailTooltip.style.display = 'block';
        emailTooltip.innerHTML = `<p>¡Debe ingresar correctamente la dirección de correo electronico que proporcionó para mandarle el enlace!</p>`;
        return;
    }

    // DESHABILITAR EL BOTÓN DE SUBMIT
    btnSubmit.disabled = true;

    // MOSTRAR EL MODAL DE LOADING
    document.querySelector("#modal-loading").style.display = 'flex';

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function (token) {
        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('email', email.value.trim());
        formData.append('token', token);

        xhttp.onreadystatechange = function () {
            if (xhttp.readyState === 4 && xhttp.status === 200) {
                // LIMPIAR EL CAMPO DEL EMAIL
                email.value = '';
                let response = JSON.parse(xhttp.responseText);
                // HABILITAR EL BOTÓN DE SUBMIT
                btnSubmit.disabled = false;
                // OCULTAR EL MODAL DE LOADING
                document.querySelector("#modal-loading").style.display = 'none';
                // MOSTRAR EL MODAL DE INFO
                document.querySelector("#modal-info").style.display = 'flex';
                document.querySelector("#modal-info_message").innerHTML = response["message"];
            } else {
                if (xhttp.status === 400) {
                    let response = JSON.parse(xhttp.responseText);
                    // HABILITAR EL BOTÓN DE SUBMIT
                    btnSubmit.disabled = false;
                    // OCULTAR EL MODAL DE LOADING
                    document.querySelector("#modal-loading").style.display = 'none';
                    // MOSTRAR EL MODAL DE ERROR
                    document.querySelector("#modal-error").style.display = 'flex';
                    document.querySelector("#modal-error_message").innerHTML = response["message"];
                }
            }
        }
        xhttp.open('POST', formReset.getAttribute('action'), true);
        xhttp.send(formData);
    });
});