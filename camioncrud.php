<?php

include_once './controllers/CatalogosController.php';

$controller = new CatalogosController();

$idCamion = $_POST['idCamion'];





  $camionM = $_POST['camionM'];
  $placasM = $_POST['placasM'];
  
  $respuesta = $controller->actualizarCamion($camionM,$placasM, $idCamion);
  if ($respuesta->exito) {
    echo "aqui puedes mandar un json, pero aqui no lo ocupamos";
  } else {
    echo $respuesta->mensaje;
  }

