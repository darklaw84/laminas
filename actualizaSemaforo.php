<?php 
session_start();
include_once './controllers/CotizacionController.php';

$contCot= new CotizacionController();


$id=$_POST['idCotizacion'];
$color=$_POST['color'];

$res=$contCot->actualizaSemaforo($id,$color);



echo json_encode($res);
