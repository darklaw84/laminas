<?php 

include_once './controllers/AdministradorController.php';

$controller = new AdministradorController();

$idAdmin = $_POST['idAdmin'] ;
$tipo = $_POST['tipo'] ;

if ($idAdmin != "" && $tipo=="update") {
    
    $nombreAdmin = $_POST['nombreAdmin'] ;
    $correoAdmin = $_POST['correoAdmin'] ;
    $telefonoAdmin = $_POST['telefonoAdmin'] ;
    $apellidosAdmin = $_POST['apellidosAdmin'] ;

    $genRem = $_POST['genRem'] ;
    $recMat = $_POST['recMat'] ;
    $creaCot = $_POST['creaCot'] ;
    $ordCompra = $_POST['ordCompra'] ;
    $productos = $_POST['productos'] ;
    $proveedores = $_POST['proveedores'] ;
    $calibres = $_POST['calibres'] ;
    $tipos = $_POST['tipos'] ;
    $clientes = $_POST['clientes'] ;
    $producciones = $_POST['producciones'] ;
    $usuarios = $_POST['usuarios'] ;

    $eliminaCotizacion = $_POST['eliminaCotizacion'] ;
    $cambiarPrecios = $_POST['cambiarPrecios'] ;
    $devoluciones = $_POST['devoluciones'] ;
    $eliminaOCompra = $_POST['eliminaOCompra'] ;
    $salidaInventario = $_POST['salidaInventario'] ;
    $editarProductos = $_POST['editarProductos'] ;
    $autorizarPedidos = $_POST['autorizarPedidos'] ;

    $inventarios = $_POST['inventarios'] ;
    $verCotizaciones = $_POST['verCotizaciones'] ;
    $traspasos = $_POST['traspasos'] ;

    


    
    
    $respuesta = $controller->actualizarAdministrador($nombreAdmin, $apellidosAdmin,$correoAdmin,$telefonoAdmin, $idAdmin,
    $clientes,$proveedores,$productos, $ordCompra,$creaCot,$recMat,$calibres,$tipos,$producciones,$usuarios,$eliminaCotizacion,
    $cambiarPrecios,$devoluciones,$eliminaOCompra,$salidaInventario,
    $editarProductos,$autorizarPedidos,$genRem   ,$inventarios,$verCotizaciones,$traspasos);
    if ($respuesta->exito) {
      echo "aqui puedes mandar un json, pero aqui no lo ocupamos";
    }
    else
    {
        echo $respuesta->mensaje;
    }
}



?>