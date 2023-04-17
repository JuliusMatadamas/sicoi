<input type="hidden" id="entorno" value="<?php echo ENTORNO; ?>">

<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Info - vacaciones</span>
            </h2>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-5 col-xs-12 col-sm-10 col-md-7">
                    <a href="<?php echo ENTORNO; ?>/info/vacaciones" class="a-vacacion">
                        <i class="fa-solid fa-left-long"></i>
                        <span>Volver al listado de vacaciones.</span>
                    </a>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-12">
                    <h3>Crear solicitud de vacaciones</h3>
                </div>
            </div>

            <?php
            $fechaDeInicio = $_SESSION["usuario"]["fecha_inicio"];
            $fechaActual = date("Y-m-d");

            $startDate = new DateTime($fechaDeInicio);
            $currentDate = new DateTime($fechaActual);

            $interval = $currentDate->diff($startDate);
            $antiguedad = intval($interval->format('%y'));

            if ($antiguedad == 0) {
                ?><div class="row">
                <div class="col-12">
                    <p>Aun no cuentas con la antigüedad requeridad para disponer de vacaciones por ley.</p>
                </div>
            </div>
            <?php
            } else {
                // SI NO SE HAN REGISTRADO FECHAS A TOMAR DE VACACIONES
                if (count($data) == 0) {
                    $diasSolicitados = 0;
                } else {
                    $diasSolicitados = array_count_values(array_column($data, 'age'))[$antiguedad];
                }
                // DÍAS DE VACACIONES POR AÑO SEGUN LEY FEDERAL DEL TRABAJO
                $diasPorAnnio = [1 => 12, 2 => 14, 3 => 16, 4 => 18, 5 => 20, 10 => 22, 15 => 24, 20 => 26, 25 => 28, 30 => 30, 35 => 32, 40 => 34, 45 => 36, 50 => 38];
                $diasPorTomar = $diasPorAnnio[$antiguedad] - $diasSolicitados;
                $diasQueCorresponden = $diasPorAnnio[$antiguedad];

                // INICIO DE PERÍODO
                $periodoInicio = date("Y-m-d", strtotime("+$antiguedad year", strtotime($fechaDeInicio)));

                // FINALIZACIÓN DE PERÍODO
                $periodoTermino = date("Y-m-d", strtotime("+".($antiguedad + 1)." year", strtotime($fechaDeInicio)));

                ?><form id="form-vacaciones" action="<?php echo ENTORNO; ?>/info/vacaciones/store" method="post">
                <input type="hidden" name="csrf" id="csrf" value="<?php echo $_SESSION["usuario"]["csrf"]; ?>">
                <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo $_SESSION["usuario"]["id"]; ?>">
                <input type="hidden" name="age" id="age" value="<?php echo $antiguedad; ?>">

                <div class="row">
                    <div class="col-12">
                        <h4>Ingresa las fechas que vas a disponer de vacaciones</h4>
                        <p>Por ley te corresponden <?php echo $diasQueCorresponden; ?> días, haz solicitado <?php echo $diasSolicitados; ?> días y te quedan <?php echo $diasPorTomar; ?> días por solicitar.</p>
                    </div>
                </div>

                <?php
                for ($i = 0; $i < $diasPorTomar; $i++) {
                    ?><div class="row">
                    <div class="col-2 col-lg-2 col-md-2 col-sm-3 col-xs-4 text-end">
                        <label for="fecha_<?php echo $i; ?>">Fecha <?php echo (($i + 1) < 10 ? '0' . ($i + 1) : $i + 1); ?></label>
                    </div>
                    <div class="col-3 col-lg-3 col-md-4 col-sm-5 col-xs-5">
                        <input type="date" name="fecha_<?php echo $i; ?>" id="fecha_<?php echo $i; ?>" class="form-control" min="<?php echo $periodoInicio; ?>" max="<?php echo $periodoTermino; ?>">
                    </div>
                </div>
                <?php
                }
                ?><!-- ./ fechas -->

                <div class="row">
                    <div class="col-12 text-end">
                        <?php
                        if ($diasPorTomar > 0) {
                            echo '<button id="btn-submit" class="btn btn-dark">Guardar</button>';
                        }
                        ?><!-- ./ button -->
                    </div>
                </div>
            </form>
            <?php
            }
            ?><!-- ./ días disponibles a solicitar -->
        </div>
    </div>
</div>

<section id="modal-vacaciones_message" class="modal">
    <div class="card">
        <div class="card-header">
            <h1 class="text-warning" id="vacaciones_message-header"></h1>
        </div>
        <div class="card-body">
            <div class="row">
                <div id="vacaciones_message-body" class="col-12"></div>
            </div>
            <hr>
            <div class="row">
                <div id="modal-btn" class="col-12 text-end">
                    <button class="btn btn-dark" onclick="ocultarModal(this)">Enterado</button>
                </div>
            </div>
        </div>
    </div>
</section>
