<?php
session_start();
require "../php/funciones.php";

//echo "<br />" . sha1("os_clave_maestra") . "<br />";

function ejecucion() {
    
// os_clave_maestra sha1
$clave_maestra = "46c106ceed22532befa148a4b72a6a1a4d05c8ba";

if ( validar_post("enviar_mod") ) {

    if ( $_SESSION["os_usr_nivel"] == "SuperAdmin" && validar_get("id") && validar_post("contra_m") ) {
        if ( $_POST["contra_m"] != $clave_maestra ) {
            //La clave maestra NO es correcta
            return ["La clave maestra ingresada no es correcta", 1];
        } else if ( validar_post("id_usr" )) {
            $id_mod = $_POST["id_usr"];
        } else {
            return ["No hay información sobre a cual usuario se cambiará la contraseña", 1];
        }
    }

    if ( validar_post("contra_actual") && validar_post("n_contra")  && validar_post("n_contra_1") && $_POST["n_contra"] == $_POST["n_contra_1"] ) {
        require "../php/config_bd.php";
        
        $conexion_db = mysqli_connect($_hostdb, $_usuariodb, $_contradb, $_nombredb);

        if ( !$conexion_db ) {
            return ["Ha ocurrido un error al intentar conectarse a base de datos", 1];
        }

        $consulta = "SELECT usuario from usuarios where usuario='{$_SESSION["os_usuario"]}' and contra=sha2('{$_POST["contra_actual"]}',256)";

        $res = mysqli_query($conexion_db, $consulta);

        if ( !($res->num_rows) ) {
            mysqli_close($conexion_db);
            return ["La contraseña actual no corresponde", 1];
        }

        $consulta = "UPDATE usuarios set contra=sha2('{$_POST["n_contra"]}',256) where " . ( isset($id_mod)? "id=$id_mod" : "usuario='{$_SESSION["os_usuario"]}'" );

        /*if ( isset($id_mod) ) {
            $consulta = "UPDATE usuarios set contra=sha2('{$_POST["n_contra"]}',256) where id=$id_mod";
        } else {
            $consulta = "UPDATE usuarios set contra=sha2('{$_POST["n_contra"]}',256) where usuario={$_SESSION["os_usuario"]}";
        }*/

        mysqli_query($conexion_db, $consulta);

        if ( !mysqli_affected_rows($conexion_db) ) {
            mysqli_close($conexion_db);
            return ["Ha ocurrido un error, la contraseña no se ha cambiado", 1];
        } else {
            mysqli_close($conexion_db);
            return ["", 0];
        }
    }
    return ["Error desconocido", 1];
}
return ["", 2];
}

$resultados = ejecucion();

if ( $resultados[1] == 0 ) $mod_correcta = true;
else if ( $resultados[1] != 2 ) $mensaje_err = $resultados[0];

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
                /*if ( !validar_campo(document.getElementById("contra"), 1, 20) ) {
                    campos += "- La contraseña no cumple los requerimientos\n"
                    resultado = false
                }
                if ( document.getElementById("contra").value != document.getElementById("contra_2").value ) {
                    campos += "- Las contraseñas no coinciden\n"
                    resultado = false
                }*/
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
                if ( document.getElementById("tipo_usuario").value == "SuperAdmin" && !confirm("El tipo de usuario a asignar es SuperAdmin, este tipo tiene total control sobre el sistema.\nADVERTENCIA: No se puede regresar a otro tipo después de esto. ¿Quieres continuar?")  )
                    return false
                return resultado
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
                <?php if ( $_SESSION["os_usr_nivel"] ==  "SuperAdmin" ) { ?>
                <a class="navbar-brand js-scroll-trigger" href="./">Usuarios</a>
                <a class="navbar-brand js-scroll-trigger">-></a>
                <a class="navbar-brand js-scroll-trigger" href="./consultar_usuarios.php">Consulta</a>
                <a class="navbar-brand js-scroll-trigger">-></a>
                <?php } ?>
                <a class="navbar-brand js-scroll-trigger" style="color: #1a1a1a;">Modificar contraseña</a>
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
                        <h2 class="text-center mt-0">Modificación de contraseña</h2>
                        <p class="text-white-75 font-weight-light mb-5" id="usuario">Bienvenido: <?php echo "{$_SESSION["os_usuario"]} - {$_SESSION["os_usr_nivel"]}" ?></p>
                        <!--p class="text-white-75 font-weight-light mb-5">Dentro de esta página podrás utilizar las herramientas para el inventario.</p-->
                        <hr class="divider my-4" />
                        <div id="div_formu">
                            <form name="form_modificar" method="post">
                                <label for="contra_acual">Ingresa tu contraseña: </label>
                                <input type="password" name="contra_actual" id="contra_actual" placeholder="Contraseña actual" required />
                                <br />
                                <label for="contra_acual">Ingresa la nueva contraseña: </label>
                                <input type="password" name="n_contra" id="n_contra" placeholder="Contraseña nueva" required />
                                <br />
                                <label for="contra_acual">Repite la nueva contraseña: </label>
                                <input type="password" name="n_contra_1" id="n_contra_1" placeholder="Repetir contraseña nueva" required />
                                <?php
                                if ( $_SESSION["os_usr_nivel"] == "SuperAdmin" && validar_get("id") ) {
                                    ?>
                                    <p>
                                        Modificación de contraseña para usuario con ID: <?php echo $_GET["id"] ?>
                                        <input type="number" name="id_usr" id="id_usr" value="<?php echo $_GET["id"] ?>" hidden required />
                                        <br />
                                        Para modificar una contraseña de un usuario que no te pertenece, necesitarás la clave maestra.
                                        <br />
                                        La clave maestra se encuentra en la documentación del sistema, recomendamos copiar y pegar desde el documento virtual.
                                    </p>
                                    <input type="password" name="contra_m" id="contra_m" placeholder="Clave maestra" required />
                                    <br />
                                <?php
                                }
                                ?>
                                <br />
                                <input type="submit" name="enviar_mod" value="Enviar cambios" />
                                <input type="reset" name="limpiar" value="Limpiar formulario" />
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
            <?php if ( isset($mod_correcta) ) { ?>
            if ( confirm("La modificación de la contraseña se ha realizado éxitosamente.\n¿Deseas regresar a la consulta de usuarios?") )
                window.location.href = "./consultar_usuarios.php"
            <?php } else if ( isset($mensaje_err) ) { ?>
            alert("<?php echo $mensaje_err ?>")
            <?php } ?>
            document.currentScript.remove()
            </script>
    </body>
</html>
