<?php
header("Content-Type: text/html;charset=utf-8");
session_start();
require "../php/funciones.php";// or die ("ERROR FATAL. Favor de contactar a soporte");

if ( !validar_sesion() || $_SESSION["os_usr_nivel"] != "SuperAdmin" && $_SESSION["os_usr_nivel"] != "Inventario" && $_SESSION["os_usr_nivel"] != "Administrador" ) {
    header("Location: ../");
}
if (!validar_get("id")) {
    header("Location: ./consultar_inventario.php");
}
$id_producto = $_GET["id"];

require "../php/config_bd.php";

$conexion_db = mysqli_connect($_hostdb, $_usuariodb, $_contradb, $_nombredb);
mysqli_set_charset($conexion_db, "utf8");

if ( $conexion_db ) {
    if ( isset($_POST["modificar_producto"]) && $_POST["modificar_producto"] == "Modificar producto" ) {
        if ( validar_post("id_prod") && validar_post("codigo_barras") && validar_post("descripcion") && validar_post("precio") && validar_post("cantidad") ) {
            
            $consulta = "UPDATE productos set id_codigo = '{$_POST["codigo_barras"]}', descripcion = '{$_POST["descripcion"]}', precio = {$_POST["precio"]}, cantidad = {$_POST["cantidad"]} where id_registro = {$_POST["id_prod"]};";
            mysqli_query($conexion_db, $consulta);

            /*echo mysqli_info($conexion_db);
            echo "<br/>$consulta<br/>";*/
            
            if ( !mysqli_affected_rows($conexion_db) ) {
                $mensaje_err = "No se ha registrado la información en base de datos, posiblemente el código de barras se encuentra registrado en otro producto o algún dato no era correcto";
            } else $modificacion_correcta = true;

        } else {
            $mensaje_err = "No se ha obtenido toda la información para el producto";
        }
        
    } else if ( isset($_POST["agregar_cantidad"]) && $_POST["agregar_cantidad"] == "Añadir cantidad" ) {
        if ( validar_post("id_prod") && validar_post("cantidad_agregar") ) {
            
            $consulta = "UPDATE productos set cantidad = cantidad + {$_POST["cantidad_agregar"]} where id_registro = {$_POST["id_prod"]};";
            mysqli_query($conexion_db, $consulta);
            
            if ( !mysqli_affected_rows($conexion_db) ) {
                $mensaje_err = "No se ha registrado la información en base de datos, posiblemente algún dato no era correcto";
            } else $agregar_correcto = true;

        } else {
            $mensaje_err = "Los datos para la cantidad a añadir no son válidos o no están presentes";
        }
    }/* else {
        $mensaje_err = "No se ha recibido toda la información del producto, favor de llenar todos los campos o intentalo de nuevo";
    }*/

    
    $consulta = "select * from productos where id_registro = $id_producto;";
    
    $res = mysqli_query($conexion_db, $consulta);
    
    mysqli_close($conexion_db);
    
    if ( !($res->num_rows) ) {
        alerta_js("No se ha encontrado información sobre ese producto");
        consola_js("window.location.href = ./consultar_inventario.php");
    }
    $info = $res->fetch_array();

} else { 
    $mensaje_err = "Ha ocurrido un error al intentar conectarse con la base de datos, favor de intentar nuevamente";
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
        <title>Modificación de producto con ID: <?php echo $id_producto ?> - Offset Sale (OS)</title>
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
            //const producto_actual = new producto(< ?php echo $info["id_registro"] . ", " . $info["id_codigo"] . ", " . $info["descripcion"] . ", " . $info["cantidad"] . ", " . $info["precio"]  ? >)
            const producto_actual = new producto(<?php echo "{$info["id_registro"]}, '{$info["id_codigo"]}', \"{$info["descripcion"]}\", {$info["cantidad"]}, {$info["precio"]}"  ?>)
            Object.freeze(producto_actual)
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
            var cantidad_act = <?php echo $info["cantidad"] . "\n" ?>
            const actualizar_proxTotal = (el) => {
                var btn = document.getElementById("btn_agregar")
                if ( isNaN(el.value) || el.value <= 0 ) {
                    btn.setAttribute("disabled", "")
                } else {
                    document.getElementById("total_producto").innerHTML = "El nuevo total de la cantidad será: " + ( cantidad_act + parseInt(el.value) )
                    btn.removeAttribute("disabled")
                }
            }
            const validar_modificacion = () => {
                var info_prod = new producto(
                    producto_actual.id,
                    document.getElementById("inp_codb").value,
                    document.getElementById("inp_descr").value,
                    parseInt(document.getElementById("inp_cant").value),
                    parseInt(document.getElementById("inp_precio").value)
                )
                if ( !info_prod.validar() ) {
                    alert("La información respecto al producto no es válida")
                    return false
                }
                if ( producto_actual.comparar(info_prod) ) {
                    alert("Los datos ingresados son la misma información, no se ha modificado los datos")
                    return false
                }
                //alert("Los objetos son distintos")
                if ( info_prod.precio != producto_actual.precio ) {
                    if ( !confirm("Se ha detectado que se cambiará el precio del producto\n\nADVERTENCIA\nSi se está utilizando el carrito en este momento se conservará el precio actual en la compra (en un margen máximo de $10.00), " + 
                        "favor de verificar si se están realizando compras.\n¿Deseas continuar con la modificación?") )
                        return false
                }
                if ( info_prod.cantidad < producto_actual.cantidad ) {
                    alert("Se ha detectado que se asignará una cantidad menor de productos\n\nADVERTENCIA\nSi se está utilizando el carrito en este momento y la cantidad solicitada es mayor a la nueva, la compra en curso no podrá ser efectuada debido a la menor cantidad disponible.")
                }
                return true
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
                <a class="navbar-brand js-scroll-trigger" href="./consultar_inventario.php">Consultar</a>
                <a class="navbar-brand js-scroll-trigger">-></a>
                <a class="navbar-brand js-scroll-trigger" style="color: #1a1a1a;">Modificar producto</a>
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
                        <h2 class="text-center mt-0">Modificar producto</h2>
                        <p class="text-white-75 font-weight-light mb-5" id="usuario">Bienvenido: <?php echo "{$_SESSION["os_usuario"]} - {$_SESSION["os_usr_nivel"]}" ?></p>
                        <!--p class="text-white-75 font-weight-light mb-5">Dentro de esta página podrás utilizar las herramientas para el inventario.</p-->
                        <hr class="divider my-4" />
                        <div id="div_formu" style="display: flex;">
                            <form method="post" name="form_modificar" onsubmit="return validar_modificacion()" style="width:40%;">
                                <input type="text" name="id_prod" <?php echo "value=\"{$info["id_registro"]}\"" ?> hidden/>
                                ID producto: <input type="text" <?php echo "value=\"{$info["id_registro"]}\"" ?> disabled />
                                Código de barras: <input type="text" name="codigo_barras" id="inp_codb" placeholder="Código de barras" <?php echo "value=\"{$info["id_codigo"]}\"" ?> required />
                                <!--input type="text" name="descripcion" placeholder="Descripción" /-->
                                Descripción:
                                <textarea name="descripcion" id="inp_descr" placeholder="Descripción" required><?php echo $info["descripcion"] ?></textarea>
                                Precio: $<input type="number" min="0.1" step="0.01" name="precio" id="inp_precio" placeholder="Precio" <?php echo "value=\"{$info["precio"]}\"" ?> required />
                                Cantidad: <input type="number" min="1" name="cantidad" id="inp_cant" placeholder="cantidad" <?php echo "value=\"{$info["cantidad"]}\"" ?> required />
                                <input type="submit" name="modificar_producto" value="Modificar producto" />
                            </form>
                            <form method="post" name="agregar_cant" onsubmit="return validar_agregar()" style="width:40%;">
                                <input type="text" name="id_prod" <?php echo "value=\"{$info["id_registro"]}\"" ?> hidden/>
                                <p>Si solo deseas añadir más cantidad al inventario (sin alterar otra información) puedes hacerlo aquí: </p>
                                <!--Cantidad a añadir: <input type="number" name="cantidad_agregar" min="0" value="0" onchange="actualizar_proxTotal(this)" onkeypress="(e) => { var key = e.charCode || e.keyCode || 0; if ( key == 13 ) alert('Enter presionado'); return false; }" /-->
                                Cantidad a añadir: <input type="number" name="cantidad_agregar" min="0" value="0" onchange="actualizar_proxTotal(this)" />
                                <input type="submit" name="agregar_cantidad" id="btn_agregar" value="Añadir cantidad" disabled />
                                <p id="total_producto">El nuevo total de la cantidad será: <?php echo $info["cantidad"] ?></p>
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
            if ( isset($mensaje_err) ) { echo "alert(\"$mensaje_err\")\n"; }
            if ( isset($modificacion_correcta) )  { ?>
            if ( confirm("Los cambios fueron realizados exitosamente\n\n¿Regresar a la consulta de inventario?") )
                window.location.href = "./consultar_inventario.php"
            <?php
            }
            else if ( isset($agregar_correcto) )  {?>
            if ( confirm("Se agrego la cantidad al inventario exitosamente\n\n¿Regresar a la consulta de inventario?") )
                window.location.href = "./consultar_inventario.php"
            <?php
            }
            ?>
            document.currentScript.remove()
        </script>
    </body>
</html>
