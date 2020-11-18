<?php 
session_start();
include_once './controllers/CotizacionController.php';

$contCot= new CotizacionController();


$id=$_POST['id'];
$costo=$_POST['costo'];

$res=$contCot->actualizarCostoEnvio($id,$costo);



echo json_encode($res);
