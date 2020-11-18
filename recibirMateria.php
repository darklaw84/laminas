<?php 
session_start();
include_once './controllers/OrdenesController.php';

$controller= new OrdenesController();




if (isset($_POST['idMateriaF'])) {
    $idMateriaF = $_POST['idMateriaF'];
    $idAlmacenF = $_POST['idAlmacenF'];
    $idUnidadF = $_POST['idUnidadF'];
    $cantidadF = $_POST['cantidadF'];
    $idProductoF = $_POST['idProductoF'];
    $pesoTeoricoF = $_POST['pesoTeoricoF'];
    $idOrden = $_POST['idOrden'];
    
    $controller->recibirMateria($idMateriaF, $_SESSION['nombreUsr'], 
    $idAlmacenF,$idUnidadF,$cantidadF,$idProductoF,$pesoTeoricoF,$idOrden);
}

echo '{"exito":true}';

?>