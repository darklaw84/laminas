<?php

include_once './controllers/OrdenesController.php';
if (!isset($_SESSION['nombreUsr'])) {
    echo "<script>window.setTimeout(function() { window.location = 'login.php' }, 10);</script>";
}
$controller = new OrdenesController();


$entro = "";
if (isset($_POST['entro'])) {
    $entro = $_POST['entro'];
}

if (isset($_GET['entro'])) {
    $entro = $_GET['entro'];
}


$idOrden = "";
if (isset($_POST['idOrden'])) {
    $idOrden = $_POST['idOrden'];
}

if (isset($_GET['idOrden'])) {
    $idOrden = $_GET['idOrden'];
}


if ($entro == "1") {


    $detalle = $controller->obtenerOrden($idOrden);
    if ($detalle->exito) {
        $partidas = $detalle->registros[0]['productos'];
    } else {
        $mensajeEnviar = $detalle->mensaje;
    }
}



?>


    <input type="hidden" name="idOrden" id="idOrdenRecMat" value="<?php if (isset($idOrden)) {
                                                    echo $idOrden;
                                                } ?>">




<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">



                <form class="form-row col-md-12" action="index.php?p=recMat" method="POST">

                    <div class="col-md-6">

                    </div>
                    <div class="col-md-4">
                        <label for="idProdH">No Orden</label>
                        <div>
                            <input type="text" maxlength="10" class="form-control" id="idOrden" name="idOrden" value="<?php if (isset($idOrden)) {
                                                                                                                            echo strtoupper($idOrden);
                                                                                                                        } ?>" placeholder="#" />

                        </div>
                    </div>

                    <div class="col-md-2">
                        <label for="">&nbsp; </label>
                        <div>
                            <input type="hidden" value="1" name="entro">
                            <button type="submit" class="btn btn-primary">Consultar</button> </div>
                    </div>




                </form>
            </div>


        </div>

        <!-- aqui va el contenido de la página -->
        <?php if (isset($idOrden) && $idOrden != "") { ?>
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <table style="width: 100%;" id="detalle" class="table table-hover table-striped table-bordered ">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Unidad</th>
                                <th>Cant Ordenada</th>
                                <th>Cant Recibida</th>
                                <th>Peso Ordenado</th>
                                <th>Peso Recibido</th>
                                <th>Recibir</th>

                            </tr>
                        </thead>
                        <tbody>

                            <?php if (isset($partidas) && count($partidas) > 0) {
                                foreach ($partidas as $ins) {
                                    if (is_numeric($ins['largo'])) {
                                        $largo = $ins['largo'];
                                        
                                    } else {
                                        $largo = "";
                                    } ?>
                                    <tr id="<?php echo $ins['idOrdenCompraDet'] ?>">

                                        <a>
                                            <td data-target="producto"><?php echo strtoupper($ins['sku'] . " " . $ins['producto'] . " " . $largo . " " . $ins['ancho'] . " " . $ins['calibre'] . " " . $ins['tipo']) ?></td>
                                            <td><?php echo $ins['unidad'] ?></td>
                                            <td><?php echo $ins['cantidad'] ?></td>
                                            <td><?php echo $ins['totalCantidad'] ?></td>
                                            <td><?php echo $ins['pesoTeorico'] ?></td>
                                            <td><?php echo $ins['total'] ?></td>
                                            <td><input class="idUnidad" type="hidden" value="<?php echo $ins['idUnidad'] ?>">
                                                <input class="prodPesoTeorico" type="hidden" value="<?php echo $ins['prodPesoTeorico'] ?>">
                                                <input class="idProducto" type="hidden" value="<?php echo $ins['idProducto'] ?>">
                                                <?php if ($ins['recibido'] == 1) {
                                                    echo "Recibido";
                                                } else { ?><a href="#" class="btn btn-primary" data-role="recibirMateria" data-id="<?php echo $ins['idOrdenCompraDet'] ?>">Recibir</a><?php } ?></td>


                                    </tr>
                            <?php }
                            } ?>


                        </tbody>

                    </table>
                </div>
            </div>
        <?php } ?>



        <!-- hasta aqui llega-->

    </div>
    <?php include_once('footer.php') ?>
</div>
<script>
    document.getElementById('idOrden').focus();
    document.getElementById('idOrden').select();
</script>
<script>
    $(document).ready(function() {
        $('#detalle').DataTable({
            "lengthMenu": [
                [-1, 100, 200],
                ["Todos", 100, 200]
            ]
        });
    });
</script>