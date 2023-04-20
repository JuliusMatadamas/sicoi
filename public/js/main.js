const FILETYPES = ["image/apng", "image/bmp", "image/gif", "image/jpeg", "image/pjpeg", "image/png", "image/svg+xml", "image/tiff", "image/webp", "image/x-icon"];

// OCULTAR EL MODAL AL DAR CLIC FUERA DEL ÁREA DEL MENSAJE
document.addEventListener('click', e => {
    if (e.target.getAttribute('class') == 'modal') {
        if (e.target.getAttribute("id") !== "modal-loading") {
            e.target.style.display = 'none';
        }
    }
});

function getPosition() {
    const options = {
        enableHighAccuracy: true,
        timeout: 5000,
        maximumAge: 0,
    };
    return new Promise((resolve, reject) => {
        navigator.geolocation.getCurrentPosition(resolve, reject, options);
    });
}

function soloLetrasEspaciosAcentos(cadena) {
    let regex = /^[A-Za-zÁÉÍÓÚÜáéíóúüñÑ ]+$/g;
    return regex.test(cadena);
}

function soloNumerosLetrasEspaciosAcentos(cadena) {
    let regex = /^[0-9A-Za-zÁÉÍÓÚÜáéíóúüñÑ ]+$/g;
    return regex.test(cadena);
}


function contieneNumeros(cadena) {
    let regex = /\d/;
    return regex.test(cadena);
}

function soloNumeros(cadena) {
    let regex = new RegExp('^[0-9]*$');
    return regex.test(cadena);
}

function ocultarModal(e) {
    e.parentNode.parentNode.parentNode.parentNode.parentNode.style.display = 'none';
}

function isValidEmail(email) {
    return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)
}

function soloLetrasConNumeros(str) {
    return /^[A-Za-z0-9]*$/.test(str);
}

function esFechaValida(dateString) {
    let el = dateString.replaceAll('/', '-').split('-');
    let d = new Date(parseInt(el[0]), parseInt(el[1]) - 1, parseInt(el[2]));
    if ((d instanceof Date && !isNaN(d)) === false) {
        return false;
    } else {
        return d.getMonth() !== parseInt(el[1]);
    }
}

function validFileType(file) {
    return FILETYPES.includes(file);
}

function confirmLogOut() {
    document.querySelector("#modal-confirm_logout").style.display = 'flex';
}

function logOut() {
    location.href = '/log_out';
}

function validateRfc(n, ap, am, fn, rfc) {
}

function getTime() {
    let now = new Date();
    return now.getTime();
}

function generateUniqueID() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let uniqueID = '';

    for (let i = 0; i < 100; i++) {
        uniqueID += chars.charAt(Math.floor(Math.random() * chars.length));
    }

    uniqueID += getTime();

    return uniqueID;
}

function asignarFechaActual() {
    let f = new Date();
    let day = f.getDate();
    let month = f.getMonth() + 1;
    if (month < 10) {
        month = "0" + month;
    }
    let year = f.getFullYear();

    return year + '-' + month + '-' + day;
}

function generateRandomHexColor() {
    // Genera un número hexadecimal de 6 dígitos
    const hexColor = Math.floor(Math.random() * 16777215).toString(16);
    // Agrega ceros a la izquierda si es necesario
    return "#" + "0".repeat(6 - hexColor.length) + hexColor;
}
