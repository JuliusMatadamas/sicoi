<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">
<input type="hidden" id="csrf" value="<?php echo $_SESSION["usuario"]["csrf"]; ?>">
<input type="hidden" id="id_usuario" value="<?php echo $_SESSION["usuario"]["id"]; ?>">

<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Técnicos - movimientos en mi inventario</span>
            </h2>
        </div>

        <div class="card-body">
            <?php
            if (isset($_POST["id_venta"])) {
            ?><!-- Salida a cliente -->
            <input type="hidden" id="decodificadores" value='<?php echo json_encode($data["decodificadores"]); ?>'>
            <input type="hidden" id="id_cliente" value='<?php echo $_POST["id_cliente"]; ?>'>
            <div class="row">
                <div class="col-12">
                    <h3>Ingresa las cantidades empleadas de los siguientes items en la visita:</h3>
                </div>
            </div>

            <?php
            foreach ($data["inventario"] as $item) {
            ?><!-- Item -->
            <div class="row">
                <div class="col-3 col-md-2 col-lg-2 col-xl-2 col-label">
                    <input type="text" class="input-label" disabled value="Categoría:">
                </div>

                <div class="col-9 col-md-2 col-lg-2 col-xl-2 col-input">
                    <input type="text" class="input-inventario" disabled value="<?php echo $item["categoria"]; ?>">
                </div>

                <div class="col-3 col-md-2 col-lg-2 col-xl-2 col-label">
                    <input type="text" class="input-label" disabled value="Producto:">
                </div>

                <div class="col-9 col-md-6 col-lg-6 col-xl-6 col-input">
                    <input type="text" class="input-inventario" disabled value="<?php echo $item["nombre"]; ?>">
                </div>

                <div class="col-3 col-md-2 col-lg-2 col-xl-2 col-label">
                    <input type="text" class="input-label" disabled value="Disponible:">
                </div>

                <div class="col-2 col-md-2 col-lg-2 col-xl-2 col-input">
                    <input type="text" class="input-inventario" disabled value="<?php echo $item["total"]; ?>">
                </div>

                <div class="col-3 col-md-2 col-lg-2 col-xl-2 col-label">
                    <input type="text" class="input-label" disabled value="Entregar:">
                </div>

                <div class="col-4 col-md-6 col-lg-6 col-xl-6 col-input">
                    <input type="hidden" id="valid_<?php echo $item["id_categoria"] . "_" . $item["id"]; ?>"
                           value="0">
                    <input type="text" id="<?php echo $item["id_categoria"] . "|" . $item["id"]; ?>"
                           class="form-control input-inventario input-cantidad"
                           onkeyup="validarSalidaCliente(this, <?php echo $item["id_categoria"] . "," . $item["id"] . "," . $item["total"]; ?>)"
                           value="">
                    <div id="feedback_<?php echo $item["id"]; ?>" class="">&nbsp;</div>
                </div>
            </div>
            <?php
            if (intval($item["id_categoria"]) === 3) {
            ?><!-- Serie de decodificadores -->
            <div id="series-decodificadores">
            </div>
            <?php
            }
            ?><!-- Decodificadores -->

            <hr>
            <?php
            }
            ?><!-- items -->

            <div class="row">
                <div class="col-12 text-end">
                    <input type="button" class="btn btn-dark w-xl-25 w-lg-25 w-md-50 w-sm-100 w-xs-100"
                           onclick="evaluarSalidasAlCliente()" value="Guardar">
                </div>
            </div>
            <?php
            } else {
            ?><!-- Salida al almacén local -->
            <div class="row">
                <div class="col-12">
                    <h3>Ingresa las cantidades de los siguientes items a devolver al almacén local</h3>
                </div>
            </div>
            <?php
            }
            ?><!-- Salidas -->
        </div>
    </div>
</div>
