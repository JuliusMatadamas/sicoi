<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">
<input type="hidden" id="registros" value="<?php echo $data[0]; ?>">
<input type="hidden" id="csrf" value="<?php echo $_SESSION["usuario"]["csrf"]; ?>">
<input type="hidden" id="idUsuario" value="<?php echo $_SESSION["usuario"]["id"]; ?>">
<input type="hidden" id="idPermisoPorEliminar" value="">
<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Info - permisos</span>
            </h2>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-5 col-xs-12 col-sm-10 col-md-7">
                    <a href="<?php echo ENTORNO; ?>/info/permisos/nuevo" class="a-permiso">
                        <i class="fa-solid fa-file-circle-plus"></i>
                        <span>Crear nueva solicitud de permiso.</span>
                    </a>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-12">
                    <h3>Listado de permisos solicitados</h3>
                    <p><?php
                        if ($data[0] == 0) {
                            if (gettype($data[1]) == "string") {
                                echo $data[1];
                            }
                        }
                        ?></p>
                    <div class="jwt-alert tooltip">
                        <span id="tooltip-jwt" class="tooltip-box"></span>
                    </div>
                    <!-- Mostrar la tabla con el listado de permisos solicitados -->
                    <section id="tableContainer">
                        <table id="tabla-permisos" class="table table-light table-bordered table-striped table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>F. INICIO</th>
                                <th>H. INICIO</th>
                                <th>F. TÉRMINO</th>
                                <th>H. TÉRMINO</th>
                                <th>MOTIVO</th>
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
                                <td><?php
                                    echo $data[1][$i]["id"];
                                    ?></td><!-- ID -->
                                <td><?php
                                    echo $data[1][$i]["fecha_inicio"];
                                    ?></td><!-- FECHA INICIO -->
                                <td><?php
                                    echo $data[1][$i]["hora_inicio"];
                                    ?></td><!-- HORA INICIO -->
                                <td><?php
                                    echo $data[1][$i]["fecha_termino"];
                                    ?></td><!-- FECHA TÉRMINO -->
                                <td><?php
                                    echo $data[1][$i]["hora_termino"];
                                    ?></td><!-- HORA TÉRMINO -->
                                <td>
                                    <input type="text" class="permiso-td_input" value="<?php
                                    echo $data[1][$i]["motivo"];
                                    ?>">
                                </td><!-- MOTIVO -->
                                <td><?php
                                    if ($data[1][$i]["validacion"] == 0) {
                                        echo '<i class="fa-solid fa-circle-half-stroke"></i>';
                                    } elseif ($data[1][$i]["validacion"] == 1) {
                                        echo '<i class="fa-solid fa-circle-check"></i>';
                                    } else {
                                        echo '<i class="fa-solid fa-circle-xmark"></i>';
                                    }
                                    ?></td><!-- VALIDACIÓN DE LA ADMINISTRACIÓN -->
                                <td>
                                    <input type="text" class="permiso-td_input" value="<?php
                                    echo $data[1][$i]["observaciones"];
                                    ?>">
                                </td><!-- OBSERVACIONES -->
                                <td>
                                    <?php
                                    if ($data[1][$i]["validacion"] == 0) {
                                    ?><div class="container-td" onclick="editarPermiso(<?php echo $data[1][$i]["id"]; ?>)">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        <span>Editar</span>
                                    </div>
                                    <?php
                                    } else {
                                    ?><div class="container-td">
                                        <i class="fa-solid fa-minus"></i>
                                    </div>
                                    <?php
                                    }
                                    ?><!-- EDICIÓN DEL PERMISO -->
                                </td>
                                <td>
                                    <?php
                                    if ($data[1][$i]["validacion"] == 0) {
                                    ?><div class="container-td" onclick="confirmarEliminacionDePermiso(<?php echo $data[1][$i]["id"]; ?>)">
                                        <i class="fa-solid fa-trash-can"></i>
                                        <span>Eliminar</span>
                                    </div>
                                    <?php
                                    } else {
                                    ?><div class="container-td">
                                        <i class="fa-solid fa-minus"></i>
                                    </div>
                                    <?php
                                    }
                                    ?><!-- ELIMINACIÓN DEL PERMISO -->
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
    </div>
</div>

<section id="modal-confirm_permiso" class="modal">
    <div class="card">
        <div class="card-header">
            <h1 class="text-warning">Confirme la acción</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div id="modal-confirm_logout-message" class="col-12">¿Estás seguro que deseas eliminar esta solicitud de permiso?</div>
            </div>
            <hr>
            <div class="row">
                <div id="modal-btn" class="col-6 col-xs-12 text-center">
                    <button class="btn btn-warning w-75" onclick="eliminarPermiso()">Confirmar</button>
                </div>
                <div id="modal-btn" class="col-6 col-xs-12 text-center">
                    <button class="btn btn-dark w-75" onclick="ocultarModal(this)">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</section>