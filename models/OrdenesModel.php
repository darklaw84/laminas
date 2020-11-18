<?php

include_once 'RespuestaBD.php';
include_once 'CatalogosModel.php';




class OrdenesModel
{

    // database connection and table name
    private $conn;


    public function __construct($db)
    {
        $this->conn = $db;
    }


    function obtenerOrdenes()
    {
        $query = "SELECT  co.*,cl.proveedor FROM  ordenescompra co
             inner join proveedores cl on co.idProveedor = cl.idProveedor
               order by fecha desc ";
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        $num = $stmt->rowCount();
        $respuesta = new RespuestaBD();

        if ($num > 0) {

            $arreglo = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['name'] to
                // just $name only
                extract($row);
                $detalle = $this->obtenerDetalleOrden($idOrden);

                $productos = $detalle->registros;

                $total = 0;
                $todasRecibida = true;
                $sinRecibidas = false;
                $tieneRecibidas = false;
                foreach ($productos as $prod) {
                    $total += $prod['precio'];
                    if (!$prod['recibido']) {
                        $sinRecibidas = true;
                        $todasRecibida = false;
                    } else {
                        $tieneRecibidas = true;
                    }
                }

                if ($todasRecibida) {
                    $esta = "Recibida Completa";
                    $icono = "correcto.png";
                } else if ($sinRecibidas) {
                    if ($tieneRecibidas) {
                        $esta = "Parcialmente Recibida";
                        $icono = "warning.png";
                    } else {
                        $esta = "Sin Recepciones";
                        $icono = "waiting.png";
                    }
                }





                $registro_item = array(
                    "idOrden" => $idOrden,
                    "idProveedor" => $idProveedor,
                    "fecha" => $fecha,
                    "fechaRequerida" => $fechaRequerida,
                    "proveedor" => $proveedor,
                    "icono" => $icono,
                    "estatus" => $estatus,
                    "usuario" => $usuario,
                    "esta" => $esta,
                    "comentarios" => $comentarios,
                    "productos" => $detalle->registros,
                    "total" => $total
                );

                array_push($arreglo, $registro_item);
            }
            $respuesta->mensaje = "";
            $respuesta->exito = true;
            $respuesta->registros = $arreglo;
        } else {
            $respuesta->mensaje = "No se encontraron datos ";
            $respuesta->exito = false;
        }


        return $respuesta;
    }



    function obtenerOrden($idOrden)
    {
        $query = "SELECT  co.*,cl.proveedor FROM  ordenescompra co
             inner join proveedores cl on co.idProveedor = cl.idProveedor
               where idOrden=" . $idOrden;
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        $num = $stmt->rowCount();
        $respuesta = new RespuestaBD();

        if ($num > 0) {

            $arreglo = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['name'] to
                // just $name only
                extract($row);
                $detalle = $this->obtenerDetalleOrden($idOrden);

                $productos = $detalle->registros;

                $total = 0;
                $conrecepcion = false;
                foreach ($productos as $prod) {
                    $total += $prod['precio'];

                    if (count($prod['recepciones']) > 0) {
                        $conrecepcion = true;
                    }
                }

                $registro_item = array(
                    "idOrden" => $idOrden,
                    "idProveedor" => $idProveedor,
                    "fecha" => $fecha,
                    "fechaRequerida" => $fechaRequerida,
                    "proveedor" => $proveedor,
                    "estatus" => $estatus,
                    "comentarios" => $comentarios,
                    "usuario" => $usuario,
                    "productos" => $detalle->registros,
                    "conrecepcion" => $conrecepcion,
                    "total" => $total
                );

                array_push($arreglo, $registro_item);
            }
            $respuesta->mensaje = "";
            $respuesta->exito = true;
            $respuesta->registros = $arreglo;
        } else {
            $respuesta->mensaje = "No se encontraron datos ";
            $respuesta->exito = false;
        }


        return $respuesta;
    }



    function actualizarOrdenDetCantidad($idOrdenCompraDet, $cantidad)
    {

        $query = "update ordencompradetalle set cantidad=" . $cantidad . " where idOrdenCompraDet=" . $idOrdenCompraDet;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }


    function actualizarOrdenDetPrecio($idOrdenCompraDet, $precio)
    {

        $query = "update ordencompradetalle set precioUnidadPeso=" . $precio . " where idOrdenCompraDet=" . $idOrdenCompraDet;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }


    function obtenerDetalleOrden($idOrden)
    {

        $porcentaje = $this->obtenerValorParametro("porcentajeRecepcion");

        $query = "SELECT  od.*,u.unidad,p.producto,c.calibre,t.tipo,
        p.pesoTeorico prodPesoTeorico,p.largo,p.sku,a.ancho FROM  ordencompradetalle od
             
             inner join productos p on p.idProducto=od.idProducto
             inner join unidades u on od.idUnidad = u.idUnidad
             inner join anchos a on a.idAncho = p.idAncho
             inner join calibres c on p.idCalibre = c.idCalibre
             inner join tipos t on p.idTipo = t.idTipo
             where idOrden=" . $idOrden;
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        $num = $stmt->rowCount();
        $respuesta = new RespuestaBD();

        if ($num > 0) {

            $arreglo = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                extract($row);

                $cantidadSinPorcentaje = $cantidad - ($cantidad * $porcentaje / 100.00);

                $pesoTeoricoSinPorcentaje = $pesoTeorico - ($pesoTeorico * $porcentaje / 100.00);
                $resRec = $this->obtenerRecepcionesDetalle($idOrdenCompraDet);
                $recepciones = $resRec->registros;

                $recibido = false;
                $totalCantidadRecibida = 0;
                $pesoRecibido = 0;
                $pesoOrdenado = 0;
                if ($idUnidad == 1) {
                    //pieza
                    //quiere decir que el precio es sin el peso
                    $precio = $cantidad * $precioUnidadPeso;


                    //para verificar si ya fue recibido buscamos en las recepciones
                    $totalCantidadRecibida = 0;
                    foreach ($recepciones as $rec) {
                        $totalCantidadRecibida += $rec['cantidad'];
                    }
                    if ($totalCantidadRecibida >= $cantidadSinPorcentaje) {
                        $recibido = true;
                    }
                } else if ($idUnidad == 2) {
                    //metro
                    //quiere decir que el precio es el peso por el precio
                    $precio = $cantidad * $precioUnidadPeso;
                    $pesoOrdenado = $pesoTeorico;
                    //para verificar si ya fue recibido buscamos en las recepciones
                    $pesoRecibido = 0;
                    foreach ($recepciones as $rec) {
                        $pesoRecibido += $rec['peso'];
                        $totalCantidadRecibida += $rec['cantidad'];
                    }
                    if ($pesoRecibido >= $pesoTeoricoSinPorcentaje) {
                        $recibido = true;
                    }
                } else if ($idUnidad == 3) {
                    //kilo
                    //quiere decir que el precio es la cantidad por el precio
                    $precio = $cantidad * $precioUnidadPeso;
                    $pesoOrdenado = $cantidad;
                    $pesoRecibido = 0;
                    foreach ($recepciones as $rec) {
                        $pesoRecibido += $rec['peso'];
                    }
                    if ($pesoRecibido >= $cantidadSinPorcentaje) {
                        $recibido = true;
                    }
                }


                $registro_item = array(
                    "idOrdenCompraDet" => $idOrdenCompraDet,
                    "producto" => $producto,
                    "unidad" => $unidad,
                    "idUnidad" => $idUnidad,
                    "calibre" => $calibre,
                    "tipo" => $tipo,
                    "pesoTeorico" => $pesoOrdenado,
                    "sku" => $sku,
                    "ancho" => $ancho,
                    "precioUnidadPeso" => $precioUnidadPeso,
                    "recepciones" => $recepciones,
                    "cantidad" => $cantidad,
                    "total" => $pesoRecibido,
                    "largo" => $largo,
                    "recibido" => $recibido,
                    "prodPesoTeorico" => $prodPesoTeorico,
                    "totalCantidad" => $totalCantidadRecibida,
                    "idProducto" => $idProducto,
                    "precio" => $precio
                );

                array_push($arreglo, $registro_item);
            }
            $respuesta->mensaje = "";
            $respuesta->exito = true;
            $respuesta->registros = $arreglo;
        } else {
            $respuesta->mensaje = "No se encontraron datos ";
            $respuesta->exito = false;
        }


        return $respuesta;
    }


    function eliminarOrden($idOrden)
    {
        $respuesta = new RespuestaBD();
        $cotizacion = $this->obtenerOrden($idOrden);

        if (!$cotizacion->registros[0]['conrecepcion']) {

            $query = "delete from ordencompradetalle where idOrden = " . $idOrden;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $query = "delete from ordenescompra where idOrden = " . $idOrden;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $respuesta->mensaje = "";
            $respuesta->exito = true;
        } else {
            $respuesta->mensaje = "La orden ya tiene recepciones, no se puede eliminar ";
            $respuesta->exito = false;
        }


        return $respuesta;
    }



    function finalizarOrden($idOrden)
    {
        $respuesta = new RespuestaBD();
       

            $query = "update ordenescompra set estatus = 'F' where idOrden = " . $idOrden;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            
            $respuesta->mensaje = "";
            $respuesta->exito = true;
        


        return $respuesta;
    }



    function obtenerRecepcionesDetalle($idOrdenCompraDet)
    {
        $query = "SELECT  r.*,u.unidad,p.producto,c.calibre,t.tipo, alm.almacen FROM  recepciones r
        inner join unidades u on  u.idUnidad=r.idUnidad
        inner join productos p on p.idProducto = r.idProducto
        inner join calibres c on c.idCalibre=p.idCalibre
        inner join tipos t on t.idTipo=p.idTipo
        inner join almacenes alm on alm.idAlmacen = r.idAlmacen 
             where idOrdenCompraDet=" . $idOrdenCompraDet;
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        $num = $stmt->rowCount();
        $respuesta = new RespuestaBD();

        if ($num > 0) {

            $arreglo = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                extract($row);

                $registro_item = array(
                    "idRecepcion" => $idRecepcion,
                    "idOrdenCompraDet" => $idOrdenCompraDet,
                    "usuarioRecibe" => $usuarioRecibe,
                    "cantidad" => $cantidad,
                    "almacen" => $almacen,
                    "unidad" => $unidad,
                    "idUnidad" => $idUnidad,
                    "peso" => $peso,
                    "idProducto" => $idProducto,
                    "idOrdenCompra" => $idOrdenCompra,
                    "calibre" => $calibre,
                    "tipo" => $tipo,
                    "producto" => $producto,
                    "fechaRecibe" => $fechaRecibe

                );

                array_push($arreglo, $registro_item);
            }
            $respuesta->mensaje = "";
            $respuesta->exito = true;
            $respuesta->registros = $arreglo;
        } else {
            $respuesta->mensaje = "No se encontraron datos ";
            $respuesta->exito = false;
        }


        return $respuesta;
    }



    function obtenerValorParametro($parametro)
    {
        $query = "SELECT  valorParametro FROM  parametros
             where nombreParametro='" . $parametro . "'";
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        $valor = "";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $valor = $valorParametro;
        }
        return $valor;
    }


    function obtenerMateriales()
    {
        $query = "select idOrden from ordenescompra
             ";
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        $num = $stmt->rowCount();
        $respuesta = new RespuestaBD();

        if ($num > 0) {

            $arreglo = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                extract($row);

                $orden = $this->obtenerOrden($idOrden)->registros;

                $registro_item = array(
                    "orden" => $orden
                );

                array_push($arreglo, $registro_item);
            }
            $respuesta->mensaje = "";
            $respuesta->exito = true;
            $respuesta->registros = $arreglo;
        } else {
            $respuesta->mensaje = "No se encontraron datos ";
            $respuesta->exito = false;
        }


        return $respuesta;
    }






    function agregarOrden(
        $idProveedor,
        $usuario

    ) {
        $respuesta = new RespuestaBD();
        //primero insertamos 
        $query = "INSERT INTO
        ordenescompra
    SET
    idProveedor=:idProveedor,usuario=:usuario, fecha=now(),
    fechaRequerida=now(), comentarios=''";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":idProveedor", $idProveedor);
        $stmt->bindParam(":usuario", $usuario);


        if ($stmt->execute()) {
            $idInsertado = $this->conn->lastInsertId();
            $respuesta->exito = true;
            $respuesta->mensaje = "";
            $respuesta->valor = $idInsertado;
            return $respuesta;
        } else {
            $respuesta->exito = false;
            $mensaje = $stmt->errorInfo();
            $respuesta->mensaje = "Ocurrió un problema actualizando";
            return $respuesta;
        }
    }


    function agregarProductoOrden(
        $idOrden,
        $idProducto,
        $cantidad,
        $pesoTeorico,
        $precioUnidadPeso,
        $idUnidad

    ) {

        //primero insertamos 
        $query = "INSERT INTO
        ordencompradetalle
    SET
    idOrden=:idOrden,idProducto=:idProducto,cantidad=:cantidad,pesoTeorico=:pesoTeorico
    ,precioUnidadPeso=:precioUnidadPeso,idUnidad=:idUnidad";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":idOrden", $idOrden);
        $stmt->bindParam(":idProducto", $idProducto);
        $stmt->bindParam(":cantidad", $cantidad);
        $stmt->bindParam(":pesoTeorico", $pesoTeorico);
        $stmt->bindParam(":precioUnidadPeso", $precioUnidadPeso);
        $stmt->bindParam(":idUnidad", $idUnidad);


        if (!$stmt->execute()) {
            $mensaje = $stmt->errorInfo();
        }
    }



    function actualizarOrden(

        $fechaRequerida,
        $comentarios,
        $idOrden
    ) {

        //primero insertamos 
        $query = "update
        ordenescompra
    SET
    
    fechaRequerida=:fechaRequerida, comentarios=:comentarios where idOrden=" . $idOrden;

        $stmt = $this->conn->prepare($query);


        $stmt->bindParam(":fechaRequerida", $fechaRequerida);
        $stmt->bindParam(":comentarios", $comentarios);

        if (!$stmt->execute()) {
            $mensaje = $stmt->errorInfo();
        }
    }



    function reimprimeRecepcion(
        $idRecepcion
    ) {

        //primero insertamos 
        $query = "update
        recepciones
    SET
    
    imprimir = 1 where idRecepcion=" . $idRecepcion;

        $stmt = $this->conn->prepare($query);


        if (!$stmt->execute()) {
            $mensaje = $stmt->errorInfo();
        }
    }


    function reimprimeProduccion(
        $idProduccion
    ) {

        //primero insertamos 
        $query = "update
        producciones
    SET
    
    imprimir = 1 where idProduccion=" . $idProduccion;

        $stmt = $this->conn->prepare($query);


        if (!$stmt->execute()) {
            $mensaje = $stmt->errorInfo();
        }
    }


    function recibirMateria(

        $idOrdenCompraDet,
        $usuario,
        $idAlmacenF,
        $idUnidadF,
        $cantidadF,
        $idProductoF,
        $pesoTeoricoF,
        $idOrden
    ) {

        //primero insertamos 
        $query = "INSERT INTO recepciones
         SET
          idOrdenCompraDet=:idOrdenCompraDet,usuarioRecibe=:usuario,cantidad=:cantidad,imprimir=1,
        idUnidad=:idUnidad,peso=:peso,fechaRecibe=now(),idProducto=:idProducto,idOrdenCompra=:idOrdenCompra,idAlmacen=:idAlmacen";


        $stmt = $this->conn->prepare($query);
        if ($idUnidadF == 1) {
            //piezas
            $peso = null;
            $cantidad = $cantidadF;
        } else if ($idUnidadF == 3) {
            //kilos
            $peso = $cantidadF;
            if ($pesoTeoricoF > 0) {
                $cantidad = $cantidadF / $pesoTeoricoF;
            } else {
                $cantidad = 0;
            }
        } else if ($idUnidadF == 2) {
            //metros
            $cantidad = $cantidadF;
            $peso = $cantidadF * $pesoTeoricoF;
        }


        $stmt->bindParam(":idOrdenCompraDet", $idOrdenCompraDet);
        $stmt->bindParam(":usuario", $usuario);
        $stmt->bindParam(":idUnidad", $idUnidadF);
        $stmt->bindParam(":idProducto", $idProductoF);
        $stmt->bindParam(":idOrdenCompra", $idOrden);
        $stmt->bindParam(":idAlmacen", $idAlmacenF);
        $stmt->bindParam(":cantidad", $cantidad);
        $stmt->bindParam(":peso", $peso);



        if (!$stmt->execute()) {
            $mensaje = $stmt->errorInfo();
        }
    }



    function eliminarProductoOrden(
        $idOrdenCompraDet

    ) {

        //primero insertamos 
        $query = "delete from 
        ordencompradetalle
     where idOrdenCompraDet=" . $idOrdenCompraDet;

        $stmt = $this->conn->prepare($query);


        if (!$stmt->execute()) {
            $mensaje = $stmt->errorInfo();
        }
    }
}
