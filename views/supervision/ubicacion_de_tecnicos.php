<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">
<input type="hidden" id="csrf" value="<?php echo $_SESSION["usuario"]["csrf"]; ?>">
<input type="hidden" id="id_usuario" value="<?php echo $_SESSION["usuario"]["id"]; ?>">

<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Supervisión - Ubicación de Técnicos</span>
            </h2>
        </div>

        <section class="card-body">
            <div class="row">
                <div class="col-4 col-xs-12 col-sm-12">
                    <label for="id_tecnico">Seleccione el técnico:</label>
                    <select name="id_tecnico" id="id_tecnico" class="form-select">
                        <option value="0">Todos</option>
                        <?php
                        foreach ($data["tecnicos"] as $tecnico) {
                        ?><!-- item -->
                        <option value="<?php echo $tecnico["id_tecnico"]; ?>"><?php echo $tecnico["tecnico"]; ?></option>
                        <?php
                        }
                        ?><!-- options -->
                    </select>
                </div>

                <div class="col-4 col-xs-12 col-sm-12">
                    <label for="fecha">Fecha a consultar:</label>
                    <input type="date" class="form-control" name="fecha" id="fecha"
                           min="<?php echo $data["fecha_min"]; ?>" max="<?php echo $data["fecha_max"]; ?>"
                           value="<?php echo $data["fecha_max"]; ?>">
                </div>

                <div class="col-4 col-xs-12 col-sm-12">
                    <label for="">&nbsp;</label>
                    <button id="btn-obtenerUbicaciones" type="button" class="btn btn-dark w-100">Ver ubicaciones
                    </button>
                </div>
            </div>

            <br>

            <div id="map"></div>

            <br>

            <div>
                <table id="tabla-ubicaciones" class="table table-light table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Tipo de venta</th>
                        <th>Fecha programada</th>
                        <th>Fecha visita</th>
                        <th>Estado de visita</th>
                        <th>Hora de visita</th>
                        <th>Técnico</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><input class="input-table" value=""></td>
                        <td><input class="input-table" value=""></td>
                        <td><input class="input-table" value=""></td>
                        <td><input class="input-table" value=""></td>
                        <td><input class="input-table" value=""></td>
                        <td><input class="input-table" value=""></td>
                        <td><input class="input-table" value=""></td>
                        <td><input class="input-table" value=""></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>