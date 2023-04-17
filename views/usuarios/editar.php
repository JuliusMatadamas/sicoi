<input type="hidden" id="url" value="<?php echo ENTORNO; ?>">
<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Edición de usuario</span>
            </h2>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-6 col-sm-12 col-xs-12">
                    <a href="<?php echo ENTORNO; ?>/usuarios" class="a-usuario">
                        <h3>
                            <i class="fa-solid fa-users-viewfinder"></i>
                            <span> Consultar usuarios</span>
                        </h3>
                    </a>
                </div>

                <div class="col-6 col-sm-12 col-xs-12">
                    <a href="<?php echo ENTORNO; ?>/usuarios/nuevo" class="a-usuario">
                        <h3>
                            <i class="fa-solid fa-user-plus"></i>
                            <span> Agregar un nuevo usuario</span>
                        </h3>
                    </a>
                </div>
            </div>

            <br>

            <!-- Formulario para edición de usuario -->
            <form id="form-editar_usuario" action="<?php echo ENTORNO; ?>/usuarios/guardar" method="post">
                <input type="hidden" id="action" value="update">
                <input type="hidden" id="csrf" value="">
                <input type="hidden" id="id" value="<?php echo $data[0]["id"]; ?>">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 tooltip">
                        <label for="empleado">Empleado:</label>
                        <input type="text" name="empleado" id="empleado" class="form-control" disabled="disabled" value="<?php echo $data[0]["empleado"]; ?>">
                        <div id="empleado-feedback">&nbsp;</div>
                        <span id="empleado-tooltip" class="tooltip-box"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 tooltip">
                        <label for="usuario">Modificar el usuario</label>
                        <input type="text" name="usuario" id="usuario" class="form-control" placeholder="Entre 7 y 10 carácteres" value="<?php echo $data[0]["usuario"]; ?>">
                        <div id="usuario-feedback">&nbsp;</div>
                        <span id="usuario-tooltip" class="tooltip-box"></span>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 tooltip">
                        <label for="password">Modificar la contraseña</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Entre 7 y 10 carácteres" value="">
                        <div id="password-feedback">&nbsp;</div>
                        <span id="password-tooltip" class="tooltip-box"></span>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 tooltip">
                        <label for="password_confirm">Confirmar la contraseña</label>
                        <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="Debe coincidir con la contraseña anterior" value="">
                        <div id="password_confirm-feedback">&nbsp;</div>
                        <span id="password_confirm-tooltip" class="tooltip-box"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <p>Marque los módulos a los que tendrá acceso el usuario.</p>
                    </div>
                </div>

                <div class="row">
                    <?php
                    for ($i = 0; $i < count($data[1]); $i++) {
                        echo '<div class="col-xs-6 col-sm-6 col-md-4 col-lg-3 col-2">'.PHP_EOL;
                        echo '<input type="checkbox" class="form-check-input" ';
                        if ($data[1][$i]["id"] == 1) {
                            echo 'checked disabled="disabled" ';
                        } else {
                            if (in_array(intval($data[1][$i]["id"]), explode(",",$data[0]["modulos"])))
                                echo 'checked ';
                        }
                        echo 'value="';
                        echo $data[1][$i]["id"];
                        echo '">'.PHP_EOL;
                        echo '<span>';
                        echo $data[1][$i]["modulo"];
                        echo '</span>'.PHP_EOL;
                        echo '</div>'.PHP_EOL;
                    }
                    ?><!-- ./ módulos -->
                </div>

                <br>

                <div class="row">
                    <div class="col-8 col-lg-6 col-md-4 col-sm-12 col-xs-12">&nbsp;</div>
                    <div class="col-2 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <input id="btn-delete" type="button" class="btn btn-danger w-100" value="Eliminar">
                    </div>

                    <div class="col-2 col-lg-3 col-md-4 col-sm-12 col-xs-12">
                        <input id="btn-submit" type="button" class="btn btn-dark w-100" value="Actualizar">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<section id="modal-confirm" class="animate__animated modal">
    <div class="card">
        <div class="card-header">
            <h1 class="text-warning">Confirme la operación</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div id="modal-confirm-message" class="col-12"></div>
            </div>
            <hr>
            <div class="row">
                <div id="modal-btn" class="col-6">
                    <button class="btn btn-warning w-100" onclick="eliminarUsuario()">Continuar</button>
                </div>
                <div id="modal-btn" class="col-6">
                    <button class="btn btn-dark w-100" onclick="ocultarModal(this)">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</section>