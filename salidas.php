<?php

include_once './controllers/CotizacionController.php';
if (!isset($_SESSION['nombreUsr'])) {
    echo "<script>window.setTimeout(function() { window.location = 'login.php' }, 10);</script>";
}
$controller = new CotizacionController();


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





//$salidas = $controller->obtenerSalidas(10)->registros;

$cotizaciones = $controller->obtenerCotizaciones("PS", 0,0)->registros;

if ($idCotizacion != "") {
    $cotizacion = $controller->obtenerCotizacion($idCotizacion)->registros[0];
    $productos = $cotizacion['productos'];
}


?>







<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-plane icon-gradient bg-ripe-malin"></i>
                    </div>
                    <div>Salidas
                        <div class="page-title-subheading">.</div>
                    </div>
                </div>


                <form id="formPedidos" class="form-row col-md-12" action="index.php?p=salidas" method="POST">

                    <div class="col-md-2">

                    </div>
                    <div class="col-md-7">
                        <label for="idProdH"># Pedido</label>
                        <div>
                            <select class=" form-control" onchange="document.getElementById('formPedidos').submit();" name="idCotizacion" id="idCotizacion">
                                <option value="0">- Seleccione -</option>
                                <?php
                                if (isset($cotizaciones)) {
                                    foreach ($cotizaciones as $uni) {
                                        $tieneProduccionesSinRemision = false;
                                        $tieneDevolucionesSinRemision=false;
                                        foreach ($uni['productos'] as $produ) {
                                            foreach ($produ['producciones'] as $producci) {
                                                if (!$producci['tieneRemision']) {
                                                    $tieneProduccionesSinRemision = true;
                                                    break;
                                                }
                                            }
                                            foreach ($produ['devolucionesproducciones'] as $producci) {
                                                if (!$producci['tieneRemision']) {
                                                    $tieneDevolucionesSinRemision = true;
                                                    break;
                                                }
                                            }
                                        }

                                        if ($tieneProduccionesSinRemision || $tieneDevolucionesSinRemision) {

                                            $verselected = "";
                                            if (isset($idCotizacion)) {
                                                if ($idCotizacion == $uni['idCotizacion']) {
                                                    $verselected = "selected";
                                                }
                                            } else {
                                                $verselected = "";
                                            }
                                            echo '<option value="' . $uni['idCotizacion'] . '"' . $verselected
                                                . '   >PE' .
                                                strtoupper($uni['idCotizacion']) . ' - ' . strtoupper($uni['cliente'])  . '</option>';
                                        }
                                    }
                                } ?>
                            </select>
                        </div>
                    </div>






                </form>
            </div>


        </div>

        <!-- aqui va el contenido de la página -->

        <?php   ?>
        <div class="main-card mb-3 card">
            <div class="card-body">
                <?php if (isset($productos) && count($productos) > 0) { ?>
                    <div class="row">
                        <div class="col-md-3">
                            <button id="btnSelTodas" class="btn btn-dark mt-2 mb-2 ">Seleccionar Todas</button>
                        </div>
                        <div class="col-md-6"></div>
                        <div class="col-md-3">
                            <button id="btnGenerarRemision" style="font-size: 10px;" class="btn btn-warning mt-2 mb-2 ">Generar Remision y Dar Salida</button>
                        </div>
                    </div>

                    <table style="width: 100%;" id="detalleProd" class="table table-hover table-striped table-bordered ">
                        <thead>
                            <tr>
                                <th>Agregar</th>
                                <th># Producción</th>
                                <th>Producto</th>

                                <th>Calibre</th>
                                <th>Unidad</th>
                                <th>Cantidad</th>
                                <th>Kilos</th>
                                <th>ML</th>
                                <th>Almacen</th>

                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            foreach ($productos as $prod) {
                                foreach ($prod['producciones'] as $produ) {

                                    if (!$produ['tieneRemision']) {

                                        if (is_numeric($prod['largo'])) {
                                            $largo = $prod['largo'];
                                        } else {
                                            $largo = "";
                                        }

                            ?>
                                        <tr id="<?php echo $produ['idProduccion'] ?>">

                                            <a>
                                                <td><input type="checkbox" id="agregar"></td>
                                                <td>PR<?php echo $produ['idProduccion'] ?></td>
                                                <td><?php echo $prod['producto']  . " " . $largo . " " . $prod['ancho'] . " " .$prod['tipo'] ?></td>
                                                <td><?php echo $prod['calibre'] ?></td>
                                                <td><?php echo $prod['unidad'] ?></td>
                                                <td><?php echo number_format($produ['cantidad'], 2, '.', ',') ?></td>
                                                <td><?php echo number_format($produ['kilos'], 2, '.', ',') ?></td>
                                                <td><?php echo number_format($prod['metros'], 2, '.', ',') ?></td>
                                                <td><?php echo strtoupper($produ['almacen']); ?></td>


                                        </tr>
                                    <?php }
                                }
                                foreach ($prod['devolucionesproducciones'] as $produ) {

                                    if (!$produ['tieneRemision']) { ?>
                                        <tr id="D<?php echo $produ['idDevolucionProduccion'] ?>">

                                            <a>
                                                <td><input type="checkbox" id="agregar"></td>
                                                <td>PD<?php echo $produ['idDevolucionProduccion'] ?></td>
                                                <td><?php echo $prod['producto'] . " " . $prod['tipo'] ?></td>
                                                <td><?php echo $prod['calibre'] ?></td>
                                                <td><?php echo $prod['unidad'] ?></td>
                                                <td><?php echo number_format($produ['cantidad'], 2, '.', ',') ?></td>
                                                <td><?php echo number_format($produ['kilos'], 2, '.', ',') ?></td>
                                                <td><?php echo number_format($prod['metros'], 2, '.', ',') ?></td>
                                                <td><?php echo strtoupper($produ['almacen']); ?></td>


                                        </tr>
                            <?php }
                                }
                            }
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