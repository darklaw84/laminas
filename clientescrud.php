<?php

include_once './controllers/CatalogosController.php';

$controller = new CatalogosController();

$idCliente = $_POST['idCliente'];
$tipo = $_POST['tipo'];


if ($idCliente != "" && $tipo == "update") {

  $cliente = $_POST['cliente'];
  $rfc = $_POST['rfc'];
  $direccion = $_POST['direccion'];

  $tipoprecio = $_POST['tipoprecio'];
  $direccionentrega = $_POST['direccionentrega'];
  $comentarios = $_POST['comentarios'];
  $telefono = $_POST['telefono'];
  $mail = $_POST['mail'];
  $idUso = $_POST['idUso'];
  $idVendedor = $_POST['idVendedor'];

  $representante = $_POST['representante'];
  $respuesta = $controller->actualizarCliente($cliente, $idCliente,
   $rfc,$direccion,$representante
  ,$telefono,
  $mail,
  $tipoprecio,
  $comentarios,
  $idUso,
  $direccionentrega,$idVendedor);
  if ($respuesta->exito) {
    echo "aqui puedes mandar un json, pero aqui no lo ocupamos";
  } else {
    echo $respuesta->mensaje;
  }
}
