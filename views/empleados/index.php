<input type="hidden" id="url" value="<?php echo ENTORNO; ?>">
<input type="hidden" id="registros" value="<?php echo $data[0]; ?>">
<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Empleados</span>
            </h2>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 text-start">
                    <a href="<?php echo ENTORNO; ?>/empleados/nuevo" class="a-empleado">
                        <h3>
                            <i class="fa-solid fa-user-plus"></i>
                            <span> Agregar un nuevo empleado</span>
                        </h3>
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-3 col-lg-4 col-md-5 col-sm-6 col-xs-12">
                    <div class="animate__animated view-profile_img"></div>
                </div>
            </div>

            <!-- TABLA DE EMPLEADOS REGISTRADOS EN LA BD -->
            <section id="tableContainer">
                <table id="tabla-empleados" class="table table-light table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Correo electrónico</th>
                            <th>Teléfono de casa</th>
                            <th>Teléfono celular</th>
                            <th>N° Seguro Social</th>
                            <th>RFC</th>
                            <th>Puesto</th>
                            <th>Alta</th>
                            <th>Baja</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        for ($i = 0; $i < count($data[1]); $i++)
                        {
                        ?><tr>
                            <td><?php echo $data[1][$i]["id"]; ?></td>
                            <td>
                                <a class="a-profile" onclick="mostrarProfileImg('<?php echo ENTORNO.$data[1][$i]["profile_img"]; ?>', '<?php echo $data[1][$i]["empleado"]; ?>')">
                                    <?php echo $data[1][$i]["empleado"]; ?>
                                </a>
                            </td>
                            <td>
                                <a class="a-profile" href="mailto:<?php echo $data[1][$i]["email"]; ?>">
                                    <?php echo $data[1][$i]["email"]; ?>
                                </a>
                            </td>
                            <td>
                                <a class="a-profile" href="tel:+52<?php echo $data[1][$i]["telefono_casa"]; ?>">
                                    <?php echo $data[1][$i]["telefono_casa"]; ?>
                                </a>
                            </td>
                            <td>
                                <a class="a-profile" href="tel:+52<?php echo $data[1][$i]["telefono_celular"]; ?>">
                                    <?php echo $data[1][$i]["telefono_celular"]; ?>
                                </a>
                            </td>
                            <td><?php echo $data[1][$i]["seguro_social"]; ?></td>
                            <td><?php echo $data[1][$i]["rfc"]; ?></td>
                            <td><?php echo $data[1][$i]["puesto"]; ?></td>
                            <td><?php echo $data[1][$i]["fecha_inicio"]; ?></td>
                            <td><?php echo $data[1][$i]["fecha_baja"]; ?></td>
                            <td>
                                <form name="form-edit_employee_<?php echo $data[1][$i]["id"]; ?>" id="form-edit_employee_<?php echo $data[1][$i]["id"]; ?>" action="<?php echo ENTORNO; ?>/empleados/editar" method="post">
                                    <div class="container-edit_empleado" onclick="document.forms['form-edit_employee_<?php echo $data[1][$i]["id"]; ?>'].submit()">
                                        <input type="hidden" name="id" value="<?php echo $data[1][$i]["id"]; ?>">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                        <span>Editar</span>
                                    </div>
                                </form>
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