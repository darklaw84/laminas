<?php
session_start();
include_once './controllers/CotizacionController.php';

$controller = new CotizacionController();

$ids = $_POST['ids'];
$idPedido = $_POST['idPedido'];


$idUsuario= $_SESSION['idUsr'];
$usuario= $_SESSION['nombreUsr'];


  
  $respuesta = $controller->generarRemision($ids, $idUsuario,$usuario,$idPedido);
  
    echo json_encode($respuesta);
  

