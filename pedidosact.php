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






if ($entro == "3") {

    if (isset($_GET['idCotizacionDet'])) {
        $idCotizacionDet = $_GET['idCotizacionDet'];

        $contCotizaciones->eliminarProductoCotizacion($idCotizacionDet);
        $contCotizaciones->recalcularCotizacion($idCotizacion);
    }
}







if (isset($idCotizacion)) {

    $respPro = $contCotizaciones->obtenerCotizacion($idCotizacion);
    if ($respPro->exito) {

        $cotizacion = $respPro->registros[0];

        $cliente = $cotizacion['cliente'];
        $descuento = $cotizacion['descuento'];
        $grantotal = $cotizacion['grantotal'];
        $subtotal = $cotizacion['montototal'];
        $productosOrden = $cotizacion['productos'];
        if ($cotizacion['pedido'] == 1) {
            $pedido = true;
        } else {
            $pedido = false;
        }
    }
}



?>




<div class="app-main__outer">
    <div class="app-main__inner">


        <!-- aqui va el contenido de la página -->

        <div class="main-card mb-3 card">
            <input type="hidden" id="idCotizacionMobil" value="<?php echo $idCotizacion; ?>" />
            <input type="hidden" id="idCotizacionModal" value="<?php echo $idCotizacion; ?>" />
            <div class="card-body">

                <div class="form-group">
                    <div class="row">



                        <div class="col-md-12 ">

                            <div class="form-row">
                                <div class="col-md-1">
                                    <label for="orden"># Pedido</label>
                                    <div>
                                        <input type="text" id="orden" disabled size="10" class="form-control" value="<?php if (isset($idCotizacion)) {
                                                                                                                            echo $idCotizacion;
                                                                                                                        } ?>" />

                                    </div>
                                </div>


                                <div class="col-md-5">
                                    <label for="proveedor">Cliente</label>
                                    <div>
                                        <input type="text" id="proveedor" disabled size="50" class="form-control" value="<?php if (isset($cliente)) {
                                                                                                                                echo strtoupper($cliente);
                                                                                                                            } ?>" />

                                    </div>
                                </div>
                                <div class="col-md-2">
                                <label for="proveedor">&nbsp;</label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <button type="button" id="btnExtras" title="Actualizar Extras Pedido" class="btn btn-info" name="signup" value="Sign up">Extras</button>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="imprimirPedido.php?idCotizacion=<?php echo $idCotizacion; ?>&idU=<?php echo $_SESSION['idUsr']?>" target="_blank"><button type="button" title="Imprimir Pedido" class="mb-2 mr-2 btn-icon btn-icon-only btn btn-warning"><i class="pe-7s-print btn-icon-wrapper"> </i></button></a>

                                        </div>
                                        <div class="col-md-4">
                                          

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="psc">Subtotal</label>
                                            <div>
                                                <input disabled="" size="5" class="form-control" value="<?php if (isset($subtotal)) {
                                                                                                            echo "$ " . number_format($subtotal, 2, '.', ',');
                                                                                                        } ?>" />

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="descuento">Descuento %</label>
                                            <div>
                                                <input type="text" disabled class="form-control" id="descuentoCam" name="descuentoCam" value="<?php if (isset($descuento)) {
                                                                                                                                                    echo $descuento;
                                                                                                                                                } ?>" />

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="psc">Total Pedido</label>
                                            <div>
                                                <input disabled="" size="5" class="form-control" value="<?php if (isset($grantotal)) {
                                                                                                            echo "$ " . number_format($grantotal, 2, '.', ',');
                                                                                                        } ?>" />

                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                          
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
                            <th>X</th>


                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $cont = 1;
                        foreach ($productosOrden as $reg) {
                            
                            if(is_numeric($reg['largo']))
                            {
                                $largo=$reg['largo'];
                                $mostrarbotonMetros=false;
                            }
                            else
                            {
                                $largo="";
                            }
                            
                            ?>
                            <tr>
                                <?php if ($reg['metros'] > 0) {
                                    $totalPartida = $reg['metros'] * $reg['preciounitario'] * $reg['cantidad'];
                                    $precioPorPieza = $totalPartida / $reg['cantidad'];
                                } else {
                                    $totalPartida = $reg['preciounitario'] * $reg['cantidad'];
                                    $precioPorPieza = "0.00";
                                } ?>
                                <td><?php echo $cont ?></td>
                                <td><?php echo strtoupper($reg['sku'] . " ".$reg['producto'] . " ". $largo." ". $reg['ancho'] . " " .$reg['calibre'] . " " . $reg['tipo']);   ?></td>
                                <td><?php echo $reg['unidad'] ?></td>
                                <td><?php echo $reg['cantidad'] ?></td>
                                <td><?php echo "$ " . number_format($reg['preciounitario'], 2, '.', ',') ?></td>
                                <td><?php echo $reg['metros'] ?></td>
                                <td><?php echo "$ " . number_format($precioPorPieza, 2, '.', ',') ?></td>
                                <td><?php echo "$ " . number_format($totalPartida, 2, '.', ',') ?></td>
                                <td><a href="index.php?p=cotizacionesact&entro=3&idCotizacion=<?php echo $idCotizacion; ?>&idCotizacionDet=<?php echo $reg['idCotizacionDet'] ?>" <?php if (isset($pedido) && $pedido == "1") {
                                                                                                                                                                                        echo "style='display:none;'";
                                                                                                                                                                                    } else { ?> class="btn btn-primary" <?php } ?>>X</a></td>


                            </tr>
                        <?php $cont++;
                        } ?>


                    </tbody>

                </table>


            </div>







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