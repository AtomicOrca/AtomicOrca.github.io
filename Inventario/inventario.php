<?php header("Content-Type: text/html;charset=utf-8"); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"/>
        <title>Inventario</title>
        <script src="./js/func_inventario.js"></script>
    </head>
    <body>
        <div>
            <form name="filtros" onsubmit="return filtrar()">
                <input type="number" placeholder="ID" name="filtro_id" id="filtro_id" />
                <input type="text" placeholder="Codigo de barras" name="filtro_cb" id="filtro_cb" />
                <input type="text" placeholder="Descripción" name="filtro_descr" id="filtro_descr" />
                <input type="submit" value="Filtrar" />
            </form>
        </div>
        <div id="secc_inventario">
            <table id="tabla_productos"></table>
        </div>
        <?php

        require "../php/config_bd.php";
        $conexion_db = mysqli_connect($_hostdb, $_usuariodb, $_contradb, $_nombredb);

        if ($conexion_db) {
            mysqli_set_charset($conexion_db, "utf8");
            $consulta = "select * from productos";

            $filas = mysqli_query($conexion_db, $consulta);

            if ($filas->num_rows) {
                echo "<script>\n";
                while ( $fila = $filas->fetch_array() ) {
                    echo "agregar_producto({$fila['id_registro']}, '{$fila['id_codigo']}', '{$fila['descripcion']}', {$fila['cantidad']}, {$fila['precio']})\n";
                }
                echo "productos_mostrar = productos\n";
                echo "actualizar_tabla()\n";
                echo "document.currentScript.remove()\n";
                echo "</script>";
            }
            mysqli_close($conexion_db);
        }

        ?>
    </body>
</html>