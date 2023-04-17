const WIDTH = 300;
let url = document.querySelector("#url");
let formNuevoEmpleado = document.querySelector("#formNuevoEmpleado");
let action = document.querySelector("#action");
let csrf = document.querySelector("#csrf");
let idUsuario = document.querySelector("#id_usuario");
let nombre = document.querySelector("#nombre");
let nombreFeedback = document.querySelector("#nombre-feedback");
let nombreTooltip = document.querySelector("#tooltip-nombre");
let nombreValid = false;
let apellidoPaterno = document.querySelector("#apellido_paterno");
let apellidoPaternoFeedback = document.querySelector("#apellido_paterno-feedback");
let apellidoPaternoTooltip = document.querySelector("#tooltip-apellido_paterno");
let apellidoPaternoValid = false;
let apellidoMaterno = document.querySelector("#apellido_materno");
let apellidoMaternoFeedback = document.querySelector("#apellido_materno-feedback");
let apellidoMaternoTooltip = document.querySelector("#tooltip-apellido_materno");
let apellidoMaternoValid = true;
let fechaNacimiento = document.querySelector("#fecha_nacimiento");
let fechaNacimientoFeedback = document.querySelector("#fecha_nacimiento-feedback");
let fechaNacimientoTooltip = document.querySelector("#tooltip-fecha_nacimiento");
let fechaNacimientoValid = false;
let idGenero = document.querySelector("#id_genero");
let idGeneroFeedback = document.querySelector("#id_genero-feedback");
let idGeneroTooltip = document.querySelector("#tooltip-id_genero");
let idGeneroValid = false;
let seguroSocial = document.querySelector("#seguro_social");
let seguroSocialFeedback = document.querySelector("#seguro_social-feedback");
let seguroSocialTooltip = document.querySelector("#tooltip-seguro_social");
let seguroSocialValid = false;
let rfc = document.querySelector("#rfc");
let rfcFeedback = document.querySelector("#rfc-feedback");
let rfcTooltip = document.querySelector("#tooltip-rfc");
let rfcValid = false;
let email = document.querySelector("#email");
let emailFeedback = document.querySelector("#email-feedback");
let emailTooltip = document.querySelector("#tooltip-email");
let emailValid = false;
let telefonoCasa = document.querySelector("#telefono_casa");
let telefonoCasaFeedback = document.querySelector("#telefono_casa-feedback");
let telefonoCasaTooltip = document.querySelector("#tooltip-telefono_casa");
let telefonoCasaValid = false;
let telefonoCelular = document.querySelector("#telefono_celular");
let telefonoCelularFeedback = document.querySelector("#telefono_celular-feedback");
let telefonoCelularTooltip = document.querySelector("#tooltip-telefono_celular");
let telefonoCelularValid = false;
let calle = document.querySelector("#calle");
let calleFeedback = document.querySelector("#calle-feedback");
let calleTooltip = document.querySelector("#tooltip-calle");
let calleValid = false;
let numeroExterior = document.querySelector("#numero_exterior");
let numeroExteriorFeedback = document.querySelector("#numero_exterior-feedback");
let numeroExteriorTooltip = document.querySelector("#tooltip-numero_exterior");
let numeroExteriorValid = true;
let numeroInterior = document.querySelector("#numero_interior");
let numeroInteriorFeedback = document.querySelector("#numero_interior-feedback");
let numeroInteriorTooltip = document.querySelector("#tooltip-numero_interior");
let numeroInteriorValid = true;
let codigoPostal = document.querySelector("#codigo_postal");
let codigoPostalFeedback = document.querySelector("#codigo_postal-feedback");
let codigoPostalTooltip = document.querySelector("#tooltip-codigo_postal");
let codigoPostalValid = false;
let idColonia = document.querySelector("#id_colonia");
let idColoniaFeedback = document.querySelector("#id_colonia-feedback");
let idColoniaTooltip = document.querySelector("#tooltip-id_colonia");
let idColoniaValid = false;
let fechaInicio = document.querySelector("#fecha_inicio");
let fechaInicioFeedback = document.querySelector("#fecha_inicio-feedback");
let fechaInicioTooltip = document.querySelector("#tooltip-fecha_inicio");
let fechaInicioValid = false;
let idPuesto = document.querySelector("#id_puesto");
let idPuestoFeedback = document.querySelector("#id_puesto-feedback");
let idPuestoTooltip = document.querySelector("#tooltip-id_puesto");
let idPuestoValid = false;
let profileImg = document.querySelector("#profile_img");
let profileImgFeedback = document.querySelector("#profile_img-feedback");
let profileImgTooltip = document.querySelector("#tooltip-profile_img");
let profileImgValid = true;
let containerProfileImg = document.querySelector(".container-profile_img");
let btnRemoveImg = document.querySelector("#btn-remove-img");
let btnSubmit = document.querySelector("#btn-submit");


// MOSTRAR VISTA PREVIA DE LA IMAGEN DE PERFIL A CARGAR
profileImg.addEventListener("change", e => {
    profileImgValid = true;
    profileImgTooltip.style.display = 'none';
    profileImg.classList.remove('is-valid', 'is-invalid');
    profileImgFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    profileImgFeedback.innerHTML = '&nbsp;';

    containerProfileImg.innerHTML = '';
    if (e.target.files.length > 0) {
        if (!validFileType(e.target.files[0].type)) {
            profileImgValid = false;
            profileImg.classList.add('is-invalid');
            profileImgFeedback.classList.add('invalid-feedback');
            profileImgFeedback.innerHTML = '¡Seleccione un archivo de tipo imagen!';
        } else {
            profileImgValid = true;
            profileImg.classList.add('is-valid');
            profileImgFeedback.classList.add('valid-feedback');
            profileImgFeedback.innerHTML = 'Parece correcto';
            btnRemoveImg.style.display = "block";
            let image_file = e.target.files[0];
            let reader = new FileReader();
            reader.readAsDataURL(image_file);

            reader.onload = event => {
                let image_url = event.target.result;
                let image = document.createElement("img");
                image.src = image_url;
                image.onload = evt => {
                    let canvas = document.createElement("canvas");
                    let ratio = WIDTH / evt.target.width;
                    canvas.width = WIDTH;
                    canvas.height = evt.target.height * ratio;

                    const CONTEXT = canvas.getContext("2d");
                    CONTEXT.drawImage(image, 0, 0, canvas.width, canvas.height);

                    let new_image_url = CONTEXT.canvas.toDataURL("image/jpeg", 90);
                    let new_image = document.createElement("img");
                    new_image.setAttribute('id', 'img-result');
                    new_image.src = new_image_url;
                    containerProfileImg.classList.remove("animate__fadeOut");
                    containerProfileImg.classList.add("animate__fadeIn");
                    containerProfileImg.appendChild(new_image);
                }
            }
        }
    }
})

btnRemoveImg.addEventListener("click", e => {
    profileImgValid = true;
    profileImg.classList.remove('is-valid', 'is-invalid');
    profileImgFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    profileImgFeedback.innerHTML = '&nbsp;';
    profileImg.value = "";
    containerProfileImg.classList.add("animate__fadeOut");
    setTimeout(function () {
        containerProfileImg.classList.remove('animate__fadeIn', 'animate__fadeOut');
        containerProfileImg.innerHTML = '';
        btnRemoveImg.style.display = 'none';
    }, 1000);
})

/** VALIDACIÓN AL CAPTURAR LOS DATOS */
nombre.addEventListener("keyup", e => {
    nombreValid = false;
    nombreTooltip.style.display = 'none';
    nombre.classList.remove('is-valid', 'is-invalid');
    nombreFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    nombreFeedback.innerHTML = '&nbsp;';

    if (nombre.value.trim().length === 0) {
        nombreValid = false;
        nombre.classList.remove('is-valid', 'is-invalid');
        nombreFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        nombreFeedback.innerHTML = '&nbsp;';
        return;
    }

    if (nombre.value.trim().length > 0 && !soloLetrasEspaciosAcentos(nombre.value.trim())) {
        nombreValid = false;
        nombre.classList.add('is-invalid');
        nombreFeedback.classList.add('invalid-feedback');
        nombreFeedback.innerHTML = '¡Solo letras y espacios!';
        return;
    }

    if (nombre.value.trim().length > 2) {
        nombreValid = true;
        nombre.classList.add('is-valid');
        nombreFeedback.classList.add('valid-feedback');
        nombreFeedback.innerHTML = 'Parece correcto';
    }

    evaluarRfc();
});

apellidoPaterno.addEventListener("keyup", e => {
    apellidoPaternoValid = false;
    apellidoPaternoTooltip.style.display = 'none';
    apellidoPaterno.classList.remove('is-valid', 'is-invalid');
    apellidoPaternoFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    apellidoPaternoFeedback.innerHTML = '&nbsp;';

    if (apellidoPaterno.value.trim().length === 0) {
        apellidoPaternoValid = false;
        apellidoPaterno.classList.remove('is-valid', 'is-invalid');
        apellidoPaternoFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        apellidoPaternoFeedback.innerHTML = '&nbsp;';
        return;
    }

    if (apellidoPaterno.value.trim().length > 0 && !soloLetrasEspaciosAcentos(apellidoPaterno.value.trim())) {
        apellidoPaternoValid = false;
        apellidoPaterno.classList.add('is-invalid');
        apellidoPaternoFeedback.classList.add('invalid-feedback');
        apellidoPaternoFeedback.innerHTML = '¡Solo letras y espacios!';
        return;
    }

    if (apellidoPaterno.value.trim().length > 2) {
        apellidoPaternoValid = true;
        apellidoPaterno.classList.add('is-valid');
        apellidoPaternoFeedback.classList.add('valid-feedback');
        apellidoPaternoFeedback.innerHTML = 'Parece correcto';
    }

    evaluarRfc();
});

apellidoMaterno.addEventListener("keyup", e => {
    apellidoMaternoValid = true;
    apellidoMaternoTooltip.style.display = 'none';
    apellidoMaterno.classList.remove('is-valid', 'is-invalid');
    apellidoMaternoFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    apellidoMaternoFeedback.innerHTML = '&nbsp;';

    if (apellidoMaterno.value.trim().length === 0) {
        apellidoMaternoValid = true;
        apellidoMaterno.classList.remove('is-valid', 'is-invalid');
        apellidoMaternoFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        apellidoMaternoFeedback.innerHTML = '&nbsp;';
    }

    if (apellidoMaterno.value.trim().length > 0 && !soloLetrasEspaciosAcentos(apellidoMaterno.value.trim())) {
        apellidoMaternoValid = false;
        apellidoMaterno.classList.add('is-invalid');
        apellidoMaternoFeedback.classList.add('invalid-feedback');
        apellidoMaternoFeedback.innerHTML = '¡Solo letras y espacios!';
        return;
    }

    if (apellidoMaterno.value.trim().length > 2) {
        apellidoMaternoValid = true;
        apellidoMaterno.classList.add('is-valid');
        apellidoMaternoFeedback.classList.add('valid-feedback');
        apellidoMaternoFeedback.innerHTML = 'Parece correcto';
    }

    evaluarRfc();
});

fechaNacimiento.addEventListener("change", e => {
    fechaNacimientoValid = false;
    fechaNacimientoTooltip.style.display = 'none';
    fechaNacimiento.classList.remove('is-valid', 'is-invalid');
    fechaNacimientoFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    fechaNacimientoFeedback.innerHTML = '&nbsp;';

    let v = fechaNacimiento.value;

    if (v.trim().length === 0) {
        fechaNacimientoValid = false;
        fechaNacimiento.classList.remove('is-valid', 'is-invalid');
        fechaNacimientoFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        fechaNacimientoFeedback.innerHTML = '&nbsp;';
    } else {
        let el = v.split('-');
        let birthYear = new Date(parseInt(el[0]), parseInt(el[1]) - 1, parseInt(el[2])).getFullYear();
        let currentYear = new Date().getFullYear();

        if (isNaN(currentYear - birthYear)) {
            fechaNacimientoValid = false;
            fechaNacimiento.classList.remove('is-valid', 'is-invalid');
            fechaNacimientoFeedback.classList.remove('valid-feedback', 'invalid-feedback');
            fechaNacimientoFeedback.innerHTML = '&nbsp;';
        } else {
            if ((currentYear - birthYear) < 18) {
                fechaNacimientoValid = false;
                fechaNacimiento.classList.add('is-invalid');
                fechaNacimientoFeedback.classList.add('invalid-feedback');
                fechaNacimientoFeedback.innerHTML = '¡La persona debe tener al menos 18 años!';
            } else {
                if ((currentYear - birthYear) >= 65) {
                    fechaNacimientoValid = false;
                    fechaNacimiento.classList.add('is-invalid');
                    fechaNacimientoFeedback.classList.add('invalid-feedback');
                    fechaNacimientoFeedback.innerHTML = '¡La persona tiene al menos 65 años!';
                } else {
                    fechaNacimientoValid = true;
                    fechaNacimiento.classList.add('is-valid');
                    fechaNacimientoFeedback.classList.add('valid-feedback');
                    fechaNacimientoFeedback.innerHTML = 'Parece correcto';
                }
            }
        }
    }

    evaluarRfc();
});

idGenero.addEventListener("change", e => {
    idGeneroValid = false;
    idGeneroTooltip.style.display = 'none';
    idGenero.classList.remove('is-valid', 'is-invalid');
    idGeneroFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    idGeneroFeedback.innerHTML = '&nbsp;';

    if (parseInt(idGenero.value) === 0) {
        idGeneroValid = false;
        idGenero.classList.remove('is-valid', 'is-invalid');
        idGeneroFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        idGeneroFeedback.innerHTML = '&nbsp;';
    } else {
        idGeneroValid = true;
        idGenero.classList.add('is-valid');
        idGeneroFeedback.classList.add('valid-feedback');
        idGeneroFeedback.innerHTML = 'Parece correcto';
    }
});

seguroSocial.addEventListener("keyup", e => {
    seguroSocialValid = false;
    seguroSocialTooltip.style.display = 'none';
    seguroSocial.classList.remove('is-valid', 'is-invalid');
    seguroSocialFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    seguroSocialFeedback.innerHTML = '&nbsp;';

    if (seguroSocial.value.trim().length === 0) {
        seguroSocialValid = false;
        seguroSocial.classList.remove('is-valid', 'is-invalid');
        seguroSocialFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        seguroSocialFeedback.innerHTML = '&nbsp;';
    } else {
        if (!soloNumeros(seguroSocial.value.trim())) {
            seguroSocialValid = false;
            seguroSocial.classList.add('is-invalid');
            seguroSocialFeedback.classList.add('invalid-feedback');
            seguroSocialFeedback.innerHTML = '¡Ingrese solo números!';
        } else {
            if (seguroSocial.value.trim().length !== 11) {
                seguroSocialValid = false;
                seguroSocial.classList.add('is-invalid');
                seguroSocialFeedback.classList.add('invalid-feedback');
                seguroSocialFeedback.innerHTML = 'El número debe tener 11 dígitos';
            } else {
                seguroSocialValid = true;
                seguroSocial.classList.add('is-valid');
                seguroSocialFeedback.classList.add('valid-feedback');
                seguroSocialFeedback.innerHTML = 'Parece correcto';
            }
        }
    }
});

rfc.addEventListener("keyup", e => {
    evaluarRfc();
});

function evaluarRfc() {
    rfcValid = false;
    rfcTooltip.style.display = 'none';
    rfc.classList.remove('is-valid', 'is-invalid');
    rfcFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    rfcFeedback.innerHTML = '&nbsp;';

    if (rfc.value.trim().length === 0) {
        rfcValid = false;
        rfc.classList.remove('is-valid', 'is-invalid');
        rfcFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        rfcFeedback.innerHTML = '&nbsp;';
    } else {
        if (rfc.value.trim().length !== 13) {
            rfcValid = false;
            rfc.classList.add('is-invalid');
            rfcFeedback.classList.add('invalid-feedback');
            rfcFeedback.innerHTML = '¡El RFC debe tener 13 carácteres (letras y números)!';
        } else {
            if (!soloLetrasConNumeros(rfc.value.trim()))
            {
                rfcValid = false;
                rfc.classList.add('is-invalid');
                rfcFeedback.classList.add('invalid-feedback');
                rfcFeedback.innerHTML = '¡El RFC debe tener 13 carácteres (letras y números)!';
            } else {
                rfcValid = true;
                rfc.classList.add('is-valid');
                rfcFeedback.classList.add('valid-feedback');
                rfcFeedback.innerHTML = 'Parece correcto';
            }
        }
    }
}

email.addEventListener("keyup", e => {
    emailValid = false;
    emailTooltip.style.display = 'none';
    email.classList.remove('is-valid', 'is-invalid');
    emailFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    emailFeedback.innerHTML = '&nbsp;';

    if (email.value.trim().length === 0) {
        emailValid = false;
        email.classList.remove('is-valid', 'is-invalid');
        emailFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        emailFeedback.innerHTML = '&nbsp;';
    } else {
        if (!isValidEmail(email.value.trim())) {
            emailValid = false;
            email.classList.add('is-invalid');
            emailFeedback.classList.add('invalid-feedback');
            emailFeedback.innerHTML = '¡Ingrese una dirección de correo electrónico válida!';
        } else {
            emailValid = true;
            email.classList.add('is-valid');
            emailFeedback.classList.add('valid-feedback');
            emailFeedback.innerHTML = 'Parece correcto';
        }
    }
});

telefonoCasa.addEventListener("keyup", e => {
    telefonoCasaValid = false;
    telefonoCasaTooltip.style.display = 'none';
    telefonoCasa.classList.remove('is-valid', 'is-invalid');
    telefonoCasaFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    telefonoCasaFeedback.innerHTML = '&nbsp;';

    if (telefonoCasa.value.trim().length === 0) {
        telefonoCasaValid = false;
        telefonoCasa.classList.remove('is-valid', 'is-invalid');
        telefonoCasaFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        telefonoCasaFeedback.innerHTML = '&nbsp;';
    } else {
        if (telefonoCasa.value.trim().length !== 10) {
            telefonoCasaValid = false;
            telefonoCasa.classList.add('is-invalid');
            telefonoCasaFeedback.classList.add('invalid-feedback');
            telefonoCasaFeedback.innerHTML = '¡Debe tener 10 dígitos!';
        } else {
            telefonoCasaValid = true;
            telefonoCasa.classList.add('is-valid');
            telefonoCasaFeedback.classList.add('valid-feedback');
            telefonoCasaFeedback.innerHTML = 'Parece correcto';
        }
    }
});

telefonoCelular.addEventListener("keyup", e => {
    telefonoCelularValid = false;
    telefonoCelularTooltip.style.display = 'none';
    telefonoCelular.classList.remove('is-valid', 'is-invalid');
    telefonoCelularFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    telefonoCelularFeedback.innerHTML = '&nbsp;';

    if (telefonoCelular.value.trim().length === 0) {
        telefonoCelularValid = false;
        telefonoCelular.classList.remove('is-valid', 'is-invalid');
        telefonoCelularFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        telefonoCelularFeedback.innerHTML = '&nbsp;';
    } else {
        if (telefonoCelular.value.trim().length !== 10) {
            telefonoCelularValid = false;
            telefonoCelular.classList.add('is-invalid');
            telefonoCelularFeedback.classList.add('invalid-feedback');
            telefonoCelularFeedback.innerHTML = '¡Debe tener 10 dígitos!';
        } else {
            telefonoCelularValid = true;
            telefonoCelular.classList.add('is-valid');
            telefonoCelularFeedback.classList.add('valid-feedback');
            telefonoCelularFeedback.innerHTML = 'Parece correcto';
        }
    }
});

calle.addEventListener("keyup", e => {
    calleValid = false;
    calleTooltip.style.display = 'none';
    calle.classList.remove('is-valid', 'is-invalid');
    calleFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    calleFeedback.innerHTML = '&nbsp;';

    if (calle.value.trim().length === 0) {
        calleValid = false;
        calle.classList.remove('is-valid', 'is-invalid');
        calleFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        calleFeedback.innerHTML = '&nbsp;';
    } else {
        if (!soloNumerosLetrasEspaciosAcentos(calle.value.trim())) {
            calleValid = false;
            calle.classList.add('is-invalid');
            calleFeedback.classList.add('invalid-feedback');
            calleFeedback.innerHTML = `Solo ingresa letras, espacios y/o acentos.`;
        } else {
            calleValid = true;
            calle.classList.add('is-valid');
            calleFeedback.classList.add('valid-feedback');
            calleFeedback.innerHTML = 'Parece correcto';
        }
    }
});

numeroExterior.addEventListener("keyup", e => {
    numeroExteriorValid = true;
    numeroExteriorTooltip.style.display = 'none';
    numeroExterior.classList.remove('is-valid', 'is-invalid');
    numeroExteriorFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    numeroExteriorFeedback.innerHTML = '&nbsp;';

    if (numeroExterior.value.trim().length === 0) {
        numeroExteriorValid = true;
        numeroExterior.classList.remove('is-valid', 'is-invalid');
        numeroExteriorFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        numeroExteriorFeedback.innerHTML = '&nbsp;';
    } else {
        if (!soloLetrasConNumeros(numeroExterior.value.trim())) {
            numeroExteriorValid = false;
            numeroExterior.classList.add('is-invalid');
            numeroExteriorFeedback.classList.add('invalid-feedback');
            numeroExteriorFeedback.innerHTML = '¡Solo números con/sin letras!';
        } else {
            numeroExteriorValid = true;
            numeroExterior.classList.add('is-valid');
            numeroExteriorFeedback.classList.add('valid-feedback');
            numeroExteriorFeedback.innerHTML = 'Parece correcto';
        }
    }
});


numeroInterior.addEventListener("keyup", e => {
    numeroInteriorValid = true;
    numeroInteriorTooltip.style.display = 'none';
    numeroInterior.classList.remove('is-valid', 'is-invalid');
    numeroInteriorFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    numeroInteriorFeedback.innerHTML = '&nbsp;';

    if (numeroInterior.value.trim().length === 0) {
        numeroInteriorValid = true;
        numeroInterior.classList.remove('is-valid', 'is-invalid');
        numeroInteriorFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        numeroInteriorFeedback.innerHTML = '&nbsp;';
    } else {
        if (!soloLetrasConNumeros(numeroInterior.value.trim())) {
            numeroInteriorValid = false;
            numeroInterior.classList.add('is-invalid');
            numeroInteriorFeedback.classList.add('invalid-feedback');
            numeroInteriorFeedback.innerHTML = '¡Solo números con/sin letras!';
        } else {
            numeroInteriorValid = true;
            numeroInterior.classList.add('is-valid');
            numeroInteriorFeedback.classList.add('valid-feedback');
            numeroInteriorFeedback.innerHTML = 'Parece correcto';
        }
    }
});

// CARGAR LAS COLONIAS DEPENDIENDO DEL CÓDIGO POSTAL
codigoPostal.addEventListener("keyup", e => {
    codigoPostalValid = false;
    codigoPostalTooltip.innerHTML = "";
    codigoPostalTooltip.style.display = "none";
    idColonia.classList.remove('is-valid', 'is-invalid');
    idColoniaFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    idColoniaFeedback.innerHTML = '&nbsp;';

    let cp = codigoPostal.value.trim();

    if (cp.length === 0) {
        codigoPostalValid = false;
        codigoPostal.classList.remove('is-valid', 'is-invalid');
        codigoPostalFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        codigoPostalFeedback.innerHTML = '&nbsp;';

        idColonia.innerHTML = '<option value="0">Ingrese primero el código postal</option>';
        return;
    }

    if (cp.length < 5 && !soloNumeros(cp)) {
        codigoPostalValid = false;
        codigoPostal.classList.remove('is-valid');
        codigoPostalFeedback.classList.remove('valid-feedback');

        codigoPostal.classList.add('is-invalid');
        codigoPostalFeedback.classList.add('invalid-feedback');
        codigoPostalFeedback.innerHTML = 'Sólo números, 5 dígitos.'

        idColonia.innerHTML = '<option value="0">El código postal debe tener solo números y ser de 5 dígitos</option>';
        return;
    }

    if (cp.length < 5 && soloNumeros(cp)) {
        codigoPostalValid = false;
        codigoPostal.classList.remove('is-valid', 'is-invalid');
        codigoPostalFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        codigoPostalFeedback.innerHTML = '&nbsp;'

        idColonia.innerHTML = '<option value="0">El código postal debe tener 5 dígitos</option>';
        return;
    }

    if (cp.length > 5 && !soloNumeros(cp)) {
        codigoPostalValid = false;
        codigoPostal.classList.remove('is-valid');
        codigoPostalFeedback.classList.remove('valid-feedback');

        codigoPostal.classList.add('is-invalid');
        codigoPostalFeedback.classList.add('invalid-feedback');
        codigoPostalFeedback.innerHTML = 'Sólo números, 5 dígitos.'

        idColonia.innerHTML = '<option value="0">El código postal debe tener solo números y ser de 5 dígitos</option>';
        return;
    }

    if (cp.length > 5 && soloNumeros(cp)) {
        codigoPostalValid = false;
        codigoPostal.classList.remove('is-valid');
        codigoPostalFeedback.classList.remove('valid-feedback');

        codigoPostal.classList.add('is-invalid');
        codigoPostalFeedback.classList.add('invalid-feedback');
        codigoPostalFeedback.innerHTML = 'Sólo 5 dígitos.'

        idColonia.innerHTML = '<option value="0">El código postal debe tener 5 dígitos</option>';
        return;
    }

    if (cp.length === 5 && !soloNumeros(cp)) {
        codigoPostalValid = false;
        codigoPostal.classList.remove('is-valid');
        codigoPostalFeedback.classList.remove('valid-feedback');

        codigoPostal.classList.add('is-invalid');
        codigoPostalFeedback.classList.add('invalid-feedback');
        codigoPostalFeedback.innerHTML = 'Sólo números, 5 dígitos.'

        idColonia.innerHTML = '<option value="0">El código postal debe tener solo números y ser de 5 dígitos</option>';
        return;
    }

    if (cp.length === 5 && soloNumeros(cp)) {
        codigoPostal.classList.remove('is-invalid');
        codigoPostalFeedback.classList.remove('invalid-feedback');

        codigoPostal.classList.add('is-valid');
        codigoPostalFeedback.classList.add('valid-feedback');
        codigoPostalFeedback.innerHTML = 'Parece correcto.'

        // Enviar datos vía AJAX
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
                    // SI EL SERVIDOR ENVÍO RESPUESTA
                    let res = JSON.parse(xhttp.responseText);
                    let data = JSON.parse(res["data"]);
                    // SI LA RESPUESTA NO CONTIENE DATOS
                    if (Object.keys(data).length === 0) {
                        codigoPostalValid = false;
                        idColonia.innerHTML = '<option value="0">No se encontraron colonias que coincidan con el código postal ingresado.</option>';
                        codigoPostalTooltip.innerHTML = "Parece que el código postal no corresponde a Matamoros.";
                        codigoPostalTooltip.style.display = "block";
                        codigoPostal.focus();
                        return
                    }

                    codigoPostalValid = true;
                    let o = `<option value="0">Seleccione...</option>`;
                    for (let i = 0; i < Object.keys(data).length; i++) {
                        o += `<option value="${data[i]["id"]}">${data[i]["colonia"]}</option>`;
                    }
                    idColonia.innerHTML = o;
                } else {
                    if (xhttp.status === 400) {
                        codigoPostalValid = false;
                        let res = JSON.parse(xhttp.responseText);
                        console.log(res);
                    }
                }
            }
            xhttp.open('post', url.value + '/api/colonias', true);
            xhttp.send(formData);
        });
    }
});

idColonia.addEventListener("change", e => {
    idColoniaValid = false;
    idColoniaTooltip.style.display = 'none';
    idColonia.classList.remove('is-valid', 'is-invalid');
    idColoniaFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    idColoniaFeedback.innerHTML = '&nbsp;';

    if (parseInt(idColonia.value) === 0) {
        idColoniaValid = false;
        idColonia.classList.remove('is-valid', 'is-invalid');
        idColoniaFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        idColoniaFeedback.innerHTML = '&nbsp;';
    } else {
        idColoniaValid = true;
        idColonia.classList.add('is-valid');
        idColoniaFeedback.classList.add('valid-feedback');
        idColoniaFeedback.innerHTML = 'Parece correcto';
    }
});

fechaInicio.addEventListener("change", e => {
    fechaInicioValid = false;
    fechaInicioTooltip.style.display = 'none';
    fechaInicio.classList.remove('is-valid', 'is-invalid');
    fechaInicioFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    fechaInicioFeedback.innerHTML = '&nbsp;';

    if (fechaInicio.value.trim().length === 0) {
        fechaInicioValid = false;
        fechaInicio.classList.remove('is-valid', 'is-invalid');
        fechaInicioFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        fechaInicioFeedback.innerHTML = '&nbsp;';
    } else {
        if (!esFechaValida(fechaInicio.value)) {
            fechaInicioValid = false;
            fechaInicio.classList.add('is-invalid');
            fechaInicioFeedback.classList.add('invalid-feedback');
            fechaInicioFeedback.innerHTML = '¡Ingrese una fecha válida en formato YYY-mm-dd!';
        } else {
            fechaInicioValid = true;
            fechaInicio.classList.add('is-valid');
            fechaInicioFeedback.classList.add('valid-feedback');
            fechaInicioFeedback.innerHTML = 'Parece correcto';
        }
    }
});

idPuesto.addEventListener("change", e => {
    idPuestoValid = false;
    idPuestoTooltip.style.display = 'none';
    idPuesto.classList.remove('is-valid', 'is-invalid');
    idPuestoFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    idPuestoFeedback.innerHTML = '&nbsp;';

    if (parseInt(idPuesto.value) === 0) {
        idPuestoValid = false;
        idPuesto.classList.remove('is-valid', 'is-invalid');
        idPuestoFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        idPuestoFeedback.innerHTML = '&nbsp;';
    } else {
        idPuestoValid = true;
        idPuesto.classList.add('is-valid');
        idPuestoFeedback.classList.add('valid-feedback');
        idPuestoFeedback.innerHTML = 'Parece correcto';
    }
});

/** ENVÍO DEL FORMULARIO PREVIA VALIDACIÓN DE LOS DATOS */
formNuevoEmpleado.addEventListener("submit", e => {
    e.preventDefault();

    if (!nombreValid) {
        nombreTooltip.innerHTML = '¡Complete este campo como se solicita!';
        nombreTooltip.style.display = 'block';
        nombre.focus();
        return
    }

    if (!apellidoPaternoValid) {
        apellidoPaternoTooltip.innerHTML = '¡Complete este campo como se solicita!';
        apellidoPaternoTooltip.style.display = 'block';
        apellidoPaterno.focus();
        return;
    }

    if (!apellidoMaternoValid) {
        apellidoMaternoTooltip.innerHTML = '¡Este campo no cumple con las reglas!';
        apellidoMaternoTooltip.style.display = 'block';
        apellidoMaterno.focus();
        return;
    }

    if (!fechaNacimientoValid) {
        fechaNacimientoTooltip.innerHTML = '¡Revise la fecha ingresada!';
        fechaNacimientoTooltip.style.display = 'block';
        fechaNacimiento.focus();
        return;
    }

    if (!idGeneroValid) {
        idGeneroTooltip.innerHTML = '¡Debe seleccionar un género!';
        idGeneroTooltip.style.display = 'block';
        idGenero.focus();
        return;
    }

    if (!seguroSocialValid) {
        seguroSocialTooltip.innerHTML = '¡Complete este campo como se solicita!';
        seguroSocialTooltip.style.display = 'block';
        seguroSocial.focus();
        return;
    }

    if (!rfcValid) {
        rfcTooltip.innerHTML = '¡Complete este campo como se solicita!';
        rfcTooltip.style.display = 'block';
        rfc.focus();
        return;
    }

    if (!emailValid) {
        emailTooltip.innerHTML = '¡Complete este campo como se solicita!';
        emailTooltip.style.display = 'block';
        email.focus();
        return;
    }

    if (!telefonoCasaValid) {
        telefonoCasaTooltip.innerHTML = '¡Complete este campo como se solicita!';
        telefonoCasaTooltip.style.display = 'block';
        telefonoCasa.focus();
        return;
    }

    if (!telefonoCelularValid) {
        telefonoCelularTooltip.innerHTML = '¡Complete este campo como se solicita!';
        telefonoCelularTooltip.style.display = 'block';
        telefonoCelular.focus();
        return;
    }

    if (!calleValid) {
        calleTooltip.innerHTML = '¡Complete este campo como se solicita!';
        calleTooltip.style.display = 'block';
        calle.focus();
        return;
    }

    if (!numeroExteriorValid) {
        numeroExteriorTooltip.innerHTML = '¡Este campo no cumple con las reglas!';
        numeroExteriorTooltip.style.display = 'block';
        numeroExterior.focus();
        return;
    }

    if (!numeroInteriorValid) {
        numeroInteriorTooltip.innerHTML = '¡Este campo no cumple con las reglas!';
        numeroInteriorTooltip.style.display = 'block';
        numeroInterior.focus();
        return;
    }

    if (!codigoPostalValid) {
        codigoPostalTooltip.innerHTML = '¡Este campo no cumple con las reglas!';
        codigoPostalTooltip.style.display = 'block';
        codigoPostal.focus();
        return;
    }

    if (!idColoniaValid) {
        idColoniaTooltip.innerHTML = '¡Se requiere seleccionar la colonia!';
        idColoniaTooltip.style.display = 'block';
        idColonia.focus();
        return;
    }

    if (!fechaInicioValid) {
        fechaInicioTooltip.innerHTML = '¡Ingrese una fecha válida!';
        fechaInicioTooltip.style.display = 'block';
        fechaInicio.focus();
        return;
    }

    if (!idPuestoValid) {
        idPuestoTooltip.innerHTML = '¡Se debe seleccionar una opción!';
        idPuestoTooltip.style.display = 'block';
        idPuesto.focus();
        return;
    }

    if (!profileImgValid) {
        profileImgTooltip.innerHTML = '¡Se debe seleccionar un archivo de tipo imagen (extensión .jpeg, .jpg, .png)!';
        profileImgTooltip.style.display = 'block';
        profileImg.focus();
        return;
    }

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
        formData.append('nombre', nombre.value);
        formData.append('apellido_paterno', apellidoPaterno.value);
        formData.append('apellido_materno', apellidoMaterno.value);
        formData.append('fecha_nacimiento', fechaNacimiento.value);
        formData.append('id_genero', idGenero.value);
        formData.append('seguro_social', seguroSocial.value);
        formData.append('rfc', rfc.value);
        formData.append('email', email.value);
        formData.append('telefono_casa', telefonoCasa.value);
        formData.append('telefono_celular', telefonoCelular.value);
        formData.append('calle', calle.value);
        formData.append('numero_exterior', numeroExterior.value);
        formData.append('numero_interior', numeroInterior.value);
        formData.append('id_colonia', idColonia.value);
        formData.append('fecha_inicio', fechaInicio.value);
        formData.append('id_puesto', idPuesto.value);
        formData.append('token', token);

        // SE AGREGA LA IMAGEN CARGADA
        let imgProfile = document.querySelector("#img-result");
        if (typeof (imgProfile) != 'undefined' && imgProfile != null) {
            formData.append('profile_img', imgProfile.getAttribute('src'));
        } else {
            formData.append('profile_img', '');
        }


        xhttp.onreadystatechange = function() {
            if (xhttp.readyState === 4 && xhttp.status === 200)
            {
                // VACIAR EL FORMULARIO
                formNuevoEmpleado.reset();
                nombreValid = false;
                apellidoPaternoValid = false;
                apellidoMaternoValid = true;
                fechaNacimientoValid = false;
                idGeneroValid = false;
                seguroSocialValid = false;
                rfcValid = false;
                emailValid = false;
                telefonoCasaValid = false;
                telefonoCelularValid = false;
                calleValid = false;
                numeroExteriorValid = true;
                numeroInteriorValid = true;
                codigoPostalValid = false;
                idColoniaValid = false;
                fechaInicioValid = false;
                idPuestoValid = false;
                profileImgValid = true;
                let inputs = document.querySelectorAll(".is-valid");
                for (let i = 0; i < inputs.length; i++)
                {
                    inputs[i].classList.remove('is-valid');
                }
                let feedbacks = document.querySelectorAll(".valid-feedback");
                for (let i = 0; i < feedbacks.length; i++)
                {
                    feedbacks[i].classList.remove('valid-feedback');
                    feedbacks[i].innerHTML = '&nbsp;';
                }
                idColonia.innerHTML = '<option value="0">Ingrese primero el código postal</option>';
                profileImg.value = "";
                containerProfileImg.classList.add("animate__fadeOut");
                setTimeout(function () {
                    containerProfileImg.classList.remove('animate__fadeIn', 'animate__fadeOut');
                    containerProfileImg.innerHTML = '';
                    btnRemoveImg.style.display = 'none';
                }, 1000);
                // HABILITAR EL BOTÓN DE SUBMIT DEL FORMULARIO
                btnSubmit.disabled = false;
                // OCULTAR MODAL DE LOADING
                document.querySelector("#modal-loading").style.display = "none";
                // MOSTRAR MODAL DE INFO
                document.querySelector("#modal-info").style.display = "flex";
                // MOSTRAR EL MENSAJE
                let respuesta = JSON.parse(xhttp.responseText);
                document.querySelector("#modal-info-message").innerHTML = respuesta["message"];
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
        xhttp.open('POST', url.value + '/empleados/nuevo', true);
        xhttp.send(formData);
    });
})