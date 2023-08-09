<?php
    function v_texto($var) {
        return isset($var) && $var != "";
    }
    function validar_nivel($nivel) {
        return $nivel == "Administrador" || $nivel == "Cajero" || $nivel == "Inventario" || $nivel == "SuperAdmin";
    }
    function usr_comp($tipos_usuario) {
        $res = false;
        for ($i=0; $i < count($tipos_usuario); $i++) {
            if ( $_SESSION["os_usr_nivel"] == $tipos_usuario[$i] ) {
                $res = true;
                break;
            }
        }
        return $res;
    }
    function alerta_js($texto) {
        echo "<script>alert(\"" . $texto . "\")</script>";
    }
    function log_js($texto) {
        echo "<script>console.log(\"" . $texto . "\")</script>";
    }
    function consola_js($comandos) {
        echo "<script>" . $comandos . "</script>";
    }
    function validar_sesion() {
        return isset($_SESSION['os_usuario']) && $_SESSION['os_usuario'] != "" && isset($_SESSION['os_usr_nivel']) && validar_nivel($_SESSION['os_usr_nivel']);
    }
    function validar_session($index) {
        return isset($_SESSION[$index]) && $_SESSION[$index] != "";
    }
    function validar_post($index) {
        return isset($_POST[$index]) && $_POST[$index] != "";
    }
    function validar_get($index) {
        return isset($_GET[$index]) && $_GET[$index] != "";
    }
    function generar_cadena($longitud = 10) {
        $c_arr = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!#$%&+-*";
        $res = "  ";
        for ($i=0; $i<$longitud; $i++) {
            $rand = rand(0,strlen($c_arr));
            $res[$i] = $c_arr[$rand];
        }
        return $res;
    }
?>