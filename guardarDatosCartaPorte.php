<?php 
include_once './controllers/CotizacionController.php';

$contCot= new CotizacionController();

$id=$_POST['id'];
$placas=$_POST['placas'];
$contenedor=$_POST['contenedor'];
$operador=$_POST['operador'];
$tipoUnidad=$_POST['tipoUnidad'];


$contCot->actualizarCartaPorte($contenedor,
$placas,
$tipoUnidad,
$operador,
$id);

echo '{"exito":true}';

?>