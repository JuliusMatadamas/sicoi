<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">
<input type="hidden" id="id_usuario" value="<?php echo $_SESSION["usuario"]["id"]; ?>">
<input type="hidden" id="csrf" value="<?php echo $_SESSION["usuario"]["csrf"]; ?>">
<input type="hidden" id="registros" value="<?php echo $data["registros"]; ?>">

<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Almacén - Organizaciones</span>
            </h2>
        </div>

        <div class="card-body">

            <div class="row">
                <div class="col-4 col-xs-12 col-sm-12 col-md-8 tooltip">
                    <label for="organizacion">Organización:</label>
                    <input type="text" class="form-control" id="organizacion" placeholder="Entre 3 Y 10 caracteres">
                    <div id="organizacion-feedback" class="">&nbsp;</div>
                    <div id="organizacion-tooltip" class="tooltip-box">&nbsp;</div>
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
                <table id="tabla-organizaciones" class="table table-light table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Organización</th>
                        <th>Descripción</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $cont = 1;
                    foreach ($data["organizaciones"] as $organizacion) {
                    ?>
                    <tr>
                        <td><input class="input-table" value="<?php echo $cont; ?>"></td>
                        <td><input class="input-table" value="<?php echo $organizacion["organizacion"]; ?>"></td>
                        <td><input class="input-table" value="<?php echo $organizacion["descripcion"]; ?>"></td>
                        <td>
                            <div class="td-actions"
                                 onclick="setDelete('<?php echo $organizacion["id"] . "', '" . $organizacion["organizacion"] . "', '" . $organizacion["descripcion"] . "'"; ?>)">
                                <i class="fa-regular fa-trash-can"></i>
                                <span>Eliminar</span>
                            </div>
                        </td>
                        <td>
                            <div class="td-actions"
                                 onclick="setEdit('<?php echo $organizacion["id"] . "', '" . $organizacion["organizacion"] . "', '" . $organizacion["descripcion"] . "'"; ?>)">
                                <i class="fa-regular fa-pen-to-square"></i>
                                <span>Editar</span>
                            </div>
                        </td>
                    </tr>
                    <?php
                    $cont++;
                    }
                    ?><!-- items -->
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<section id="modal-confirm_organizacion" class="modal">
    <div class="card">
        <div class="card-header">
            <h1 class="text-warning">Eliminar organización</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div id="modal-confirm_organizacion-message" class="col-12"></div>
            </div>
            <hr>
            <div class="row">
                <div id="modal-btn" class="col-6 col-xs-12 text-center">
                    <button id="modal-confirm_organizacion-btn" class="btn btn-warning w-75">Confirmar</button>
                </div>
                <div id="modal-btn" class="col-6 col-xs-12 text-center">
                    <button class="btn btn-dark w-75" onclick="ocultarModal(this); cancelDeleteOrganizacion()">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
