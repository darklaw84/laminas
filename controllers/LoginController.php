<?php

include_once './config/database.php';
include_once './models/LoginModel.php';


class LoginController
{
    

    public function __construct()
    {
        
    }


    

    function registrarOrganizador($nombre,$apellidos,$organizacion,$telefono,$idEstado,$correo,$password)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase= new LoginModel($db);
        $respuesta = $clase->registrarOrganizador($nombre,$apellidos,$organizacion,$telefono,$idEstado,$correo,$password);
        
    return $respuesta;
    }

    function obtenerEstados($idPais)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase= new LoginModel($db);
        $respuesta = $clase->obtenerEstados($idPais);
        
    return $respuesta;
    }

    function login($correo,$password)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase= new LoginModel($db);
        $respuesta = $clase->login($correo,$password);
        return $respuesta;
    }



}


?>
 

