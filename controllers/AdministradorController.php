<?php

include_once './config/database.php';
include_once './models/AdministradorModel.php';


class AdministradorController
{


    public function __construct()
    {
    }


    function obtenerAdministradores()
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new AdministradorModel($db);
        $respuesta = $clase->obtenerAdministradores();

        return $respuesta;
    }


    function obtenerAdministrador($idAdministador)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new AdministradorModel($db);
        $respuesta = $clase->obtenerAdministrador($idAdministador);

        return $respuesta;
    }

    function obtenerOrganizadores()
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new AdministradorModel($db);
        $respuesta = $clase->obtenerOrganizadores();

        return $respuesta;
    }

    function agregarAdministrador($nombre, $apellidos, $correo, $telefono, $password,
    $clientes,$proveedores,$productos, $ordCompra,$creaCot,$recMat,$calibres,$tipos,$producciones,$usuarios,$eliminaCotizacion,
    $cambiarPrecios,$devoluciones,$eliminaOCompra,$salidaInventario,$editarProductos,$autorizarPedidos,$genRem,$inventarios,$verCotizaciones,$traspasos)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new AdministradorModel($db);
        $respuesta = $clase->agregarAdministrador($nombre, $apellidos, $correo, $telefono, $password,
        $clientes,$proveedores,$productos, $ordCompra,$creaCot,$recMat,$calibres,$tipos,$producciones,$usuarios,$eliminaCotizacion,
        $cambiarPrecios,$devoluciones,$eliminaOCompra,$salidaInventario,$editarProductos,$autorizarPedidos,$genRem,$inventarios,$verCotizaciones,$traspasos);

        return $respuesta;
    }

    function actualizarAdministrador($nombre, $apellidos, $correo, $telefono, $idUsuario,
    $clientes,$proveedores,$productos, $ordCompra,$creaCot,$recMat,$calibres,$tipos,$producciones,$usuarios,$eliminaCotizacion,
    $cambiarPrecios,$devoluciones,$eliminaOCompra,$salidaInventario,$editarProductos,$autorizarPedidos,$genRem,$inventarios,$verCotizaciones,$traspasos)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new AdministradorModel($db);
        $respuesta = $clase->actualizarAdministrador($nombre, $apellidos, $correo, $telefono, $idUsuario,
        $clientes,$proveedores,$productos, $ordCompra,$creaCot,$recMat,$calibres,$tipos,$producciones,$usuarios,$eliminaCotizacion,
        $cambiarPrecios,$devoluciones,$eliminaOCompra,$salidaInventario,$editarProductos,$autorizarPedidos,$genRem,$inventarios,$verCotizaciones,$traspasos);
        return $respuesta;
    }


    function actualizarParametro($nombreParametro, $valorParametro)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new AdministradorModel($db);
        $respuesta = $clase->actualizarParametro($nombreParametro, $valorParametro);
        return $respuesta;
    }


    

    function actualizarOrganizador(
        $nombre,
        $apellidos,
        $correo,
        $telefono,
        $idEstado,
        $organizacion,
        $idUsuario
    ) {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new AdministradorModel($db);
        $respuesta = $clase->actualizarOrganizador(
            $nombre,
            $apellidos,
            $correo,
            $telefono,
            $idEstado,
            $organizacion,
            $idUsuario
        );
        return $respuesta;
    }



    function toggleAdministrador($idAdministrador, $activo)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new AdministradorModel($db);
        $respuesta = $clase->toggleAdministrador($idAdministrador, $activo);
        return $respuesta;
    }

    function actualizarPassword($idUsuario, $passanterior, $passnuevo)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new AdministradorModel($db);
        $respuesta = $clase->actualizarPassword($idUsuario, $passanterior, $passnuevo);
        return $respuesta;
    }


    function  obtenerValorParametro($nombreParametro)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new AdministradorModel($db);
        $respuesta = $clase-> obtenerValorParametro($nombreParametro);
        return $respuesta;
    }


   
}
