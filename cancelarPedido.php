<?php 
include_once './controllers/CotizacionController.php';

$contCot= new CotizacionController();


$id=$_POST['id'];


$contCot->cancelarPedido($id);

echo '{"exito":true}';
