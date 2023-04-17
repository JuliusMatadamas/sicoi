<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Técnicos - Mi Inventario</span>
            </h2>
        </div>

        <section class="card-body">
            <?php
            if (count($data["inventario"]) === 0) {
            ?><!-- Sin inventario -->
            <div class="row">
                <div class="col-12">
                    <h3>No tienes items disponibles en tu inventario</h3>
                </div>
            </div>
            <?php
            } else {
            ?><!-- inventario -->
            <div class="row">
                <div class="col-12">
                    <h3>Items que tienes disponibles en tu inventario</h3>
                </div>
            </div>

            <div>
                <table id="tabla-mi_inventario" class="table table-light table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Categoria</th>
                        <th>Producto</th>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($data["inventario"] as $inventario) {
                    ?>
                    <tr>
                        <td>
                            <input type="text" class="input-table" value="<?php echo $inventario["categoria"]; ?>">
                        </td>
                        <td>
                            <input type="text" class="input-table" value="<?php echo $inventario["nombre"]; ?>">
                        </td>
                        <td>
                            <input type="text" class="input-table" value="<?php echo $inventario["descripcion"]; ?>">
                        </td>
                        <td>
                            <input type="text" class="input-table text-center"
                                   value="<?php echo $inventario["total"]; ?>">
                        </td>
                    </tr>
                    <?php
                    }
                    ?><!-- items -->
                    </tbody>
                </table>
            </div>
            <?php
            }
            ?><!-- inventario -->
        </section>
    </div>
</div>