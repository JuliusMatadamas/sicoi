let entorno = document.querySelector("#entorno");
let formEditNueva = document.querySelector("#form-nueva_clave");
let id = document.querySelector("#id");
let password = document.querySelector("#password");
let passwordFeedback = document.querySelector("#feedback-password");
let passwordTooltip = document.querySelector("#tooltip-password");
let passwordValid = false;
let passwordConfirm = document.querySelector("#password_confirm");
let passwordConfirmFeedback = document.querySelector("#feedback-password_confirm");
let passwordConfirmTooltip = document.querySelector("#tooltip-password_confirm");
let passwordConfirmValid = false;
let btnSubmit = document.querySelector('button[type="submit"]');

password.addEventListener("keyup", e => {
    passwordTooltip.style.display = 'none';
    password.classList.remove('is-valid', 'is-invalid');
    passwordFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    passwordFeedback.innerHTML = '&nbsp;';
    passwordValid = false;

    if (password.value.trim().length === 0) {
        password.classList.remove('is-valid', 'is-invalid');
        passwordFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        passwordFeedback.innerHTML = '&nbsp;';
        passwordValid = false;
        return;
    }

    if (password.value.trim().length < 7) {
        password.classList.add('is-invalid');
        passwordFeedback.classList.add('invalid-feedback');
        passwordFeedback.innerHTML = `Te faltan ${7 - password.value.trim().length} carácteres`;
        passwordValid = false;
        return;
    }

    if (password.value.trim().length > 10) {
        password.classList.add('is-invalid');
        passwordFeedback.classList.add('invalid-feedback');
        passwordFeedback.innerHTML = `Te sobran ${password.value.trim().length - 10} carácteres`;
        passwordValid = false;
        return;
    }

    password.classList.add('is-valid');
    passwordFeedback.classList.add('valid-feedback');
    passwordFeedback.innerHTML = `Parece correcto.`;
    passwordValid = true;
});


passwordConfirm.addEventListener("keyup", e => {
    passwordConfirmTooltip.style.display = 'none';
    passwordConfirm.classList.remove('is-valid', 'is-invalid');
    passwordConfirmFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    passwordConfirmFeedback.innerHTML = '&nbsp;';
    passwordConfirmValid = false;

    if (passwordConfirm.value.trim().length === 0) {
        passwordConfirm.classList.remove('is-valid', 'is-invalid');
        passwordConfirmFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        passwordConfirmFeedback.innerHTML = '&nbsp;';
        passwordConfirmValid = false;
        return;
    }

    if (passwordConfirm.value.trim().length < 7) {
        passwordConfirm.classList.add('is-invalid');
        passwordConfirmFeedback.classList.add('invalid-feedback');
        passwordConfirmFeedback.innerHTML = `Te faltan ${7 - passwordConfirm.value.trim().length} carácteres`;
        passwordConfirmValid = false;
        return;
    }

    if (passwordConfirm.value.trim().length > 10) {
        passwordConfirm.classList.add('is-invalid');
        passwordConfirmFeedback.classList.add('invalid-feedback');
        passwordConfirmFeedback.innerHTML = `Te sobran ${passwordConfirm.value.trim().length - 10} carácteres`;
        passwordConfirmValid = false;
        return;
    }

    if (passwordConfirm.value.trim() !== password.value.trim()) {
        passwordConfirm.classList.add('is-invalid');
        passwordConfirmFeedback.classList.add('invalid-feedback');
        passwordConfirmFeedback.innerHTML = `¡Las contraseñas deben coincidir!`;
        passwordConfirmValid = false;
        return;
    }

    passwordConfirm.classList.add('is-valid');
    passwordConfirmFeedback.classList.add('valid-feedback');
    passwordConfirmFeedback.innerHTML = `Parece correcto.`;
    passwordConfirmValid = true;
});

formEditNueva.addEventListener("submit", e => {
    e.preventDefault();

    if (!passwordValid) {
        passwordTooltip.style.display = 'block';
        passwordTooltip.innerHTML = 'Debes llenar este campo como se solicita.';
        password.focus();
        return;
    }

    if (!passwordConfirmValid) {
        passwordConfirmTooltip.style.display = 'block';
        passwordConfirmTooltip.innerHTML = 'Debes llenar este campo como se solicita.';
        passwordConfirm.focus();
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
        formData.append('id', id.value.trim());
        formData.append('password', password.value.trim());
        formData.append('password_confirm', passwordConfirm.value.trim());
        formData.append('token', token);

        xhttp.onreadystatechange = function () {
            if (xhttp.readyState === 4 && xhttp.status === 200) {
                // OCULTAR EL MODAL LOADING
                document.querySelector("#modal-loading").style.display = 'none';
                // HABILITAR EL BOTÓN DE SUBMIT
                btnSubmit.disabled = false;

                let response = JSON.parse(xhttp.responseText);

                // MOSTRAR MODAL DE INFO
                document.querySelector("#modal-info").style.display = 'flex';
                document.querySelector("#modal-info_message").innerHTML = response["message"];

                // AÑADIR EVENTO CLIC AL BTN DEL MODAL
                document.querySelector("#modal-info_btn").addEventListener("click", e => {
                    location.href = entorno.value;
                });

                // REDIRIGIR AL USUARIO
                setTimeout(() => {
                    location.href = entorno.value;
                }, 1500);
            } else {
                if (xhttp.status === 400) {
                    // OCULTAR EL MODAL LOADING
                    document.querySelector("#modal-loading").style.display = 'none';
                    // HABILITAR BOTÓN DE SUBMIT
                    btnSubmit.disabled = false;

                    let response = JSON.parse(xhttp.responseText);
                    // MOSTRAR MODAL DE ERROR
                    document.querySelector("#modal-error").style.display = 'flex';
                    document.querySelector("#modal-error_message").innerHTML = response["message"];
                }
            }
        }
        xhttp.open('POST', formEditNueva.getAttribute('action'), true);
        xhttp.send(formData);
    });
});