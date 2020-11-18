<?php 
session_start();
include_once './controllers/OrdenesController.php';


$controller= new OrdenesController();


$id=$_POST['id'];

$res=$controller->reimprimeRecepcion($id);



echo '{"exito":true}';
