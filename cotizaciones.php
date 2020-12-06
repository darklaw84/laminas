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

if ($entro != "") {

    $idCliente = $_POST['idCliente'];

    $respuesta = $controller->agregarCotizacion(
        $idCliente,
        $_SESSION['nombreUsr'],
        $_SESSION['idUsr']
    );

    if (!$respuesta->exito) {
        $mensajeEnviar = $respuesta->mensaje;
    } else {
        echo "<script>window.setTimeout(function() { window.location = 'index.php?p=cotizacionesact&idCotizacion=" . $respuesta->valor . "' }, 10);</script>";
    }
}



if ($_SESSION['verCotizaciones']) {
    $respuesta = $controller->obtenerCotizaciones("C", $_SESSION['idUsr']);
    $registros = $respuesta->registros;
} else {
    $respuesta = $controller->obtenerCotizaciones("PE", $_SESSION['idUsr']);
    $registros = $respuesta->registros;
}



$respClientes = $contcat->obtenerclientes();
$clientes = $respClientes->registros;





?>




<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="col-md-3">


                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="pe-7s-piggy icon-gradient bg-ripe-malin"></i>
                        </div>
                        <div>Cotizaciones
                            <div class="page-title-subheading">.</div>
                        </div>
                    </div>
                </div>

                <form class="form-row col-md-12" action="index.php?p=cotizaciones" method="POST">

                    <div class="col-md-1">

                    </div>
                    <div class="col-md-6">
                        <label for="idProdH">Clientes</label>
                        <div>
                            <select id="idCliente" name="idCliente" class="multiselect-dropdown form-control">
                                <?php
                                if (isset($clientes)) {
                                    foreach ($clientes as $ins) {

                                        echo '<option value="' . $ins['idCliente'] . '"  >' . strtoupper($ins['cliente']) . '</option>';
                                    }
                                } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <label for="">&nbsp; </label>
                        <div>
                            <input type="hidden" value="1" name="entro">
                            <button type="submit" class="btn btn-primary">Nueva Cotización</button> </div>
                    </div>




                </form>
            </div>


        </div>

        <!-- aqui va el contenido de la página -->

        <div class="main-card mb-3 card">
            <div class="card-body">
                <table style="width: 100%;" id="cotizaciones" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th># Cot.</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Total</th>
                            <th>Partidas</th>
                            <th>Pedido</th>
                            <?php if ($_SESSION['eliminaCotizacion'] == "1") { ?>
                                <th>Eliminar</th>
                            <?php } ?>
                            <th>Usuario</th>


                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $reg) {

                            if ($reg['pedido'] != "1") {

                        ?>
                                <tr id="<?php echo $reg['idCotizacion'] ?>">


                                    <td><?php echo $reg['idCotizacion'] ?></td>
                                    <td><?php echo strtoupper($reg['cliente']) ?></td>
                                    <td><?php echo $reg['fecha'] ?></td>

                                    <td><?php if ($reg['costoEnvio'] == "") {
                                            $reg['costoEnvio'] = 0;
                                        }
                                        echo "$ " . number_format($reg['grantotal'] + $reg['costoEnvio'], 2, '.', ',') ?></td>
                                    <td><button type="button" data-toggle="popover-custom-content" rel="popover-focus" popover-id="<?php echo $reg['idCotizacion'] ?>" class="mr-2 mb-2 btn btn-dark"><?php echo count($reg['productos'], COUNT_NORMAL) ?> Partidas</button></td>
                                    <td><img class="imgEntrada" src="imagenes/correcto.png" height="20px" style="display: <?php if ($reg['pedido'] == "1") {
                                                                                                                                echo "block";
                                                                                                                            } else {
                                                                                                                                echo "none";
                                                                                                                            } ?>;">
                                    </td>

                                    <td><?php if ($_SESSION['eliminaCotizacion'] == "1" && $reg['pedido'] != "1") { ?><a href="#" class="btn btn-warning" data-role="eliminaCotizacion" data-id="<?php echo $reg['idCotizacion'] ?>">Eliminar</a>
                                        <?php } ?></td>

                                    <td><?php echo strtoupper($reg['usuario']) ?></td>
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
                            <a class="btn btn-warning" href="index.php?p=cotizacionesact&idCotizacion=<?php echo $reg['idCotizacion'] ?>">Actualizar / Consultar</a>
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
                            <th>Precio UM</th>
                            <th>Metros</th>
                            <th>Precio Unitario</th>
                            <th>Monto</th>
                            <th>Metros Lineales</th>
                            <th>Peso Teórico</th>


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
                                <?php if ($reg['metros'] > 0) {

                                    $metrosLineales = $reg['metros'] * $reg['cantidad'];
                                } else {
                                    if (is_numeric($reg['largo'])) {
                                        $metrosLineales = $largo * $reg['cantidad'];
                                    } else {
                                        $metrosLineales = 0;
                                    }
                                }

                                $totalPartida = $reg['preciounitario'] * $reg['cantidad']; ?>

                                <td><?php echo strtoupper($reg['sku'] . " " . $reg['producto'] . " " . $largo . " " . $reg['ancho'] . " " . $reg['calibre'] . " " . $reg['tipo']);  ?></td>
                                <td><?php echo $reg['unidad'] ?></td>
                                <td><?php echo $reg['cantidad'] ?></td>
                                <td><?php echo "$ " . number_format($reg['precioUM'], 2, '.', ',') ?></td>
                                <td><?php echo $reg['metros'] ?></td>
                                <td><?php echo "$ " . number_format($reg['preciounitario'], 2, '.', ',') ?></td>
                                <td><?php echo "$ " . number_format($totalPartida, 2, '.', ',') ?></td>
                                <td><?php echo number_format($metrosLineales, 2, '.', ',') ?></td>
                                <td><?php if ($reg['idUnidad'] == 3) {
                                        echo number_format($reg['cantidad'], 2, '.', ',');
                                    } else {
                                        echo number_format($reg['pesoTeorico'] * $reg['cantidad'], 2, '.', ',');
                                    } ?></td>


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
        $('#cotizaciones').DataTable({
            "order": [
                [0, "desc"]
            ],
            "lengthMenu": [
                [100, 200, -1],
                [100, 200, "Todos"]
            ]
        });
    });
</script>