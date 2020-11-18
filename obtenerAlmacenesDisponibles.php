<?php

include_once './controllers/CotizacionController.php';

$contCot= new CotizacionController();


$id=$_POST['id'];

$almacenes=$contCot->obtenerAlmacenesDisponibles($id)->registros;

echo json_encode($almacenes);

?>