<?php
require "./php/funciones.php";
session_start();
if ( validar_sesion() ) {
    unset($_SESSION['os_usuario']);
    unset($_SESSION['os_usr_nivel']);
}
header("Location: ./iniciar_sesion.php");
exit();

?>