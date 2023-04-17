let url = document.querySelector("#url");
let f = document.querySelector("#form-nuevo_usuario");
let action = document.querySelector("#action");
let csrf = document.querySelector("#csrf");
let idEmpleado = document.querySelector("#id");
let idEmpleadoFeedback = document.querySelector("#id-feedback");
let idEmpleadoTooltip = document.querySelector("#id-tooltip");
let idEmpleadoValid = false;
let usuario = document.querySelector("#usuario");
let usuarioFeedback = document.querySelector("#usuario-feedback");
let usuarioTooltip = document.querySelector("#usuario-tooltip");
let usuarioValid = false;
let password = document.querySelector("#password");
let passwordFeedback = document.querySelector("#password-feedback");
let passwordTooltip = document.querySelector("#password-tooltip");
let passwordValid = false;
let passwordConfirm = document.querySelector("#password_confirm");
let passwordConfirmFeedback = document.querySelector("#password_confirm-feedback");
let passwordConfirmTooltip = document.querySelector("#password_confirm-tooltip");
let passwordConfirmValid = false;
let checkboxs = document.querySelectorAll('input[type="checkbox"]');
let modulos = [];
let btnCancelar = document.querySelector("#btn-cancelar");
let btnSubmit = document.querySelector("#btn-submit");

idEmpleado.addEventListener("change", e => {
    idEmpleado.classList.remove('is-valid', 'is-invalid');
    idEmpleadoFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    idEmpleadoFeedback.innerHTML = '&nbsp;';
    idEmpleadoValid = false;
    idEmpleadoTooltip.style.display = 'none';

    if (parseInt(idEmpleado.value) !== 0) {
        idEmpleado.classList.add('is-valid');
        idEmpleadoFeedback.classList.add('valid-feedback');
        idEmpleadoFeedback.innerHTML = 'Parece correcto';
        idEmpleadoValid = true;
    }
});

usuario.addEventListener("keyup", e => {
    usuario.classList.remove('is-valid', 'is-invalid');
    usuarioFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    usuarioFeedback.innerHTML = '&nbsp;';
    usuarioValid = false;
    usuarioTooltip.style.display = 'none';

    if (usuario.value.trim().length === 0) {
        usuario.classList.remove('is-valid', 'is-invalid');
        usuarioFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        usuarioFeedback.innerHTML = '&nbsp;';
        usuarioValid = false;
    } else {
        if (usuario.value.trim().length < 7) {
            usuario.classList.add('is-invalid');
            usuarioFeedback.classList.add('invalid-feedback');
            usuarioFeedback.innerHTML = `Te faltan ${7 - usuario.value.trim().length} carácteres`;
            usuarioValid = false;
        } else {
            if (usuario.value.trim().length > 10) {
                usuario.classList.add('is-invalid');
                usuarioFeedback.classList.add('invalid-feedback');
                usuarioFeedback.innerHTML = `Te sobran ${usuario.value.trim().length - 10} carácteres`;
                usuarioValid = false;
            } else {
                usuario.classList.add('is-valid');
                usuarioFeedback.classList.add('valid-feedback');
                usuarioFeedback.innerHTML = `Parece correcto`;
                usuarioValid = true;
            }
        }
    }
});

password.addEventListener("keyup", e => {
    password.classList.remove('is-valid', 'is-invalid');
    passwordFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    passwordFeedback.innerHTML = '&nbsp;';
    passwordValid = false;
    passwordTooltip.style.display = 'none';

    if (password.value.trim().length === 0) {
        password.classList.remove('is-valid', 'is-invalid');
        passwordFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        passwordFeedback.innerHTML = '&nbsp;';
        passwordValid = false;
    } else {
        if (password.value.trim().length < 7) {
            password.classList.add('is-invalid');
            passwordFeedback.classList.add('invalid-feedback');
            passwordFeedback.innerHTML = `Te faltan ${7 - password.value.trim().length} carácteres`;
            passwordValid = false;
        } else {
            if (password.value.trim().length > 10) {
                password.classList.add('is-invalid');
                passwordFeedback.classList.add('invalid-feedback');
                passwordFeedback.innerHTML = `Te sobran ${password.value.trim().length - 10} carácteres`;
                passwordValid = false;
            } else {
                password.classList.add('is-valid');
                passwordFeedback.classList.add('valid-feedback');
                passwordFeedback.innerHTML = `Parece correcto`;
                passwordValid = true;
            }
        }
    }
});

passwordConfirm.addEventListener("keyup", e => {
    passwordConfirm.classList.remove('is-valid', 'is-invalid');
    passwordConfirmFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    passwordConfirmFeedback.innerHTML = '&nbsp;';
    passwordConfirmValid = false;
    passwordConfirmTooltip.style.display = 'none';

    if (passwordConfirm.value.trim().length === 0) {
        passwordConfirm.classList.remove('is-valid', 'is-invalid');
        passwordConfirmFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        passwordConfirmFeedback.innerHTML = '&nbsp;';
        passwordConfirmValid = false;
    } else {
        if (passwordConfirm.value.trim().length < 7) {
            passwordConfirm.classList.add('is-invalid');
            passwordConfirmFeedback.classList.add('invalid-feedback');
            passwordConfirmFeedback.innerHTML = `Te faltan ${7 - passwordConfirm.value.trim().length} carácteres`;
            passwordConfirmValid = false;
        } else {
            if (passwordConfirm.value.trim().length > 10) {
                passwordConfirm.classList.add('is-invalid');
                passwordConfirmFeedback.classList.add('invalid-feedback');
                passwordConfirmFeedback.innerHTML = `Te sobran ${passwordConfirm.value.trim().length - 10} carácteres`;
                passwordConfirmValid = false;
            } else {
                if (passwordConfirm.value.trim() !== password.value.trim()) {
                    passwordConfirm.classList.add('is-invalid');
                    passwordConfirmFeedback.classList.add('invalid-feedback');
                    passwordConfirmFeedback.innerHTML = `¡Las contraseñas no coinciden!`;
                    passwordConfirmValid = false;
                } else {
                    passwordConfirm.classList.add('is-valid');
                    passwordConfirmFeedback.classList.add('valid-feedback');
                    passwordConfirmFeedback.innerHTML = `Parece correcto`;
                    passwordConfirmValid = true;
                }
            }
        }
    }
});

btnCancelar.addEventListener("click", () => {
    idEmpleado.value = 0;
    idEmpleado.classList.remove('is-valid', 'is-invalid');
    idEmpleadoFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    idEmpleadoFeedback.innerHTML = '&nbsp;';
    idEmpleadoValid = false;
    idEmpleadoTooltip.style.display = 'none';

    usuario.value = '';
    usuario.classList.remove('is-valid', 'is-invalid');
    usuarioFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    usuarioFeedback.innerHTML = '&nbsp;';
    usuarioValid = false;
    usuarioTooltip.style.display = 'none';

    password.value = '';
    password.classList.remove('is-valid', 'is-invalid');
    passwordFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    passwordFeedback.innerHTML = '&nbsp;';
    passwordValid = false;
    passwordTooltip.style.display = 'none';

    passwordConfirm.value = '';
    passwordConfirm.classList.remove('is-valid', 'is-invalid');
    passwordConfirmFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    passwordConfirmFeedback.innerHTML = '&nbsp;';
    passwordConfirmValid = false;
    passwordConfirmTooltip.style.display = 'none';

    checkboxs.forEach(i => {
        if (!i.getAttribute("disabled")) {
            if (i.checked) {
                i.checked = false;
            }
        }
    });
});

f.addEventListener("submit", e => {
    e.preventDefault();

    if (!idEmpleadoValid) {
        idEmpleado.focus();
        idEmpleadoTooltip.style.display = 'block';
        idEmpleadoTooltip.innerHTML = `Debes seleccionar un empleado.`;
        return;
    }

    if (!usuarioValid) {
        usuario.focus();
        usuarioTooltip.style.display = 'block';
        usuarioTooltip.innerHTML = `Debes llenar este campo como se solicita.`;
        return;
    }

    if (!passwordValid) {
        password.focus();
        passwordTooltip.style.display = 'block';
        passwordTooltip.innerHTML = `Debes llenar este campo como se solicita.`;
        return;
    }

    if (!passwordConfirmValid) {
        passwordConfirm.focus();
        passwordConfirmTooltip.style.display = 'block';
        passwordConfirmTooltip.innerHTML = `El llenado de este campo debe coincidir con el de la contraseña.`;
        return;
    }

    checkboxs.forEach(i => {
        if (i.checked) modulos.push(parseInt(i.value));
    });

    // DESHABILITAR EL BOTÓN DE SUBMIR
    btnSubmit.disabled = true;

    // MOSTRAR EL MODAL DE LOADING
    document.querySelector("#modal-loading").style.display = "flex";


    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function(token) {
        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('action', action.value);
        formData.append('csrf', csrf.value);
        formData.append('id', parseInt(idEmpleado.value));
        formData.append('usuario', usuario.value.trim());
        formData.append('password', password.value.trim());
        formData.append('passwordConfirm', passwordConfirm.value.trim());
        formData.append('modulos', modulos);
        formData.append('token', token);

        xhttp.onreadystatechange = function() {
            if (xhttp.readyState === 4 && xhttp.status === 200)
            {
                // HABILITAR EL BOTÓN DE SUBMIR
                btnSubmit.disabled = false;
                // LIMPIAR EL FORMULARIO
                idEmpleado.value = 0;
                idEmpleado.classList.remove('is-valid', 'is-invalid');
                idEmpleadoFeedback.classList.remove('valid-feedback', 'invalid-feedback');
                idEmpleadoFeedback.innerHTML = '&nbsp;';
                idEmpleadoTooltip.style.display = 'none';
                usuario.value = '';
                usuario.classList.remove('is-valid', 'is-invalid');
                usuarioFeedback.classList.remove('valid-feedback', 'invalid-feedback');
                usuarioFeedback.innerHTML = '&nbsp;';
                usuarioTooltip.style.display = 'none';
                password.value = '';
                password.classList.remove('is-valid', 'is-invalid');
                passwordFeedback.classList.remove('valid-feedback', 'invalid-feedback');
                passwordFeedback.innerHTML = '&nbsp;';
                passwordTooltip.style.display = 'none';
                passwordConfirm.value = '';
                passwordConfirm.classList.remove('is-valid', 'is-invalid');
                passwordConfirmFeedback.classList.remove('valid-feedback', 'invalid-feedback');
                passwordConfirmFeedback.innerHTML = '&nbsp;';
                passwordConfirmTooltip.style.display = 'none';
                // OCULTAR EL MODAL DE LOADING
                document.querySelector("#modal-loading").style.display = "none";
                // MOSTRAR EL MODAL DE INFO
                document.querySelector("#modal-info").style.display = "flex";
                let response = JSON.parse(xhttp.responseText);
                document.querySelector("#modal-info-message").innerHTML = response["message"];

                window.addEventListener("click", e => {
                    location.reload();
                });
            }
            else
            {
                if (xhttp.status === 400)
                {
                    // HABILITAR EL BOTÓN DE SUBMIR
                    btnSubmit.disabled = false;
                    // OCULTAR EL MODAL DE LOADING
                    document.querySelector("#modal-loading").style.display = "none";
                    // MOSTRAR EL MODAL DE ERROR
                    document.querySelector("#modal-error").style.display = "flex";
                    let response = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-error-message").innerHTML = response["message"];
                }
            }
        }
        xhttp.open('POST', url.value + '/usuarios/guardar', true);
        xhttp.send(formData);
    });
});