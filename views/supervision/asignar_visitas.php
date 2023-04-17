<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">
<input type="hidden" id="id_usuario" value="<?php echo $_SESSION["usuario"]["id"]; ?>">
<input type="hidden" id="registros" value="<?php echo intval($data["total"][0]["total"]); ?>">

<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Supervisión - asignación de visitas</span>
            </h2>
        </div>

        <div class="card-body">
            <!--
            FORMULARIO PARA LA ASIGNACIÓN DE UN TÉCNICO QUE REALIZARÁ LA VISITA A UN CLIENTE
            -->
            <form id="form-asignar_visita" action="<?php echo ENTORNO; ?>/supervision/asignar_visitas" method="post">
                <input type="hidden" id="csrf" value="<?php echo $_SESSION["usuario"]["csrf"]; ?>">
                <input type="hidden" id="id_venta" value="">
                <div class="row">
                    <div class="col-5 col-xs-12 col-sm-12 col-md-12">
                        <label for="cliente">Cliente a visitar:</label>
                        <input type="text" class="form-control" disabled id="cliente" value="">
                    </div>

                    <div class="col-7 col-xs-12 col-sm-12 col-md-12">
                        <label for="tipo_de_venta">Tipo de venta:</label>
                        <input type="text" class="form-control" disabled id="tipo_de_venta" value="">
                    </div>
                </div>

                <div class="row">
                    <div class="col-3 col-xs-12 col-sm-8 col-md-4 col-lg-3">
                        <label for="fecha_programada">Fecha programada:</label>
                        <input type="text" class="form-control" disabled id="fecha_programada">
                    </div>

                    <div class="col-2 col-xs-12 col-sm-4 col-md-3 col-lg-2">
                        <label for="codigo_postal">Código postal:</label>
                        <input type="text" class="form-control" disabled id="codigo_postal">
                    </div>

                    <div class="col-3 col-xs-12 col-sm-12 col-md-5 col-lg-3">
                        <label for="colonia">Colonia:</label>
                        <input type="text" class="form-control" disabled id="colonia">
                    </div>

                    <div class="col-4 col-xs-12 col-sm-12 col-md-12 col-lg-4">
                        <label for="calle">Calle:</label>
                        <input type="text" class="form-control" disabled id="calle">
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="f-r">
                            <div class="row">
                                <div class="col-6 col-xs-12 col-sm-12 tooltip">
                                    <label for="id_tecnico">Técnico a asignar:</label>
                                    <select class="form-control" id="id_tecnico">
                                        <option value="0">Seleccione...</option>
                                        <?php
                                        foreach ($data["tecnicos"] as $tecnico) {
                                        ?><!-- option -->
                                        <option value="<?php echo $tecnico["id_tecnico"]; ?>"><?php echo $tecnico["tecnico"]; ?></option>
                                        <?php
                                        }
                                        ?><!-- options -->
                                    </select>
                                    <div id="id_tecnico-feedback" class=""></div>
                                    <div id="id_tecnico-tooltip" class="tooltip-box"></div>
                                </div>

                                <div class="col-3 col-xs-12 col-sm-6">
                                    <label for="">&nbsp;</label>
                                    <input type="button" onclick="cancelacion()" class="btn btn-warning w-100"
                                           value="Cancelar">
                                </div>

                                <div class="col-3 col-xs-12 col-sm-6">
                                    <label for="">&nbsp;</label>
                                    <input type="button" onclick="submitForm()" class="btn btn-dark w-100"
                                           value="Asignar">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!--
            TABLA CON LA LISTA DE VENTAS POR ASIGNAR TÉCNICO QUE REALICE LA VISITA
            -->
            <div class="row">
                <div class="col-12">
                    <strong>Lista de ventas por asignar técnico que realizará el servicio.</strong>
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
                        <th>Cliente</th>
                        <th>Colonia</th>
                        <th>Calle</th>
                        <th>CP</th>
                        <th>Tipo de venta</th>
                        <th>Fecha programada</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($data["ventas"] as $venta) {
                    ?>
                    <tr>
                        <td><input type="text" class="input-table" value="<?php echo $venta["id"]; ?>"></td>
                        <td><input type="text" class="input-table" value="<?php echo $venta["cliente"]; ?>"></td>
                        <td><input type="text" class="input-table" value="<?php echo $venta["colonia"]; ?>"></td>
                        <td><input type="text" class="input-table" value="<?php echo $venta["calle"]; ?>"></td>
                        <td><input type="text" class="input-table" value="<?php echo $venta["codigo_postal"]; ?>"></td>
                        <td><input type="text" class="input-table" value="<?php echo $venta["tipo_de_venta"]; ?>"></td>
                        <td><input type="text" class="input-table" value="<?php echo $venta["fecha_programada"]; ?>">
                        </td>
                        <td>
                            <div class="td-actions"
                                 onclick="asignacion(<?php echo "'" . implode("','", $venta) . "'"; ?>)">
                                <i class="fa-solid fa-person-chalkboard"></i>
                                <span>Asignar técnico</span>
                            </div>
                        </td>
                    </tr>
                    <?php
                    }
                    ?><!-- items -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
