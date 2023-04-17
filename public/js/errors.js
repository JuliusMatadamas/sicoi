let btnInicio = document.querySelector("#btn-inicio");
let btnBack = document.querySelector("#btn-back");

btnInicio.addEventListener('click', e => {
    window.location = e.target.dataset.info;
})

btnBack.addEventListener('click', e => {
    window.history.back();
})