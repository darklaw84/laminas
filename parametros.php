<?php

include_once './controllers/AdministradorController.php';
if (!isset($_SESSION['nombreUsr'])) {
    echo "<script>window.setTimeout(function() { window.location = 'login.php' }, 10);</script>";
}
$controller = new AdministradorController();

$entro = "";
if (isset($_POST['entro'])) {
    $entro = $_POST['entro'];
}

if (isset($_GET['entro'])) {
    $entro = $_GET['entro'];
}

if ($entro != "") {

    $tipocambio = $_POST['tipocambio'];
    if ($tipocambio != "") {
        $controller->actualizarParametro("tc", $tipocambio);
        echo "<script>window.setTimeout(function() { window.location = 'index.php?p=parametros' }, 10);</script>";
    } else {
        $mensajeEnviar = "El valor del tipo de cambio debe de ser númerico";
    }
}

$resTC = $controller->obtenerValorParametro("tc");
$tipocambio = $resTC->valor;






?>




<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="lnr-user icon-gradient bg-ripe-malin"></i>
                    </div>
                    <div>Administración
                        <div class="page-title-subheading">.</div>
                    </div>
                </div>
                <div class="page-title-actions">


                </div>
            </div>
        </div>

        <!-- aqui va el contenido de la página -->
        <div class="row">
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <h5 class="card-title">Cambiar Tipo de cambio</h5>
                        <form id="adminForm" class="col-md-10 mx-auto" method="post" action="index.php?p=parametros">

                            <div class=" form-group">
                                <label for="tipocambio">Tipo de Cambio</label>
                                <input type="number" step="0.001" class="form-control" id="tipocambio" name="tipocambio" value="<?php echo $tipocambio; ?>" />
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="entro" value="1" />
                                <button type="submit" class="btn btn-primary" name="signup" value="Sign up">Cambiar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
            </div>
        </div>



        <!-- hasta aqui llega-->

    </div>
    <?php include_once('footer.php') ?>
</div>