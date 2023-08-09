<?php
    session_start();
    require "./php/funciones.php";
    if( !validar_sesion() ){
        header("Location: ./iniciar_sesion.php");
        exit();
    }
    $usr_inventario = array("SuperAdmin", "Administrador", "Inventario");
    $usr_caja = array("SuperAdmin", "Administrador", "Cajero");
    $usr_usr = array("SuperAdmin");
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <!--title>Creative - Start Bootstrap Theme</title-->
        <title>Menu de inicio del sistema - Offset Sale (OS)</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="./img/logo.png" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v5.13.0/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
        <!-- Third party plugin CSS-->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
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
                /* rgb(92 106 118) 100%) */
                background: linear-gradient(to bottom, rgba(73, 98, 119, 0.7) 0%, rgba(73, 98, 119, 0.7) 100%)/*, url("./assets/img/bg-masthead.jpg") !important*/;
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
        <script>
            function cerrar_sesion() {
                if (confirm("¿Seguro/a de cerrar la sesión actual?"))
                    window.location.href = "./cerrar_sesion.php";
            }
            function privilegios() {
                alert("Lo sentimos, tu nivel de usuario no cuenta con los privilegios necesarios para administrar esta sección.")
            }
        </script>
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
            <div class="container">
                <!--a class="navbar-brand js-scroll-trigger" href="http://www.upslp.edu.mx">UPSLP</a-->
                <a class="navbar-brand js-scroll-trigger" style="color: #1a1a1a;">Inicio</a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto my-2 my-lg-0">
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" style="color: black;"><?php echo "{$_SESSION["os_usuario"]} ({$_SESSION["os_usr_nivel"]})" ?></a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" <?php if (usr_comp($usr_inventario)) echo 'href="./Inventario/"'; else echo 'onclick="privilegios()"'; ?>>Inventario</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" <?php if (usr_comp($usr_caja)) echo 'href="./Caja/"'; else echo 'onclick="privilegios()"'; ?>>Caja</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" <?php if (usr_comp($usr_usr)) echo 'href="./Usuarios/"'; else echo 'onclick="privilegios()"'; ?>>Usuarios</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="./Usuarios/modificar_contra.php">Cambiar contraseña</a></li>
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
                        <h2 class="text-center mt-0">Gestión del sitio</h2>
                        <p class="text-white-75 font-weight-light mb-5" id="usuario">Bienvenido: <?php echo "{$_SESSION["os_usuario"]} - {$_SESSION["os_usr_nivel"]}" ?></p>
                        <p class="text-white-75 font-weight-light mb-5">Dentro de esta página podrás ir a las otras secciones del sistema</p>
                        <hr class="divider my-4" />
                        <div class="row">
                            <a class="col-lg-3 col-md-6 text-center" <?php if (usr_comp(["SuperAdmin", "Administrador", "Inventario"])) echo 'href="./Inventario/"'?>>
                                <div class="mt-5<?php if (!usr_comp(["SuperAdmin", "Administrador", "Inventario"])) echo " bloqueado"?>">
                                    <!--i class="fas fa-4x fa-user-edit text-primary mb-4"></i-->
                                    <!--i class="fas fa-4x fa-boxes-stacked text-primary mb-4"></i-->
                                    <i class="fas fa-4x fa-solid fa-boxes text-primary mb-4"></i>
                                    <h3 class="h4 mb-2">Inventario</h3>
                                    <p class="text-muted mb-0">Ir a la gestión del inventario.</p>
                                </div>
                            </a>
                            <a class="col-lg-3 col-md-6 text-center" <?php if (usr_comp(["SuperAdmin", "Administrador", "Cajero"])) echo 'href="./Caja/"'?>>
                                <div class="mt-5<?php if (!usr_comp(["SuperAdmin", "Administrador", "Cajero"])) echo " bloqueado"?>">
                                    <!--i class="fas fa-4x fa-chalkboard-teacher text-primary mb-4"></i-->
                                    <i class="fas fa-4x fa-cash-register text-primary mb-4"></i>
                                    <h3 class="h4 mb-2">Caja</h3>
                                    <p class="text-muted mb-0">Realizar/consultar ventas y cortes.</p>
                                </div>
                            </a>
                            <a class="col-lg-3 col-md-6 text-center" <?php if (usr_comp(["SuperAdmin"])) echo 'href="./Usuarios/"'?>>
                                <div class="mt-5<?php if (!usr_comp(["SuperAdmin"])) echo " bloqueado"?>">
                                    <i class="fas fa-4x fa-users text-primary mb-4"></i>
                                    <h3 class="h4 mb-2">Usuarios</h3>
                                    <p class="text-muted mb-0">Ir a la gestión de usuarios.</p>
                                </div>
                            </a>
                            <a class="col-lg-3 col-md-6 text-center" onclick="cerrar_sesion()">
                                <div class="mt-5">
                                    <!--i class="fas fa-4x fa-book text-primary mb-4"></i-->
                                    <i class="fas fa-4x fa-sign-out-alt text-primary mb-4"></i>
                                    <h3 class="h4 mb-2">Cerrar sesión</h3>
                                    <p class="text-muted mb-0">Click aquí para salir del sistema.</p>
                                </div>
                            </a>
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
        <script src="js/scripts.js"></script>
        <script src="https://use.fontawesome.com/2b56ecae06.js"></script>
    </body>
</html>
