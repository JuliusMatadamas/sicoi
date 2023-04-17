<h1>SubMódulos</h1>
<form action="<?php echo ENTORNO; ?>/submodulos" method="post">
    <div>
        <label for="submodulo">Submodulo</label>
        <input type="text" name="submodulo" id="submodulo">
    </div>
    <br>
    
    <div>
        <label for="ruta">Ruta</label>
        <input type="text" name="ruta" id="ruta">
    </div>
    <br>
    
    <div>
        <label for="idModulo">Id de Módulo</label>
        <select name="id_modulo" id="id_modulo">
            <option value="0">seleccione...</option>
            <?php
            for ($i = 0; $i < count($data); $i++)
            {
                echo '<option value="'.$data[$i]['id'].'">'.$data[$i]['modulo'].'</option>'.PHP_EOL;
            }
            ?><!-- /option -->
        </select>
    </div>
    <br>

    <div>
        <button type="submit">Guardar</button>
    </div>
</form>
