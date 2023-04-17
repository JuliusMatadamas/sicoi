<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">

<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Ventas - editar</span>
            </h2>
        </div>

        <section class="card-body">
            <!--
            ENLACES
            -->
            <div class="row">
                <div class="col-4 col-xs-12 link-venta">
                    <a href="<?php echo ENTORNO; ?>/ventas/dashboard" class="a-cliente">
                        <h3>
                            <i class="fa-solid fa-chart-line"></i>
                            <span> Dashboard</span>
                        </h3>
                    </a>
                </div>

                <div class="col-4 col-xs-12 link-venta">
                    <a href="<?php echo ENTORNO; ?>/ventas/consultar" class="a-cliente">
                        <h3>
                            <i class="fa-solid fa-magnifying-glass-dollar"></i>
                            <span> Consultar</span>
                        </h3>
                    </a>
                </div>

                <div class="col-4 col-xs-12 link-venta">
                    <a href="<?php echo ENTORNO; ?>/clientes/reportes" class="a-cliente">
                        <h3>
                            <i class="fa-solid fa-money-bill-trend-up"></i>
                            <span> Reportes</span>
                        </h3>
                    </a>
                </div>
            </div>

            <?php
            if (!$data["with"]) {
                ?><div class="row">
                <div class="col-12">
                    <h4>No se recibió al menos alguno de los parámetros necesarios para editar la venta.</h4>
                </div>
            </div>
            <?php
            } else {
                if (!$data["finded"]) {
                    ?><div class="row">
                <div class="col-12">
                    <h4>No se encontró ningún registro que coincida con los parámetros recibidos.</h4>
                </div>
            </div>
            <?php
                }
                else {
                    ?><!--
            FORMULARIO DE REGISTRO DE VENTA
            -->
            <form id="form-venta" action="<?php echo ENTORNO; ?>/ventas/editar" method="post">
                <input type="hidden" id="csrf" value="<?php echo $_SESSION["usuario"]["csrf"]; ?>">
                <input type="hidden" id="id_usuario" value="<?php echo $_SESSION["usuario"]["id"]; ?>">
                <input type="hidden" id="id_venta" value="<?php echo $data["id_venta"]; ?>">
                <input type="hidden" id="action" value="update">
                <div class="row">
                    <div class="col-6 col-md-12 col-sm-12 col-xs-12 tooltip">
                        <input type="hidden" id="id_cliente_valid" value="1">
                        <input type="hidden" id="id_cliente" value="<?php echo $data["id_cliente"]; ?>">
                        <label>Cliente:</label>
                        <input type="text" disabled class="form-control" value="<?php echo $data["cliente"]; ?>">
                    </div>
                </div>

                <!-- INFO DE VENTA -->
                <div class="row">
                    <div class="col-12 col-xl-6">
                        <div class="row">
                            <div class="col-12 tooltip">
                                <label for="id_tipo_de_venta">Venta:</label>
                                <select id="id_tipo_de_venta" class="form-control">
                                    <option value="0">Seleccione</option>
                                    <?php
                                    foreach ($data["tipos_de_ventas"] as $tipoDeVenta) {
                                        echo '<option value="';
                                        echo $tipoDeVenta["id"];
                                        echo '"';
                                        if ($tipoDeVenta["id"] === $data["id_tipo_de_venta"]) echo " selected";
                                        echo '>';
                                        echo $tipoDeVenta["tipo"];
                                        echo '</option>';
                                    }
                                    ?><!-- items -->
                                </select>
                                <div id="id_tipo_de_venta-feedback" class="">&nbsp;</div>
                                <div id="id_tipo_de_venta-tooltip" class="tooltip-box"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 tooltip">
                                <label for="fecha_programada">Fecha programada:</label>
                                <input type="date" class="form-control" id="fecha_programada" value="<?php echo $data["fecha_programada"]; ?>">
                                <div id="fecha_programada-feedback" class="">&nbsp;</div>
                                <div id="fecha_programada-tooltip" class="tooltip-box"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-xl-6">
                        <div class="row">
                            <div class="col-12 tooltip">
                                <label for="observaciones">Observaciones:</label>
                                <textarea class="form-control" id="observaciones" placeholder="Al menos 10 caracteres, máximo 255 caracteres." cols="30" rows="5"><?php echo $data["observaciones"]; ?></textarea>
                                <div id="observaciones-feedback" class="">&nbsp;</div>
                                <div id="observaciones-tooltip" class="tooltip-box"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 text-end">
                        <div class="form-buttons row">
                            <div class="col-4 col-xs-12">
                                <input id="btn-eliminar" type="button" value="Eliminar" class="btn btn-danger w-100">
                            </div>

                            <div class="col-4 col-xs-12">
                                <input id="btn-cancelar" type="button" value="Cancelar" class="btn btn-warning w-100">
                            </div>

                            <div class="col-4 col-xs-12">
                                <button type="submit" class="btn btn-dark w-100">Actualizar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <?php
                }
            }
            ?><!-- -->
        </section>
    </div>
</div>

<section id="modal-confirm_delete" class="modal">
    <div class="card">
        <div class="card-header">
            <h1 class="text-warning">Eliminar la venta</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div id="modal-confirm_delete-message" class="col-12">¿Estás seguro que deseas eliminar esta venta?</div>
            </div>
            <hr>
            <div class="row">
                <div id="modal-btn" class="col-6 col-xs-12 text-center">
                    <button class="btn btn-warning w-75" onclick="eliminarVenta()">Confirmar</button>
                </div>
                <div id="modal-btn" class="col-6 col-xs-12 text-center">
                    <button class="btn btn-dark w-75" onclick="ocultarModal(this)">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</section>