<?php

include_once './controllers/OrdenesController.php';
include_once './controllers/CatalogosController.php';
if (!isset($_SESSION['nombreUsr'])) {
    echo "<script>window.setTimeout(function() { window.location = 'login.php' }, 10);</script>";
}

$controller = new CatalogosController();
$contCotizaciones = new OrdenesController();

$entro = "";
if (isset($_POST['entro'])) {
    $entro = $_POST['entro'];
}

if (isset($_GET['entro'])) {
    $entro = $_GET['entro'];
}


$idOrden = "";
if (isset($_POST['idOrden'])) {
    $idOrden = $_POST['idOrden'];
}

if (isset($_GET['idOrden'])) {
    $idOrden = $_GET['idOrden'];
}


if ($entro == "2") {

    if (isset($_POST['idProd'])) {
        $idProd = $_POST['idProd'];
        if ($idProd > 0) {
            $pesoTeorico = $_POST['pesoTeorico'];
            $precioUni = $_POST['precioUni'];
            $cantProd = $_POST['cantProd'];
            $idUnidad = $_POST['idUnidad'];


            if ($cantProd == "") {
                $mensajeEnviar = "La cantidad de productos es requerida";
            } else {
                if ($idUnidad == 2 && $pesoTeorico == "") {
                    $mensajeEnviar = "Si la unidad es Metros, debes asignar un peso teórico";
                } else {
                    if ($precioUni == "") {
                        $mensajeEnviar = "El precio por unidad es requerido";
                    } else {
                        if ($pesoTeorico == "") {
                            $pesoTeorico = 0;
                        }
                        $contCotizaciones->agregarProductoOrden(
                            $idOrden,
                            $idProd,
                            $cantProd,
                            $pesoTeorico,
                            $precioUni,
                            $idUnidad

                        );

                        $pesoTeorico = "";
                        $precioUni = "";
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

    if (isset($_GET['idOrdenCompraDet'])) {
        $idOrdenCompraDet = $_GET['idOrdenCompraDet'];

        $contCotizaciones->eliminarProductoOrden($idOrdenCompraDet);
    }
}


if ($entro == "1") {
    $fechaRequerida = $_POST['fechaRequerida'];
    $comentarios = $_POST['comentarios'];

    $contCotizaciones->actualizarOrden($fechaRequerida, $comentarios, $idOrden);
}



if (isset($_POST['idOrdenDetCambiar'])) {
    $idOrdenDetCambiar = $_POST['idOrdenDetCambiar'];

    if (isset($_POST['cantidadpcambiar'])) {
        $cantidadCambiar = $_POST['cantidadpcambiar'];
        $contCotizaciones->actualizarOrdenDetCantidad($idOrdenDetCambiar, $cantidadCambiar);
    }

    if (isset($_POST['preciopcambiar'])) {
        $preciopcambiar = $_POST['preciopcambiar'];
        $contCotizaciones->actualizarOrdenDetPrecio($idOrdenDetCambiar, $preciopcambiar);
    }
}




if (isset($idOrden)) {

    $respPro = $contCotizaciones->obtenerOrden($idOrden);
    if ($respPro->exito) {


        $proveedor = $respPro->registros[0]['proveedor'];
        $fechaRequerida = $respPro->registros[0]['fechaRequerida'];
        $total = $respPro->registros[0]['total'];
        $productosOrden = $respPro->registros[0]['productos'];
        $estatus = $respPro->registros[0]['estatus'];
        $comentarios = $respPro->registros[0]['comentarios'];
    }
}


$respuesta = $controller->obtenerProductosEntrada();
$productos = $respuesta->registros;


$resUni = $controller->obtenerUnidades();
$unidades = $resUni->registros;

$tieneRecibidos = false;

foreach ($productosOrden as $prod) {
    if (count($prod['recepciones']) > 0) {
        $tieneRecibidos = true;
        break;
    }
}




?>




<div class="app-main__outer">
    <div class="app-main__inner">


        <!-- aqui va el contenido de la página -->

        <div class="main-card mb-3 card">
            <input type="hidden" id="idOrdenModal" value="<?php echo $idOrden; ?>" />
            <div class="card-body">

                <div class="form-group">
                    <div class="row">



                        <div class="col-md-12 ">
                            <form id="HeadersForm" enctype="multipart/form-data" method="post" action="index.php?p=ordenescompraact">
                                <div class="form-row">
                                    <div class="col-md-2">
                                        <label for="orden"># Orden</label>
                                        <div>
                                            <input type="text" id="orden" disabled size="10" class="form-control" value="<?php if (isset($idOrden)) {
                                                                                                                                echo $idOrden;
                                                                                                                            } ?>" />

                                        </div>
                                    </div>

                                    <div class="col-md-8">
                                        <label for="proveedor">Proveedor</label>
                                        <div>
                                            <input type="text" id="proveedor" disabled size="50" class="form-control" value="<?php if (isset($proveedor)) {
                                                                                                                                    echo strtoupper($proveedor);
                                                                                                                                } ?>" />

                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="psc">Total Orden Compra</label>
                                        <div>
                                            <input disabled="" size="5" class="form-control" value="<?php if (isset($total)) {
                                                                                                        echo "$ " . number_format($total, 2, '.', '');
                                                                                                    } ?>" />

                                        </div>
                                    </div>


                                </div>
                                <div class="form-row mt-1">
                                    <div class="col-md-2">
                                        <label for="descuento">Fecha Requerida</label>
                                        <div>
                                            <input type="date" class="form-control" id="fechaRequerida" name="fechaRequerida" value="<?php if (isset($fechaRequerida)) {
                                                                                                                                            echo $fechaRequerida;
                                                                                                                                        } ?>" />

                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="descuento">Comentarios</label>
                                        <div>
                                            <input type="text" maxlength="500" class="form-control" id="comentarios" name="comentarios" value="<?php if (isset($comentarios)) {
                                                                                                                                                    echo strtoupper($comentarios);
                                                                                                                                                } ?>" />

                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="descuento">&nbsp;</label>
                                        <div>
                                            <?php if ($estatus != "F") { ?>
                                                <button type="button" id="btnFinalizarOrden" class="btn btn-success" name="signup" value="Sign up">Finalizar Orden Compra</button>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="cantKit">&nbsp;</label>
                                        <div>

                                            <input type="hidden" name="idOrden" id="idOrden" value="<?php echo $idOrden; ?>" />
                                            <input type="hidden" name="entro" value="1" />
                                            <button type="submit" class="btn btn-primary" name="signup" value="Sign up">Guardar</button>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="cantKit">&nbsp;</label>
                                        <div>
                                            <div class="row">

                                                <div class="col-12">
                                                    <a title="Imprimir Orden de Compra" href="imprimirOrdenCompra.php?idOrden=<?php echo $idOrden; ?>" target="_blank"><button type="button" class="mb-2 mr-2 btn-icon btn-icon-only btn btn-warning"><i class="pe-7s-print btn-icon-wrapper"> </i></button></a>
                                                </div>
                                            </div>
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
                            <form id="ProdsForm" enctype="multipart/form-data" method="post" action="index.php?p=ordenescompraact">
                                <input type="hidden" name="pesoTeoricoF" id="pesoTeoricoF">
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <label for="idProd">Agregar Producto</label>
                                        <div>
                                            <select id="idProdC" onchange="my_function(this.value)" name="idProd" class="multiselect-dropdown form-control">
                                                <option value="0">Seleccione</option>
                                                <?php
                                                if (isset($productos)) {
                                                    foreach ($productos as $ins) {
                                                        $verselected = "";
                                                        if (isset($idProd)) {
                                                            if ($idProd == $ins['idProducto']) {
                                                                $verselected = "selected";
                                                            }
                                                        } else {
                                                            $verselected = "";
                                                        }
                                                        echo '<option value="' . $ins['idProducto'] . '" ' . $verselected
                                                            . '   >' . strtoupper($ins['sku'] . " - " . $ins['producto'] . " - " . $ins['ancho'] . " - " . $ins['calibre'] . " - " . $ins['tipo']) . '</option>';
                                                    }
                                                } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-7">
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
                                                    <select class=" form-control " id="idUnidad" name="idUnidad">

                                                    </select>
                                                </div>
                                            </div>
                                            <div style="display: none;" class="col-md-3" id="divPesoTeorico">
                                                <label for="pesoTeorico">Peso Teórico</label>
                                                <div>
                                                    <input type="number" size="5" step="0.01" class="form-control" id="pesoTeorico" name="pesoTeorico" value="<?php if (isset($pesoTeorico)) {
                                                                                                                                                                    echo $pesoTeorico;
                                                                                                                                                                } ?>" />
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label id="lblPrecio" for="precioUni">Precio UM</label>
                                                <div>
                                                    <input type="number" size="5" placeholder="$" step="0.01" class="form-control" id="precioUni" name="precioUni" value="<?php if (isset($precioUni)) {
                                                                                                                                                                                echo $precioUni;
                                                                                                                                                                            } ?>" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <label for="">&nbsp; </label>
                                        <div>

                                            <input type="hidden" name="idOrden" value="<?php echo $idOrden; ?>" />
                                            <input type="hidden" name="entro" value="2" />
                                            <button type="submit" class="btn btn-primary" <?php if ($estatus == "F") {
                                                                                                echo "disabled";
                                                                                            ?> title="No puedes agregar productos porque la orden ya fue finalizada" <?php } ?> name="signup" value="Sign up">Agregar</button> </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>

                </div>


                <table style="width: 100%;" id="ordenesdet" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cantidad</th>
                            <th>UM</th>
                            <th>Descripción</th>


                            <th>Peso Teórico Kgs.</th>
                            <th>Precio Unitario</th>
                            <th>Importe</th>
                            <th>X</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $cont = 1;
                        foreach ($productosOrden as $reg) {

                            if (is_numeric($reg['largo'])) {
                                $largo = $reg['largo'];
                            } else {
                                $largo = "";
                            }
                        ?>

                            <tr id="<?php echo $reg['idOrdenCompraDet'] ?>">
                                <td><?php echo $cont; ?></td>
                                <td><button type="button" data-toggle="popover-custom-content" rel="popover-focus" popover-id="K<?php echo $reg['idOrdenCompraDet'] ?>" class="mr-2 mb-2 btn btn-warning"><?php echo number_format($reg['cantidad'], 2, '.', ',') ?></button></td>
                                <td><?php echo $reg['unidad'] ?></td>
                                <td><?php echo strtoupper($reg['sku'] . " " . $reg['producto'] . " " . $largo . " " . $reg['ancho'] . " " . $reg['calibre'] . " " . $reg['tipo']) ?></td>



                                <td><?php if ($reg['pesoTeorico'] > 0) {
                                        if ($reg['idUnidad'] == 3) {
                                            echo number_format($reg['cantidad'], 2, '.', ',');
                                        } else {
                                            echo number_format($reg['cantidad'] * $reg['pesoTeorico'], 2, '.', ',');
                                        }
                                    } else if ($reg['prodPesoTeorico'] > 0) {
                                        if ($reg['idUnidad'] == 3) {
                                            echo number_format($reg['cantidad'], 2, '.', ',');
                                        } else {
                                            echo number_format($reg['cantidad'] * $reg['prodPesoTeorico'], 2, '.', ',');
                                        }
                                    } ?></td>
                                <td><button type="button" data-toggle="popover-custom-content" rel="popover-focus" popover-id="P<?php echo $reg['idOrdenCompraDet'] ?>" class="mr-2 mb-2 btn btn-warning"><?php echo number_format($reg['precioUnidadPeso'], 2, '.', ',') ?></button></td>
                                <td><?php echo "$ " . number_format($reg['precio'], 2, '.', ','); ?></td>

                                <td><?php if ($reg['recibido'] == 1) {
                                        echo "Recibido";
                                    } else {
                                        if (!$tieneRecibidos) { ?><a href="index.php?p=ordenescompraact&entro=3&idOrden=<?php echo $idOrden; ?>&idOrdenCompraDet=<?php echo $reg['idOrdenCompraDet'] ?>" class="btn btn-primary">X</a><?php }
                                                                                                                                                                                                                                } ?></td>


                            </tr>
                        <?php $cont++;
                        } ?>

                    </tbody>

                </table>


            </div>

            <?php foreach ($productosOrden as $reg) { ?>
                <div id="popover-content-K<?php echo  $reg['idOrdenCompraDet'] ?>" class="d-none">
                    <div class="dropdown-menu-header">
                        <div class="dropdown-menu-header-inner bg-primary">

                            <div class="menu-header-content">
                                <form action="index.php?p=ordenescompraact" method="POST">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <label for="porsobcos">Cantidad</label>
                                            <div>
                                                <input type="number" size="18" step="0.01" class="form-control" id="cantidadpcambiar" name="cantidadpcambiar" value="<?php echo number_format($reg['cantidad'], 2, '.', '') ?>" />

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div>

                                                <input type="hidden" name="idOrdenDetCambiar" value=" <?php echo  $reg['idOrdenCompraDet'] ?>" />

                                                <input type="hidden" name="idOrden" value="<?php echo $idOrden; ?>" />


                                                <button type="submit" class="btn btn-danger" <?php if (isset($estatus) && $estatus == "F") {
                                                                                                    echo "disabled ";
                                                                                                    echo "title='Esta Orden ya fuen finalizada, no se puede editar'";
                                                                                                } ?> name="signup" value="Sign up">Cambiar</button> </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

                <div id="popover-content-P<?php echo  $reg['idOrdenCompraDet'] ?>" class="d-none">
                    <div class="dropdown-menu-header">
                        <div class="dropdown-menu-header-inner bg-primary">

                            <div class="menu-header-content">
                                <form action="index.php?p=ordenescompraact" method="POST">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <label for="porsobcos">Precio</label>
                                            <div>
                                                <input type="number" size="18" step="0.01" class="form-control" id="preciopcambiar" name="preciopcambiar" value="<?php echo number_format($reg['precioUnidadPeso'], 2, '.', '') ?>" />

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div>

                                                <input type="hidden" name="idOrdenDetCambiar" value=" <?php echo  $reg['idOrdenCompraDet'] ?>" />

                                                <input type="hidden" name="idOrden" value="<?php echo $idOrden; ?>" />


                                                <button type="submit" class="btn btn-danger" <?php if (isset($estatus) && $estatus == "F") {
                                                                                                    echo "disabled ";
                                                                                                    echo "title='Esta Orden ya fuen finalizada, no se puede editar'";
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
            $('#ordenesdet').DataTable({
                "lengthMenu": [
                    [100, 200, -1],
                    [100, 200, "Todos"]
                ]
            });
        });
    </script>