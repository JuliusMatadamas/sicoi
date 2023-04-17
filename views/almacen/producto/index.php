<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">
<input type="hidden" id="id_usuario" value="<?php echo $_SESSION["usuario"]["id"]; ?>">
<input type="hidden" id="csrf" value="<?php echo $_SESSION["usuario"]["csrf"]; ?>">
<input type="hidden" id="registros" value="<?php echo intval($data["registros"][0]["total"]); ?>">

<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Almacén - Productos</span>
            </h2>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-4 col-xs-12 col-sm-12 col-md-8 tooltip">
                    <label for="id_categoria">Categoría:</label>
                    <select class="form-control" id="id_categoria">
                        <option value="0">Seleccione</option>
                        <?php
                        foreach ($data["categorias"] as $categoria) {
                        ?>
                        <option value="<?php echo $categoria["id"]; ?>"><?php echo $categoria["categoria"]; ?></option>
                        <?php
                        }
                        ?><!-- items -->
                    </select>
                    <div id="id_categoria-feedback" class="">&nbsp;</div>
                    <div id="id_categoria-tooltip" class="tooltip-box">&nbsp;</div>
                </div>
            </div>

            <!-- televes.com -->
            <div class="row">
                <div class="col-4 col-xs-12 col-sm-12 col-md-8 tooltip">
                    <label for="producto">Producto:</label>
                    <input type="text" class="form-control" id="producto" placeholder="Entre 3 Y 45 caracteres">
                    <div id="producto-feedback" class="">&nbsp;</div>
                    <div id="producto-tooltip" class="tooltip-box">&nbsp;</div>
                </div>

                <div class="col-8 col-xs-12 col-sm-12 col-md-12 tooltip">
                    <label for="descripcion">Descripción:</label>
                    <input type="text" class="form-control" id="descripcion" placeholder="Entre 5 Y 255 caracteres">
                    <div id="descripcion-feedback" class="">&nbsp;</div>
                    <div id="descripcion-tooltip" class="tooltip-box">&nbsp;</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="container-buttons">
                        <div class="row">
                            <div class="col-6 col-xs-12">
                                <input id="btn-cancelar" type="button" class="btn btn-warning w-100" value="Cancelar">
                            </div>

                            <div class="col-6 col-xs-12">
                                <input id="btn-submit" type="button" class="btn btn-dark w-100" value="Guardar">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="jwt-alert tooltip">
                <span id="tooltip-jwt" class="tooltip-box"></span>
            </div>
            <div>
                <table id="tabla-productos" class="table table-light table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Categoria</th>
                        <th>Producto</th>
                        <th>Descripción</th>
                        <th colspan="2">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($data["productos"] as $producto) {
                    ?>
                    <tr>
                        <td><input class="input-table" value="<?php echo $producto["id"]; ?>"></td>
                        <td><input class="input-table" value="<?php echo $producto["categoria"]; ?>"></td>
                        <td><input class="input-table" value="<?php echo $producto["nombre"]; ?>"></td>
                        <td><input class="input-table" value="<?php echo $producto["descripcion"]; ?>"></td>
                        <td>
                            <div class="td-actions"
                                 onclick="setDelete(<?php echo intval($producto["id"]) . ", " . intval($producto["id_categoria"]) . ",'" . $producto["nombre"] . "', '" . $producto["descripcion"] . "'"; ?>)">
                                <i class="fa-regular fa-trash-can"></i>
                                <span>Eliminar</span>
                            </div>
                        </td>
                        <td>
                            <div class="td-actions"
                                 onclick="setEdit(<?php echo intval($producto["id"]) . ", " . intval($producto["id_categoria"]) . ",'" . $producto["nombre"] . "', '" . $producto["descripcion"] . "'"; ?>)">
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

<section id="modal-confirm_producto" class="modal">
    <div class="card">
        <div class="card-header">
            <h1 class="text-warning">Eliminar producto</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div id="modal-confirm_producto-message" class="col-12"></div>
            </div>
            <hr>
            <div class="row">
                <div id="modal-btn" class="col-6 col-xs-12 text-center">
                    <button id="modal-confirm_producto-btn" class="btn btn-warning w-75">Confirmar</button>
                </div>
                <div id="modal-btn" class="col-6 col-xs-12 text-center">
                    <button class="btn btn-dark w-75" onclick="ocultarModal(this); cancelDeleteProducto()">Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

