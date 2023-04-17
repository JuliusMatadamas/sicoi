<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Técnicos - visitas asignadas</span>
            </h2>
        </div>

        <div class="card-body">
            <?php
            if (count($data["visitas_asignadas"]) === 0) {
            ?><h3>No se encontraron visitas por realizar asignadas.</h3>
            <?php
            } else {
            foreach ($data["visitas_asignadas"] as $visita) {
            ?><!-- item -->
            <section id="detail-<?php echo $visita["id"]; ?>" class="visita">
                <a href="#detail-<?php echo $visita["id"]; ?>">
                    <span class="row">
                        <span class="col-6 col-xs-12 col-sm-12">Tipo: <?php echo $visita["tipo_de_venta"]; ?></span>
                        <span class="col-6 col-xs-12 col-sm-12">Fecha programada: <?php echo $visita["fecha_programada"]; ?></span>
                    </span>
                </a>
                <div class="detail">
                    <div class="row">
                        <div class="col-6">
                            <p><strong>Cliente:</strong> <?php echo $visita["cliente"]; ?></p>
                        </div>

                        <div class="col-6">
                            <p><strong>Colonia:</strong> <?php echo $visita["colonia"]; ?></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <p><strong>Domicilio:</strong> <?php echo $visita["calle"]; ?>
                                <span> <strong>Ext:</strong> <?php echo $visita["numero_exterior"]; ?></span><span> <strong>Int:</strong> <?php echo $visita["numero_interior"]; ?></span>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <p><strong>Observaciones:</strong> <?php echo $visita["observaciones"]; ?></p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 col-sm-12 col-xs-12 tel">
                            <a href="tel:+52<?php echo $visita["telefono_casa"]; ?>">
                                <strong>Tel. Casa:</strong>
                                <i class="fa-solid fa-phone-flip"></i>
                                <span><?php echo $visita["telefono_casa"]; ?></span>
                            </a>
                        </div>

                        <div class="col-6 col-sm-12 col-xs-12 tel">
                            <a href="tel:+52<?php echo $visita["telefono_celular"]; ?>">
                                <strong>Tel. Celular:</strong>
                                <i class="fa-solid fa-phone-flip"></i>
                                <span><?php echo $visita["telefono_celular"]; ?></span>
                            </a>
                        </div>
                    </div>

                    <div class="row mb">
                        <div class="col-12 text-end">
                            <form action="<?php echo ENTORNO; ?>/tecnicos/modificar_visita" method="post">
                                <input type="hidden" name="id_venta" value="<?php echo $visita["id"]; ?>">
                                <input type="hidden" name="id_cliente" value="<?php echo $visita["id_cliente"]; ?>">
                                <button type="submit"
                                        class="btn btn-secondary w-xl-25 w-lg-25 w-md-50 w-sm-100 w-xs-100">Modificar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
            <?php
            }
            }
            ?><!-- visitas asignadas -->
        </div>
    </div>
</div>
