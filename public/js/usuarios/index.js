let regs = document.querySelector("#registros");
let dt = new JSTable("#tabla-usuarios", {
    serverSide: true,
    deferLoading: regs.value,
    ajax: url.value + '/api/usuarios'
});