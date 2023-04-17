<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Dashboard visitas</span>
            </h2>
        </div>

        <section class="card-body">
            <!--
            ENLACES
            -->
            <div class="row">
                <div class="col-4 col-xs-12 col-sm-12 link-sup">
                    <a href="<?php echo ENTORNO; ?>/supervision/asignar_visitas">
                        <h3>
                            <i class="fa-solid fa-sheet-plastic"></i>
                            <span> Asignar visitas</span>
                        </h3>
                    </a>
                </div>

                <div class="col-4 col-xs-12 col-sm-12 link-sup">
                    <a href="<?php echo ENTORNO; ?>/supervision/consultar_visitas">
                        <h3>
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <span> Consultar visitas</span>
                        </h3>
                    </a>
                </div>

                <div class="col-4 col-xs-12 col-sm-12 link-sup">
                    <a href="<?php echo ENTORNO; ?>/supervision/ubicacion_de_tecnicos">
                        <h3>
                            <i class="fa-solid fa-magnifying-glass-location"></i>
                            <span> Ubicación de técnicos</span>
                        </h3>
                    </a>
                </div>
            </div>

            <!-- DASHBOARD -->
            <div class="row">
                <div class="col-12">
                    <?php include_once "dashboards/radius-pie.php"; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <?php include_once "dashboards/columns.php"; ?>
                </div>
            </div>
        </section>
    </div>
</div>
