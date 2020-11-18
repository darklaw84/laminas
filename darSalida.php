<?php
session_start();
include_once './controllers/CotizacionController.php';

$controller = new CotizacionController();

$idProduccion = $_POST['idProduccion'];


$idUsuario= $_SESSION['idUsr'];
$usuario= $_SESSION['nombreUsr'];


  
  $respuesta = $controller->darSalidaProduccion($idProduccion, $idUsuario,$usuario);
  
    echo json_encode($respuesta);
  

