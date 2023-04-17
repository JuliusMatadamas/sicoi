<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">
<input type="hidden" id="id_usuario" value="<?php echo $_SESSION["usuario"]["id"]; ?>">
<input type="hidden" id="csrf" value="<?php echo $_SESSION["usuario"]["csrf"]; ?>">
<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Técnicos - modificar visita</span>
            </h2>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 text-start">
                    <a href="<?php echo ENTORNO; ?>/tecnicos/visitas_asignadas" class="a-visita">
                        <h3>
                            <i class="fa-solid fa-list"></i>
                            <span> Volver a la lista de visitas asignadas</span>
                        </h3>
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-6 col-xs-12 col-sm-12">
                    <strong>Cliente:</strong>
                    <span><?php echo $data["visita"][0]["cliente"]; ?></span>
                </div>

                <div class="col-6 col-xs-12 col-sm-12">
                    <strong>Fecha programada:</strong>
                    <span id="fecha_programada"><?php echo $data["visita"][0]["fecha_programada"]; ?></span>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <strong>Tipo:</strong>
                    <span><?php echo $data["visita"][0]["tipo_de_venta"]; ?></span>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <strong>Colonia: </strong>
                    <span><?php echo $data["visita"][0]["colonia"]; ?>. </span>
                    <strong>Domicilio:</strong>
                    <span><?php echo $data["visita"][0]["calle"]; ?> </span>
                    <strong>Ext:</strong>
                    <span><?php echo $data["visita"][0]["numero_exterior"]; ?> </span>
                    <strong>Int:</strong>
                    <span><?php echo $data["visita"][0]["numero_interior"]; ?> </span>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <strong>Observaciones: </strong>
                    <span><?php echo $data["visita"][0]["observaciones"]; ?>. </span>
                </div>
            </div>

            <div class="row tels">
                <div class="col-6 col-xs-12">
                    <a href="tel:+52<?php echo $data["visita"][0]["telefono_casa"]; ?>">
                        <strong>
                            <span>Tel. Casa</span>
                            <i class="fa-solid fa-phone-flip"></i>
                            <span>: </span>
                        </strong>
                        <span><?php echo $data["visita"][0]["telefono_casa"]; ?></span>
                    </a>
                </div>

                <div class="col-6 col-xs-12">
                    <a href="tel:+52<?php echo $data["visita"][0]["telefono_celular"]; ?>">
                        <strong>
                            <span>Tel. Celular</span>
                            <i class="fa-solid fa-phone-flip"></i>
                            <span>: </span>
                        </strong>
                        <span><?php echo $data["visita"][0]["telefono_celular"]; ?></span>
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <label for="acciones">Acción a realizar:</label>
                    <select class="form-select" id="acciones">
                        <option value="0">Seleccionar...</option>
                        <option value="1">Registrar visita</option>
                        <option value="2">Reprogramar</option>
                        <option value="3">Sin encontrar</option>
                    </select>
                </div>
            </div>

            <div id="container-form_actions">
                <!-- FORM REGISTRAR VISITA -->
                <form id="form-registrar_visita" action="<?php echo ENTORNO; ?>/tecnicos/actualizar_visita"
                      method="post"
                      onsubmit="return obtenerUbicacion(event, validarForm1)">
                    <div class="row">
                        <div class="col-8 col-xs-12 col-sm-12 col-md-12 col-lg-7 tooltip">
                            <label for="observaciones_visita">Ingrese las observaciones de la visita:</label>
                            <input type="text" class="form-control" id="observaciones_visita"
                                   placeholder="Ejemplo: Se probaron varios decodificadores porque no se captaba la señal.">
                            <div id="observaciones_visita-feedback" class="">Entre 10 y 255 caracteres</div>
                            <div id="observaciones_visita-tooltip" class="tooltip-box">&nbsp;</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 text-end">
                            <small>Se registrará tu ubicación cuando des clic en el botón</small>
                            <button type="submit" class="btn btn-dark w-xs-100 w-sm-100 w-md-50 w-lg-25">Guardar cambios
                            </button>
                        </div>
                    </div>
                </form>

                <!-- FORM REPROGRAMACIÓN -->
                <form id="form-reprogramacion" action="<?php echo ENTORNO; ?>/tecnicos/actualizar_visita" method="post"
                      onsubmit="return obtenerUbicacion(event, validarForm2)">
                    <div class="row">
                        <div class="col-4 col-xs-12 col-sm-12 col-md-6 col-lg-5 tooltip">
                            <label for="fecha_reprogramada">Ingrese la nueva fecha a realizar la visita:</label>
                            <input type="date" class="form-control" id="fecha_reprogramada"
                                   min="<?php echo $data["min"]; ?>" max="<?php echo $data["max"]; ?>">
                            <div id="fecha_reprogramada-feedback" class="">La nueva fecha no debe ser posterior a 15
                                días.
                            </div>
                            <div id="fecha_reprogramada-tooltip" class="tooltip-box">&nbsp;</div>
                        </div>

                        <div class="col-8 col-xs-12 col-sm-12 col-md-12 col-lg-7 tooltip">
                            <label for="observaciones_reprogramacion">Ingrese el motivo de la reprogramación de la
                                visita:</label>
                            <input type="text" class="form-control" id="observaciones_reprogramacion"
                                   placeholder="Ejemplo: El cliente no se iba a encontrar en el domicilio y solicitó se le visite en otra fecha">
                            <div id="observaciones_reprogramacion-feedback" class="">Entre 10 y 255 caracteres</div>
                            <div id="observaciones_reprogramacion-tooltip" class="tooltip-box">&nbsp;</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 text-end">
                            <small>Se registrará tu ubicación cuando des clic en el botón</small>
                            <button type="submit" class="btn btn-dark w-xs-100 w-sm-100 w-md-50 w-lg-25">Guardar cambios
                            </button>
                        </div>
                    </div>
                </form>

                <!-- FORM NO ENCONTRADO -->
                <form id="form-no_encontrado" action="<?php echo ENTORNO; ?>/tecnicos/actualizar_visita" method="post"
                      onsubmit="return obtenerUbicacion(event, validarForm3)">
                    <div class="row">
                        <div class="col-12 tooltip">
                            <p>Maque la opción que aplique al caso</p>
                            <div id="msg-tooltip" class="tooltip-box">&nbsp;</div>
                        </div>
                    </div>

                    <div id="opciones"></div>

                    <div class="row">
                        <div class="col-12 tooltip">
                            <label for="observaciones_no_encontrado">Observaciones</label>
                            <textarea id="observaciones_no_encontrado" class="form-control" cols="30" rows="2"
                                      placeholder="Entre 10 y 255 caracteres"></textarea>
                            <div id="observaciones_no_encontrado-feedback" class="">&nbsp;</div>
                            <div id="observaciones_no_encontrado-tooltip" class="tooltip-box">&nbsp;</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 text-end">
                            <small>Se registrará tu ubicación cuando des clic en el botón</small>
                            <button type="submit" class="btn btn-dark w-xs-100 w-sm-100 w-md-50 w-lg-25">Guardar cambios
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<section id="modal-registrar_visita" class="modal">
    <div class="card">
        <div class="card-header">
            <h1 class="text-success">Visita registrada</h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div id="modal-registrar_visita-message" class="col-12">Ahora debes registrar la salida de tu inventario
                    de los materiales utilizados.
                </div>
            </div>
            <hr>
            <div class="row">
                <div id="modal-btn" class="col-12">
                    <form action="<?php echo ENTORNO ?>/tecnicos/salidas" method="post">
                        <input type="hidden" name="id_venta" id="id_venta" value="<?php echo $data["id_venta"]; ?>">
                        <input type="hidden" name="id_cliente" id="id_cliente"
                               value="<?php echo $data["id_cliente"]; ?>">
                        <button type="submit" class="btn btn-dark w-50">Enterado</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
