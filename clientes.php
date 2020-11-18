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

    $cliente = $_POST['cliente'];
    $rfc = $_POST['rfc'];
    $comentarios = $_POST['comentarios'];
    $direccion = $_POST['direccion'];
    $representante = $_POST['representante'];
    $direccionentrega = $_POST['direccionentrega'];
    $mail = $_POST['mail'];
    $idUso = $_POST['idUso'];
    $telefono = $_POST['telefono'];


    $tipoprecio = $_POST['tipoprecio'];


    if (
        $cliente == "" || $rfc == "" || $direccion == "" || $representante == ""
        || $mail == "" || $telefono == "" || $direccionentrega == ""
    ) {
        $mensajeEnviar = "Todos los campos son obligatorios, por favor verifique";
    } else {



        $respuesta = $controller->agregarCliente(
            $cliente,
            $rfc,
            $direccion,
            $representante,
            $telefono,
            $mail,
            $tipoprecio,
            $comentarios,
            $idUso,
            $direccionentrega
        );

        if (!$respuesta->exito) {
            $mensajeEnviar = $respuesta->mensaje;
        } else {
            $cliente = "";
            $rfc = "";
            $direccion = "";
            $representante = "";
            $telefono = "";
            $mail = "";
            $direccionentrega = "";
            $comentarios = "";
        }
    }
} else {
    if (isset($_GET['idCliente'])) {
        $idCliente = $_GET['idCliente'];
        $activo = $_GET['activo'];
        if ($idCliente != "") {
            $controller->toggleCliente($idCliente, $activo);
        }
    }
}
$respuesta = $controller->obtenerclientes();
$registros = $respuesta->registros;


$respuesta = $controller->obtenerUsos();
$usos = $respuesta->registros;







?>




<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="lnr-user icon-gradient bg-ripe-malin"></i>
                    </div>
                    <div>Clientes
                        <div class="page-title-subheading">.</div>
                    </div>
                </div>
                <div class="page-title-actions">


                    <button type="button" data-toggle="collapse" href="#collapseNuevoAdministrador" class="btn btn-primary">Nuevo Cliente</button>


                </div>
            </div>
        </div>

        <!-- aqui va el contenido de la página -->
        <div class="collapse" id="collapseNuevoAdministrador">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Nuevo Cliente</h5>
                    <form id="adminForm" class="col-md-10 mx-auto" method="post" action="index.php?p=clientes">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="clave">RFC</label>
                                    <div>
                                        <input type="text" maxlength="30" class="form-control" id="rfc" name="rfc" value="<?php if (isset($rfc)) {
                                                                                                                                    echo strtoupper($rfc);
                                                                                                                                } ?>" placeholder="RFC" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="cliente">Cliente</label>
                                    <div>
                                        <input type="text" maxlength="150" class="form-control" id="cliente" name="cliente" value="<?php if (isset($cliente)) {
                                                                                                                                        echo strtoupper($cliente);
                                                                                                                                    } ?>" placeholder="Cliente" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row mt-2">
                                <div class="col-md-6">
                                    <label for="clave">Dirección</label>
                                    <div>
                                        <input type="text" maxlength="255" class="form-control" id="direccion" name="direccion" value="<?php if (isset($direccion)) {
                                                                                                                                            echo strtoupper($direccion);
                                                                                                                                        } ?>" placeholder="Dirección" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="cliente">Contacto</label>
                                    <div>
                                        <input type="text" maxlength="50" class="form-control" id="representante" name="representante" value="<?php if (isset($representante)) {
                                                                                                                                                    echo strtoupper($representante);
                                                                                                                                                } ?>" placeholder="Representante" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row mt-2">
                                <div class="col-md-6">
                                    <label for="clave">Teléfono</label>
                                    <div>
                                        <input type="text" maxlength="20" class="form-control" id="telefono" name="telefono" value="<?php if (isset($telefono)) {
                                                                                                                                        echo strtoupper($telefono);
                                                                                                                                    } ?>" placeholder="telefono" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="cliente">Mail</label>
                                    <div>
                                        <input type="email" maxlength="200" class="form-control" id="mail" name="mail" value="<?php if (isset($mail)) {
                                                                                                                                echo strtoupper($mail);
                                                                                                                            } ?>" placeholder="Mail" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-row mt-2">
                                <div class="col-md-6">
                                    <label for="clave">Dirección de Entrega</label>
                                    <div>
                                        <input type="text" maxlength="300" class="form-control" id="direccionentrega" name="direccionentrega" value="<?php if (isset($direccionentrega)) {
                                                                                                                                                            echo strtoupper($direccionentrega);
                                                                                                                                                        } ?>" placeholder="Dirección de Entrega" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="clave">Tipo Precio</label>
                                    <select class=" form-control " id="tipoprecio" name="tipoprecio">
                                        <option value="G">General</option>
                                        <option value="R">Revendedor</option>

                                    </select>
                                </div>


                            </div>
                            <div class="form-row mt-2">
                                <div class="col-md-6">
                                    <label for="clave">Comentarios</label>
                                    <div>
                                        <input type="text" maxlength="200" class="form-control" id="comentarios" name="comentarios" value="<?php if (isset($comentarios)) {
                                                                                                                                                            echo strtoupper($comentarios);
                                                                                                                                                        } ?>" placeholder="Comentarios" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="clave">Tipo CFDI</label>
                                    <div>
                                    <select class=" form-control " id="idUso" name="idUso">
                                            <?php
                                            if (isset($usos)) {
                                                foreach ($usos as $uni) {
                                                    echo '<option value="' . $uni['idUso'] . '" >' .
                                                        strtoupper($uni['uso']) . '</option>';
                                                }
                                            } ?>
                                        </select>
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
                <table style="width: 100%;" id="clientes" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>RFC</th>
                            <th>Cliente</th>
                           
                            <th>Contacto</th>
                            <th>Teléfono</th>
                            <th>Mail</th>
                            <th>CFDI</th>
                           
                            <th>Precio</th>



                            <th>Actualizar</th>
                            <th>Activo</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $reg) { ?>
                            <tr id="<?php echo $reg['idCliente'] ?>">
                                <td data-target="rfc"><?php echo strtoupper($reg['rfc']) ?></td>
                                <td data-target="cliente"><?php echo strtoupper($reg['cliente']) ?></td>
                               
                                <td data-target="representante"><?php echo strtoupper($reg['representante']) ?></td>
                                <td data-target="telefono"><?php echo strtoupper($reg['telefono']) ?></td>
                                <td data-target="mail"><?php echo strtoupper($reg['mail']) ?></td>
                                <td data-target="uso"><?php echo strtoupper($reg['uso']) ?></td>
                                
                                <td data-target="precio"><?php if ($reg['tipoprecio'] == "G") {
                                                                echo "General";
                                                            } else {
                                                                echo "Revendedor";
                                                            } ?></td>



                                <td><a href="#" class="btn btn-primary" data-role="updateClientes" data-id="<?php echo $reg['idCliente'] ?>">Actualizar</a></td>
                                <td>
                                <input class="idUso" type="hidden" value="<?php echo $reg['idUso'] ?>">
                                <input class="comentarios" type="hidden" value="<?php echo $reg['comentarios'] ?>">
                                    <a href="index.php?p=clientes&idCliente=<?php echo $reg['idCliente'] ?>&activo=<?php if ($reg['activo'] == 1) {
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
        $('#clientes').DataTable({
            "lengthMenu": [
                [100, 200, -1],
                [100, 200, "Todos"]
            ]
        });
    });
</script>