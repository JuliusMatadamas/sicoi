<input type="hidden" id="url" value="<?php echo ENTORNO; ?>">
<input type="hidden" id="registros" value="<?php echo $data[0]; ?>">
<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Administración - Géneros</span>
            </h2>
        </div>

        <div class="card-body">
            <!-- SECCIÓN PARA CREAR O EDITAR UN GENERO EN LA BD -->
            <section id="crearEditarGenero">
                <form id="form-genero" action="<?php echo ENTORNO; ?>/administracion/generos" method="post">
                    <input type="hidden" id="action" value="create">
                    <input type="hidden" id="csrf" value="">
                    <input type="hidden" id="id" value="">
                    <div class="row">
                        <div class="col-4 col-xs-12 col-sm-12 col-md-6 col-lg-6 tooltip">
                            <label id="label-genero" for="genero">Ingrese el género</label>
                            <input type="text" id="genero" name="genero" class="form-control" placeholder="Únicamente letras, 3 mínimo">
                            <div id="genero-feedback">&nbsp;</div>
                            <span id="tooltip-genero" class="tooltip-box"></span>
                        </div>

                        <div class="col-4 col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label>&nbsp;</label>
                            <input type="button" id="btn-submit" class="btn btn-primary w-100" value="Guardar">
                            <div>&nbsp;</div>
                        </div>

                        <div class="col-4 col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <label>&nbsp;</label>
                            <input type="button" id="btn-cancelar" class="btn btn-warning w-100" value="Cancelar">
                            <div>&nbsp;</div>
                        </div>
                    </div>
                </form>
            </section>

            <br>

            <!-- TABLA DE GÉNEROS REGISTRADOS EN LA BD -->
            <section id="tableContainer">
                <table id="tabla-generos" class="table table-light table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Genero</th>
                            <th colspan="2" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 0; $i < count($data[1]); $i++)
                        {
                        ?><tr>
                            <td><?php echo $data[1][$i]["id"]; ?></td>
                            <td><?php echo $data[1][$i]["genero"]; ?></td>
                            <td>
                                <div class="container-edit_genero" onclick="setEdit('<?php echo $data[1][$i]["id"]; ?>','<?php echo $data[1][$i]["genero"]; ?>')">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                    <span>Editar</span>
                                </div>
                            </td>
                            <td>
                                <div class="container-edit_genero" onclick="mostrarConfirmacion('<?php echo $data[1][$i]["id"]; ?>','<?php echo $data[1][$i]["genero"]; ?>')">
                                    <i class="fa-regular fa-trash-can"></i>
                                    <span>Eliminar</span>
                                </div>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </section>
        </div>
    </div>
</div>

<section id="modal-confirm" class="modal">
    <div class="card">
        <div class="card-header">
            <h1 class="text-warning">Confirme la operación</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div id="modal-confirm-message" class="col-12"></div>
            </div>
            <hr>
            <div class="row">
                <div class="col-4 col-xs-12">&nbsp;</div>
                <div class="col-4 col-xs-12">
                    <button id="btn-confirmar" class="btn btn-primary w-100">Continuar</button>
                </div>
                <div class="col-4 col-xs-12">
                    <button class="btn btn-secondary w-100" onclick="ocultarModal(this)">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</section>
