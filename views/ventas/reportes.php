<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">
<input type="hidden" id="csrf" value="<?php echo $_SESSION["usuario"]["csrf"]; ?>">
<input type="hidden" id="id_usuario" value="<?php echo $_SESSION["usuario"]["id"]; ?>">
<input type="hidden" id="fecha_inicio_default" value="<?php echo $data["fecha_inicio"]; ?>">
<input type="hidden" id="fecha_termino_default" value="<?php echo $data["fecha_termino"]; ?>">

<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Ventas - reportes</span>
            </h2>
        </div>

        <section class="card-body">
            <!--
            ENLACES
            -->
            <div class="row">
                <div class="col-4 col-xs-12 link-venta">
                    <a href="<?php echo ENTORNO; ?>/ventas/consultar" class="a-venta">
                        <h3>
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <span> Consultar</span>
                        </h3>
                    </a>
                </div>

                <div class="col-4 col-xs-12 link-venta">
                    <a href="<?php echo ENTORNO; ?>/ventas/nueva" class="a-venta">
                        <h3>
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                            <span> Nueva</span>
                        </h3>
                    </a>
                </div>

                <div class="col-4 col-xs-12 link-venta">
                    <a href="<?php echo ENTORNO; ?>/ventas/dashboard" class="a-cliente">
                        <h3>
                            <i class="fa-solid fa-chart-line"></i>
                            <span> Dashboard</span>
                        </h3>
                    </a>
                </div>
            </div>

            <!-- FILTROS -->
            <div class="row">
                <div class="col-12">
                    <h4>Ver las ventas realizadas de</h4>
                </div>
            </div>

            <div class="row">
                <div class="col-3 col-lg-4 col-md-12 col-sm-12 col-xs-12">
                    <label for="vendedores">Vendedor(es):</label>
                    <div id="vendedores" class="container-select-multiple">
                        <div class="select-btn" onclick="this.classList.toggle('open')">
                            <span class="btn-text">Seleccione</span>
                            <span class="arrow-down">
                                <i class="fa-solid fa-chevron-down"></i>
                            </span>
                        </div>

                        <ul class="list-items">
                            <li id="0" class="item" onclick="addToList(this)">
                                <span class="checkbox">
                                    <i class="fa-solid fa-check check-icon"></i>
                                </span>
                                <span class="item-text">Todos</span>
                            </li>
                            <?php
                            $cont = 1;
                            foreach ($data["vendedores"] as $vendedor) {
                                ?><li id="<?php echo $cont; ?>" class="item" onclick="addToList(this)">
                                <span id="<?php echo $vendedor["id_vendedor"]; ?>" class="checkbox">
                                    <i class="fa-solid fa-check check-icon"></i>
                                </span>
                                <span class="item-text"><?php echo $vendedor["vendedor"]; ?></span>
                            </li>
                            <?php
                            $cont++;
                            }
                            ?><!-- items -->
                        </ul>
                    </div>
                    <small>Si no se selecciona por lo menos un vendedor, se mostrarán las ventas de todos los vendedores.</small>
                </div>
                
                <div class="col-6 col-lg-8 col-md-12 col-sm-12 col-xs-12">
                    <label for="tipos_de_ventas">Tipo de venta</label>
                    <div id="tipos_de_ventas" class="container-select-multiple">
                        <div class="select-btn" onclick="this.classList.toggle('open')">
                            <span class="btn-text">Seleccione</span>
                            <span class="arrow-down">
                                <i class="fa-solid fa-chevron-down"></i>
                            </span>
                        </div>

                        <ul class="list-items">
                            <li id="0" class="item" onclick="addToList(this)">
                                <span class="checkbox">
                                    <i class="fa-solid fa-check check-icon"></i>
                                </span>
                                <span class="item-text">Todos</span>
                            </li>
                            <?php
                            $cont = 1;
                            foreach ($data["tipos_de_ventas"] as $tipoDeVenta) {
                            ?><li id="<?php echo $cont; ?>" class="item" onclick="addToList(this)">
                                <span id="<?php echo $tipoDeVenta["id_tipo_de_venta"]; ?>" class="checkbox">
                                    <i class="fa-solid fa-check check-icon"></i>
                                </span>
                                <span class="item-text"><?php echo $tipoDeVenta["tipo_de_venta"]; ?></span>
                            </li>
                            <?php
                            $cont++;
                            }
                            ?><!-- items -->
                        </ul>
                    </div>
                    <small>Si no se selecciona por lo menos un tipo de venta, se mostrarán todas las ventas de todos los tipos.</small>
                </div>
            </div>

            <div class="row">
                <div class="col-2 col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <label for="fecha_inicio">Entre la fecha:</label>
                    <input type="date" id="fecha_inicio" class="form-control" min="<?php echo $data["fecha_inicio"]; ?>" max="<?php echo $data["fecha_termino"]; ?>" value="<?php echo $data["fecha_inicio"]; ?>">
                </div>

                <div class="col-2 col-lg-3 col-md-4 col-sm-6 col-xs-12">
                    <label for="fecha_termino">Y la fecha:</label>
                    <input type="date" id="fecha_termino" class="form-control" min="<?php echo $data["fecha_inicio"]; ?>" max="<?php echo $data["fecha_termino"]; ?>" value="<?php echo $data["fecha_termino"]; ?>">
                </div>
            </div>

            <small>Solo se podrán seleccionar fechas entre la fecha actual y la fecha <?php echo $data["fecha_inicio"]; ?> que es donde se comenzó a registrar las ventas en este sistema.</small>

            <div class="row">
                <div class="col-12 text-end">
                    <input id="btn-mostrar" type="button" class="btn btn-dark w-xl-25 w-lg-25 w-md-50 w-sm-100 w-xs-100" value="Mostrar">
                </div>
            </div>

            <br>

            <div id="table-container">
                <hr>
                <br>
                <div id="export-button" class="text-end">
                    <button class="btn btn-success" onclick="exportTableToExcel()">
                        <i class="fa-regular fa-file-excel"></i>
                        <span>Exportar a excel</span>
                    </button>
                </div>
                <table id="tabla-reportes" class="table table-light table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Vendedor</th>
                        <th>Cliente</th>
                        <th>Fecha venta</th>
                        <th>Tipo</th>
                        <th>Fecha programada</th>
                        <th>Fecha visita</th>
                        <th>Hora visita</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody id="tBodyReport">
                    </tbody>
                </table>
            </div>

            <div id="table-to_export-container">
                <table id="tabla-to-export">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Vendedor</th>
                        <th>Cliente</th>
                        <th>Fecha venta</th>
                        <th>Tipo</th>
                        <th>Fecha programada</th>
                        <th>Fecha visita</th>
                        <th>Hora visita</th>
                        <th>Status</th>
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
        </section>
    </div>
</div>
