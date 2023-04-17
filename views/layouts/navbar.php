<div class="side-bar">
    <header>
        <div class="close-btn">
            <i class="fas fa-times"></i>
        </div>
        <h1>
            <i class="fa-solid fa-warehouse"></i>
            <span>SICOI</span>
        </h1>
    </header>

    <ul class="nav-links">
        <li>
            <a href="<?php echo ENTORNO; ?>/info">
                <i class="fa-solid fa-house"></i>
                <span class="link-name">Inicio</span>
            </a>
        </li>
        <!-- USER LINKS -->
        <?php
        $output = [];
        for ($i = 0; $i < count($_SESSION["usuario"]["modulos"]); $i++) {
            $output[$i]["id_modulo"] = $_SESSION["usuario"]["modulos"][$i]["id_modulo"];
            $output[$i]["modulo"] = $_SESSION["usuario"]["modulos"][$i]["modulo"];
            $output[$i]["icono"] = $_SESSION["usuario"]["modulos"][$i]["icono"];
        }
        $modulos = array_map("unserialize", array_unique(array_map("serialize", $output)));
        foreach ($modulos

        as $key => $modulo) {
        ?><!-- ITEM MENU -->
        <li>
            <div class="icon-link">
                <a href="#">
                    <i class="fa-solid <?php echo $modulo["icono"]; ?>"></i>
                    <span class="link-name"><?php echo $modulo["modulo"]; ?></span>
                </a>
                <i class="fa-solid fa-sort-down arrow"></i>
            </div>
            <ul class="sub-menu">
                <?php
                sort($_SESSION["usuario"]["modulos"]);
                for ($j = 0;
                $j < count($_SESSION["usuario"]["modulos"]);
                $j++) {
                if ($_SESSION["usuario"]["modulos"][$j]["id_modulo"] == $modulo["id_modulo"]) {
                ?><!-- item submenu -->
                <li>
                    <a href="<?php echo ENTORNO . $_SESSION["usuario"]["modulos"][$j]["ruta"]; ?>"><?php echo $_SESSION["usuario"]["modulos"][$j]["submodulo"]; ?></a>
                </li>
                <?php
                }
                }
                ?><!-- ./ SUBMENU -->
            </ul>
        </li>
        <?php
        }

        ?><!-- ./USER LINKS -->

        <!-- USER PROFILE -->
        <li>
            <div class="profile-details">
                <div class="profile-content">
                    <img src="<?php echo ENTORNO . $_SESSION["usuario"]["profile_img"]; ?>">
                </div>
                <div class="name-job">
                    <div class="profile_name"><?php echo $_SESSION["usuario"]["nombre"] . " " . $_SESSION["usuario"]["apellido_paterno"]; ?></div>
                    <div class="job"><?php echo $_SESSION["usuario"]["puesto"]; ?></div>
                </div>
                <i class="fa-solid fa-arrow-right-from-bracket" onclick="confirmLogOut()"></i>
            </div>
        </li>
    </ul>
</div>