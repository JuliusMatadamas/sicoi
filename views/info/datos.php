<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">
<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Info - datos de contacto</span>
            </h2>
        </div>

        <div class="card-body">
            <p>En esta sección podrás modificar/actualizar tus datos de contacto que actualmente se tienen en el sistema.</p>

            <form id="form-datos" action="<?php echo ENTORNO; ?>/info/datos">
                <input type="hidden" name="id" id="id" value="<?php echo $_SESSION["usuario"]["id"]; ?>">
                <input type="hidden" name="csrf" id="csrf" value="<?php echo $_SESSION["usuario"]["csrf"]; ?>">
                <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION["usuario"]["id"]; ?>">
                <div class="row">
                    <div class="col-2 col-md-4 col-sm-5 col-xs-12 tooltip">
                        <label id="label-codigo_postal" for="codigo_postal">Código postal</label>
                        <input type="text" name="codigo_postal" id="codigo_postal" class="form-control" placeholder="5 dígitos" value="<?php echo $data["cp"][0]["codigo_postal"]; ?>">
                        <div id="feedback-codigo_postal" class="">&nbsp;</div>
                        <span id="tooltip-codigo_postal" class="tooltip-box"></span>
                    </div>

                    <div class="col-10 col-md-8 col-sm-7 col-xs-12 tooltip">
                        <label id="label-id_colonia" for="id_colonia">Colonia</label>
                        <select name="id_colonia" id="id_colonia" class="form-control">
                            <option value="0">Seleccione...</option>
                            <?php
                            foreach ($data["colonias"] as $key => $value) {
                                echo '<option value="'.$value["id"].'" ';
                                if ($value["id"] == $data["empleado"][0]["id_colonia"])
                                    echo 'selected="selected"';
                                echo '>'.$value["colonia"].'</option>'.PHP_EOL;
                            }
                            ?><!-- ./ options -->
                        </select>
                        <div id="feedback-id_colonia" class="">&nbsp;</div>
                        <span id="tooltip-id_colonia" class="tooltip-box"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-8 col-sm-12 col-xs-12 tooltip">
                        <label id="label-calle" for="calle">Calle</label>
                        <input type="text" name="calle" id="calle" class="form-control" placeholder="Al menos un caracter" value="<?php echo $data["empleado"][0]["calle"]; ?>">
                        <div id="feedback-calle" class="">&nbsp;</div>
                        <span id="tooltip-calle" class="tooltip-box"></span>
                    </div>

                    <div class="col-2 col-sm-6 col-xs-6 tooltip">
                        <label id="label-numero_exterior" for="numero_exterior">N° exterior</label>
                        <input type="text" name="numero_exterior" id="numero_exterior" class="form-control" value="<?php echo $data["empleado"][0]["numero_exterior"]; ?>">
                        <div id="feedback-numero_exterior" class="">&nbsp;</div>
                        <span id="tooltip-numero_exterior" class="tooltip-box"></span>
                    </div>

                    <div class="col-2 col-sm-6 col-xs-6 tooltip">
                        <label id="label-numero_interior" for="numero_interior">N° interior</label>
                        <input type="text" name="numero_interior" id="numero_interior" class="form-control" value="<?php echo $data["empleado"][0]["numero_interior"]; ?>">
                        <div id="feedback-numero_interior" class="">&nbsp;</div>
                        <span id="tooltip-numero_interior" class="tooltip-box"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 col-md-4 col-sm-12 col-xs-12 tooltip">
                        <label id="label-email" for="email">Correo electrónico</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="correo@ejemplo.com" value="<?php echo $data["empleado"][0]["email"]; ?>">
                        <div id="feedback-email" class="">&nbsp;</div>
                        <span id="tooltip-email" class="tooltip-box"></span>
                    </div>

                    <div class="col-3 col-md-4 col-sm-6 col-xs-12 tooltip">
                        <label id="label-telefono_casa" for="telefono_casa">Tel. Casa</label>
                        <input type="text" name="telefono_casa" id="telefono_casa" class="form-control" placeholder="10 dígitos" value="<?php echo $data["empleado"][0]["telefono_casa"]; ?>">
                        <div id="feedback-telefono_casa" class="">&nbsp;</div>
                        <span id="tooltip-telefono_casa" class="tooltip-box"></span>
                    </div>

                    <div class="col-3 col-md-4 col-sm-6 col-xs-12 tooltip">
                        <label id="label-telefono_celular" for="telefono_celular">Tel. Celular</label>
                        <input type="text" name="telefono_celular" id="telefono_celular" class="form-control" placeholder="10 dígitos" value="<?php echo $data["empleado"][0]["telefono_celular"]; ?>">
                        <div id="feedback-telefono_celular" class="">&nbsp;</div>
                        <span id="tooltip-telefono_celular" class="tooltip-box"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="row form-buttons">
                            <div class="col-6 col-xs-12">
                                <input type="button" id="btn-cancelar" class="btn btn-warning w-100" value="Cancelar">
                            </div>

                            <div class="col-6 col-xs-12">
                                <button type="submit" class="btn btn-dark w-100">Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
