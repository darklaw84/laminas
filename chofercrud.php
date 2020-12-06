<?php

include_once './controllers/CatalogosController.php';

$controller = new CatalogosController();

$idChofer = $_POST['idChofer'];





  $choferM = $_POST['choferM'];
  
  $respuesta = $controller->actualizarChofer($choferM, $idChofer);
  if ($respuesta->exito) {
    echo "aqui puedes mandar un json, pero aqui no lo ocupamos";
  } else {
    echo $respuesta->mensaje;
  }

