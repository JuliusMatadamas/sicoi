<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">

<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Clientes - edición</span>
            </h2>
        </div>

        <section class="card-body">
            <div class="row">
                <div class="col-6 text-start">
                    <a href="<?php echo ENTORNO; ?>/clientes/consultar" class="a-cliente">
                        <h3>
                            <i class="fa-solid fa-users-viewfinder"></i>
                            <span> Consultar listado de clientes</span>
                        </h3>
                    </a>
                </div>

                <div class="col-6 text-end">
                    <a href="<?php echo ENTORNO; ?>/clientes/nuevo" class="a-cliente">
                        <h3>
                            <i class="fa-solid fa-user-plus"></i>
                            <span> Agregar un nuevo cliente</span>
                        </h3>
                    </a>
                </div>
            </div>

            <section>
                <form id="form-editar_cliente" action="<?php echo ENTORNO; ?>/clientes/editar" method="post">
                    <input type="hidden" id="id_usuario" value="<?php echo $_SESSION["usuario"]["id"]; ?>">
                    <input type="hidden" id="csrf" value="<?php echo $_SESSION["usuario"]["csrf"]; ?>">
                    <input type="hidden" id="id_cliente" value="<?php echo $data["cliente"]["id_cliente"]; ?>">

                    <div class="row">
                        <div class="col-4 col-sm-12 col-xs-12 tooltip">
                            <label for="nombre">Nombre:</label>
                            <input id="nombre" type="text" class="form-control" placeholder="Solo letras, entre 2 y 40." value="<?php echo $data["cliente"]["nombre"]; ?>">
                            <div id="nombre-feedback" class="">&nbsp;</div>
                            <div id="nombre-tooltip" class="tooltip-box"></div>
                        </div>

                        <div class="col-4 col-sm-6 col-xs-12 tooltip">
                            <label for="apellido_paterno">Apellido paterno:</label>
                            <input id="apellido_paterno" type="text" class="form-control" placeholder="Solo letras, entre 2 y 40." value="<?php echo $data["cliente"]["apellido_paterno"]; ?>">
                            <div id="apellido_paterno-feedback" class="">&nbsp;</div>
                            <div id="apellido_paterno-tooltip" class="tooltip-box"></div>
                        </div>

                        <div class="col-4 col-sm-6 col-xs-12 tooltip">
                            <label for="apellido_materno">Apellido materno:</label>
                            <input id="apellido_materno" type="text" class="form-control" placeholder="Opcional (solo letras, entre 2 y 40)." value="<?php echo $data["cliente"]["apellido_materno"]; ?>">
                            <div id="apellido_materno-feedback" class="">&nbsp;</div>
                            <div id="apellido_materno-tooltip" class="tooltip-box"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-6 col-xs-12 tooltip">
                                    <label for="fecha_nacimiento">Fecha de nacimiento:</label>
                                    <input id="fecha_nacimiento" type="date" class="form-control" placeholder="YYYY-MM-DD" value="<?php echo $data["cliente"]["fecha_nacimiento"]; ?>">
                                    <div id="fecha_nacimiento-feedback" class="">&nbsp;</div>
                                    <div id="fecha_nacimiento-tooltip" class="tooltip-box"></div>
                                </div>

                                <div class="col-6 col-xs-12 tooltip">
                                    <label for="id_genero">Genero:</label>
                                    <select id="id_genero" class="form-control">
                                        <option value="0">Seleccione</option>
                                        <?php
                                        foreach ($data["generos"] as $genero) {
                                            $idg = $genero["id"];
                                            $g = $genero["genero"];
                                            $genero["id"] == $data["cliente"]["id_genero"] ? $selected = "selected" : $selected = "";
                                            echo "<option value='$idg' $selected>$g</option>";
                                        }
                                        ?><!-- items -->
                                    </select>
                                    <div id="id_genero-feedback" class="">&nbsp;</div>
                                    <div id="id_genero-tooltip" class="tooltip-box"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-6 col-xs-12 tooltip">
                                    <label for="telefono_casa">Teléfono de casa:</label>
                                    <input id="telefono_casa" type="text" class="form-control" placeholder="10 dígitos" value="<?php echo $data["cliente"]["telefono_casa"]; ?>">
                                    <div id="telefono_casa-feedback" class="">&nbsp;</div>
                                    <div id="telefono_casa-tooltip" class="tooltip-box"></div>
                                </div>

                                <div class="col-6 col-xs-12 tooltip">
                                    <label for="telefono_celular">Teléfono celular:</label>
                                    <input id="telefono_celular" type="text" class="form-control" placeholder="10 dígitos" value="<?php echo $data["cliente"]["telefono_celular"]; ?>">
                                    <div id="telefono_celular-feedback" class="">&nbsp;</div>
                                    <div id="telefono_celular-tooltip" class="tooltip-box"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-5 col-md-12 col-sm-12 col-xs-12">
                            <label for="email">Correo electrónico:</label>
                            <input id="email" type="email" class="form-control" placeholder="tucorreo@ejemplo.com" value="<?php echo $data["cliente"]["email"]; ?>">
                            <div id="email-feedback" class="">&nbsp;</div>
                            <div id="email-tooltip" class="tooltip-box"></div>
                        </div>

                        <div class="col-2 col-md-4 col-sm-4 col-xs-12">
                            <label for="codigo_postal">Código Postal:</label>
                            <input id="codigo_postal" type="text" class="form-control" placeholder="5 dígitos" value="<?php echo $data["cliente"]["codigo_postal"]; ?>">
                            <div id="codigo_postal-feedback" class="">&nbsp;</div>
                            <div id="codigo_postal-tooltip" class="tooltip-box"></div>
                        </div>

                        <div class="col-5 col-md-8 col-sm-8 col-xs-12">
                            <label for="id_colonia">Seleccione la colonia:</label>
                            <select id="id_colonia" class="form-control">
                                <option value="0">Seleccione</option>
                                <?php
                                foreach ($data["colonias"] as $colonia) {
                                    $idc = $colonia["id"];
                                    $c = $colonia["colonia"];
                                    $colonia["id"] == $data["cliente"]["id_colonia"] ? $selected = "selected" : $selected = "";
                                    echo "<option value='$idc' $selected>$c</option>";
                                }
                                ?><!-- items -->
                            </select>
                            <div id="id_colonia-feedback" class="">&nbsp;</div>
                            <div id="id_colonia-tooltip" class="tooltip-box"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-2 col-lg-3 col-md-12 col-sm-12 col-xs-12">
                            <label for="rfc">RFC:</label>
                            <input id="rfc" type="text" class="form-control" placeholder="13 caracteres, solo letras y números." value="<?php echo $data["cliente"]["rfc"]; ?>">
                            <div id="rfc-feedback" class="">&nbsp;</div>
                            <div id="rfc-tooltip" class="tooltip-box"></div>
                        </div>

                        <div class="col-6 col-lg-5 col-md-12 col-sm-12 col-xs-12">
                            <label for="calle">Calle:</label>
                            <input id="calle" type="text" class="form-control" placeholder="Entre 2 y 45 caracteres." value="<?php echo $data["cliente"]["calle"]; ?>">
                            <div id="calle-feedback" class="">&nbsp;</div>
                            <div id="calle-tooltip" class="tooltip-box"></div>
                        </div>

                        <div class="col-2 col-md-6 col-sm-6 col-xs-12">
                            <label for="numero_exterior">N° exterior:</label>
                            <input id="numero_exterior" type="text" class="form-control" placeholder="Solo letras y números." value="<?php echo $data["cliente"]["numero_exterior"]; ?>">
                            <div id="numero_exterior-feedback" class="">&nbsp;</div>
                            <div id="numero_exterior-tooltip" class="tooltip-box"></div>
                        </div>

                        <div class="col-2 col-md-6 col-sm-6 col-xs-12">
                            <label for="numero_interior">N° interior:</label>
                            <input id="numero_interior" type="text" class="form-control" placeholder="Solo letras y números." value="<?php echo $data["cliente"]["numero_interior"]; ?>">
                            <div id="numero_interior-feedback" class="">&nbsp;</div>
                            <div id="numero_interior-tooltip" class="tooltip-box"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <label for="observaciones">Observaciones:</label>
                            <input id="observaciones" type="text" class="form-control" placeholder="Registrar indicaciones (ubicación y fachada del domicilio, horario de contacto, etc.) para localizar al cliente, al menos 10 caracteres." value="<?php echo $data["cliente"]["observaciones"]; ?>">
                            <div id="observaciones-feedback" class="">&nbsp;</div>
                            <div id="observaciones-tooltip" class="tooltip-box"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 text-end">
                            <div class="form-buttons row">
                                <div class="col-6 col-xs-12">
                                    <input id="btn-cancelar" type="button" value="Cancelar" class="btn btn-warning w-100">
                                </div>

                                <div class="col-6 col-xs-12">
                                    <button type="submit" class="btn btn-dark w-100">Actualizar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
    </div>
</div>
</div>

<section id="modal-confirm_cancel" class="modal">
    <div class="card">
        <div class="card-header">
            <h1 class="text-warning">Cancelar la actualización</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div id="modal-confirm_logout-message" class="col-12">¿Estás seguro que deseas cancelar la actualización de datos del cliente?</div>
            </div>
            <hr>
            <div class="row">
                <div id="modal-btn" class="col-6 col-xs-12 text-center">
                    <button class="btn btn-warning w-75" onclick="cancelarActualizacion()">Confirmar</button>
                </div>
                <div id="modal-btn" class="col-6 col-xs-12 text-center">
                    <button class="btn btn-dark w-75" onclick="ocultarModal(this)">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</section>