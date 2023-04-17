<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">
<div class="container-nueva_clave">
    <div class="card shadow">
        <div class="card-header">
            <h1 class="text-center">
                <i class="fa-solid fa-warehouse"></i>
                <span>SICOI</span>
            </h1>
        </div>
        <div class="card-body text-center">
            <?php if (empty($data)) { ?><!-- TOKEN NO RECIBIDO -->
            <H2>¡Error!</H2>

            <p>No se recibieron los parámetros necesarios para generar la nueva contraseña,</p>
            <p>de clic en la opción requerida</p>

            <br>

            <div class="row">
                <div class="col-6 col-xs-12">
                    <a href="<?php echo ENTORNO; ?>">
                        <i class="fa-solid fa-door-open"></i>
                        <span>Ir a la página de login</span>
                    </a>
                </div>
                <div class="col-6 col-xs-12">
                    <a href="<?php echo ENTORNO; ?>/reset_clave">
                        <i class="fa-solid fa-envelope-open-text"></i>
                        <span>Generar enlace de reset</span>
                    </a>
                </div>
            </div>
            <?php } else {
                if (!$data["result"]) { ?><!-- TOKEN NO VÁLIDO -->
            <H2>¡Error!</H2>

            <p>Los parámetros recibidos no fueron aceptados, posiblemente el token no sea válido o ya haya caducado.</p>
            <p>de clic en la opción requerida</p>

            <br>

            <div class="row">
                <div class="col-6 col-xs-12">
                    <a href="<?php echo ENTORNO; ?>">
                        <i class="fa-solid fa-door-open"></i>
                        <span>Ir a la página de login</span>
                    </a>
                </div>
                <div class="col-6 col-xs-12">
                    <a href="<?php echo ENTORNO; ?>/reset_clave">
                        <i class="fa-solid fa-envelope-open-text"></i>
                        <span>Generar enlace de reset</span>
                    </a>
                </div>
            </div>
            <?php
                } else {
                    ?><!-- TOKEN VALIDADO -->
            <H2>Reseteo de contraseña</H2>

            <form id="form-nueva_clave" action="<?php echo ENTORNO; ?>/nueva_clave" method="post">
                <input type="hidden" id="id" value="<?php echo $data["id"]; ?>">
                <div class="row">
                    <div class="col-6 col-xs-12 col-sm-12 tooltip">
                        <label for="password">Ingrese la nueva contraseña</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Entre 7 y 10 carácteres" value="">
                        <div id="feedback-password" class="">&nbsp;</div>
                        <span id="tooltip-password" class="tooltip-box"></span>
                    </div>

                    <div class="col-6 col-xs-12 col-sm-12 tooltip">
                        <label for="password_confirm">Confirme la nueva contraseña</label>
                        <input type="password" class="form-control" name="password_confirm" id="password_confirm" placeholder="Entre 7 y 10 carácteres" value="">
                        <div id="feedback-password_confirm" class="">&nbsp;</div>
                        <span id="tooltip-password_confirm" class="tooltip-box"></span>
                    </div>
                </div>

                <br>

                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-dark w-50">Guardar</button>
                    </div>
                </div>
            </form>
                <?php
                }
            } ?>
        </div>
    </div>
</div>
