<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">
<input type="hidden" id="registros" value="<?php echo count($data[1]); ?>">
<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Administración - Revisión de incapacidades</span>
            </h2>
        </div>

        <div class="card-body">
            <?php
            if (!$data[0]["resultado"]) {
                ?><div class="row">
                <div class="col-12">
                    <p><?php echo $data[0]["mensaje"]; ?></p>
                </div>
            </div>
            <?php
            } else {
                ?><form id="form-validate_incapacidad" action="<?php echo ENTORNO; ?>/administracion/incapacidades" method="post">
                <div class="row">
                    <input type="hidden" id="id_incapacidad" value="">
                    <input type="hidden" id="id_usuario" value="<?php echo $_SESSION["usuario"]["id"]; ?>">
                    <input type="hidden" id="csrf" value="<?php echo $_SESSION["usuario"]["csrf"]; ?>">

                    <div class="col-7 col-xs-12 col-sm-12 col-md-12 left" id="vista-comprobante">
                    </div>
                    <div class="col-5 col-xs-12 col-sm-12 col-md-12 right">
                        <div class="row">
                            <div class="col-12">
                                <label>Empleado:</label>
                                <input type="text" id="empleado" disabled class="form-control" value="">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6 col-xs-12">
                                <label>De:</label>
                                <input type="date" id="inicio" disabled class="form-control" value="">
                            </div>

                            <div class="col-6 col-xs-12">
                                <label>A:</label>
                                <input type="date" id="termino" disabled class="form-control" value="">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <label>Evaluar</label>
                                <select name="validacion" id="validacion" class="form-control">
                                    <option value="0">Seleccionar</option>
                                    <option value="1">Aceptar</option>
                                    <option value="2">Rechazar</option>
                                </select>
                                <div id="validacion-feedback" class="">&nbsp;</div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <label>Observaciones <span><small>(obligatoria solo en caso de rechazo de la incapacidad)</small></span></label>
                                <textarea name="observaciones" id="observaciones" cols="30" rows="2" class="form-control" placeholder="Mínimo 20 cáracteres en caso de rechazo."></textarea>
                                <div id="observaciones-feedback" class="">&nbsp;</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-dark w-50">Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <br>

            <!--
            TABLA DE INCAPACIDADES POR VALIDAR REGISTRADAS EN LA BD
            -->
            <div class="jwt-alert tooltip">
                <span id="tooltip-jwt" class="tooltip-box"></span>
            </div>

            <div>
                <table id="tabla-incapacidades_por_validar" class="table table-light table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Empleado</th>
                        <th>De:</th>
                        <th>A:</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    for ($i = 0; $i < count($data[1]); $i++) {
                        ?><tr>
                        <td><?php echo $data[1][$i]["id"]; ?></td>
                        <td><?php echo $data[1][$i]["empleado"]; ?></td>
                        <td><?php echo $data[1][$i]["fecha_inicio"]; ?></td>
                        <td><?php echo $data[1][$i]["fecha_termino"]; ?></td>
                        <td>
                            <div class="td-actions" onclick="cargarIncapacidad('<?php echo implode("|",$data[1][$i]); ?>')">
                                <i class="fa-solid fa-location-crosshairs"></i>
                                <span>Ver</span>
                            </div>
                        </td>
                    </tr>
                    <?php
                    }
                    ?><!-- ./ items -->
                    </tbody>
                </table>
            </div>
            <?php
            }
            ?><!-- -->
        </div>
    </div>
</div>
