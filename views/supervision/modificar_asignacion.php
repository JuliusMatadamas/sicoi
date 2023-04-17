<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">
<input type="hidden" id="id_usuario" value="<?php echo $_SESSION["usuario"]["id"]; ?>">

<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Supervisión - modificación de asignación de visita</span>
            </h2>
        </div>

        <div class="card-body">
            <!--
            FORMULARIO PARA LA ASIGNACIÓN DE UN TÉCNICO QUE REALIZARÁ LA VISITA A UN CLIENTE
            -->
            <form id="form-asignar_visita" action="<?php echo ENTORNO; ?>/supervision/actualizar_asignacion"
                  method="post">
                <input type="hidden" id="csrf" value="<?php echo $_SESSION["usuario"]["csrf"]; ?>">
                <input type="hidden" id="id_venta" value="<?php echo intval($data["id"]); ?>">
                <div class="row">
                    <div class="col-5 col-xs-12 col-sm-12 col-md-12">
                        <label for="cliente">Cliente a visitar:</label>
                        <input type="text" class="form-control" disabled id="cliente"
                               value="<?php echo $data["asignacion"][0]["cliente"]; ?>">
                    </div>

                    <div class="col-7 col-xs-12 col-sm-12 col-md-12">
                        <label for="tipo_de_venta">Tipo de venta:</label>
                        <input type="text" class="form-control" disabled id="tipo_de_venta"
                               value="<?php echo $data["asignacion"][0]["tipo_de_venta"]; ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-3 col-xs-12 col-sm-8 col-md-4 col-lg-3">
                        <label for="fecha_programada">Fecha programada:</label>
                        <input type="text" class="form-control" disabled id="fecha_programada"
                               value="<?php echo $data["asignacion"][0]["fecha_programada"]; ?>">
                    </div>

                    <div class="col-2 col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <label for="codigo_postal">Código postal:</label>
                        <input type="text" class="form-control" disabled id="codigo_postal"
                               value="<?php echo $data["asignacion"][0]["codigo_postal"]; ?>">
                    </div>

                    <div class="col-3 col-xs-12 col-sm-12 col-md-5 col-lg-3">
                        <label for="colonia">Colonia:</label>
                        <input type="text" class="form-control" disabled id="colonia"
                               value="<?php echo $data["asignacion"][0]["colonia"]; ?>">
                    </div>

                    <div class="col-4 col-xs-12 col-sm-12 col-md-12 col-lg-4">
                        <label for="calle">Calle:</label>
                        <input type="text" class="form-control" disabled id="calle"
                               value="<?php echo $data["asignacion"][0]["calle"]; ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="f-r">
                            <div class="row">
                                <div class="col-6 col-xs-12 col-sm-12 tooltip">
                                    <label for="id_tecnico">Técnico asignado:</label>
                                    <select class="form-control" id="id_tecnico">
                                        <option value="0">Ninguno</option>
                                        <?php
                                        foreach ($data["tecnicos"] as $tecnico) {
                                        intval($tecnico["id_tecnico"]) == intval($data["asignacion"][0]["id_tecnico_asignado"]) ? $d = "selected" : $d = "";
                                        ?><!-- option -->
                                        <option
                                                value="<?php echo $tecnico["id_tecnico"]; ?>"
                                            <?php echo $d; ?>
                                        ><?php echo $tecnico["tecnico"]; ?></option>
                                        <?php
                                        }
                                        ?><!-- options -->
                                    </select>
                                    <div id="id_tecnico-feedback" class=""></div>
                                    <div id="id_tecnico-tooltip" class="tooltip-box"></div>
                                </div>

                                <div class="col-3 col-xs-12 col-sm-6">
                                    <label for="">&nbsp;</label>
                                    <input type="button"
                                           onclick="window.location = '<?php echo ENTORNO; ?>/supervision/consultar_visitas'"
                                           class="btn btn-warning w-100"
                                           value="Cancelar">
                                </div>

                                <div class="col-3 col-xs-12 col-sm-6">
                                    <label for="">&nbsp;</label>
                                    <input type="button" onclick="submitForm()" class="btn btn-dark w-100"
                                           value="Modificar">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
