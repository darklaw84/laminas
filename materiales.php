<?php

include_once './controllers/OrdenesController.php';
if (!isset($_SESSION['nombreUsr'])) {
    echo "<script>window.setTimeout(function() { window.location = 'login.php' }, 10);</script>";
}
$controller = new OrdenesController();


$respuesta = $controller->obtenerMateriales();
$ordenes = $respuesta->registros;







?>




<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-ticket icon-gradient bg-ripe-malin"></i>
                    </div>
                    <div>Materiales
                        <div class="page-title-subheading">.</div>
                    </div>
                </div>

            </div>
        </div>

        <!-- aqui va el contenido de la página -->

        <div class="row mt-2 mb-2">
            <div class="col-md-10">
            </div>

            <div class="col-md-2">
                <a href="generarExcelMaterialesDetalle.php" target="_blank" class="btn btn-success"> Exportar Detalle XLSX
                </a>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <table style="width: 100%;" id="materiales" class="table table-hover table-striped table-bordered ">
                    <thead>
                        <tr>
                            <th># Orden</th>
                            <th>Producto</th>
                            <th>Calibre</th>
                            <th>Tipo</th>
                            <th>Unidad</th>
                            <th>Peso Teórico</th>

                            <th>Recibido</th>
                            <th>Fecha Recepción</th>
                            <th>Recepciones</th>
                            <th>Usuario</th>

                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($ordenes as $ord) {
                            foreach ($ord['orden'][0]['productos'] as $det) {
                                $orde = $ord['orden'][0];
                                if (is_numeric($det['largo'])) {
                                    $largo = $det['largo'];
                                } else {
                                    $largo = "";
                                }

                                if (is_numeric($det['largo'])) {
                                    $metrosLineales = $largo * $det['cantidad'];
                                } else {
                                    $metrosLineales = 0;
                                }

                        ?>
                                <tr>

                                    <td><?php echo $orde['idOrden'] ?></td>
                                    <td><?php echo  strtoupper($det['sku'] . " " . $det['producto'] . " " . $largo . " " . $det['ancho']) ?></td>
                                    <td><?php echo strtoupper($det['calibre']) ?></td>
                                    <td><?php echo strtoupper($det['tipo']) ?></td>
                                    <td><?php echo strtoupper($det['unidad']) ?></td>
                                    <td><?php if ($det['idUnidad'] == 3) {
                                            echo number_format($det['cantidad'], 2, '.', ',');
                                        } else if ($det['idUnidad'] == 1) {
                                            if ($det['prodPesoTeorico'] > 0) {
                                                echo number_format($det['prodPesoTeorico'] * $det['cantidad'], 2, '.', ',');
                                            } else {
                                                echo number_format($det['pesoTeorico'] * $det['cantidad'], 2, '.', ',');
                                            }
                                        } else {
                                            if ($metrosLineales == 0) {
                                                echo number_format($det['pesoTeorico'] * $det['cantidad'], 2, '.', ',');
                                            } else {
                                                echo number_format($det['pesoTeorico'] * $metrosLineales, 2, '.', ',');
                                            }
                                        }  ?></td>
                                    <td><?php if ($det['recibido'] == "1") {
                                            echo "."; ?><img src="./imagenes/correcto.png" style="height: 20px"><?php } else { ?><img src="./imagenes/incorrecto.png" style="height: 20px"><?php } ?></td>
                                    <td><?php echo $det['fechaUltimaRecepcion']; ?></td>
                                    <td><button type="button" data-toggle="popover-custom-content" rel="popover-focus" popover-id="<?php echo $det['idOrdenCompraDet'] ?>" class="mr-2 mb-2 btn btn-dark"><?php echo count($det['recepciones'], COUNT_NORMAL) ?> Recepciones</button></td>
                                    <td><?php echo strtoupper($orde['usuario']) ?></td>
                                </tr>
                        <?php }
                        } ?>


                    </tbody>

                </table>
            </div>
        </div>




        <?php foreach ($ordenes as $ord) {
            foreach ($ord['orden'][0]['productos'] as $reg) { ?>
                <div id="popover-content-<?php echo  $reg['idOrdenCompraDet'] ?>" class="d-none">
                    <div class="dropdown-menu-header">
                        <div class="dropdown-menu-header-inner bg-primary">
                            <div class="menu-header-image opacity-5" style="background-image: url('assets/images/dropdown-header/abstract2.jpg');"></div>
                            <div class="menu-header-content">

                                <h5 class="menu-header-title">Recepciones</h5>

                            </div>
                        </div>
                    </div>
                    <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered ">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Producto</th>
                                <th>Unidad</th>
                                <th>Cant Ordenada</th>
                                <th>Cant Recibida</th>
                                <th>Usuario</th>
                                <th>Fecha</th>
                                <th>Almacen</th>
                                <th>Reimprimir</th>


                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($reg['recepciones'] as $ins) {

                                $ordenada = 0;
                                $recibida = 0;
                                if ($ins['idUnidad'] == 3) {
                                    $ordenada = $reg['pesoTeorico'];
                                    $recibida = $ins['peso'];
                                } else if ($ins['idUnidad'] == 2) {
                                    $ordenada = $reg['cantidad'];
                                    $recibida = $ins['cantidad'];
                                } else {
                                    $ordenada = $reg['cantidad'];
                                    $recibida = $ins['cantidad'];
                                }

                            ?>
                                <tr id="R<?php echo $ins['idRecepcion'] ?>">
                                    <td>R<?php echo $ins['idRecepcion'] ?></td>
                                    <td><?php echo $ins['producto'] . " " . $ins['calibre'] . " " . $ins['tipo'] ?></td>
                                    <td><?php echo $ins['unidad'] ?></td>
                                    <td><?php echo $ordenada ?></td>
                                    <td><?php echo $recibida ?></td>

                                    <td><?php echo $ins['usuarioRecibe'] ?></td>
                                    <td><?php echo $ins['fechaRecibe'] ?></td>
                                    <td><?php echo $ins['almacen'] ?></td>
                                    <td><a href="#" class="btn btn-warning" data-role="reimprimeRecepcion" data-id="<?php echo $ins['idRecepcion'] ?>">Reimprimir</a></td>

                                    <td><a href="#" class="btn btn-warning" data-role="eliminaRecepcion" data-id="<?php echo $ins['idRecepcion'] ?>">Eliminar</a>
                                </tr>
                            <?php } ?>


                        </tbody>

                    </table>
                </div>
        <?php }
        } ?>


        <!-- hasta aqui llega-->

    </div>
    <?php include_once('footer.php') ?>
</div>
<script>
    $(document).ready(function() {
        $('#materiales').DataTable({
            "order": [
                [0, "desc"]
            ],
            "lengthMenu": [
                [-1, 100, 200],
                ["Todos", 100, 200]
            ]
        });
    });
</script>