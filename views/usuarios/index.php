<input type="hidden" id="url" value="<?php echo ENTORNO; ?>">
<input type="hidden" id="registros" value="<?php echo $data[0]; ?>">
<div class="container">
    <div class="card shadow">
        <div class="card-header">
            <h2>
                <i class="fa-solid fa-bars btn-menu"></i>
                <span>Usuarios de la aplicación</span>
            </h2>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 text-start">
                    <a href="<?php echo ENTORNO; ?>/usuarios/nuevo" class="a-usuario">
                        <h3>
                            <i class="fa-solid fa-user-plus"></i>
                            <span> Agregar un nuevo usuario</span>
                        </h3>
                    </a>
                </div>
            </div>

            <!-- TABLA DE USUARIOS REGISTRADOS EN LA BD -->
            <section id="tableContainer">
                <table id="tabla-usuarios" class="table table-light table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Empleado</th>
                        <th>Usuario</th>
                        <th>Puesto</th>
                        <th>Acción</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    for ($i = 0; $i < count($data[1]); $i++)
                    {
                        echo '<tr>
                        <td>'.$data[1][$i]["id"].'</td>
                        <td>'.$data[1][$i]["empleado"].'</td>
                        <td>'.$data[1][$i]["usuario"].'</td>
                        <td>'.$data[1][$i]["puesto"].'</td>
                        <td>
                            <form name="form-edit_user_'.$data[1][$i]["id"].'" id="form-edit_user_'.$data[1][$i]["id"].'" action="'.ENTORNO.'/usuarios/editar" method="post">
                                <div class="container-edit_usuario" onclick="document.forms['."'form-edit_user_".$data[1][$i]["id"]."'".'].submit()">
                                    <input type="hidden" name="id" value="'.$data[1][$i]["id"].'">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                    <span>Editar</span>
                                </div>
                            </form>
                        </td>
                    </tr>';
                    }
                    ?><!-- ./ items -->
                    </tbody>
                </table>
            </section>
        </div>
    </div>
</div>
