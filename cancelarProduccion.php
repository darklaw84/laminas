<?php 
session_start();
include_once './controllers/CotizacionController.php';

$contCot= new CotizacionController();


$id=$_POST['id'];

$res=$contCot->cancelarProduccion($id);



echo json_encode($res);
