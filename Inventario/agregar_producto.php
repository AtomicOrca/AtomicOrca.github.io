<?php
header("Content-Type: text/html;charset=utf-8");
session_start();
require "../php/funciones.php";// or die ("ERROR FATAL. Favor de contactar a soporte");

//if ( !validar_sesion() || $_SESSION["os_usr_nivel"] != "SuperAdmin" && $_SESSION["os_usr_nivel"] != "Inventario" && $_SESSION["os_usr_nivel"] != "Administrador" ) {
if ( !validar_sesion() || !usr_comp(["SuperAdmin", "Inventario", "Administrador"]) ) {
    header("Location: ../");
}

if ( isset($_POST["registrar_producto"]) && $_POST["registrar_producto"] == "Ingresar producto a almacen" ) {
    if ( validar_post("codigo_barras") && validar_post("descripcion") && validar_post("precio") && validar_post("cantidad") ) {
        require "../php/config_bd.php";
        $conexion_db = mysqli_connect($_hostdb, $_usuariodb, $_contradb, $_nombredb);

        if ( $conexion_db ) {
            $consulta = "INSERT into productos (id_codigo, descripcion, precio, cantidad, usuario) values ('{$_POST["codigo_barras"]}', '{$_POST["descripcion"]}', {$_POST["precio"]}, {$_POST["cantidad"]}, '{$_SESSION["os_usuario"]}');";
            mysqli_query($conexion_db, $consulta);

            if ( !mysqli_affected_rows($conexion_db) ) {
                $mensaje_err = "No se ha registrado la información en base de datos, posiblemente el código de barras se encuentra registrado en otro producto o algún dato no era correcto";
            } else {
                header("Location: ./agregar_producto.php?registro_correcto");
            }
            mysqli_close($conexion_db);
        } else {
            $mensaje_err = "Ha ocurrido un error al intentar conectarse con la base de datos, favor de intentar nuevamente";
        }

    } else {
        $mensaje_err = "No se ha recibido toda la información del producto, favor de llenar todos los campos o intentalo de nuevo";
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
        </style>
        <link href="./styleForm.css" rel="stylesheet" />
        <script>
            class producto {
                constructor(id, id_codigo, descripcion, cantidad, precio) {
                    this.id = id
                    this.id_codigo = id_codigo
                    this.descripcion = descripcion
                    this.cantidad = cantidad
                    this.precio = precio
                }
                comparar = (prod) => {
                    if ( prod.constructor.name != "producto" ) return false
                    if ( this.id != prod.id ) return false
                    if ( this.id_codigo != prod.id_codigo ) return false
                    if ( this.descripcion != prod.descripcion ) return false
                    if ( this.cantidad != prod.cantidad ) return false
                    if ( this.precio != prod.precio ) return false
                    return true
                }
                validar = () => {
                    if ( isNaN(this.id) ) return false
                    if ( isNaN(this.cantidad) || this.cantidad <= 0 ) return false
                    if ( isNaN(this.precio) || this.precio <= 0 ) return false
                    return true
                }
            }
            document.currentScript.remove()
        </script>
        <script>
            function cerrar_sesion() {
                if (confirm("¿Seguro/a de cerrar la sesión actual?"))
                    window.location.href = "../cerrar_sesion.php";
            }
            function privilegios() {
                alert("Lo sentimos, tu nivel de usuario no cuenta con los privilegios necesarios para administrar esta sección.")
            }
            const validar_registro = () => {
                producto_eval = new producto(
                    0,
                    document.getElementById("inp_codb").value,
                    document.getElementById("inp_descr").value,
                    document.getElementById("inp_cant").value,
                    document.getElementById("inp_precio").value
                )
                if ( !producto_eval.validar() ) {
                    alert("La cantidad o el precio no son valores válidos")
                    return false
                }
                if ( producto_eval.descripcion.length <= 5 ) {
                    if ( !confirm("La longitud de la descripción es muy corta, ¿Deseas guardarlo de todos modos?") )
                        return false
                }
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
                <a class="navbar-brand js-scroll-trigger" href="./">Inventario</a>
                <a class="navbar-brand js-scroll-trigger">-></a>
                <a class="navbar-brand js-scroll-trigger" style="color: #1a1a1a;">Agregar producto</a>
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
                        <h2 class="text-center mt-0">Agregar producto a inventario</h2>
                        <p class="text-white-75 font-weight-light mb-5" id="usuario">Bienvenido: <?php echo "{$_SESSION["os_usuario"]} - {$_SESSION["os_usr_nivel"]}" ?></p>
                        <!--p class="text-white-75 font-weight-light mb-5">Dentro de esta página podrás utilizar las herramientas para el inventario.</p-->
                        <hr class="divider my-4" />
                        <div id="div_formu">
                            <form method="post" name="form_modificar" onsubmit="return validar_registro()" >
                                <!--ID producto: <input type="text" < ?php echo "value=\"{$info["id_registro"]}\"" ?> disabled />
                                <br />
                                <br /-->
                                <label>Código de barras</label>
                                <input type="text" name="codigo_barras" id="inp_codb" placeholder="Código de barras" required />
                                <!--input type="text" name="descripcion" placeholder="Descripción" /-->
                                <label>Descripción</label>
                                <textarea name="descripcion" id="inp_descr" placeholder="Descripción" required></textarea>
                                Precio:<input type="number" min="0.1" step="0.1" name="precio" id="inp_precio" placeholder="Precio" required />
                                Cantidad: <input type="number" min="1" name="cantidad" id="inp_cant" placeholder="Cantidad" required />
                                <input type="submit" name="registrar_producto" value="Ingresar producto a almacen" />
                                <input type="reset" name="reset_form" value="Limpiar formulario" />
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
            if ( isset($mensaje_err) ) {
                echo "alert('$mensaje_err')\n";
                if ( isset($_POST["codigo_barras"]) ) echo "document.getElementById('inp_codb').value = '{$_POST["codigo_barras"]}'\n";
                if ( isset($_POST["descripcion"]) ) echo "document.getElementById('inp_descr').value = '{$_POST["descripcion"]}'\n";
                if ( isset($_POST["precio"]) ) echo "document.getElementById('inp_precio').value = {$_POST["precio"]}\n";
                if ( isset($_POST["cantidad"]) ) echo "document.getElementById('inp_cant').value = {$_POST["cantidad"]}\n";
            } elseif ( isset($_GET["registro_correcto"]) ) {
                echo "alert(\"Se ha registrado exitosamente el producto\")\n";
            }
            ?>
            document.currentScript.remove()
        </script>
    </body>
</html>
