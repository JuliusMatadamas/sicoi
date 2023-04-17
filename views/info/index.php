<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Info</span>
            </h2>
        </div>

        <div class="card-body">
            <p class="text-center">
                <small>
                    <strong>
                        Usuario
                    </strong>
                </small>
            </p>

            <h3 class="text-center">
                <?php
                echo $_SESSION["usuario"]["nombre"] . " " . $_SESSION["usuario"]["apellido_paterno"] . " " . $_SESSION["usuario"]["apellido_materno"];
                ?>
            </h3>

            <p class="text-center">Número de seguro social</p>
            <p class="text-center"><b><?php echo $_SESSION["usuario"]["seguro_social"] ?></b></p>

            <p class="text-center">Registro Federal de Contribuyentes</p>
            <p class="text-center"><b><?php echo $_SESSION["usuario"]["rfc"] ?></b></p>

            <p class="text-center">Puesto</p>
            <p class="text-center"><b><?php echo $_SESSION["usuario"]["puesto"] ?></b></p>

            <p class="text-center">Inicio en la empresa</p>
            <p class="text-center"><b><?php echo $_SESSION["usuario"]["fecha_inicio"] ?></b></p>
        </div>
    </div>
</div>
