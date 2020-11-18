<?php

include_once './controllers/CatalogosController.php';

$controller = new CatalogosController();

$idAncho = $_POST['idAncho'];





  $anchoM = $_POST['anchoM'];
  
  $respuesta = $controller->actualizarAncho($anchoM, $idAncho);
  if ($respuesta->exito) {
    echo "aqui puedes mandar un json, pero aqui no lo ocupamos";
  } else {
    echo $respuesta->mensaje;
  }

