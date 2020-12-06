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

    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $password = $_POST['password'];

    if ($nombre == "" || $apellidos == "" || $correo == "" || $telefono == "" || $password == "") {
        $mensajeEnviar = "Todos los campos son obligatorios, por favor verifique";
    } else {



        $respuesta = $controller->agregarAdministrador(
            $nombre,
            $apellidos,
            $correo,
            $telefono,
            md5($password),
            isset($_POST['clientes']),
            isset($_POST['proveedores']),
            isset($_POST['productos']),
            isset($_POST['ordCompra']),
            isset($_POST['creaCot']),
            isset($_POST['recMat']),
            isset($_POST['calibres']),
            isset($_POST['tipos']),
            isset($_POST['producciones']),
            isset($_POST['usuarios']),
            isset($_POST['eliminaCotizacion']),
            isset($_POST['cambiarPrecios']),
            isset($_POST['devoluciones']),
            isset($_POST['eliminaOCompra']),
            isset($_POST['salidaInventario']),
            isset($_POST['editarProductos']),
            isset($_POST['autorizarPedidos']),
            isset($_POST['genRem']),
            isset($_POST['inventarios']),
            isset($_POST['verCotizaciones']),
            isset($_POST['cancelarPedidos']),
            isset($_POST['agregarAbonos']),
            isset($_POST['pedidoCantidades']),
            isset($_POST['cancelarRemisiones']),
            isset($_POST['traspasos'])
        );



        if (!$respuesta->exito) {
            $mensajeEnviar = $respuesta->mensaje;
        }
    }
} else {
    if (isset($_GET['idAdministrador'])) {
        $idAdministrador = $_GET['idAdministrador'];
        $activo = $_GET['activo'];
        if ($idAdministrador != "") {
            $controller->toggleAdministrador($idAdministrador, $activo);
        }
    }
}
$respuesta = $controller->obtenerAdministradores();
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
                    <div>Usuarios
                        <div class="page-title-subheading">.</div>
                    </div>
                </div>
                <div class="page-title-actions">


                    <button type="button" data-toggle="collapse" href="#collapseNuevoAdministrador" class="btn btn-primary">Nuevo Usuario</button>


                </div>
            </div>
        </div>

        <!-- aqui va el contenido de la página -->
        <div class="collapse" id="collapseNuevoAdministrador">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Nuevo Usuario</h5>
                    <form id="adminForm" class="col-md-10 mx-auto" method="post" action="index.php?p=administradores">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="nombre">Nombre</label>
                                    <div>
                                        <input type="text" maxlength="50" class="form-control" id="nombre" name="nombre" value="<?php if (isset($nombre)) {
                                                                                                                                    echo strtoupper($nombre);
                                                                                                                                } ?>" placeholder="Nombre" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="apellidos">Apellidos</label>
                                    <div>
                                        <input type="text" maxlength="50" class="form-control" id="apellidos" name="apellidos" value="<?php if (isset($apellidos)) {
                                                                                                                                            echo strtoupper($apellidos);
                                                                                                                                        } ?>" placeholder="Apellidos" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <label for="correo">Correo</label>
                                    <div>
                                        <input type="email" maxlength="100" class="form-control" id="correo" name="correo" value="<?php if (isset($correo)) {
                                                                                                                                        echo strtoupper($correo);
                                                                                                                                    } ?>" placeholder="Correo" />
                                    </div>
                                </div>
                                <div class="col-md-4">

                                    <label for="correo">Teléfono</label>
                                    <div>
                                        <input type="text" maxlength="15" class="form-control" id="telefono" name="telefono" value="<?php if (isset($telefono)) {
                                                                                                                                        echo strtoupper($telefono);
                                                                                                                                    } ?>" placeholder="Teléfono" />
                                    </div>

                                </div>
                                <div class="col-md-4">

                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-4 col-md-2">
                                    <label for="correo">Usuarios</label>
                                    <div>
                                        <input type="checkbox" name="usuarios" id="usuarios">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Clientes</label>
                                    <div>
                                        <input type="checkbox" name="clientes" id="clientes">
                                    </div>
                                </div>
                                <div class="col-4  col-md-2">
                                    <label for="correo">Proveedores</label>
                                    <div>
                                        <input type="checkbox" name="proveedores" id="proveedores">
                                    </div>
                                </div>

                                <div class=" col-4 col-md-2">
                                    <label for="correo">Ver Productos</label>
                                    <div>
                                        <input type="checkbox" name="productos" id="productos">
                                    </div>
                                </div>
                                <div class=" col-4 col-md-2">
                                    <label for="correo">Editar Productos</label>
                                    <div>
                                        <input type="checkbox" name="editarProductos" id="editarProductos">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Ord. Compra</label>
                                    <div>
                                        <input type="checkbox" name="ordCompra" id="ordCompra">
                                    </div>
                                </div>

                                <div class="col-4 col-md-2">
                                    <label for="correo">Crea Cotización</label>
                                    <div>
                                        <input type="checkbox" name="creaCot" id="creaCot">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Recepción Mat</label>
                                    <div>
                                        <input type="checkbox" name="recMat" id="recMat">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Gen. Remisión</label>
                                    <div>
                                        <input type="checkbox" name="genRem" id="genRem">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Calibres</label>
                                    <div>
                                        <input type="checkbox" name="calibres" id="calibres">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Tipos</label>
                                    <div>
                                        <input type="checkbox" name="tipos" id="tipos">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Autorizar Pedidos</label>
                                    <div>
                                        <input type="checkbox" name="autorizarPedidos" id="autorizarPedidos">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Produccion</label>
                                    <div>
                                        <input type="checkbox" name="producciones" id="producciones">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Elimina Cotización</label>
                                    <div>
                                        <input type="checkbox" name="eliminaCotizacion" id="eliminaCotizacion">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Elimina Orden Compra</label>
                                    <div>
                                        <input type="checkbox" name="eliminaOCompra" id="eliminaOCompra">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Cambiar Precios</label>
                                    <div>
                                        <input type="checkbox" name="cambiarPrecios" id="cambiarPrecios">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Devoluciones</label>
                                    <div>
                                        <input type="checkbox" name="devoluciones" id="devoluciones">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Dar Salidas</label>
                                    <div>
                                        <input type="checkbox" name="salidaInventario" id="salidaInventario">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Ver Inventarios</label>
                                    <div>
                                        <input type="checkbox" name="inventarios" id="inventarios">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Realizar Traspasos</label>
                                    <div>
                                        <input type="checkbox" name="traspasos" id="traspasos">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Ver todas las Cotizaciones</label>
                                    <div>
                                        <input type="checkbox" name="verCotizaciones" id="verCotizaciones">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Actualizar Cantidades Pedido</label>
                                    <div>
                                        <input type="checkbox" name="pedidoCantidades" id="pedidoCantidades">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Cancelar Remisiones</label>
                                    <div>
                                        <input type="checkbox" name="cancelarRemisiones" id="cancelarRemisiones">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Realizar Abonos</label>
                                    <div>
                                        <input type="checkbox" name="agregarAbonos" id="agregarAbonos">
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="correo">Cancelar Pedidos</label>
                                    <div>
                                        <input title="aún sin este permiso, cada usuario puede cancelar sus propios pedidos" type="checkbox" name="cancelarPedidos" id="cancelarPedidos">
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
                <table style="width: 100%;" id="administradores" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellidos</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Actualizar</th>
                            <th>Activo</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $reg) { ?>
                            <tr id="<?php echo $reg['idUsuario'] ?>">
                                <td data-target="nombre"><?php echo strtoupper($reg['nombre']) ?>
                                <td data-target="apellidos"><?php echo strtoupper($reg['apellidos']) ?></td>
                                <td data-target="correo"><?php echo strtoupper($reg['correo']) ?></td>
                                <td data-target="telefono"><?php echo strtoupper($reg['telefono']) ?></td>
                                <td><a href="#" class="btn btn-primary" data-role="updateAdministradores" data-id="<?php echo $reg['idUsuario'] ?>">Actualizar</a></td>
                                <td><a href="index.php?p=administradores&idAdministrador=<?php echo $reg['idUsuario'] ?>&activo=<?php if ($reg['activo'] == 1) {
                                                                                                                                    echo "0";
                                                                                                                                } else {
                                                                                                                                    echo "1";
                                                                                                                                } ?>" class="btn btn-primary"><?php if ($reg['activo'] == 1) {
                                                                                                                                                                    echo "Desactivar";
                                                                                                                                                                } else {
                                                                                                                                                                    echo "Activar";
                                                                                                                                                                } ?></a>



                                    <input class="cancelarPedidos" type="hidden" value="<?php echo $reg['cancelarPedidos'] ?>">
                                    <input class="agregarAbonos" type="hidden" value="<?php echo $reg['agregarAbonos'] ?>">
                                    <input class="pedidoCantidades" type="hidden" value="<?php echo $reg['pedidoCantidades'] ?>">
                                    <input class="cancelarRemisiones" type="hidden" value="<?php echo $reg['cancelarRemisiones'] ?>">
                                    <input class="clientes" type="hidden" value="<?php echo $reg['clientes'] ?>">
                                    <input class="productos" type="hidden" value="<?php echo $reg['productos'] ?>">
                                    <input class="calibres" type="hidden" value="<?php echo $reg['calibres'] ?>">
                                    <input class="tipos" type="hidden" value="<?php echo $reg['tipos'] ?>">
                                    <input class="proveedores" type="hidden" value="<?php echo $reg['proveedores'] ?>">
                                    <input class="producciones" type="hidden" value="<?php echo $reg['producciones'] ?>">
                                    <input class="usuarios" type="hidden" value="<?php echo $reg['usuarios'] ?>">
                                    <input class="autorizarPedidos" type="hidden" value="<?php echo $reg['autorizarPedidos'] ?>">
                                    <input class="editarProductos" type="hidden" value="<?php echo $reg['editarProductos'] ?>">
                                    <input class="ordCompra" type="hidden" value="<?php echo $reg['ordCompra'] ?>">
                                    <input class="creaCot" type="hidden" value="<?php echo $reg['creaCot'] ?>">
                                    <input class="recMat" type="hidden" value="<?php echo $reg['recMat'] ?>">
                                    <input class="calibres" type="hidden" value="<?php echo $reg['calibres'] ?>">
                                    <input class="tipos" type="hidden" value="<?php echo $reg['tipos'] ?>">
                                    <input class="eliminaCotizacion" type="hidden" value="<?php echo $reg['eliminaCotizacion'] ?>">
                                    <input class="cambiarPrecios" type="hidden" value="<?php echo $reg['cambiarPrecios'] ?>">
                                    <input class="devoluciones" type="hidden" value="<?php echo $reg['devoluciones'] ?>">
                                    <input class="eliminaOCompra" type="hidden" value="<?php echo $reg['eliminaOCompra'] ?>">
                                    <input class="salidaInventario" type="hidden" value="<?php echo $reg['salidaInventario'] ?>">
                                    <input class="traspasos" type="hidden" value="<?php echo $reg['traspasos'] ?>">
                                    <input class="verCotizaciones" type="hidden" value="<?php echo $reg['verCotizaciones'] ?>">
                                    <input class="inventarios" type="hidden" value="<?php echo $reg['inventarios'] ?>">
                                    <input class="genRem" type="hidden" value="<?php echo $reg['genRem'] ?>"></td>
                                </td>

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
        $('#administradores').DataTable({
            "lengthMenu": [
                [100, 200, -1],
                [100, 200, "Todos"]
            ]
        });
    });
</script>