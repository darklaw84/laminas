<?php

include_once './config/database.php';
include_once './models/CatalogosModel.php';
include_once './controllers/AdministradorController.php';


class CatalogosController
{


    public function __construct()
    {
    }




    function obtenerProductos()
    {

        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $contAd = new AdministradorController();
        $respuesta = $clase->obtenerProductos();

        return $respuesta;
    }

    function obtenerProductosEntrada()
    {

        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $contAd = new AdministradorController();
        $respuesta = $clase->obtenerProductosEntrada();

        return $respuesta;
    }


    function obtenerProductosEntradaConComprometido()
    {

        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $contAd = new AdministradorController();
        $respuesta = $clase->obtenerProductosEntradaConComprometido();

        return $respuesta;
    }

    


    function obtenerProductosSalida()
    {

        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $contAd = new AdministradorController();
        $respuesta = $clase->obtenerProductosSalida();

        return $respuesta;
    }



    function obtenerInventario($idProducto)
    {

        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);

        $salidas = $clase->obtenerSalidas($idProducto)->registros;
        $recepciones = $clase->obtenerRecepciones($idProducto)->registros;
        $traspasos = $clase->obtenerTraspasos($idProducto)->registros;

        $inventario = array_merge($salidas, $recepciones);
        $inventario = array_merge($inventario, $traspasos);

        return $inventario;
    }




    function obtenerUnidades()
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->obtenerUnidades();

        return $respuesta;
    }


    function agregarProducto(
        $producto,
        $idCalibre,
        $idTipo,
        $idUnidad,
        $pesoTeorico,
        $precioGen,
        $precioRev,
        $sku,
        $idAncho,
        $largo,
        $idUnidadFactura,
        $chkSalida,
        $chkEntrada,
        $medidas,
        $idMateriaPrima
    ) {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->agregarProducto(
            $producto,
            $idCalibre,
            $idTipo,
            $idUnidad,
            $pesoTeorico,
            $precioGen,
            $precioRev,
            $sku,
            $idAncho,
            $largo,
            $idUnidadFactura,
            $chkSalida,
            $chkEntrada,
            $medidas,
            $idMateriaPrima
        );
        return $respuesta;
    }


    function actualizarProducto(
        $producto,
        $idProducto,
        $idCalibre,
        $idTipo,
        $idUnidad,
        $pesoTeorico,
        $precioGen,
        $precioRev,
        $idAncho,
        $largo,
        $idUnidadFactura,
        $entrada,
        $salida,
        $medidasreves,
        $idMateriaPrima
    ) {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->actualizarProducto(
            $producto,
            $idProducto,
            $idCalibre,
            $idTipo,
            $idUnidad,
            $pesoTeorico,
            $precioGen,
            $precioRev,
            $idAncho,
            $largo,
            $idUnidadFactura,
            $entrada,
            $salida,
            $medidasreves,
            $idMateriaPrima
        );
        return $respuesta;
    }


    function obtenerProducto($idProducto)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $contAd = new AdministradorController();
        $respuesta = $clase->obtenerProducto($idProducto);
        return $respuesta;
    }





    function actualizarCliente(
        $cliente,
        $idCliente,
        $clave,
        $direccion,
        $representante,
        $telefono,
        $mail,
        $tipoprecio,
        $comentarios,
        $idUso,
        $direccionentrega,
        $idVendedor
    ) {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->actualizarCliente(
            $cliente,
            $idCliente,
            $clave,
            $direccion,
            $representante,
            $telefono,
            $mail,
            $tipoprecio,
            $comentarios,
            $idUso,
            $direccionentrega,
            $idVendedor
        );
        return $respuesta;
    }


    function agregarCliente($cliente, $rfc, $direccion, $representante, $telefono, $mail, $tipoprecio, $comentarios, $idUso, $direccionentrega, $idVendedor)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->agregarCliente($cliente, $rfc, $direccion, $representante, $telefono, $mail, $tipoprecio, $comentarios, $idUso, $direccionentrega, $idVendedor);
        return $respuesta;
    }

    function actualizarTipo($tipo, $idTipo)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->actualizarTipo($tipo, $idTipo);
        return $respuesta;
    }


    function actualizarAncho($tipo, $idTipo)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->actualizarAncho($tipo, $idTipo);
        return $respuesta;
    }

    function eliminarProducto($idProducto)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->eliminarProducto($idProducto);
        return $respuesta;
    }


    function actualizarChofer($chofer, $idChofer)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->actualizarChofer($chofer, $idChofer);
        return $respuesta;
    }


    function actualizarCamion($camion, $placas, $idCamion)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->actualizarCamion($camion, $placas, $idCamion);
        return $respuesta;
    }


    function agregarCalibre($calibre)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->agregarCalibre($calibre);
        return $respuesta;
    }


    function actualizarCalibre($calibre, $idCalibre)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->actualizarCalibre($calibre, $idCalibre);
        return $respuesta;
    }


    function agregarTipo($tipo)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->agregarTipo($tipo);
        return $respuesta;
    }


    function agregarChofer($chofer)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->agregarChofer($chofer);
        return $respuesta;
    }


    function agregarCamion($camion, $placas)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->agregarCamion($camion, $placas);
        return $respuesta;
    }



    function agregarLargo($tipo)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->agregarLargo($tipo);
        return $respuesta;
    }


    function agregarAncho($tipo)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->agregarAncho($tipo);
        return $respuesta;
    }

    function obtenerclientes()
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->obtenerclientes();
        return $respuesta;
    }

    function obtenerUsos()
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->obtenerUsos();
        return $respuesta;
    }

    function obtenerTipos()
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->obtenerTipos();
        return $respuesta;
    }


    function obtenerCamiones()
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->obtenerCamiones();
        return $respuesta;
    }


    function obtenerChoferes()
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->obtenerChoferes();
        return $respuesta;
    }


    function obtenerLargos($activos)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->obtenerLargos($activos);
        return $respuesta;
    }


    function obtenerAnchos($activos)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->obtenerAnchos($activos);
        return $respuesta;
    }

    function obtenerFormasPago($activos)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->obtenerFormasPago($activos);
        return $respuesta;
    }

    function obtenerTiposActivos()
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->obtenerTiposActivos();
        return $respuesta;
    }

    function obtenerCalibres()
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->obtenerCalibres();
        return $respuesta;
    }

    function obtenerAlmacenes()
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->obtenerAlmacenes();
        return $respuesta;
    }


    function obtenerRecepcionesGlobal()
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->obtenerRecepcionesGlobal();
        return $respuesta;
    }

    function obtenerAlmacenesDisponibles($idRecepcion)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->obtenerAlmacenesDisponibles($idRecepcion);
        return $respuesta;
    }


    function toggleCliente($idCliente, $activo)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->toggleCliente($idCliente, $activo);
        return $respuesta;
    }

    function toggleCalibre($idCalibre, $activo)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->toggleCalibre($idCalibre, $activo);
        return $respuesta;
    }

    function toggleTipo($idTipo, $activo)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->toggleTipo($idTipo, $activo);
        return $respuesta;
    }


    function toggleCamion($idCamion, $activo)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->toggleCamion($idCamion, $activo);
        return $respuesta;
    }


    function toggleChofer($idChofer, $activo)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->toggleChofer($idChofer, $activo);
        return $respuesta;
    }


    function toggleLargo($idTipo, $activo)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->toggleLargo($idTipo, $activo);
        return $respuesta;
    }



    function toggleAncho($idTipo, $activo)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->toggleAncho($idTipo, $activo);
        return $respuesta;
    }




    function actualizarProveedor($proveedor, $idProveedor, $telefono, $comentarios, $rfc, $direccion)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->actualizarProveedor($proveedor, $idProveedor, $telefono, $comentarios, $rfc, $direccion);
        return $respuesta;
    }


    function agregarProveedor($proveedor, $telefono, $comentarios, $rfc, $direccion)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->agregarProveedor($proveedor, $telefono, $comentarios, $rfc, $direccion);
        return $respuesta;
    }

    function obtenerProveedores()
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->obtenerProveedores();
        return $respuesta;
    }


    function toggleProveedor($idProveedor, $activo)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->toggleProveedor($idProveedor, $activo);
        return $respuesta;
    }

    function toggleProducto($idProducto, $activo)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CatalogosModel($db);
        $respuesta = $clase->toggleProducto($idProducto, $activo);
        return $respuesta;
    }
}
