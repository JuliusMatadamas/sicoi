<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">
<div class="container container-reset">
    <div class="card shadow">
        <div class="card-header">
            <h1 class="text-center">
                <i class="fa-solid fa-warehouse"></i>
                <span>SICOI</span>
            </h1>
        </div>
        <div class="card-body">
            <form id="form-reset" action="<?php echo ENTORNO; ?>/reset_clave" method="post">
                <div class="row">
                    <col-12>
                        <h3>Reseteo de contraseña olvidada</h3>
                    </col-12>
                </div>

                <div class="row">
                    <div class="col-12 tooltip">
                        <label for="email">Ingrese el correo electrónico que proporcionó al registrarse</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="ejemplo@correo.com" value="">
                        <div id="feedback-email" class="">&nbsp;</div>
                        <span id="tooltip-email" class="tooltip-box"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <p>Se le mandará un enlace con los demás pasos para resetear su contraseña.</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 col-xs-12">
                        <a href="<?php echo ENTORNO; ?>">
                            <i class="fa-solid fa-circle-left"></i>
                            <span>Dirigirse al login</span>
                        </a>
                    </div>
                    <div class="col-6 col-xs-12">
                        <button type="submit" class="btn btn-dark w-100">Enviar enlace</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
