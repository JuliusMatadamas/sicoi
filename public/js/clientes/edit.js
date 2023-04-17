let entorno = document.querySelector("#entorno");
let csrf = document.querySelector("#csrf");
let idUsuario = document.querySelector("#id_usuario");
let idCliente = document.querySelector("#id_cliente");
let form = document.querySelector("#form-editar_cliente");
let nombre = document.querySelector("#nombre");
let nombreFeedback = document.querySelector("#nombre-feedback");
let nombreTooltip = document.querySelector("#nombre-tooltip");
let nombreValid = true;
let apellidoPaterno = document.querySelector("#apellido_paterno");
let apellidoPaternoFeedback = document.querySelector("#apellido_paterno-feedback");
let apellidoPaternoTooltip = document.querySelector("#apellido_paterno-tooltip");
let apellidoPaternoValid = true;
let apellidoMaterno = document.querySelector("#apellido_materno");
let apellidoMaternoFeedback = document.querySelector("#apellido_materno-feedback");
let apellidoMaternoTooltip = document.querySelector("#apellido_materno-tooltip");
let apellidoMaternoValid = true;
let fechaNacimiento = document.querySelector("#fecha_nacimiento");
let fechaNacimientoFeedback = document.querySelector("#fecha_nacimiento-feedback");
let fechaNacimientoTooltip = document.querySelector("#fecha_nacimiento-tooltip");
let fechaNacimientoValid = true;
let idGenero = document.querySelector("#id_genero");
let idGeneroFeedback = document.querySelector("#id_genero-feedback");
let idGeneroTooltip = document.querySelector("#id_genero-tooltip");
let idGeneroValid = true;
let telefonoCasa = document.querySelector("#telefono_casa");
let telefonoCasaFeedback = document.querySelector("#telefono_casa-feedback");
let telefonoCasaTooltip = document.querySelector("#telefono_casa-tooltip");
let telefonoCasaValid = true;
let telefonoCelular = document.querySelector("#telefono_celular");
let telefonoCelularFeedback = document.querySelector("#telefono_celular-feedback");
let telefonoCelularTooltip = document.querySelector("#telefono_celular-tooltip");
let telefonoCelularValid = true;
let email = document.querySelector("#email");
let emailFeedback = document.querySelector("#email-feedback");
let emailTooltip = document.querySelector("#email-tooltip");
let emailValid = true;
let codigoPostal = document.querySelector("#codigo_postal");
let codigoPostalFeedback = document.querySelector("#codigo_postal-feedback");
let codigoPostalTooltip = document.querySelector("#codigo_postal-tooltip");
let idColonia = document.querySelector("#id_colonia");
let idColoniaFeedback = document.querySelector("#id_colonia-feedback");
let idColoniaTooltip = document.querySelector("#id_colonia-tooltip");
let idColoniaValid = true;
let rfc = document.querySelector("#rfc");
let rfcFeedback = document.querySelector("#rfc-feedback");
let rfcTooltip = document.querySelector("#rfc-tooltip");
let rfcValid = true;
let calle = document.querySelector("#calle");
let calleFeedback = document.querySelector("#calle-feedback");
let calleTooltip = document.querySelector("#calle-tooltip");
let calleValid = true;
let numeroExterior = document.querySelector("#numero_exterior");
let numeroExteriorFeedback = document.querySelector("#numero_exterior-feedback");
let numeroExteriorTooltip = document.querySelector("#numero_exterior-tooltip");
let numeroExteriorValid = true;
let numeroInterior = document.querySelector("#numero_interior");
let numeroInteriorFeedback = document.querySelector("#numero_interior-feedback");
let numeroInteriorTooltip = document.querySelector("#numero_interior-tooltip");
let numeroInteriorValid = true;
let observaciones = document.querySelector("#observaciones");
let observacionesFeedback = document.querySelector("#observaciones-feedback");
let observacionesTooltip = document.querySelector("#observaciones-tooltip");
let observacionesValid = true;
let btnCancelar = document.querySelector("#btn-cancelar");
let btnSubmit = document.querySelector('button[type="submit"]');

nombre.addEventListener("keyup", e => {
    nombre.classList.remove('is-valid', 'is-invalid');
    nombreFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    nombreFeedback.innerHTML = "&nbsp;";
    nombreTooltip.style.display = "none";
    nombreTooltip.innerHTML = "&nbsp;";

    if (nombre.value.trim().length === 0) {
        nombre.classList.remove('is-valid', 'is-invalid');
        nombreFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        nombreFeedback.innerHTML = "&nbsp;";
        nombreTooltip.style.display = "none";
        nombreTooltip.innerHTML = "&nbsp;";
        nombreValid = false;
        return;
    }

    if (!soloLetrasEspaciosAcentos(nombre.value.trim())) {
        nombre.classList.add('is-invalid');
        nombreFeedback.classList.add('invalid-feedback');
        nombreFeedback.innerHTML = "Ingrese solo letras, acentos y/o espacios.";
        nombreValid = false;
        return;
    }

    if (nombre.value.trim().length < 2) {
        nombre.classList.add('is-invalid');
        nombreFeedback.classList.add('invalid-feedback');
        nombreFeedback.innerHTML = `Te faltan ${2 - nombre.value.trim().length} caracteres.`;
        nombreTooltip.style.display = "none";
        nombreTooltip.innerHTML = "&nbsp;";
        nombreValid = false;
        return;
    }

    if (nombre.value.trim().length > 40) {
        nombre.classList.add('is-invalid');
        nombreFeedback.classList.add('invalid-feedback');
        nombreFeedback.innerHTML = `Te sobran ${nombre.value.trim().length - 40} caracteres.`;
        nombreTooltip.style.display = "none";
        nombreTooltip.innerHTML = "&nbsp;";
        nombreValid = false;
        return;
    }

    nombre.classList.add('is-valid');
    nombreFeedback.classList.add('valid-feedback');
    nombreFeedback.innerHTML = `Parece correcto`;
    nombreTooltip.style.display = "none";
    nombreTooltip.innerHTML = "&nbsp;";
    nombreValid = true;
});

apellidoPaterno.addEventListener("keyup", e => {
    apellidoPaterno.classList.remove('is-valid', 'is-invalid');
    apellidoPaternoFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    apellidoPaternoFeedback.innerHTML = "&nbsp;";
    apellidoPaternoTooltip.style.display = "none";
    apellidoPaternoTooltip.innerHTML = "&nbsp;";

    if (apellidoPaterno.value.trim().length === 0) {
        apellidoPaterno.classList.remove('is-valid', 'is-invalid');
        apellidoPaternoFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        apellidoPaternoFeedback.innerHTML = "&nbsp;";
        apellidoPaternoTooltip.style.display = "none";
        apellidoPaternoTooltip.innerHTML = "&nbsp;";
        apellidoPaternoValid = false;
        return;
    }

    if (!soloLetrasEspaciosAcentos(apellidoPaterno.value.trim())) {
        apellidoPaterno.classList.add('is-invalid');
        apellidoPaternoFeedback.classList.add('invalid-feedback');
        apellidoPaternoFeedback.innerHTML = "Ingrese solo letras, acentos y/o espacios.";
        apellidoPaternoValid = false;
        return;
    }

    if (apellidoPaterno.value.trim().length < 2) {
        apellidoPaterno.classList.add('is-invalid');
        apellidoPaternoFeedback.classList.add('invalid-feedback');
        apellidoPaternoFeedback.innerHTML = `Te faltan ${2 - apellidoPaterno.value.trim().length} caracteres.`;
        apellidoPaternoTooltip.style.display = "none";
        apellidoPaternoTooltip.innerHTML = "&nbsp;";
        apellidoPaternoValid = false;
        return;
    }

    if (apellidoPaterno.value.trim().length > 40) {
        apellidoPaterno.classList.add('is-invalid');
        apellidoPaternoFeedback.classList.add('invalid-feedback');
        apellidoPaternoFeedback.innerHTML = `Te sobran ${apellidoPaterno.value.trim().length - 40} caracteres.`;
        apellidoPaternoTooltip.style.display = "none";
        apellidoPaternoTooltip.innerHTML = "&nbsp;";
        apellidoPaternoValid = false;
        return;
    }

    apellidoPaterno.classList.add('is-valid');
    apellidoPaternoFeedback.classList.add('valid-feedback');
    apellidoPaternoFeedback.innerHTML = `Parece correcto`;
    apellidoPaternoTooltip.style.display = "none";
    apellidoPaternoTooltip.innerHTML = "&nbsp;";
    apellidoPaternoValid = true;
});

apellidoMaterno.addEventListener("keyup", e => {
    apellidoMaterno.classList.remove('is-valid', 'is-invalid');
    apellidoMaternoFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    apellidoMaternoFeedback.innerHTML = "&nbsp;";
    apellidoMaternoTooltip.style.display = "none";
    apellidoMaternoTooltip.innerHTML = "&nbsp;";

    if (apellidoMaterno.value.trim().length === 0) {
        apellidoMaterno.classList.remove('is-valid', 'is-invalid');
        apellidoMaternoFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        apellidoMaternoFeedback.innerHTML = "&nbsp;";
        apellidoMaternoTooltip.style.display = "none";
        apellidoMaternoTooltip.innerHTML = "&nbsp;";
        apellidoMaternoValid = true;
        return;
    }

    if (apellidoMaterno.value.trim().length !== 0) {
        if (!soloLetrasEspaciosAcentos(apellidoMaterno.value.trim())) {
            apellidoMaterno.classList.add('is-invalid');
            apellidoMaternoFeedback.classList.add('invalid-feedback');
            apellidoMaternoFeedback.innerHTML = "Ingrese solo letras, acentos y/o espacios.";
            apellidoMaternoValid = false;
            return;
        }

        if (apellidoMaterno.value.trim().length < 2) {
            apellidoMaterno.classList.add('is-invalid');
            apellidoMaternoFeedback.classList.add('invalid-feedback');
            apellidoMaternoFeedback.innerHTML = `Te faltan ${2 - apellidoMaterno.value.trim().length} caracteres.`;
            apellidoMaternoTooltip.style.display = "none";
            apellidoMaternoTooltip.innerHTML = "&nbsp;";
            apellidoMaternoValid = false;
            return;
        }

        if (apellidoMaterno.value.trim().length > 40) {
            apellidoMaterno.classList.add('is-invalid');
            apellidoMaternoFeedback.classList.add('invalid-feedback');
            apellidoMaternoFeedback.innerHTML = `Te sobran ${apellidoMaterno.value.trim().length - 40} caracteres.`;
            apellidoMaternoTooltip.style.display = "none";
            apellidoMaternoTooltip.innerHTML = "&nbsp;";
            apellidoMaternoValid = false;
            return;
        }

        apellidoMaterno.classList.add('is-valid');
        apellidoMaternoFeedback.classList.add('valid-feedback');
        apellidoMaternoFeedback.innerHTML = `Parece correcto`;
        apellidoMaternoTooltip.style.display = "none";
        apellidoMaternoTooltip.innerHTML = "&nbsp;";
        apellidoMaternoValid = true;
    }
});

fechaNacimiento.addEventListener('keyup', evt => {
    validacionDeFecha();
});
fechaNacimiento.addEventListener('change', evt => {
    validacionDeFecha();
});
function validacionDeFecha(){
    fechaNacimiento.classList.remove('is-valid', 'is-invalid');
    fechaNacimientoFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    fechaNacimientoFeedback.innerHTML = "&nbsp;";
    fechaNacimientoTooltip.style.display = "none";
    fechaNacimientoTooltip.innerHTML = "&nbsp;";

    if (fechaNacimiento.value.trim().length === 0) {
        fechaNacimiento.classList.remove('is-valid', 'is-invalid');
        fechaNacimientoFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        fechaNacimientoFeedback.innerHTML = "&nbsp;";
        fechaNacimientoTooltip.style.display = "none";
        fechaNacimientoTooltip.innerHTML = "&nbsp;";
        fechaNacimientoValid = false;
    } else {
        if (!esFechaValida(fechaNacimiento.value.trim())) {
            fechaNacimiento.classList.add('is-invalid');
            fechaNacimientoFeedback.classList.add('invalid-feedback');
            fechaNacimientoFeedback.innerHTML = "La fecha ingresada no es valida.";
            fechaNacimientoTooltip.style.display = "none";
            fechaNacimientoTooltip.innerHTML = "&nbsp;";
            fechaNacimientoValid = false;
        } else {
            let el = fechaNacimiento.value.trim().split('-');
            let birthYear = new Date(parseInt(el[0]), parseInt(el[1]) - 1, parseInt(el[2])).getFullYear();
            let currentYear = new Date().getFullYear();

            if (isNaN(currentYear - birthYear)) {
                fechaNacimiento.classList.remove('is-valid', 'is-invalid');
                fechaNacimientoFeedback.classList.remove('valid-feedback', 'invalid-feedback');
                fechaNacimientoFeedback.innerHTML = "&nbsp;";
                fechaNacimientoTooltip.style.display = "none";
                fechaNacimientoTooltip.innerHTML = "&nbsp;";
                fechaNacimientoValid = false;
            } else {
                if ((currentYear - birthYear) < 18) {
                    fechaNacimiento.classList.add('is-invalid');
                    fechaNacimientoFeedback.classList.add('invalid-feedback');
                    fechaNacimientoFeedback.innerHTML = "El cliente debe ser mayor de edad.";
                    fechaNacimientoTooltip.style.display = "none";
                    fechaNacimientoTooltip.innerHTML = "&nbsp;";
                    fechaNacimientoValid = false;
                } else {
                    if ((currentYear - birthYear) >= 65) {
                        fechaNacimiento.classList.add('is-invalid');
                        fechaNacimientoFeedback.classList.add('invalid-feedback');
                        fechaNacimientoFeedback.innerHTML = "El cliente no debe tener de 65 años en adelante.";
                        fechaNacimientoTooltip.style.display = "none";
                        fechaNacimientoTooltip.innerHTML = "&nbsp;";
                        fechaNacimientoValid = false;
                    } else {
                        fechaNacimiento.classList.add('is-valid');
                        fechaNacimientoFeedback.classList.add('valid-feedback');
                        fechaNacimientoFeedback.innerHTML = "Parece correcta";
                        fechaNacimientoTooltip.style.display = "none";
                        fechaNacimientoTooltip.innerHTML = "&nbsp;";
                        fechaNacimientoValid = true;
                    }
                }
            }
        }
    }
}

idGenero.addEventListener("change", e => {
    idGenero.classList.remove('is-valid', 'is-invalid');
    idGeneroFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    idGeneroFeedback.innerHTML = `&nbsp;`;
    idGeneroTooltip.style.display = 'none';
    idGeneroTooltip.innerHTML = `&nbsp;`;

    if (parseInt(idGenero.value) === 0) {
        idGenero.classList.remove('is-valid', 'is-invalid');
        idGeneroFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        idGeneroFeedback.innerHTML = `&nbsp;`;
        idGeneroTooltip.style.display = 'none';
        idGeneroTooltip.innerHTML = `&nbsp;`;
        idGeneroValid = false;
    } else {
        idGenero.classList.add('is-valid');
        idGeneroFeedback.classList.add('valid-feedback');
        idGeneroFeedback.innerHTML = `Parece correcto`;
        idGeneroTooltip.style.display = 'none';
        idGeneroTooltip.innerHTML = `&nbsp;`;
        idGeneroValid = true;
    }
});

telefonoCasa.addEventListener("keyup", e => {
    telefonoCasa.classList.remove('is-valid', 'is-invalid');
    telefonoCelularFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    telefonoCasaFeedback.innerHTML = `&nbsp;`;
    telefonoCasaTooltip.style.display = 'none';
    telefonoCelularTooltip.innerHTML = `&nbsp;`;

    if (telefonoCasa.value.trim().length === 0) {
        telefonoCasa.classList.remove('is-valid', 'is-invalid');
        telefonoCelularFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        telefonoCasaFeedback.innerHTML = `&nbsp;`;
        telefonoCasaTooltip.style.display = 'none';
        telefonoCelularTooltip.innerHTML = `&nbsp;`;
        telefonoCasaValid = false;
    } else {
        if (!soloNumeros(telefonoCasa.value.trim())) {
            telefonoCasa.classList.add('is-invalid');
            telefonoCelularFeedback.classList.add('invalid-feedback');
            telefonoCasaFeedback.innerHTML = `Ingresa solo números`;
            telefonoCasaTooltip.style.display = 'none';
            telefonoCelularTooltip.innerHTML = `&nbsp;`;
            telefonoCasaValid = false;
        } else {
            if (telefonoCasa.value.trim().length < 10) {
                telefonoCasa.classList.add('is-invalid');
                telefonoCelularFeedback.classList.add('invalid-feedback');
                telefonoCasaFeedback.innerHTML = `Te faltan ${10 - telefonoCasa.value.trim().length} dígitos.`;
                telefonoCasaTooltip.style.display = 'none';
                telefonoCelularTooltip.innerHTML = `&nbsp;`;
                telefonoCasaValid = false;
                return;
            }

            if (telefonoCasa.value.trim().length > 10) {
                telefonoCasa.classList.add('is-invalid');
                telefonoCelularFeedback.classList.add('invalid-feedback');
                telefonoCasaFeedback.innerHTML = `Te sobran ${telefonoCasa.value.trim().length - 10} dígitos.`;
                telefonoCasaTooltip.style.display = 'none';
                telefonoCelularTooltip.innerHTML = `&nbsp;`;
                telefonoCasaValid = false;
                return;
            }

            telefonoCasa.classList.add('is-valid');
            telefonoCelularFeedback.classList.add('valid-feedback');
            telefonoCasaFeedback.innerHTML = `Parece correcto`;
            telefonoCasaTooltip.style.display = 'none';
            telefonoCelularTooltip.innerHTML = `&nbsp;`;
            telefonoCasaValid = true;
        }
    }
});

telefonoCelular.addEventListener("keyup", e => {
    telefonoCelular.classList.remove('is-valid', 'is-invalid');
    telefonoCelularFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    telefonoCelularFeedback.innerHTML = `&nbsp;`;
    telefonoCelularTooltip.style.display = 'none';
    telefonoCelularTooltip.innerHTML = `&nbsp;`;

    if (telefonoCelular.value.trim().length === 0) {
        telefonoCelular.classList.remove('is-valid', 'is-invalid');
        telefonoCelularFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        telefonoCelularFeedback.innerHTML = `&nbsp;`;
        telefonoCelularTooltip.style.display = 'none';
        telefonoCelularTooltip.innerHTML = `&nbsp;`;
        telefonoCelularValid = false;
    } else {
        if (!soloNumeros(telefonoCelular.value.trim())) {
            telefonoCelular.classList.add('is-invalid');
            telefonoCelularFeedback.classList.add('invalid-feedback');
            telefonoCelularFeedback.innerHTML = `Ingresa solo números`;
            telefonoCelularTooltip.style.display = 'none';
            telefonoCelularTooltip.innerHTML = `&nbsp;`;
            telefonoCelularValid = false;
        } else {
            if (telefonoCelular.value.trim().length < 10) {
                telefonoCelular.classList.add('is-invalid');
                telefonoCelularFeedback.classList.add('invalid-feedback');
                telefonoCelularFeedback.innerHTML = `Te faltan ${10 - telefonoCelular.value.trim().length} dígitos.`;
                telefonoCelularTooltip.style.display = 'none';
                telefonoCelularTooltip.innerHTML = `&nbsp;`;
                telefonoCelularValid = false;
                return;
            }

            if (telefonoCelular.value.trim().length > 10) {
                telefonoCelular.classList.add('is-invalid');
                telefonoCelularFeedback.classList.add('invalid-feedback');
                telefonoCelularFeedback.innerHTML = `Te sobran ${telefonoCelular.value.trim().length - 10} dígitos.`;
                telefonoCelularTooltip.style.display = 'none';
                telefonoCelularTooltip.innerHTML = `&nbsp;`;
                telefonoCelularValid = false;
                return;
            }

            telefonoCelular.classList.add('is-valid');
            telefonoCelularFeedback.classList.add('valid-feedback');
            telefonoCelularFeedback.innerHTML = `Parece correcto`;
            telefonoCelularTooltip.style.display = 'none';
            telefonoCelularTooltip.innerHTML = `&nbsp;`;
            telefonoCelularValid = true;
        }
    }
});

email.addEventListener("keyup", e => {
    email.classList.remove('is-valid', 'is-invalid');
    emailFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    emailFeedback.innerHTML = "&nbsp;";
    emailTooltip.style.display = "none";
    emailTooltip.innerHTML = "&nbsp;";

    if (email.value.trim().length === 0) {
        email.classList.remove('is-valid', 'is-invalid');
        emailFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        emailFeedback.innerHTML = "&nbsp;";
        emailTooltip.style.display = "none";
        emailTooltip.innerHTML = "&nbsp;";
        emailValid = false;
        return;
    }

    if (!isValidEmail(email.value.trim())) {
        email.classList.add('is-invalid');
        emailFeedback.classList.add('invalid-feedback');
        emailFeedback.innerHTML = `Ingrese una dirección de correo electrónico válida.`;
        emailTooltip.style.display = "none";
        emailTooltip.innerHTML = "&nbsp;";
        emailValid = false;
        return;
    }

    email.classList.add('is-valid');
    emailFeedback.classList.add('valid-feedback');
    emailFeedback.innerHTML = `Parece correcto`;
    emailTooltip.style.display = "none";
    emailTooltip.innerHTML = "&nbsp;";
    emailValid = true;
});

codigoPostal.addEventListener("keyup", e => {
    codigoPostal.classList.remove('is-valid', 'is-invalid');
    codigoPostalFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    codigoPostalFeedback.innerHTML = `&nbsp;`;
    codigoPostalTooltip.style.display = 'none';
    codigoPostalTooltip.innerHTML = `&nbsp;`;

    if (codigoPostal.value.trim().length === 0) {
        codigoPostal.classList.remove('is-valid', 'is-invalid');
        codigoPostalFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        codigoPostalFeedback.innerHTML = `&nbsp;`;
        codigoPostalTooltip.style.display = 'none';
        codigoPostalTooltip.innerHTML = `&nbsp;`;
        removerColonias();
        return;
    }

    if (!soloNumeros(codigoPostal.value.trim())) {
        codigoPostal.classList.add('is-invalid');
        codigoPostalFeedback.classList.add('invalid-feedback');
        codigoPostalFeedback.innerHTML = `Ingresa solo números.`;
        codigoPostalTooltip.style.display = 'none';
        codigoPostalTooltip.innerHTML = `&nbsp;`;
        removerColonias();
        return;
    }

    if (codigoPostal.value.trim().length < 5) {
        codigoPostal.classList.add('is-invalid');
        codigoPostalFeedback.classList.add('invalid-feedback');
        codigoPostalFeedback.innerHTML = `Te faltan ${5 - codigoPostal.value.trim().length} dígitos`;
        codigoPostalTooltip.style.display = 'none';
        codigoPostalTooltip.innerHTML = `&nbsp;`;
        removerColonias();
        return;
    }

    if (codigoPostal.value.trim().length > 5) {
        codigoPostal.classList.add('is-invalid');
        codigoPostalFeedback.classList.add('invalid-feedback');
        codigoPostalFeedback.innerHTML = `Te sobran ${codigoPostal.value.trim().length - 5} dígitos`;
        codigoPostalTooltip.style.display = 'none';
        codigoPostalTooltip.innerHTML = `&nbsp;`;
        removerColonias();
        return;
    }

    codigoPostal.classList.add('is-valid');
    codigoPostalFeedback.classList.add('valid-feedback');
    codigoPostalFeedback.innerHTML = `Parece correcto`;
    codigoPostalTooltip.style.display = 'none';
    codigoPostalTooltip.innerHTML = `&nbsp;`;
    cargarColonias(codigoPostal.value);
});
function removerColonias() {
    idColonia.innerHTML = `<option value="0">Ingrese el código postal</option>`;
    idColoniaValid = false;
}
function cargarColonias(id) {
    idColonia.innerHTML = `<option value="0">Espere...</option>`;

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function (token) {

        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('codigo_postal', codigoPostal.value);
        formData.append('csrf', csrf.value);
        formData.append('id_usuario', idUsuario.value);
        formData.append('token', token);


        xhttp.onreadystatechange = function () {
            if (xhttp.readyState === 4 && xhttp.status === 200) {
                let res = JSON.parse(xhttp.responseText);
                let items = JSON.parse(res.data);
                if (Object.keys(items).length === 0) {
                    codigoPostal.classList.remove('is-valid', 'is-invalid');
                    codigoPostalFeedback.classList.remove('valid-feedback', 'invalid-feedback');
                    codigoPostalFeedback.innerHTML = `&nbsp;`;
                    codigoPostalTooltip.style.display = 'block';
                    codigoPostalTooltip.innerHTML = `No se encontraron colonias que coincidan con el código postal ingresado.`;
                    idColonia.innerHTML = '';
                    idColoniaValid = false
                } else {
                    let options = `<option value="0">Seleccione</option>`;
                    for (let i = 0; i < Object.keys(items).length; i++) {
                        options += `<option value="${items[i].id}">${items[i].colonia}</option>`;
                    }
                    idColonia.innerHTML = options;
                }
            } else {
                let res = JSON.parse(xhttp.responseText);
                if (!res.response) {
                    codigoPostal.classList.remove('is-valid', 'is-invalid');
                    codigoPostalFeedback.classList.remove('valid-feedback', 'invalid-feedback');
                    codigoPostalFeedback.innerHTML = `&nbsp;`;
                    codigoPostalTooltip.style.display = 'block';
                    codigoPostalTooltip.innerHTML = `${res.message}`;
                    idColonia.innerHTML = '';
                    idColoniaValid = false;
                }
            }
        }
        xhttp.open('post', entorno.value + '/api/colonias', true);
        xhttp.send(formData);
    });
}

idColonia.addEventListener("change", e => {
    idColonia.classList.remove('is-valid', 'is-invalid');
    idColoniaFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    idColoniaFeedback.innerHTML = `&nbsp;`;
    idColoniaTooltip.style.display = 'none';
    idColoniaTooltip.innerHTML = `&nbsp;`;

    if (parseInt(idColonia.value) === 0) {
        idColonia.classList.remove('is-valid', 'is-invalid');
        idColoniaFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        idColoniaFeedback.innerHTML = `&nbsp;`;
        idColoniaTooltip.style.display = 'none';
        idColoniaTooltip.innerHTML = `&nbsp;`;
        idColoniaValid = false;
        return;
    }

    idColonia.classList.add('is-valid');
    idColoniaFeedback.classList.add('valid-feedback');
    idColoniaFeedback.innerHTML = `Parece correcto`;
    idColoniaTooltip.style.display = 'none';
    idColoniaTooltip.innerHTML = `&nbsp;`;
    idColoniaValid = true;
});

rfc.addEventListener("keyup", e => {
    rfc.classList.remove('is-valid', 'is-invalid');
    rfcFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    rfcFeedback.innerHTML = '&nbsp;';
    rfcTooltip.style.display = 'none';
    rfcTooltip.innerHTML = `&nbsp;`;

    if (rfc.value.trim().length === 0) {
        rfc.classList.remove('is-valid', 'is-invalid');
        rfcFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        rfcFeedback.innerHTML = '&nbsp;';
        rfcTooltip.style.display = 'none';
        rfcTooltip.innerHTML = `&nbsp;`;
        rfcValid = false;
        return;
    }

    if (!soloLetrasConNumeros(rfc.value.trim())) {
        rfc.classList.add('is-invalid');
        rfcFeedback.classList.add('invalid-feedback');
        rfcFeedback.innerHTML = `Ingresa solo letras y números.`;
        rfcTooltip.style.display = 'none';
        rfcTooltip.innerHTML = `&nbsp;`;
        rfcValid = false;
        return;
    }

    if (rfc.value.trim().length < 13) {
        rfc.classList.add('is-invalid');
        rfcFeedback.classList.add('invalid-feedback');
        rfcFeedback.innerHTML = `Te faltan ${13 - rfc.value.trim().length} caracteres`;
        rfcTooltip.style.display = 'none';
        rfcTooltip.innerHTML = `&nbsp;`;
        rfcValid = false;
        return;
    }

    if (rfc.value.trim().length > 13) {
        rfc.classList.add('is-invalid');
        rfcFeedback.classList.add('invalid-feedback');
        rfcFeedback.innerHTML = `Te sobran ${rfc.value.trim().length - 13} caracteres`;
        rfcTooltip.style.display = 'none';
        rfcTooltip.innerHTML = `&nbsp;`;
        rfcValid = false;
        return;
    }

    rfc.classList.add('is-valid');
    rfcFeedback.classList.add('valid-feedback');
    rfcFeedback.innerHTML = `Parece correcto`;
    rfcTooltip.style.display = 'none';
    rfcTooltip.innerHTML = `&nbsp;`;
    rfcValid = true;
});

calle.addEventListener("keyup", e => {
    calle.classList.remove('is-valid', 'is-invalid');
    calleFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    calleFeedback.innerHTML = "&nbsp;";
    calleTooltip.style.display = "none";
    calleTooltip.innerHTML = "&nbsp;";

    if (calle.value.trim().length === 0) {
        calle.classList.remove('is-valid', 'is-invalid');
        calleFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        calleFeedback.innerHTML = "&nbsp;";
        calleTooltip.style.display = "none";
        calleTooltip.innerHTML = "&nbsp;";
        calleValid = false;
        return;
    }

    if (calle.value.trim().length < 2) {
        calle.classList.add('is-invalid');
        calleFeedback.classList.add('invalid-feedback');
        calleFeedback.innerHTML = `Te faltan ${2 - calle.value.trim().length} caracteres.`;
        calleTooltip.style.display = "none";
        calleTooltip.innerHTML = "&nbsp;";
        calleValid = false;
        return;
    }

    if (calle.value.trim().length > 45) {
        calle.classList.add('is-invalid');
        calleFeedback.classList.add('invalid-feedback');
        calleFeedback.innerHTML = `Te sobran ${calle.value.trim().length - 40} caracteres.`;
        calleTooltip.style.display = "none";
        calleTooltip.innerHTML = "&nbsp;";
        calleValid = false;
        return;
    }

    calle.classList.add('is-valid');
    calleFeedback.classList.add('valid-feedback');
    calleFeedback.innerHTML = `Parece correcto`;
    calleTooltip.style.display = "none";
    calleTooltip.innerHTML = "&nbsp;";
    calleValid = true;
});

numeroExterior.addEventListener("keyup", e => {
    numeroExterior.classList.remove('is-valid', 'is-invalid');
    numeroExteriorFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    numeroExteriorFeedback.innerHTML = "&nbsp;";
    numeroExteriorTooltip.style.display = "none";
    numeroExteriorTooltip.innerHTML = "&nbsp;";

    if (numeroExterior.value.trim().length === 0) {
        numeroExterior.classList.remove('is-valid', 'is-invalid');
        numeroExteriorFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        numeroExteriorFeedback.innerHTML = "&nbsp;";
        numeroExteriorTooltip.style.display = "none";
        numeroExteriorTooltip.innerHTML = "&nbsp;";
        numeroExteriorValid = false;
        return;
    }

    if (!soloLetrasConNumeros(numeroExterior.value.trim())) {
        numeroExterior.classList.add('is-invalid');
        numeroExteriorFeedback.classList.add('invalid-feedback');
        numeroExteriorFeedback.innerHTML = "Ingrese solo números con letras opcionalmente.";
        numeroExteriorValid = false;
        return;
    }

    numeroExterior.classList.add('is-valid');
    numeroExteriorFeedback.classList.add('valid-feedback');
    numeroExteriorFeedback.innerHTML = `Parece correcto`;
    numeroExteriorTooltip.style.display = "none";
    numeroExteriorTooltip.innerHTML = "&nbsp;";
    numeroExteriorValid = true;
});

numeroInterior.addEventListener("keyup", e => {
    numeroInterior.classList.remove('is-valid', 'is-invalid');
    numeroInteriorFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    numeroInteriorFeedback.innerHTML = "&nbsp;";
    numeroInteriorTooltip.style.display = "none";
    numeroInteriorTooltip.innerHTML = "&nbsp;";

    if (numeroInterior.value.trim().length === 0) {
        numeroInterior.classList.remove('is-valid', 'is-invalid');
        numeroInteriorFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        numeroInteriorFeedback.innerHTML = "&nbsp;";
        numeroInteriorTooltip.style.display = "none";
        numeroInteriorTooltip.innerHTML = "&nbsp;";
        numeroInteriorValid = true;
        return;
    }

    if (!soloLetrasConNumeros(numeroInterior.value.trim())) {
        numeroInterior.classList.add('is-invalid');
        numeroInteriorFeedback.classList.add('invalid-feedback');
        numeroInteriorFeedback.innerHTML = "Ingrese solo números con letras opcionalmente.";
        numeroInteriorValid = false;
        return;
    }

    numeroInterior.classList.add('is-valid');
    numeroInteriorFeedback.classList.add('valid-feedback');
    numeroInteriorFeedback.innerHTML = `Parece correcto`;
    numeroInteriorTooltip.style.display = "none";
    numeroInteriorTooltip.innerHTML = "&nbsp;";
    numeroInteriorValid = true;
});

observaciones.addEventListener("keyup", e => {
    observaciones.classList.remove('is-valid', 'is-invalid');
    observacionesFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    observacionesFeedback.innerHTML = "&nbsp;";
    observacionesTooltip.style.display = "none";
    observacionesTooltip.innerHTML = "&nbsp;";

    if (observaciones.value.trim().length === 0) {
        observaciones.classList.remove('is-valid', 'is-invalid');
        observacionesFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        observacionesFeedback.innerHTML = "&nbsp;";
        observacionesTooltip.style.display = "none";
        observacionesTooltip.innerHTML = "&nbsp;";
        observacionesValid = false;
        return;
    }

    if (observaciones.value.trim().length < 10) {
        observaciones.classList.add('is-invalid');
        observacionesFeedback.classList.add('invalid-feedback');
        observacionesFeedback.innerHTML = `Te faltan ${2 - observaciones.value.trim().length} caracteres.`;
        observacionesTooltip.style.display = "none";
        observacionesTooltip.innerHTML = "&nbsp;";
        observacionesValid = false;
        return;
    }

    observaciones.classList.add('is-valid');
    observacionesFeedback.classList.add('valid-feedback');
    observacionesFeedback.innerHTML = `Parece correcto`;
    observacionesTooltip.style.display = "none";
    observacionesTooltip.innerHTML = "&nbsp;";
    observacionesValid = true;
});

btnCancelar.addEventListener("click", e => {
    let captura = 0;
    if (nombre.value.trim().length !== 0) captura++;
    if (apellidoPaterno.value.trim().length !== 0) captura++;
    if (apellidoMaterno.value.trim().length !== 0) captura++;
    if (fechaNacimiento.value.trim().length !== 0) captura++;
    if (parseInt(idGenero.value) !== 0) captura++;
    if (telefonoCasa.value.trim().length !== 0) captura++;
    if (telefonoCelular.value.trim().length !== 0) captura++;
    if (email.value.trim().length !== 0) captura++;
    if (codigoPostal.value.trim().length !== 0) captura++;
    if (idColonia.value.trim().length !== 0) captura++;
    if (rfc.value.trim().length !== 0) captura++;
    if (calle.value.trim().length !== 0) captura++;
    if (numeroExterior.value.trim().length !== 0) captura++;
    if (numeroInterior.value.trim().length !== 0) captura++;
    if (observaciones.value.trim().length !== 0) captura++;
    if (captura !== 0) document.querySelector("#modal-confirm_cancel").style.display = 'flex';
});
function cancelarActualizacion(){
    window.location = entorno.value + '/clientes/consultar';
}

form.addEventListener("submit", e => {
    e.preventDefault();

    if (!nombreValid) {
        nombre.focus();
        nombreTooltip.style.display = 'block';
        nombreTooltip.innerHTML = `Complete este campo como se solicita.`;
        return;
    }
    if (!apellidoPaternoValid) {
        apellidoPaterno.focus();
        apellidoPaternoTooltip.style.display = 'block';
        apellidoPaternoTooltip.innerHTML = `Complete este campo como se solicita.`;
        return;
    }
    if (!apellidoMaternoValid) {
        apellidoMaterno.focus();
        apellidoMaternoTooltip.style.display = 'block';
        apellidoMaternoTooltip.innerHTML = `Complete este campo como se solicita.`;
        return;
    }
    if (!fechaNacimientoValid) {
        fechaNacimiento.focus();
        fechaNacimientoTooltip.style.display = 'block';
        fechaNacimientoTooltip.innerHTML = `Complete este campo como se solicita.`;
        return;
    }
    if (!idGeneroValid) {
        idGenero.focus();
        idGeneroTooltip.style.display = 'block';
        idGeneroTooltip.innerHTML = `Seleccione una opción de este campo.`;
        return;
    }
    if (!telefonoCasaValid) {
        telefonoCasa.focus();
        telefonoCasaTooltip.style.display = 'block';
        telefonoCasaTooltip.innerHTML = `Complete este campo como se solicita.`;
        return;
    }
    if (!telefonoCelularValid) {
        telefonoCelular.focus();
        telefonoCelularTooltip.style.display = 'block';
        telefonoCelularTooltip.innerHTML = `Complete este campo como se solicita.`;
        return;
    }
    if (!emailValid) {
        email.focus();
        emailTooltip.style.display = 'block';
        emailTooltip.innerHTML = `Complete este campo como se solicita.`;
        return;
    }
    if (codigoPostal.value.trim().length === 0) {
        codigoPostal.focus();
        codigoPostalTooltip.style.display = 'block';
        codigoPostalTooltip.innerHTML = `Complete este campo como se solicita.`;
        return;
    }
    if (!idColoniaValid) {
        idColonia.focus();
        idColoniaTooltip.style.display = 'block';
        idColoniaTooltip.innerHTML = `Seleccione una opción de este campo.`;
        return;
    }
    if (!rfcValid) {
        rfc.focus();
        rfcTooltip.style.display = 'block';
        rfcTooltip.innerHTML = `Complete este campo como se solicita`;
        return;
    }
    if (!calleValid) {
        calle.focus();
        calleTooltip.style.display = 'block';
        calleTooltip.innerHTML = `Complete este campo como se solicita.`;
        return;
    }
    if (!numeroExteriorValid) {
        numeroExterior.focus();
        numeroExteriorTooltip.style.display = 'block';
        numeroExteriorTooltip.innerHTML = `Complete este campo como se solicita.`;
        return;
    }
    if (!numeroInteriorValid) {
        numeroInterior.focus();
        numeroInteriorTooltip.style.display = 'block';
        numeroInteriorTooltip.innerHTML = `Complete este campo como se solicita.`;
        return;
    }
    if (!observacionesValid) {
        observaciones.focus();
        observacionesTooltip.style.display = 'block';
        observacionesTooltip.innerHTML = `Complete este campo como se solicita.`;
        return;
    }

    btnSubmit.disabled = true;
    document.querySelector("#modal-loading").style.display = 'flex';

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function (token) {

        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('id_usuario', idUsuario.value);
        formData.append('token', token);
        formData.append('csrf', csrf.value);
        formData.append('id_cliente', idCliente.value);
        formData.append('nombre', nombre.value.trim());
        formData.append('apellido_paterno', apellidoPaterno.value.trim());
        formData.append('apellido_materno', apellidoMaterno.value.trim());
        formData.append('fecha_nacimiento', fechaNacimiento.value.trim());
        formData.append('id_genero', parseInt(idGenero.value));
        formData.append('telefono_casa', telefonoCasa.value.trim());
        formData.append('telefono_celular', telefonoCelular.value.trim());
        formData.append('email', email.value.trim());
        formData.append('id_colonia', idColonia.value.trim());
        formData.append('rfc', rfc.value.trim());
        formData.append('calle', calle.value.trim());
        formData.append('numero_exterior', numeroExterior.value.trim());
        formData.append('numero_interior', numeroInterior.value.trim());
        formData.append('observaciones', observaciones.value.trim());


        xhttp.onreadystatechange = function () {
            if (xhttp.readyState === 4 && xhttp.status === 200) {
                let res = JSON.parse(xhttp.responseText);
                btnSubmit.disabled = false;
                document.querySelector("#modal-loading").style.display = 'none';
                document.querySelector("#modal-info").style.display = 'flex';
                document.querySelector("#modal-info-message").innerHTML = `<p class="text-center">${res.message}</p>`;
                return;
            } else {
                if (xhttp.status === 400) {
                    let res = JSON.parse(xhttp.responseText);
                    btnSubmit.disabled = false;
                    document.querySelector("#modal-loading").style.display = 'none';
                    document.querySelector("#modal-error").style.display = 'flex';
                    document.querySelector("#modal-error-message").innerHTML = `<p class="text-center">${res.message}</p>`;
                    return;
                }
            }
        }
        xhttp.open('post', form.getAttribute('action'), true);
        xhttp.send(formData);
    });
});