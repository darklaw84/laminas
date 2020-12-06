<?php

session_start();
include_once './controllers/AdministradorController.php';
if (!isset($_SESSION['idUsr'])) {

    echo "<script>window.setTimeout(function() { window.location = 'login.php' }, 10);</script>";
} else {
    if ($_SESSION['idUsr'] == "") {
        echo "<script>window.setTimeout(function() { window.location = 'login.php' }, 10);</script>";
    }
}

include_once './controllers/CatalogosController.php';

$catCon = new CatalogosController();


$contAd = new AdministradorController();
$resTC = $contAd->obtenerValorParametro("tc");
$tc = $resTC->valor;
?>

<!doctype html>
<html lang="en">

<head>
    <link rel="icon" href="./imagenes/logo.png" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Láminas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />

    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no">


    <link href="./main.87c0748b313a1dda75f5.css" rel="stylesheet">
    <script src="./js/jquery.js"></script>
    <script src="./js/bootstrap.js"></script>
    <script type="text/javascript" src="./assets/scripts/main.87c0748b313a1dda75f5.js"></script>


    <script src="./js/administradores.js"></script>
    <script src="./js/cotizaciones.js"></script>
    <script src="./js/ordenes.js"></script>
    <script src="./js/producciones.js"></script>
    <script src="./js/recibirMateria.js"></script>
    <script src="./js/salidas.js"></script>

    <script src="./js/dataTables.js"></script>


</head>

<body>

    <div id="modalMensaje" class="modal fade mensajeError" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Mensaje</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>
    <div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
        <!--Header START-->

        <div class="app-header header-shadow">
            <div class="app-header__logo">
                <div class=""><img src="./imagenes/logo.png" style="height: 30px"></div>
                <div class="header__pane ml-auto">
                    <div>
                        <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>

                    </div>
                </div>
            </div>
            <div class="app-header__mobile-menu">
                <div>
                    <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="app-header__menu">
                <span>
                    <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                        <span class="btn-icon-wrapper">
                            <i class="fa fa-ellipsis-v fa-w-6"></i>
                        </span>
                    </button>
                </span>
            </div>
            <div class="app-header__content">

                <div class="app-header-right">
                    <div class="header-dots">



                        <div class="dropdown">
                            <button type="button" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false" class="p-0 btn btn-link dd-chart-btn">
                                <span class="icon-wrapper icon-wrapper-alt rounded-circle">
                                    <span class="icon-wrapper-bg bg-success"></span>
                                    <i class="pe-7s-user bg"></i>
                                </span>
                            </button>
                            <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-xl rm-pointers dropdown-menu dropdown-menu-right">
                                <div class="app-sidebar__inner">
                                    <ul class="vertical-nav-menu">

                                        <li>
                                            <a href="logout.php">
                                                <i class="metismenu-icon pe-7s-right-arrow">
                                                </i>Log out
                                            </a>
                                        </li>
                                        <li>
                                            <a href="index.php?p=cambiarpass">
                                                <i class="metismenu-icon pe-7s-key">
                                                </i>Cambiar Contraseña
                                            </a>
                                        </li>

                                    </ul>
                                </div>


                            </div>
                        </div>
                        <div class="header-btn-lg pr-0">

                            <div class="widget-content p-0">
                                <div class="widget-content-wrapper">
                                    <div class="widget-content p-0">
                                        <div class="widget-content-wrapper">

                                            <div class="widget-content-left">
                                                <div class="widget-heading"><?php
                                                                            if (isset($_SESSION['nombreUsr'])) {
                                                                                echo $_SESSION['nombreUsr'];
                                                                            }

                                                                            ?>
                                                </div>
                                                <div class="widget-subheading opacity-8 align-items-center">

                                                    <?php

                                                    if (isset($_SESSION['organizacionUsr'])) {
                                                        echo $_SESSION['organizacionUsr'];
                                                    } ?>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </div>
        <!--Header END-->

        <div class="app-main">
            <div class="app-sidebar sidebar-shadow">
                <div class="app-header__logo">
                    <div class="logo-src"></div>
                    <div class="header__pane ml-auto">
                        <div>
                            <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="app-header__mobile-menu">
                    <div>
                        <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
                <div class="app-header__menu">
                    <span>
                        <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                            <span class="btn-icon-wrapper">
                                <i class="fa fa-ellipsis-v fa-w-6"></i>
                            </span>
                        </button>
                    </span>
                </div>
                <div class="scrollbar-sidebar">
                    <div class="app-sidebar__inner">
                        <ul class="vertical-nav-menu">
                            <li class="app-sidebar__heading">Menu</li>

                            <?php if (
                                $_SESSION['usuarios'] == "1" || $_SESSION['clientes'] == "1"
                                || $_SESSION['proveedores'] == "1" || $_SESSION['calibres'] == "1" ||
                                $_SESSION['tipos'] == "1"
                            ) { ?>

                                <li>
                                    <a href="#">
                                        <i class=" pe-7s-copy-file metismenu-icon"></i>
                                        Catálogos
                                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                    </a>
                                    <ul>
                                        <?php if ($_SESSION['usuarios'] == "1") { ?>

                                            <li>
                                                <a href="index.php?p=administradores">
                                                    <i class="lnr-user metismenu-icon">
                                                    </i>Usuarios
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($_SESSION['clientes'] == "1") { ?>
                                            <li>
                                                <a href="index.php?p=clientes">
                                                    <i class="pe-7s-users metismenu-icon">
                                                    </i>Clientes
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($_SESSION['proveedores'] == "1") { ?>
                                            <li>
                                                <a href="index.php?p=proveedores">
                                                    <i class="pe-7s-users metismenu-icon">
                                                    </i>Proveedores
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($_SESSION['calibres'] == "1") { ?>
                                            <li>
                                                <a href="index.php?p=calibres">
                                                    <i class="lnr-page-break metismenu-icon">
                                                    </i>Calibres
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <?php if ($_SESSION['tipos'] == "1") { ?>
                                            <li>
                                                <a href="index.php?p=tipos">
                                                    <i class="pe-7s-paperclip metismenu-icon">
                                                    </i>Materiales
                                                </a>
                                            </li>
                                        <?php } ?>

                                        <?php if ($_SESSION['tipos'] == "1") { ?>
                                            <li>
                                                <a href="index.php?p=anchos">
                                                    <i class="pe-7s-paperclip metismenu-icon">
                                                    </i>Anchos
                                                </a>
                                            </li>
                                        <?php } ?>

                                        <?php if ($_SESSION['usuarios'] == "1") { ?>
                                            <li>
                                                <a href="index.php?p=camiones">
                                                    <i class="pe-7s-car metismenu-icon">
                                                    </i>Unidades
                                                </a>
                                            </li>
                                        <?php } ?>


                                        <?php if ($_SESSION['usuarios'] == "1") { ?>
                                            <li>
                                                <a href="index.php?p=choferes">
                                                    <i class="pe-7s-smile metismenu-icon">
                                                    </i>Choferes
                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ul>

                                </li>

                            <?php } ?>

                            <?php if ($_SESSION['ordCompra'] == "1") { ?>
                                <li>
                                    <a href="index.php?p=ordenescompra">
                                        <i class="pe-7s-wallet metismenu-icon">
                                        </i>Órdenes Compra
                                    </a>
                                </li>
                            <?php } ?>

                            <?php if ($_SESSION['recMat'] == "1") { ?>
                                <li>
                                    <a href="index.php?p=recMat">
                                        <i class="pe-7s-note2 metismenu-icon">
                                        </i>Recibir Material
                                    </a>
                                </li>
                            <?php } ?>

                            <li>
                                <a href="index.php?p=materiales">
                                    <i class="pe-7s-ticket metismenu-icon">
                                    </i>Materiales
                                </a>
                            </li>


                            <?php if ($_SESSION['creaCot'] == "1") { ?>

                                <li>
                                    <a href="index.php?p=cotizaciones">
                                        <i class="pe-7s-piggy metismenu-icon">
                                        </i>Cotizaciones
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if ($_SESSION['creaCot'] == "1") { ?>

                                <li>
                                    <a href="index.php?p=pedidos">
                                        <i class="pe-7s-cart metismenu-icon">
                                        </i>Pedidos
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if ($_SESSION['producciones'] == "1") { ?>

                                <li>
                                    <a href="index.php?p=producciones">
                                        <i class="pe-7s-scissors metismenu-icon">
                                        </i>Producción
                                    </a>
                                </li>
                            <?php } ?>

                            <?php if ($_SESSION['salidaInventario'] == "1") { ?>

                                <li>
                                    <a href="index.php?p=salidas">
                                        <i class="pe-7s-plane metismenu-icon">
                                        </i>Dar Salida
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if ($_SESSION['genRem'] == "1") { ?>

                                <li>
                                    <a href="index.php?p=remisiones">
                                        <i class=" pe-7s-compass metismenu-icon">
                                        </i>Remisiones
                                    </a>
                                </li>
                            <?php } ?>

                            <?php if ($_SESSION['inventarios'] == "1") { ?>

                                <li>
                                    <a href="index.php?p=inventario">
                                        <i class="pe-7s-box1 metismenu-icon">
                                        </i>Inventario
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if ($_SESSION['devoluciones'] == "1") { ?>

                                <li>
                                    <a href="index.php?p=devoluciones">
                                        <i class="pe-7s-loop metismenu-icon">
                                        </i>Devoluciones
                                    </a>
                                </li>
                            <?php } ?>





                            <?php if ($_SESSION['productos'] == "1") { ?>
                                <li>
                                    <a href="index.php?p=productos">
                                        <i class="pe-7s-gift metismenu-icon">
                                        </i>Productos
                                    </a>
                                </li>
                            <?php } ?>






                        </ul>
                    </div>
                </div>
            </div>

            <!-- aqui va la iclusion de paginas -->
            <?php
            if (isset($_GET['p'])) {
                $p = $_GET['p'];

                if ($p == 'administradores') {
                    include_once("administradores.php");
                } else if ($p == 'productos') {
                    include_once("productos.php");
                } else if ($p == 'cotizaciones') {
                    include_once("cotizaciones.php");
                } else if ($p == 'productosact') {
                    include_once("productosact.php");
                } else if ($p == 'ordenescompra') {
                    include_once("ordenescompra.php");
                } else if ($p == 'cambiarpass') {
                    include_once("cambiarpass.php");
                } else if ($p == 'clientes') {
                    include_once("clientes.php");
                } else if ($p == 'cotizacionesact') {
                    include_once("cotizacionesact.php");
                } else if ($p == 'ordenescompraact') {
                    include_once("ordenescompraact.php");
                } else if ($p == 'parametros') {
                    include_once("parametros.php");
                } else if ($p == 'proveedores') {
                    include_once("proveedores.php");
                } else if ($p == 'tipos') {
                    include_once("tipos.php");
                } else if ($p == 'calibres') {
                    include_once("calibres.php");
                } else if ($p == 'recMat') {
                    include_once("recMat.php");
                } else if ($p == 'pedidosact') {
                    include_once("pedidosact.php");
                } else if ($p == 'pedidos') {
                    include_once("pedidos.php");
                } else if ($p == 'materiales') {
                    include_once("materiales.php");
                } else if ($p == 'producciones') {
                    include_once("producciones.php");
                } else if ($p == 'salidas') {
                    include_once("salidas.php");
                } else if ($p == 'inventario') {
                    include_once("inventario.php");
                } else if ($p == 'anchos') {
                    include_once("anchos.php");
                } else if ($p == 'remisiones') {
                    include_once("remisiones.php");
                } else if ($p == 'devoluciones') {
                    include_once("devoluciones.php");
                } else if ($p == 'choferes') {
                    include_once("choferes.php");
                } else if ($p == 'camiones') {
                    include_once("camiones.php");
                }
            }

            ?>



            <!--  hasta aqui -----------------  -->
        </div>
    </div>




    <div id="modalAdministradoresUpdate" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Actualizar Administrador</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="card-body">


                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="nombre">Nombre</label>
                                    <div>
                                        <input type="text" maxlength="50" class="form-control" id="nombreAdmin" name="nombreAdmin" placeholder="Nombre" />
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <label for="apellidos">Apellidos</label>
                                    <div>
                                        <input type="text" maxlength="50" class="form-control" id="apellidosAdmin" name="apellidosAdmin" placeholder="Apellidos" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="correo">Correo</label>
                                    <div>
                                        <input type="email" maxlength="100" class="form-control" id="correoAdmin" name="correoAdmin" placeholder="Correo" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="telefono">Teléfono</label>
                                    <div>
                                        <input type="text" maxlength="15" class="form-control" id="telefonoAdmin" name="telefonoAdmin" placeholder="Teléfono" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-4 col-md-2">
                                    <label for="correo">Usuarios</label>
                                    <div>
                                        <input type="checkbox" name="usuariosM" id="usuariosM">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Clientes</label>
                                    <div>
                                        <input type="checkbox" name="clientesM" id="clientesM">
                                    </div>
                                </div>
                                <div class="col-4  col-md-2">
                                    <label for="correo">Proveedores</label>
                                    <div>
                                        <input type="checkbox" name="proveedoresM" id="proveedoresM">
                                    </div>
                                </div>

                                <div class=" col-4 col-md-2">
                                    <label for="correo">Ver Productos</label>
                                    <div>
                                        <input type="checkbox" name="productosM" id="productosM">
                                    </div>
                                </div>
                                <div class=" col-4 col-md-2">
                                    <label for="correo">Editar Productos</label>
                                    <div>
                                        <input type="checkbox" name="editarProductosM" id="editarProductosM">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Ord. Compra</label>
                                    <div>
                                        <input type="checkbox" name="ordCompraM" id="ordCompraM">
                                    </div>
                                </div>

                                <div class="col-4 col-md-2">
                                    <label for="correo">Crea Cotización</label>
                                    <div>
                                        <input type="checkbox" name="creaCotM" id="creaCotM">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Recepción Mat</label>
                                    <div>
                                        <input type="checkbox" name="recMatM" id="recMatM">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Gen. Remisión</label>
                                    <div>
                                        <input type="checkbox" name="genRemM" id="genRemM">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Calibres</label>
                                    <div>
                                        <input type="checkbox" name="calibresM" id="calibresM">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Materiales</label>
                                    <div>
                                        <input type="checkbox" name="tiposM" id="tiposM">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Producción</label>
                                    <div>
                                        <input type="checkbox" name="produccionesM" id="produccionesM">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Elimina Cotización</label>
                                    <div>
                                        <input type="checkbox" name="eliminaCotizacion" id="eliminaCotizacionM">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Elimina Orden Compra</label>
                                    <div>
                                        <input type="checkbox" name="eliminaOCompra" id="eliminaOCompraM">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Cambiar Precios</label>
                                    <div>
                                        <input type="checkbox" name="cambiarPrecios" id="cambiarPreciosM">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Devoluciones</label>
                                    <div>
                                        <input type="checkbox" name="devoluciones" id="devolucionesM">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Dar Salidas</label>
                                    <div>
                                        <input type="checkbox" name="salidaInventario" id="salidaInventarioM">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Autorizar Pedidos</label>
                                    <div>
                                        <input type="checkbox" name="autorizarPedidosM" id="autorizarPedidosM">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Ver Inventarios</label>
                                    <div>
                                        <input type="checkbox" name="inventarios" id="inventariosM">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Realizar Traspasos</label>
                                    <div>
                                        <input type="checkbox" name="traspasos" id="traspasosM">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Ver todas las Cotizaciones</label>
                                    <div>
                                        <input type="checkbox" name="verCotizaciones" id="verCotizacionesM">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Actualizar Cantidades Pedido</label>
                                    <div>
                                        <input type="checkbox" name="pedidoCantidades" id="pedidoCantidadesM">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Cancelar Remisiones</label>
                                    <div>
                                        <input type="checkbox" name="cancelarRemisiones" id="cancelarRemisionesM">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Realizar Abonos</label>
                                    <div>
                                        <input type="checkbox" name="agregarAbonos" id="agregarAbonosM">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Cancelar Pedidos</label>
                                    <div>
                                        <input title="aún sin este permiso, cada usuario puede cancelar sus propios pedidos" type="checkbox" name="cancelarPedidos" id="cancelarPedidosM">
                                    </div>
                                </div>





                            </div>
                        </div>

                        <input type="hidden" id="idAdmin" />

                    </div>

                </div>
                <div class="modal-footer">
                    <a href="#" id="guardarAdmin" class="btn btn-primary pull-right">Actualizar</a>
                </div>
            </div>
        </div>
    </div>



    <?php

    $res = $catCon->obtenerFormasPago(true);
    $formas = $res->registros;


    $respuesta = $catCon->obtenerUsos();
    $usos = $respuesta->registros;

    $respuesta = $catCon->obtenerCamiones();
    $camiones = $respuesta->registros;

    $respuesta = $catCon->obtenerChoferes();
    $choferes = $respuesta->registros;


    $respuesta = $contAd->obtenerAdministradores();
    $vendedores = $respuesta->registros;
    


    ?>



    <div id="modalExtrasUpdate" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Actualizar Extras</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="card-body">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="observacionesM">Observaciones</label>
                                    <div>
                                        <input type="text" maxlength="100" class="form-control" id="observacionesM" name="observacionesM" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="condicionesM">Condiciones </label>
                                    <div>
                                        <input type="text" maxlength="100" class="form-control" id="condicionesM" name="condicionesM" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="vigenciaM">Vigencia de Precios </label>
                                    <div>
                                        <input type="date" class="form-control" id="vigenciaM" name="vigenciaM" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="formaM">Forma de pago </label>
                                    <div>
                                        <select class=" form-control " name="formaM" id="formaM">
                                            <?php
                                            if (isset($formas)) {
                                                foreach ($formas as $uni) {
                                                    echo '<option value="' . $uni['idFormaPago'] . '" >' .
                                                        strtoupper($uni['formaPago']) . '</option>';
                                                }
                                            } ?>
                                        </select> </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <!-- <div class="col-md-6">
                                    <label for="vigenciaM">Lugar de Entrega </label>
                                    <div>
                                        <input type="text" maxlength="100" class="form-control" id="lugarM" name="lugarM" />
                                    </div>
                                </div> -->
                                <div class="col-md-6">
                                    <label for="vigenciaM">Fecha de Entrega </label>
                                    <div>
                                        <input type="date" class="form-control" id="fechaentregaM" name="fechaentregaM" />
                                    </div>
                                </div>
                            </div>
                        </div>


                        <input type="hidden" id="idCotizacionM" />

                    </div>

                </div>
                <div class="modal-footer">
                    <a href="#" id="guardarExtras" class="btn btn-primary pull-right">Actualizar Extras</a>
                </div>
            </div>
        </div>
    </div>









    <div id="modalCartaPorte" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lblTituloCP">Datos Operador</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="card-body">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-5">
                                    <label for="tipoUnidadM">Tipo Unidad</label>
                                    <div>
                                        <input type="text" maxlength="50" class="form-control" id="tipoUnidadM" name="tipoUnidadM" />
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <label for="formaM">Placas </label>
                                    <div>
                                        <select class=" form-control " name="idCamionCP" id="idCamionCP">
                                            <?php
                                            if (isset($camiones)) {
                                                foreach ($camiones as $uni) {
                                                    echo '<option value="' . $uni['idCamion'] . '" >' .
                                                        strtoupper($uni['camion'] . " " . $uni['placas']) . '</option>';
                                                }
                                            } ?>
                                        </select> </div>
                                </div>
                                <div class="col-md-5">
                                    <label for="formaM">Operador </label>
                                    <div>
                                        <select class=" form-control " name="idChoferCP" id="idChoferCP">
                                            <?php
                                            if (isset($formas)) {
                                                foreach ($choferes as $uni) {
                                                    echo '<option value="' . $uni['idChofer'] . '" >' .
                                                        strtoupper($uni['chofer']) . '</option>';
                                                }
                                            } ?>
                                        </select> </div>
                                </div>
                                <div class="col-md-5">
                                    <label for="contenedorM">No. Contenedor </label>
                                    <div>
                                        <input type="text" maxlength="50" class="form-control" id="contenedorM" name="contenedorM" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label for="contenedorM">Con Peso </label>
                                    <div>
                                        <input type="checkbox" id="conPesoM" name="conPesoM" />
                                    </div>
                                </div>
                            </div>

                        </div>


                        <input type="hidden" id="idRemisionM" />

                    </div>

                </div>
                <div class="modal-footer">
                    <a href="#" id="imprimirCartaPorte" class="btn btn-primary pull-right">Imprimir Carta Porte</a>
                </div>
            </div>
        </div>
    </div>



    <div id="modalCancelarPedido" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lblTituloCP">Cancelar Pedido</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="card-body">
                        <div class="form-group">
                        <label > ¿Esta seguro que desea cancelar el pedido?</label>

                        </div>


                        <input type="hidden" id="idPedidoCancelar" />

                    </div>

                </div>
                <div class="modal-footer">
                    <a href="#" id="cancelarPedido" class="btn btn-primary pull-right">Cancelar Pedido</a>
                </div>
            </div>
        </div>
    </div>



    <div id="modalCancelarRemision" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="lblTituloCP">Cancelar Remisión</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="card-body">
                        <div class="form-group">
                        <label > ¿Esta seguro que desea cancelar la remisión?</label>

                        </div>


                        <input type="hidden" id="idRemisionCancelar" />

                    </div>

                </div>
                <div class="modal-footer">
                    <a href="#" id="cancelarRemision" class="btn btn-primary pull-right">Cancelar Remisión</a>
                </div>
            </div>
        </div>
    </div>



    <div id="modalAbono" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Realizar Abono</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="card-body">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="tipoUnidadM">Monto</label>
                                    <div>
                                        <input type="number" maxlength="10" class="form-control" id="montoAbono" name="montoAbono" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="clave">Forma de Pago</label>
                                    <div>
                                        <select class=" form-control " id="idFormaPagoAbono" name="idFormaPagoAbono">
                                            <?php
                                            if (isset($formas)) {
                                                foreach ($formas as $uni) {
                                                    echo '<option value="' . $uni['idFormaPago'] . '" >' .
                                                        strtoupper($uni['formaPago']) . '</option>';
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                </div>


                            </div>

                        </div>


                        <input type="hidden" id="idCotizacionAbono" />

                    </div>

                </div>
                <div class="modal-footer">
                    <a href="#" id="realizarAbono" class="btn btn-primary pull-right">Realizar Abono</a>
                </div>
            </div>
        </div>
    </div>


    <div id="modalTraspaso" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Realizar Traspaso</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="card-body">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="tipoUnidadM">Almacenes</label>
                                    <div>
                                        <select class=" form-control " id="idAlmacenTraspaso">

                                        </select>
                                    </div>
                                </div>

                            </div>

                        </div>


                        <input type="hidden" id="idRecepcionM" />

                    </div>

                </div>
                <div class="modal-footer">
                    <a href="#" id="realizarTraspaso" class="btn btn-primary pull-right">Realizar Traspaso</a>
                </div>
            </div>
        </div>
    </div>

    <div id="modalClientesUpdate" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Actualizar Cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="card-body">


                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="claveM">RFC</label>
                                    <div>
                                        <input type="text" maxlength="30" class="form-control" id="rfcM" name="rfcM" placeholder="RFC" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="clienteM">Cliente</label>
                                    <div>
                                        <input type="text" maxlength="150" class="form-control" id="clienteM" name="clienteM" placeholder="Cliente" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-6">
                                <label for="direccionM">Dirección</label>
                                <div>
                                    <input type="text" maxlength="255" class="form-control" id="direccionM" name="direccionM" placeholder="Dirección" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="representanteM">Contacto</label>
                                <div>
                                    <input type="text" maxlength="50" class="form-control" id="representanteM" name="representanteM" placeholder="Representante" />
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-2">
                            <div class="col-md-6">
                                <label for="clave">Teléfono</label>
                                <div>
                                    <input type="text" maxlength="20" class="form-control" id="telefonoM" name="telefonoM" placeholder="telefono" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="cliente">Mail</label>
                                <div>
                                    <input type="email" maxlength="200" class="form-control" id="mailM" name="mailM" placeholder="Mail" />
                                </div>
                            </div>
                        </div>
                        <div class="form-row mt-2">
                            <div class="col-md-6">
                                <label for="clave">Dirección de Entrega</label>
                                <div>
                                    <input type="text" maxlength="300" class="form-control" id="direccionentregaM" name="direccionentregaM" placeholder="Dirección de Entrega" />
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="clave">Tipo Precio</label>
                                <select class=" form-control " id="tipoprecioM" name="tipoprecioM">
                                    <option value="G">General</option>
                                    <option value="R">Revendedor</option>

                                </select>
                            </div>


                        </div>
                        <div class="form-row mt-2">
                            <div class="col-md-6">
                                <label for="clave">Comentarios</label>
                                <div>
                                    <input type="text" maxlength="200" class="form-control" id="comentariosM" name="comentariosM" placeholder="Comentarios" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="clave">Tipo CFDI</label>
                                <div>
                                    <select class=" form-control " id="idUsoM" name="idUsoM">
                                        <?php
                                        if (isset($usos)) {
                                            foreach ($usos as $uni) {
                                                echo '<option value="' . $uni['idUso'] . '" >' .
                                                    strtoupper($uni['uso']) . '</option>';
                                            }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                    <label for="clave">Vendedor</label>
                                    <div>
                                    <select class=" form-control " id="idVendedorM" name="idVendedorM">
                                            <?php
                                            if (isset($vendedores)) {
                                                foreach ($vendedores as $uni) {
                                                    echo '<option value="' . $uni['idUsuario'] . '" >' .
                                                        strtoupper($uni['nombre']." ".$uni['apellidos']) . '</option>';
                                                }
                                            } ?>
                                        </select>
                                     </div>
                                </div>



                        </div>


                        <input type="hidden" id="idCliente" />

                    </div>

                </div>
                <div class="modal-footer">
                    <a href="#" id="guardarCliente" class="btn btn-primary pull-right">Actualizar</a>
                </div>
            </div>
        </div>
    </div>



    <div id="modalCalibresUpdate" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Actualizar Calibre</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="card-body">


                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="calibreM">Calibre</label>
                                    <div>
                                        <input type="text" maxlength="30" class="form-control" id="calibreM" name="calibreM" placeholder="Calibre" />
                                    </div>
                                </div>

                            </div>
                        </div>


                        <input type="hidden" id="idCalibre" />

                    </div>

                </div>
                <div class="modal-footer">
                    <a href="#" id="guardarCalibre" class="btn btn-primary pull-right">Actualizar</a>
                </div>
            </div>
        </div>
    </div>


    <div id="modalTiposUpdate" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Actualizar Material</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="card-body">


                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="tipoM">Material</label>
                                    <div>
                                        <input type="text" maxlength="30" class="form-control" id="tipoM" name="tipoM" placeholder="Material" />
                                    </div>
                                </div>

                            </div>
                        </div>


                        <input type="hidden" id="idTipo" />

                    </div>

                </div>
                <div class="modal-footer">
                    <a href="#" id="guardarTipo" class="btn btn-primary pull-right">Actualizar</a>
                </div>
            </div>
        </div>
    </div>



    <div id="modalAnchosUpdate" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Actualizar Ancho</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="card-body">


                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="anchoM">Ancho</label>
                                    <div>
                                        <input type="text" maxlength="30" class="form-control" id="anchoM" name="anchoM" placeholder="Ancho" />
                                    </div>
                                </div>

                            </div>
                        </div>


                        <input type="hidden" id="idAncho" />

                    </div>

                </div>
                <div class="modal-footer">
                    <a href="#" id="guardarAncho" class="btn btn-primary pull-right">Actualizar</a>
                </div>
            </div>
        </div>
    </div>




    <div id="modalChoferesUpdate" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Actualizar Chofer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="card-body">


                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="anchoM">Chofer</label>
                                    <div>
                                        <input type="text" maxlength="100" class="form-control" id="choferM" name="choferM" placeholder="Chofer" />
                                    </div>
                                </div>

                            </div>
                        </div>


                        <input type="hidden" id="idChofer" />

                    </div>

                </div>
                <div class="modal-footer">
                    <a href="#" id="guardarChofer" class="btn btn-primary pull-right">Actualizar</a>
                </div>
            </div>
        </div>
    </div>


    <div id="modalCamionesUpdate" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Actualizar Unidad</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="card-body">


                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="anchoM">Unidad</label>
                                    <div>
                                        <input type="text" maxlength="50" class="form-control" id="camionM" name="camionM" placeholder="Unidad" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="anchoM">Placas</label>
                                    <div>
                                        <input type="text" maxlength="50" class="form-control" id="placasMCam" name="placasMCam" placeholder="Placas" />
                                    </div>
                                </div>

                            </div>
                        </div>


                        <input type="hidden" id="idCamion" />

                    </div>

                </div>
                <div class="modal-footer">
                    <a href="#" id="guardarCamion" class="btn btn-primary pull-right">Actualizar</a>
                </div>
            </div>
        </div>
    </div>

    <div id="modalProveedoresUpdate" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Actualizar Proveedor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="card-body">


                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="proveedorM">Proveedor</label>
                                    <div>
                                        <input type="text" maxlength="50" class="form-control" id="proveedorM" name="proveedorM" placeholder="Proveedor" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="proveedor">RFC</label>
                                    <div>
                                        <input type="text" maxlength="50" class="form-control" id="rfcPM" name="rfcPM" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="proveedor">Dirección</label>
                                    <div>
                                        <input type="text" maxlength="200" class="form-control" id="direccionPM" name="direccionPM" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="proveedor">Teléfono</label>
                                    <div>
                                        <input type="text" maxlength="50" class="form-control" id="telefonoPM" name="telefonoPM" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="proveedor">Comentarios</label>
                                    <div>
                                        <input type="text" maxlength="300" class="form-control" id="comentariosPM" name="comentariosPM" />
                                    </div>
                                </div>

                            </div>
                        </div>


                        <input type="hidden" id="idProveedor" />

                    </div>

                </div>
                <div class="modal-footer">
                    <a href="#" id="guardarProveedor" class="btn btn-primary pull-right">Actualizar</a>
                </div>
            </div>
        </div>
    </div>


    <div id="modalPedidoUpdate" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Generar Pedido</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="card-body">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label>Si se genera el pedido ya no se puede modificar la cotización, ¿Desea generar el pedido? </label>

                                </div>

                            </div>
                        </div>
                        <input type="hidden" id="idCotizacionM" />

                    </div>

                </div>
                <div class="modal-footer">
                    <a href="#" id="generarPedido" class="btn btn-primary pull-right">Generar Pedido</a>
                </div>
            </div>
        </div>
    </div>


    <div id="modalFinalizaOrden" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Finaliza Orden</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="card-body">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label>Si se finaliza la orden ya no se podrá modificar, ¿esta seguro que desea finalizar la orden? </label>

                                </div>

                            </div>
                        </div>
                        <input type="hidden" id="idCotizacionM" />

                    </div>

                </div>
                <div class="modal-footer">
                    <a href="#" id="finalizaOrden" class="btn btn-primary pull-right">Finalizar Orden de Compra</a>
                </div>
            </div>
        </div>
    </div>


    <div id="modalCancelarAbono" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Cancelar Abono</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="card-body">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label>¿Esta seguro que desea cancelar el abono? </label>

                                </div>

                            </div>
                        </div>
                        <input type="hidden" id="idAbonoCancelar" />

                    </div>

                </div>
                <div class="modal-footer">
                    <a href="#" id="cancelarAbono" class="btn btn-primary pull-right">Cancelar Abono</a>
                </div>
            </div>
        </div>
    </div>

    <?php
    $respuesta = $catCon->obtenerCalibres();
    $calibres = $respuesta->registros;

    $respuesta = $catCon->obtenerAlmacenes();
    $almacenes = $respuesta->registros;

    $res = $catCon->obtenerTiposActivos();
    $tipos = $res->registros;

    $res = $catCon->obtenerUnidades();
    $unidades = $res->registros;


    $res = $catCon->obtenerAnchos(true);
    $anchos = $res->registros;

    ?>

    <div id="modalProductosUpdate" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Actualizar Producto <label id="productosku"></label></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="card-body">


                        <div class="form-group">
                            <div class="form-row">

                                <div class="col-md-4">
                                    <label for="productoM">Producto</label>
                                    <div>
                                        <input type="text" maxlength="100" class="form-control" id="productoM" name="productoM" placeholder="Producto" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="largoM">Largo</label>
                                    <div>
                                        <input type="text" maxlength="10" class="form-control" id="largoM" name="largoM" placeholder="Largo" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="idCalibre">Ancho</label>
                                    <div>
                                        <select class=" form-control selectAncho" name="idAnchoM" id="idAnchoM">
                                            <?php
                                            if (isset($anchos)) {
                                                foreach ($anchos as $uni) {
                                                    echo '<option value="' . $uni['idAncho'] . '" >' .
                                                        strtoupper($uni['ancho']) . '</option>';
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="idCalibre">Unidad</label>
                                    <div>
                                        <select class=" form-control selectUnidad" name="idUnidad" id="idUnidadM">
                                            <?php
                                            if (isset($unidades)) {
                                                foreach ($unidades as $uni) {
                                                    echo '<option value="' . $uni['idUnidad'] . '" >' .
                                                        strtoupper($uni['unidad']) . '</option>';
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="idUnidadFacturaM">Unidad Factura</label>
                                    <div>
                                        <select class=" form-control selectUnidad" name="idUnidadFacturaM" id="idUnidadFacturaM">
                                            <?php
                                            if (isset($unidades)) {
                                                foreach ($unidades as $uni) {
                                                    echo '<option value="' . $uni['idUnidad'] . '" >' .
                                                        strtoupper($uni['unidad']) . '</option>';
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-md-4">
                                <label for="idCalibre">Precio General</label>
                                <div>
                                    <input type="number" <?php if ($_SESSION['cambiarPrecios'] != "1") {
                                                                echo "readonly";
                                                            } ?> step=".000001" maxlength="20" class="form-control" id="precioGenM" name="precioGenM" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="idCalibre">Precio Revendedores</label>
                                <div>
                                    <input type="number" <?php if ($_SESSION['cambiarPrecios'] != "1") {
                                                                echo "readonly";
                                                            } ?> step=".000001" maxlength="20" class="form-control" id="precioRevM" name="precioRevM" />
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <label for="idTipo">Entrada</label>
                                <div>
                                    <input type="checkbox" id="chkEntradaM" name="chkEntradaM">
                                </div>
                            </div>
                            <div class="col-6 col-md-2">
                                <label for="idTipo">Salida</label>
                                <div>
                                    <input type="checkbox" id="chkSalidaM" name="chkSalidaM">
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="idCalibre">Calibre</label>
                                    <div>
                                        <select class=" form-control selectCalibre" name="idCalibre" id="idCalibreM">
                                            <?php
                                            if (isset($calibres)) {
                                                foreach ($calibres as $uni) {
                                                    echo '<option value="' . $uni['idCalibre'] . '" >' .
                                                        strtoupper($uni['calibre']) . '</option>';
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="idTipo">Material</label>
                                    <div>
                                        <select class=" form-control selectTipo" name="idTipo" id="idTipoM">

                                            <?php
                                            if (isset($tipos)) {
                                                foreach ($tipos as $uni) {
                                                    echo '<option value="' . $uni['idTipo'] . '"  >' .
                                                        strtoupper($uni['tipo']) . '</option>';
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="formFactorM" class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="idCalibre">Peso Teórico</label>
                                    <div>
                                        <input type="number" step=".000001" maxlength="20" class="form-control" id="pesoTeoricoM" name="pesoTeoricoM" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="idCalibre">&nbsp;</label>
                                    <div>
                                        Kgs. / Metro.
                                    </div>
                                </div>
                                <div class="col-4 col-md-3">
                                    <label for="medidas">Medidas al revés</label>
                                    <div>
                                        <input type="checkbox" name="medidasM" id="medidasM">
                                    </div>
                                </div>

                            </div>
                        </div>


                        <input type="hidden" id="idProducto" />

                    </div>

                </div>
                <div class="modal-footer">
                    <a href="#" id="guardarProducto" class="btn btn-primary pull-right">Actualizar</a>
                </div>
            </div>
        </div>
    </div>

    <?php
    if (isset($_GET['p'])) {
        if ($_GET['p'] == "organizadores") {
            include_once './controllers/LoginController.php';
            $adcont = new LoginController();
            $resEstados = $adcont->obtenerEstados(1);
            $estados = $resEstados->registros;
        }
    }

    ?>








    <div id="modalProductosUpdate" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Actualizar Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="card-body">


                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="claveProd">Clave</label>
                                    <div>
                                        <input type="text" maxlength="50" class="form-control" id="claveProd" name="claveProd" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="descCortaProd">Descripción Corta </label>
                                    <div>
                                        <input type="text" maxlength="200" class="form-control" id="descCortaProd" name="descCortaProd" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="descLargaProd">Descripción Larga</label>
                                    <div>
                                        <textarea name="descLargaProd" id="descLargaProd" style="width: 100%" rows="3"></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="idUnidadProd">Unidad</label>
                                    <div>
                                        <select class=" form-control selectUnidadProd" name="idUnidadProd" id="idUnidadProd">
                                            <?php
                                            if (isset($unidades)) {
                                                foreach ($unidades as $uni) {
                                                    echo '<option value="' . $uni['idUnidad'] . '" >' .
                                                        strtoupper($uni['unidad']) . '</option>';
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="idLineaProd">Linea</label>
                                    <div>
                                        <select class=" form-control selectLineaProd" name="idLineaProd" id="idLineaProd">
                                            <option value="NULL">Sin linea</option>
                                            <?php
                                            if (isset($lineas)) {
                                                foreach ($lineas as $uni) {
                                                    echo '<option value="' . $uni['idLinea'] . '"  >' .
                                                        strtoupper($uni['linea']) . '</option>';
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="idProductoProd" />

                    </div>

                </div>
                <div class="modal-footer">
                    <a href="#" id="guardarProducto" class="btn btn-primary pull-right">Actualizar</a>
                </div>
            </div>
        </div>
    </div>

    <div id="modalRecibirMateria" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Recibir Material</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="telefono">¿Esta seguro que desea recibir la materia?</label>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="productoRecibirM">Producto</label>
                                    <div>
                                        <div>

                                            <input type="hidden" id="idProductoM">
                                            <input type="text" disabled class="form-control" id="productoRecibirM" name="productoRecibirM" />
                                        </div>
                                    </div>
                                </div>
                                <div id="divPesoTeorico" class="col-md-6">
                                    <label for="productoPesoTeoricoM">Peso Teórico x Metro</label>
                                    <div>
                                        <div>
                                            <input type="text" disabled class="form-control" id="productoPesoTeoricoM" name="productoPesoTeoricoM" />
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="form-row mt-2">
                                <div class="col-md-6">
                                    <label for="idUnidadProd">Unidad</label>
                                    <div>
                                        <select class=" form-control selectUnidadRec" name="idUnidadRec" id="idUnidadRec">

                                        </select>
                                    </div>
                                </div>
                                <div id="divCantidadRecM" class="col-md-6">
                                    <label id="lblCantidadM" for="cantidadRecM"></label>
                                    <div>
                                        <input type="number" step=".01" class="form-control" id="cantidadRecM" name="cantidadRecM" />
                                    </div>
                                </div>

                            </div>
                            <div class="form-row mt-2">
                                <div class="col-md-12">
                                    <label for="idUnidadProd">Almacen</label>
                                    <div>
                                        <select class=" form-control selectAlmacenRec" name="idAlmacenRec" id="idAlmacenRec">
                                            <option value="0">- Seleccione Almacen - </option>
                                            <?php
                                            if (isset($almacenes)) {
                                                foreach ($almacenes as $uni) {
                                                    echo '<option value="' . $uni['idAlmacen'] . '" >' .
                                                        strtoupper($uni['almacen']) . '</option>';
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <input type="hidden" id="idMateria" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="recibirMateria" class="btn btn-primary pull-right">Recibir</button>
                </div>
            </div>
        </div>
    </div>



    <div id="modalGenerarProduccion" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Generar Producto Terminado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group">
                            <label id="lblProducto"></label>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label style="font-size: 20px;" id="lblProcesado"></label>
                            </div>
                            <div class="col-md-6">
                                <label style="font-size: 20px;" id="lblPesoTeorico"></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row mt-2">
                                <div class="col-md-6">
                                    <label for="codigoBarras"># Recepción o Devolución </label>
                                    <div>
                                        <div>
                                            <input type="text" class="form-control" id="codigoBarrasProd" name="codigoBarrasProd" placeholder="R# / D#" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label id="lblCantMetros" for="cantidad"></label>
                                    <div>
                                        <div>


                                            <input type="number" class="form-control" id="utilizadosM" name="utilizadosM" />
                                        </div>
                                    </div>
                                </div>


                            </div>

                            <div class="form-row">
                                <div class="col-md-6">
                                    <label id="lblCantMetros" for="cantidad">Kilos Reales Utilizados</label>
                                    <div>
                                        <div>


                                            <input type="number" class="form-control" id="utilizadosUsM" name="utilizadosUsM" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="idUnidadProd">Almacen Destino</label>
                                    <div>
                                        <select class=" form-control " name="idAlmacenProd" id="idAlmacenProd">
                                            <option value="0">- Seleccione Almacen - </option>
                                            <?php
                                            if (isset($almacenes)) {
                                                foreach ($almacenes as $uni) {
                                                    echo '<option value="' . $uni['idAlmacen'] . '" >' .
                                                        strtoupper($uni['almacen']) . '</option>';
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <input type="hidden" id="idCotizacionDetM" />
                        <input type="hidden" id="idProductoDet" />
                        <input type="hidden" id="pesoTeoricoProd" />
                        <input type="hidden" id="idUnidadProd" />
                        <input type="hidden" id="metrosProd" />
                        <input type="hidden" id="idProductoM">

                    </div>
                </div>
                <div class="modal-footer">
                    <button id="generarMateria" class="btn btn-primary pull-right">Generar Producto Terminado</button>
                </div>
            </div>
        </div>
    </div>


    <div id="modalCancelarProduccion" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Cancelar Producto Terminado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <label>¿Esta seguro que desea cancelar el folio de producción?</label>
                        <input type="hidden" id="idProduccionCancelar" />


                    </div>
                </div>
                <div class="modal-footer">
                    <button id="cancelarProduccion" class="btn btn-primary pull-right">Cancelar Producción</button>
                </div>
            </div>
        </div>
    </div>


    <div id="modalEliminaCotizacion" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Eliminar Cotización</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="telefono">¿Esta seguro que desea eliminar la cotización?</label>
                        </div>
                        <div class="form-group">

                        </div>
                        <input type="hidden" id="idCotizacionM" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btnEliminarCotizacion" class="btn btn-primary pull-right">Eliminar</button>
                </div>
            </div>
        </div>
    </div>


    <div id="modalEliminaOCompra" class="modal fade " tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Eliminar Orden de Compra</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="telefono">¿Esta seguro que desea eliminar la Orden?</label>
                        </div>
                        <div class="form-group">

                        </div>
                        <input type="hidden" id="idOrdenM" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btnEliminarOrden" class="btn btn-primary pull-right">Eliminar</button>
                </div>
            </div>
        </div>
    </div>






    <div id="modalMensajeError" class="modal fade mensajeError" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Mensaje</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>



    <?php
    if (isset($mensajeEnviar)) {

        if ($mensajeEnviar != "") {
            echo "<script>$(document).ready(function(){
        $('#modalMensaje').find('.modal-body').text('" . $mensajeEnviar . "').end().modal('show');
     });</script>";
        }
    }
    ?>

</body>


</html>