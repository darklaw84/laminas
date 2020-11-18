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

    $ancho = $_POST['ancho'];
   


    if ($ancho == "" ) {
        $mensajeEnviar = "Todos los campos son obligatorios, por favor verifique";
    } else {



        $respuesta = $controller->agregarAncho($ancho);

        if (!$respuesta->exito) {
            $mensajeEnviar = $respuesta->mensaje;
        }
    }
} else {
    if (isset($_GET['idAncho'])) {
        $idAncho = $_GET['idAncho'];
        $activo = $_GET['activo'];
        if ($idAncho != "") {
            $controller->toggleAncho($idAncho, $activo);
        }
    }
}
$respuesta = $controller->obtenerAnchos(false);
$registros = $respuesta->registros;







?>




<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-paperclip icon-gradient bg-ripe-malin"></i>
                    </div>
                    <div>Anchos
                        <div class="page-title-subheading">.</div>
                    </div>
                </div>
                <div class="page-title-actions">


                    <button type="button" data-toggle="collapse" href="#collapseNuevoAdministrador" class="btn btn-primary">Nuevo Ancho</button>


                </div>
            </div>
        </div>

        <!-- aqui va el contenido de la página -->
        <div class="collapse" id="collapseNuevoAdministrador">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Nuevo Ancho</h5>
                    <form id="adminForm" class="col-md-10 mx-auto" method="post" action="index.php?p=anchos">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="clave">Ancho</label>
                                    <div>
                                        <input type="text" maxlength="30" class="form-control" id="ancho" name="ancho" value="<?php if (isset($ancho)) {
                                                                                                                                    echo strtoupper($ancho);
                                                                                                                                } ?>" placeholder="ancho" />
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
                <table style="width: 100%;" id="anchos" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Ancho</th>
                            <th>Actualizar</th>
                            <th>Activo</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $reg) { ?>
                            <tr id="<?php echo $reg['idAncho'] ?>">
                                <td data-target="ancho"><?php echo strtoupper($reg['ancho']) ?></td>
                               

                                <td><a href="#" class="btn btn-primary" data-role="updateAnchos" data-id="<?php echo $reg['idAncho'] ?>">Actualizar</a></td>
                                <td><a href="index.php?p=anchos&idAncho=<?php echo $reg['idAncho'] ?>&activo=<?php if ($reg['activo'] == 1) {
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
    $('#anchos').DataTable( {
        "lengthMenu": [[100, 200,-1], [100,200, "Todos"]]
    } );
} );

</script>