<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">
<input type="hidden" id="csrf" value="<?php echo $_SESSION["usuario"]["csrf"]; ?>">
<input type="hidden" id="id_usuario" value="<?php echo $_SESSION["usuario"]["id"]; ?>">

<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Técnicos - Entradas</span>
            </h2>
        </div>

        <section class="card-body">
            <div id="container-entradas"></div>
        </section>
    </div>
</div>