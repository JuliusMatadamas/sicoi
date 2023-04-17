<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">
<input type="hidden" id="id_usuario" value="<?php echo $_SESSION["usuario"]["id"]; ?>">
<input type="hidden" id="csrf" value="<?php echo $_SESSION["usuario"]["csrf"]; ?>">
<input type="hidden" id="registros" value="<?php echo count($data); ?>">

<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Almacén - Categorías</span>
            </h2>
        </div>

        <div class="card-body">
            <!--
            CAMPO PARA REGISTRAR/ACTUALIZAR UNA CATEGORÍA DE ALMACÉN
            -->
            <div class="row">
                <div class="col-6 tooltip">
                    <label for="categoria">Ingresa el nombre de la categoría:</label>
                    <input type="text" class="form-control" id="categoria" placeholder="Entre 7 y 45 caracteres">
                    <div id="categoria-feedback" class="">&nbsp;</div>
                    <div id="categoria-tooltip" class="tooltip-box"></div>
                </div>

                <div class="col-3">
                    <label for="">&nbsp;</label>
                    <input id="btn-cancelar" type="button" class="btn btn-warning w-100" value="Cancelar">
                </div>

                <div class="col-3">
                    <label for="">&nbsp;</label>
                    <input id="btn-submit" type="button" class="btn btn-dark w-100" value="Guardar">
                </div>
            </div>

            <!--
            TABLA DE CATEGORÍAS REGISTRADAS EN LA BASE DE DATOS
            -->
            <div class="jwt-alert tooltip">
                <span id="tooltip-jwt" class="tooltip-box"></span>
            </div>
            <div>
                <table id="tabla-categorias" class="table table-light table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Categoría</th>
                            <th colspan="2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($data as $d) {
                            ?><tr>
                            <td><?php echo $d["id"]; ?></td>
                            <td><?php echo $d["categoria"]; ?></td>
                            <td>
                                <div class="td-actions" onclick="setDelete(<?php echo intval($d["id"]).",'".$d["categoria"]."'"; ?>)">
                                    <i class="fa-regular fa-trash-can"></i>
                                    <span>Eliminar</span>
                                </div>
                            </td>
                            <td>
                                <div class="td-actions" onclick="setEdit(<?php echo intval($d["id"]).",'".$d["categoria"]."'"; ?>)">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                    <span>Editar</span>
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

<section id="modal-confirm_categoria" class="modal">
    <div class="card">
        <div class="card-header">
            <h1 class="text-warning">Eliminar categoría</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div id="modal-confirm_categoria-message" class="col-12"></div>
            </div>
            <hr>
            <div class="row">
                <div id="modal-btn" class="col-6 col-xs-12 text-center">
                    <button class="btn btn-warning w-75" onclick="deleteCategoria()">Confirmar</button>
                </div>
                <div id="modal-btn" class="col-6 col-xs-12 text-center">
                    <button class="btn btn-dark w-75" onclick="ocultarModal(this); cancelDeleteCategoria()">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</section>
