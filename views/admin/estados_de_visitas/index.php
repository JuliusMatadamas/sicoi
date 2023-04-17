<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">
<input type="hidden" id="registros" value="<?php echo $data[0]; ?>">
<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Administración - Estados de visitas</span>
            </h2>
        </div>

        <div class="card-body">
            <!--
            SECCIÓN PARA CREAR O EDITAR UN ESTADO DE VISITA EN LA BD
            -->
            <section id="crearEditarEstadoDeVisita">
                <form id="form-estados_de_visitas" action="<?php echo ENTORNO; ?>/administracion/estados_de_visitas" method="post">
                    <input type="hidden" id="action" value="create">
                    <input type="hidden" id="csrf" value="<?php echo $_SESSION["usuario"]["csrf"]; ?>">
                    <input type="hidden" id="id_usuario" value="<?php echo $_SESSION["usuario"]["id"]; ?>">
                    <input type="hidden" id="id" value="">

                    <div class="row">
                        <div class="col-4 col-xs-12 col-sm-12 col-md-6 col-lg-6 tooltip">
                            <label id="estado_de_visita-label" for="estado_de_visita">Ingrese el estado de la visita</label>
                            <input type="text" id="estado_de_visita" name="estado_de_visita" class="form-control" placeholder="Únicamente letras, 7 mínimo">
                            <div id="estado_de_visita-feedback">&nbsp;</div>
                            <span id="estado_de_visita-tooltip" class="tooltip-box"></span>
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

            <!--
            TABLA DE ESTADOS DE VISITAS REGISTRADOS EN LA BD
            -->
            <div class="jwt-alert tooltip">
                <span id="tooltip-jwt" class="tooltip-box"></span>
            </div>

            <section id="tableContainer">
                <table id="tabla-estados_de_visitas" class="table table-light table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Estado de visita</th>
                        <th colspan="2" class="text-center">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    for ($i = 0; $i < count($data[1]); $i++) {
                    ?><!-- item -->
                    <tr>
                        <td><?php
                            echo $data[1][$i]["id"];
                            ?></td><!-- ID -->
                        <td><?php
                            echo $data[1][$i]["estado_de_visita"];
                            ?></td><!-- ESTADO DE VISITA -->
                        <td>
                            <div class="container-td" onclick="setEdit(<?php echo $data[1][$i]["id"].",'".$data[1][$i]["estado_de_visita"]."'"; ?>)">
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span>Editar</span>
                            </div>
                        </td>
                        <td>
                            <div class="container-td" onclick="confirmarEliminacionDeEstadoDeVisita(<?php echo $data[1][$i]["id"]; ?>)">
                                <i class="fa-solid fa-trash-can"></i>
                                <span>Eliminar</span>
                            </div>
                        </td>
                    </tr>
                    <?php
                    }
                    ?><!-- ./ items -->
                    </tbody>
                </table>
            </section>
        </div>
    </div>
</div>

<section id="modal-confirm_estado_de_visita" class="modal">
    <div class="card">
        <div class="card-header">
            <h1 class="text-warning">Eliminación de estado de visita</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div id="modal-confirm_logout-message" class="col-12">¿Estás seguro que deseas eliminar este estado de visita?</div>
            </div>
            <hr>
            <div class="row">
                <div id="modal-btn" class="col-6 col-xs-12 text-center">
                    <button class="btn btn-warning w-75" onclick="deleteEstadoDeVisita()">Confirmar</button>
                </div>
                <div id="modal-btn" class="col-6 col-xs-12 text-center">
                    <button class="btn btn-dark w-75" onclick="ocultarModal(this)">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</section>
