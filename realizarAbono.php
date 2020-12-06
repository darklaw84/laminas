<?php 
session_start();
include_once './controllers/CotizacionController.php';

$contCot= new CotizacionController();


$id=$_POST['id'];
$montoAbono=$_POST['montoAbono'];
$idFormaPago=$_POST['idFormaPago'];



$res=$contCot->realizarAbono($id,$montoAbono,$_SESSION['idUsr'],$idFormaPago);



echo json_encode($res);
