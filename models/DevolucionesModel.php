<?php

include_once 'RespuestaBD.php';



class DevolucionesModel
{

    // database connection and table name
    private $conn;



    public function __construct($db)
    {
        $this->conn = $db;
    }





    function insertarDevolucion($idUsuario, $idProducto, $cantidad, $kilos, $idAlmacen)
    {

        $respuesta = new RespuestaBD();


        // check if more than 0 record found


        // query to insert record
        $query = "INSERT INTO
                    devoluciones
                SET
                idProducto=:idProducto,cantidad=:cantidad,kilos=:kilos,idAlmacen=:idAlmacen,
                idUsuario=:idUsuario,fecha=now()";

        // prepare query
        $stmt = $this->conn->prepare($query);



        $stmt->bindParam(":idProducto", $idProducto);
        $stmt->bindParam(":cantidad", $cantidad);
        $stmt->bindParam(":kilos", $kilos);
        $stmt->bindParam(":idUsuario", $idUsuario);
        $stmt->bindParam(":idAlmacen", $idAlmacen);

        // execute query
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


    function obtenerDevoluciones()
    {


        $query = "SELECT  d.kilos,d.cantidad,d.idDevolucion,d.fecha,pr.sku,pr.largo
        ,u.nombre usuario,pr.producto,c.calibre,t.tipo,a.ancho,pr.largo, un.unidad,alm.almacen,d.idAlmacen
        FROM   devoluciones d
        inner join usuarios u on u.idUsuario = d.idUsuario
        inner join productos pr on pr.idProducto = d.idProducto
        inner join unidades un on un.idUnidad=pr.idUnidadFactura
        inner join anchos a on a.idAncho= pr.idAncho
        inner join calibres c on c.idCalibre = pr.idCalibre
        inner join tipos t on t.idTipo =pr.idTipo
        inner join almacenes alm on alm.idAlmacen = d.idAlmacen
         order by d.fecha";


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

                $devolucionesproducciones = $this->obtenerDevolucionesProducciones($idDevolucion)->registros;
                $usados = 0;
                $restante = 0;
                foreach ($devolucionesproducciones as $devprod) {
                    $usados = $usados + $devprod['cantidad'];
                }
                $restante = $cantidad - $usados;

                $registro_item = array(
                    "idDevolucion" => $idDevolucion,
                    "usuario" => $usuario,
                    "fecha" => $fecha,
                    "tipo" => $tipo,
                    "ancho" => $ancho,
                    "largo" => $largo,
                    "calibre" => $calibre,
                    "unidad" => $unidad,
                    "sku" => $sku,
                    "producto" => $producto,
                    "largo" => $largo,
                    "kilos" => $kilos,
                    "almacen" => $almacen,
                    "idAlmacen" => $idAlmacen,
                    "usados" => $usados,
                    "restante" => $restante,
                    "devolucionesproducciones" => $devolucionesproducciones,

                    "cantidad" => $cantidad

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


    function obtenerDevolucion($idDevolucion)
    {


        $query = "SELECT  d.kilos,d.cantidad,d.idDevolucion,d.fecha,pr.sku,pr.largo,d.idProducto
        ,u.nombre usuario,pr.producto,c.calibre,t.tipo,a.ancho,pr.largo, un.unidad,alm.almacen,d.idAlmacen
        FROM   devoluciones d
        inner join usuarios u on u.idUsuario = d.idUsuario
        inner join productos pr on pr.idProducto = d.idProducto
        inner join unidades un on un.idUnidad=pr.idUnidadFactura
        inner join anchos a on a.idAncho= pr.idAncho
        inner join calibres c on c.idCalibre = pr.idCalibre
        inner join tipos t on t.idTipo =pr.idTipo
        inner join almacenes alm on alm.idAlmacen = d.idAlmacen
        where d.idDevolucion = " . $idDevolucion;


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

                $devolucionproducciones = $this->obtenerDevolucionesProducciones($idDevolucion)->registros;

                $cantUsada = 0;
                foreach ($devolucionproducciones as $devpro) {
                    $cantUsada = $cantUsada + $devpro['cantidad'];
                }

                $restante = $cantidad - $cantUsada;

                $registro_item = array(
                    "idDevolucion" => $idDevolucion,
                    "usuario" => $usuario,
                    "fecha" => $fecha,
                    "tipo" => $tipo,
                    "ancho" => $ancho,
                    "largo" => $largo,
                    "calibre" => $calibre,
                    "idProducto" => $idProducto,
                    "unidad" => $unidad,
                    "sku" => $sku,
                    "producto" => $producto,
                    "largo" => $largo,
                    "kilos" => $kilos,
                    "almacen" => $almacen,
                    "idAlmacen" => $idAlmacen,
                    "devolucionproducciones" => $devolucionproducciones,
                    "cantUsada" => $cantUsada,
                    "restante" => $restante,

                    "cantidad" => $cantidad

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


    function obtenerDevolucionesProducciones($idDevolucion)
    {


        $query = "SELECT  * from devolucionesproducciones where idDevolucion = " . $idDevolucion;


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
                    "idDevolucionProduccion" => $idDevolucionProduccion,
                    "idDevolucion" => $idDevolucion,
                    "cantidad" => $cantidad,
                    "kilos" => $kilos,
                    "idUsuario" => $idUsuario,
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
}
