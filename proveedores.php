<?php

include_once './controllers/CatalogosController.php';
if (!isset($_SESSION['nombreUsr'])) {
    echo "<script>window.setTimeout(function() { window.location = 'login.php' }, 10);</script>";
}
$controller = new CatalogosController();

$entro = "";
if (isset($_POST['entro'])) {
    $entro = $_POST['entro'];
}

if (isset($_GET['entro'])) {
    $entro = $_GET['entro'];
}

if ($entro != "") {

    $proveedor = $_POST['proveedor'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $comentarios = $_POST['comentarios'];
    $rfc = $_POST['rfc'];



    if ($proveedor == "") {
        $mensajeEnviar = "Todos los campos son obligatorios, por favor verifique";
    } else {



        $respuesta = $controller->agregarProveedor($proveedor, $telefono, $comentarios, $rfc, $direccion);

        if (!$respuesta->exito) {
            $mensajeEnviar = $respuesta->mensaje;
        } else {
            $proveedor = "";
            $telefono = "";
            $comentarios = "";
            $rfc = "";
            $direccion = "";
        }
    }
} else {
    if (isset($_GET['idProveedor'])) {
        $idProveedor = $_GET['idProveedor'];
        $activo = $_GET['activo'];
        if ($idProveedor != "") {
            $controller->toggleProveedor($idProveedor, $activo);
        }
    }
}
$respuesta = $controller->obtenerProveedores();
$registros = $respuesta->registros;







?>




<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="lnr-user icon-gradient bg-ripe-malin"></i>
                    </div>
                    <div>Proveedores
                        <div class="page-title-subheading">.</div>
                    </div>
                </div>
                <div class="page-title-actions">


                    <button type="button" data-toggle="collapse" href="#collapseNuevoAdministrador" class="btn btn-primary">Nuevo Proveedor</button>


                </div>
            </div>
        </div>

        <!-- aqui va el contenido de la página -->
        <div class="collapse" id="collapseNuevoAdministrador">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Nuevo Proveedor</h5>
                    <form id="adminForm" class="col-md-10 mx-auto" method="post" action="index.php?p=proveedores">
                        <div class="form-group">
                            <div class="form-row">

                                <div class="col-md-6">
                                    <label for="proveedor">Proveedor</label>
                                    <div>
                                        <input type="text" maxlength="50" class="form-control" id="proveedor" name="proveedor" value="<?php if (isset($proveedor)) {
                                                                                                                                            echo strtoupper($proveedor);
                                                                                                                                        } ?>" placeholder="Proveedor" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="proveedor">RFC</label>
                                    <div>
                                        <input type="text" maxlength="50" class="form-control" id="rfc" name="rfc" value="<?php if (isset($rfc)) {
                                                                                                                                echo strtoupper($rfc);
                                                                                                                            } ?>" placeholder="rfc" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="proveedor">Dirección</label>
                                    <div>
                                        <input type="text" maxlength="200" class="form-control" id="direccion" name="direccion" value="<?php if (isset($direccion)) {
                                                                                                                                            echo strtoupper($direccion);
                                                                                                                                        } ?>" placeholder="direccion" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="proveedor">Teléfono</label>
                                    <div>
                                        <input type="text" maxlength="50" class="form-control" id="telefono" name="telefono" value="<?php if (isset($telefono)) {
                                                                                                                                        echo strtoupper($telefono);
                                                                                                                                    } ?>" placeholder="telefono" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="proveedor">Comentarios</label>
                                    <div>
                                        <input type="text" maxlength="300" class="form-control" id="comentarios" name="comentarios" value="<?php if (isset($comentarios)) {
                                                                                                                                                echo strtoupper($comentarios);
                                                                                                                                            } ?>" placeholder="comentarios" />
                                    </div>
                                </div>
                            </div>

                        </div>



                        <div class="form-group">
                            <input type="hidden" name="entro" value="1" />
                            <button type="submit" class="btn btn-primary" name="signup" value="Sign up">Crear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="main-card mb-3 card">
            <div class="card-body">
                <table style="width: 100%;" id="proveedores" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>RFC</th>
                            <th>Proveedor</th>
                            <th>Dirección</th>
                            <th>Telefono</th>
                            <th>Comentarios</th>
                            <th>Actualizar</th>
                            <th>Activo</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $reg) { ?>
                            <tr id="<?php echo $reg['idProveedor'] ?>">
                                <td data-target="rfc"><?php echo strtoupper($reg['rfc']) ?></td>
                                <td data-target="proveedor"><?php echo strtoupper($reg['proveedor']) ?></td>
                                <td data-target="direccion"><?php echo strtoupper($reg['direccion']) ?></td>
                                <td data-target="telefono"><?php echo strtoupper($reg['telefono']) ?></td>
                                <td data-target="comentarios"><?php echo strtoupper($reg['comentarios']) ?></td>
                                <td><a href="#" class="btn btn-primary" data-role="updateProveedores" data-id="<?php echo $reg['idProveedor'] ?>">Actualizar</a></td>
                                <td><a href="index.php?p=proveedores&idProveedor=<?php echo $reg['idProveedor'] ?>&activo=<?php if ($reg['activo'] == 1) {
                                                                                                                                echo "0";
                                                                                                                            } else {
                                                                                                                                echo "1";
                                                                                                                            } ?>" class="btn btn-primary"><?php if ($reg['activo'] == 1) {
                                                                                                                                                                echo "Activado";
                                                                                                                                                            } else {
                                                                                                                                                                echo "Desactivado";
                                                                                                                                                            } ?></a></td>

                            </tr>
                        <?php } ?>

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
        $('#proveedores').DataTable({
            "lengthMenu": [
                [100, 200, -1],
                [100, 200, "Todos"]
            ]
        });
    });
</script>