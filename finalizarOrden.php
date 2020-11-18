<?php 
session_start();
include_once './controllers/OrdenesController.php';

$contCot= new OrdenesController();


$idOrden=$_POST['idOrden'];

$res=$contCot->finalizarOrden($idOrden);



echo json_encode($res);
