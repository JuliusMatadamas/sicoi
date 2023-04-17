<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">
<input type="hidden" id="registros" value="<?php echo $data[0]; ?>">
<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Info - incapacidades</span>
            </h2>
        </div>

        <div class="card-body">
            <p>En esta sección podrás registrar-actualizar el período que estarás de incapacidad así como el comprobante expedido por el seguro social.</p>

            <!-- FORMULARIO PARA REGISTRAR/ACTUALIZAR UNA NUEVA INCAPACIDAD -->
            <form id="form-incapacidades" action="<?php echo ENTORNO; ?>/info/incapacidades" method="post">
                <input type="hidden" name="id_incapacidad" id="id_incapacidad" value="">
                <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION["usuario"]["id"]; ?>">
                <input type="hidden" name="csrf" id="csrf" value="<?php echo $_SESSION["usuario"]["csrf"]; ?>">
                <input type="hidden" name="action" id="action" value="create">

                <div class="row">
                    <!-- FECHAS, COMPROBANTE -->
                    <div class="col-4 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-12">
                                <ul>
                                    <li>Indica las fechas en que inicia y termina tu incapacidad.</li>
                                    <li>Luego carga el comprobante oficial del seguro social.</li>
                                </ul>
                            </div>
                        </div>

                        <!-- FECHA DE INICIO -->
                        <div class="row">
                            <div class="col-12 col-md-6 tooltip">
                                <label for="fecha_inicio">Inicia</label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" placeholder="yyyy-mm-dd" value="">
                                <div id="feedback-fecha_inicio" class="">&nbsp;</div>
                                <span id="tooltip-fecha_inicio" class="tooltip-box"></span>
                            </div>

                            <!-- FECHA DE TÉRMINO -->
                            <div class="col-12 col-md-6 tooltip">
                                <label for="fecha_termino">Termina</label>
                                <input type="date" name="fecha_termino" id="fecha_termino" class="form-control" placeholder="yyyy-mm-dd" value="">
                                <div id="feedback-fecha_termino" class="">&nbsp;</div>
                                <span id="tooltip-fecha_termino" class="tooltip-box"></span>
                            </div>
                        </div>

                        <!-- COMPROBANTE -->
                        <div class="row">
                            <div class="col-12 tooltip">
                                <label for="comprobante">Comprobante <small>(solo archivos en alguno de los siguientes formatos: .pdf, .png, .jpg, .jpeg)</small></label>
                                <input type="file" name="comprobante" id="comprobante" class="form-control" accept="application/pdf, image/*" value="">
                                <div id="feedback-comprobante" class="">&nbsp;</div>
                                <span id="tooltip-comprobante" class="tooltip-box"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-dark w-75">Guardar</button>
                            </div>
                        </div>
                        <div class="row" id="container-btn_cancel">
                        </div>
                    </div>

                    <!-- VISTA PREVIA COMPROBANTE -->
                    <div class="col-8 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-12">
                                <div id="comprobante-preview" class="container-preview">Vista previa</div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <hr>

            <h3>Tus incapacidades registradas.</h3>
            <div class="jwt-alert tooltip">
                <span id="tooltip-jwt" class="tooltip-box"></span>
            </div>

            <section id="tableContainer">
                <table id="tabla-incapacidades" class="table table-light table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>FECHA INICIO</th>
                        <th>FECHA TÉRMINO</th>
                        <th>COMPROBANTE</th>
                        <th>VALIDACIÓN</th>
                        <th>OBSERVACIONES</th>
                        <th colspan="2">ACCIONES</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    for ($i = 0; $i < count($data[1]); $i++) {
                    ?><!-- item -->
                    <tr>
                        <td><?php echo $data[1][$i]["id"]; ?></td>
                        <td><?php echo $data[1][$i]["fecha_inicio"]; ?></td>
                        <td><?php echo $data[1][$i]["fecha_termino"]; ?></td>
                        <td>
                            <div class="container-td" onclick="previewComprobante('<?php echo ENTORNO.$data[1][$i]["comprobante"]; ?>')">
                                <i class="fa-solid fa-expand"></i>
                                <span>Ver</span>
                            </div>
                        </td>
                        <td>
                            <?php
                            if ($data[1][$i]["validacion"] == 0) {
                                echo '<i class="fa-solid fa-circle-half-stroke"></i>';
                            } elseif ($data[1][$i]["validacion"] == 1) {
                                echo '<i class="fa-solid fa-circle-check"></i>';
                            } else {
                                echo '<i class="fa-solid fa-circle-xmark"></i>';
                            }
                            ?><!-- . -->
                        </td>
                        <td>
                            <input type="text" class="input-incapacidad" value="<?php echo $data[1][$i]["observaciones"]; ?>">
                        </td>
                        <td>
                            <?php
                            if ($data[1][$i]["validacion"] == 1) {
                                echo '<div class="container-td"><i class="fa-solid fa-minus"></i></div>';
                            } else {
                                echo '<div class="container-td" onclick="editarIncapacidad(';
                                echo $data[1][$i]["id"];
                                echo ')"><i class="fa-solid fa-pen-to-square"></i><span>Editar</span></div>';
                            }
                            ?><!-- . -->
                        </td>
                        <td>
                            <?php
                            if ($data[1][$i]["validacion"] == 1) {
                                echo '<div class="container-td"><i class="fa-solid fa-minus"></i></div>';
                            } else {
                                echo '<div class="container-td" onclick="confirmarEliminacionDeIncapacidad(';
                                echo $data[1][$i]["id"];
                                echo ')"><i class="fa-solid fa-trash-can"></i><span>Eliminar</span></div>';
                            }
                            ?><!-- . -->
                        </td>
                    </tr>
                    <?php
                    }
                    ?><!-- ./ items -->
                    </tbody>
                </table>
            </section>
        </div>
    </div>
</div>

<section id="modal-confirm" class="modal">
    <div class="card">
        <div class="card-header">
            <h1 class="text-warning">Por favor, confirme la acción.</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div id="modal-confirm_logout-message" class="col-12">¿Estás seguro que deseas eliminar la incapacidad?</div>
            </div>
            <hr>
            <div class="row">
                <div id="modal-btn" class="col-6 col-xs-12 text-center">
                    <button class="btn btn-warning w-75" onclick="procederConEliminacion()">Confirmar</button>
                </div>
                <div id="modal-btn" class="col-6 col-xs-12 text-center">
                    <button class="btn btn-dark w-75" onclick="ocultarModal(this); cancelarEliminacion()">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</section>
