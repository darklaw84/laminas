<?php

include_once './controllers/CatalogosController.php';

$controller = new CatalogosController();

$idTipo = $_POST['idTipo'];
$tipo = $_POST['tipo'];


if ($idTipo != "" && $tipo == "update") {

  $tipoM = $_POST['tipoM'];
  
  $respuesta = $controller->actualizarTipo($tipoM, $idTipo);
  if ($respuesta->exito) {
    echo "aqui puedes mandar un json, pero aqui no lo ocupamos";
  } else {
    echo $respuesta->mensaje;
  }
}
