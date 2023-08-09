<?php
session_start();
require "./php/funciones.php";// or die ("ERROR FATAL. Favor de contactar a soporte");

//if ( isset($_SESSION['usuario']) && $_SESSION['usuario'] != "" && isset($_SESSION['nivel']) && validar_nivel($_SESSION['nivel']) )
if ( validar_sesion() )
    $sesion_activa = true;
elseif ( isset($_POST['enviar']) ) {
    //if ( isset($_POST['usuario']) && $_POST['usuario'] != "" && isset($_POST['contra']) && $_POST['contra'] != "" ) {
    if ( validar_post("usuario") && validar_post("contra") ) {
        require "php/config_bd.php";
        $conexion_db = mysqli_connect($_hostdb, $_usuariodb, $_contradb, $_nombredb);
        if ($conexion_db != null) {
            //$mensaje = "Conexión éxitosa";
            //mysqli_query($conexion_db, "select * from usuarios");

            $consulta = "select usuario, nivel from usuarios where usuario='{$_POST['usuario']}' and contra=sha2('{$_POST['contra']}',256) and habilitado=true";
            $res = mysqli_query($conexion_db, $consulta);

            if ($res->num_rows < 1 || $res->num_rows >1) {
                $mensaje = "El usuario o la contraseña son erroneas o se encuentra inhabilitado\\n";
                //$mensaje .= $consulta;
            } else {
                $datos = $res->fetch_array();
                //alerta_js($datos);
                $_SESSION['os_usuario'] = $datos['usuario'];
                $_SESSION['os_usr_nivel'] = $datos['nivel'];
                $mensaje = "Bienvenido/a {$_SESSION['os_usuario']} - {$_SESSION['os_usr_nivel'] }";
                header("Location: ./index.php");
                exit();
            }

            mysqli_close($conexion_db);
        } else {
            $mensaje = "Ha ocurrido un error al intentarse conectar a la base de datos";
        }

    } else {
        $mensaje = "Ha ocurrido un error con la informacion, favor de intentarlo de nuevo";
    }
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
        <title>Inicio de Sesion - Offset Sale (OS)</title>
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
                background: linear-gradient(to bottom, rgba(73, 98, 119, 0.7) 0%, rgb(92 106 118) 100%)/*, url("./assets/img/bg-masthead.jpg") !important*/;
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

            #log{
            background-color: #98B5CD;
           // background-color:  #7c8c97;
            border-radius: 10px;
            margin: 10px;
            text-align: center;
            margin-left: 0px;
            margin-right: 0px;
            padding: 20px;
        }
        #presen{
            border-radius: 10px;
            margin-top: -10px;
        }
        </style>
        <script>
            var validar = () => {
                var resultado = validar_campo(document.getElementById("usuario"), 1, 20) && validar_campo(document.getElementById("contra"), 1, 20)
                if (!resultado) alert("Los datos no son válidos, favor de verificar")
                return resultado
            }
            var sesion_activa = () => {
                var pagina = "./cerrar_sesion.php"
                if ( !confirm("Ya se encuentra una sesión activa.\n¿Deseas iniciar como otro usuario (cerrar sesión)?") )
                    pagina = "./"
                window.location.href = pagina
            }
        </script>
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
            <div class="container">
                <!--a class="navbar-brand js-scroll-trigger" href="http://www.upslp.edu.mx">UPSLP</a-->
                <a class="navbar-brand js-scroll-trigger" href="../">Inicio de Sesion</a>
               
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto my-2 my-lg-0">
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" style="color: black;"><?php echo "Offset Sale (OS)" ?></a></li>
                        <!--li class="nav-item"><a class="nav-link js-scroll-trigger" < ?php if (usr_comp(["SuperAdmin", "Administrador", "Inventario"])) echo 'href="./Inventario/"'; else echo 'onclick="privilegios()"'; ?>>Inventario</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" < ?php if (usr_comp(["SuperAdmin", "Administrador", "Cajero"])) echo 'href="./Caja/"'; else echo 'onclick="privilegios()"'; ?>>Caja</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" < ?php if (usr_comp(["SuperAdmin"])) echo 'href="./Usuarios/"'; else echo 'onclick="privilegios()"'; ?>>Usuarios</a></li-->
                        
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Masthead-->
        <header class="masthead">
            <div class="container h-100">
                <div class="row h-100 align-items-center justify-content-center text-center">
                    <div class="container">
                       
                        <div id="presen">
                            <img src="img/name.PNG" alt="Nombre Del Software" id="presen">
                        </div>

                        <div id="log">
                            <form action="" method="post" onsubmit="return validar()">
                                <p>Para tener acceso al sitio, debes iniciar sesión primero.</p>
                                <h2 id="logtittle">Iniciar sesión</h2>
                                <label for="usuario">Usuario:  </label><input type="text" id="usuario" name="usuario" max="20" placeholder="Usuario" required /><p></p>
                                <label for="pass">Contraseña:  </label><input type="password" id="contra" name="contra" max="20" placeholder="Contraseña" required /><p></p>
                                <input type="submit" id="btn_enviar" name="enviar" value="Iniciar sesión" />
                            </form>
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
    </body>


    <?php
    if (isset($sesion_activa)) consola_js("sesion_activa()");
    if (isset($mensaje)) alerta_js($mensaje)
    ?>
</html>