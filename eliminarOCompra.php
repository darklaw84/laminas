<?php 
session_start();
include_once './controllers/OrdenesController.php';

$contCot= new OrdenesController();


$id=$_POST['id'];

$res=$contCot->eliminarOrden($id);



echo json_encode($res);
