<input type="hidden" id="url" value="<?php echo ENTORNO; ?>">
<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Agregar un nuevo usuario</span>
            </h2>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 text-start">
                    <a href="<?php echo ENTORNO; ?>/usuarios/consultar" class="a-usuario">
                        <h3>
                            <i class="fa-solid fa-users-viewfinder"></i>
                            <span> Consultar usuarios</span>
                        </h3>
                    </a>
                </div>
            </div>

            <br>

            <?php
            if (count($data[0]) === 0) {
            ?><!-- Sin empleados -->
            <div class="row">
                <div class="col-12 text-center">
                    <p>No se encontraron empleados activos a los cuales asignar usuario en el sistema.</p>
                    <img src="<?php echo ENTORNO ?>/public/img/nothing-found.png" alt="No se encontraron empleados">
                </div>
            </div>
            <?php
            } else {
            ?><!-- Formulario para alta de usuarios -->
            <form id="form-nuevo_usuario" action="<?php echo ENTORNO; ?>/usuarios/guardar" method="post">
                <input type="hidden" id="action" value="create">
                <input type="hidden" id="csrf" value="">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 tooltip">
                        <label for="id">Seleccione el empleado</label>
                        <select name="id" id="id" class="form-control">
                            <option value="0">Seleccione...</option>
                            <?php
                            for ($i = 0; $i < count($data[0]); $i++) {
                                echo '<option value="' . $data[0][$i]["id"] . '">' . $data[0][$i]["empleado"] . '</option>'.PHP_EOL;
                            }
                            ?><!-- ./ options -->
                        </select>
                        <div id="id-feedback">&nbsp;</div>
                        <span id="id-tooltip" class="tooltip-box"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 tooltip">
                        <label for="usuario">Ingrese el usuario</label>
                        <input type="text" name="usuario" id="usuario" class="form-control" placeholder="Entre 7 y 10 carácteres" value="">
                        <div id="usuario-feedback">&nbsp;</div>
                        <span id="usuario-tooltip" class="tooltip-box"></span>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 tooltip">
                        <label for="password">Ingrese la contraseña</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Entre 7 y 10 carácteres" value="">
                        <div id="password-feedback">&nbsp;</div>
                        <span id="password-tooltip" class="tooltip-box"></span>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 tooltip">
                        <label for="password_confirm">Confirme la contraseña</label>
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
                        if ($data[1][$i]["id"] == 1)
                            echo 'checked disabled="disabled" ';
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
                    <div class="col-12">
                        <div class="row form-buttons">
                            <div class="col-6 col-xs-12">
                                <input id="btn-cancelar" type="button" class="btn btn-warning w-100" value="Cancelar">
                            </div>

                            <div class="col-6 col-xs-12">
                                <input id="btn-submit" type="submit" class="btn btn-dark w-100" value="Guardar">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <?php
            }
            ?><!-- ./ Sin empleados -->
        </div>
    </div>
</div>
