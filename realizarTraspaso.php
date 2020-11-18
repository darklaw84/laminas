<?php 

session_start();
include_once './controllers/CotizacionController.php';

$contCot= new CotizacionController();


$idAlmacenTraspaso=$_POST['idAlmacenTraspaso'];
$id=$_POST['id'];


$contCot->realizarTraspaso($id, $idAlmacenTraspaso,$_SESSION['idUsr']);

echo '{"exito":true}';

?>