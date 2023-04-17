<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">
<input type="hidden" id="registros" value="<?php echo $data[0]; ?>">

<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Ventas - consulta</span>
            </h2>
        </div>

        <section class="card-body">
            <!--
            ENLACES
            -->
            <div class="row">
                <div class="col-4 col-xs-12 link-venta">
                    <a href="<?php echo ENTORNO; ?>/ventas/dashboard" class="a-venta">
                        <h3>
                            <i class="fa-solid fa-chart-line"></i>
                            <span> Dashboard</span>
                        </h3>
                    </a>
                </div>

                <div class="col-4 col-xs-12 link-venta">
                    <a href="<?php echo ENTORNO; ?>/ventas/nueva" class="a-venta">
                        <h3>
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                            <span> Nueva</span>
                        </h3>
                    </a>
                </div>

                <div class="col-4 col-xs-12 link-venta">
                    <a href="<?php echo ENTORNO; ?>/ventas/reportes" class="a-venta">
                        <h3>
                            <i class="fa-solid fa-money-bill-trend-up"></i>
                            <span> Reportes</span>
                        </h3>
                    </a>
                </div>
            </div>

            <!--
            PERÍODO
            -->
            <div class="row">
                <div class="col-12">
                    <h4>Mostrar ventas realizadas</h4>
                </div>
            </div>

            <div class="jwt-alert tooltip">
                <span id="tooltip-jwt" class="tooltip-box"></span>
            </div>

            <div>
                <table id="tabla-ventas" class="table table-light table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Vendedor</th>
                        <th>Cliente</th>
                        <th>Venta</th>
                        <th>Fecha de venta</th>
                        <th>Fecha programada</th>
                        <th>Estado de visita</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($data[1] as $venta) {
                    ?>
                    <tr>
                        <td>
                            <input type="text" class="input-table" value="<?php echo $venta["id"]; ?>">
                        </td>
                        <td>
                            <input type="text" class="input-table" value="<?php echo $venta["vendedor"]; ?>">
                        </td>
                        <td>
                            <input type="text" class="input-table" value="<?php echo $venta["cliente"]; ?>">
                        </td>
                        <td>
                            <input type="text" class="input-table" value="<?php echo $venta["venta"]; ?>">
                        </td>
                        <td>
                            <input type="text" class="input-table" value="<?php echo $venta["fecha_venta"]; ?>">
                        </td>
                        <td>
                            <input type="text" class="input-table" value="<?php echo $venta["fecha_programada"]; ?>">
                        </td>
                        <td>
                            <input type="text" class="input-table" value="<?php echo $venta["estado_de_visita"]; ?>">
                        </td>
                        <td>
                            <?php
                            if (in_array(intval($venta["id_estado_de_visita"]), [1, 2, 5])) {
                            ?>
                            <div class="td-actions"
                                 onclick="window.location = '<?php echo ENTORNO . '/ventas/editar?id_venta=' . $venta["id"] . '&id_estado_de_visita=' . $venta["id_estado_de_visita"]; ?>'">
                                <i class="fa-regular fa-pen-to-square"></i>
                                <span>Editar</span>
                            </div>
                            <?php
                            }
                            ?><!-- -->
                        </td>
                    </tr>
                    <?php
                    }
                    ?><!-- ITEMS -->
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
