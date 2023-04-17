const WIDTH = 720;
let d = new Date();
let entorno = document.querySelector("#entorno");
let regs = document.querySelector("#registros");
let month = d.getMonth() + 1;
if ( month < 10 ) month = '0' + month;
let day = d.getDate();
if ( day < 10 ) day = '0' + day;
let currentDate = `${d.getFullYear()}-${month}-${day}`;
let formIncapacidades = document.querySelector("#form-incapacidades");
let idUsuario = document.querySelector("#id_usuario");
let csrf = document.querySelector("#csrf");
let idIncapacidad = document.querySelector("#id_incapacidad");
let action = document.querySelector("#action");
let fechaInicio = document.querySelector("#fecha_inicio");
let fechaInicioFeedback = document.querySelector("#feedback-fecha_inicio");
let fechaInicioTooltip = document.querySelector("#tooltip-fecha_inicio");
let fechaInicioValid = true;
let fechaTermino = document.querySelector("#fecha_termino");
let fechaTerminoFeedback = document.querySelector("#feedback-fecha_termino");
let fechaTerminoTooltip = document.querySelector("#tooltip-fecha_termino");
let fechaTerminoValid = true;
let comprobante = document.querySelector("#comprobante");
let comprobanteFeedback = document.querySelector("#feedback-comprobante");
let comprobanteTooltip = document.querySelector("#tooltip-comprobante");
let comprobanteValid = false;
let comprobanteResult = '';
let comprobantePreview = document.querySelector("#comprobante-preview");
let formatos = ["application/pdf", "image/png", "image/jpg", "image/jpeg"];
let imageFormats = ["image/png", "image/jpg", "image/jpeg"];
let btnSubmit = document.querySelector('button[type="submit"]');
let containerBtnCancel = document.querySelector("#container-btn_cancel");
let jwtTooltip = document.querySelector("#tooltip-jwt");
let dt = new JSTable("#tabla-incapacidades", {
    serverSide: true,
    deferLoading: regs.value,
    ajax: entorno.value + '/api/incapacidades'
});

dt.on("fetchData", function(serverData){
    jwtTooltip.style.display = 'none';

    if (serverData.length == 0) {
        jwtTooltip.style.display = 'block';
        jwtTooltip.innerHTML = 'El token ha caducado, necesita iniciar sesión nuevamente.';
    } else {
        jwtTooltip.style.display = 'none';
    }
});

fechaInicio.value = currentDate;
fechaTermino.value = currentDate;

fechaInicio.addEventListener("change", e => {
    fechaInicio.classList.remove('is-valid', 'is-invalid');
    fechaInicioFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    fechaInicioFeedback.innerHTML = '&nbsp;';
    fechaInicioTooltip.style.display = "none";
    fechaInicioValid = false;

    if (fechaInicio.value.trim().length === 0) {
        fechaInicio.classList.remove('is-valid', 'is-invalid');
        fechaInicioFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        fechaInicioFeedback.innerHTML = '&nbsp;';
        fechaInicioTooltip.style.display = "none";
        fechaInicioValid = false;
        return;
    }

    if (!esFechaValida(fechaInicio.value)) {
        fechaInicio.classList.add('is-invalid');
        fechaInicioFeedback.classList.add('invalid-feedback');
        fechaInicioFeedback.innerHTML = 'La fecha no es válida';
        fechaInicioTooltip.style.display = "none";
        fechaInicioValid = false;
        return;
    }

    let fI = fechaInicio.value.split('-');
    let d1 = new Date(parseInt(fI[0]), parseInt(fI[1]) - 1, parseInt(fI[2]));

    let fT = fechaTermino.value.split('-');
    let d2 = new Date(parseInt(fT[0]), parseInt(fT[1]) - 1, parseInt(fT[2]));

    if ((d2.getTime() - d1.getTime()) < 0) {
        fechaTermino.classList.add('is-invalid');
        fechaTerminoFeedback.classList.add('invalid-feedback');
        fechaTerminoFeedback.innerHTML = 'La fecha de término debe ser igual o posterior a la fecha de inicio.';
        fechaTerminoTooltip.style.display = "none";
        fechaTerminoValid = false;
    }

    fechaInicio.classList.add('is-valid');
    fechaInicioFeedback.classList.add('valid-feedback');
    fechaInicioFeedback.innerHTML = 'Parece correcta';
    fechaInicioTooltip.style.display = "none";
    fechaInicioValid = true;
});

fechaTermino.addEventListener("change", e => {
    fechaTermino.classList.remove('is-valid', 'is-invalid');
    fechaTerminoFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    fechaTerminoFeedback.innerHTML = '&nbsp;';
    fechaTerminoTooltip.style.display = "none";
    fechaTerminoValid = false;

    if (fechaTermino.value.trim().length === 0) {
        fechaTermino.classList.remove('is-valid', 'is-invalid');
        fechaTerminoFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        fechaTerminoFeedback.innerHTML = '&nbsp;';
        fechaTerminoTooltip.style.display = "none";
        fechaTerminoValid = false;
        return;
    }

    if (!esFechaValida(fechaTermino.value)) {
        fechaTermino.classList.add('is-invalid');
        fechaTerminoFeedback.classList.add('invalid-feedback');
        fechaTerminoFeedback.innerHTML = 'La fecha no es válida';
        fechaTerminoTooltip.style.display = "none";
        fechaTerminoValid = false;
        return;
    }

    let fI = fechaInicio.value.split('-');
    let d1 = new Date(parseInt(fI[0]), parseInt(fI[1]) - 1, parseInt(fI[2]));

    let fT = fechaTermino.value.split('-');
    let d2 = new Date(parseInt(fT[0]), parseInt(fT[1]) - 1, parseInt(fT[2]));

    if ((d2.getTime() - d1.getTime()) < 0) {
        fechaTermino.classList.add('is-invalid');
        fechaTerminoFeedback.classList.add('invalid-feedback');
        fechaTerminoFeedback.innerHTML = 'La fecha de término debe ser igual o posterior a la fecha de inicio.';
        fechaTerminoTooltip.style.display = "none";
        fechaTerminoValid = false;
        return;
    }

    fechaTermino.classList.add('is-valid');
    fechaTerminoFeedback.classList.add('valid-feedback');
    fechaTerminoFeedback.innerHTML = 'Parece correcta';
    fechaTerminoTooltip.style.display = "none";
    fechaTerminoValid = true;
});

comprobante.addEventListener('change', e => {
    comprobante.classList.remove('is-valid', 'is-invalid');
    comprobanteFeedback.classList.remove('valid-feedback', 'invalid-feedback');
    comprobanteFeedback.innerHTML = "&nbsp;";
    comprobanteTooltip.style.display = "none";
    comprobanteValid = false;

    if (e.target.files[0] === undefined) {
        comprobante.classList.remove('is-valid', 'is-invalid');
        comprobanteFeedback.classList.remove('valid-feedback', 'invalid-feedback');
        comprobanteFeedback.innerHTML = "&nbsp;";
        comprobanteTooltip.style.display = "none";
        comprobanteValid = false;
        return;
    }

    if (!formatos.includes(e.target.files[0].type)) {
        comprobante.classList.add('is-invalid');
        comprobanteFeedback.classList.add('invalid-feedback');
        comprobanteFeedback.innerHTML = "Selecciona un archivo en alguno de los formatos que se indican!";
        comprobanteTooltip.style.display = "none";
        comprobanteValid = false;
        return;
    }

    comprobante.classList.add('is-valid');
    comprobanteFeedback.classList.add('valid-feedback');
    comprobanteFeedback.innerHTML = "Parece correcto";
    comprobanteTooltip.style.display = "none";
    comprobanteValid = true;

    const file = e.target.files[0];
    const reader = new FileReader();

    if (e.target.files[0].type == "application/pdf") {

        reader.onload = event => {
            let dataUrl = event.target.result;
            comprobantePreview.innerHTML = `<object id="obj-result" type="application/pdf" data="${dataUrl}" class="obj-preview"></object>`;
        };
        reader.readAsDataURL(file);
    }

    if (imageFormats.includes(e.target.files[0].type)) {
        reader.readAsDataURL(file);

        reader.onload = event => {
            let dataUrl = event.target.result;
            let image = document.createElement("img");
            image.src = dataUrl;
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
                new_image.classList.add("img-preview");
                new_image.src = new_image_url;
                comprobantePreview.innerHTML = '';
                comprobantePreview.appendChild(new_image);
            }
        }
    }
});

formIncapacidades.addEventListener("submit", e => {
    e.preventDefault();

    if (!fechaInicioValid) {
        fechaInicioTooltip.style.display = 'block';
        fechaInicioTooltip.innerHTML = `La fecha de inicio no es válida.`;
        fechaInicio.focus();
        return;
    }

    if (!fechaTerminoValid) {
        fechaTerminoTooltip.style.display = 'block';
        fechaTerminoTooltip.innerHTML = `La fecha de término no es válida.`;
        fechaTermino.focus();
        return;
    }

    if (!comprobanteValid) {
        comprobanteTooltip.style.display = 'block';
        comprobanteTooltip.innerHTML = `Se debe cargar el comprobante en un formato válido (pdf, jpeg, jpg, png)`;
        comprobante.focus();
        return;
    }

    if (document.querySelector("#obj-result") !== null) {
        comprobanteResult = document.querySelector("#obj-result").getAttribute('data');
    }

    if (document.querySelector("#img-result") !== null) {
        comprobanteResult = document.querySelector("#img-result").getAttribute('src');
    }

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function (token) {

        btnSubmit.disabled = true;
        document.querySelector("#modal-loading").style.display = 'flex';

        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('id', idIncapacidad.value);
        formData.append('id_usuario', idUsuario.value);
        formData.append('csrf', csrf.value);
        formData.append('action', action.value);
        formData.append('fecha_inicio', fechaInicio.value);
        formData.append('fecha_termino', fechaTermino.value);
        formData.append('comprobante', comprobanteResult);
        formData.append('token', token);


        xhttp.onreadystatechange = function () {
            if (xhttp.readyState === 4 && xhttp.status === 200) {
                cancelEdit();
                dt.update();

                btnSubmit.disabled = false;
                document.querySelector("#modal-loading").style.display = 'none';
                let response = JSON.parse(xhttp.responseText);
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
        xhttp.open('POST', formIncapacidades.getAttribute('action'), true);
        xhttp.send(formData);
    });
});

function previewComprobante(e) {
    let els = e.split('.');
    let ext = els.pop();

    if (ext == "pdf") {
        comprobantePreview.innerHTML = `<object id="obj-result" type="application/pdf" data="${e}" class="obj-preview"></object>`;
    } else {
        comprobantePreview.innerHTML = `<img src="${e}" class="img-preview">`;
    }
}

function editarIncapacidad(i) {
    comprobanteTooltip.style.display = 'none';

    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function (token) {

        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('id_incapacidad', i);
        formData.append('id_usuario', idUsuario.value);
        formData.append('csrf', csrf.value);
        formData.append('token', token);


        xhttp.onreadystatechange = function () {
            if (xhttp.readyState === 4 && xhttp.status === 200) {
                let response = JSON.parse(xhttp.responseText);
                let data = JSON.parse(response.data);
                idIncapacidad.value = data.id;
                action.value = "update";
                fechaInicio.value = data.fecha_inicio;
                fechaInicioValid = true;
                fechaTermino.value = data.fecha_termino;
                fechaTerminoValid = true;
                if (data.comprobante.split('.').pop() == 'pdf') {
                    const myFile = new File([""], entorno.value + data.comprobante, {
                        type: 'application/pdf',
                        lastModified: new Date(),
                    });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(myFile);
                    comprobante.files = dataTransfer.files;
                    comprobantePreview.innerHTML = `<object id="obj-result" type="application/pdf" data="${entorno.value + data.comprobante}" class="obj-preview"></object>`
                } else {
                    let imgType = 'image/' + data.comprobante.split('.').pop();
                    const myFile = new File([""], entorno.value + data.comprobante, {
                        type: imgType,
                        lastModified: new Date(),
                    });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(myFile);
                    comprobante.files = dataTransfer.files;
                    comprobantePreview.innerHTML = `<img src="${entorno.value + data.comprobante}" class="img-preview">`
                }
                comprobanteValid = true;
                btnSubmit.innerHTML = 'Actualizar';
                containerBtnCancel.innerHTML = `<div class="col-12 text-end">
                    <button type="button" class="btn btn-warning w-75" onclick="cancelEdit()">Cancelar</button>
                    </div>`;
            } else {
                if (xhttp.status === 400) {
                    let response = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-error").style.display = 'flex';
                    document.querySelector("#modal-error-message").innerHTML = response["message"];
                }
            }
        }
        xhttp.open('POST', entorno.value + '/api/incapacidades', true);
        xhttp.send(formData);
    });
}

function confirmarEliminacionDeIncapacidad(i) {
    idIncapacidad.value = i;
    action.value = 'delete';
    document.querySelector("#modal-confirm").style.display = 'flex';
}

function procederConEliminacion() {
    document.querySelector("#modal-confirm").style.display = 'none';
    document.querySelector("#modal-loading").style.display = 'flex';
    // ENVIAR VÍA AJAX LA INFORMACIÓN A LA BD
    grecaptcha.execute('6LftkdAiAAAAACOhv43G34x3CFqmKiBPgkGKyjkB', {
        action: 'submit'
    }).then(function (token) {

        let xhttp = new XMLHttpRequest();
        let formData = new FormData();
        formData.append('id', idIncapacidad.value);
        formData.append('id_usuario', idUsuario.value);
        formData.append('action', 'delete');
        formData.append('csrf', csrf.value);
        formData.append('token', token);


        xhttp.onreadystatechange = function () {
            if (xhttp.readyState === 4 && xhttp.status === 200) {
                dt.update();
                idIncapacidad.value = '';
                action.value = "create";
                let response = JSON.parse(xhttp.responseText);
                document.querySelector("#modal-loading").style.display = 'none';
                document.querySelector("#modal-info").style.display = 'flex';
                document.querySelector("#modal-info-message").innerHTML = response["message"];
            } else {
                if (xhttp.status === 400) {
                    idIncapacidad.value = '';
                    action.value = "create";
                    let response = JSON.parse(xhttp.responseText);
                    document.querySelector("#modal-loading").style.display = 'none';
                    document.querySelector("#modal-error").style.display = 'flex';
                    document.querySelector("#modal-error-message").innerHTML = response["message"];
                }
            }
        }
        xhttp.open('POST', formIncapacidades.getAttribute('action'), true);
        xhttp.send(formData);
    });
}

function cancelarEliminacion() {
    idIncapacidad.value = '';
    action.value = 'create';
}

function cancelEdit() {
    action.value = "create";
    fechaInicio.value = currentDate;
    fechaInicioValid = true;
    fechaTermino.value = currentDate;
    fechaTerminoValid = true;
    comprobante.value = '';
    comprobantePreview.innerHTML = 'Vista previa';
    comprobanteValid = false;
    btnSubmit.innerHTML = 'Guardar';
    containerBtnCancel.innerHTML = '';
}