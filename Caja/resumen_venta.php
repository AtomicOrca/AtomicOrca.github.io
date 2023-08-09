<?php
    session_start();
    require "../php/funciones.php";
    if( !validar_sesion() || !usr_comp(["SuperAdmin", "Administrador", "Cajero"]) ){
        header("Location: ../");
        exit();
    }
    if (!validar_get("id")) {
        header("Location: ../");
        exit();
    }
    $id_compra = $_GET["id"];
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <!--title>Creative - Start Bootstrap Theme</title-->
        <title>Resumen de compra con ID: <?php echo $id_compra; ?> - Offset Sale (OS)</title>
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
                background: linear-gradient(to bottom, rgb(92 106 118) 100%, rgba(73, 98, 119, 0.7) 0%)/*, url("./assets/img/bg-masthead.jpg") !important*/;
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
        </script>
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
            <div class="container">
                <!--a class="navbar-brand js-scroll-trigger" href="http://www.upslp.edu.mx">UPSLP</a-->
                <a class="navbar-brand js-scroll-trigger" href="../">Inicio</a>
                <a class="navbar-brand js-scroll-trigger">-></a>
                <a class="navbar-brand js-scroll-trigger" href="./">Caja</a>
                <a class="navbar-brand js-scroll-trigger">-></a>
                <a class="navbar-brand js-scroll-trigger" href="./consultar_ventas.php">Consulta de ventas</a>
                <a class="navbar-brand js-scroll-trigger">-></a>
                <a class="navbar-brand js-scroll-trigger" style="color: #1a1a1a;">Resumen (ID: <?php echo $id_compra; ?>)</a>
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
                        <h2 class="text-center mt-0">Resumen de venta (ID: <?php echo $id_compra; ?>)</h2>
                        <?php if ( isset($_GET["postcompra"]) ) { echo "<p>Compra realizada correctamente</p>"; } ?>
                        <p class="text-white-75 font-weight-light mb-5" id="usuario">Bienvenido: <?php echo "{$_SESSION["os_usuario"]} - {$_SESSION["os_usr_nivel"]}" ?></p>
                        <!--p class="text-white-75 font-weight-light mb-5">Dentro de esta página podrás utilizar las herramientas para el inventario.</p-->
                        <hr class="divider my-4" />
                        <div></div>
                        <!--a href="./generar_csv.php" target="_BLANK"><button>Archivo csv con todos los registros</button></a>
                        <br /-->
                        <div id="secc_registro" style="overflow: scroll;height: 60vh;margin: auto;align-content: center;padding: 30px;text-align: center;color: #fff;">
                        <?php
                        require "../php/config_bd.php";
                        $conexion_db = mysqli_connect($_hostdb, $_usuariodb, $_contradb, $_nombredb);
                        if ($conexion_db) {
                            mysqli_set_charset($conexion_db, "utf8");
                            $consulta = "select * from compras where id_compra = $id_compra";
                            $res = mysqli_query($conexion_db, $consulta);
                            
                            if ( $res->num_rows ) {
                                $r = $res->fetch_array();
                                $fh = date("d/m/Y H:i:s", strtotime($r["fecha_hora"]) );
                                echo "<p>Fecha y hora: $fh</p>";
                                echo "<p>Total de compra: $ " . number_format($r["total"], 2) . "</p>";
                                if ( validar_get("cambio") ) {
                                    echo "<p>Dinero ingresado: $ " . number_format($r["total"] + $_GET["cambio"], 2) . "</p>";
                                    echo "<p>Cambio a entregar: $ " . number_format($_GET["cambio"], 2) . "</p>";
                                }
                                if ( !isset($_GET["postcompra"]) ) echo "<p>¿Venta devuelta?: " . ($r["devuelto"]? "Sí" : "No") . "</p>";
                                echo "<p>Usuario que realizó la venta: {$r["usuario"]}</p>";

                                echo "<table id='productos_compra' style='margin: auto;padding: 10px;background-color: #fff;color: #000;' cellpadding='5'>";
                                echo "<tbody>";
                                echo "<tr><th>ID producto</th><th>Código</th><th>Descripción</th><th>Precio actual</th><th>Precio</th><th>Cantidad</th><th>Subtotal</th></tr>";

                                $consulta = "select p.id_registro, p.id_codigo, p.descripcion, p.precio, r.precio_venta, r.cantidad, (p.precio * r.cantidad) subtotal from productos p, compras q, productos_compras r where p.id_registro = r.id_producto and q.id_compra = r.id_compra and q.id_compra = $id_compra;";
                                //echo $consulta;

                                $res = mysqli_query($conexion_db, $consulta);

                                if ( $res->num_rows ) {
                                    while ( $fila = $res->fetch_array() ) {
                                        echo "<tr>";
                                        echo "<td>{$fila['id_registro']}</td>";
                                        echo "<td>{$fila['id_codigo']}</td>";
                                        echo "<td>{$fila['descripcion']}</td>";
                                        echo "<td>$ " . number_format($fila['precio'], 2) . "</td>";
                                        echo "<td>$ " . number_format($fila['precio_venta'], 2) . "</td>";
                                        echo "<td>{$fila['cantidad']}</td>";
                                        $subtotal = $fila['cantidad'] * $fila['precio_venta'];
                                        echo "<td>$ " . number_format($subtotal, 2) . "</td>";
                                        echo "</tr>";
                                    }
                                    echo "<tr><td colspan=7 style='text-align: right;'>Total: $" . number_format($r["total"], 2) ."</td></tr>";
                                } else {
                                    echo "<tr><td colspan=7>Error al verificar los productos</td></tr>";
                                }

                                echo "</tbody></table>";
                            } else {
                                echo "<p>No se ha encontrado una compra con ese ID en base de datos.</p>";
                            }
                            mysqli_close($conexion_db);
                        } else {
                            echo "<p>No se pudo establecer conexión con la base de datos.<br/>No se ha podido obtener la información.</p>";
                        }
                        if (isset($_GET["postcompra"])) echo "<button id='btn_regresar' onclick=\"window.location.href = './carrito.php' \">Regresar al carrito</button>";
                        //else echo "<button onclick=\"window.history.back()\">Regresar</button>";
                        else echo "<button onclick=\"window.location.href = './consultar_ventas.php'\">Regresar</button>";
                        ?>
                            <!--table id="tabla_ventas" style="margin: auto;padding: 10px;background-color: #fff;" cellpadding="5"></table-->
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
</html>
