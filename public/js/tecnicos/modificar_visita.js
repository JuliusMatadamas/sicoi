const options = {};
const o0 = {};
o0["id"] = "option1";
o0["text"] = "Se localizó el domicilio, pero el cliente no se encuentra y no responde a las llamadas telefónicas.";
o0["info"] = "";
options[0] = o0;
const o1 = {};
o1["id"] = "option2";
o1["text"] = "Se localizó el domicilio, pero indican que el cliente no vive allí y no responde a las llamadas telefónicas.";
o1["info"] = "(indicar en observaciones el nombre de la persona que atendió)";
options[1] = o1;
const o2 = {};
o2["id"] = "option3";
o2["text"] = "Se localizó el domicilio, pero es un inmueble abandonado y el cliente no responde a las llamadas telefónicas.";
o2["info"] = "";
options[2] = o2;
const o3 = {};
o3["id"] = "option4";
o3["text"] = "No se localizó el domicilio y el cliente no responde a las llamadas telefónicas.";
o3["info"] = "";
options[3] = o3;
const o4 = {};
o4["id"] = "option5";
o4["text"] = "Otro";
o4["info"] = "(detallar en observaciones)";
options[4] = o4;
const opciones = document.querySelector("#opciones");
let acciones = document.querySelector("#acciones");
let f1 = document.querySelector("#form-registrar_visita");
let f2 = document.querySelector("#form-reprogramacion");
let f3 = document.querySelector("#form-no_encontrado");
let idVenta = document.querySelector("#id_venta");
let entorno = document.querySelector("#entorno");
let idUsuario = document.querySelector("#id_usuario");
let csrf = document.querySelector("#csrf");
let fechaProgramada = document.querySelector("#fecha_programada");
let msgTooltip = document.querySelector("#msg-tooltip");
let observacionesVisita = document.querySelector("#observaciones_visita");
let observacionesVisitaFeedback = document.querySelector("#observaciones_visita-feedback");
let observacionesVisitaTooltip = document.querySelector("#observaciones_visita-tooltip");
let observacionesVisitaValid = false;
let fechaReProgramada = document.querySelector("#fecha_reprogramada");
let fechaReProgramadaFeedback = document.querySelector("#fecha_reprogramada-feedback");
let fechaReProgramadaTooltip = document.querySelector("#fecha_reprogramada-tooltip");
let fechaReProgramadaValid = false;
let observacionesReprogramacion = document.querySelector("#observaciones_reprogramacion");
let observacionesReprogramacionFeedback = document.querySelector("#observaciones_reprogramacion-feedback");
let observacionesReprogramacionTooltip = document.querySelector("#observaciones_reprogramacion-tooltip");
let observacionesReprogramacionValid = false;
let observacionesNoEncontrado = document.querySelector("#observaciones_no_encontrado");
let observacionesNoEncontradoFeedback = document.querySelector("#observaciones_no_encontrado-feedback");
let observacionesNoEncontradoTooltip = document.querySelector("#observaciones_no_encontrado-tooltip");
let latitude, longitude;

Object.keys(options).forEach(function (key) {
    let option = options[key];

    let d0 = document.createElement("div");
    d0.classList.add("row");

    let d1 = document.createElement("div");
    d1.classList.add("col-12");

    let i = `<label><input type="radio" name="opcion[]" id="${option.id}"> ${option.text} <small>${option.info}</small>.</label>`;

    d1.innerHTML = i;

    d0.appendChild(d1);

    opciones.appendChild(d0);
});

observacionesVisita.addEventListener("keyup", () => {
    observacionesVisita.classList.remove("is-valid", "is-invalid");
    observacionesVisitaFeedback.classList.remove("valid-feedback", "invalid-feedback");
    observacionesVisitaFeedback.innerHTML = `&nbsp;`;
    observacionesVisitaTooltip.style.display = "none";
    observacionesVisitaTooltip.innerHTML = "&nbsp;";

    if (observacionesVisita.value.trim().length === 0) {
        observacionesVisita.classList.remove("is-valid", "is-invalid");
        observacionesVisitaFeedback.classList.remove("valid-feedback", "invalid-feedback");
        observacionesVisitaFeedback.innerHTML = "&nbsp;";
        observacionesVisitaValid = false;
        return;
    }

    if (observacionesVisita.value.trim().length < 10 || observacionesVisita.value.trim().length > 255) {
        observacionesVisita.classList.add("is-invalid");
        observacionesVisitaFeedback.classList.add("invalid-feedback");
        observacionesVisitaFeedback.innerHTML = "Las observaciones deben tener entre 10 y 255 caracteres.";
        observacionesVisitaValid = false;
        return;
    }

    observacionesVisita.classList.add("is-valid");
    observacionesVisitaFeedback.classList.add("valid-feedback");
    observacionesVisitaFeedback.innerHTML = "Parece correcto.";
    observacionesVisitaValid = true;
});


["click", "keyup", "change"].forEach(e => {
    fechaReProgramada.addEventListener(e, () => {
        fechaReProgramada.classList.remove("is-valid", "is-invalid");
        fechaReProgramadaFeedback.classList.remove("valid-feedback", "invalid-feedback");
        fechaReProgramadaFeedback.innerHTML = `&nbsp;`;
        fechaReProgramadaTooltip.style.display = "none";
        fechaReProgramadaTooltip.innerHTML = "&nbsp;";

        if (fechaReProgramada.value.trim().length === 0) {
            fechaReProgramada.classList.remove("is-valid", "is-invalid");
            fechaReProgramadaFeedback.classList.remove("valid-feedback", "invalid-feedback");
            fechaReProgramadaFeedback.innerHTML = "&nbsp;";
            fechaReProgramadaValid = false;
            return;
        }

        if (!validarFecha(fechaReProgramada.value)) {
            fechaReProgramada.classList.add("is-invalid");
            fechaReProgramadaFeedback.classList.add("invalid-feedback");
            fechaReProgramadaFeedback.innerHTML = "La fecha no debe ser igual o anterior a la actual ni posterior a 15 días.";
            fechaReProgramadaValid = false;
            return;
        }

        fechaReProgramada.classList.add("is-valid");
        fechaReProgramadaFeedback.classList.add("valid-feedback");
        fechaReProgramadaFeedback.innerHTML = "La fecha parece correcta.";
        fechaReProgramadaValid = true;
    });
});

observacionesReprogramacion.addEventListener("keyup", () => {
    observacionesReprogramacion.classList.remove("is-valid", "is-invalid");
    observacionesReprogramacionFeedback.classList.remove("valid-feedback", "invalid-feedback");
    observacionesReprogramacionFeedback.innerHTML = `&nbsp;`;
    observacionesReprogramacionTooltip.style.display = "none";
    observacionesReprogramacionTooltip.innerHTML = "&nbsp;";

    if (observacionesReprogramacion.value.trim().length === 0) {
        observacionesReprogramacion.classList.remove("is-valid", "is-invalid");
        observacionesReprogramacionFeedback.classList.remove("valid-feedback", "invalid-feedback");
        observacionesReprogramacionFeedback.innerHTML = "&nbsp;";
        observacionesReprogramacionValid = false;
        return;
    }

    if (observacionesReprogramacion.value.trim().length < 10 || observacionesReprogramacion.value.trim().length > 255) {
        observacionesReprogramacion.classList.add("is-invalid");
        observacionesReprogramacionFeedback.classList.add("invalid-feedback");
        observacionesReprogramacionFeedback.innerHTML = "Las observaciones deben tener entre 10 y 255 caracteres.";
        observacionesReprogramacionValid = false;
        return;
    }

    observacionesReprogramacion.classList.add("is-valid");
    observacionesReprogramacionFeedback.classList.add("valid-feedback");
    observacionesReprogramacionFeedback.innerHTML = "Parece correcto.";
    observacionesReprogramacionValid = true;
});

acciones.addEventListener("change", () => {
    if (parseInt(acciones.value) === 0) {
        f1.style.height = "0px";
        f2.style.height = "0px";
        f3.style.height = "0px";
        return;
    }

    if (parseInt(acciones.value) === 1) {
        f2.style.height = "0px";
        f3.style.height = "0px";
        f1.style.height = "300px";
        return;
    }

    if (parseInt(acciones.value) === 2) {
        f1.style.height = "0px";
        f3.style.height = "0px";
        f2.style.height = "300px";
        return;
    }

    if (parseInt(acciones.value) === 3) {
        f1.style.height = "0px";
        f2.style.height = "0px";
        f3.style.height = "580px";
    }
});

f3.addEventListener("click", () => {
    msgTooltip.style.display = "none";
    if (observacionesNoEncontrado.value.trim().length === 0) {
        observacionesNoEncontrado.classList.remove("is-valid", "is-invalid");
        observacionesNoEncontradoFeedback.classList.remove("valid-feedback", "invalid-feedback");
        observacionesNoEncontradoFeedback.innerHTML = `&nbsp;`;
        observacionesNoEncontradoTooltip.style.display = "none";
        observacionesNoEncontradoTooltip.innerHTML = "&nbsp;";
    }
});

observacionesNoEncontrado.addEventListener("keyup", () => {
    observacionesNoEncontrado.classList.remove("is-valid", "is-invalid");
    observacionesNoEncontradoFeedback.classList.remove("valid-feedback", "invalid-feedback");
    observacionesNoEncontradoFeedback.innerHTML = `&nbsp;`;
    observacionesNoEncontradoTooltip.style.display = "none";
    observacionesNoEncontradoTooltip.innerHTML = "&nbsp;";

    if (observacionesNoEncontrado.value.trim().length === 0) {
        observacionesNoEncontrado.classList.remove("is-valid", "is-invalid");
        observacionesNoEncontradoFeedback.classList.remove("valid-feedback", "invalid-feedback");
        observacionesNoEncontradoFeedback.innerHTML = `&nbsp;`;
        return;
    }

    if (observacionesNoEncontrado.value.trim().length < 10) {
        observacionesNoEncontrado.classList.add("is-invalid");
        observacionesNoEncontradoFeedback.classList.add("invalid-feedback");
        observacionesNoEncontradoFeedback.innerHTML = `Te faltan ${10 - observacionesNoEncontrado.value.trim().length} caracteres.`;
        return;
    }

    if (observacionesNoEncontrado.value.trim().length > 255) {
        observacionesNoEncontrado.classList.add("is-invalid");
        observacionesNoEncontradoFeedback.classList.add("invalid-feedback");
        observacionesNoEncontradoFeedback.innerHTML = `Te sobran ${observacionesNoEncontrado.value.trim().length - 255} caracteres.`;
        return;
    }

    observacionesNoEncontrado.classList.add("is-valid");
    observacionesNoEncontradoFeedback.classList.add("valid-feedback");
    observacionesNoEncontradoFeedback.innerHTML = `Parece correcto`;
});

function obtenerUbicacion(e, callback) {
    e.preventDefault();
    getPosition().then((position) => {
        latitude = position.coords.latitude;
        longitude = position.coords.longitude;
        callback();
    }).catch((error) => {
        console.log(error.message);
        latitude = "";
        longitude = "";
        callback();
    });
    return false;
}

function validarForm1() {
    if (!observacionesVisitaValid) {
        observacionesVisitaTooltip.style.display = "block";
        observacionesVisitaTooltip.innerHTML = "Las observaciones no cumplen con lo requerido.";
        return;
    }

    document.querySelector("#modal-loading").style.display = "flex";

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function (token) {

        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('id_venta', idVenta.value);
        formData.append('fecha_programada', fechaProgramada.innerText);
        formData.append('id_estado_de_visita', 3);
        formData.append('latitud', latitude.toString());
        formData.append('longitud', longitude.toString());
        formData.append('observaciones', observacionesVisita.value.trim());
        formData.append('csrf', csrf.value);
        formData.append('id_usuario', idUsuario.value);
        formData.append('token', token);


        xhttp.onreadystatechange = function () {
            if (xhttp.readyState) {
                if (xhttp.status === 200) {
                    document.querySelector("#modal-loading").style.display = "none";
                    let response = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-registrar_visita").style.display = "flex";
                }

                if (xhttp.status === 400) {
                    document.querySelector("#modal-loading").style.display = "none";
                    let response = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-error").style.display = "flex";
                    document.querySelector("#modal-error-message").innerHTML = `${response.message}`;
                }
            }
        }
        xhttp.open('post', f3.getAttribute("action"), true);
        xhttp.send(formData);
    });
}

function validarForm2() {
    if (!fechaReProgramadaValid) {
        fechaReProgramada.focus();
        fechaReProgramadaTooltip.style.display = "block";
        fechaReProgramadaTooltip.innerHTML = "La fecha a reprogramar no cumple con lo requerido.";
        return;
    }

    if (!observacionesReprogramacionValid) {
        observacionesReprogramacionTooltip.style.display = "block";
        observacionesReprogramacionTooltip.innerHTML = "Las observaciones no cumplen con lo requerido.";
        return;
    }

    document.querySelector("#modal-loading").style.display = "flex";

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function (token) {

        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('id_venta', idVenta.value);
        formData.append('fecha_programada', fechaReProgramada.value.trim());
        formData.append('id_estado_de_visita', 2);
        formData.append('latitud', latitude.toString());
        formData.append('longitud', longitude.toString());
        formData.append('observaciones', observacionesReprogramacion.value.trim());
        formData.append('csrf', csrf.value);
        formData.append('id_usuario', idUsuario.value);
        formData.append('token', token);


        xhttp.onreadystatechange = function () {
            if (xhttp.readyState) {
                if (xhttp.status === 200) {
                    document.querySelector("#modal-loading").style.display = "none";
                    let response = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-info").style.display = "flex";
                    document.querySelector("#modal-info-message").innerHTML = `${response.message}`;
                    document.querySelector("#modal-info button").addEventListener("click", () => {
                        window.location = entorno.value + "/tecnicos/visitas_asignadas";
                    });
                }

                if (xhttp.status === 400) {
                    document.querySelector("#modal-loading").style.display = "none";
                    let response = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-error").style.display = "flex";
                    document.querySelector("#modal-error-message").innerHTML = `${response.message}`;
                }
            }
        }
        xhttp.open('post', f3.getAttribute("action"), true);
        xhttp.send(formData);
    });
}

function validarForm3(e) {
    let inputs = document.querySelectorAll('input[type=radio]');
    let checked = false;
    let id;
    let text;

    inputs.forEach(input => {
        if (input.checked) {
            id = input.getAttribute("id");
            checked = true;
        }
    });

    if (!checked) {
        msgTooltip.style.display = "block";
        msgTooltip.innerHTML = "¡Debes marcar una de las opciones que se muestran!";
        return;
    }

    Object.keys(options).forEach(function (key) {
        let option = options[key];
        if (option.id === id) {
            text = option.text;
        }
    });

    if (id === "option2") {
        if (observacionesNoEncontrado.value.trim().length < 10 || observacionesNoEncontrado.value.trim().length > 255) {
            observacionesNoEncontrado.focus();
            observacionesNoEncontrado.classList.add("is-invalid");
            observacionesNoEncontradoFeedback.classList.add("invalid-feedback");
            observacionesNoEncontradoFeedback.innerHTML = `¡Ingrese el nombre de quién atendió en el domicilio! Al menos 10 caracteres y máximo 255.`;
            return;
        } else {
            text += " - " + observacionesNoEncontrado.value.trim();
        }
    }

    if (id === "option5") {
        if (observacionesNoEncontrado.value.trim().length < 10 || observacionesNoEncontrado.value.trim().length > 255) {
            observacionesNoEncontrado.focus();
            observacionesNoEncontrado.classList.add("is-invalid");
            observacionesNoEncontradoFeedback.classList.add("invalid-feedback");
            observacionesNoEncontradoFeedback.innerHTML = `Ingrese las observaciones, entre 10 y 255 caracteres.`;
            return;
        } else {
            text += " - " + observacionesNoEncontrado.value.trim();
        }
    }

    document.querySelector("#modal-loading").style.display = "flex";

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function (token) {

        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('id_venta', idVenta.value);
        formData.append('fecha_programada', fechaProgramada.innerText);
        formData.append('id_estado_de_visita', 5);
        formData.append('latitud', latitude.toString());
        formData.append('longitud', longitude.toString());
        formData.append('observaciones', text);
        formData.append('csrf', csrf.value);
        formData.append('id_usuario', idUsuario.value);
        formData.append('token', token);


        xhttp.onreadystatechange = function () {
            if (xhttp.readyState) {
                if (xhttp.status === 200) {
                    document.querySelector("#modal-loading").style.display = "none";
                    let response = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-info").style.display = "flex";
                    document.querySelector("#modal-info-message").innerHTML = `${response.message}`;
                    document.querySelector("#modal-info button").addEventListener("click", () => {
                        window.location = entorno.value + "/tecnicos/visitas_asignadas";
                    });
                }

                if (xhttp.status === 400) {
                    document.querySelector("#modal-loading").style.display = "none";
                    let response = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-error").style.display = "flex";
                    document.querySelector("#modal-error-message").innerHTML = `${response.message}`;
                }
            }
        }
        xhttp.open('post', f3.getAttribute("action"), true);
        xhttp.send(formData);
    });
}

function validarFecha(fecha) {
    // Convertir la fecha actual en un objeto Date en JavaScript.
    const fechaActual = new Date();

    // Convertir la fecha dada en un objeto Date en JavaScript.
    const fechaDada = new Date(fecha);

    // Calcular la diferencia de tiempo entre las dos fechas en milisegundos.
    const diferencia = fechaDada.getTime() - fechaActual.getTime();

    // Convertir la diferencia de tiempo en días.
    const diasDiferencia = Math.ceil(diferencia / (1000 * 3600 * 24));

    // Comprobar si la diferencia de tiempo es menor o igual a 15 días y si la fecha dada no es anterior a la fecha actual.
    if (diasDiferencia >= 1 && diasDiferencia <= 15) {
        return true;
    } else {
        return false;
    }
}
