<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">
<input type="hidden" id="csrf" value="<?php echo $_SESSION["usuario"]["csrf"]; ?>">
<input type="hidden" id="id_usuario" value="<?php echo $_SESSION["usuario"]["id"]; ?>">

<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Almacén - Reportes</span>
            </h2>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <h3>Mostrar reporte de movimientos</h3>
                </div>
            </div>

            <div class="row">
                <div class="col-6 col-xs-12 col-sm-12">
                    <div class="card-select">
                        <div class="row">
                            <div class="col-12">
                                <label for="id_organizacion">Por organización:</label>
                                <select class="form-select" name="id_organizacion" id="id_organizacion">
                                    <option value="0">Todas las organizaciones</option>
                                    <?php
                                    foreach ($data["organizaciones"] as $organizacion) {
                                    ?><!-- item -->
                                    <option value="<?php echo $organizacion["id"]; ?>"><?php echo $organizacion["organizacion"]; ?></option>
                                    <?php
                                    }
                                    ?><!-- options -->
                                </select>
                            </div>

                            <div class="col-12 text-end">
                                <button type="button" onclick="reportePorOrganizacion()"
                                        class="btn btn-dark w-xl-25 w-lg-25 w-md-50 w-sm-100 w-xs-100">
                                    Mostrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-xs-12 col-sm-12">
                    <div class="card-select">
                        <div class="row">
                            <div class="col-12">
                                <label for="numero_de_serie">Por numero de serie:</label>
                                <input type="text" class="form-control" name="numero_de_serie" id="numero_de_serie"
                                       value="">
                                <div id="numero_de_serie-feedback" class=""></div>
                            </div>

                            <div class="col-12 text-end">
                                <button type="button" onclick="reportePorSerie()"
                                        class="btn btn-dark w-xl-25 w-lg-25 w-md-50 w-sm-100 w-xs-100">
                                    Mostrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <br>

            <div id="table-container">
            </div>

            <div id="table-to_export-container">
                <table id="tabla-to-export">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Org. Origen</th>
                        <th>Estado Origen</th>
                        <th>Fecha Origen</th>
                        <th>Org. Destino</th>
                        <th>Estado Destino</th>
                        <th>Fecha Destino</th>
                        <th>Categoría</th>
                        <th>Producto</th>
                        <th>Serie</th>
                        <th>Cantidad</th>
                    </tr>
                    </thead>
                    <tbody id="tBodyToExport">
                    </tbody>
                </table>
            </div>

            <div id="error-container">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <p id="error-message"></p>
            </div>
        </div>
    </div>
</div>
