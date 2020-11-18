<?php 

include_once './controllers/CatalogosController.php';

$controller = new CatalogosController();

$idProducto = $_POST['idProducto'] ;


if ($idProducto != "") {
    
    
    
    $respuesta = $controller->obtenerProducto($idProducto);
    if ($respuesta->exito) {
      $json=json_encode($respuesta->registros[0]);
      echo $json;
    }
    else
    {
        echo $respuesta->mensaje;
    }
}



?>