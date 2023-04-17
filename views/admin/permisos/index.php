<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">
<input type="hidden" id="registros" value="<?php echo count($data[1]); ?>">
<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Administración - Revisión de permisos</span>
            </h2>
        </div>

        <div class="card-body">
            <?php
            if (!$data[0]["resultado"]) {
                $msg = $data[0]["mensaje"];
                echo '<p>$msg</p>';
            } else {
                unset($data[0]);
                $permisos = $data[1];
                ?><form id="form-validate_permiso" action="<?php echo ENTORNO; ?>/administracion/permisos" method="post">
                <input type="hidden" id="csrf" value="<?php echo $_SESSION["usuario"]["csrf"]; ?>">
                <input type="hidden" id="id_usuario" value="<?php echo $_SESSION["usuario"]["id"]; ?>">
                <input type="hidden" id="id_permiso" value="">

                <div class="row">
                    <div class="col-4 col-xs-12 col-sm-8 col-md-6">
                        <label>Empleado</label>
                        <input type="text" class="form-control" disabled id="empleado" value="">
                    </div>
                </div>

                <div class="row">
                    <div class="col-5 col-xs-12 col-sm-12 col-md-12 col-lg-6">
                        <div class="col-12">
                            <p>De:</p>
                        </div>
                        <div class="col-6">
                            <input type="date" class="form-control" disabled id="fecha_inicio" value="">
                        </div>
                        <div class="col-6">
                            <input type="time" class="form-control" disabled id="hora_inicio" value="00:00">
                        </div>
                    </div>
                    <div class="col-5 col-xs-12 col-sm-12 col-md-12 col-lg-6">
                        <div class="col-12">
                            <p>A:</p>
                        </div>
                        <div class="col-6">
                            <input type="date" class="form-control" disabled id="fecha_termino" value="">
                        </div>
                        <div class="col-6">
                            <input type="time" class="form-control" disabled id="hora_termino" value="00:00">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <textarea id="motivo" cols="30" rows="3" class="form-control" disabled></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <p>Validación del permiso</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <select name="validacion" id="validacion" class="form-control">
                            <option value="0">Seleccione</option>
                            <option value="1">Conceder</option>
                            <option value="2">Denegar</option>
                        </select>
                        <div id="validacion-feedback" class=""></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <label>Observaciones <span><small>(Obligatorio en caso de denegar el permiso)</small></span></label>
                        <textarea id="observaciones" cols="30" rows="3" class="form-control" placeholder="Mínimo 20 caracteres"></textarea>
                        <div id="observaciones-feedback" class=""></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-dark w-xs-100 w-sm-100 w-md-50 w-lg-25 w-xl-25">Guardar</button>
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
                <table id="tabla-permisos_por_validar" class="table table-light table-bordered table-striped table-hover">
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
                    for ($i = 0; $i < count($permisos); $i++) {
                    ?><tr>
                        <td><?php echo $permisos[$i]["id"]; ?></td>
                        <td><?php echo $permisos[$i]["empleado"]; ?></td>
                        <td><?php echo $permisos[$i]["fecha_inicio"]." ".substr($permisos[$i]["hora_inicio"], 0, 5); ?></td>
                        <td><?php echo $permisos[$i]["fecha_termino"]." ".substr($permisos[$i]["hora_termino"], 0, 5); ?></td>
                        <td>
                            <div class="td-actions" onclick="cargarPermiso('<?php echo implode("|",$permisos[$i]); ?>')">
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
