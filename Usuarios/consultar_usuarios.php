<?php
    session_start();
    require "../php/funciones.php";
    if( !validar_sesion() || !usr_comp(["SuperAdmin"]) ){
        header("Location: ../");
        exit();
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
        <title>Consulta de usuarios - Offset Sale (OS)</title>
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
        <script>
            function cerrar_sesion() {
                if (confirm("¿Seguro/a de cerrar la sesión actual?"))
                    window.location.href = "../cerrar_sesion.php";
            }
            function privilegios() {
                alert("Lo sentimos, tu nivel de usuario no cuenta con los privilegios necesarios para administrar esta sección.")
            }
            var usuario_actual = "<?php echo $_SESSION["os_usuario"] ?>"
        </script>
        <script src="./js/func_usuarios.js"></script>
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
                <a class="navbar-brand js-scroll-trigger" style="color: #1a1a1a;">Consulta</a>
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
                        <h2 class="text-center mt-0">Consulta de usuarios</h2>
                        <p class="text-white-75 font-weight-light mb-5" id="usuario">Bienvenido: <?php echo "{$_SESSION["os_usuario"]} - {$_SESSION["os_usr_nivel"]}" ?></p>
                        <!--p class="text-white-75 font-weight-light mb-5">Dentro de esta página podrás utilizar las herramientas para el inventario.</p-->
                        <hr class="divider my-4" />
                        <div>
                        <!--a href="./generar_csv.php" target="_BLANK"><button>Archivo csv con todos los registros</button></a>
                        <br /-->
                        <p class="text-white-75 font-weight-light mb-5" style="margin-top:15px;">Ordenar el contenido de la tabla</p>
                        <form name="orden" onsubmit="return ordenar();">
                            <select name="txt_orden" id="id_orden">
                                <option value="0">ID</option>
                                <option value="1">Usuario</option>
                                <option value="2">Nombre</option>
                                <option value="3">Correo</option>
                                <option value="4">Nivel</option>
                                <option value="5">Habilitado</option>
                            </select>
                            <select name="modo_orden" id="id_modo">
                                <option value="0">Ascendente</option>
                                <option value="1">Descendente</option>
                            </select>
                            <input type="submit" name="btn_ordenar" value="Ordenar" />
                        </form>
                        <p class="text-white-75 font-weight-light mb-5" style="margin-top:15px;">Filtrar el contenido de la tabla</p>
                        <form name="filtros" onsubmit="return filtrar()">
                            <input type="number" placeholder="ID" name="filtro_id" id="filtro_id" />
                            <input type="text" placeholder="Usuario" name="filtro_usuario" id="filtro_usuario" />
                            <input type="text" placeholder="Nombre" name="filtro_nombre" id="filtro_nombre" />
                            <input type="text" placeholder="Correo" name="filtro_correo" id="filtro_correo" />
                            <select name="filtro_nivel" id="filtro_nivel" >
                                <option value="">- Selecciona un nivel</option>
                                <option>SuperAdmin</option>
                                <option>Administrador</option>
                                <option>Caja</option>
                                <option>Inventario</option>
                            </select>
                            <select name="filtro_habilitado" id="filtro_habilitado">
                                <option value="">- ¿Habilitado? </option>
                                <option value="1">Si</option>
                                <option value="0">No</option>
                            </select>
                            <input type="submit" value="Filtrar" />
                            <input type="reset" value="Limpiar filtros" onclick="setTimeout('filtrar()', 50)" />
                        </form>
                        <br />
                    </div>
                    <div id="secc_registro" style="overflow-y: scroll;overflow-x: hidden;height: 50vh;margin: auto;align-content: center;padding: 30px;text-align: center;">
                        <table id="tabla_usuarios" style="margin: auto;padding: 10px;background-color: #fff;" cellpadding="5"></table>
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
        <?php
        require "../php/config_bd.php";
        $conexion_db = mysqli_connect($_hostdb, $_usuariodb, $_contradb, $_nombredb);

        if ($conexion_db) {
            mysqli_set_charset($conexion_db, "utf8");
            $consulta = "SELECT * from usuarios";
            /*if ( validar_post("txt_orden") && validar_post("modo_orden") ) {
                $consulta .= " order by {$_POST['txt_orden']} {$_POST['modo_orden']}";
            }*/

            $filas = mysqli_query($conexion_db, $consulta);

            if ($filas->num_rows) {
                echo "<script>\n";
                while ( $fila = $filas->fetch_array() ) {
                    echo "agregar_usuario({$fila['id']}, '{$fila['usuario']}', {$fila['habilitado']}, '{$fila['nombre']}', '{$fila['correo']}', '{$fila['nivel']}')\n";
                }
                echo "usuarios_mostrar = usuarios\n";
                echo "actualizar_tabla()\n";
                echo "document.currentScript.remove()\n";
                echo "</script>";
            }
            mysqli_close($conexion_db);
        } else {
            echo "<p>Ha ocurrido un error al intentar conectarse a la base de datos</p>";
        }
        ?>
    </body>
</html>
