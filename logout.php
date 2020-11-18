<?php

session_start();

$_SESSION['idUsr']="";

echo "<script>window.setTimeout(function() { window.location = 'login.php' }, 10);</script>";


?>
