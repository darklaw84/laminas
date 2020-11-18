<?php 
session_start();
include_once './controllers/OrdenesController.php';


$controller= new OrdenesController();


$id=$_POST['id'];

$res=$controller->reimprimeProduccion($id);



echo '{"exito":true}';
