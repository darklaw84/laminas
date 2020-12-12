<?php

include_once 'RespuestaBD.php';
include_once 'CatalogosModel.php';
include_once 'DevolucionesModel.php';




class CotizacionesModel
{

    // database connection and table name
    private $conn;


    public function __construct($db)
    {
        $this->conn = $db;
    }


    function obtenerCotizaciones($tipo, $idUsuario,$numAlmacen)
    {
        if ($tipo == "C") {
            $query = "SELECT  co.*,cl.cliente,fp.formapago FROM  cotizaciones co
             inner join clientes cl on co.idCliente = cl.idCliente
             left join formaspago fp on fp.idFormaPago = co.idFormaPago
               order by fecha desc ";
        } else if ($tipo == "P") {
            $query = "SELECT  co.*,cl.cliente,fp.formapago FROM  cotizaciones co
            inner join clientes cl on co.idCliente = cl.idCliente
            left join formaspago fp on fp.idFormaPago = co.idFormaPago
            where pedido=1
              order by fecha desc ";
        } else if ($tipo == "PR") {
            $query = "SELECT  co.*,cl.cliente,fp.formapago FROM  cotizaciones co
            inner join clientes cl on co.idCliente = cl.idCliente
            left join formaspago fp on fp.idFormaPago = co.idFormaPago
            where pedido=1 and produccion = 1 and cancelado is null  and numAlmacen = ".$numAlmacen."
              order by fecha desc ";
        }else if ( $tipo == "PS") {
            $query = "SELECT  co.*,cl.cliente,fp.formapago FROM  cotizaciones co
            inner join clientes cl on co.idCliente = cl.idCliente
            left join formaspago fp on fp.idFormaPago = co.idFormaPago
            where pedido=1 and produccion = 1  
              order by fecha desc ";
        } else if ($tipo == "PE") {
            $query = "SELECT  co.*,cl.cliente,fp.formapago FROM  cotizaciones co
            inner join clientes cl on co.idCliente = cl.idCliente
            left join formaspago fp on fp.idFormaPago = co.idFormaPago
            where idUsuario = " . $idUsuario . "
              order by fecha desc ";
        }

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
                $detalle = $this->obtenerDetalleCotizacion($idCotizacion);

                $abonos = $this->obtenerAbonosCotizacion($idCotizacion)->registros;

                $ultimaFechaEntrega= $this->obtenerUltimaFechaDeEntrega($idCotizacion)->valor;

                $totalAbonos = 0;
                $pedidoPagado = false;
                foreach ($abonos as $abo) {
                    if ($abo['usuarioCancela'] == "") {
                        $totalAbonos = $totalAbonos +  $abo['monto'];
                    }
                }

                if ($totalAbonos >= $grantotal) {
                    $pedidoPagado = true;
                }

                $productos = $detalle->registros;
                $terminada = true;
                $todasConRemisiones = true;
                $tieneRemision = false;
                foreach ($productos as $prod) {
                    if (!$prod['partidaTerminada']) {
                        $terminada = false;
                    }

                    if (!$prod['todasConRemisiones']) {
                        $todasConRemisiones = false;
                    }

                    if ($prod['tieneRemision']) {
                        $tieneRemision = true;
                    }
                }




                $registro_item = array(
                    "idCotizacion" => $idCotizacion,
                    "idCliente" => $idCliente,
                    "fecha" => $fecha,
                    "montototal" => $montototal,
                    "descuento" => $descuento,
                    "observaciones" => $observaciones,
                    "tieneRemision" => $tieneRemision,
                    "idUsuario" => $idUsuario,
                    "costoEnvio" => $costoEnvio,
                    "cliente" => $cliente,
                    "cancelado" => $cancelado,
                    "condiciones" => $condiciones,
                    "ultimaFechaEntrega" => $ultimaFechaEntrega,
                    "vigencia" => $vigencia,
                    "formapago" => $formapago,
                    "pedidoPagado" => $pedidoPagado,
                    "abonos" => $abonos,
                    "totalAbonos" => $totalAbonos,
                    "semaforo" => $semaforo,
                    "lugarentrega" => $lugarentrega,
                    "grantotal" => $grantotal,
                    "usuario" => $usuario,
                    "fechaEntrega" => $fechaEntrega,
                    "pedido" => $pedido,
                    "produccion" => $produccion,
                    "terminada" => $terminada,
                    "productos" => $productos

                );

                if ($tipo == "PS") {
                    if (!$todasConRemisiones) {
                        array_push($arreglo, $registro_item);
                    }
                } else {

                    array_push($arreglo, $registro_item);
                }
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




    function obtenerRemisiones()
    {

        $query = "SELECT  r.*,cl.cliente,cl.direccion,
        cl.direccionentrega,cl.comentarios,cl.representante
         FROM  remisiones r
        inner join cotizaciones c on c.idCotizacion=r.idPedido
        inner join clientes cl on cl.idCliente = c.idCliente
              order by idRemision desc ";


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

                $detalle =  $this->obtenerDetalleRemision($idRemision)->registros;
                $devoluciones =  $this->obtenerDetalleRemisionDevolucion($idRemision)->registros;
                $detalle = array_merge($detalle, $devoluciones);
                $registro_item = array(
                    "idRemision" => $idRemision,
                    "idUsuario" => $idUsuario,
                    "fecha" => $fecha,
                    "detalle" => $detalle,
                    "cliente" => $cliente,
                    "idCotizacion" => $idPedido,
                    "direccion" => $direccion,
                    "direccionentrega" => $direccionentrega,
                    "comentarios" => $comentarios,
                    "representante" => $representante,
                    "usuario" => $usuario

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


    function obtenerRemision($idRemision)
    {

        $query = "SELECT  r.idRemision,r.idUsuario,r.fecha,r.tipoUnidad,r.contenedor,coti.usuario vendedor
        ,ch.chofer,ca.camion,ca.placas,ca.camion,r.idPedido,r.usuario FROM  remisiones r
            left join choferes ch on ch.idChofer=r.operador
            left join camiones ca on ca.idCamion = r.placas
            inner join cotizaciones coti on coti.idCotizacion = r.idPedido
            where idRemision = " . $idRemision . "
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
                // extract row
                // this will make $row['name'] to
                // just $name only
                extract($row);

                $detalle =  $this->obtenerDetalleRemision($idRemision)->registros;
                $devoluciones =  $this->obtenerDetalleRemisionDevolucion($idRemision)->registros;
                $detalle = array_merge($detalle, $devoluciones);
                $registro_item = array(
                    "idRemision" => $idRemision,
                    "idUsuario" => $idUsuario,
                    "fecha" => $fecha,
                    "operador" => $chofer,
                    "camion" => $camion,
                    "tipoUnidad" => $tipoUnidad,
                    "placas" => $placas,
                    "contenedor" => $contenedor,
                    "detalle" => $detalle,
                    "idPedido" => $idPedido,
                    "vendedor" => $vendedor,
                    "usuario" => $usuario

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





    function obtenerDetalleRemision($idRemision)
    {

        $query = "SELECT alm.almacen,rd.idRemisionDet,rd.idRemision,pr.kilos,pr.cantidad,pr.usuario,pr.fecha,
        u.unidad unidadFactura,a.ancho,c.calibre,t.tipo,p.sku,p.producto ,cd.metros,p.largo,cd.preciounitario,pr.idProduccion
         FROM  remisiondetalle rd
                inner join producciones pr on pr.idProduccion = rd.idProduccion
                inner join productos p on p.idProducto = pr.idProductoProduccion
                inner join cotizaciondetalle cd on cd.idCotizacionDet = pr.idCotizacionDetalle
                inner join unidades u on u.idUnidad=p.idUnidadFactura
                inner join calibres c on c.idCalibre = p.idCalibre
                inner join tipos t on t.idTipo = p.idTipo
                inner join anchos a on a.idAncho= p.idAncho
                inner join almacenes alm on alm.idAlmacen = pr.idAlmacen
                where rd.idRemision = " . $idRemision . "

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
                // extract row
                // this will make $row['name'] to
                // just $name only
                extract($row);



                $registro_item = array(
                    "idRemision" => $idRemision,
                    "idRemisionDet" => $idRemisionDet,
                    "kilos" => $kilos,
                    "cantidad" => $cantidad,
                    "unidadFactura" => $unidadFactura,
                    "preciounitario" => $preciounitario,
                    "ancho" => $ancho,
                    "largo" => $largo,
                    "calibre" => $calibre,
                    "usuario" => $usuario,
                    "fecha" => $fecha,
                    "idProduccion" => $idProduccion,

                    "tipo" => $tipo,
                    "sku" => $sku,
                    "producto" => $producto,
                    "almacen" => $almacen,
                    "metros" => $metros

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



    function obtenerDetalleRemisionDevolucion($idRemision)
    {

        $query = "SELECT alm.almacen,rd.idRemisionDet,us.nombre usuario,rd.idRemision,pr.kilos,pr.cantidad,pr.fecha,
        u.unidad unidadFactura,a.ancho,c.calibre,t.tipo,p.sku,p.producto ,cd.metros,p.largo,cd.preciounitario,pr.idDevolucionProduccion
         FROM  remisiondetalle rd
                inner join devolucionesproducciones pr on pr.idDevolucionProduccion = rd.idDevolucionProduccion
                inner join productos p on p.idProducto = pr.idProducto
                inner join cotizaciondetalle cd on cd.idCotizacionDet = pr.idCotizacionDetalle
                inner join unidades u on u.idUnidad=p.idUnidadFactura
                inner join usuarios us on us.idUsuario=pr.idUsuario
                inner join calibres c on c.idCalibre = p.idCalibre
                inner join tipos t on t.idTipo = p.idTipo
                inner join anchos a on a.idAncho= p.idAncho
                inner join almacenes alm on alm.idAlmacen = pr.idAlmacen
                where rd.idRemision = " . $idRemision . "

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
                // extract row
                // this will make $row['name'] to
                // just $name only
                extract($row);



                $registro_item = array(
                    "idRemision" => $idRemision,
                    "idRemisionDet" => $idRemisionDet,
                    "kilos" => $kilos,
                    "cantidad" => $cantidad,
                    "idDevolucionProduccion" => $idDevolucionProduccion,
                    "unidadFactura" => $unidadFactura,
                    "preciounitario" => $preciounitario,
                    "ancho" => $ancho,
                    "largo" => $largo,
                    "calibre" => $calibre,
                    "usuario" => $usuario,
                    "fecha" => $fecha,

                    "tipo" => $tipo,
                    "sku" => $sku,
                    "producto" => $producto,
                    "almacen" => $almacen,
                    "metros" => $metros

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



    function consultaPartidaCotizacion($idCotizacionDet)
    {

        $query = "SELECT idProducto,idCotizacion from cotizaciondetalle 
                where idCotizacionDet = " . $idCotizacionDet . "

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
                // extract row
                // this will make $row['name'] to
                // just $name only
                extract($row);



                $registro_item = array(
                    "idProducto" => $idProducto,
                    "idCotizacion" => $idCotizacion
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





    function validaRecepcion($idRecepcion)
    {

        $query = "select * from recepciones where idRecepcion = " . $idRecepcion;


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



                $registro_item = array(
                    "idRecepcion" => $idRecepcion,
                    "idProducto" => $idProducto

                );


                array_push($arreglo, $registro_item);
            }
            $respuesta->mensaje = "";
            $respuesta->exito = true;
            $respuesta->registros = $arreglo;
        } else {
            $respuesta->mensaje = "No existe la recepcion ";
            $respuesta->exito = false;
        }


        return $respuesta;
    }


    function togglePedido($idPedido, $activo, $numAlmacen)
    {

        $respuesta = new RespuestaBD();

        // check if more than 0 record found

        // query to insert record
        $query = "UPDATE
                   cotizaciones
                SET
                    produccion=" . $activo . ",numAlmacen=" . $numAlmacen . " where idCotizacion=" . $idPedido;

        // prepare query
        $stmt = $this->conn->prepare($query);

        if ($stmt->execute()) {
            $respuesta->exito = true;
            $respuesta->mensaje = "";
            return $respuesta;
        } else {
            $respuesta->exito = false;
            $respuesta->mensaje = "Ocurrió un problema togglenizando";
            return $respuesta;
        }
    }

    function generarPedido($idCotizacion)
    {


        $query = "update  cotizaciones set pedido=1  WHERE idCotizacion=" . $idCotizacion;
        $stmt = $this->conn->prepare($query);

        $stmt->execute();
    }


    function cancelarPedido($idCotizacion)
    {


        $query = "update  cotizaciones set cancelado=1  WHERE idCotizacion=" . $idCotizacion;
        $stmt = $this->conn->prepare($query);

        $stmt->execute();
    }


    function cancelarRemision($idRemision)
    {


        $remision = $this->obtenerRemision($idRemision)->registros[0];


        $query = "delete from remisiondetalle where idRemision = " . $idRemision;
        $stmt = $this->conn->prepare($query);

        $stmt->execute();


        $query = "delete from remisiones where idRemision = " . $idRemision;
        $stmt = $this->conn->prepare($query);

        $stmt->execute();


        foreach ($remision['detalle'] as $det) {
            if ($det['idProduccion'] != "") {
                $query = "delete from salidas where idProduccion = " . $det['idProduccion'];
                $stmt = $this->conn->prepare($query);

                $stmt->execute();
            } else {
                $query = "delete from salidas where idDevolucionProduccion = " . $det['idDevolucionProduccion'];
                $stmt = $this->conn->prepare($query);

                $stmt->execute();
            }
        }
    }


    function generarMateriaProduccion(
        $idCotizacionDetM,
        $kilos,
        $cantidad,
        $usuario,
        $idUsuario,
        $idProducto,
        $idAlmacen,
        $codigoBarras,
        $utilizadosUsM
    ) {

        if ($utilizadosUsM == "") {
            $utilizadosUsM = 0;
        }

        $respuesta = new RespuestaBD();

        $respuesta = $this->validaRecepcion($codigoBarras);

        if ($respuesta->exito) {
            //primero insertamos 
            $query = "INSERT INTO
        producciones
    SET
    idCotizacionDetalle=:idCotizacionDetalle,kilos=:kilos,cantidad=:cantidad,
    usuario=:usuario,idUsuario=:idUsuario,idRecepcion=:idRecepcion,imprimir=1,
    idProductoRecepcion=:idProductoRecepcion,idProductoProduccion=:idProductoProduccion
    ,idAlmacen=:idAlmacen,kilosUsuario=:utilizadosUsM, fecha=now()";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":idCotizacionDetalle", $idCotizacionDetM);
            $stmt->bindParam(":usuario", $usuario);
            $stmt->bindParam(":kilos", $kilos);
            $stmt->bindParam(":cantidad", $cantidad);
            $stmt->bindParam(":idUsuario", $idUsuario);
            $stmt->bindParam(":idProductoRecepcion", $respuesta->registros[0]['idProducto']);
            $stmt->bindParam(":idAlmacen", $idAlmacen);
            $stmt->bindParam(":idProductoProduccion", $idProducto);
            $stmt->bindParam(":idRecepcion", $codigoBarras);
            $stmt->bindParam(":utilizadosUsM", $utilizadosUsM);





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
        } else {
            return $respuesta;
        }
    }




    function generarMateriaDevolucion(
        $idCotizacionDetM,
        $kilos,
        $cantidad,
        $usuario,
        $idUsuario,
        $idProducto,
        $idAlmacen,
        $codigoBarras,
        $utilizadosUsM,
        $db
    ) {

        $devModel = new DevolucionesModel($db);

        $respuesta = new RespuestaBD();

        //primero hay que validar que la devolucion tenga cantidad y sea el mismo producto




        $res = $devModel->obtenerDevolucion($codigoBarras);

        if (count($res->registros) > 0) {

            $devolucion = $res->registros[0];
            $idProductoDevolucion = $devolucion['idProducto'];

            if ($idProductoDevolucion == $idProducto) {

                if ($cantidad <= $devolucion['restante']) {
                    //si ya sabemos que se tiene la cantidad y el tipo de producto, insertamos en devolucion producciones y en producciones
                    $query = "INSERT INTO
                    devolucionesproducciones
                SET
                idCotizacionDetalle=:idCotizacionDetalle,idDevolucion=:idDevolucion,
                kilos=:kilos,cantidad=:cantidad,
                idUsuario=:idUsuario,imprimir=1,
                idProducto=:idProducto
                ,idAlmacen=:idAlmacen,kilosUsuario=:utilizadosUsM, fecha=now()";

                    if ($utilizadosUsM == "") {
                        $utilizadosUsM = 0;
                    }

                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(":idCotizacionDetalle", $idCotizacionDetM);
                    $stmt->bindParam(":idDevolucion", $codigoBarras);
                    $stmt->bindParam(":kilos", $kilos);
                    $stmt->bindParam(":cantidad", $cantidad);
                    $stmt->bindParam(":idUsuario", $idUsuario);
                    $stmt->bindParam(":idProducto", $idProducto);
                    $stmt->bindParam(":idAlmacen", $idAlmacen);
                    $stmt->bindParam(":utilizadosUsM", $utilizadosUsM);





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
                } else {
                    $respuesta->exito = false;
                    $respuesta->mensaje = "La devolución no tiene suficiente cantidad, se requieren " . $cantidad .
                        " y solo se tienen " . $devolucion['restante'];
                    return $respuesta;
                }
            } else {
                $respuesta->exito = false;
                $respuesta->mensaje = "El producto que se esta produciendo no es el mismo de la devolución";
                return $respuesta;
            }
        } else {
            $respuesta->exito = false;
            $respuesta->mensaje = "La devolución no existe";
            return $respuesta;
        }
    }


    function darSalidaProduccion($idProduccion, $idUsuario, $usuario)
    {

        $respuesta = new RespuestaBD();


        if (strpos($idProduccion, 'D') !== false) {

            $idProduccion = str_replace("D", "", $idProduccion);

            $respuesta = $this->validaSalidasDevolucionProduccion($idProduccion);

            if (!$respuesta->exito) {
                return $respuesta;
            } else {

                //primero insertamos 
                $query = "INSERT INTO
            salidas
        SET
        idDevolucionProduccion=:idProduccion,usuario=:usuario,idUsuario=:idUsuario, fecha=now()";

                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":idProduccion", $idProduccion);
                $stmt->bindParam(":usuario", $usuario);
                $stmt->bindParam(":idUsuario", $idUsuario);


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
        } else {



            $respuesta = $this->obtenerSalidasProduccion($idProduccion);

            if (!$respuesta->exito) {
                return $respuesta;
            } else {

                //primero insertamos 
                $query = "INSERT INTO
            salidas
        SET
        idProduccion=:idProduccion,usuario=:usuario,idUsuario=:idUsuario, fecha=now()";

                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":idProduccion", $idProduccion);
                $stmt->bindParam(":usuario", $usuario);
                $stmt->bindParam(":idUsuario", $idUsuario);


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
        }


        return $respuesta;
    }



    function generarRemision($ids, $idUsuario, $usuario, $idPedido)
    {

        $respuesta = new RespuestaBD();




        //primero insertamos la remision

        $respuesta = $this->InsertaRemision($idUsuario, $usuario, $idPedido);

        if ($respuesta->exito) {

            //luego insertamos el detalle
            foreach ($ids as $id) {
                $this->InsertaDetalleRemision($respuesta->valor, $id);
                $this->darSalidaProduccion($id, $idUsuario, $usuario);
            }
        }





        return $respuesta;
    }



    function InsertaRemision($idUsuario, $usuario, $idPedido)
    {

        $respuesta = new RespuestaBD();




        //primero insertamos la remision
        $query = "INSERT INTO
            remisiones 
        SET
        fecha=now(),usuario=:usuario,idUsuario=:idUsuario,idPedido=:idPedido";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":usuario", $usuario);
        $stmt->bindParam(":idUsuario", $idUsuario);
        $stmt->bindParam(":idPedido", $idPedido);




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



        return $respuesta;
    }



    function InsertaDetalleRemision($idRemision, $idProduccion)
    {

        $respuesta = new RespuestaBD();


        if (strpos($idProduccion, 'D') !== false) {
            //primero insertamos la remision
            $query = "INSERT INTO
        remisiondetalle 
    SET
    idRemision=:idRemision,idDevolucionProduccion=:idDevolucionProduccion";

            $idProduccion = str_replace("D", "", $idProduccion);

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":idRemision", $idRemision);
            $stmt->bindParam(":idDevolucionProduccion", $idProduccion);


            if ($stmt->execute()) {
                $respuesta->exito = true;
                $respuesta->mensaje = "";
                return $respuesta;
            } else {
                $respuesta->exito = false;
                $mensaje = $stmt->errorInfo();
                $respuesta->mensaje = "Ocurrió un problema actualizando";
                return $respuesta;
            }
        } else {

            //primero insertamos la remision
            $query = "INSERT INTO
            remisiondetalle 
        SET
        idRemision=:idRemision,idProduccion=:idProduccion";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":idRemision", $idRemision);
            $stmt->bindParam(":idProduccion", $idProduccion);


            if ($stmt->execute()) {
                $respuesta->exito = true;
                $respuesta->mensaje = "";
                return $respuesta;
            } else {
                $respuesta->exito = false;
                $mensaje = $stmt->errorInfo();
                $respuesta->mensaje = "Ocurrió un problema actualizando";
                return $respuesta;
            }
        }



        return $respuesta;
    }



    function obtenerSalidasProduccion($idProduccion)
    {



        $query = "SELECT *  
         from salidas where idProduccion =" . $idProduccion;



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



                $registro_item = array(
                    "usuario" => $usuario,
                    "fecha" => $fecha

                );

                array_push($arreglo, $registro_item);
            }
            $respuesta->mensaje = "Ya se le dió salida a este producto terminado ";
            $respuesta->exito = false;
            $respuesta->registros = $arreglo;
        } else {
            $respuesta->mensaje = " ";
            $respuesta->exito = true;
        }


        return $respuesta;
    }



    function validaSalidasDevolucionProduccion($idProduccion)
    {



        $query = "SELECT *  
         from salidas where idDevolucionProduccion =" . $idProduccion;



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



                $registro_item = array(
                    "usuario" => $usuario,
                    "fecha" => $fecha

                );

                array_push($arreglo, $registro_item);
            }
            $respuesta->mensaje = "Ya se le dió salida a este producto terminado ";
            $respuesta->exito = false;
            $respuesta->registros = $arreglo;
        } else {
            $respuesta->mensaje = " ";
            $respuesta->exito = true;
        }


        return $respuesta;
    }



    function obtenerSalidas($cantidad)
    {
        if ($cantidad == "") {
            $query = "SELECT s.idSalida, p.kilos,p.cantidad,pr.producto,c.calibre,t.tipo,cd.metros lineales,u.unidad,cd.idUnidad,s.usuario,s.fecha
             FROM  salidas s
             inner join producciones p on p.idProduccion = s.idProduccion 
        inner join productos pr on pr.idProducto = p.idProducto
        inner join calibres c on c.idCalibre = pr.idCalibre
        inner join tipos t on t.idTipo =pr.idTipo
        inner join cotizaciondetalle cd on cd.idCotizacionDet = p.idCotizacionDetalle
        inner join unidades u on u.idUnidad=cd.idUnidad

            order by s.fecha desc ";
        } else {
            $query = "SELECT s.idSalida,p.kilos,p.cantidad,pr.producto,c.calibre,t.tipo,cd.metros lineales,u.unidad,cd.idUnidad,s.usuario,s.fecha
            FROM  salidas s
            inner join producciones p on p.idProduccion = s.idProduccion 
       inner join productos pr on pr.idProducto = p.idProducto
       inner join calibres c on c.idCalibre = pr.idCalibre
       inner join tipos t on t.idTipo =pr.idTipo
       inner join cotizaciondetalle cd on cd.idCotizacionDet = p.idCotizacionDetalle
       inner join unidades u on u.idUnidad=cd.idUnidad

           order by s.fecha desc  limit " . $cantidad;
        }






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



                $registro_item = array(
                    "idSalida" => $idSalida,
                    "producto" => $producto,
                    "calibre" => $calibre,
                    "tipo" => $tipo,
                    "lineales" => $lineales,
                    "idUnidad" => $idUnidad,
                    "unidad" => $unidad,
                    "kilos" => $kilos,
                    "cantidad" => $cantidad,
                    "usuario" => $usuario,

                    "fecha" => $fecha

                );

                array_push($arreglo, $registro_item);
            }
            $respuesta->mensaje = "Ya se le dió salida a este producto terminado ";
            $respuesta->exito = false;
            $respuesta->registros = $arreglo;
        } else {
            $respuesta->mensaje = " ";
            $respuesta->exito = true;
        }


        return $respuesta;
    }





    function obtenerExtrasCotizacion($idCotizacion)
    {


        $query = "SELECT c.observaciones,c.condiciones,c.vigencia,fp.formapago,c.lugarentrega,
        c.fechaentrega,c.idFormaPago
         from cotizaciones c 
         left join formaspago fp on fp.idFormaPago= c.idFormaPago
           
        where c.idCotizacion =" . $idCotizacion;



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



                $registro_item = array(
                    "observaciones" => $observaciones,
                    "condiciones" => $condiciones,
                    "vigencia" => $vigencia,
                    "formapago" => $formapago,
                    "idFormaPago" => $idFormaPago,
                    "lugarentrega" => $lugarentrega,
                    "fechaentrega" => $fechaentrega,



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



    function  obtenerAlmacenesDisponibles($idRecepcion)
    {

        if (strpos($idRecepcion, 'D') !== false) {

            $idRecepcion = str_replace("D", "", $idRecepcion);

            $query = "SELECT *
            from almacenes where idAlmacen <> 
            (select idAlmacen from devoluciones where idDevolucion = " . $idRecepcion . ") ";
        } else {

            $query = "SELECT *
         from almacenes where idAlmacen <> 
         (select idAlmacen from recepciones where idRecepcion = " . $idRecepcion . ") ";
        }


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



                $registro_item = array(
                    "idAlmacen" => $idAlmacen,
                    "almacen" => $almacen

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


    function obtenerDatosCartaPorte($idRemision)
    {


        $query = "SELECT *
         from remisiones 
         l
           
        where idRemision =" . $idRemision;



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



                $registro_item = array(
                    "tipoUnidad" => $tipoUnidad,
                    "placas" => $placas,
                    "operador" => $operador,
                    "contenedor" => $contenedor

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



    function obtenerDatosCodigoBarras($codigo)
    {


        $query = "SELECT * from recepciones where idRecepcion= '" . $codigo . "'";



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



                $registro_item = array(
                    "peso" => $peso,
                    "idAlmacen" => $idAlmacen,
                    "idProducto" => $idProducto
                );

                array_push($arreglo, $registro_item);
            }
            $respuesta->mensaje = "";
            $respuesta->exito = true;
            $respuesta->registros = $arreglo;
        } else {
            $respuesta->mensaje = "No se encontro la materia prima con ese código  ";
            $respuesta->exito = false;
        }


        return $respuesta;
    }




    function obtenerProduccionesCodigo($codigo)
    {


        $query = "SELECT * from producciones where idRecepcion= '" . $codigo . "'";



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



                $registro_item = array(
                    "kilos" => $kilos
                );

                array_push($arreglo, $registro_item);
            }
            $respuesta->mensaje = "";
            $respuesta->exito = true;
            $respuesta->registros = $arreglo;
        } else {
            $respuesta->mensaje = "No se encontro la materia prima con ese código  ";
            $respuesta->exito = false;
        }


        return $respuesta;
    }



    function eliminarCotizacion($idCotizacion)
    {
        $respuesta = new RespuestaBD();
        $cotizacion = $this->obtenerCotizacion($idCotizacion);

        if ($cotizacion->registros[0]['pedido'] != "1") {
            $query = "delete from cotizaciones where idCotizacion = " . $idCotizacion;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $respuesta->mensaje = "";
            $respuesta->exito = true;
        } else {
            $respuesta->mensaje = "La cotización ya es pedido, no se puede eliminar ";
            $respuesta->exito = false;
        }


        return $respuesta;
    }


    function actualizarCostoEnvio($idCotizacion, $costo)
    {
        $respuesta = new RespuestaBD();



        $query = "update cotizaciones set costoEnvio =" . $costo . " where idCotizacion = " . $idCotizacion;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $respuesta->mensaje = "";
        $respuesta->exito = true;


        return $respuesta;
    }


    function  realizarAbono($idCotizacion, $montoAbono, $idUsuario, $idFormaPago)
    {
        $respuesta = new RespuestaBD();

        $query = "insert into abonos (idUsuario,monto,fecha,idCotizacion,idFormaPago) 
            values (" . $idUsuario . "," . $montoAbono . ",
            now()," . $idCotizacion . "," . $idFormaPago . ")";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $respuesta->mensaje = "";
        $respuesta->exito = true;


        return $respuesta;
    }

    function actualizaSemaforo($idCotizacion, $color)
    {
        $respuesta = new RespuestaBD();



        $query = "update cotizaciones set semaforo ='" . $color . "' where idCotizacion = " . $idCotizacion;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $respuesta->mensaje = "";
        $respuesta->exito = true;


        return $respuesta;
    }


    function cancelarProduccion($idProduccion)
    {


        $respuesta = new RespuestaBD();

        if (strpos($idProduccion, 'D') !== false) {

            $idProduccion = str_replace("D", "", $idProduccion);

            $respuesta = $this->validaFolioDevolucionProduccion($idProduccion);

            if ($respuesta->exito) {
                $query = "delete from devolucionesproducciones where idDevolucionProduccion = " . $idProduccion;
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $respuesta->mensaje = "";
                $respuesta->exito = true;
            }
        } else {


            $respuesta = $this->validaFolioProduccion($idProduccion);

            if ($respuesta->exito) {
                $query = "delete from producciones where idProduccion = " . $idProduccion;
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $respuesta->mensaje = "";
                $respuesta->exito = true;
            }
        }


        return $respuesta;
    }


    function cancelarAbono($idAbono, $idUsuario)
    {
        $respuesta = new RespuestaBD();



        $query = "update abonos set idUsuarioCancela = " . $idUsuario . ", 
            fechaCancela=now() where idAbono=" . $idAbono . "";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $respuesta->mensaje = "";
        $respuesta->exito = true;



        return $respuesta;
    }





    function obtenerTotalKilosRecepciones($idProducto)
    {


        $query = "SELECT sum(peso) suma   from recepciones  
           
        where idProducto =" . $idProducto;
        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        $respuesta = new RespuestaBD();
        $sumaTotal = 0;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $sumaTotal = $suma;
        }
        $respuesta->mensaje = "";
        $respuesta->exito = true;
        $respuesta->valor = $sumaTotal;


        return $respuesta;
    }

    function obtenerTotalKilosProducidos($idProducto)
    {


        $query = "SELECT sum(kilos) suma   from cotizaciondetalle  
           
        where idProducto =" . $idProducto;
        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        $respuesta = new RespuestaBD();
        $sumaTotal = 0;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $sumaTotal = $suma;
        }
        $respuesta->mensaje = "";
        $respuesta->exito = true;
        $respuesta->valor = $sumaTotal;


        return $respuesta;
    }

    function actualizarCantidadCotDet($idCotizacionDet, $cantidad)
    {

        $query = "update cotizaciondetalle set cantidad=" . $cantidad . " where idCotizacionDet=" . $idCotizacionDet;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }



    function actualizarPrecioCotDet($idCotizacionDet, $precio)
    {

        $query = "update cotizaciondetalle set precioUnitario=" . $precio . " where idCotizacionDet=" . $idCotizacionDet;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }




    function realizarTraspaso($idRecepcion, $idAlmacen, $idUsuario,$idChofer)
    {

        if (strpos($idRecepcion, 'D') !== false) {

            $idRecepcion = str_replace("D", "", $idRecepcion);

            $query = "update devoluciones set idalmacen=" . $idAlmacen . " where idDevolucion=" . $idRecepcion;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
        } else {

            $idAlmacenSale = $this->obtenerDatosCodigoBarras($idRecepcion)->registros[0]['idAlmacen'];




            $query = "update recepciones set idalmacen=" . $idAlmacen . " where idRecepcion=" . $idRecepcion;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();


            $query = "insert into traspasos (idUsuario, idAlmacen, tipo, fecha, idRecepcion,idChofer) values
        (" . $idUsuario . "," . $idAlmacenSale . ",'S',now()," . $idRecepcion . ",".$idChofer.")";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            $query = "insert into traspasos (idUsuario, idAlmacen, tipo, fecha, idRecepcion,idChofer) values
        (" . $idUsuario . "," . $idAlmacen . ",'E',addtime(now(),'2')," . $idRecepcion . ",".$idChofer.")";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
        }
    }

    function actualizarMetrosCotDet($idCotizacionDet, $metros, $db)
    {

        $detalle = $this->consultaPartidaCotizacion($idCotizacionDet)->registros[0];
        $idProducto = $detalle['idProducto'];
        $idCotizacion = $detalle['idCotizacion'];

        $cotizacion = $this->obtenerCotizacion($idCotizacion)->registros[0];
        $tipoPrecio = $cotizacion['tipoPrecio'];
        //primero tenemos que ir por el producto 
        $catModel = new CatalogosModel($db);
        $producto = $catModel->obtenerProducto($idProducto)->registros[0];

        if ($tipoPrecio == "G") {
            $precioProductoSinIva = $producto['precioGen'];
        } else {
            $precioProductoSinIva = $producto['precioRev'];
        }
        $precioConIva = $precioProductoSinIva * 1.16;
        $preciUnitario = $precioConIva * $metros;
        $preciUnitario = round($preciUnitario);

        $query = "update cotizaciondetalle set preciounitario=" . $preciUnitario . ", metros=" . $metros . " where idCotizacionDet=" . $idCotizacionDet;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }


    function obtenerCotizacion($idCotizacion)
    {
        $query = "SELECT  co.*,cl.cliente,cl.direccion,cl.direccionentrega,cl.representante,
        cl.telefono,cl.mail,cl.tipoPrecio,fp.formapago,us.uso,usu.nombre nombreUsuario,usu.apellidos 
         FROM  cotizaciones co
             inner join clientes cl on co.idCliente = cl.idCliente
             inner join usuarios usu on usu.idUsuario = co.idUsuario
             left join formaspago fp on fp.idFormaPago = co.idFormaPago
             left join cfdiusos us on us.idUso = cl.idUso
               where idCotizacion=" . $idCotizacion;
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
                $detalle = $this->obtenerDetalleCotizacion($idCotizacion);

                $productos = $detalle->registros;


                $tieneRemision = false;
                foreach ($productos as $prod) {


                    if ($prod['tieneRemision']) {
                        $tieneRemision = true;
                    }
                }


                $registro_item = array(
                    "idCotizacion" => $idCotizacion,
                    "idCliente" => $idCliente,
                    "fecha" => $fecha,
                    "tieneRemision" => $tieneRemision,
                    "montototal" => $montototal,
                    "costoEnvio" => $costoEnvio,
                    "descuento" => $descuento,
                    "observaciones" => $observaciones,
                    "tipoPrecio" => $tipoPrecio,
                    "direccionentrega" => $direccionentrega,
                    "representante" => $representante,
                    "condiciones" => $condiciones,
                    "nombreUsuario" => $nombreUsuario,
                    "apellidos" => $apellidos,
                    "cliente" => $cliente,
                    "idUsuario" => $idUsuario,
                    "direccion" => $direccion,
                    "mail" => $mail,
                    "vigencia" => $vigencia,
                    "formapago" => $formapago,
                    "uso" => $uso,
                    "pedido" => $pedido,
                    "telefono" => $telefono,
                    "lugarentrega" => $lugarentrega,
                    "grantotal" => $grantotal,
                    "usuario" => $usuario,
                    "fechaEntrega" => $fechaEntrega,
                    "productos" => $productos

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


    function validaFolioProduccion($idProduccion)
    {
        $query = "Select * from remisiondetalle where idProduccion = " . $idProduccion . "";
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        $num = $stmt->rowCount();
        $respuesta = new RespuestaBD();

        if ($num > 0) {



            $respuesta->mensaje = "El folio de producción no se puede eliminar porque ya se le dió Salida";
            $respuesta->exito = false;
        } else {
            $respuesta->mensaje = " ";
            $respuesta->exito = true;
        }


        return $respuesta;
    }



    function validaFolioDevolucionProduccion($idProduccion)
    {


        $query = "Select * from remisiondetalle where idDevolucionProduccion = " . $idProduccion . "";
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        $num = $stmt->rowCount();
        $respuesta = new RespuestaBD();

        if ($num > 0) {



            $respuesta->mensaje = "El folio de devolución producción no se puede eliminar porque ya se le dió Salida";
            $respuesta->exito = false;
        } else {
            $respuesta->mensaje = " ";
            $respuesta->exito = true;
        }


        return $respuesta;
    }



    function recalcularCotizacion($idCotizacion)
    {

        $cotizacion = $this->obtenerCotizacion($idCotizacion);
        $descuento = $cotizacion->registros[0]['descuento'];
        $detalle = $this->obtenerDetalleCotizacion($idCotizacion);

        $productos = $detalle->registros;

        $subtotal = 0;
        foreach ($productos as $reg) {
            $totalPartida = 0;

            $totalPartida = $reg['preciounitario'] * $reg['cantidad'];


            $subtotal += $totalPartida;
        }

        if ($descuento == null || $descuento == "") {
            $grantotal = $subtotal;
        } else {
            $grantotal = $subtotal - ($subtotal * $descuento / 100);
        }

        $subtotal = $grantotal / 1.16;

        $this->actualizarTotales($subtotal, $grantotal, $idCotizacion);




        return true;
    }



    function obtenerDetalleCotizacion($idCotizacion)
    {
        $query = "SELECT  od.*,uf.unidad unidadFactura,u.unidad,p.sku,p.producto,p.medidasreves,
        c.calibre,t.tipo,p.pesoTeorico,p.largo,p.entrada,p.salida,a.ancho FROM  cotizaciondetalle od
             
             inner join productos p on p.idProducto=od.idProducto
             inner join unidades u on od.idUnidad = u.idUnidad
             inner join unidades uf on uf.idUnidad=p.idUnidadFactura
             inner join calibres c on p.idCalibre = c.idCalibre
             inner join anchos a on a.idAncho=p.idAncho
             inner join tipos t on p.idTipo = t.idTipo
             where idCotizacion=" . $idCotizacion;
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

                $producciones = $this->obtenerProducciones($idCotizacionDet)->registros;
                $devolucionesproducciones = $this->obtenerProduccionesDevoluciones($idCotizacionDet)->registros;



                $partidaTerminada = false;
                $cantidadProcesada = 0;
                $todasConRemisiones = true;
                $tieneRemision = false;
                if (count($producciones) > 0 || count($devolucionesproducciones) > 0) {
                    foreach ($producciones as $prod) {
                        if (!$prod['tieneRemision']) {
                            $todasConRemisiones = false;
                        } else {
                            $tieneRemision = true;
                        }
                        $cantidadProcesada = $cantidadProcesada + $prod['cantidad'];
                    }

                    foreach ($devolucionesproducciones as $prod) {
                        if (!$prod['tieneRemision']) {
                            $todasConRemisiones = false;
                        } else {
                            $tieneRemision = true;
                        }
                        $cantidadProcesada = $cantidadProcesada + $prod['cantidad'];
                    }
                } else {
                    $todasConRemisiones = false;
                }

                if ($cantidadProcesada >= $cantidad) {
                    $partidaTerminada = true;
                }


                $registro_item = array(
                    "idCotizacionDet" => $idCotizacionDet,
                    "producto" => $producto,
                    "idProducto" => $idProducto,
                    "unidad" => $unidad,
                    "calibre" => $calibre,
                    "tieneRemision" => $tieneRemision,
                    "medidasreves" => $medidasreves,
                    "tipo" => $tipo,
                    "sku" => $sku,
                    "ancho" => $ancho,
                    "largo" => $largo,
                    "entrada" => $entrada,
                    "unidadFactura" => $unidadFactura,
                    "salida" => $salida,
                    "idUnidad" => $idUnidad,
                    "cantidad" => $cantidad,
                    "devolucionesproducciones" => $devolucionesproducciones,
                    "pesoTeorico" => $pesoTeorico,
                    "partidaTerminada" => $partidaTerminada,
                    "todasConRemisiones" => $todasConRemisiones,
                    "producciones" => $producciones,
                    "cantidadProcesada" => $cantidadProcesada,
                    "preciounitario" => $preciounitario,
                    "precioUM" => $precioUM,
                    "metros" => $metros
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



    function obtenerAbonosCotizacion($idCotizacion)
    {
        $query = "SELECT  a.*,u.nombre usuario,uc.nombre usuarioCancela,f.formapago FROM  abonos a
             
            
             inner join usuarios u on u.idUsuario=a.idUsuario
             left join formaspago f on f.idFormaPago=a.idFormaPago
             left join usuarios uc on uc.idUsuario=a.idUsuarioCancela
             where idCotizacion=" . $idCotizacion;
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
                    "idAbono" => $idAbono,
                    "monto" => $monto,
                    "formapago" => $formapago,
                    "fecha" => $fecha,
                    "usuarioCancela" => $usuarioCancela,
                    "fechaCancela" => $fechaCancela,
                    "usuario" => $usuario
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


    function obtenerUltimaFechaDeEntrega($idCotizacion)
    {
        $query = "SELECT  max(fecha) fechaUltimaEntrega FROM remisiones 
             where idPedido=" . $idCotizacion;
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        $fechaEntrega="";
        // execute query
        $stmt->execute();
        $num = $stmt->rowCount();
        $respuesta = new RespuestaBD();

        if ($num > 0) {

            $arreglo = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                extract($row);

                $fechaEntrega = $fechaUltimaEntrega;

            }
            $respuesta->mensaje = "";
            $respuesta->exito = true;
            $respuesta->valor = $fechaEntrega;
        } else {
            $respuesta->mensaje = "No se encontraron datos ";
            $respuesta->exito = false;
        }


        return $respuesta;
    }



    function obtenerProducciones($idCotizacionDetalle)
    {
        $query = "SELECT  p.*,rd.idRemision, alm.almacen FROM  producciones p
        inner join almacenes alm on alm.idAlmacen = p.idAlmacen
            left join remisiondetalle rd on rd.idProduccion =p.idProduccion
             where p.idCotizacionDetalle=" . $idCotizacionDetalle;
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

                $tieneRemision = false;

                if ($idRemision != null && $idRemision != "") {
                    $tieneRemision = true;
                }

                $registro_item = array(
                    "idProduccion" => $idProduccion,
                    "kilos" => $kilos,
                    "usuario" => $usuario,
                    "almacen" => $almacen,
                    "cantidad" => $cantidad,
                    "idUsuario" => $idUsuario,
                    "tieneRemision" => $tieneRemision,
                    "fecha" => $fecha
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



    function obtenerProduccionesDevoluciones($idCotizacionDetalle)
    {
        $query = "SELECT  p.*,rd.idRemision, alm.almacen,u.nombre usuario FROM  devolucionesproducciones p
        inner join usuarios u on u.idUsuario = p.idUsuario 
        inner join almacenes alm on alm.idAlmacen = p.idAlmacen
            left join remisiondetalle rd on rd.idDevolucionProduccion =p.idDevolucionProduccion
             where p.idCotizacionDetalle=" . $idCotizacionDetalle;
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

                $tieneRemision = false;

                if ($idRemision != null && $idRemision != "") {
                    $tieneRemision = true;
                }

                $registro_item = array(
                    "idDevolucionProduccion" => $idDevolucionProduccion,
                    "kilos" => $kilos,
                    "usuario" => $usuario,
                    "almacen" => $almacen,
                    "cantidad" => $cantidad,
                    "idUsuario" => $idUsuario,
                    "tieneRemision" => $tieneRemision,
                    "fecha" => $fecha
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







    function obtenerUltimasProducciones($cantidad)
    {
        $query = "SELECT  p.*,cd.idCotizacion,pr.producto,c.calibre,
        t.tipo,u.unidad unidadFactura,pr.largo,a.ancho,cd.metros,pr.sku,remd.idRemisionDet FROM  producciones p
        inner join productos pr on pr.idProducto = p.idProductoProduccion
        inner join calibres c on c.idCalibre = pr.idCalibre
        inner join anchos a on a.idAncho=pr.idAncho
        inner join unidades u on u.idUnidad=pr.idUnidadFactura
        inner join tipos t on t.idTipo =pr.idTipo
        inner join cotizaciondetalle cd on cd.idCotizacionDet = p.idcotizacionDetalle
        left join remisiondetalle remd on remd.idProduccion = p.idProduccion

            
             order by idProduccion desc limit " . $cantidad;
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
                    "idProduccion" => $idProduccion,
                    "tipoProd" => "P",
                    "idCotizacion" => $idCotizacion,
                    "kilos" => $kilos,
                    "usuario" => $usuario,
                    "producto" => $producto,
                    "unidadFactura" => $unidadFactura,
                    "ancho" => $ancho,
                    "calibre" => $calibre,
                    "fecha" => $fecha,
                    "metros" => $metros,
                    "sku" => $sku,
                    "largo" => $largo,
                    "tipo" => $tipo,
                    "idRemisionDet" => $idRemisionDet,
                    "idUsuario" => $idUsuario,
                    "cantidad" => $cantidad,
                    "fecha" => $fecha
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




    function obtenerUltimasDevolucionesProducciones($cantidad)
    {
        $query = "SELECT  p.*,cd.idCotizacion,pr.producto,c.calibre,us.nombre usuario,
        t.tipo,u.unidad unidadFactura,pr.largo,a.ancho,cd.metros,pr.sku,remd.idRemisionDet FROM  devolucionesproducciones p
        inner join productos pr on pr.idProducto = p.idProducto
        inner join usuarios us on us.idUsuario = p.idUsuario
        inner join calibres c on c.idCalibre = pr.idCalibre
        inner join anchos a on a.idAncho=pr.idAncho
        inner join unidades u on u.idUnidad=pr.idUnidadFactura
        inner join tipos t on t.idTipo =pr.idTipo
        inner join cotizaciondetalle cd on cd.idCotizacionDet = p.idcotizacionDetalle
        left join remisiondetalle remd on remd.idDevolucionProduccion = p.idDevolucionProduccion

            
             order by idDevolucionProduccion desc limit " . $cantidad;
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
                    "idProduccion" => $idDevolucionProduccion,
                    "tipoProd" => "D",
                    "idCotizacion" => $idCotizacion,
                    "kilos" => $kilos,
                    "usuario" => $usuario,
                    "producto" => $producto,
                    "unidadFactura" => $unidadFactura,
                    "ancho" => $ancho,
                    "calibre" => $calibre,
                    "fecha" => $fecha,
                    "metros" => $metros,
                    "sku" => $sku,
                    "largo" => $largo,
                    "tipo" => $tipo,
                    "idRemisionDet" => $idRemisionDet,
                    "idUsuario" => $idUsuario,
                    "cantidad" => $cantidad,
                    "fecha" => $fecha
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



    function obtenerProduccion($idProduccion)
    {
        $query = "SELECT  p.*,pr.producto,c.calibre,t.tipo,cd.metros lineales,u.unidad,cd.idUnidad,cl.cliente,co.idCotizacion FROM  producciones p 
        inner join productos pr on pr.idProducto = p.idProducto
        inner join calibres c on c.idCalibre = pr.idCalibre
        inner join tipos t on t.idTipo =pr.idTipo
        inner join cotizaciondetalle cd on cd.idCotizacionDet = p.idCotizacionDetalle
        inner join unidades u on u.idUnidad=cd.idUnidad
        inner join cotizaciones co on co.idCotizacion = cd.idCotizacion
        inner join clientes cl on cl.idCliente = co.idCliente

             where p.idProduccion=" . $idProduccion;
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
                    "idProduccion" => $idProduccion,
                    "producto" => $producto,
                    "calibre" => $calibre,
                    "tipo" => $tipo,
                    "lineales" => $lineales,
                    "idUnidad" => $idUnidad,
                    "unidad" => $unidad,
                    "kilos" => $kilos,
                    "idCotizacion" => $idCotizacion,
                    "cliente" => $cliente,
                    "cantidad" => $cantidad,

                    "fecha" => $fecha
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




    function agregarCotizacion(
        $idCliente,
        $usuario,
        $idUsuario

    ) {
        $respuesta = new RespuestaBD();
        //primero insertamos 
        $query = "INSERT INTO
        cotizaciones
    SET
    idCliente=:idCliente,usuario=:usuario,idUsuario=:idUsuario, fecha=now()";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":idCliente", $idCliente);
        $stmt->bindParam(":usuario", $usuario);
        $stmt->bindParam(":idUsuario", $idUsuario);


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


    function agregarProductoCotizacion(
        $idCotizacion,
        $idProducto,
        $cantidad,
        $preciounitario,
        $metros,
        $idUnidad

    ) {

        //primero insertamos 
        $query = "INSERT INTO
        cotizaciondetalle
    SET
    idCotizacion=:idCotizacion,idProducto=:idProducto,cantidad=:cantidad,
    preciounitario=:preciounitario,precioUM=:precioUM
    ,metros=:metros,idUnidad=:idUnidad";
        $preciounitario = $preciounitario * 1.16;
        $precioUM = $preciounitario;
        if ($metros > 0) {
            $preciounitario = $preciounitario * $metros;
            $preciounitario = round($preciounitario);
        } else {
            $preciounitario = round($preciounitario);
        }

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":idCotizacion", $idCotizacion);
        $stmt->bindParam(":idProducto", $idProducto);
        $stmt->bindParam(":cantidad", $cantidad);
        $stmt->bindParam(":preciounitario", $preciounitario);
        $stmt->bindParam(":precioUM", $precioUM);
        $stmt->bindParam(":metros", $metros);
        $stmt->bindParam(":idUnidad", $idUnidad);


        if (!$stmt->execute()) {
            $mensaje = $stmt->errorInfo();
            $error = $mensaje[0];
        }
    }



    function actualizarCartaPorte(

        $contenedor,
        $placas,
        $tipoUnidad,
        $operador,
        $idRemision
    ) {

        //primero insertamos 


        $query = "update
                    remisiones
                SET
                
                contenedor=:contenedor,placas=:placas,
                tipoUnidad=:tipoUnidad,operador=:operador
                where idRemision=" . $idRemision;


        $stmt = $this->conn->prepare($query);


        $stmt->bindParam(":contenedor", $contenedor);
        $stmt->bindParam(":placas", $placas);

        $stmt->bindParam(":tipoUnidad", $tipoUnidad);
        $stmt->bindParam(":operador", $operador);


        if (!$stmt->execute()) {
            $mensaje = $stmt->errorInfo();
        }
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

        //primero insertamos 

        if ($fechaEntrega == "") {
            if ($vigencia == "") {
                $query = "update
                    cotizaciones
                SET
                
                lugarentrega=:lugarEntrega,idFormaPago=:formaPago,
                condiciones=:condiciones,observaciones=:observaciones
                where idCotizacion=" . $idCotizacion;
            } else {
                $query = "update
                cotizaciones
            SET
            
            lugarentrega=:lugarEntrega,idFormaPago=:formaPago,
            vigencia=:vigencia,condiciones=:condiciones,observaciones=:observaciones
             where idCotizacion=" . $idCotizacion;
            }
        } else {
            if ($vigencia == "") {
                $query = "update
            cotizaciones
        SET
        
        fechaEntrega=:fechaEntrega,lugarentrega=:lugarEntrega,idFormaPago=:formaPago,
        condiciones=:condiciones,observaciones=:observaciones
         where idCotizacion=" . $idCotizacion;
            } else {
                $query = "update
                cotizaciones
            SET
            
            fechaEntrega=:fechaEntrega,lugarentrega=:lugarEntrega,idFormaPago=:formaPago,
            vigencia=:vigencia,condiciones=:condiciones,observaciones=:observaciones
             where idCotizacion=" . $idCotizacion;
            }
        }

        $stmt = $this->conn->prepare($query);

        if ($fechaEntrega != "") {
            $stmt->bindParam(":fechaEntrega", $fechaEntrega);
        }
        $stmt->bindParam(":lugarEntrega", $lugarEntrega);
        $stmt->bindParam(":formaPago", $formaPago);
        if ($vigencia != "") {
            $stmt->bindParam(":vigencia", $vigencia);
        }
        $stmt->bindParam(":condiciones", $condiciones);
        $stmt->bindParam(":observaciones", $observaciones);


        if (!$stmt->execute()) {
            $mensaje = $stmt->errorInfo();
        }
    }



    function actualizarDescuentoCotizacion(

        $descuento,

        $idCotizacion
    ) {

        //primero insertamos 
        $query = "update
        cotizaciones
    SET
    
    descuento=:descuento
     where idCotizacion=" . $idCotizacion;

        $stmt = $this->conn->prepare($query);


        $stmt->bindParam(":descuento", $descuento);



        if (!$stmt->execute()) {
            $mensaje = $stmt->errorInfo();
        }
    }


    function actualizaInventarioProducto($idProducto, $kilospiezas)
    {

        //primero insertamos 
        $query = "update
        productos
    SET
    invkilospiezas=" . $kilospiezas . " where idProducto=" . $idProducto;

        $stmt = $this->conn->prepare($query);


        if (!$stmt->execute()) {
            $mensaje = $stmt->errorInfo();
        }
    }


    function actualizarTotales(

        $montototal,
        $grantotal,
        $idCotizacion
    ) {

        //primero insertamos 
        $query = "update
        cotizaciones
    SET
    
    montototal=:montototal,grantotal=:grantotal 
     where idCotizacion=" . $idCotizacion;

        $stmt = $this->conn->prepare($query);


        $stmt->bindParam(":montototal", $montototal);
        $stmt->bindParam(":grantotal", $grantotal);



        if (!$stmt->execute()) {
            $mensaje = $stmt->errorInfo();
        }
    }



    function eliminarProductoCotizacion(
        $idCotizacionDet

    ) {

        //primero insertamos 
        $query = "delete from 
        cotizaciondetalle
     where idCotizacionDet=" . $idCotizacionDet;

        $stmt = $this->conn->prepare($query);


        if (!$stmt->execute()) {
            $mensaje = $stmt->errorInfo();
        }
    }


    function duplicarPartida(
        $idCotizacionDet

    ) {

        //primero insertamos 
        $query = "INSERT INTO cotizaciondetalle (idProducto,cantidad,preciounitario,metros,idCotizacion,idUnidad,precioUM)
        SELECT idProducto,cantidad,preciounitario,metros,idCotizacion,idUnidad,precioUM FROM cotizaciondetalle
        
     where idCotizacionDet=" . $idCotizacionDet;

        $stmt = $this->conn->prepare($query);


        if (!$stmt->execute()) {
            $mensaje = $stmt->errorInfo();
        }
    }
}
