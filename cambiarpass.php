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

    $passanterior = $_POST['passanterior'];
    $passnuevo = $_POST['passnuevo'];
    $passconf = $_POST['passconf'];


    if ($passanterior == "" || $passnuevo == "" || $passconf == "") {
        $mensajeEnviar = "Todos los campos son obligatorios, por favor verifique";
    } else {

        if ($passnuevo == $passconf) {

            $respuesta = $controller->actualizarPassword($_SESSION['idUsr'], md5($passanterior), md5($passnuevo));
            if (!$respuesta->exito) {
                $mensajeEnviar = $respuesta->mensaje;
            }
            else{
                $mensajeEnviar ="Se cambió con éxito la contraseña";
            }
        } else {
            $mensajeEnviar = "La contraseña nueva no coincide con la confirmación";
        }
    }
}







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
                        <h5 class="card-title">Cambiar Contraseña</h5>
                        <form id="adminForm" class="col-md-10 mx-auto" method="post" action="index.php?p=cambiarpass">

                            <div class=" form-group">
                                <label for="passanterior">Contraseña Anterior</label>
                                <input type="password" class="form-control" id="passanterior" name="passanterior" placeholder="Anterior" />
                            </div>

                            <div class=" form-group">
                                <label for="passnuevo">Nueva Contraseña</label>
                                <input type="password" class="form-control" id="passnuevo" name="passnuevo" placeholder="Nueva" />
                            </div>
                            <div class=" form-group">
                                <label for="passconf">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="passconf" name="passconf" placeholder="Confirmar" />
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