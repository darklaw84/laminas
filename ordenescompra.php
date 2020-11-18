<?php
include_once './controllers/CatalogosController.php';
include_once './controllers/OrdenesController.php';
if (!isset($_SESSION['nombreUsr'])) {
    echo "<script>window.setTimeout(function() { window.location = 'login.php' }, 10);</script>";
}
$controller = new OrdenesController();
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

    $idProveedor = $_POST['idProveedor'];

    $respuesta = $controller->agregarOrden(
        $idProveedor,
        $_SESSION['nombreUsr']
    );

    if (!$respuesta->exito) {
        $mensajeEnviar = $respuesta->mensaje;
    } else {
        echo "<script>window.setTimeout(function() { window.location = 'index.php?p=ordenescompraact&idOrden=" . $respuesta->valor . "' }, 10);</script>";
    }
}




$respuesta = $controller->obtenerOrdenes();
$registros = $respuesta->registros;



$respClientes = $contcat->obtenerProveedores();
$clientes = $respClientes->registros;





?>




<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
            <div class="col-md-4"><div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-wallet icon-gradient bg-ripe-malin"></i>
                    </div>
                    <div>Órdenes de Compra
                       
                    </div>
                </div>
            </div>


                <form class="form-row col-md-12" action="index.php?p=ordenescompra" method="POST">

                    <div class="col-md-1">

                    </div>
                    <div class="col-md-4">
                        <label for="idProdH">Proveedores</label>
                        <div>
                            <select id="idProveedor" name="idProveedor" class="multiselect-dropdown form-control">
                                <?php
                                if (isset($clientes)) {
                                    foreach ($clientes as $ins) {

                                        echo '<option value="' . $ins['idProveedor'] . '"  >' . strtoupper($ins['proveedor']) . '</option>';
                                    }
                                } ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <label for="">&nbsp; </label>
                        <div>
                            <input type="hidden" value="1" name="entro">
                            <button type="submit" class="btn btn-primary">Nueva Orden de Compra</button> </div>
                    </div>




                </form>
            </div>


        </div>

        <!-- aqui va el contenido de la página -->

        <div class="main-card mb-3 card">
            <div class="card-body">
                <table style="width: 100%;" id="ordenes" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Proveedor</th>
                            <th>Fecha</th>
                            <th>Fecha Requerida</th>
                            <th>Estatus</th>
                            <th>Total</th>
                            <th>Partidas</th>
                            <th>Usuario</th>
                            <?php if ($_SESSION['eliminaOCompra'] == "1") { ?>
                                <th>Eliminar</th>
                            <?php } ?>


                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $reg) { ?>
                            <tr id="<?php echo $reg['idOrden']?>">


                                <td>O<?php echo $reg['idOrden'] ?></td>
                                <td><?php echo strtoupper($reg['proveedor']) ?></td>
                                <td><?php echo $reg['fecha'] ?></td>
                                <td><?php echo $reg['fechaRequerida'] ?></td>
                                <td><img title="<?php echo $reg['esta']; ?>" src="./imagenes/<?php echo $reg['icono']; ?>" style="height: 20px"></td>
                                <td><?php echo "$ " . number_format($reg['total'], 2, '.', ',') ?></td>
                                <td><button type="button" data-toggle="popover-custom-content" rel="popover-focus" popover-id="<?php echo $reg['idOrden'] ?>" class="mr-2 mb-2 btn btn-dark"><?php echo count($reg['productos'], COUNT_NORMAL) ?> Partidas</button></td>
                                <td><?php echo strtoupper($reg['usuario']) ?></td>
                                <?php if ($_SESSION['eliminaOCompra'] == "1") { ?>
                                    <td><a href="#" class="btn btn-warning" data-role="eliminaOCompra" data-id="<?php echo $reg['idOrden'] ?>">Eliminar</a></td>
                                <?php } ?>

                            </tr>
                        <?php } ?>

                    </tbody>

                </table>
            </div>
        </div>

        <?php foreach ($registros as $reg) { ?>
            <div id="popover-content-<?php echo  $reg['idOrden'] ?>" class="d-none">
                <div class="dropdown-menu-header">
                    <div class="dropdown-menu-header-inner bg-primary">
                        <div class="menu-header-image opacity-5" style="background-image: url('assets/images/dropdown-header/abstract2.jpg');"></div>
                        <div class="menu-header-content">
                            <a class="btn btn-warning" href="index.php?p=ordenescompraact&idOrden=<?php echo $reg['idOrden'] ?>">Actualizar / Consultar</a>
                            <h5 class="menu-header-title">Partidas</h5>

                        </div>
                    </div>
                </div>
                <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered ">
                    <thead>
                        <tr>
                      
                            <th>Producto</th>
                            <th>Unidad</th>
                            <th>Peso Teórico</th>
                            <th>$ x U. de P.</th>
                            <th>Monto</th>
                            <th>Recibido</th>
                           

                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($reg['productos'] as $ins) { 
                            if (is_numeric($ins['largo'])) {
                                $largo = $ins['largo'];
                                
                            } else {
                                $largo = "";
                            }
                            ?>
                            <tr>

                           
                                <td><?php echo strtoupper($ins['sku'] . " " . $ins['producto'] . " " . $largo . " " . $ins['ancho'] . " " . $ins['calibre'] . " " . $ins['tipo']) ?></td>
                                <td><?php echo $ins['unidad'] ?></td>
                                <td><?php echo $ins['pesoTeorico'] ?></td>
                                <td><?php echo "$ " . number_format($ins['precioUnidadPeso'], 2, '.', '') ?></td>
                                <td><?php echo "$ " . number_format($ins['pesoTeorico'] * $ins['precioUnidadPeso'], 2, '.', '') ?></td>
                                <td><?php if ($ins['recibido'] == "1") { ?><img src="./imagenes/correcto.png" style="height: 20px"><?php } else { ?><img src="./imagenes/incorrecto.png" style="height: 20px"><?php } ?></td>


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
    $('#ordenes').DataTable( {
        "order": [[ 0, "desc" ]],
        "lengthMenu": [[100, 200,-1], [100,200, "Todos"]]
    } );
} );

</script>