<?php

include_once './config/database.php';
include_once './models/CotizacionesModel.php';


class CotizacionController
{


    public function __construct()
    {
    }




    function obtenerCotizaciones($tipo, $idUsuario)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->obtenerCotizaciones($tipo, $idUsuario);

        return $respuesta;
    }


    function obtenerRemisiones()
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->obtenerRemisiones();

        return $respuesta;
    }


    function obtenerRemision($idRemision)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->obtenerRemision($idRemision);

        return $respuesta;
    }

    function obtenerSalidas($cantidad)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->obtenerSalidas($cantidad);

        return $respuesta;
    }

    function darSalidaProduccion($idProduccion, $idUsuario, $usuario)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->darSalidaProduccion($idProduccion, $idUsuario, $usuario);

        return $respuesta;
    }


    function generarRemision($ids, $idUsuario, $usuario, $idPedido)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->generarRemision($ids, $idUsuario, $usuario, $idPedido);

        return $respuesta;
    }




    function actualizarInventario($idProducto)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $kilosRecepciones = $clase->obtenerTotalKilosRecepciones($idProducto)->valor;
        $kilosProducidos = $clase->obtenerTotalKilosProducidos($idProducto)->valor;
        $clase->actualizaInventarioProducto($idProducto, $kilosRecepciones - $kilosProducidos);

        return true;
    }

    function togglePedido($idPedido, $activo)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->togglePedido($idPedido, $activo);

        return $respuesta;
    }


    function obtenerCotizacion($idCotizacion)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->obtenerCotizacion($idCotizacion);

        return $respuesta;
    }

    function obtenerProduccion($idProduccion)
    {
        $idProduccion = str_replace("P", "", $idProduccion);
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->obtenerProduccion($idProduccion);

        return $respuesta;
    }


    function obtenerUltimasProducciones($cant)
    {

        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->obtenerUltimasProducciones($cant);

        return $respuesta;
    }




    function actualizarCantidadCotDet($idCotizacionDet, $cantidad)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->actualizarCantidadCotDet($idCotizacionDet, $cantidad);

        return $respuesta;
    }

    function actualizarMetrosCotDet($idCotizacionDet, $metros)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->actualizarMetrosCotDet($idCotizacionDet, $metros,$db);

        return $respuesta;
    }


    function realizarTraspaso($idRecepcion, $idAlmacen,$idUsuario)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->realizarTraspaso($idRecepcion, $idAlmacen,$idUsuario);

        return $respuesta;
    }


    function  generarPedido($idCotizacion)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->generarPedido($idCotizacion);

        return $respuesta;
    }

    function generarMateriaProduccion(
        $idCotizacionDetM,
        $kilos,
        $cantidad,
        $usuario,
        $idUsuario,
        $idProducto,
        $idAlmacen,
        $codigo,$utilizadosUsM
    ) {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->generarMateriaProduccion(
            $idCotizacionDetM,
            $kilos,
            $cantidad,
            $usuario,
            $idUsuario,
            $idProducto,
            $idAlmacen,
            $codigo,$utilizadosUsM
        );

        return $respuesta;
    }


    function  eliminarCotizacion($idCotizacion)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->eliminarCotizacion($idCotizacion);

        return $respuesta;
    }



    function  actualizarCostoEnvio($idCotizacion, $costo)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->actualizarCostoEnvio($idCotizacion, $costo);

        return $respuesta;
    }


    function  actualizaSemaforo($idCotizacion, $color)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->actualizaSemaforo($idCotizacion, $color);

        return $respuesta;
    }

    function  cancelarProduccion($idCotizacion)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->cancelarProduccion($idCotizacion);

        return $respuesta;
    }


    function  validarCodigoBarras($codigo, $idProducto, $quierUsar)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->obtenerDatosCodigoBarras($codigo);

        if ($respuesta->exito) {
            $producciones = $clase->obtenerProduccionesCodigo($codigo)->registros;

            $datosCodigo = $respuesta->registros[0];

            $usada = 0;
            if (count($producciones) > 0) {

                foreach ($producciones as $prod) {
                    $usada = $usada + $prod['kilos'];
                }
            }


            if ($datosCodigo['peso'] >= ($usada + $quierUsar)) {
                $respuesta->exito = true;
                return $respuesta;
            } else {
                $respuesta->exito = false;
                $disponible = $datosCodigo['peso'] - $usada;

                $respuesta->mensaje = "No hay suficiente materia
                 prima, solo hay disponible: " . $disponible . ", se quiere usar " . $quierUsar;
                return $respuesta;
            }
        } else {
            return $respuesta;
        }
    }



    function obtenerExtrasCotizacion($idCotizacion)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->obtenerExtrasCotizacion($idCotizacion);

        return $respuesta;
    }

    function obtenerDatosCartaPorte($idRemision)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->obtenerDatosCartaPorte($idRemision);

        return $respuesta;
    }

    function obtenerAlmacenesDisponibles($idRecepcion)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->obtenerAlmacenesDisponibles($idRecepcion);

        return $respuesta;
    }

    function actualizarDescuentoCotizacion($descuento, $idCotizacion)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->actualizarDescuentoCotizacion($descuento, $idCotizacion);

        return $respuesta;
    }

    function recalcularCotizacion($idCotizacion)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->recalcularCotizacion($idCotizacion);

        return $respuesta;
    }


    function agregarCotizacion(
        $idCliente,
        $usuario,
        $idUsuario

    ) {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->agregarCotizacion(
            $idCliente,
            $usuario,
            $idUsuario
        );

        return $respuesta;
    }


    function agregarProductoCotizacion(
        $idCotizacion,
        $idProducto,
        $cantidad,
        $preciounitario,
        $metros,
        $idUnidad

    ) {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->agregarProductoCotizacion(
            $idCotizacion,
            $idProducto,
            $cantidad,
            $preciounitario,
            $metros,
            $idUnidad

        );

        return $respuesta;
    }





    function actualizarCotizacion(
        $fechaEntrega,
        $lugarEntrega,
        $formaPago,
        $vigencia,
        $condiciones,
        $observaciones,

        $idCotizacion
    ) {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->actualizarCotizacion(
            $fechaEntrega,
            $lugarEntrega,
            $formaPago,
            $vigencia,
            $condiciones,
            $observaciones,

            $idCotizacion
        );

        return $respuesta;
    }


    function actualizarCartaPorte(
        $contenedor,
        $placas,
        $tipoUnidad,
        $operador,
        $idRemision
    ) {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->actualizarCartaPorte(
            $contenedor,
            $placas,
            $tipoUnidad,
            $operador,
            $idRemision
        );

        return $respuesta;
    }


    function eliminarProductoCotizacion(
        $idCotizacionDet

    ) {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new CotizacionesModel($db);
        $respuesta = $clase->eliminarProductoCotizacion(
            $idCotizacionDet

        );

        return $respuesta;
    }
}
