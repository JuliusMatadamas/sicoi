let regs = document.querySelector("#registros");
let dt = new JSTable("#tabla-empleados", {
    serverSide: true,
    deferLoading: regs.value,
    ajax: url.value + '/api/empleados'
});

function mostrarProfileImg(url, name) {
    if (url.search("/public/img/profiles/") !== -1) {
        let view = document.querySelector(".view-profile_img");
        view.parentElement.parentElement.style.marginTop = '50px';
        view.innerHTML =   `<span class="closeview-profile_img" onclick="hideProfileImg()">&times;</span>
                        <img src="${url}" alt="">
                        <span class="info-profile_img">${name}</span>`;
    } else {
        let view = document.querySelector(".view-profile_img");
        view.parentElement.parentElement.style.marginTop = '50px';
        view.innerHTML =   `<span class="closeview-profile_img" onclick="hideProfileImg()">&times;</span>
                        <div>Sin imagen de perfil cargada.</div>
                        <span class="info-profile_img">${name}</span>`;
    }
}

function hideProfileImg() {
    let view = document.querySelector(".view-profile_img");
    view.parentElement.parentElement.style.marginTop = '0px';
    view.innerHTML =   ``;
}
