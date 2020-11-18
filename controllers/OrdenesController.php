<?php

include_once './config/database.php';
include_once './models/OrdenesModel.php';


class OrdenesController
{


    public function __construct()
    {
    }




    function obtenerOrdenes()
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new OrdenesModel($db);
        $respuesta = $clase->obtenerOrdenes();

        return $respuesta;
    }


    function obtenerOrden($idOrden)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new OrdenesModel($db);
        $respuesta = $clase->obtenerOrden($idOrden);

        return $respuesta;
    }


    function eliminarOrden($idOrden)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new OrdenesModel($db);
        $respuesta = $clase->eliminarOrden($idOrden);

        return $respuesta;
    }

    function finalizarOrden($idOrden)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new OrdenesModel($db);
        $respuesta = $clase->finalizarOrden($idOrden);

        return $respuesta;
    }

    function obtenerMateriales()
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new OrdenesModel($db);
        $respuesta = $clase->obtenerMateriales();

        return $respuesta;
    }

    function recibirMateria($idMateria,$idUsuario,$idAlmacenF,$idUnidadF,
    $cantidadF,$idProductoF,$pesoTeoricoF,$idOrden)
    {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new OrdenesModel($db);
        $respuesta = $clase->recibirMateria($idMateria,$idUsuario,$idAlmacenF,$idUnidadF,
        $cantidadF,$idProductoF,$pesoTeoricoF,$idOrden);

        return $respuesta;
    }


    function agregarOrden(
        $idProveedor,
        $usuario

    ) {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new OrdenesModel($db);
        $respuesta = $clase->agregarOrden(
            $idProveedor,
            $usuario
        );

        return $respuesta;
    }


    function agregarProductoOrden(
        $idOrden,
        $idProducto,
        $cantidad,
        $pesoTeorico,
        $precioUnidadPeso,
        $idUnidad

    ) {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new OrdenesModel($db);
        $respuesta = $clase->agregarProductoOrden(
            $idOrden,
            $idProducto,
            $cantidad,
            $pesoTeorico,
            $precioUnidadPeso,
            $idUnidad

        );

        return $respuesta;
    }





    function actualizarOrden(
       
        $fechaRequerida,
        $comentarios,
        $idOrden
    ) {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new OrdenesModel($db);
        $respuesta = $clase->actualizarOrden(
           
            $fechaRequerida,
            $comentarios,
            $idOrden
        );

        return $respuesta;
    }

    function reimprimeRecepcion(
        $idRecepcion
    ) {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new OrdenesModel($db);
        $respuesta = $clase->reimprimeRecepcion($idRecepcion );

        return $respuesta;
    }

    function reimprimeProduccion(
        $idProduccion
    ) {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new OrdenesModel($db);
        $respuesta = $clase->reimprimeProduccion($idProduccion );

        return $respuesta;
    }


    function eliminarProductoOrden(
        $idOrdenCompraDet
        
    )  {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new OrdenesModel($db);
        $respuesta = $clase->eliminarProductoOrden(
            $idOrdenCompraDet
            
        ) ;

        return $respuesta;
    }



    function actualizarOrdenDetCantidad($idOrdenCompraDet, $cantidad) {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new OrdenesModel($db);
        $respuesta = $clase->actualizarOrdenDetCantidad($idOrdenCompraDet, $cantidad);

        return $respuesta;
    }


    function actualizarOrdenDetPrecio($idOrdenCompraDet, $precio) {
        $database = new Database();
        $db = $database->getConnection();
        $clase = new OrdenesModel($db);
        $respuesta = $clase-> actualizarOrdenDetPrecio($idOrdenCompraDet, $precio);

        return $respuesta;
    }


    
}
