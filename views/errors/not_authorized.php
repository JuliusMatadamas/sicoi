<div class="container-error">
    <div class="card shadow">
        <div class="card-header">
            <h1 class="text-center">
                <i class="fa-solid fa-warehouse"></i>
                <span>SICOI</span>
            </h1>
        </div>
        <div class="card-body text-center">
            <h2>Error 403</h2>
            <H3>Recurso no autorizado</H3>
            <p>No cuentas con la autoriación para acceder a la página o recurso solicitado.</p>
            <div class="row">
                <div class="col-6 col-sm-12 col-xs-12">
                    <button id="btn-inicio" data-info="<?php echo ENTORNO; if(!isset($_SESSION["usuario"])) { echo '/log_out'; } ?>" class="btn btn-info w-100">Ir a la página de inicio</button>
                </div>
                <div class="col-6 col-sm-12 col-xs-12">
                    <button id="btn-back" class="btn btn-secondary w-100">Ir a la página anterior</button>
                </div>
            </div>
        </div>
    </div>
</div>