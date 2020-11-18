<?php 
include_once './controllers/CotizacionController.php';

$contCot= new CotizacionController();


$idCotizacion=$_POST['idCotizacion'];


$contCot->generarPedido($idCotizacion);

echo "exito";

?>