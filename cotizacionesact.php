<?php

include_once './controllers/CotizacionController.php';
include_once './controllers/CatalogosController.php';
if (!isset($_SESSION['nombreUsr'])) {
    echo "<script>window.setTimeout(function() { window.location = 'login.php' }, 10);</script>";
}

$controller = new CatalogosController();
$contCotizaciones = new CotizacionController();

$entro = "";
if (isset($_POST['entro'])) {
    $entro = $_POST['entro'];
}

if (isset($_GET['entro'])) {
    $entro = $_GET['entro'];
}


$idCotizacion = "";
if (isset($_POST['idCotizacion'])) {
    $idCotizacion = $_POST['idCotizacion'];
}

if (isset($_GET['idCotizacion'])) {
    $idCotizacion = $_GET['idCotizacion'];
}


if ($entro == "2") {

    if (isset($_POST['idProd'])) {
        $idProd = $_POST['idProd'];
        if ($idProd > 0) {
            $precioUnitarioProd = $_POST['precioUnitarioProd'];
            $metrosProd = $_POST['metrosProd'];
            $cantProd = $_POST['cantProd'];
            $idUnidad = $_POST['idUnidad'];


            if ($cantProd == "") {
                $mensajeEnviar = "La cantidad de productos es requerida";
                $idProd = 0;
            } else {
                if ($precioUnitarioProd == "") {
                    $mensajeEnviar = "El precio unitario es requerido";
                    $idProd = 0;
                } else {

                    if ($idUnidad == "" || $idUnidad == "0") {
                        $mensajeEnviar = "Debe seleccionar una unidad válida";
                        $idProd = 0;
                    } else {

                        if ($metrosProd == "") {
                            $metrosProd = 0;
                        }
                        $contCotizaciones->agregarProductoCotizacion(
                            $idCotizacion,
                            $idProd,
                            $cantProd,
                            $precioUnitarioProd,
                            $metrosProd,
                            $idUnidad

                        );

                        $contCotizaciones->recalcularCotizacion($idCotizacion);

                        $precioUnitarioProd = "";
                        $metrosProd = "";
                        $cantProd = "";
                        $idProd = "";
                    }
                }
            }
        } else {
            $mensajeEnviar = "Seleccione un producto válido";
        }
    }
}




if ($entro == "3") {

    if (isset($_GET['idCotizacionDet'])) {
        $idCotizacionDet = $_GET['idCotizacionDet'];

        $contCotizaciones->eliminarProductoCotizacion($idCotizacionDet);
        $contCotizaciones->recalcularCotizacion($idCotizacion);
    }
}

if ($entro == "4") {

    if (isset($_GET['idCotizacionDet'])) {
        $idCotizacionDet = $_GET['idCotizacionDet'];

        $contCotizaciones->duplicarPartida($idCotizacionDet);
        $contCotizaciones->recalcularCotizacion($idCotizacion);
    }
}


if ($entro == "1") {
    $descuentoCam = $_POST['descuentoCam'];


    $contCotizaciones->actualizarDescuentoCotizacion($descuentoCam, $idCotizacion);
    $contCotizaciones->recalcularCotizacion($idCotizacion);
}


if (isset($_POST['idCotizacionDetCambiar'])) {
    $idCotizacionDetCambiar = $_POST['idCotizacionDetCambiar'];

    if (isset($_POST['cantidadpcambiar'])) {
        $cantidadCambiar = $_POST['cantidadpcambiar'];
        $contCotizaciones->actualizarCantidadCotDet($idCotizacionDetCambiar, $cantidadCambiar);
    }

    if (isset($_POST['preciopcambiar'])) {
        $preciopcambiar = $_POST['preciopcambiar'];
        $contCotizaciones->actualizarPrecioCotDet($idCotizacionDetCambiar, $preciopcambiar);
    }

    if (isset($_POST['metrospcambiar'])) {
        $metrospcambiar = $_POST['metrospcambiar'];
        $contCotizaciones->actualizarMetrosCotDet($idCotizacionDetCambiar, $metrospcambiar);
    }
    $contCotizaciones->recalcularCotizacion($idCotizacion);
}




if (isset($idCotizacion)) {

    $respPro = $contCotizaciones->obtenerCotizacion($idCotizacion);
    if ($respPro->exito) {

        $cotizacion = $respPro->registros[0];

        $cliente = $cotizacion['cliente'];
        $tipoPrecio = $cotizacion['tipoPrecio'];
        $descuento = $cotizacion['descuento'];
        $grantotal = $cotizacion['grantotal'];
        $tieneRemision = $cotizacion['tieneRemision'];
        $costoEnvio = $cotizacion['costoEnvio'];
        $subtotal = $cotizacion['montototal'];
        $idUsuario = $cotizacion['idUsuario'];
        $productosOrden = $cotizacion['productos'];
        if ($cotizacion['pedido'] == 1) {
            $pedido = true;
        } else {
            $pedido = false;
        }
    }
}


$respuesta = $controller->obtenerProductosSalida();
$productos = $respuesta->registros;




?>




<div class="app-main__outer">
    <div class="app-main__inner">


        <!-- aqui va el contenido de la página -->

        <div class="main-card mb-3 card">
            <input type="hidden" id="idCotizacionMobil" value="<?php echo $idCotizacion; ?>" />
            <input type="hidden" id="idCotizacionModal" value="<?php echo $idCotizacion; ?>" />
            <input type="hidden" id="idCotizacion" name="idCotizacion" value="<?php echo $idCotizacion; ?>" />
            <div class="card-body">

                <div class="form-group">
                    <div class="row">



                        <div class="col-md-12 ">

                            <div class="form-row">
                                <div class="col-md-2">
                                    <label for="orden"># Cotizacion</label>
                                    <div>
                                        <input type="text" id="orden" disabled size="10" class="form-control" value="<?php if (isset($idCotizacion)) {
                                                                                                                            echo $idCotizacion;
                                                                                                                        } ?>" />

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="proveedor">Cliente</label>
                                    <div>
                                        <input type="text" id="proveedor" disabled size="50" class="form-control" value="<?php if (isset($cliente)) {
                                                                                                                                echo strtoupper($cliente);
                                                                                                                            } ?>" />

                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label for="psc">Subtotal</label>
                                    <div>
                                        <input disabled="" size="5" class="form-control" value="<?php if (isset($subtotal)) {
                                                                                                    echo "$ " . number_format($subtotal + ($costoEnvio / 1.16), 2, '.', ',');
                                                                                                } ?>" />

                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label for="psc">Total Cotización</label>
                                    <div>
                                        <input disabled="" size="5" class="form-control" value="<?php if (isset($grantotal)) {
                                                                                                    echo "$ " . number_format($grantotal + $costoEnvio, 2, '.', ',');
                                                                                                } ?>" />

                                    </div>
                                </div>


                            </div>
                            <form id="HeadersForm" enctype="multipart/form-data" method="post" action="index.php?p=cotizacionesact">
                                <div class="form-row mt-1">
                                    <!--   <div class="col-md-2">
                                        <label for="descuento">Descuento</label>
                                        <div>
                                            <input type="number" class="form-control" id="descuentoCam" name="descuentoCam" value="<?php if (isset($descuento)) {
                                                                                                                                        echo $descuento;
                                                                                                                                    } ?>" />

                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <label for="cantKit">&nbsp;</label>
                                        <div>

                                           
                                            <input type="hidden" name="entro" value="1" />
                                            <button type="submit" class="btn btn-primary" name="signup" value="Sign up">Recalcular</button>
                                        </div>
                                    </div>-->
                                    <div class="col-md-4">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="cantKit">&nbsp;</label>
                                        <div>
                                            <?php if (!$tieneRemision && ($idUsuario == $_SESSION['idUsr'] || $_SESSION['verCotizaciones'] == "1")) { ?>
                                                <button type="button" id="btnExtras" title="Actualizar Extras Cotización" class="btn btn-info" name="signup" value="Sign up">Extras</button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="cantKit">&nbsp;</label>
                                        <div>

                                            <button type="button" id="btnHacerPedido" <?php if (isset($pedido) && $pedido == "1") {
                                                                                            echo "style='display:none;'";
                                                                                        } ?> class="btn btn-success" name="signup" value="Sign up">Pedido?</button>
                                        </div>
                                    </div>


                                    <div class="col-md-1">
                                        <label for="cantKit">&nbsp;</label>
                                        <div>
                                            <div class="row">

                                                <div class="col-12">
                                                    <a href="imprimirCotizacion.php?idCotizacion=<?php echo $idCotizacion; ?>&idU=<?php echo $_SESSION['idUsr'] ?>" target="_blank"><button title="Imprimir Cotización" type="button" class="mb-2 mr-2 btn-icon btn-icon-only btn btn-warning"><i class="pe-7s-print btn-icon-wrapper"> </i></button></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="cantKit">Costo Envio</label>
                                        <div>
                                            <input type="number" maxlength="10" step="0.01" class="form-control" id="costoEnvio" name="costoEnvio" value="<?php if (isset($costoEnvio)) {

                                                                                                                                                                echo $costoEnvio;
                                                                                                                                                            } ?>" />

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>


                    </div>
                </div>


                <div class="form-group">
                    <div class="row">

                        <div class="col-md-12">
                            <form id="ProdsForm" enctype="multipart/form-data" method="post" action="index.php?p=cotizacionesact">
                                <input type="hidden" name="porcentajeSC" id="porcentajeSCProd">
                                <input type="hidden" name="pesoTeoricoProductoSel" id="pesoTeoricoProductoSel">
                                <input type="hidden" name="precioGen" id="precioGen">
                                <input type="hidden" name="precioRev" id="precioRev">
                                <input type="hidden" name="largo" id="largo">
                                <input type="hidden" name="idUnidadProdSeleccionado" id="idUnidadProdSeleccionado">
                                <input type="hidden" name="tipoPrecio" id="tipoPrecio" value="<?php echo $tipoPrecio ?>">

                                <div class="form-row">
                                    <div class="col-md-5">
                                        <label for="idProd">Agregar Producto</label>
                                        <div>
                                            <select id="idProd" name="idProd" onchange="miFuncion(this.value)" class="multiselect-dropdown form-control">
                                                <option value="0">Seleccione</option>
                                                <?php
                                                if (isset($productos)) {
                                                    foreach ($productos as $ins) {
                                                        $verselected = "";
                                                        if (is_numeric($ins['largo'])) {
                                                            $largo = $ins['largo'];
                                                        } else {
                                                            $largo = "";
                                                        }
                                                        if (isset($idProd)) {
                                                            if ($idProd == $ins['idProducto']) {
                                                                $verselected = "selected";
                                                            }
                                                        } else {
                                                            $verselected = "";
                                                        }
                                                        $descripcion = strtoupper($ins['sku'] . " " . $ins['producto'] . " " . $largo . " " . $ins['ancho'] . " " . $ins['calibre'] . " " . $ins['tipo']);
                                                        $descripcion = str_replace("N/A", "", $descripcion);
                                                        echo '<option value="' . $ins['idProducto'] . '" ' . $verselected
                                                            . '   >' . strtoupper($descripcion) . '</option>';
                                                    }
                                                } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <label for="cantProd">Cantidad</label>
                                                <div>
                                                    <input type="number" size="5" step="0.01" class="form-control" id="cantProd" name="cantProd" value="<?php if (isset($cantProd)) {
                                                                                                                                                            echo $cantProd;
                                                                                                                                                        } ?>" />
                                                </div>
                                            </div>
                                            <div class="col-md-3">

                                                <label for="idCalibre">Unidad</label>
                                                <div>
                                                    <select class=" form-control " id="idUnidadCot" name="idUnidad">

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label id="lblPrecio" for="pesoTeorico">Precio Unitario</label>
                                                <div>
                                                    <input type="number" readonly size="5" step="0.01" class="form-control" id="precioUnitarioProd" name="precioUnitarioProd" value="<?php if (isset($precioUnitarioProd)) {
                                                                                                                                                                                            echo $precioUnitarioProd;
                                                                                                                                                                                        } ?>" />
                                                </div>
                                            </div>
                                            <div id="divMetros" class="col-md-3">
                                                <label for="precioUni">Metros Largo</label>
                                                <div>
                                                    <input type="number" size="5" step="0.01" class="form-control" id="metrosProd" name="metrosProd" value="<?php if (isset($metrosProd)) {
                                                                                                                                                                echo $metrosProd;
                                                                                                                                                            } ?>" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="">&nbsp; </label>
                                        <div>

                                            <input type="hidden" name="idCotizacion" value="<?php echo $idCotizacion; ?>" />
                                            <input type="hidden" name="entro" value="2" />
                                            <button type="submit" class="btn btn-primary" <?php if ($pedido) {
                                                                                                echo "disabled";
                                                                                            ?> title="Ya no puedes agregar productos porque ya es un pedido la cotización" <?php } ?> name="signup" value="Sign up">Agregar</button> </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>

                </div>


                <table style="width: 100%;" id="cotizacionesdet" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Producto</th>
                            <th>Unidad</th>
                            <th>Cantidad</th>
                            <th>Precio UM</th>
                            <th>Metros</th>
                            <th>Precio Unitario</th>
                            <th>Monto</th>
                            <th>Metros Lineales</th>
                            <th>Peso Teórico</th>
                            <th>X</th>
                            <th>Duplicar</th>


                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $cont = 1;
                        foreach ($productosOrden as $reg) {
                            $mostrarbotonMetros = true;
                            if (is_numeric($reg['largo'])) {
                                $largo = $reg['largo'];
                                $mostrarbotonMetros = false;
                            } else {
                                $largo = "";
                            }


                            if ($reg['metros'] > 0) {

                                $metrosLineales = $reg['metros'] * $reg['cantidad'];
                            } else {
                                if (is_numeric($reg['largo'])) {
                                    $metrosLineales = $largo * $reg['cantidad'];
                                } else {
                                    $metrosLineales = 0;
                                }
                            }

                        ?>
                            <tr>
                                <?php
                                $totalPartida =  $reg['preciounitario'] * $reg['cantidad'];
                                ?>
                                <td><?php echo $cont ?></td>
                                <td><?php echo strtoupper($reg['sku'] . " " . $reg['producto'] . " " . $largo . " " . $reg['ancho'] . " " . $reg['calibre'] . " " . $reg['tipo']);   ?></td>
                                <td><?php echo $reg['unidad'] ?></td>
                                <td><button type="button" data-toggle="popover-custom-content" rel="popover-focus" popover-id="KCA<?php echo $reg['idCotizacionDet'] ?>" class="mr-2 mb-2 btn btn-dark"><?php echo number_format($reg['cantidad'], 2, '.', ',') ?></button></td>
                                <td><?php echo "$ " . number_format($reg['precioUM'], 2, '.', ',') ?></td>
                                <td><?php if ($reg['idUnidad'] == 2 && $mostrarbotonMetros) { ?><button type="button" data-toggle="popover-custom-content" rel="popover-focus" popover-id="KCM<?php echo $reg['idCotizacionDet'] ?>" class="mr-2 mb-2 btn btn-warning"><?php echo number_format($reg['metros'], 2, '.', ',') ?></button><?php } else {
                                                                                                                                                                                                                                                                                                                                        echo number_format($reg['metros'], 2, '.', ',');
                                                                                                                                                                                                                                                                                                                                    } ?></td>
                                <td><button type="button" data-toggle="popover-custom-content" rel="popover-focus" popover-id="KCP<?php echo $reg['idCotizacionDet'] ?>" class="mr-2 mb-2 btn btn-danger"><?php echo number_format($reg['preciounitario'], 2, '.', ',') ?></button></td>
                                <td><?php echo "$ " . number_format($totalPartida, 2, '.', ',') ?></td>
                                <td><?php echo number_format($metrosLineales, 2, '.', ',') ?></td>
                                <td><?php if ($reg['idUnidad'] == 3) {
                                        echo number_format($reg['cantidad'], 2, '.', ',');
                                    } else if ($reg['idUnidad'] == 1) {
                                        echo number_format($reg['pesoTeorico'] * $reg['cantidad'], 2, '.', ',');
                                    } else {
                                        if ($metrosLineales == 0) {
                                            echo number_format($reg['pesoTeorico'] * $reg['cantidad'], 2, '.', ',');
                                        } else {
                                            echo number_format($reg['pesoTeorico'] * $metrosLineales, 2, '.', ',');
                                        }
                                    } ?></td>
                                <td><a href="index.php?p=cotizacionesact&entro=3&idCotizacion=<?php echo $idCotizacion; ?>&idCotizacionDet=<?php echo $reg['idCotizacionDet'] ?>" <?php if (isset($pedido) && $pedido == "1") {
                                                                                                                                                                                        echo "style='display:none;'";
                                                                                                                                                                                    } else { ?> class="btn btn-primary" <?php } ?>>X</a></td>
                                <td><a href="index.php?p=cotizacionesact&entro=4&idCotizacion=<?php echo $idCotizacion; ?>&idCotizacionDet=<?php echo $reg['idCotizacionDet'] ?>">Duplicar</a></td>

                            </tr>
                        <?php $cont++;
                        } ?>


                    </tbody>

                </table>


            </div>

            <?php foreach ($productosOrden as $reg) { ?>


                <div id="popover-content-KCA<?php echo  $reg['idCotizacionDet'] ?>" class="d-none">
                    <div class="dropdown-menu-header">
                        <div class="dropdown-menu-header-inner bg-primary">

                            <div class="menu-header-content">
                                <form action="index.php?p=cotizacionesact" method="POST">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <label for="porsobcos">Cantidad</label>
                                            <div>
                                                <input type="number" size="18" step="0.01" class="form-control" id="cantidadpcambiar" name="cantidadpcambiar" value="<?php echo number_format($reg['cantidad'], 2, '.', '') ?>" />

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div>

                                                <input type="hidden" name="idCotizacionDetCambiar" value=" <?php echo  $reg['idCotizacionDet'] ?>" />

                                                <input type="hidden" name="idCotizacion" value="<?php echo $idCotizacion; ?>" />


                                                <button type="submit" class="btn btn-danger" <?php if (isset($pedido) && $pedido == "1") {
                                                                                                    echo "disabled ";
                                                                                                    echo "title='Esta Cotización ya es un pedido, no la puedes editar desde esta pantalla'";
                                                                                                } ?> name="signup" value="Sign up">Cambiar</button> </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
                <div id="popover-content-KCP<?php echo  $reg['idCotizacionDet'] ?>" class="d-none">
                    <div class="dropdown-menu-header">
                        <div class="dropdown-menu-header-inner bg-primary">

                            <div class="menu-header-content">
                                <form action="index.php?p=cotizacionesact" method="POST">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <label for="porsobcos">Precio</label>
                                            <div>
                                                <input type="number" size="18" step="0.01" class="form-control" id="preciopcambiar" name="preciopcambiar" value="<?php echo number_format($reg['preciounitario'], 2, '.', '') ?>" />

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div>

                                                <input type="hidden" name="idCotizacionDetCambiar" value=" <?php echo  $reg['idCotizacionDet'] ?>" />

                                                <input type="hidden" name="idCotizacion" value="<?php echo $idCotizacion; ?>" />


                                                <button type="submit" class="btn btn-danger" <?php if (isset($pedido) && $pedido == "1") {
                                                                                                    echo "disabled ";
                                                                                                    echo "title='Esta Cotización ya es un pedido, no la puedes editar desde esta pantalla'";
                                                                                                } ?> name="signup" value="Sign up">Cambiar</button> </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
                <div id="popover-content-KCM<?php echo  $reg['idCotizacionDet'] ?>" class="d-none">
                    <div class="dropdown-menu-header">
                        <div class="dropdown-menu-header-inner bg-primary">

                            <div class="menu-header-content">
                                <form action="index.php?p=cotizacionesact" method="POST">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <label for="porsobcos">Metros</label>
                                            <div>
                                                <input type="number" size="18" step="0.01" class="form-control" id="metrospcambiar" name="metrospcambiar" value="<?php echo number_format($reg['metros'], 2, '.', '') ?>" />

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div>

                                                <input type="hidden" name="idCotizacionDetCambiar" value=" <?php echo  $reg['idCotizacionDet'] ?>" />

                                                <input type="hidden" name="idCotizacion" value="<?php echo $idCotizacion; ?>" />


                                                <button type="submit" class="btn btn-danger" <?php if (isset($pedido) && $pedido == "1") {
                                                                                                    echo "disabled ";
                                                                                                    echo "title='Esta Cotización ya es un pedido, no la puedes editar desde esta pantalla'";
                                                                                                } ?> name="signup" value="Sign up">Cambiar</button> </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            <?php } ?>






            <!-- hasta aqui llega-->

        </div>
        <?php include_once('footer.php') ?>
    </div>
    <script>
        $(document).ready(function() {
            $('#cotizacionesdet').DataTable({
                "lengthMenu": [
                    [100, 200, -1],
                    [100, 200, "Todos"]
                ]
            });
        });
    </script>