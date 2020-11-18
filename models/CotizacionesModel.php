<?php

include_once 'RespuestaBD.php';
include_once 'CatalogosModel.php';




class CotizacionesModel
{

    // database connection and table name
    private $conn;


    public function __construct($db)
    {
        $this->conn = $db;
    }


    function obtenerCotizaciones($tipo, $idUsuario)
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
        } else if ($tipo == "PR" || $tipo == "PS") {
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

                $productos = $detalle->registros;
                $terminada = true;
                $todasConRemisiones = true;
                foreach ($productos as $prod) {
                    if (!$prod['partidaTerminada']) {
                        $terminada = false;
                    }

                    if (!$prod['todasConRemisiones']) {
                        $todasConRemisiones = false;
                    }
                }




                $registro_item = array(
                    "idCotizacion" => $idCotizacion,
                    "idCliente" => $idCliente,
                    "fecha" => $fecha,
                    "montototal" => $montototal,
                    "descuento" => $descuento,
                    "observaciones" => $observaciones,
                    "cliente" => $cliente,
                    "condiciones" => $condiciones,
                    "vigencia" => $vigencia,
                    "formapago" => $formapago,
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

        $query = "SELECT  r.* FROM  remisiones r
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

                $registro_item = array(
                    "idRemision" => $idRemision,
                    "idUsuario" => $idUsuario,
                    "fecha" => $fecha,
                    "operador" => $operador,
                    "tipoUnidad" => $tipoUnidad,
                    "placas" => $placas,
                    "contenedor" => $contenedor,
                    "detalle" => $detalle,
                    "idPedido" => $idPedido,
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

        $query = "SELECT rd.idRemisionDet,rd.idRemision,pr.kilos,pr.cantidad,pr.usuario,pr.fecha,
        u.unidad unidadFactura,a.ancho,c.calibre,t.tipo,p.sku,p.producto ,cd.metros,p.largo,cd.preciounitario
         FROM  remisiondetalle rd
                inner join producciones pr on pr.idProduccion = rd.idProduccion
                inner join productos p on p.idProducto = pr.idProducto
                inner join cotizaciondetalle cd on cd.idCotizacionDet = pr.idCotizacionDetalle
                inner join unidades u on u.idUnidad=p.idUnidadFactura
                inner join calibres c on c.idCalibre = p.idCalibre
                inner join tipos t on t.idTipo = p.idTipo
                inner join anchos a on a.idAncho= p.idAncho
                where rd.idRemision = ".$idRemision."

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
                    
                    "tipo" => $tipo,
                    "sku" => $sku,
                    "producto" => $producto,
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
                where idCotizacionDet = ".$idCotizacionDet."

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
                    "idRecepcion" => $idRecepcion

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


    function togglePedido($idPedido, $activo)
    {

        $respuesta = new RespuestaBD();

        // check if more than 0 record found

        // query to insert record
        $query = "UPDATE
                   cotizaciones
                SET
                    produccion=" . $activo . " where idCotizacion=" . $idPedido;

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


    function generarMateriaProduccion(
        $idCotizacionDetM,
        $kilos,
        $cantidad,
        $usuario,
        $idUsuario,
        $idProducto,
        $idAlmacen,
        $codigoBarras,$utilizadosUsM
    ) {


        $respuesta = new RespuestaBD();

        $respuesta = $this->validaRecepcion($codigoBarras);

        if ($respuesta->exito) {
            //primero insertamos 
            $query = "INSERT INTO
        producciones
    SET
    idCotizacionDetalle=:idCotizacionDetalle,kilos=:kilos,cantidad=:cantidad,
    usuario=:usuario,idUsuario=:idUsuario,idRecepcion=:idRecepcion,imprimir=1,
    idProducto=:idProducto,idAlmacen=:idAlmacen,kilosUsuario=:utilizadosUsM, fecha=now()";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":idCotizacionDetalle", $idCotizacionDetM);
            $stmt->bindParam(":usuario", $usuario);
            $stmt->bindParam(":kilos", $kilos);
            $stmt->bindParam(":cantidad", $cantidad);
            $stmt->bindParam(":idUsuario", $idUsuario);
            $stmt->bindParam(":idProducto", $idProducto);
            $stmt->bindParam(":idAlmacen", $idAlmacen);
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


    function darSalidaProduccion($idProduccion, $idUsuario, $usuario)
    {

        $respuesta = new RespuestaBD();
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


        $query = "SELECT *
         from almacenes where idAlmacen <> 
         (select idAlmacen from recepciones where idRecepcion = ".$idRecepcion.") ";
         



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


    function actualizarCostoEnvio($idCotizacion,$costo)
    {
        $respuesta = new RespuestaBD();
        

        
            $query = "update cotizaciones set costoEnvio =".$costo." where idCotizacion = " . $idCotizacion;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $respuesta->mensaje = "";
            $respuesta->exito = true;
       

        return $respuesta;
    }


    function actualizaSemaforo($idCotizacion,$color)
    {
        $respuesta = new RespuestaBD();
        

        
            $query = "update cotizaciones set semaforo ='".$color."' where idCotizacion = " . $idCotizacion;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $respuesta->mensaje = "";
            $respuesta->exito = true;
       

        return $respuesta;
    }


    function cancelarProduccion($idProduccion)
    {
        $respuesta = new RespuestaBD();
        $respuesta = $this->validaFolioProduccion($idProduccion);

        if ($respuesta->exito) {
            $query = "delete from producciones where idProduccion = " . $idProduccion;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $respuesta->mensaje = "";
            $respuesta->exito = true;
        }


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


    

    function realizarTraspaso($idRecepcion, $idAlmacen,$idUsuario)
    {


        $idAlmacenSale = $this->obtenerDatosCodigoBarras($idRecepcion)->registros[0]['idAlmacen'];


       

        $query = "update recepciones set idalmacen=" . $idAlmacen . " where idRecepcion=" . $idRecepcion;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();


        $query = "insert into traspasos (idUsuario, idAlmacen, tipo, fecha, idRecepcion) values
        (".$idUsuario.",".$idAlmacenSale.",'S',now(),".$idRecepcion.")"; 
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $query = "insert into traspasos (idUsuario, idAlmacen, tipo, fecha, idRecepcion) values
        (".$idUsuario.",".$idAlmacen.",'E',addtime(now(),'2'),".$idRecepcion.")"; 
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
    }

    function actualizarMetrosCotDet($idCotizacionDet, $metros,$db)
    {

        $detalle=$this->consultaPartidaCotizacion($idCotizacionDet)->registros[0];
        $idProducto=$detalle['idProducto'];
        $idCotizacion=$detalle['idCotizacion'];

        $cotizacion =$this->obtenerCotizacion($idCotizacion)->registros[0];
        $tipoPrecio = $cotizacion['tipoPrecio'];
        //primero tenemos que ir por el producto 
        $catModel = new CatalogosModel($db);
        $producto=$catModel->obtenerProducto($idProducto)->registros[0];
        
        if($tipoPrecio=="G")
        {
            $precioProductoSinIva=$producto['precioGen'];
        }
        else
        {
            $precioProductoSinIva=$producto['precioRev'];
        }
        $precioConIva=$precioProductoSinIva*1.16;
        $preciUnitario=$precioConIva*$metros;
        $preciUnitario=round($preciUnitario);

        $query = "update cotizaciondetalle set preciounitario=".$preciUnitario.", metros=" . $metros . " where idCotizacionDet=" . $idCotizacionDet;
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



                $registro_item = array(
                    "idCotizacion" => $idCotizacion,
                    "idCliente" => $idCliente,
                    "fecha" => $fecha,
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

        $subtotal=$grantotal / 1.16;

        $this->actualizarTotales($subtotal, $grantotal, $idCotizacion);




        return true;
    }



    function obtenerDetalleCotizacion($idCotizacion)
    {
        $query = "SELECT  od.*,uf.unidad unidadFactura,u.unidad,p.sku,p.producto,
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

                $partidaTerminada = false;
                $cantidadProcesada = 0;
                $todasConRemisiones = true;
                if (count($producciones) > 0) {
                    foreach ($producciones as $prod) {
                        if (!$prod['tieneRemision']) {
                            $todasConRemisiones = false;
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
                    "tipo" => $tipo,
                    "sku" => $sku,
                    "ancho" => $ancho,
                    "largo" => $largo,
                    "entrada" => $entrada,
                    "unidadFactura" => $unidadFactura,
                    "salida" => $salida,
                    "idUnidad" => $idUnidad,
                    "cantidad" => $cantidad,
                    "producciones" => $producciones,
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




    function obtenerUltimasProducciones($cantidad)
    {
        $query = "SELECT  p.*,cd.idCotizacion,pr.producto,c.calibre,
        t.tipo,u.unidad unidadFactura,pr.largo,a.ancho,cd.metros,pr.sku,remd.idRemisionDet FROM  producciones p
        inner join productos pr on pr.idProducto = p.idProducto
        inner join calibres c on c.idCalibre = pr.idCalibre
        inner join anchos a on a.idAncho=pr.idAncho
        inner join unidades u on u.idUnidad=pr.idUnidadFactura
        inner join tipos t on t.idTipo =pr.idTipo
        inner join cotizaciondetalle cd on cd.idCotizacionDet = p.idcotizacionDetalle
        left join remisiondetalle remd on remd.idProduccion = p.idProduccion

            
             order by idProduccion desc limit ".$cantidad;
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
    $preciounitario=$preciounitario*1.16;
    $precioUM=$preciounitario;
    if($metros>0)
    {
        $preciounitario= $preciounitario*$metros;
        $preciounitario= round($preciounitario);
    }
    else
    {
        $preciounitario= round($preciounitario);
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
}
