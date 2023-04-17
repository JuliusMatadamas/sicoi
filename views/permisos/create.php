<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">

<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Info - permisos</span>
            </h2>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-5 col-xs-12 col-sm-10 col-md-7">
                    <a href="<?php echo ENTORNO; ?>/info/permisos" class="a-permiso">
                        <i class="fa-solid fa-left-long"></i>
                        <span>Volver al listado de permisos.</span>
                    </a>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-12">
                    <h3>Crear solicitud de permiso</h3>
                </div>
            </div>

            <!-- FORMULARIO -->
            <form id="form-create_permiso" action="<?php echo ENTORNO; ?>/info/permisos/nuevo" method="post">
                <input type="hidden" name="csrf" id="csrf" value="<?php echo $_SESSION["usuario"]["csrf"]; ?>">
                <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION["usuario"]["id"]; ?>">

                <div class="row">
                    <div class="col-4 col-md-6 col-sm-12 col-xs-12">
                        <p>Indique la fecha y hora de inicio</p>
                        <div class="row">
                            <div class="col-8 tooltip">
                                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="">
                                <div id="feedback-fecha_inicio" class="">&nbsp;</div>
                                <span id="tooltip-fecha_inicio" class="tooltip-box"></span>
                            </div>

                            <div class="col-4 tooltip">
                                <select name="hora_inicio" id="hora_inicio" class="form-control">
                                    <?php
                                    foreach ($data[0] as $key => $value) {
                                        echo "<option value='$value'>$value</option>";
                                    }
                                    ?><!-- ./options -->
                                </select>
                                <div id="feedback-hora_inicio" class="">&nbsp;</div>
                                <span id="tooltip-hora_inicio" class="tooltip-box"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-4 col-md-6 col-sm-12 col-xs-12">
                        <p>Indique la fecha y hora de término</p>
                        <div class="row">
                            <div class="col-8 tooltip">
                                <input type="date" name="fecha_termino" id="fecha_termino" class="form-control" value="">
                                <div id="feedback-fecha_termino" class="">&nbsp;</div>
                                <span id="tooltip-fecha_termino" class="tooltip-box"></span>
                            </div>

                            <div class="col-4 tooltip">
                                <select name="hora_termino" id="hora_termino" class="form-control">
                                    <?php
                                    foreach ($data[0] as $key => $value) {
                                        echo "<option value='$value'>$value</option>";
                                    }
                                    ?><!-- ./options -->
                                </select>
                                <div id="feedback-hora_termino" class="">&nbsp;</div>
                                <span id="tooltip-hora_termino" class="tooltip-box"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <p>Indique el motivo por el que solicita el permiso.</p>
                    <div class="col-12">
                        <textarea name="motivo" id="motivo" cols="30" rows="5" class="form-control" placeholder="Mínimo 20 caracteres, ejemplo: junta de padres en la esc. de hijo, realizar trámite personal, etc."></textarea>
                        <div id="feedback-motivo" class="">&nbsp;</div>
                        <span id="tooltip-motivo" class="tooltip-box"></span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-8 col-sm-6 col-xs-12">&nbsp;</div>
                    <div class="col-4 col-sm-6 col-xs-12">
                        <button type="submit" id="btn-submit" class="btn btn-dark w-100">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
