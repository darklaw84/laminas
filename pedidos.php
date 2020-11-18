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


if (isset($_GET['idPedido'])) {
    $idPedido = $_GET['idPedido'];
    $activo = $_GET['activo'];
    if ($idPedido != "") {
        $controller->togglePedido($idPedido, $activo);
    }
}



$respuesta = $controller->obtenerCotizaciones("P", 0);
$registros = $respuesta->registros;



$respClientes = $contcat->obtenerclientes();
$clientes = $respClientes->registros;





?>




<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-cart icon-gradient bg-ripe-malin"></i>
                    </div>
                    <div>Pedidos
                        <div class="page-title-subheading">.</div>
                    </div>
                </div>



            </div>


        </div>

        <!-- aqui va el contenido de la página -->

        <div class="main-card mb-3 card">
            <div class="card-body">
                <table style="width: 100%;" id="pedidos" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th># Ped.</th>
                            <th>Cliente</th>
                            <th>Usuario</th>
                            <th>Fecha Entrega</th>
                            <th>Forma Pago</th>
                            <th>Total</th>
                            <th>Partidas</th>
                            <th>Fase</th>
                            <th>Prioridad</th>


                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $reg) {

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

                        ?>
                            <tr id="<?php echo $reg['idCotizacion']; ?>">


                                <td>PE<?php echo $reg['idCotizacion'] ?></td>
                                <td><?php echo strtoupper($reg['cliente']) ?></td>
                                <td><?php echo strtoupper($reg['usuario']) ?></td>
                                <td><?php echo $diaS . " " . $reg['fechaEntrega'] ?></td>
                                <td><?php echo $reg['formapago'] ?></td>

                                <td><?php echo "$ " . number_format($reg['grantotal'], 2, '.', ',') ?></td>
                                <td><button type="button" data-toggle="popover-custom-content" rel="popover-focus" popover-id="<?php echo $reg['idCotizacion'] ?>" class="mr-2 mb-2 btn btn-dark"><?php echo count($reg['productos'], COUNT_NORMAL) ?> Partidas</button>
                                    <?php if ($reg['terminada']) { ?><img src="./imagenes/correcto.png" style="height: 20px"><?php } ?></td>
                                <td>
                                    
                                    <?php if(isset($_SESSION['autorizarPedidos']) && $_SESSION['autorizarPedidos']=="1"){ ?><a href="index.php?p=pedidos&idPedido=<?php echo $reg['idCotizacion'] ?>&activo=<?php if ($reg['produccion'] == 1) {
                                                                                                                        echo "0";
                                                                                                                    } else {
                                                                                                                        echo "1";
                                                                                                                    } ?>" style="font-size: 10px;" <?php if ($reg['produccion'] == 0) {
                                                                                                                                                        echo "Class='btn btn-primary'";
                                                                                                                                                    } else {
                                                                                                                                                        echo "Class='btn btn-danger'";
                                                                                                                                                    } ?>><?php if ($reg['produccion'] == 0) {
                                                                                                                                                                echo " Autorizar Producción";
                                                                                                                                                            } else {
                                                                                                                                                                echo "Cancelar Producción";
                                                                                                                                                            } ?></a><?php }?></td>
                                <td><a href="#" title="Baja" data-role="ponerVerde" data-id="<?php echo $reg['idCotizacion'] ?>">
                                        <img class="verde" src="./imagenes/<?php if ($reg['semaforo'] == "V") {
                                                                    echo "verde";
                                                                } else {
                                                                    echo "verdeg";
                                                                } ?>.jpg" height="20px"></a>
                                    <a href="#" title="Media" data-role="ponerAmarillo" data-id="<?php echo $reg['idCotizacion'] ?>">
                                        <img class="amarillo" src="./imagenes/<?php if ($reg['semaforo'] == "A") {
                                                                    echo "amarillo";
                                                                } else {
                                                                    echo "amarillog";
                                                                } ?>.jpg" height="20px"></a>
                                    <a href="#" title="Alta" data-role="ponerRojo" data-id="<?php echo $reg['idCotizacion'] ?>">
                                        <img class="rojo" src="./imagenes/<?php if ($reg['semaforo'] == "R") {
                                                                    echo "rojo";
                                                                } else {
                                                                    echo "rojog";
                                                                } ?>.jpg" height="20px"></a></td>
                            </tr>
                        <?php } ?>

                    </tbody>

                </table>
            </div>
        </div>

        <?php foreach ($registros as $reg) { ?>
            <div id="popover-content-<?php echo  $reg['idCotizacion'] ?>" class="d-none">
                <div class="dropdown-menu-header">
                    <div class="dropdown-menu-header-inner bg-primary">
                        <div class="menu-header-image opacity-5" style="background-image: url('assets/images/dropdown-header/abstract2.jpg');"></div>
                        <div class="menu-header-content">
                            <a class="btn btn-warning" href="index.php?p=pedidosact&idCotizacion=<?php echo $reg['idCotizacion'] ?>">Actualizar / Consultar</a>
                            <h5 class="menu-header-title">Partidas</h5>

                        </div>
                    </div>
                </div>
                <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered ">
                    <thead>
                        <tr>

                            <th>Producto</th>
                            <th>Unidad</th>
                            <th>Cantidad</th>
                            <th>$ x U. de M.</th>
                            <th>Metros</th>
                            <th>$ x Pieza</th>
                            <th>Monto</th>
                            <th>Prod</th>


                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($reg['productos'] as $reg) {
                            
                            if (is_numeric($reg['largo'])) {
                                $largo = $reg['largo'];
                                
                            } else {
                                $largo = "";
                            }?>
                            <tr>
                                <?php if ($reg['metros'] > 0) {
                                    $totalPartida = $reg['metros'] * $reg['preciounitario'] * $reg['cantidad'];
                                    $precioPorPieza = $totalPartida / $reg['cantidad'];
                                } else {
                                    $totalPartida = $reg['preciounitario'] * $reg['cantidad'];
                                    $precioPorPieza = "0.00";
                                } ?>

                                <td><?php echo strtoupper($reg['sku'] . " " . $reg['producto'] . " " . $largo . " " . $reg['ancho'] . " " . $reg['calibre'] . " " . $reg['tipo']);  ?></td>
                                <td><?php echo $reg['unidad'] ?></td>
                                <td><?php echo $reg['cantidad'] ?></td>
                                <td><?php echo "$ " . number_format($reg['preciounitario'], 2, '.', ',') ?></td>
                                <td><?php echo $reg['metros'] ?></td>
                                <td><?php echo "$ " . number_format($precioPorPieza, 2, '.', ',') ?></td>
                                <td><?php echo "$ " . number_format($totalPartida, 2, '.', ',') ?></td>
                                <td><?php if ($reg['partidaTerminada']) { ?><img src="./imagenes/correcto.png" style="height: 20px"><?php }  ?></td>


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
        $('#pedidos').DataTable({
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