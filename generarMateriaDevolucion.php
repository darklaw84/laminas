<?php 
session_start();
include_once './controllers/CotizacionController.php';

$contCot= new CotizacionController();


$idCotizacionDetM=$_POST['idCotizacionDetM'];
$utilizadosM=$_POST['utilizadosM'];
$usuario= $_SESSION['nombreUsr'];
$idUsuario =$_SESSION['idUsr'];
$kilos =$_POST['kilos'];
$idProducto =$_POST['idProducto'];
$idAlmacen =$_POST['idAlmacen'];
$codigo =$_POST['codigo'];
$utilizadosUsM =$_POST['utilizadosUsM'];



$respuesta=$contCot->generarMateriaDevolucion($idCotizacionDetM, $kilos,$utilizadosM,
 $usuario,$idUsuario,$idProducto,$idAlmacen,$codigo,$utilizadosUsM);

echo json_encode($respuesta);

?>