<?php

include_once './controllers/CatalogosController.php';

$controller = new CatalogosController();

$idProducto = $_POST['idProducto'];
$tipo = $_POST['tipo'];


if ($idProducto != "" && $tipo == "update") {

  $producto = $_POST['producto'];
  $idCalibre = $_POST['idCalibre'];
  $idTipo = $_POST['idTipo'];
  $idUnidad = $_POST['idUnidad'];

  $idUnidadFactura = $_POST['idUnidadFactura'];
  $largo = $_POST['largo'];
  $idAncho = $_POST['idAncho'];
  $pesoTeorico = $_POST['pesoTeorico'];
  $precioRev = $_POST['precioRev'];
  $precioGen = $_POST['precioGen'];
  $entrada = $_POST['entrada'];
  $salida = $_POST['salida'];

  $medidasreves = $_POST['medidasreves'];
  
 
  $respuesta = $controller->actualizarProducto($producto, $idProducto,$idCalibre,
  $idTipo,$idUnidad,$pesoTeorico,$precioGen,$precioRev,$idAncho,$largo,$idUnidadFactura,$entrada,$salida,$medidasreves);
  if ($respuesta->exito) {
    echo "aqui puedes mandar un json, pero aqui no lo ocupamos";
  } else {
    echo $respuesta->mensaje;
  }
}
