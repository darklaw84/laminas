<?php 
include_once './controllers/CatalogosController.php';

$contCot= new CatalogosController();


$idProducto=$_POST['idProducto'];



$respuesta=$contCot->eliminarProducto($idProducto);

echo json_encode($respuesta);
