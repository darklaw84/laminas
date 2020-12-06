<?php 
include_once './controllers/CotizacionController.php';

$contCot= new CotizacionController();


$id=$_POST['id'];


$contCot->cancelarRemision($id);

echo '{"exito":true}';
