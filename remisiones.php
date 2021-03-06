<?php

include_once './controllers/CotizacionController.php';
if (!isset($_SESSION['nombreUsr'])) {
    echo "<script>window.setTimeout(function() { window.location = 'login.php' }, 10);</script>";
}
$controller = new CotizacionController();



$respuesta = $controller->obtenerRemisiones();
$registros = $respuesta->registros;





?>




<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-compass icon-gradient bg-ripe-malin"></i>
                    </div>
                    <div>Remisiones
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
                <a href="generarExcelRemisiones.php" target="_blank" class="btn btn-success"> Exportar XLSX
                </a>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <table style="width: 100%;" id="remisiones" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th># Remision</th>
                            <th># Pedido</th>
                            <th>Cliente</th>
                            <th>Usuario</th>
                            <th>Vendedor</th>
                            <th>Fecha</th>
                            <th>Chofer</th>
                            <th>Partidas</th>
                            <th>Imprimir</th>
                            <th>Cancelar</th>


                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $reg) { ?>
                            <tr>


                                <td><?php echo $reg['idRemision'] ?></td>
                                <td>PE<?php echo strtoupper($reg['idCotizacion']) ?></td>
                                <td><?php echo $reg['cliente'] ?></td>
                                <td><?php echo strtoupper($reg['usuario']) ?></td>
                                <td><?php echo strtoupper($reg['vendedor']) ?></td>
                                <td><?php echo $reg['fecha'] ?></td>
                                <td><?php echo $reg['chofer'] ?></td>


                                <td><button type="button" data-toggle="popover-custom-content" rel="popover-focus" popover-id="<?php echo $reg['idRemision'] ?>" class="mr-2 mb-2 btn btn-dark"><?php echo count($reg['detalle'], COUNT_NORMAL) ?> Partidas</button>
                                <td> <a href="#" class="btn btn-warning" data-role="imprimeRemision" data-id="R<?php echo $reg['idRemision'] ?>">Imprimir Remisión</a>
                                    <a href="#" class="btn btn-primary" data-role="imprimeCartaPorte" data-id="<?php echo $reg['idRemision'] ?>">Carta Porte</a></td>
                                <td>
                                    <?php if ($_SESSION['cancelarRemisiones'] == "1") { ?>
                                        <a class="btn btn-warning" href="#" data-role="cancelarRemision" data-id="<?php echo $reg['idRemision'] ?>">Cancelar</a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>

                    </tbody>

                </table>
            </div>
        </div>

        <?php foreach ($registros as $reg) { ?>
            <div id="popover-content-<?php echo  $reg['idRemision'] ?>" class="d-none">
                <div class="dropdown-menu-header">
                    <div class="dropdown-menu-header-inner bg-primary">
                        <div class="menu-header-image opacity-5" style="background-image: url('assets/images/dropdown-header/abstract2.jpg');"></div>
                        <div class="menu-header-content">

                            <h5 class="menu-header-title">Partidas</h5>

                        </div>
                    </div>
                </div>
                <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered ">
                    <thead>
                        <tr>
                            <th>Cantidad</th>
                            <th>Unidad</th>
                            
                            <th>Producto</th>
                            <th>Metros</th>


                            <th>Usuario</th>
                            <th>Fecha</th>
                            <th>Almacen</th>



                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($reg['detalle'] as $reg) {

                            if (is_numeric($reg['largo'])) {
                                $largo = $reg['largo'];
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
                                <td><?php echo number_format($reg['cantidad'], 2, '.', ',') ?></td>
                                <td><?php echo $reg['unidadFactura'] ?></td>
                                
                                <td><?php echo strtoupper($reg['sku'] . " " . $reg['producto'] . " " . $largo . " " . $reg['ancho'] . " " . $reg['calibre'] . " " . $reg['tipo']);  ?></td>
                                <td><?php echo $reg['metros'] ?></td>


                                <td><?php echo $reg['usuario'] ?></td>
                                <td><?php echo $reg['fecha'] ?></td>
                                <td><?php echo $reg['almacen'] ?></td>



                            </tr>
                        <?php } ?>


                    </tbody>

                </table>
            </div>
        <?php } ?>


        <!-- hasta aqui llega-->

    </div>
    <?php include_once('footer.php') ?>
</div>
<script>
    $(document).ready(function() {
        $('#remisiones').DataTable({
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