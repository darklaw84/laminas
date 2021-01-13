<?php 
session_start();
include_once './controllers/CotizacionController.php';

$contCot= new CotizacionController();


$id=$_POST['id'];
$montoAbono=$_POST['montoAbono'];
$idFormaPago=$_POST['idFormaPago'];
$fechaAbono=$_POST['fechaAbono'];



$res=$contCot->realizarAbono($id,$montoAbono,$_SESSION['idUsr'],$idFormaPago,$fechaAbono);



echo json_encode($res);
