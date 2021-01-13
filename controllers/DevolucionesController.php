<?php

include_once './config/database.php';
include_once './models/DevolucionesModel.php';


class DevolucionesController
{
    

    public function __construct()
    {
        
    }


    

    function insertarDevolucion($idUsuario,$idProducto,$cantidad,$kilos,$idAlmacen)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase= new DevolucionesModel($db);
        $respuesta = $clase-> insertarDevolucion($idUsuario,$idProducto,$cantidad,$kilos,$idAlmacen);
        
    return $respuesta;
    }

    function eliminarDevolucion($idDevolucion)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase= new DevolucionesModel($db);
        $respuesta = $clase-> eliminarDevolucion($idDevolucion);
        
    return $respuesta;
    }


    function obtenerDevoluciones()
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase= new DevolucionesModel($db);
        $respuesta = $clase-> obtenerDevoluciones();
        
    return $respuesta;
    }


}
