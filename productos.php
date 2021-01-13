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

$chkEntrada = "";
$chkSalida = "";
if ($entro != "") {

    $producto = $_POST['producto'];
    $sku = $_POST['sku'];
    $idCalibre = $_POST['idCalibre'];
    $idTipo = $_POST['idTipo'];
    $idUnidad = $_POST['idUnidad'];
    $idUnidadFactura = $_POST['idUnidadFactura'];
    $largo = $_POST['largo'];
    $idAncho = $_POST['idAncho'];
    $idMateriaPrima = $_POST['idMateriaPrima'];
    $precioGen = $_POST['precioGen'];
    $precioRev = $_POST['precioRev'];

    if (isset($_POST['chkEntrada'])) {
        $chkEntrada = "1";
    } else {
        $chkEntrada = "0";
    }


    if (isset($_POST['chkSalida'])) {
        $chkSalida = "1";
    } else {
        $chkSalida = "0";
    }



    if ($idUnidad == 2 || $idUnidad == 1) {
        $pesoTeorico = $_POST['pesoTeorico'];
    } else {
        $pesoTeorico = 0;
    }

    if ($chkEntrada == "" && $chkSalida == "") {
        $mensajeEnviar = "Debes asignar el producto a entrada, a salida o a ambos";
    } else {

        if (($idUnidad == 2 || $idUnidad == 1) && $pesoTeorico == "") {
            $mensajeEnviar = "Si ingresas un producto en Metros, es obligatorio el peso teórico";
        } else {

            if ($producto == "" || $precioGen == "" || $precioRev == "") {
                $mensajeEnviar = "Todos los campos son obligatorios, por favor verifique";
            } else {



                $respuesta = $controller->agregarProducto(
                    $producto,
                    $idCalibre,
                    $idTipo,
                    $idUnidad,
                    $pesoTeorico,
                    $precioGen,
                    $precioRev,
                    $sku,
                    $idAncho,
                    $largo,
                    $idUnidadFactura,
                    $chkSalida,
                    $chkEntrada,
                    isset($_POST['medidas']),
                    $idMateriaPrima
                );

                if (!$respuesta->exito) {
                    $mensajeEnviar = $respuesta->mensaje;
                } else {
                    $producto = "";
                    $idCalibre = "";
                    $idTipo = "";
                    $idUnidad = "";
                    $precioGen = "";
                    $sku = "";
                    $precioRev = "";
                    $idUnidadFactura = "";

                    $chkEntrada = "";
                    $chkSalida = "";
                    $largo = "";
                }
            }
        }
    }
} else {
    if (isset($_GET['idProducto'])) {
        $idProducto = $_GET['idProducto'];
        $activo = $_GET['activo'];
        if ($idProducto != "") {
            $controller->toggleProducto($idProducto, $activo);
        }
    }
}
$respuesta = $controller->obtenerProductos();
$registros = $respuesta->registros;


$respuesta = $controller->obtenerCalibres();
$calibres = $respuesta->registros;


$res = $controller->obtenerTiposActivos();
$tipos = $res->registros;


$res = $controller->obtenerUnidades();
$unidades = $res->registros;


$res = $controller->obtenerAnchos(true);
$anchos = $res->registros;



$respuesta = $controller->obtenerProductosEntrada();
$productosEntrada = $respuesta->registros;




?>




<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-gift icon-gradient bg-ripe-malin"></i>
                    </div>
                    <div>Productos
                        <div class="page-title-subheading">.</div>
                    </div>
                </div>
                <div class="page-title-actions">


                    <button type="button" data-toggle="collapse" href="#collapseNuevoAdministrador" class="btn btn-primary">Nuevo Producto</button>


                </div>
            </div>
        </div>

        <!-- aqui va el contenido de la página -->
        <div class="collapse" id="collapseNuevoAdministrador">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <h5 class="card-title">Nuevo Producto</h5>
                    <form id="adminForm" class="col-md-10 mx-auto" method="post" action="index.php?p=productos">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="sku">SKU</label>
                                    <div>
                                        <input type="text" maxlength="50" class="form-control" id="sku" name="sku" value="<?php if (isset($sku)) {
                                                                                                                                echo strtoupper($sku);
                                                                                                                            } ?>" placeholder="sku" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="producto">Producto</label>
                                    <div>
                                        <input type="text" maxlength="50" class="form-control" id="producto" name="producto" value="<?php if (isset($producto)) {
                                                                                                                                        echo strtoupper($producto);
                                                                                                                                    } ?>" placeholder="producto" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="producto">Largo</label>
                                    <div>
                                        <input type="text" maxlength="10" class="form-control" id="largo" name="largo" value="<?php if (isset($largo)) {
                                                                                                                                    echo strtoupper($largo);
                                                                                                                                } ?>" placeholder="Largo" />
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <label for="idAncho">Ancho</label>
                                    <div>
                                        <select class=" form-control " id="idAncho" name="idAncho">
                                            <?php
                                            if (isset($anchos)) {
                                                foreach ($anchos as $uni) {
                                                    echo '<option value="' . $uni['idAncho'] . '" >' .
                                                        strtoupper($uni['ancho']) . '</option>';
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <label for="idCalibre">Calibre</label>
                                    <div>
                                        <select class=" form-control " name="idCalibre">
                                            <?php
                                            if (isset($calibres)) {
                                                foreach ($calibres as $uni) {
                                                    echo '<option value="' . $uni['idCalibre'] . '" >' .
                                                        strtoupper($uni['calibre']) . '</option>';
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                </div>


                            </div>

                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <label for="idUnidad">Unidad</label>
                                    <div>
                                        <select class=" form-control " id="idUnidad" name="idUnidad">
                                            <?php
                                            if (isset($unidades)) {
                                                foreach ($unidades as $uni) {
                                                    echo '<option value="' . $uni['idUnidad'] . '" >' .
                                                        strtoupper($uni['unidad']) . '</option>';
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="idTipo">Material</label>
                                    <div>
                                        <select class=" form-control " name="idTipo">

                                            <?php
                                            if (isset($tipos)) {
                                                foreach ($tipos as $uni) {
                                                    echo '<option value="' . $uni['idTipo'] . '"  >' .
                                                        strtoupper($uni['tipo']) . '</option>';
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="idUnidad">Unidad Factura</label>
                                    <div>
                                        <select class=" form-control " id="idUnidadFactura" name="idUnidadFactura">
                                            <?php
                                            if (isset($unidades)) {
                                                foreach ($unidades as $uni) {
                                                    echo '<option value="' . $uni['idUnidad'] . '" >' .
                                                        strtoupper($uni['unidad']) . '</option>';
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-6 col-md-4">
                                    <label for="idCalibre">Precio General Sin IVA</label>
                                    <div>
                                        <input type="number" step=".000001" maxlength="8" class="form-control" id="precioGen" name="precioGen" value="<?php if (isset($precioGen)) {
                                                                                                                                                            echo strtoupper($precioGen);
                                                                                                                                                        } ?>" />
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <label for="idTipo">Precio Revendedores sin IVA</label>
                                    <div>
                                        <input type="number" step=".000001" maxlength="8" class="form-control" id="precioRev" name="precioRev" value="<?php if (isset($precioRev)) {
                                                                                                                                                            echo strtoupper($precioRev);
                                                                                                                                                        } ?>" />

                                    </div>
                                </div>
                                <div class="col-6 col-md-2">
                                    <label for="idTipo">Entrada</label>
                                    <div>
                                        <input type="checkbox" id="chkEntrada" name="chkEntrada" <?php if ($chkEntrada == "1") {
                                                                                                        echo "checked";
                                                                                                    } ?>>
                                    </div>
                                </div>
                                <div class="col-6 col-md-2">
                                    <label for="idTipo">Salida</label>
                                    <div>
                                        <input type="checkbox" id="chkSalida" name="chkSalida" <?php if ($chkSalida == "1") {
                                                                                                    echo "checked";
                                                                                                } ?>>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <label for="idCalibre">Materia Prima</label>
                                    <div>
                                        <select class=" form-control " name="idMateriaPrima">
                                            <option value="0">N/A</option>
                                            <?php
                                            if (isset($productosEntrada)) {
                                                foreach ($productosEntrada as $ins) {
                                                    echo '<option value="' . $ins['idProducto'] . '" >' .
                                                        strtoupper($ins['sku'] . " - " . $ins['producto'] . " - " . $ins['ancho'] . " - " . $ins['calibre'] . " - " . $ins['tipo']) . '</option>';
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4 col-md-2">
                                    <label for="medidas">Medidas al revés</label>
                                    <div>
                                        <input type="checkbox" name="medidas" id="medidas">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="formFactor" style="display:none;" class="form-group">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <label for="idCalibre">Peso Teórico Kgs. / UM</label>
                                    <div>
                                        <input type="number" step=".000001" maxlength="20" class="form-control" id="pesoTeorico" name="pesoTeorico" value="<?php if (isset($pesoTeorico)) {
                                                                                                                                                                echo strtoupper($pesoTeorico);
                                                                                                                                                            } ?>" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="idCalibre">&nbsp;</label>
                                    <div>

                                    </div>
                                </div>

                            </div>
                        </div>



                        <div class="form-group">
                            <input type="hidden" name="entro" value="1" />
                            <input type="hidden" id="cambiarPrecios" value="<?php echo $_SESSION['cambiarPrecios']; ?>" />


                            <button type="submit" class="btn btn-primary" name="signup" value="Sign up">Crear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="main-card mb-3 card">
            <div class="card-body">
                <table style="width: 100%;" id="productos" class="table table-striped table-bordered dt-responsive nowrap">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Producto</th>
                            <th>Material</th>
                            <th>Calibre</th>
                            <th>Largo</th>
                            <th>Ancho</th>



                            <th>Unidad</th>
                            <th>Peso UM</th>
                            <th>$ Gen</th>
                            <th>$ Rev</th>
                            <th>Tipo</th>
                            <th><?php if (isset($_SESSION['editarProductos']) && $_SESSION['editarProductos'] == "1") {
                                    echo "Actualizar";
                                } else {
                                    echo "Ver";
                                } ?></th>
                            <th>Activo</th>


                            <th>Uni Fac</th>
                            <th>X</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $reg) { ?>
                            <tr id="TR<?php echo $reg['idProducto'] ?>">
                                <td data-target="sku"><?php echo strtoupper($reg['sku']) ?></td>
                                <td data-target="producto"><?php echo strtoupper($reg['producto']) ?></td>
                                <td data-target="tipo"><?php echo strtoupper($reg['tipo']) ?></td>
                                <td data-target="calibre"><?php echo strtoupper($reg['calibre']) ?></td>

                                <td data-target="largo"><?php echo strtoupper($reg['largo']) ?></td>
                                <td data-target="ancho"><?php echo strtoupper($reg['ancho']) ?></td>

                                <td data-target="unidad"><?php echo strtoupper($reg['unidad']) ?></td>
                                <td data-target="pesoTeorico"><?php echo strtoupper($reg['pesoTeorico']) ?></td>
                                <td data-target="precioGen"><?php echo strtoupper($reg['precioGen']) ?></td>
                                <td data-target="precioRev"><?php echo strtoupper($reg['precioRev']) ?></td>
                                <td><?php if($reg['entrada']=="1"){ echo ".";}else if($reg['salida']=="1"){ echo "..";} ?><img class="imgEntrada" src="imagenes/in.png" height="10px" style="display: <?php if ($reg['entrada'] == "1") {
                                                                                                                    echo "block";
                                                                                                                } else {
                                                                                                                    echo "none";
                                                                                                                } ?>;">
                                    <img class="imgSalida" src="imagenes/out.png" height="10px" style="display: <?php if ($reg['salida'] == "1") {
                                                                                                                    echo "block";
                                                                                                                } else {
                                                                                                                    echo "none";
                                                                                                                } ?>;"></td>
                                <td><a href="#" class="btn btn-primary" data-role="updateProductos" data-id="<?php echo $reg['idProducto'] ?>"><?php if (isset($_SESSION['editarProductos']) && $_SESSION['editarProductos'] == "1") {
                                                                                                                                                    echo "Actualizar";
                                                                                                                                                } else {
                                                                                                                                                    echo "Ver";
                                                                                                                                                } ?></a></td>
                                <td> <input class="idCalibre" type="hidden" value="<?php echo $reg['idCalibre'] ?>">
                                    <input class="idTipo" type="hidden" value="<?php echo $reg['idTipo'] ?>">
                                    <input class="idAncho" type="hidden" value="<?php echo $reg['idAncho'] ?>">
                                    <input class="entrada" type="hidden" value="<?php echo $reg['entrada'] ?>">
                                    <input class="medidasreves" type="hidden" value="<?php echo $reg['medidasreves'] ?>">
                                    <input class="salida" type="hidden" value="<?php echo $reg['salida'] ?>">
                                    <input class="idMateriaPrima" type="hidden" value="<?php echo $reg['idMateriaPrima'] ?>">
                                    <input class="idUnidad" type="hidden" value="<?php echo $reg['idUnidad'] ?>">
                                    <input class="idUnidadFactura" type="hidden" value="<?php echo $reg['idUnidadFactura'] ?>"><a href="index.php?p=productos&idProducto=<?php echo $reg['idProducto'] ?>&activo=<?php if ($reg['activo'] == 1) {
                                                                                                                                                                                                                        echo "0";
                                                                                                                                                                                                                    } else {
                                                                                                                                                                                                                        echo "1";
                                                                                                                                                                                                                    } ?>" class="btn btn-primary"><?php if ($reg['activo'] == 1) {
                                                                                                                                                                                                                                                        echo "Activado";
                                                                                                                                                                                                                                                    } else {
                                                                                                                                                                                                                                                        echo "Desactivado";
                                                                                                                                                                                                                                                    } ?></a></td>



                                <td data-target="unidadFactura"><?php echo strtoupper($reg['unidadFactura']) ?></td>
                                <td> <a href="#" class="btn btn-primary" data-role="eliminarProducto" data-id="<?php echo $reg['idProducto'] ?>">X</a></td>
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
        $('#productos').DataTable({
            "lengthMenu": [
                [-1, 100, 200],
                ["Todos", 100, 200]
            ]
        });
    });
</script>