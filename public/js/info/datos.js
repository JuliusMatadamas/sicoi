let entorno = document.querySelector("#entorno");
let formDatos = document.querySelector("#form-datos");
let id = document.querySelector("#id");
let csrf = document.querySelector("#csrf");
let idUsuario = document.querySelector("#id_usuario");
let validCsrf = false;
let codigoPostal = document.querySelector("#codigo_postal");
let codigoPostalFeedback = document.querySelector("#feedback-codigo_postal");
let codigoPostalTooltip = document.querySelector("#tooltip-codigo_postal");
let codigoPostalValid = true;

let idColonia = document.querySelector("#id_colonia");
let idColoniaFeedback = document.querySelector("#feedback-id_colonia");
let idColoniaTooltip = document.querySelector("#tooltip-id_colonia");
let idColoniaValid = true;

let calle = document.querySelector("#calle");
let calleFeedback = document.querySelector("#feedback-calle");
let calleTooltip = document.querySelector("#tooltip-calle");
let calleValid = true;

let numeroExterior = document.querySelector("#numero_exterior");
let numeroExteriorFeedback = document.querySelector("#feedback-numero_exterior");
let numeroExteriorTooltip = document.querySelector("#tooltip-numero_exterior");
let numeroExteriorValid = true;

let numeroInterior = document.querySelector("#numero_interior");
let numeroInteriorFeedback = document.querySelector("#feedback-numero_interior");
let numeroInteriorTooltip = document.querySelector("#tooltip-numero_interior");
let numeroInteriorValid = true;

let email = document.querySelector("#email");
let emailFeedback = document.querySelector("#feedback-email");
let emailTooltip = document.querySelector("#tooltip-email");
let emailValid = true;

let telefonoCasa = document.querySelector("#telefono_casa");
let telefonoCasaFeedback = document.querySelector("#feedback-telefono_casa");
let telefonoCasaTooltip = document.querySelector("#tooltip-telefono_casa");
let telefonoCasaValid = true;

let telefonoCelular = document.querySelector("#telefono_celular");
let telefonoCelularFeedback = document.querySelector("#feedback-telefono_celular");
let telefonoCelularTooltip = document.querySelector("#tooltip-telefono_celular");
let telefonoCelularValid = true;

let btnCancelar = document.querySelector('#btn-cancelar');
let btnSubmit = document.querySelector('button[type="submit"]');

btnCancelar.addEventListener("click", () => {
    window.location = entorno.value + '/info';
});

codigoPostal.addEventListener("keyup", e => {
    codigoPostal.classList.remove('is-valid', 'is-invalid');
    codigoPostalFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    codigoPostalFeedback.innerHTML = '&nbsp;';
    codigoPostalTooltip.style.display = 'none';
    codigoPostalValid = false;

    if (codigoPostal.value.trim().length === 0) {
        codigoPostal.classList.remove('is-valid', 'is-invalid');
        codigoPostalFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        codigoPostalFeedback.innerHTML = '&nbsp;';
        codigoPostalTooltip.style.display = 'none';
        codigoPostalValid = false;
        idColonia.innerHTML = '';
        return;
    }

    if (codigoPostal.value.trim().length < 5) {
        codigoPostal.classList.add('is-invalid');
        codigoPostalFeedback.classList.add('invalid-feedback');
        codigoPostalFeedback.innerHTML = `¡Te faltan ${5 - codigoPostal.value.trim().length} dígitos!`;
        codigoPostalTooltip.style.display = 'none';
        codigoPostalValid = false;
        idColonia.innerHTML = '';
        return;
    }

    if (codigoPostal.value.trim().length > 5) {
        codigoPostal.classList.add('is-invalid');
        codigoPostalFeedback.classList.add('invalid-feedback');
        codigoPostalFeedback.innerHTML = `¡Te sobran ${codigoPostal.value.trim().length - 5} dígitos!`;
        codigoPostalTooltip.style.display = 'none';
        codigoPostalValid = false;
        idColonia.innerHTML = '';
        return;
    }

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function (token) {
        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('id', id.value);
        formData.append('csrf', csrf.value);
        formData.append('codigo_postal', codigoPostal.value);
        formData.append('token', token);

        xhttp.onreadystatechange = function () {
            if (xhttp.readyState === 4 && xhttp.status === 200) {
                // SI EL SERVIDOR ENVÍO RESPUESTA
                let res = JSON.parse(xhttp.responseText);
                let data = JSON.parse(res["data"]);
                // SI LA RESPUESTA NO CONTIENE DATOS
                if (Object.keys(data).length === 0) {
                    codigoPostalValid = false;
                    codigoPostalTooltip.innerHTML = "Parece que el código postal no corresponde a Matamoros.";
                    codigoPostalTooltip.style.display = "block";
                    codigoPostal.focus();
                    return
                }
                // SI LA RESPUESTA CONTIENE DATOS
                codigoPostalValid = true;
                let o = `<option value="0">Seleccione...</option>`;
                for (let i = 0; i < Object.keys(data).length; i++) {
                    o += `<option value="${data[i]["id"]}">${data[i]["colonia"]}</option>`;
                }
                idColonia.innerHTML = o;
                validCsrf = true;
            } else {
                if (xhttp.status === 400) {
                    let response = JSON.parse(xhttp.responseText);
                    codigoPostalValid = false;
                    idColonia.innerHTML = `<option value="0">${response["message"]}</option>`;
                    response["message"].includes("csrf") ? validCsrf = false : validCsrf = true;
                }
            }
        }
        xhttp.open('POST', entorno.value + '/api/colonias', true);
        xhttp.send(formData);
    });
});

idColonia.addEventListener("change", e => {
    idColonia.classList.remove('is-valid', 'is-invalid');
    idColoniaFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    idColoniaFeedback.innerHTML = '&nbsp;';
    idColoniaTooltip.style.display = 'none';
    idColoniaValid = false;

    if (parseInt(idColonia.value) === 0) {
        idColonia.classList.remove('is-valid', 'is-invalid');
        idColoniaFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        idColoniaFeedback.innerHTML = '&nbsp;';
        idColoniaTooltip.style.display = 'none';
        idColoniaValid = false;
        return;
    } else {
        idColonia.classList.add('is-valid');
        idColoniaFeedback.classList.add('valid-feedback');
        idColoniaFeedback.innerHTML = 'Parece correcto';
        idColoniaTooltip.style.display = 'none';
        idColoniaValid = true;
    }
});

calle.addEventListener("keyup", e => {
    calle.classList.remove('is-valid', 'is-invalid');
    calleFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    calleFeedback.innerHTML = '&nbsp;';
    calleTooltip.style.display = 'none';
    calleValid = false;

    if (calle.value.trim().length === 0) {
        calle.classList.remove('is-valid', 'is-invalid');
        calleFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        calleFeedback.innerHTML = '&nbsp;';
        calleTooltip.style.display = 'none';
        calleValid = false;
        return;
    }

    calle.classList.add('is-valid');
    calleFeedback.classList.add('valid-feedback');
    calleFeedback.innerHTML = 'Parece correcto';
    calleTooltip.style.display = 'none';
    calleValid = true;
});

numeroExterior.addEventListener("keyup", e => {
    numeroExterior.classList.remove('is-valid', 'is-invalid');
    numeroExteriorFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    numeroExteriorFeedback.innerHTML = '&nbsp;';
    numeroExteriorTooltip.style.display = 'none';
    numeroExteriorValid = false;

    if (numeroExterior.value.trim().length === 0) {
        numeroExterior.classList.remove('is-valid', 'is-invalid');
        numeroExteriorFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        numeroExteriorFeedback.innerHTML = '&nbsp;';
        numeroExteriorTooltip.style.display = 'none';
        numeroExteriorValid = false;
        return;
    }

    if (!soloLetrasConNumeros(numeroExterior.value.trim())) {
        numeroExterior.classList.add('is-invalid');
        numeroExteriorFeedback.classList.add('invalid-feedback');
        numeroExteriorFeedback.innerHTML = 'Solo números y letras';
        numeroExteriorTooltip.style.display = 'none';
        numeroExteriorValid = false;
        return;
    }

    numeroExterior.classList.add('is-valid');
    numeroExteriorFeedback.classList.add('valid-feedback');
    numeroExteriorFeedback.innerHTML = 'Parece correcto';
    numeroExteriorTooltip.style.display = 'none';
    numeroExteriorValid = true;
});

numeroInterior.addEventListener("keyup", e => {
    numeroInterior.classList.remove('is-valid', 'is-invalid');
    numeroInteriorFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    numeroInteriorFeedback.innerHTML = '&nbsp;';
    numeroInteriorTooltip.style.display = 'none';
    numeroInteriorValid = true;

    if (numeroInterior.value.trim().length === 0) {
        numeroInterior.classList.remove('is-valid', 'is-invalid');
        numeroInteriorFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        numeroInteriorFeedback.innerHTML = '&nbsp;';
        numeroInteriorTooltip.style.display = 'none';
        numeroInteriorValid = true;
        return;
    }

    if (!soloLetrasConNumeros(numeroInterior.value.trim())) {
        numeroInterior.classList.add('is-invalid');
        numeroInteriorFeedback.classList.add('invalid-feedback');
        numeroInteriorFeedback.innerHTML = 'Solo números y letras';
        numeroInteriorTooltip.style.display = 'none';
        numeroInteriorValid = false;
        return;
    }

    numeroInterior.classList.add('is-valid');
    numeroInteriorFeedback.classList.add('valid-feedback');
    numeroInteriorFeedback.innerHTML = 'Parece correcto';
    numeroInteriorTooltip.style.display = 'none';
    numeroInteriorValid = true;
});

email.addEventListener("keyup", e => {
    email.classList.remove('is-valid', 'is-invalid');
    emailFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    emailFeedback.innerHTML = '&nbsp;';
    emailTooltip.style.display = 'none';
    emailValid = true;

    if (email.value.trim().length === 0) {
        email.classList.remove('is-valid', 'is-invalid');
        emailFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        emailFeedback.innerHTML = '&nbsp;';
        emailTooltip.style.display = 'none';
        emailValid = true;
        return;
    }

    if (!isValidEmail(email.value.trim())) {
        email.classList.add('is-invalid');
        emailFeedback.classList.add('invalid-feedback');
        emailFeedback.innerHTML = 'Ingresar una dirección de correo electrónico válida';
        emailTooltip.style.display = 'none';
        emailValid = false;
        return;
    }

    email.classList.add('is-valid');
    emailFeedback.classList.add('valid-feedback');
    emailFeedback.innerHTML = 'Parece correcto';
    emailTooltip.style.display = 'none';
    emailValid = true;
});

telefonoCasa.addEventListener("keyup", e => {
    telefonoCasa.classList.remove('is-valid', 'is-invalid');
    telefonoCasaFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    telefonoCasaFeedback.innerHTML = '&nbsp;';
    telefonoCasaTooltip.style.display = 'none';
    telefonoCasaValid = false;

    if (telefonoCasa.value.trim().length === 0) {
        telefonoCasa.classList.remove('is-valid', 'is-invalid');
        telefonoCasaFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        telefonoCasaFeedback.innerHTML = '&nbsp;';
        telefonoCasaTooltip.style.display = 'none';
        telefonoCasaValid = false;
        return;
    }

    if (!soloNumeros(telefonoCasa.value.trim())) {
        telefonoCasa.classList.add('is-invalid');
        telefonoCasaFeedback.classList.add('invalid-feedback');
        telefonoCasaFeedback.innerHTML = '¡Ingresa solo números!';
        telefonoCasaTooltip.style.display = 'none';
        telefonoCasaValid = false;
        return;
    }

    if (telefonoCasa.value.trim().length < 10) {
        telefonoCasa.classList.add('is-invalid');
        telefonoCasaFeedback.classList.add('invalid-feedback');
        telefonoCasaFeedback.innerHTML = `Te faltan ${10 - telefonoCasa.value.trim().length} dígitos`;
        telefonoCasaTooltip.style.display = 'none';
        telefonoCasaValid = false;
        return;
    }

    if (telefonoCasa.value.trim().length > 10) {
        telefonoCasa.classList.add('is-invalid');
        telefonoCasaFeedback.classList.add('invalid-feedback');
        telefonoCasaFeedback.innerHTML = `Te sobran ${telefonoCasa.value.trim().length - 10} dígitos`;
        telefonoCasaTooltip.style.display = 'none';
        telefonoCasaValid = false;
        return;
    }

    telefonoCasa.classList.add('is-valid');
    telefonoCasaFeedback.classList.add('valid-feedback');
    telefonoCasaFeedback.innerHTML = 'Parece correcto';
    telefonoCasaTooltip.style.display = 'none';
    telefonoCasaValid = true;
});

telefonoCelular.addEventListener("keyup", e => {
    telefonoCelular.classList.remove('is-valid', 'is-invalid');
    telefonoCelularFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    telefonoCelularFeedback.innerHTML = '&nbsp;';
    telefonoCelularTooltip.style.display = 'none';
    telefonoCelularValid = false;

    if (telefonoCelular.value.trim().length === 0) {
        telefonoCelular.classList.remove('is-valid', 'is-invalid');
        telefonoCelularFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        telefonoCelularFeedback.innerHTML = '&nbsp;';
        telefonoCelularTooltip.style.display = 'none';
        telefonoCelularValid = false;
        return;
    }

    if (!soloNumeros(telefonoCelular.value.trim())) {
        telefonoCelular.classList.add('is-invalid');
        telefonoCelularFeedback.classList.add('invalid-feedback');
        telefonoCelularFeedback.innerHTML = '¡Ingresa solo números!';
        telefonoCelularTooltip.style.display = 'none';
        telefonoCelularValid = false;
        return;
    }

    if (telefonoCelular.value.trim().length < 10) {
        telefonoCelular.classList.add('is-invalid');
        telefonoCelularFeedback.classList.add('invalid-feedback');
        telefonoCelularFeedback.innerHTML = `Te faltan ${10 - telefonoCelular.value.trim().length} dígitos`;
        telefonoCelularTooltip.style.display = 'none';
        telefonoCelularValid = false;
        return;
    }

    if (telefonoCelular.value.trim().length > 10) {
        telefonoCelular.classList.add('is-invalid');
        telefonoCelularFeedback.classList.add('invalid-feedback');
        telefonoCelularFeedback.innerHTML = `Te sobran ${telefonoCelular.value.trim().length - 10} dígitos`;
        telefonoCelularTooltip.style.display = 'none';
        telefonoCelularValid = false;
        return;
    }

    telefonoCelular.classList.add('is-valid');
    telefonoCelularFeedback.classList.add('valid-feedback');
    telefonoCelularFeedback.innerHTML = 'Parece correcto';
    telefonoCelularTooltip.style.display = 'none';
    telefonoCelularValid = true;
});

formDatos.addEventListener("submit", e => {
    e.preventDefault();

    if (!codigoPostalValid) {
        codigoPostal.focus();
        codigoPostalTooltip.style.display = 'block';
        codigoPostalTooltip.innerHTML = `Completa esta campo como se solicita.`;
        return;
    }

    if (!idColonia) {
        if (!validCsrf) {
            idColonia.focus();
            idColoniaTooltip.style.display = 'block';
            idColoniaTooltip.innerHTML = `Para poder llenar esta campo se requiere cerrar y volver a iniciar la sesión.`;
            return;
        } else {
            idColonia.focus();
            idColoniaTooltip.style.display = 'block';
            idColoniaTooltip.innerHTML = `Se debe seleccionar un item de la lista.`;
            return;
        }
    }

    if (!idColoniaValid) {
        idColonia.focus();
        idColoniaTooltip.style.display = 'block';
        idColoniaTooltip.innerHTML = `Se debe seleccionar un item de la lista.`;
        return;
    }

    if (!calleValid) {
        calle.focus();
        calleTooltip.style.display = 'block';
        calleTooltip.innerHTML = `Completa esta campo como se solicita.`;
        return;
    }

    if (!numeroExteriorValid) {
        numeroExterior.focus();
        numeroExteriorTooltip.style.display = 'block';
        numeroExteriorTooltip.innerHTML = `Completa esta campo como se solicita.`;
        return;
    }

    if (!numeroInteriorValid) {
        numeroInterior.focus();
        numeroInteriorTooltip.style.display = 'block';
        numeroInteriorTooltip.innerHTML = `Completa esta campo como se solicita.`;
        return;
    }

    if (!emailValid) {
        email.focus();
        emailTooltip.style.display = 'block';
        emailTooltip.innerHTML = `Completa esta campo como se solicita.`;
        return;
    }

    if (!telefonoCasaValid) {
        telefonoCasa.focus();
        telefonoCasaTooltip.style.display = 'block';
        telefonoCasaTooltip.innerHTML = `Completa esta campo como se solicita.`;
        return;
    }

    if (!telefonoCelularValid) {
        telefonoCelular.focus();
        telefonoCelularTooltip.style.display = 'block';
        telefonoCelularTooltip.innerHTML = `Completa esta campo como se solicita.`;
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
        formData.append('id', id.value);
        formData.append('csrf', csrf.value);
        formData.append('id_usuario', idUsuario.value);
        formData.append('id_colonia', idColonia.value);
        formData.append('calle', calle.value);
        formData.append('numero_exterior', numeroExterior.value);
        formData.append('numero_interior', numeroInterior.value);
        formData.append('email', email.value);
        formData.append('telefono_casa', telefonoCasa.value);
        formData.append('telefono_celular', telefonoCelular.value);
        formData.append('token', token);

        xhttp.onreadystatechange = function () {
            if (xhttp.readyState === 4 && xhttp.status === 200) {
                btnSubmit.disabled = false;
                document.querySelector("#modal-loading").style.display = 'none';

                let response = JSON.parse(xhttp.responseText);
                console.log(response);
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
        xhttp.open('POST', formDatos.getAttribute('action'), true);
        xhttp.send(formData);
    });
});
