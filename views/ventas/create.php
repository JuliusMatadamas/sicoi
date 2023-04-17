<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">

<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Ventas - nueva</span>
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

            <!--
            FORMULARIO DE REGISTRO DE VENTA
            -->
            <form id="form-venta" action="<?php echo ENTORNO; ?>/ventas/nueva" method="post">
                <input type="hidden" id="csrf" value="<?php echo $_SESSION["usuario"]["csrf"]; ?>">
                <input type="hidden" id="id_usuario" value="<?php echo $_SESSION["usuario"]["id"]; ?>">

                <!-- INFO CLIENTE -->
                <?php
                if (!$data["cliente"]["parametro"]) {
                    ?><input type="hidden" id="id_cliente_valid" value="0">
                <div class="row">
                    <div class="col-6 col-md-12 col-sm-12 col-xs-12 tooltip">
                        <label for="id_cliente">Seleccione el cliente:</label>
                        <select id="id_cliente" class="form-control">
                            <option value="0">Seleccione</option>
                            <?php
                            foreach ($data["clientes"] as $cliente) {
                                ?><option value="<?php echo $cliente["id"]; ?>"><?php echo $cliente["nombre"]; ?></option>
                            <?php
                            }
                            ?><!-- items -->
                        </select>
                        <div id="id_cliente-feedback" class="">&nbsp;</div>
                        <div id="id_cliente-tooltip" class="tooltip-box"></div>
                    </div>
                </div>
                <?php
                } else {
                    if ($data["cliente"]["id"] === 0) {
                        ?><input type="hidden" id="id_cliente_valid" value="0">
                <div class="row">
                    <div class="col-12">
                        <small>
                            <strong>*El id recibido no corresponde a ningún cliente registrado en el sistema.</strong>
                        </small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6 col-md-12 col-sm-12 col-xs-12 tooltip">
                        <label for="id_cliente">Seleccione el cliente:</label>
                        <select id="id_cliente" class="form-control">
                            <option value="0">Seleccione</option>
                            <?php
                            foreach ($data["clientes"] as $cliente) {
                            ?><option value="<?php echo $cliente["id"]; ?>"><?php echo $cliente["nombre"]; ?></option>
                            <?php
                            }
                            ?><!-- items -->
                        </select>
                        <div id="id_cliente-feedback" class="">&nbsp;</div>
                        <div id="id_cliente-tooltip" class="tooltip-box"></div>
                    </div>
                </div>
                <?php
                    } else {
                        ?><input type="hidden" id="id_cliente_valid" value="1">
                <div class="row">
                    <div class="col-6 col-md-12 col-sm-12 col-xs-12 tooltip">
                        <input type="hidden" id="id_cliente" value="<?php echo $data["cliente"]["id"]; ?>">
                        <label>Cliente:</label>
                        <input type="text" disabled class="form-control" value="<?php echo $data["cliente"]["nombre"]; ?>">
                        <div id="id_cliente-feedback" class="">&nbsp;</div>
                        <div id="id_cliente-tooltip" class="tooltip-box"></div>
                    </div>
                </div>
                <?php
                    }
                }
                ?><!-- ./INFO CLIENTE -->

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
                                    ?><option value="<?php echo $tipoDeVenta["id"]; ?>"><?php echo $tipoDeVenta["tipo"]; ?></option>
                                    <?php
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
                                <input type="date" class="form-control" id="fecha_programada" value="">
                                <div id="fecha_programada-feedback" class="">&nbsp;</div>
                                <div id="fecha_programada-tooltip" class="tooltip-box"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-xl-6">
                        <div class="row">
                            <div class="col-12 tooltip">
                                <label for="observaciones">Observaciones:</label>
                                <textarea class="form-control" id="observaciones" placeholder="Al menos 10 caracteres, máximo 255 caracteres." cols="30" rows="5"></textarea>
                                <div id="observaciones-feedback" class="">&nbsp;</div>
                                <div id="observaciones-tooltip" class="tooltip-box"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 text-end">
                        <div class="form-buttons row">
                            <div class="col-6 col-xs-12">
                                <input id="btn-cancelar" type="button" value="Cancelar" class="btn btn-warning w-100">
                            </div>

                            <div class="col-6 col-xs-12">
                                <button type="submit" class="btn btn-dark w-100">Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>

