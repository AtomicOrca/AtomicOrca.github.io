<?php
session_start();
require "../php/funciones.php";
if ( !validar_sesion() || $_SESSION["os_usr_nivel"] != "SuperAdmin" && $_SESSION["os_usr_nivel"] != "Inventario" && $_SESSION["os_usr_nivel"] != "Administrador" ) {
    header("Location: ../");
}
require "../php/config_bd.php";

$conexion_db = mysqli_connect($_hostdb, $_usuariodb, $_contradb, $_nombredb);

if ( !$conexion_db ) {
    echo "Ocurrió un error al intentar conectarse con la base de datos, no se pudo obtener la información\n<br/>\n";
    echo "<a href='../'>Click para ir a la página de inicio</a>";
    exit();
}

$consulta = "select * from productos";

$resultado = mysqli_query($conexion_db, $consulta);

/*if ( !($resultado->num_rows) ) {
    echo "No hay información sobre productos en la base de datos\n<br/>\n";
    echo "<a href='../'>Click para ir a la página de inicio</a>";
    exit();
}*/

header('Content-type: application/txt;charset=utf-8');
//header("Content-Type: application/vnd.ms-excel");
date_default_timezone_set("America/Mexico_City");
$nombre_arch = "OS_Inventario_" . date("dmy_Hi") . ".csv";
header("Content-Disposition: attachment; filename=$nombre_arch");
?>

Offset Sale OS
Elementos en el inventario en la fecha y hora actual
,Fecha:,<?php echo date("d/m/y") ?>,,Hora:,<?php echo date("H:i:s") . "\n" ?>

ID registro,Codigo de barras,Descripcion,Precio,Cantidad,Registrado por
<?php
if ( $resultado->num_rows ) {
    while ( $fila = $resultado->fetch_array() ) {
        echo "{$fila["id_registro"]},\"{$fila["id_codigo"]}\",\"{$fila["descripcion"]}\",{$fila["precio"]},{$fila["cantidad"]},\"{$fila["usuario"]}\"\n";
    }
} else {
    echo "No se ha encontrado informacion en el inventario";
}
mysqli_close($conexion_db);
?>