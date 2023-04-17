<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">
<input type="hidden" id="registros" value="<?php echo $data[0]; ?>">
<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Administración - Validación de vacaciones</span>
            </h2>
        </div>

        <div class="card-body">
            <?php
            if ($data[0] === 0){
                if (is_string($data[1])) {
                    $result = $data[1];
                    echo "<p>$result</p>";
                }
            } else {
                ?><form id="form-validate_vacaciones" action="<?php echo ENTORNO; ?>/administracion/vacaciones" method="post">
                <input type="hidden" id="csrf" value="<?php echo $_SESSION["usuario"]["csrf"]; ?>">
                <input type="hidden" id="id_usuario" value="<?php echo $_SESSION["usuario"]["id"]; ?>">
                <section>
                    <table class="table table-light table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Empleado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($data[1] as $item) {
                        ?><tr>
                            <td><?php echo $item["id"]; ?></td>
                            <td><?php echo $item["empleado"]; ?></td>
                            <td><?php echo $item["fecha"]; ?></td>
                            <td>
                                <select class="select-vacacion" id="<?php echo $item["id"]; ?>">
                                    <option value="0">Seleccionar</option>
                                    <option value="1">Aceptar</option>
                                    <option value="2">Rechazar</option>
                                </select>
                            </td>
                        </tr>
                        <?php
                        }
                        ?><!-- items -->
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="col-12">
                            <div class="alert-validation">&nbsp;</div>
                        </div>
                    </div>
                </section>

                <div class="row">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-dark w-xs-100 w-sm-50 w-md-50 w-lg-25 w-xl-25">Guardar</button>
                    </div>
                </div>
            </form>
            <?php
            }
            ?><!-- -->
        </div>
    </div>
</div>
