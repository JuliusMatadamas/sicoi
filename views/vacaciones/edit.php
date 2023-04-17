<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Info - vacaciones</span>
            </h2>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-6 col-md-12 col-sm-12 col-xs-12">
                    <a href="<?php echo ENTORNO; ?>/info/vacaciones/nuevo" class="a-vacacion">
                        <i class="fa-solid fa-file-circle-plus"></i>
                        <span>Crear nueva solicitud de vacaciones.</span>
                    </a>
                </div>

                <div class="col-6 col-md-12 col-sm-12 col-xs-12">
                    <a href="<?php echo ENTORNO; ?>/info/vacaciones" class="a-vacacion">
                        <i class="fa-solid fa-left-long"></i>
                        <span>Volver al listado de vacaciones.</span>
                    </a>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-12">
                    <h3>Editar solicitud de vacaciones</h3>
                </div>
            </div>

            <?php
            if (count($data) == 0) {
                ?><div class="row">
                <div class="col-12">
                    <p>El id recibido no fue encontrado en la BD.</p>
                </div>
            </div>
            <?php
            } else {
                if (intval($data["id_usuario"]) != intval($_SESSION["usuario"]["id"])) {
                    ?><div class="row">
                <div class="col-12">
                    <p>El id del registro no corresponde a tu usuario.</p>
                </div>
            </div>
            <?php
                } else {
                    if (intval($data["estado"]) != 0){
                        ?><div class="row">
                <div class="col-12">
                    <p>No se puede modificar la fecha porque ya se cumplió la fecha.</p>
                </div>
            </div>
            <?php
                    } else {
                        ?><form id="form-update_vacacion" action="<?php echo ENTORNO; ?>/info/vacaciones/update" method="post">
                <input type="hidden" name="csrf" id="csrf" value="<?php echo $_SESSION["usuario"]["csrf"]; ?>">
                <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION["usuario"]["id"]; ?>">
                <input type="hidden" name="id" id="id" value="<?php echo $data["id"]; ?>">
                <div class="row">
                    <div class="col-12">
                        <p>Ingresa la nueva fecha a tomar de vacaciones</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-3 col-md-6 col-sm-12 col-xs-12 tooltip">
                        <input type="date" class="form-control" name="fecha" id="fecha" value="<?php echo $data["fecha"]; ?>">
                        <span id="tooltip-fecha" class="tooltip-box"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-dark">Actualizar</button>
                    </div>
                </div>
            </form>
            <?php
                    }
                }
            }
            ?><!-- -->
        </div>
    </div>
</div>
