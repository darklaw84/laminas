<?php 

session_start();
include_once './controllers/CotizacionController.php';

$contCot= new CotizacionController();


$idAlmacenTraspaso=$_POST['idAlmacenTraspaso'];
$id=$_POST['id'];
$idChofer=$_POST['idChofer'];


$contCot->realizarTraspaso($id, $idAlmacenTraspaso,$_SESSION['idUsr'],$idChofer);

echo '{"exito":true}';

?>