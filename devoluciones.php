<?php

include_once './controllers/CatalogosController.php';
include_once './controllers/DevolucionesController.php';
if (!isset($_SESSION['nombreUsr'])) {
    echo "<script>window.setTimeout(function() { window.location = 'login.php' }, 10);</script>";
}
$controller = new CatalogosController();
$devcontroller = new DevolucionesController();


$entro = "";
if (isset($_POST['entro'])) {
    $entro = $_POST['entro'];
}

if (isset($_GET['entro'])) {
    $entro = $_GET['entro'];
}


if ($entro == "1") {

    $cantidad = $_POST['cantidadDev'];
    $idProdDev = $_POST['idProdDev'];
    $idAlmacen = $_POST['idAlmacen'];
    $producto = $controller->obtenerProducto($idProdDev)->registros[0];

    if (trim($cantidad) != "") {
        if ($cantidad <= 0) {
            $mensajeEnviar = "La cantidad a devolver no puede ser menor o igual a 0";
        } else {
            $devcontroller->insertarDevolucion($_SESSION['idUsr'], $idProdDev, $cantidad, $cantidad * $producto['pesoTeorico'], $idAlmacen);
        }
    } else {
        $mensajeEnviar = "La cantidad a devolver no puede ser 0";
    }
}


if (isset($_GET['idDevolucion'])) {
    $idDevolucion = $_GET['idDevolucion'];
}
else
{
    $idDevolucion="";
}

if ($idDevolucion != "") {
    $respu = $devcontroller->eliminarDevolucion($idDevolucion);

    if (!$respu->exito) {
        $mensajeEnviar = $respu->mensaje;
    }
}



$respuesta = $controller->obtenerProductosSalida();
$productos = $respuesta->registros;


$respuesta = $devcontroller->obtenerDevoluciones();
$devoluciones = $respuesta->registros;


$respuesta = $controller->obtenerAlmacenes();
$almacenes = $respuesta->registros;

?>







<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-loop icon-gradient bg-ripe-malin"></i>
                    </div>
                    <div>Devoluciones
                        <div class="page-title-subheading">.</div>
                    </div>
                </div>


            </div>


        </div>

        <!-- aqui va el contenido de la página -->

        <?php   ?>
        <div class="main-card mb-3 card">
            <div class="card-body">
                <form id="formDevoluciones" action="index.php?p=devoluciones" method="POST">


                    <div class="row">


                        <div class="col-md-5">
                            <label for="idProd">Producto a devolver</label>
                            <div>
                                <select id="idProdDev" name="idProdDev" class="multiselect-dropdown form-control">
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
                                                . '   >' . strtoupper($ins['sku'] . " - " . $ins['producto'] . " - " . $ins['calibre'] . " - " . $ins['tipo']) . '</option>';
                                        }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="idProd">Almacen Destino</label>
                            <div>
                                <select id="idAlmacen" name="idAlmacen" class="multiselect-dropdown form-control">

                                    <?php
                                    if (isset($almacenes)) {
                                        foreach ($almacenes as $ins) {
                                            $verselected = "";
                                            if (isset($idAlmacen)) {
                                                if ($idAlmacen == $ins['idAlmacen']) {
                                                    $verselected = "selected";
                                                }
                                            } else {
                                                $verselected = "";
                                            }
                                            echo '<option value="' . $ins['idAlmacen'] . '" ' . $verselected
                                                . '   >' . strtoupper($ins['almacen']) . '</option>';
                                        }
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="cantidad">Cantidad</label>
                            <div>
                                <input type="number" id="cantidadDev" name="cantidadDev" size="10" class="form-control" />

                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="cantidad">&nbsp;</label>
                            <div>
                                <button type="submit" id="btnHacerDevolucion" class="btn btn-warning mt-2 mb-2 ">Hacer Devolución</button>
                            </div>
                        </div>


                    </div>
                    <input type="hidden" name="entro" value="1">

                </form>


            </div>
        </div>
        <div class="main-card mb-3 card">
            <div class="card-body">

                <?php if (isset($devoluciones) && count($devoluciones) > 0) { ?>

                    <h2>Devoluciones realizadas</h2>
                    <table style="width: 100%;" id="devoluciones" class="table table-hover table-striped table-bordered ">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>UM</th>
                                <th>Cantidad</th>
                                <th>Producto</th>
                                <th>Kilos</th>
                                <th>Usuario</th>
                                <th>Fecha</th>
                                <th>Almacen</th>
                                <th>X</th>


                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            foreach ($devoluciones as $reg) {
                                if (is_numeric($reg['largo'])) {
                                    $largo = $reg['largo'];
                                } else {
                                    $largo = "";
                                }
                            ?>
                                <tr>

                                    <a>

                                        <td><?php echo $reg['idDevolucion'] ?></td>

                                        <td><?php echo $reg['unidad'] ?></td>
                                        <td><?php echo $reg['cantidad'] ?></td>
                                        <td><?php echo strtoupper($reg['sku'] . " " . $reg['producto'] . " " . $largo . " " . $reg['ancho'] . " " . $reg['calibre'] . " " . $reg['tipo']);   ?></td>
                                        <td><?php echo number_format($reg['kilos'], 2, '.', ',') ?></td>
                                        <td><?php echo strtoupper($reg['usuario']); ?></td>
                                        <td><?php echo strtoupper($reg['fecha']); ?></td>
                                        <td><?php echo strtoupper($reg['almacen']); ?></td>

                                        <td><a class="btn btn-primary" href="index.php?p=devoluciones&entro=4&idDevolucion=<?php echo $reg['idDevolucion']; ?>">X</a></td>
                                </tr>
                            <?php }

                            ?>


                        </tbody>

                    </table>
                <?php } ?>
            </div>
            <!-- hasta aqui llega-->
        </div>
    </div>
    <?php include_once('footer.php') ?>
</div>