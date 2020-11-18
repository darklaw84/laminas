<?php

include_once './controllers/CotizacionController.php';

$contCot= new CotizacionController();


$idCotizacion=$_POST['idCotizacion'];

$extras=$contCot->obtenerExtrasCotizacion($idCotizacion)->registros[0];

echo json_encode($extras);

?>