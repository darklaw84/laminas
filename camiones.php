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

    $camion = $_POST['camion'];
    $placas = $_POST['placas'];
   


    if ($placas == "" || $camion=="" ) {
        $mensajeEnviar = "Todos los campos son obligatorios, por favor verifique";
    } else {



        $respuesta = $controller->agregarCamion($camion,$placas);

        if (!$respuesta->exito) {
            $mensajeEnviar = $respuesta->mensaje;
        }
        else
        {
            $placas="";
            $camion="";
        }
    }
} else {
    if (isset($_GET['idCamion'])) {
        $idCamion = $_GET['idCamion'];
        $activo = $_GET['activo'];
        if ($idCamion != "") {
            $controller->toggleCamion($idCamion, $activo);
        }
    }
}
$respuesta = $controller->obtenerCamiones();
$registros = $respuesta->registros;







?>




<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-car icon-gradient bg-ripe-malin"></i>
                    </div>
                    <div>Unidades
                        <div class="page-title-subheading">.</div>
                    </div>
                </div>
                <div class="page-title-actions">


                    <button type="button" data-toggle="collapse" href="#collapseNuevoAdministrador" class="btn btn-primary">Nueva Unidad</button>


                </div>
            </div>
        </div>

        <!-- aqui va el contenido de la página -->
        <div class="collapse" id="collapseNuevoAdministrador">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Nueva Unidad</h5>
                    <form id="adminForm" class="col-md-10 mx-auto" method="post" action="index.php?p=camiones">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="clave">Unidad</label>
                                    <div>
                                        <input type="text" maxlength="50" class="form-control" id="camion" name="camion" value="<?php if (isset($camion)) {
                                                                                                                                    echo strtoupper($camion);
                                                                                                                                } ?>" placeholder="unidad" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="clave">Placas</label>
                                    <div>
                                        <input type="text" maxlength="50" class="form-control" id="placas" name="placas" value="<?php if (isset($placas)) {
                                                                                                                                    echo strtoupper($placas);
                                                                                                                                } ?>" placeholder="placas" />
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
                <table style="width: 100%;" id="almacenes" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Unidad</th>
                            <th>Placas</th>
                            <th>Actualizar</th>
                            <th>Activo</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $reg) { ?>
                            <tr id="<?php echo $reg['idCamion'] ?>">
                                <td data-target="camion"><?php echo strtoupper($reg['camion']) ?></td>
                                <td data-target="placas"><?php echo strtoupper($reg['placas']) ?></td>
                               

                                <td><a href="#" class="btn btn-primary" data-role="updateCamiones" data-id="<?php echo $reg['idCamion'] ?>">Actualizar</a></td>
                                <td><a href="index.php?p=camiones&idCamion=<?php echo $reg['idCamion'] ?>&activo=<?php if ($reg['activo'] == 1) {
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
    $('#almacenes').DataTable( {
        "lengthMenu": [[100, 200,-1], [100,200, "Todos"]]
    } );
} );

</script>