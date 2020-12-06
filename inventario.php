<?php

include_once './controllers/CatalogosController.php';
include_once './controllers/DevolucionesController.php';
if (!isset($_SESSION['nombreUsr'])) {
    echo "<script>window.setTimeout(function() { window.location = 'login.php' }, 10);</script>";
}
$controller = new CatalogosController();
$devcontroller = new DevolucionesController();



if (isset($_POST['idProd'])) {
    $idProd = $_POST['idProd'];
    $inventario = $controller->obtenerInventario($idProd);
    $respuesta = $controller->obtenerAlmacenesDisponibles($idProd);
    $almacenes = $respuesta->registros;
}

if (isset($_GET['idProd'])) {
    $idProd = $_GET['idProd'];
    $inventario = $controller->obtenerInventario($idProd);
    $respuesta = $controller->obtenerAlmacenesDisponibles($idProd);
    $almacenes = $respuesta->registros;
}

$respuesta = $controller->obtenerRecepcionesGlobal();
$recepciones = $respuesta->registros;


$respuesta = $controller->obtenerProductosEntrada();
$productos = $respuesta->registros;


$respuesta = $devcontroller->obtenerDevoluciones();
$devoluciones = $respuesta->registros;


?>







<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="col-md-2">


                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="pe-7s-box1 icon-gradient bg-ripe-malin"></i>
                        </div>
                        <div>Inventario
                            <div class="page-title-subheading">.</div>
                        </div>
                    </div>
                </div>

                <form id="formInventario" class="form-row col-md-12" action="index.php?p=inventario" method="POST">

                    <div class="col-md-1">

                    </div>
                    <div class="col-md-5">
                        <label for="idProdH">Producto</label>
                        <div>
                            <select id="idProd" name="idProd" onchange="document.getElementById('formInventario').submit()" class="multiselect-dropdown form-control">
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
                                            . '   >' . strtoupper($ins['sku'] . " - " . $ins['producto'] . " - " . $ins['ancho'] . " - " . $ins['calibre'] . " - " . $ins['tipo']) . '</option>';
                                    }
                                } ?>
                            </select>
                        </div>
                    </div>







                </form>
            </div>


        </div>



        <div class="main-card mb-3 card">
            <div class="card-body">
                <h2>Kardex por MP</h2>
                <table style="width: 100%;" id="inventarioT" class="table table-hover table-striped table-bordered ">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th># Rec</th>
                            <th># Mov</th>
                            <th>Cantidad</th>
                            <th>Unidad</th>
                            <th>Kilos</th>
                            <th>E / S</th>
                            <th>Almacen</th>
                            <th>Usuario</th>


                        </tr>
                    </thead>
                    <tbody>

                        <?php if (isset($inventario) && count($inventario) > 0) {
                            foreach ($inventario as $ins) { ?>
                                <tr>

                                    <a>
                                        <td><?php echo $ins['fecha'] ?></td>
                                        <td><?php echo $ins['id'] ?></td>
                                        <td><?php echo $ins['idMov'] ?></td>
                                        <td><?php if ($ins['tipo'] == "S") {
                                                echo number_format($ins['cantidad'] * -1, 2, '.', ',');
                                            } else {
                                                echo number_format($ins['cantidad'], 2, '.', ',');
                                            } ?></td>
                                        <td><?php echo $ins['unidad'] ?></td>
                                        <td><?php if ($ins['idUnidad'] == "1") {
                                                if ($ins['tipo'] == "S") {
                                                    echo  number_format($ins['cantidad'] * -1, 2, '.', ',');
                                                } else {
                                                    echo  number_format($ins['cantidad'], 2, '.', ',');
                                                }
                                            } else {
                                                if ($ins['tipo'] == "S") {
                                                    echo  number_format($ins['peso'] * -1, 2, '.', ',');
                                                } else {
                                                    echo  number_format($ins['peso'], 2, '.', ',');
                                                }
                                            } ?></td>
                                        <td><?php if ($ins['tipo'] == "S") { ?>
                                                <img src="./imagenes/out.png" style="height: 30px"> <?php } else { ?>
                                                <img src="./imagenes/in.png" style="height: 30px"><?php } ?></td>
                                        <td><?php echo $ins['almacen'] ?></td>
                                        <td><?php echo $ins['usuario'] ?></td>

                                </tr>
                        <?php }
                        } ?>


                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" style="text-align:right">Total:</th>
                            <th colspan="3"></th>

                            <th></th>



                        </tr>
                    </tfoot>

                </table>
            </div>
            <!-- hasta aqui llega-->
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <h2>Inventario MP</h2>
                <table style="width: 100%;" id="recepcionesT" class="table table-hover table-striped table-bordered ">
                    <thead>
                        <tr>

                            <th>#</th>
                            <th>Producto</th>
                            <th>UM</th>
                            <th>Cant Total</th>




                            <th>Usados</th>
                            <th>Disponibles</th>
                            <th>Almacen</th>
                            <?php if ($_SESSION['traspasos'] == "1") { ?>
                                <th>Traspaso</th>
                            <?php } ?>


                        </tr>
                    </thead>
                    <tbody>

                        <?php if (isset($recepciones) && count($recepciones) > 0) {
                            foreach ($recepciones as $det) {
                                if (is_numeric($det['largo'])) {
                                    $largo = $det['largo'];
                                    $mostrarbotonMetros = false;
                                } else {
                                    $largo = "";
                                } ?>
                                <tr>

                                    <a>
                                        <td><?php echo $det['id'] ?></td>
                                        <td><?php echo $det['sku'] . " " . $det['producto'] . " " . $largo . " " . $det['ancho'] . " " . $det['calibre'] . " " . $det['tipo'] ?></td>
                                        <td><?php echo $det['unidad'] ?></td>
                                        <td><?php echo  number_format($det['peso'], 2, '.', ','); ?></td>
                                        <td><?php echo  number_format($det['kilosUsados'], 2, '.', ','); ?></td>
                                        <td><?php echo  number_format($det['restante'], 2, '.', ','); ?></td>
                                        <td><?php echo $det['almacen'] ?></td>
                                        <?php if ($_SESSION['traspasos'] == "1") { ?>


                                            <td><a href="#" class="btn btn-primary" data-role="hacerTraspaso" data-id="<?php echo $det['idRecepcion'] ?>">Traspaso</a></td>
                                        <?php } ?>
                                </tr>
                        <?php }
                        } ?>


                    </tbody>


                </table>
            </div>
            <!-- hasta aqui llega-->
        </div>


        <div class="main-card mb-3 card">
            <div class="card-body">
                <h2>Devoluciones Vigentes</h2>
                <table style="width: 100%;" id="recepcionesT" class="table table-hover table-striped table-bordered ">
                    <thead>
                        <tr>

                            <th>#</th>
                            <th>Producto</th>
                            <th>UM</th>
                            <th>Cant Total</th>




                            <th>Usados</th>
                            <th>Disponibles</th>
                            <th>Almacen</th>
                            <?php if ($_SESSION['traspasos'] == "1") { ?>
                                <th>Traspaso</th>
                            <?php } ?>


                        </tr>
                    </thead>
                    <tbody>

                        <?php if (isset($devoluciones) && count($devoluciones) > 0) {
                            foreach ($devoluciones as $det) {

                                if ($det['restante'] > 0) {
                                    if (is_numeric($det['largo'])) {
                                        $largo = $det['largo'];
                                    } else {
                                        $largo = "";
                                    }


                        ?>
                                    <tr>

                                        <a>
                                            <td>D<?php echo $det['idDevolucion'] ?></td>
                                            <td><?php echo $det['sku'] . " " . $det['producto'] . " " . $largo . " " . $det['ancho'] . " " . $det['calibre'] . " " . $det['tipo'] ?></td>
                                            <td><?php echo $det['unidad'] ?></td>
                                            <td><?php echo  number_format($det['cantidad'], 2, '.', ','); ?></td>
                                            <td><?php echo  number_format($det['usados'], 2, '.', ','); ?></td>
                                            <td><?php echo  number_format($det['restante'], 2, '.', ','); ?></td>
                                            <td><?php echo $det['almacen'] ?></td>
                                            <?php if ($_SESSION['traspasos'] == "1") { ?>


                                                <td><a href="#" class="btn btn-primary" data-role="hacerTraspaso" data-id="D<?php echo $det['idDevolucion'] ?>">Traspaso</a></td>
                                            <?php } ?>
                                    </tr>
                        <?php }
                            }
                        } ?>


                    </tbody>


                </table>
            </div>
            <!-- hasta aqui llega-->
        </div>
    </div>
    <?php include_once('footer.php') ?>
</div>

<script>
    $(document).ready(function() {
        $('#recepcionesT').DataTable({
            scrollY: '35vh',
            scrollCollapse: true,
            paging: false,
            "lengthMenu": [
                [-1, 100, 200],
                ["Todos", 100, 200]
            ]
        });
    });
</script>