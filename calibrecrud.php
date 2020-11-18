<?php

include_once './controllers/CatalogosController.php';

$controller = new CatalogosController();

$idCalibre = $_POST['idCalibre'];
$tipo = $_POST['tipo'];


if ($idCalibre != "" && $tipo == "update") {

  $calibre = $_POST['calibre'];
  
  $respuesta = $controller->actualizarCalibre($calibre, $idCalibre);
  if ($respuesta->exito) {
    echo "aqui puedes mandar un json, pero aqui no lo ocupamos";
  } else {
    echo $respuesta->mensaje;
  }
}
