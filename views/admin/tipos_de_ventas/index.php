<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">
<input type="hidden" id="registros" value="<?php echo $data[0]; ?>">
<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Administración - Tipos de ventas</span>
            </h2>
        </div>

        <div class="card-body">
            <section>
                <form id="form-tipos_de_ventas" action="<?php echo ENTORNO; ?>/administracion/tipos_de_ventas" method="post">
                    <input type="hidden" id="csrf" value="<?php echo $_SESSION["usuario"]["csrf"]; ?>">
                    <input type="hidden" id="id_usuario" value="<?php echo $_SESSION["usuario"]["id"]; ?>">
                    <input type="hidden" id="id_tipo_de_venta" value="">
                    <input type="hidden" id="action" value="create">

                    <div class="row">
                        <div class="col-12">
                            <label id="label-tipo_de_venta" for="tipo_de_venta">Ingrese el tipo de venta:</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 col-xs-12 col-sm-12 tooltip">
                            <input type="text" id="tipo_de_venta" class="form-control" value="" placeholder="Entre 10 y 100 caracteres, solo letras y/o espacios">
                            <div id="tipo_de_venta-feedback" class="">&nbsp;</div>
                            <span id="tipo_de_venta-tooltip" class="tooltip-box"></span>
                        </div>

                        <div class="col-3 col-xs-12 col-sm-6 container-td">
                            <input type="button" id="btn-submit" class="btn btn-secondary w-100" value="Guardar">
                            <div>&nbsp;</div>
                        </div>

                        <div class="col-3 col-xs-12 col-sm-6 container-td">
                            <input type="button" id="btn-cancelar" class="btn btn-warning w-100" value="Cancelar">
                            <div>&nbsp;</div>
                        </div>
                    </div>
                </form>
            </section>

            <br>

            <div class="jwt-alert tooltip">
                <span id="tooltip-jwt" class="tooltip-box"></span>
            </div>

            <section>
                <?php
                if (is_string($data[1])) {
                    $result = $data[1];
                    echo "<p>$result</p>";
                } else {
                    ?><table id="tabla-tipos_de_ventas" class="table table-light table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Tipo</th>
                        <th colspan="2">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($data[1] as $item) {
                        ?><tr>
                        <td><?php echo $item["id"]; ?></td>
                        <td><?php echo $item["tipo"]; ?></td>
                        <td>
                            <div class="container-td" onclick="setEditTipo('<?php echo implode('|', $item); ?>')">
                                <i class="fa-regular fa-pen-to-square"></i>
                                <span>Editar</span>
                            </div>
                        </td>
                        <td>
                            <div class="container-td" onclick="confirmDeleteTipo('<?php echo implode('|', $item); ?>')">
                                <i class="fa-regular fa-trash-can"></i>
                                <span>Eliminar</span>
                            </div>
                        </td>
                    </tr>
                    <?php
                    }
                    ?><!-- items -->
                    </tbody>
                </table>
                <?php
                }
                ?><!-- -->
            </section>
        </div>
    </div>
</div>

<section id="modal-confirm_delete_tipo" class="modal">
    <div class="card">
        <div class="card-header">
            <h1 class="text-warning">Eliminación de tipo de venta</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div id="modal-confirm_logout-message" class="col-12">¿Estás seguro que deseas eliminar el tipo de venta "<span id="tipo_de_venta_detalle"></span>"?</div>
            </div>
            <hr>
            <div class="row">
                <div id="modal-btn" class="col-6 col-xs-12 text-center">
                    <button class="btn btn-warning w-75" onclick="deleteTipo()">Confirmar</button>
                </div>
                <div id="modal-btn" class="col-6 col-xs-12 text-center">
                    <button class="btn btn-dark w-75" onclick="cancelDelete();ocultarModal(this)">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</section>
