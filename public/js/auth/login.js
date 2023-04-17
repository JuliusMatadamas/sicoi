let entorno = document.querySelector("#entorno");
let form = document.querySelector("#form-login");
let usuario = document.querySelector("#usuario");
let usuarioFeedback = document.querySelector("#feedback-usuario");
let usuarioTooltip = document.querySelector("#tooltip-usuario");
let usuarioValid = false;
let password = document.querySelector("#password");
let passwordFeedback = document.querySelector("#feedback-password");
let passwordTooltip = document.querySelector("#tooltip-password");
let passwordValid = false;
let btnSubmit = document.querySelector("#btn-submit");
let btnReset = document.querySelector("#btn-reset");

usuario.addEventListener("keyup", () => {
    usuarioTooltip.style.display = 'none';
    usuario.classList.remove('is-valid', 'is-invalid');
    usuarioFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    usuarioFeedback.innerHTML = '&nbsp;';
    usuarioValid = false;

    if (usuario.value.trim().length === 0) {
        usuario.classList.remove('is-valid', 'is-invalid');
        usuarioFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        usuarioFeedback.innerHTML = '&nbsp;';
        usuarioValid = false;
        return;
    }

    if (usuario.value.trim().length < 7) {
        usuario.classList.add('is-invalid');
        usuarioFeedback.classList.add('invalid-feedback');
        usuarioFeedback.innerHTML = `Te faltan ${7 - usuario.value.trim().length} carácteres`;
        usuarioValid = false;
        return;
    }

    if (usuario.value.trim().length > 10) {
        usuario.classList.add('is-invalid');
        usuarioFeedback.classList.add('invalid-feedback');
        usuarioFeedback.innerHTML = `Te sobran ${usuario.value.trim().length - 10} carácteres`;
        usuarioValid = false;
        return;
    }

    usuario.classList.add('is-valid');
    usuarioFeedback.classList.add('valid-feedback');
    usuarioFeedback.innerHTML = `Parece correcto.`;
    usuarioValid = true;
});

password.addEventListener("keyup", () => {
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


form.addEventListener("submit", e => {
    e.preventDefault();

    if (!usuarioValid) {
        usuarioTooltip.style.display = 'block';
        usuarioTooltip.innerHTML = `Llena este campo como se solicita.`;
        return;
    }

    if (!passwordValid) {
        passwordTooltip.style.display = 'block';
        passwordTooltip.innerHTML = `Llena este campo como se solicita.`;
        return;
    }

    // DESHABILITAR BOTONES
    btnSubmit.disabled = true;
    btnReset.disabled = true;

    // MOSTRAR MODAL LOADING
    document.querySelector("#modal-loading").style.display = 'flex';

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function (token) {
        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('usuario', usuario.value.trim());
        formData.append('password', password.value.trim());
        formData.append('token', token);

        xhttp.onreadystatechange = function () {
            if (xhttp.readyState === 4 && xhttp.status === 200) {
                // OCULTAR EL MODAL LOADING
                document.querySelector("#modal-loading").style.display = 'none';
                // HABILITAR BOTONES
                btnSubmit.disabled = false;
                btnReset.disabled = false;
                //
                let response = JSON.parse(xhttp.responseText);

                // MOSTRAR MODAL DE LOGIN
                document.querySelector("#modal-login").style.display = 'flex';
                document.querySelector("#modal-login_message").innerHTML = response["message"];

                // AÑADIR EVENTO CLIC AL BTN DEL MODAL
                document.querySelector("#modal-login_btn").addEventListener("click", () => {
                    location.href = entorno.value + '/info';
                });

                // REDIRIGIR AL USUARIO
                setTimeout(() => {
                    location.href = entorno.value + '/info';
                }, 1500);
            } else {
                if (xhttp.status === 400) {
                    // OCULTAR EL MODAL LOADING
                    document.querySelector("#modal-loading").style.display = 'none';
                    // HABILITAR BOTONES
                    btnSubmit.disabled = false;
                    btnReset.disabled = false;
                    let response = JSON.parse(xhttp.responseText);
                    // MOSTRAR MODAL DE ERROR
                    document.querySelector("#modal-error").style.display = 'flex';
                    document.querySelector("#modal-error_message").innerHTML = response["message"];
                }
            }
        }
        xhttp.open('POST', entorno.value, true);
        xhttp.send(formData);
    });
});

btnReset.addEventListener("click", () => {
    window.location = entorno.value + '/reset_clave';
});