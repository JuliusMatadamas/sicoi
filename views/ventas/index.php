<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">

<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Ventas - dashboard</span>
            </h2>
        </div>

        <section class="card-body">
            <!--
            ENLACES
            -->
            <div class="row">
                <div class="col-4 col-xs-12 link-venta">
                    <a href="<?php echo ENTORNO; ?>/ventas/consultar" class="a-cliente">
                        <h3>
                            <i class="fa-solid fa-magnifying-glass-dollar"></i>
                            <span> Consultar</span>
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
                    <a href="<?php echo ENTORNO; ?>/clientes/reportes" class="a-venta">
                        <h3>
                            <i class="fa-solid fa-money-bill-trend-up"></i>
                            <span> Reportes</span>
                        </h3>
                    </a>
                </div>
            </div>

            <!-- DASHBOARD -->
            <div class="row">
                <div class="col-12">
                    <?php include_once "dashboards/ventas_ultimos7dias.php";?>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <?php include_once "dashboards/ventas_porSemanaEnElAnnio.php";?>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <?php include_once "dashboards/ventas_porTipoEnElAnnio.php";?>
                </div>
            </div>
        </section>
    </div>
</div>
