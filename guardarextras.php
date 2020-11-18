<?php 
include_once './controllers/CotizacionController.php';

$contCot= new CotizacionController();

$observaciones=$_POST['observaciones'];
$condiciones=$_POST['condiciones'];
$vigencia=$_POST['vigencia'];
$forma=$_POST['forma'];
$lugar=$_POST['lugar'];
$idCotizacion=$_POST['idCotizacion'];
$fechaentrega=$_POST['fechaentrega'];


$contCot->actualizarCotizacion($fechaentrega,$lugar,$forma,
$vigencia,$condiciones,$observaciones,$idCotizacion);

echo "exico";

?>