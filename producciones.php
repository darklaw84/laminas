<?php
include_once './controllers/CatalogosController.php';
include_once './controllers/CotizacionController.php';
if (!isset($_SESSION['nombreUsr'])) {
    echo "<script>window.setTimeout(function() { window.location = 'login.php' }, 10);</script>";
}
$controller = new CotizacionController();
$contcat = new CatalogosController();
$controllerAdm = new AdministradorController();

$entro = "";
if (isset($_POST['entro'])) {
    $entro = $_POST['entro'];
}

if (isset($_GET['entro'])) {
    $entro = $_GET['entro'];
}



if (isset($_POST['numAlmacenProd'])) {
    $_SESSION["numAlmacenProduccion"] = $_POST['numAlmacenProd'];
}

if (!isset($_SESSION["numAlmacenProduccion"])) {
    $_SESSION["numAlmacenProduccion"] = 1;
}





$respuesta = $controller->obtenerCotizaciones("PR", 0, $_SESSION["numAlmacenProduccion"]);
$registros = $respuesta->registros;



$respuesta = $controller->obtenerUltimasProducciones(40);
$producciones = $respuesta->registros;

?>




<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">

                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-scissors icon-gradient bg-ripe-malin"></i>
                    </div>
                    <div>Producción
                        <div class="page-title-subheading">.</div>
                    </div>
                </div>


            </div>


        </div>
        <form id="formAlmacenes" method="POST" action="index.php?p=producciones">
            <div class="row mb-2 mt-2">
                <div class="col-md-4">
                </div>
                <div class="col-md-4">
                    <label for="clave">Almacen</label>
                    <div>
                        <select class=" form-control " onchange="document.getElementById('formAlmacenes').submit();" id="numAlmacenProd" name="numAlmacenProd">
                            <option <?php if (isset($_SESSION["numAlmacenProduccion"]) && $_SESSION["numAlmacenProduccion"] == "1") {
                                        echo "selected";
                                    } ?> value="1">Almacen 1</option>
                            <option <?php if (isset($_SESSION["numAlmacenProduccion"]) && $_SESSION["numAlmacenProduccion"] == "2") {
                                        echo "selected";
                                    } ?> value="2">Almacen 2</option>

                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                </div>
            </div>
        </form>

        <!-- aqui va el contenido de la página -->

        <div class="main-card mb-3 card">
            <div class="card-body">
                <h3>Pendientes de Generar</h3>
                <table style="width: 100%;" id="producciones" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th># Ped.</th>
                            <th>Gen</th>
                            <th>Producto</th>
                            <th>Medidas</th>
                            <th>Cant Solicitada</th>
                            <th>Unidad</th>
                            <th>Metros Lineales</th>
                            <th>Kilos</th>


                            <th>Cant. Procesada</th>
                            <th>Fecha Entrega</th>
                            <th>Prioridad</th>


                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $reg) {
                            foreach ($reg['productos'] as $det) {
                                if (!$det['partidaTerminada']) {

                                    $diaS = "";

                                    if ($reg['fechaEntrega'] != "") {
                                        $dia = date('N', strtotime($reg['fechaEntrega']));


                                        switch ($dia) {
                                            case 1:
                                                $diaS = "Lun";
                                                break;
                                            case 2:
                                                $diaS = "Mar";
                                                break;
                                            case 3:
                                                $diaS = "Mie";
                                                break;
                                            case 4:
                                                $diaS = "Jue";
                                                break;
                                            case 5:
                                                $diaS = "Vie";
                                                break;
                                            case 6:
                                                $diaS = "Sab";
                                                break;
                                            case 7:
                                                $diaS = "Dom";
                                                break;
                                        }
                                    }



                                    if (is_numeric($det['largo'])) {
                                        $largo = $det['largo'];
                                        $largoancho =  $largo . " " . $det['ancho'];
                                        $metrosLineales = $det['cantidad'] * $largo;
                                    } else {
                                        $largo = $det['metros'];
                                        $largoancho =  $largo . " " . $det['ancho'];
                                        $metrosLineales = $det['cantidad'] * $largo;
                                    }
                        ?>
                                    <tr id="<?php echo $det['idCotizacionDet'] ?>">


                                        <td><?php echo $reg['idCotizacion'] ?></td>
                                        <td><input class="idProducto" type="hidden" value="<?php echo $det['idProducto'] ?>">
                                            <input class="unidad" type="hidden" value="<?php echo $det['unidad'] ?>">
                                            <input class="idUnidad" type="hidden" value="<?php echo $det['idUnidad'] ?>">
                                            <input class="metros" type="hidden" value="<?php echo $det['metros'] ?>">
                                            <input class="largoancho" type="hidden" value="<?php echo $largoancho ?>">
                                            <input class="cantidad" type="hidden" value="<?php echo $det['cantidad'] ?>">
                                            <input class="cantidadProcesada" type="hidden" value="<?php echo $det['cantidadProcesada'] ?>">
                                            <input class="pesoTeorico" type="hidden" value="<?php echo $det['pesoTeorico'] ?>"><a href="#" style="font-size: 14px;" class="btn btn-primary" data-role="llenarProduccion" data-id="<?php echo $det['idCotizacionDet'] ?>">Generar Producto Terminado</a></td>

                                        <td data-target="producto"><?php echo $det['sku'] . " " . $det['producto'] . " " . $det['calibre'] . " " . $det['tipo'] ?></td>
                                        <td><?php echo strtoupper($largoancho) ?></td>
                                        <td><?php echo number_format($det['cantidad'], 2, '.', ',') ?></td>
                                        <td><?php echo strtoupper($det['unidadFactura']) ?></td>
                                        <td><?php echo strtoupper($metrosLineales) ?></td>
                                        <td><?php if ($det['pesoTeorico'] != "") {
                                                echo number_format($det['pesoTeorico'] * $metrosLineales, 2, '.', ',');
                                            } ?></td>
                                        <td><?php echo number_format($det['cantidadProcesada'], 2, '.', ',') ?></td>
                                        <td><?php echo $diaS . " " . $reg['fechaEntrega'] ?></td>
                                        <td><?php if ($reg['semaforo'] != "") {

                                                if ($reg['semaforo'] == "V") { ?>
                                                    <img src="./imagenes/verde.jpg" title="Alta" height="20px">
                                                <?php }

                                                if ($reg['semaforo'] == "A") { ?>
                                                    <img src="./imagenes/amarillo.jpg" title="Media" height="20px">
                                                <?php }
                                                if ($reg['semaforo'] == "R") { ?>
                                                    <img src="./imagenes/rojo.jpg" title="Baja" height="20px">
                                                <?php } ?>


                                            <?php } ?></td>

                                    </tr>
                        <?php }
                            }
                        } ?>

                    </tbody>

                </table>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <h3>Producciones Generadas</h3>
                <table style="width: 100%;" id="ultimasproducciones" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th># </th>
                            <th>P / D</th>
                            <th># Ped</th>
                            <th>Cancelar</th>
                            <th>Producto</th>
                            <th>Medidas</th>
                            <th>Cantidad</th>

                            <th>Unidad</th>
                            <th>Kilos Teórico</th>
                            <th>Kilos Real</th>
                            <th>Usuario</th>
                            <th>Fecha</th>
                            <th>Reimprimir</th>



                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($producciones as $reg) {

                            if (is_numeric($reg['largo'])) {
                                $largo = $reg['largo'];
                                $largoancho =  $largo . " " . $reg['ancho'];
                            } else {
                                $largo = $reg['metros'];
                                $largoancho =  $largo . " " . $reg['ancho'];
                            }
                        ?>
                            <tr id="<?php echo $reg['idProduccion'] ?>">

                                <td><?php
                                    echo $reg['idProduccion'] ?></td>
                                <td><?php if ($reg['tipoProd'] == "D") {
                                        echo "PD";
                                    } else {
                                        echo "PR";
                                    }
                                    ?></td>
                                <td>PE<?php echo $reg['idCotizacion'] ?></td>
                                <td>
                                    <?php if ($reg['idUsuario'] == $_SESSION['idUsr'] && $reg['idRemisionDet'] == null) {

                                        if ($reg['tipoProd'] == "D") {
                                    ?>
                                            <a href="#" class="btn btn-danger" data-role="cancelarProduccion" data-id="D<?php echo $reg['idProduccion'] ?>">Cancelar</a>
                                        <?php } else { ?>
                                            <a href="#" class="btn btn-danger" data-role="cancelarProduccion" data-id="<?php echo $reg['idProduccion'] ?>">Cancelar</a>
                                    <?php }
                                    } ?></td>

                                <td data-target="producto"><?php echo strtoupper($reg['sku'] . " " . $reg['producto']  . $reg['calibre'] . " " . $reg['tipo']); ?></td>
                                <td><?php echo strtoupper($largoancho) ?></td>
                                <td><?php echo number_format($reg['cantidad'], 2, '.', ',') ?></td>

                                <td><?php echo strtoupper($reg['unidadFactura']) ?></td>
                                <td><?php echo number_format($reg['kilos'], 2, '.', ',') ?></td>
                                <td><?php echo number_format($reg['kilosUsuario'], 2, '.', ',') ?></td>
                                <td><?php echo strtoupper($reg['usuario']) ?></td>
                                <td><?php echo strtoupper($reg['fecha']) ?></td>

                                <td><a href="#" class="btn btn-warning" data-role="reimprimeProduccion" data-id="<?php echo $reg['idProduccion'] ?>">Reimprimir</a></td>

                            </tr>
                        <?php

                        } ?>

                    </tbody>

                </table>
            </div>
        </div>


        <!-- hasta aqui llega-->

    </div>
    <?php include_once('footer.php') ?>
</div>
<script>
    $(document).ready(function() {
        $('#producciones').DataTable({
            "order": [
                [0, "desc"]
            ],
            "lengthMenu": [
                [-1, 100, 200],
                ["Todos", 100, 200]
            ]
        });
    });

    $(document).ready(function() {
      

        $('#ultimasproducciones').DataTable({
            scrollY: '35vh',
            scrollCollapse: true,
            paging: false,
            "order": [
                [11, "desc"]
            ]
            
        });
    });
</script>