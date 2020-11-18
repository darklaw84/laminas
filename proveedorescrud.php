<?php

include_once './controllers/CatalogosController.php';

$controller = new CatalogosController();

$idProveedor = $_POST['idProveedor'];
$tipo = $_POST['tipo'];




if ($idProveedor != "" && $tipo == "update") {

  $proveedor = $_POST['proveedor'];
  $telefono = $_POST['telefono'];
  $direccion = $_POST['direccion'];
  $rfc = $_POST['rfc'];
  $comentarios = $_POST['comentarios'];
  
  $respuesta = $controller->actualizarProveedor($proveedor, $idProveedor,$telefono,$comentarios,$rfc,$direccion);
  if ($respuesta->exito) {
    echo "aqui puedes mandar un json, pero aqui no lo ocupamos";
  } else {
    echo $respuesta->mensaje;
  }
}
