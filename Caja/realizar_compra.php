<?php
session_start();
require "../php/funciones.php";
//Validar usuario
if ( !validar_sesion() || ($_SESSION["os_usr_nivel"] != "SuperAdmin" && $_SESSION["os_usr_nivel"] != "Administrador" && $_SESSION["os_usr_nivel"] != "Cajero") )  {
    header("Location: ../");
    exit();
}
$margen_precio = 10;

if ( isset($_POST['enviar_compra']) && validar_post("info_compra") && validar_post("dinero_ingresado") ) {
    $ingresado = $_POST["dinero_ingresado"];
    $total = 0;
    $productos_info = explode("__", $_POST["info_compra"]);
    if ( count($productos_info) == 0 ) {
        header("Location: ./carrito.php");
    }

    $consulta_check = "select * from productos where ";
    $len = count($productos_info);
    for ($i=0; $i < $len; $i++) { 
        $prod_info = explode("::", $productos_info[$i]);
        $id = $prod_info[0];
        $cant = $prod_info[1];
        $precio = $prod_info[2];
        $total = $cant * $precio;
        $productos[$i]["id"] = $id;
        $productos[$i]["cant"] = $cant;
        $productos[$i]["precio"] = $precio;
        $consulta_check .= "(id_registro = $id and cantidad >= $cant and (precio = $precio or precio between $precio - $margen_precio and $precio + $margen_precio))";
        if ( $i + 1 < $len ) { $consulta_check .= " or "; }
    }

    if ( $ingresado < $total ) {
        header("Location: ./carrito.php?ic={$_POST['info_compra']}&insuficiente");
        exit();
    }

    //echo $consulta_check;

    require "../php/config_bd.php";
    $conexion_db = mysqli_connect($_hostdb, $_usuariodb, $_contradb, $_nombredb);
    if ($conexion_db) {
        $res = mysqli_query($conexion_db, $consulta_check);
        $cambio = 0;
        if ( $res->num_rows == $len ) {
            //echo "Correcto, continua";

            $consulta = "insert into compras (fecha_hora, total, usuario) values (sysdate(), 0, '{$_SESSION["os_usuario"]}')";

            mysqli_query($conexion_db, $consulta);

            $id_compra = mysqli_insert_id($conexion_db);

            for ($i=0; $i < $len; $i++) {
                //$consulta = "insert into productos_compras (id_compra, id_producto, cantidad, precio_venta) values ($id_compra, {$productos[$i]['id']}, {$productos[$i]['cant']}, (select precio from productos where id_registro={$productos[$i]['id']}) );";
                $consulta = "insert into productos_compras (id_compra, id_producto, cantidad, precio_venta) values ($id_compra, {$productos[$i]['id']}, {$productos[$i]['cant']}, {$productos[$i]['precio']} );";
                mysqli_query($conexion_db, $consulta);
                $consulta = "update productos set cantidad = cantidad - {$productos[$i]['cant']} where id_registro = {$productos[$i]['id']};";
                mysqli_query($conexion_db, $consulta);
            }
            
            //$consulta = "select SUM(p.subtotal) total from (select q.id_compra, p.id_codigo, p.descripcion, p.precio, r.cantidad, (r.precio_act * r.cantidad) subtotal from productos p, compras q, productos_compras r where p.id_registro = r.id_producto and q.id_compra = r.id_compra and q.id_compra = $id_compra) p;";
            $consulta = "select SUM(p.subtotal) total from (select (r.precio_venta * r.cantidad) subtotal from compras q, productos_compras r where q.id_compra = r.id_compra and q.id_compra = $id_compra) p;";
            
            $res_total = mysqli_query($conexion_db, $consulta);
            
            $total = ($res_total->fetch_array())["total"];
            
            $consulta = "update compras set total = $total where id_compra = $id_compra;";
            
            mysqli_query($conexion_db, $consulta);

            //echo "Compra registrada correctamente";
            $cambio = $ingresado - $total;
            $fin_correcto = true;
        } else {
            //echo "Por ahi hay un error";
        }
        mysqli_close($conexion_db);
        if (isset($fin_correcto))
            header("Location: ./resumen_venta.php?id=$id_compra&postcompra&cambio=$cambio");
        else
            header("Location: ./carrito.php?ic={$_POST['info_compra']}");
    } else {
        header("Location: ./carrito.php?ic={$_POST['info_compra']}");
    }
} else {
    //echo "No se ha recibido información sobre compra";
    header("Location: ./carrito.php");
}
?>