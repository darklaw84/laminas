<?php 
include_once './controllers/CotizacionController.php';

$contCot= new CotizacionController();


$id=$_POST['id'];
$activo=$_POST['activo'];
$numAlmacen=$_POST['numAlmacen'];


$contCot->togglePedido($id,$activo,$numAlmacen);

echo '{"exito":true}';
