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





$respuesta = $controller->obtenerCotizaciones("P", 0, 0);
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
                <h2>Pedidos Activos</h2>
                <table style="width: 100%;" id="pedidos" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th># Ped.</th>
                            <th>Cliente</th>
                            <th>Usuario</th>
                            <th>Fecha Entrega</th>
                            <th>Forma Pago</th>
                            <th>Total</th>
                            <th>Abonos</th>
                            <th>Partidas</th>
                            <th>Fase</th>
                            <th>Prioridad</th>
                            <th>Pagado</th>
                            <th>Cancelar</th>


                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $reg) {




                            $diaS = "";
                            $tieneProducciones = false;
                            $todasConRemisiones = true;
                            $tieneRemisiones = false;
                            foreach ($reg['productos'] as $prod) {
                                if (!$prod['todasConRemisiones']) {
                                    $todasConRemisiones = false;
                                }
                                if ($prod['tieneRemision']) {
                                    $tieneRemisiones = true;
                                }
                                if (count($prod['producciones']) > 0) {
                                    $tieneProducciones = true;
                                    break;
                                }
                            }

                            if (!$todasConRemisiones && $reg['cancelado'] != "1") {

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


                                    <td><?php echo $reg['idCotizacion'] ?></td>
                                    <td><?php echo strtoupper($reg['cliente']) ?></td>
                                    <td><?php echo strtoupper($reg['usuario']) ?></td>
                                    <td><?php echo $diaS . " " . $reg['fechaEntrega'] ?></td>
                                    <td><?php echo $reg['formapago'] ?></td>

                                    <td><?php if ($reg['costoEnvio'] == "") {
                                            $reg['costoEnvio'] = 0;
                                        }
                                        echo "$ " . number_format($reg['grantotal'] + $reg['costoEnvio'], 2, '.', ',') ?></td>
                                    <td><button type="button" data-toggle="popover-custom-content" rel="popover-focus" popover-id="AB<?php echo $reg['idCotizacion'] ?>" class="mr-2 mb-2 btn btn-warning"><?php echo "$ " . number_format($reg['totalAbonos'], 2, '.', ',') ?> </button></td>
                                    <td><button type="button" data-toggle="popover-custom-content" rel="popover-focus" popover-id="<?php echo $reg['idCotizacion'] ?>" class="mr-2 mb-2 btn btn-dark"><?php echo count($reg['productos'], COUNT_NORMAL) ?> Partidas</button>
                                        <?php if ($reg['terminada']) { ?><img src="./imagenes/correcto.png" style="height: 20px"><?php } ?></td>
                                    <td>


                                        <?php if (!$tieneProducciones) {
                                            if (isset($_SESSION['autorizarPedidos']) && $_SESSION['autorizarPedidos'] == "1") { ?><a href="#" data-role="autorizarProduccion" data-id="<?php if ($reg['produccion'] == 1) {
                                                                                                                                                                                            echo $reg['idCotizacion'] . "-1";
                                                                                                                                                                                        } else {
                                                                                                                                                                                            echo $reg['idCotizacion'] . "-0";
                                                                                                                                                                                        } ?>" style="font-size: 10px;" <?php if ($reg['produccion'] == 0) {
                                                                                                                                                                                                                            echo "Class='btn btn-primary'";
                                                                                                                                                                                                                        } else {
                                                                                                                                                                                                                            echo "Class='btn btn-danger'";
                                                                                                                                                                                                                        } ?>><?php if ($reg['produccion'] == 0) {
                                                                                                                                                                                                                                    echo " Autorizar Producción";
                                                                                                                                                                                                                                } else {
                                                                                                                                                                                                                                    echo "Cancelar Producción";
                                                                                                                                                                                                                                } ?></a><?php }
                                                                                                                                                                                                                                } else {
                                                                                                                                                                                                                                    if ($todasConRemisiones) {
                                                                                                                                                                                                                                        echo "Entregado";
                                                                                                                                                                                                                                    } else if ($tieneRemisiones) {
                                                                                                                                                                                                                                        echo "Parcialmente Entregado";
                                                                                                                                                                                                                                    } else {
                                                                                                                                                                                                                                        echo  "Con Producciones";
                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                } ?></td>
                                    <td><a href="#" title="Alta" data-role="ponerVerde" data-id="<?php echo $reg['idCotizacion'] ?>">
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
                                        <a href="#" title="Baja" data-role="ponerRojo" data-id="<?php echo $reg['idCotizacion'] ?>">
                                            <img class="rojo" src="./imagenes/<?php if ($reg['semaforo'] == "R") {
                                                                                    echo "rojo";
                                                                                } else {
                                                                                    echo "rojog";
                                                                                } ?>.jpg" height="20px"></a></td>
                                    <td><?php if ($reg['pedidoPagado']) {
                                            echo "Pagado";
                                        } else {
                                            echo "Pendiente de pagar";
                                        } ?></td>
                                    <td>
                                        <?php
                                        if (!$tieneProducciones && ($reg['idUsuario'] == $_SESSION['idUsr'] || $_SESSION['cancelarPedidos'] == "1")) {
                                        ?>
                                            <a href="#" class="btn btn-warning" data-role="cancelarPedido" data-id="<?php echo $reg['idCotizacion'] ?>">Cancelar</a>
                                        <?php } ?>
                                    </td>

                                </tr>
                        <?php }
                        } ?>

                    </tbody>

                </table>
            </div>
        </div>


        <div class="main-card mb-3 card">
            <div class="card-body">
                <h2>Pedidos Entregados</h2>
                <table style="width: 100%;" id="pedidosentregados" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th># Ped.</th>
                            <th>Cliente</th>
                            <th>Usuario</th>
                            <th>Fecha Entrega</th>
                            <th>Forma Pago</th>
                            <th>Total</th>
                            <th>Abonos</th>
                            <th>Partidas</th>
                            <th>Fase</th>
                            <th>Prioridad</th>
                            <th>Pagado</th>


                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $reg) {

                            $diaS = "";
                            $tieneProducciones = false;
                            $todasConRemisiones = true;
                            $tieneRemisiones = false;
                            foreach ($reg['productos'] as $prod) {
                                if (!$prod['todasConRemisiones']) {
                                    $todasConRemisiones = false;
                                }
                                if ($prod['tieneRemision']) {
                                    $tieneRemisiones = true;
                                }
                                if (count($prod['producciones']) > 0) {
                                    $tieneProducciones = true;
                                    break;
                                }
                            }

                            if ($todasConRemisiones || $reg['cancelado'] == "1") {



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


                                    <td><?php echo $reg['idCotizacion'] ?></td>
                                    <td><?php echo strtoupper($reg['cliente']) ?></td>
                                    <td><?php echo strtoupper($reg['usuario']) ?></td>
                                    <td><?php
                                        if ($todasConRemisiones) {
                                            echo $reg['ultimaFechaEntrega'];
                                        } else {

                                            echo $diaS . " " . $reg['fechaEntrega'];
                                        } ?></td>
                                    <td><?php echo $reg['formapago'] ?></td>

                                    <td><?php if ($reg['costoEnvio'] == "") {
                                            $reg['costoEnvio'] = 0;
                                        }
                                        echo "$ " . number_format($reg['grantotal'] + $reg['costoEnvio'], 2, '.', ',') ?></td>
                                    <td><button type="button" data-toggle="popover-custom-content" rel="popover-focus" popover-id="AB<?php echo $reg['idCotizacion'] ?>" class="mr-2 mb-2 btn btn-warning"><?php echo "$ " . number_format($reg['totalAbonos'], 2, '.', ',') ?> </button></td>
                                    <td><button type="button" data-toggle="popover-custom-content" rel="popover-focus" popover-id="<?php echo $reg['idCotizacion'] ?>" class="mr-2 mb-2 btn btn-dark"><?php echo count($reg['productos'], COUNT_NORMAL) ?> Partidas</button>
                                        <?php if ($reg['terminada']) { ?><img src="./imagenes/correcto.png" style="height: 20px"><?php } ?></td>
                                    <td>


                                        <?php if ($reg['cancelado']) {
                                            echo "Cancelado";
                                        } else {
                                            echo "Entregado";
                                        } ?></td>
                                    <td><a href="#" title="Alta" data-role="ponerVerde" data-id="<?php echo $reg['idCotizacion'] ?>">
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
                                        <a href="#" title="Baja" data-role="ponerRojo" data-id="<?php echo $reg['idCotizacion'] ?>">
                                            <img class="rojo" src="./imagenes/<?php if ($reg['semaforo'] == "R") {
                                                                                    echo "rojo";
                                                                                } else {
                                                                                    echo "rojog";
                                                                                } ?>.jpg" height="20px"></a></td>
                                    <td><?php if ($reg['pedidoPagado']) {
                                            echo "Pagado";
                                        } else {
                                            if ($reg['cancelado']) {
                                                echo "";
                                            } else {
                                                echo "Pendiente de pagar";
                                            }
                                        } ?></td>
                                </tr>
                        <?php }
                        } ?>

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
                            } ?>
                            <tr>
                                <?php $totalPartida =  $reg['preciounitario'] * $reg['cantidad']; ?>

                                <td><?php echo strtoupper($reg['sku'] . " " . $reg['producto'] . " " . $largo . " " . $reg['ancho'] . " " . $reg['calibre'] . " " . $reg['tipo']);  ?></td>
                                <td><?php echo $reg['unidad'] ?></td>
                                <td><?php echo $reg['cantidad'] ?></td>
                                <td><?php echo "$ " . number_format($reg['precioUM'], 2, '.', ',') ?></td>
                                <td><?php echo $reg['metros'] ?></td>
                                <td><?php echo "$ " . number_format($reg['preciounitario'], 2, '.', ',')  ?></td>
                                <td><?php echo "$ " . number_format($totalPartida, 2, '.', ',') ?></td>
                                <td><?php if ($reg['partidaTerminada']) { ?><img src="./imagenes/correcto.png" style="height: 20px"><?php }  ?></td>


                            </tr>
                        <?php } ?>


                    </tbody>

                </table>
            </div>
        <?php } ?>
        <?php foreach ($registros as $reg) { ?>
            <div id="popover-content-AB<?php echo  $reg['idCotizacion'] ?>" class="d-none">
                <div class="dropdown-menu-header">
                    <div class="dropdown-menu-header-inner bg-primary">
                        <div class="menu-header-image opacity-5" style="background-image: url('assets/images/dropdown-header/abstract2.jpg');"></div>
                        <div class="menu-header-content">
                            <?php if ($_SESSION['agregarAbonos'] == "1") { ?>
                                <a href="#" title="Abonar a Pedido" class="btn btn-warning" data-role="abonarPedido" data-id="<?php echo $reg['idCotizacion'] ?>">
                                    Abonar</a><?php } ?>
                            <h5 class="menu-header-title">Abonos</h5>

                        </div>
                    </div>
                </div>
                <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered ">
                    <thead>
                        <tr>

                            <th>#</th>
                            <th>Monto</th>
                            <th>Forma Pago</th>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Cancelar</th>
                            <th>Usuario Cancela</th>
                            <th>Fecha Cancela</th>



                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($reg['abonos'] as $reg) {

                        ?>
                            <tr>
                                <td>A<?php echo $reg['idAbono'];  ?></td>
                                <td><?php echo "$ " . number_format($reg['monto'], 2, '.', ',') ?></td>
                                <td><?php echo $reg['formapago'] ?></td>
                                <td><?php echo $reg['fecha'] ?></td>
                                <td><?php echo $reg['usuario'] ?></td>
                                <td> <?php if ($reg['usuarioCancela'] == "") {  ?> <a href="#" title="Cancelar Abono" class="btn btn-warning" data-role="cancelarAbono" data-id="<?php echo $reg['idAbono'] ?>">Cancelar</a><?php } else {
                                                                                                                                                                                                                            echo "Cancelado";
                                                                                                                                                                                                                        } ?></td>
                                <td><?php echo $reg['usuarioCancela'] ?></td>
                                <td><?php echo $reg['fechaCancela'] ?></td>
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

    $(document).ready(function() {
        $('#pedidosentregados').DataTable({
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