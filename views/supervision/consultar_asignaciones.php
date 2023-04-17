<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">
<input type="hidden" id="id_usuario" value="<?php echo $_SESSION["usuario"]["id"]; ?>">
<input type="hidden" id="registros" value="<?php echo $data["total_visitas"][0]["total"]; ?>">

<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Supervisión - consulta de visitas asignadas</span>
            </h2>
        </div>

        <div class="card-body">
            <div class="jwt-alert tooltip">
                <span id="tooltip-jwt" class="tooltip-box"></span>
            </div>
            <div>
                <table id="tabla-visitas" class="table table-light table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Cliente</th>
                        <th>Tipo de venta</th>
                        <th>Técnico asignado</th>
                        <th>Fecha programada</th>
                        <th>Estado de visita</th>
                        <th>Observaciones</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($data["visitas_programadas"] as $visita) {
                    ?><!-- tr -->
                    <tr>
                        <td><input type="text" class="input-table" value="<?php echo $visita["id"]; ?>"></td>
                        <td><input type="text" class="input-table" value="<?php echo $visita["cliente"]; ?>"></td>
                        <td><input type="text" class="input-table" value="<?php echo $visita["tipo"]; ?>"></td>
                        <td><input type="text" class="input-table" value="<?php echo $visita["tecnico_asignado"]; ?>">
                        </td>
                        <td><input type="text" class="input-table" value="<?php echo $visita["fecha_programada"]; ?>">
                        </td>
                        <td><input type="text" class="input-table" value="<?php echo $visita["estado_de_visita"]; ?>">
                        </td>
                        <td><input type="text" class="input-table" value="<?php echo $visita["observaciones"]; ?>">
                        </td>
                        <td>
                            <form action="<?php echo ENTORNO; ?>/supervision/modificar_asignacion" method="post"
                                  class="td-actions" onclick="this.submit()">
                                <input type="hidden" name="id" value="<?php echo $visita["id"]; ?>">
                                <i class="fa-regular fa-pen-to-square"></i>
                                <span>Modificar asignación</span>
                            </form>
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
