<?php 

include_once './controllers/CotizacionController.php';

$controller = new CotizacionController();

$idProducto = $_POST['idProducto'] ;

$controller->actualizarInventario($idProducto);

echo '{"exito":true}';

?>