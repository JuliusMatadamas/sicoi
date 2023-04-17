<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">
<input type="hidden" id="registros" value="<?php echo $data[0]; ?>">

<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Clientes</span>
            </h2>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 text-start">
                    <a href="<?php echo ENTORNO; ?>/clientes/nuevo" class="a-cliente">
                        <h3>
                            <i class="fa-solid fa-user-plus"></i>
                            <span> Agregar un nuevo cliente</span>
                        </h3>
                    </a>
                </div>
            </div>

            <div class="jwt-alert tooltip">
                <span id="tooltip-jwt" class="tooltip-box"></span>
            </div>

            <div>
                <table id="tabla-clientes" class="table table-light table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido Paterno</th>
                        <th>Apellido Materno</th>
                        <th>Fecha de nacimiento</th>
                        <th>Genero</th>
                        <th>RFC</th>
                        <th>Teléfono Casa</th>
                        <th>Teléfono Celular</th>
                        <th>Correo electrónico</th>
                        <th>Código postal</th>
                        <th>Colonia</th>
                        <th>Calle</th>
                        <th>N° exterior</th>
                        <th>N° interior</th>
                        <th>Observaciones</th>
                        <th colspan="2">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($data[1] as $cliente) {
                        ?><tr>
                        <td>
                            <input type="text" class="input-item" value="<?php echo $cliente["nombre"]; ?>">
                        </td>
                        <td>
                            <input type="text" class="input-item" value="<?php echo $cliente["apellido_paterno"]; ?>">
                        </td>
                        <td>
                            <input type="text" class="input-item" value="<?php echo $cliente["apellido_materno"]; ?>">
                        </td>
                        <td>
                            <input type="text" class="input-item" value="<?php echo $cliente["fecha_nacimiento"]; ?>">
                        </td>
                        <td>
                            <input type="text" class="input-item" value="<?php echo $cliente["genero"]; ?>">
                        </td>
                        <td>
                            <input type="text" class="input-item" value="<?php echo $cliente["rfc"]; ?>">
                        </td>
                        <td>
                            <input type="text" class="input-item" value="<?php echo $cliente["telefono_casa"]; ?>">
                        </td>
                        <td>
                            <input type="text" class="input-item" value="<?php echo $cliente["telefono_celular"]; ?>">
                        </td>
                        <td>
                            <input type="text" class="input-item" value="<?php echo $cliente["email"]; ?>">
                        </td>
                        <td>
                            <input type="text" class="input-item" value="<?php echo $cliente["codigo_postal"]; ?>">
                        </td>
                        <td>
                            <input type="text" class="input-item" value="<?php echo $cliente["colonia"]; ?>">
                        </td>
                        <td>
                            <input type="text" class="input-item" value="<?php echo $cliente["calle"]; ?>">
                        </td>
                        <td>
                            <input type="text" class="input-item" value="<?php echo $cliente["numero_exterior"]; ?>">
                        </td>
                        <td>
                            <input type="text" class="input-item" value="<?php echo $cliente["numero_interior"]; ?>">
                        </td>
                        <td>
                            <input type="text" class="input-item" value="<?php echo $cliente["observaciones"]; ?>">
                        </td>
                        <td>
                            <div class="td-actions" onclick="window.location = '<?php echo ENTORNO."/clientes/editar?id=".$cliente["id"]; ?>'">
                                <i class="fa-regular fa-pen-to-square"></i>
                                <span>Editar</span>
                            </div>
                        </td>
                        <td>
                            <div class="td-actions" onclick="window.location = '<?php echo ENTORNO."/ventas/nueva?id=".$cliente["id"]; ?>'">
                                <i class="fa-solid fa-comments-dollar"></i>
                                <span>Venta</span>
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
