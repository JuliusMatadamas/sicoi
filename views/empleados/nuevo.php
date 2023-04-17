<input type="hidden" id="url" value="<?php echo ENTORNO; ?>">
<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Empleados - nuevo</span>
            </h2>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 text-start">
                    <a href="<?php echo ENTORNO; ?>/empleados" class="a-empleado">
                        <h3>
                            <i class="fa-solid fa-users-viewfinder"></i>
                            <span> Clic para consultar empleados</span>
                        </h3>
                    </a>
                </div>
            </div>

            <!-- FORMULARIO PARA REGISTRAR UN NUEVO EMPLEADO EN LA BD -->
            <form id="formNuevoEmpleado" action="<?php echo ENTORNO; ?>/empleados/nuevo" method="post">
                <input type="hidden" id="action" value="create">
                <input type="hidden" id="csrf" value="<?php echo $_SESSION["usuario"]["csrf"]; ?>">
                <input type="hidden" id="id_usuario" value="<?php echo $_SESSION["usuario"]["id"]; ?>">

                <div class="row">
                    <div class="col-12">
                        <h4>Llene el siguiente formulario para registrar al nuevo empleado.</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-4 col-md-6 col-sm-12 col-xs-12 tooltip">
                        <label id="label-nombre" for="nombre">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Únicamente letras, 2 mínimo">
                        <div id="nombre-feedback">&nbsp;</div>
                        <span id="tooltip-nombre" class="tooltip-box"></span>
                    </div>

                    <div class="col-4 col-md-6 col-sm-12 col-xs-12 tooltip">
                        <label id="label-apellido_paterno" for="apellido_paterno">Apellido paterno</label>
                        <input type="text" id="apellido_paterno" name="apellido_paterno" class="form-control" placeholder="Únicamente letras, 2 mínimo">
                        <div id="apellido_paterno-feedback">&nbsp;</div>
                        <span id="tooltip-apellido_paterno" class="tooltip-box"></span>
                    </div>

                    <div class="col-4 col-md-6 col-sm-12 col-xs-12 tooltip">
                        <label id="label-apellido_materno" for="apellido_materno">Apellido materno</label>
                        <input type="text" id="apellido_materno" name="apellido_materno" class="form-control" placeholder="Únicamente letras">
                        <div id="apellido_materno-feedback">&nbsp;</div>
                        <span id="tooltip-apellido_materno" class="tooltip-box"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-3 col-md-4 col-sm-6 col-xs-12 tooltip">
                        <label id="label-fecha_nacimiento" for="fecha_nacimiento">Fecha de nacimiento</label>
                        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" value="<?php echo $data['current_date']; ?>">
                        <div id="fecha_nacimiento-feedback">&nbsp;</div>
                        <span id="tooltip-fecha_nacimiento" class="tooltip-box"></span>
                    </div>

                    <div class="col-2 col-md-3 col-sm-6 col-xs-12 tooltip">
                        <label id="label-id_genero" for="id_genero">Género</label>
                        <select name="id_genero" id="id_genero" class="form-control">
                            <option value="0">Seleccione</option>
                            <?php
                            for ($i = 0; $i < count($data['generos']); $i++)
                            {
                                echo '<option value="'.$data['generos'][$i]["id"].'">'.$data['generos'][$i]["genero"].'</option>'.PHP_EOL;
                            }
                            ?><!-- /. options -->
                        </select>
                        <div id="id_genero-feedback">&nbsp;</div>
                        <span id="tooltip-id_genero" class="tooltip-box"></span>
                    </div>

                    <div class="col-3 col-sm-12 col-xs-12 tooltip">
                        <label id="label-seguro_social" for="seguro_social">N° Seguro Social</label>
                        <input type="text" id="seguro_social" name="seguro_social" class="form-control" placeholder="11 dígitos">
                        <div id="seguro_social-feedback">&nbsp;</div>
                        <span id="tooltip-seguro_social" class="tooltip-box"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-3 col-md-4 col-sm-12 col-xs-12 tooltip">
                        <label id="label-rfc" for="rfc">RFC con Homoclave</label>
                        <input type="text" id="rfc" name="rfc" class="form-control" placeholder="13 carácteres">
                        <div id="rfc-feedback">&nbsp;</div>
                        <span id="tooltip-rfc" class="tooltip-box"></span>
                    </div>

                    <div class="col-5 col-md-8 col-sm-12 col-xs-12 tooltip">
                        <label id="label-email" for="email">Correo electrónico</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="correo@ejemplo.com">
                        <div id="email-feedback">&nbsp;</div>
                        <span id="tooltip-email" class="tooltip-box"></span>
                    </div>

                    <div class="col-2 col-md-6 col-sm-12 col-xs-12 tooltip">
                        <label id="label-telefono_casa" for="telefono_casa">Teléfono casa</label>
                        <input type="tel" id="telefono_casa" name="telefono_casa" class="form-control" placeholder="10 dígitos">
                        <div id="telefono_casa-feedback">&nbsp;</div>
                        <span id="tooltip-telefono_casa" class="tooltip-box"></span>
                    </div>

                    <div class="col-2 col-md-6 col-sm-12 col-xs-12 tooltip">
                        <label id="label-telefono_celular" for="telefono_celular">Teléfono celular</label>
                        <input type="tel" id="telefono_celular" name="telefono_celular" class="form-control" placeholder="10 dígitos">
                        <div id="telefono_celular-feedback">&nbsp;</div>
                        <span id="tooltip-telefono_celular" class="tooltip-box"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-8 col-md-12 col-sm-12 col-xs-12 tooltip">
                        <label id="label-calle" for="calle">Calle</label>
                        <input type="text" id="calle" name="calle" class="form-control" placeholder="1 caracter al menos">
                        <div id="calle-feedback">&nbsp;</div>
                        <span id="tooltip-calle" class="tooltip-box"></span>
                    </div>

                    <div class="col-2 col-md-6 col-sm-12 col-xs-12 tooltip">
                        <label id="label-numero_exterior" for="numero_exterior">Número exterior</label>
                        <input type="text" id="numero_exterior" name="numero_exterior" class="form-control">
                        <div id="numero_exterior-feedback">&nbsp;</div>
                        <span id="tooltip-numero_exterior" class="tooltip-box"></span>
                    </div>

                    <div class="col-2 col-md-6 col-sm-12 col-xs-12 tooltip">
                        <label id="label-numero_interior" for="numero_interior">Número interior</label>
                        <input type="text" id="numero_interior" name="numero_interior" class="form-control">
                        <div id="numero_interior-feedback">&nbsp;</div>
                        <span id="tooltip-numero_interior" class="tooltip-box"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-2 col-md-3 col-sm-4 col-xs-12 tooltip">
                        <label id="label-codigo_postal" for="codigo_postal">Código postal</label>
                        <input type="text" id="codigo_postal" name="codigo_postal" class="form-control" placeholder="5 dígitos">
                        <div id="codigo_postal-feedback">&nbsp;</div>
                        <span id="tooltip-codigo_postal" class="tooltip-box"></span>
                    </div>

                    <div class="col-10 col-md-9 col-sm-12 col-xs-12 tooltip">
                        <label id="label-id_colonia" for="id_colonia">Colonia</label>
                        <select name="id_colonia" id="id_colonia" class="form-control">
                            <option value="0">Ingrese primero el código postal</option>
                        </select>
                        <div id="id_colonia-feedback">&nbsp;</div>
                        <span id="tooltip-id_colonia" class="tooltip-box"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-3 col-md-4 col-sm-6 col-xs-12 tooltip">
                        <label id="label-fecha_inicio" for="fecha_inicio">Fecha de inicio</label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" value="<?php echo $data['current_date']; ?>">
                        <div id="fecha_inicio-feedback">&nbsp;</div>
                        <span id="tooltip-fecha_inicio" class="tooltip-box"></span>
                    </div>

                    <div class="col-4 col-md-4 col-sm-6 col-xs-12 tooltip">
                        <label id="label-id_puesto" for="id_puesto">Puesto</label>
                        <select name="id_puesto" id="id_puesto" class="form-control">
                            <option value="0">Seleccione</option>
                            <?php
                            for ($i = 0; $i < count($data['puestos']); $i++)
                            {
                                echo '<option value="'.$data['puestos'][$i]["id"].'">'.$data['puestos'][$i]["puesto"].'</option>'.PHP_EOL;
                            }
                            ?><!-- /. options -->
                        </select>
                        <div id="id_puesto-feedback">&nbsp;</div>
                        <span id="tooltip-id_puesto" class="tooltip-box"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-5 col-lg-6 col-md-8 col-sm-12 col-xs-12 tooltip">
                        <label id="label-profile_img" for="profile_img">Imagen de perfil <small>(solo archivos de imagen)</small></label>
                        <input type="file" id="profile_img" name="profile_img" class="form-control" accept="image/*">
                        <div id="profile_img-feedback">&nbsp;</div>
                        <span id="tooltip-profile_img" class="tooltip-box"></span>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-xs-12 col-xs-12">
                        <label>&nbsp;</label>
                        <input type="button" id="btn-remove-img" class="btn btn-warning w-100" value="Remover">
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 text-end">
                        <div class="animate__animated container-profile_img"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-8 col-md-6 col-sm-6 col-xs-12"></div>
                    <div class="col-4 col-md-6 col-sm-6 col-xs-12">
                        <input type="submit" id="btn-submit" class="btn btn-dark w-100" value="Guardar">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
