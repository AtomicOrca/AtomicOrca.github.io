<?php

session_start();
require "../php/funciones.php";

if ( validar_sesion() && $_SESSION['os_usr_nivel'] == "SuperAdmin" ) {
    $sesion_activa = true;

    if ( isset($_POST['enviar']) ) {
        if ( validar_post("usuario") && validar_post("contra") && validar_post("contra_2") && $_POST["contra"] == $_POST["contra_2"] && validar_post("tipo_usuario") && validar_post("nombre") && validar_post("correo") ) {
            require "../php/config_bd.php";
            $conexion_db = mysqli_connect($_hostdb, $_usuariodb, $_contradb, $_nombredb);
            if ($conexion_db != null) {

                $habilitado = isset($_POST['habilitado'])? 1 : 0;

                $consulta = "insert into usuarios (usuario, contra, nivel, habilitado, nombre, correo) values ('{$_POST['usuario']}', sha2('{$_POST['contra']}',256), '{$_POST['tipo_usuario']}', $habilitado, '{$_POST["nombre"]}', '{$_POST["correo"]}')";

                $res = mysqli_query($conexion_db, $consulta);

                if ( !$res ) {
                    $mensaje = "No fue posible registrar, es posible que el usuario ya se encuentra registrado o un dato no es válido";
                    $error_registro = true;
                } else {
                    $registrado = true;
                    $mensaje = "El usuario fue correctamente registrado";
                }

                mysqli_close($conexion_db);
            } else {
                $mensaje = "Ha ocurrido un error al intentarse conectar a la base de datos";
            }

        } else {
            $mensaje = "Ha ocurrido un error al recibir la informacion, favor de intentarlo de nuevo";
        }
    }
} else {
    $mensaje_permiso = "Para ejecutar esta herramienta es necesario tener ciertos permisos";
}

?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <!--title>Creative - Start Bootstrap Theme</title-->
        <title>Consultar inventario - Offset Sale (OS)</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="../img/logo.png" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v5.13.0/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
        <!-- Third party plugin CSS-->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="../css/styles.css" rel="stylesheet" />
        <style>
            #btn_inicio {
                background-color: #3a91f4;
                border-radius: 1.5em;
                text-align: center !important;
                padding-right: 15px !important;
                color: #fff !important;
            }
            header.masthead {
                /*background: linear-gradient(to bottom, rgba(73, 98, 119, 0.7) 0%, rgba(10, 17, 124, 0.8) 100%)/*, url("./assets/img/bg-masthead.jpg") !important;*/
                background: linear-gradient(to bottom, rgba(92, 106, 118, 0.7) 100%, rgba(92, 106, 118, 0.8) 100%)/*, url("./assets/img/bg-masthead.jpg") !important*/;
            }
            .text-white-75 {
                margin-bottom: 0.2em !important;
            }
            .mt-5 {
                background-color: #ffffffaa;
                border-radius: 0.7em;
                padding: 10px;
                margin-top: 1.7em !important;
            }
            .row>a {
                color: rgba(10, 17, 150, 0.9);
            }
            .row>a:hover {
                color: #f4623a;
            }
            .container>h2 {
                color: rgba(4, 8, 94, 0.9);
            }
            #c_ses:hover {
                cursor: pointer;
            }
            .bloqueado {
                background-color: #505050;
            }
        </style>
        <link href="./styleForm.css" rel="stylesheet" />
        <script>
            function cerrar_sesion() {
                if (confirm("¿Seguro/a de cerrar la sesión actual?"))
                    window.location.href = "../cerrar_sesion.php";
            }
            function privilegios() {
                alert("Lo sentimos, tu nivel de usuario no cuenta con los privilegios necesarios para administrar esta sección.")
            }
            <?php if (isset($mensaje_permiso)) { echo "alert('$mensaje_permiso')\nwindow.location.href = '../'\n"; } ?>
            const validar_correo = (correo) => {
                if ( correo.length <= 5 ) return 1
                if ( correo.length > 30 ) return 2
                if ( (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3,4})+$/.test(correo)) ) return 3
                return 0
                //return correo.length > 5 && correo.length < 20 && (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3,4})+$/.test(valor))
            }
            const validar = () => {
                var resultado = true
                var campos = ""
                if ( !validar_campo(document.getElementById("usuario"), 1, 20) ) {
                    campos += "- Usuario\n"
                    resultado = false
                }
                if ( !validar_campo(document.getElementById("contra"), 1, 20) ) {
                    campos += "- La contraseña no cumple los requerimientos\n"
                    resultado = false
                }
                if ( document.getElementById("contra").value != document.getElementById("contra_2").value ) {
                    campos += "- Las contraseñas no coinciden\n"
                    resultado = false
                }
                if ( tipos_usuario.indexOf(document.getElementById("tipo_usuario").value) == -1 ) {
                    campos += "- Tipo de usuario no válido\n"
                    resultado = false
                }                
                var res_vcorreo = validar_correo(document.getElementById("correo").value)
                switch (res_vcorreo) {
                    case 0: break
                    case 1: 
                        campos += "La longitud del correo ingresado es muy pequeña"
                        resultado = false
                        break
                    case 2:
                        campos += "La longitud del correo ingresado es muy pequeña"
                        resultado = false
                        break
                    case 3:
                        campos += "La estructura del correo ingresado no es válida"
                        resultado = false
                        break
                    default:
                        campos += "Error desconocido"
                        resultado = false
                        break
                }

                if (!resultado) alert("Los datos no son válidos, favor de verificar: \n" + campos)
                if ( document.getElementById("nombre").value < 8 && !confirm("La longitud del nombre ingresada es muy pequeña\n¿Quieres continuar?")  )
                    return false
                return resultado
            }
            const sesion_activa = () => {
                var pagina = "../cerrar_sesion.php"
                if ( !confirm("Ya se encuentra una sesión activa.\n¿Deseas iniciar como otro usuario (cerrar sesión)?") )
                    pagina = "../"
                window.location.href = pagina
            }
            const permisos_requeridos = (mensaje) => {
                alert(mensaje)
                window.location.href = "../"
            }
        </script>
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
            <div class="container">
                <!--a class="navbar-brand js-scroll-trigger" href="http://www.upslp.edu.mx">UPSLP</a-->
                <a class="navbar-brand js-scroll-trigger" href="../">Inicio</a>
                <a class="navbar-brand js-scroll-trigger">-></a>
                <a class="navbar-brand js-scroll-trigger" href="./">Usuarios</a>
                <a class="navbar-brand js-scroll-trigger">-></a>
                <a class="navbar-brand js-scroll-trigger" style="color: #1a1a1a;">Registrar usuario</a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto my-2 my-lg-0">
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" style="color: black;"><?php echo "{$_SESSION["os_usuario"]} ({$_SESSION["os_usr_nivel"]})" ?></a></li>
                        <!--li class="nav-item"><a class="nav-link js-scroll-trigger" < ?php if (usr_comp(["SuperAdmin", "Administrador", "Inventario"])) echo 'href="./Inventario/"'; else echo 'onclick="privilegios()"'; ?>>Inventario</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" < ?php if (usr_comp(["SuperAdmin", "Administrador", "Cajero"])) echo 'href="./Caja/"'; else echo 'onclick="privilegios()"'; ?>>Caja</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" < ?php if (usr_comp(["SuperAdmin"])) echo 'href="./Usuarios/"'; else echo 'onclick="privilegios()"'; ?>>Usuarios</a></li-->
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" id="c_ses" onclick="cerrar_sesion()">Cerrar sesión</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Masthead-->
        <header class="masthead">
            <div class="container h-100">
                <div class="row h-100 align-items-center justify-content-center text-center">
                    <div class="container">
                        <h2 class="text-center mt-0">Registrar usuario en sistema.</h2>
                        <p class="text-white-75 font-weight-light mb-5" id="usuario">Bienvenido: <?php echo "{$_SESSION["os_usuario"]} - {$_SESSION["os_usr_nivel"]}" ?></p>
                        <!--p class="text-white-75 font-weight-light mb-5">Dentro de esta página podrás utilizar las herramientas para el inventario.</p-->
                        <hr class="divider my-4" />
                        <div id="div_formu">
                        <form action="" method="post" onsubmit="return validar()">
                            <label for="usuario">Usuario:</label>
                            <input type="text" id="usuario" name="usuario" max="20" placeholder="Usuario" required />
                            <br />
                            <label for="contra">Contraseña:</label>
                            <br />
                            <input type="password" id="contra" name="contra" max="20" placeholder="Contraseña" required />
                            <br />
                            <label for="contra_2">Repetir contraseña:</label>
                            <br />
                            <input type="password" id="contra_2" name="contra_2" max="20" placeholder="Repetir contraseña" required />
                            <br />
                            <br />
                            <select id="tipo_usuario" name="tipo_usuario"  required>
                                <option value="">-- Selecciona un tipo de usuario --</option>
                                <option>Administrador</option>
                                <option>Cajero</option>
                                <option>Inventario</option>
                            </select>
                            <br />
                            <br />
                            <label for="habilitado">Habilitado: </label>
                            <input type="checkbox" id="chkbx_habilitado" name="habilitado" checked />
                            <br />
                            <br />
                            <label for="nombre">Nombre: </label>
                            <input type="text" id="nombre" name="nombre" max="50" placeholder="Nombre" required />
                            <br />
                            <label for="correo">Correo: </label>
                            <input type="mail" id="correo" name="correo" max="30" placeholder="Correo" required />
                            <br />
                            <input type="submit" id="btn_enviar" name="enviar" />
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
        <!-- Footer-->
        <footer class="bg-light py-5">
            <div class="container">
                <div class="small text-center text-muted">
                    Offset Sale - OS (Sistema para gestión en tienda pequeña)
                    <br/>
                    Desarrollado por alumnos de la carrera ITI en UPSLP:
                    <div>
                        <li>Cesar David Gamez Ortega - (171957) - <a href="mailto:171957@upslp.edu.mx">Correo</a></li>
                        <li>Cesar Alejandro Gomez Martinez - (180253) - <a href="mailto:180253@upslp.edu.mx">Correo</a></li>
                        <li>Cinthya Alejandra Martinez Hernandez - (172660) - <a href="mailto:172660@upslp.edu.mx">Correo</a></li>
                    </div>
                    Desarrollado como --
                    <br>
                    <a href="creditos.html" target="_blank">Más información del proyecto</a>
                </div>
            </div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>
        <!-- Third party plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
        <!-- Core theme JS-->
        <script src="../js/scripts.js"></script>
        <script src="https://use.fontawesome.com/2b56ecae06.js"></script>
        <script>
        <?php
        if (isset($mensaje)) echo "alert(\"$mensaje\")\n";
        if ( isset($_POST['enviar']) && !isset($registrado) ) { ?>
            <?php if ( validar_post("usuario") ) ?> document.getElementById("usuario").value = <?php echo "'{$_POST["usuario"]}'\n" ?>
            <?php if ( validar_post("tipo_usuario") ) ?> document.getElementById("tipo_usuario").value = <?php echo "'{$_POST["tipo_usuario"]}'\n" ?>
            <?php if ( validar_post("nombre") ) ?> document.getElementById("nombre").value = <?php echo "'{$_POST["nombre"]}'\n" ?>
            <?php if ( validar_post("correo") ) ?> document.getElementById("correo").value = <?php echo "'{$_POST["correo"]}'\n" ?>
        <?php
        } else if ( isset($registrado) ) { ?>
        if ( confirm("El usuario se ha registrado éxitosamente.\n¿Volver a la página principal?") )
            window.location.href = "../"
        <?php } ?>
        document.currentScript.remove()
        </script>
    </body>
</html>
