<?php 
session_start();
include_once './controllers/CotizacionController.php';

$contCot= new CotizacionController();


$codigo=$_POST['codigo'];
$idProducto=$_POST['idProducto'];
$utilizadosM=$_POST['utilizadosM'];

$res=$contCot->validarCodigoBarras($codigo,$idProducto,$utilizadosM);



echo json_encode($res);
