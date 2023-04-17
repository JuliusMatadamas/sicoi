<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">
<div class="container-login">
    <div class="card shadow">
        <div class="card-header">
            <h1 class="text-center">
                <i class="fa-solid fa-warehouse"></i>
                <span>SICOI</span>
            </h1>
        </div>
        <div class="card-body text-center">
            <form id="form-login" action="<?php echo ENTORNO; ?>" method="post">
                <p>Introduce los datos que se solicitan para que puedas usar la aplicación.</p>
                <div class="row">
                    <div class="col-12 tooltip">
                        <label for="usuario">Usuario</label>
                        <input type="text" class="form-control" name="usuario" id="usuario" placeholder="Entre 7 y 10 carácteres" value="">
                        <div id="feedback-usuario" class="">&nbsp;</div>
                        <span id="tooltip-usuario" class="tooltip-box"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 tooltip">
                        <label for="password">Contraseña</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Entre 7 y 10 carácteres" value="">
                        <div id="feedback-password" class="">&nbsp;</div>
                        <span id="tooltip-password" class="tooltip-box"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 col-xs-12">
                        <button id="btn-submit" type="submit" class="btn btn-dark w-100">Iniciar sesión</button>
                    </div>
                    <div class="col-6 col-xs-12">
                        <input id="btn-reset" type="button" class="btn btn-warning w-100" value="Resetear clave">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
