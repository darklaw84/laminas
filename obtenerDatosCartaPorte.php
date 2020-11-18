<?php

include_once './controllers/CotizacionController.php';

$contCot= new CotizacionController();


$id=$_POST['id'];

$extras=$contCot->obtenerDatosCartaPorte($id)->registros[0];

echo json_encode($extras);

?>