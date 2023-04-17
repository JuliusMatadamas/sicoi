<input type="hidden" id="csrf" value="<?php echo $_SESSION["usuario"]["csrf"]; ?>">
<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">
<input type="hidden" id="id_usuario" value="<?php echo $_SESSION["usuario"]["id"]; ?>">

<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Almacén - Entradas</span>
            </h2>
        </div>

        <div class="card-body">
            <p>De clic en la organización origen de la entrada al inventario.</p>

            <br>

            <!-- FORM - ENTRADAS ORGANIZACIONES EXTERNAS -->
            <div id="container-organizaciones_externas" class="container-form_card">
                <div class="container-form_card-header">
                    <h3>Organizaciones externas</h3>
                    <span><i class="fa-solid fa-caret-down"></i></span>
                </div>
                <div class="container-form_card-body">
                    <form id="foe" action="">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 tooltip">
                                <label for="foe-id_inventario_origen">Organización externa de origen:</label>
                                <select class="form-control" id="foe-id_inventario_origen">
                                    <option value="0">Seleccione...</option>
                                    <?php
                                    foreach ($data["organizaciones_externas"] as $oe) {
                                    ?><!-- item -->
                                    <option value="<?php echo $oe["id"]; ?>"><?php echo $oe["organizacion"] . " - " . $oe["descripcion"]; ?></option>
                                    <?php
                                    }
                                    ?><!-- options -->
                                </select>
                                <div id="foe-id_inventario_origen-feedback" class="">&nbsp;</div>
                                <div id="foe-id_inventario_origen-tooltip" class="tooltip-box">&nbsp;</div>
                            </div>

                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 col-xl-3">
                                <label for="foe-id_estado_de_inventario_origen">Estado origen:</label>
                                <select class="form-control" disabled id="foe-id_estado_de_inventario_origen">
                                    <option value="3">En Tránsito</option>
                                </select>
                            </div>

                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 col-xl-3 tooltip">
                                <label for="foe-fecha_origen">Fecha de envío:</label>
                                <input type="date" class="form-control" id="foe-fecha_origen"
                                       max="<?php echo date("Y-m-d", strtotime("-1 day", strtotime(date("Y-m-d")))); ?>">
                                <div id="foe-fecha_origen-feedback" class="">&nbsp;</div>
                                <div id="foe-fecha_origen-tooltip" class="tooltip-box">&nbsp;</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <label for="foe-id_inventario_destino">Organización interna destino:</label>
                                <select class="form-control" disabled id="foe-id_inventario_destino">
                                    <option value="P9Ixj1Dfa1jrlNydFnkblI5yQW7fPurdpMpHzxKydeAw6pbHbWI0jsNxQEN8ugU6vZy3WZ7kbgieID75JIYdzk34of7JBNCnlQA2">
                                        AMT - ALMACÉN MATAMOROS
                                    </option>
                                </select>
                            </div>

                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 col-xl-3 tooltip">
                                <label for="foe-id_estado_de_inventario_destino">Estado destino:</label>
                                <select class="form-control" id="foe-id_estado_de_inventario_destino">
                                    <option value="0">Seleccione...</option>
                                    <?php
                                    foreach ($data["estados_de_inventarios"] as $edi) {
                                    if (intval($edi["id"]) !== 3) {
                                    ?><!-- item -->
                                    <option value="<?php echo $edi["id"]; ?>"><?php echo $edi["estado_de_inventario"]; ?></option>
                                    <?php
                                    }
                                    }
                                    ?><!-- options -->
                                </select>
                                <div id="foe-id_estado_de_inventario_destino-feedback" class="">&nbsp;</div>
                                <div id="foe-id_estado_de_inventario_destino-tooltip" class="tooltip-box">&nbsp;</div>
                            </div>

                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3 col-xl-3 tooltip">
                                <label for="foe-fecha_destino">Fecha de recepción:</label>
                                <input type="date" class="form-control" id="foe-fecha_destino"
                                       max="<?php echo date("Y-m-d"); ?>">
                                <div id="foe-fecha_destino-feedback" class="">&nbsp;</div>
                                <div id="foe-fecha_destino-tooltip" class="tooltip-box">&nbsp;</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-3 tooltip">
                                <label for="foe-id_categoria">Categoría del producto:</label>
                                <select class="form-control" id="foe-id_categoria">
                                    <option value="0">Seleccione...</option>
                                    <?php
                                    foreach ($data["categorias"] as $c) {
                                    ?><!-- item -->
                                    <option value="<?php echo $c["id"]; ?>"><?php echo $c["categoria"]; ?></option>
                                    <?php
                                    }
                                    ?><!-- options -->
                                </select>
                                <div id="foe-id_categoria-feedback">&nbsp;</div>
                                <div id="foe-id_categoria-tooltip" class="tooltip-box">&nbsp;</div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 tooltip">
                                <label for="foe-id_producto">Producto:</label>
                                <select class="form-control" disabled id="foe-id_producto">
                                </select>
                                <div id="foe-id_producto-feedback" class="">&nbsp;</div>
                                <div id="foe-id_producto-tooltip" class="tooltip-box">&nbsp;</div>
                            </div>

                            <div class="col-xs-12 col-sm-8 col-md-10 col-lg-2 col-xl-3 tooltip">
                                <label for="foe-numero_de_serie">N° de serie:</label>
                                <input type="text" class="form-control" disabled id="foe-numero_de_serie">
                                <div id="foe-numero_de_serie-feedback" class="">&nbsp;</div>
                                <div id="foe-numero_de_serie-tooltip" class="tooltip-box">&nbsp;</div>
                            </div>

                            <div class="col-xs-12 col-sm-4 col-md-2 col-lg-2 col-xl-2 tooltip">
                                <label for="foe-cantidad">Cantidad:</label>
                                <input type="text" class="form-control" disabled id="foe-cantidad">
                                <div id="foe-cantidad-feedback" class="">&nbsp;</div>
                                <div id="foe-cantidad-tooltip" class="tooltip-box">&nbsp;</div>
                            </div>
                        </div>

                        <br>

                        <div class="row">
                            <div class="col-12 text-end">
                                <input id="foe-btn_add" type="button" disabled
                                       class="btn btn-primary w-xs-100 w-sm-100 w-md-50 w-lg-50 w-xl-25"
                                       value="Agregar a la lista">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <p class="text-warning">No refresques la página o se perderán los datos que hayan sido
                                    añadidos a la lista.</p>
                            </div>
                        </div>

                        <br>

                        <div class="row">
                            <div class="col-12 tooltip">
                                <h4>Listado de entradas al inventario</h4>
                                <div id="foe_list-tooltip" class="tooltip-box">&nbsp;</div>
                            </div>
                        </div>

                        <div class="container-table">
                            <table id="foe-tabla_productos"
                                   class="table table-light table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th>Producto(s)</th>
                                    <th>N° serie</th>
                                    <th>Cantidad</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                        <br>

                        <div class="row">
                            <div class="col-12">
                                <p class="text-warning">Al dar clic en guardar, solo se registrarán los items que
                                    aparecen en la lista.</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 text-end">
                                <input id="foe-btn_submit" type="button"
                                       class="btn btn-dark w-xs-100 w-sm-100 w-md-50 w-lg-50 w-xl-25"
                                       value="Guardar">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <br>

            <!-- FORM - ENTRADAS ORGANIZACIONES INTERNAS -->
            <div id="container-organizaciones_internas" class="container-form_card">
                <div class="container-form_card-header">
                    <h3>Organizaciones internas</h3>
                    <span><i class="fa-solid fa-caret-down"></i></span>
                </div>
                <div class="container-form_card-body">
                </div>
            </div>
        </div>
    </div>
</div>
