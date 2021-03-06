<?php

include_once 'RespuestaBD.php';



class CatalogosModel
{

    // database connection and table name
    private $conn;


    public function __construct($db)
    {
        $this->conn = $db;
    }

    function obtenerProductos()
    {


        $query = "SELECT  p.*,c.calibre,t.tipo,u.unidad,a.ancho,uf.unidad unidadFactura FROM  productos p
            inner join calibres c on c.idCalibre=p.idCalibre
            inner join tipos t on t.idTipo=p.idTipo
            inner join anchos a on a.idAncho = p.idAncho
            inner join unidades u on u.idUnidad=p.idUnidad
            inner join unidades uf on uf.idUnidad=p.idUnidadFactura

                order by producto ";



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
                    "idCalibre" => $idCalibre,
                    "idTipo" => $idTipo,
                    "idUnidad" => $idUnidad,
                    "unidad" => $unidad,
                    "idMateriaPrima" => $idMateriaPrima,
                    "medidasreves" => $medidasreves,
                    "idAncho" => $idAncho,
                    "producto" => $producto,
                    "largo" => $largo,
                    "unidadFactura" => $unidadFactura,
                    "idUnidadFactura" => $idUnidadFactura,
                    "sku" => $sku,
                    "ancho" => $ancho,
                    "entrada" => $entrada,
                    "salida" => $salida,
                    "calibre" => $calibre,
                    "pesoTeorico" => $pesoTeorico,
                    "tipo" => $tipo,
                    "invkilospiezas" => $invkilospiezas,
                    "precioGen" => $precioGen,
                    "precioRev" => $precioRev,
                    "activo" => $activo

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


    function validarProductoUsado($idProducto)
    {
        $estaUsado = false;

        $query = "select * from ordenescompradetalle where idProducto = " . $idProducto;
        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        $num = $stmt->rowCount();


        if ($num > 0) {

            $estaUsado = true;

            return $estaUsado;
        }



        $query = "select * from cotizaciondetalle where idProducto = " . $idProducto;
        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        $num = $stmt->rowCount();


        if ($num > 0) {

            $estaUsado = true;

            return $estaUsado;
        }



        return $estaUsado;
    }





    function obtenerProductosEntradaConComprometido()
    {


        $query = "SELECT  p.*,c.calibre,t.tipo,u.unidad,a.ancho,uf.unidad unidadFactura FROM  productos p
            inner join calibres c on c.idCalibre=p.idCalibre
            inner join tipos t on t.idTipo=p.idTipo
            inner join anchos a on a.idAncho = p.idAncho
            inner join unidades u on u.idUnidad=p.idUnidad
            inner join unidades uf on uf.idUnidad=p.idUnidadFactura
            where p.entrada=1 and p.activo =1

                order by sku ";



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


                $respuesta = $this->obtenerInventarioComprometido($idProducto);
                $cantidadTotal = $this->obtenerInventarioTotal($idProducto);
                $cantidadTotalUsada = $this->obtenerInventarioUsado($idProducto);


                $comprometido = 0;
                $unidadComp = "";
                if ($respuesta->exito) {
                    $comprometido = $respuesta->registros['cantidad'];
                    $unidadComp = $respuesta->registros['unidad'];
                }

                $registro_item = array(
                    "idProducto" => $idProducto,
                    "idCalibre" => $idCalibre,
                    "comprometido" => $comprometido,
                    "unidadComp" => $unidadComp,
                    "idTipo" => $idTipo,
                    "idUnidad" => $idUnidad,
                    "unidad" => $unidad,
                    "idAncho" => $idAncho,
                    "producto" => $producto,
                    "largo" => $largo,
                    "unidadFactura" => $unidadFactura,
                    "idUnidadFactura" => $idUnidadFactura,
                    "sku" => $sku,
                    "ancho" => $ancho,
                    "entrada" => $entrada,
                    "salida" => $salida,
                    "calibre" => $calibre,
                    "pesoTeorico" => $pesoTeorico,
                    "tipo" => $tipo,
                    "inventarioTotal" => $cantidadTotal,
                    "inventarioUsado" => $cantidadTotalUsada,

                    "invkilospiezas" => $invkilospiezas,
                    "precioGen" => $precioGen,
                    "precioRev" => $precioRev,
                    "activo" => $activo

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


    function obtenerInventarioComprometido($idProducto)
    {


        $query = "
        select cd.cantidad,cd.idUnidad,uni.unidad,pr.pesoTeorico,cd.metros from cotizaciondetalle cd 
        inner join unidades uni on uni.idUnidad=cd.idUnidad
        inner join productos pr on pr.idProducto = cd.idProducto
         where cd.idProducto in (
        select idProducto from productos where idMateriaPrima= " . $idProducto . ")
         and idCotizacionDet not in (select idCotizacionDetalle from producciones)";



        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        $num = $stmt->rowCount();
        $respuesta = new RespuestaBD();

        if ($num > 0) {

            $cantidadComprometida = 0;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                if ($idUnidad == 3) {
                    //kilos
                    $cantidadComprometida = $cantidadComprometida + $cantidad;
                } else if ($idUnidad == 1) {
                    //piezas

                    $cantidadComprometida = $cantidadComprometida + $cantidad;
                } else {
                    //metros
                    if ($pesoTeorico != null && $pesoTeorico > 0) {
                        if ($metros != null && $metros > 0) {
                            $cantidadComprometida = $cantidadComprometida + ($cantidad * $metros);
                        } else {
                            $cantidadComprometida = $cantidadComprometida + $cantidad;
                        }
                    } else {
                        $cantidadComprometida = $cantidadComprometida + $cantidad;
                    }
                }

                $unidadMostrar = $unidad;
                $idUnidadMost = $idUnidad;
            }
            $registro_item = array(
                "cantidad" => $cantidadComprometida,
                "idUnidad" => $idUnidadMost,
                "unidad" => $unidadMostrar
            );
            $respuesta->mensaje = "";
            $respuesta->exito = true;
            $respuesta->registros = $registro_item;
        } else {

            $respuesta->mensaje = "No se encontraron datos ";
            $respuesta->exito = false;
        }


        return $respuesta;
    }



    function obtenerInventarioTotal($idProducto)
    {


        $query = "
        select  r.cantidad,  r.peso,r.idUnidad,pr.pesoTeorico from recepciones r 
        inner join productos pr on pr.idProducto = r.idProducto 
        where r.idProducto = " . $idProducto . "";



        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        $num = $stmt->rowCount();
        $respuesta = new RespuestaBD();
        $cantidadTotal = 0;
        if ($num > 0) {


            $cantidadTotal = 0;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                if ($idUnidad == 1) {
                    //piezas

                    $cantidadTotal = $cantidadTotal + ($cantidad);
                } else if ($idUnidad == 2) {

                    $cantidadTotal = $cantidadTotal + ($cantidad);
                } else {
                    //kilos
                    $cantidadTotal = $cantidadTotal + $peso;
                }
            }
        }


        return $cantidadTotal;
    }



    function obtenerInventarioUsado($idProducto)
    {


        $query = "
        select sum(kilos) cantidad  from producciones where idProductoRecepcion = " . $idProducto . "";



        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        $num = $stmt->rowCount();

        $cantidadTotal = 0;
        if ($num > 0) {



            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $cantidadTotal = $cantidad;
            }
        }


        return $cantidadTotal;
    }





    function obtenerProductosEntrada()
    {


        $query = "SELECT  p.*,c.calibre,t.tipo,u.unidad,a.ancho,uf.unidad unidadFactura FROM  productos p
            inner join calibres c on c.idCalibre=p.idCalibre
            inner join tipos t on t.idTipo=p.idTipo
            inner join anchos a on a.idAncho = p.idAncho
            inner join unidades u on u.idUnidad=p.idUnidad
            inner join unidades uf on uf.idUnidad=p.idUnidadFactura
            where p.entrada=1 and p.activo =1

                order by sku ";



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
                    "idCalibre" => $idCalibre,
                    "idTipo" => $idTipo,
                    "idUnidad" => $idUnidad,
                    "unidad" => $unidad,
                    "idAncho" => $idAncho,
                    "producto" => $producto,
                    "largo" => $largo,
                    "unidadFactura" => $unidadFactura,
                    "idUnidadFactura" => $idUnidadFactura,
                    "sku" => $sku,
                    "ancho" => $ancho,
                    "entrada" => $entrada,
                    "salida" => $salida,
                    "calibre" => $calibre,
                    "pesoTeorico" => $pesoTeorico,
                    "tipo" => $tipo,
                    "invkilospiezas" => $invkilospiezas,
                    "precioGen" => $precioGen,
                    "precioRev" => $precioRev,
                    "activo" => $activo

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


    function obtenerProductosSalida()
    {


        $query = "SELECT  p.*,c.calibre,t.tipo,u.unidad,a.ancho,uf.unidad unidadFactura FROM  productos p
            inner join calibres c on c.idCalibre=p.idCalibre
            inner join tipos t on t.idTipo=p.idTipo
            inner join anchos a on a.idAncho = p.idAncho
            inner join unidades u on u.idUnidad=p.idUnidad
            inner join unidades uf on uf.idUnidad=p.idUnidadFactura
            where p.salida=1 and p.activo=1

                order by producto ";



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
                    "idCalibre" => $idCalibre,
                    "idTipo" => $idTipo,
                    "idUnidad" => $idUnidad,
                    "unidad" => $unidad,
                    "idAncho" => $idAncho,
                    "producto" => $producto,
                    "largo" => $largo,
                    "unidadFactura" => $unidadFactura,
                    "idUnidadFactura" => $idUnidadFactura,
                    "sku" => $sku,
                    "ancho" => $ancho,
                    "entrada" => $entrada,
                    "salida" => $salida,
                    "calibre" => $calibre,
                    "pesoTeorico" => $pesoTeorico,
                    "tipo" => $tipo,
                    "invkilospiezas" => $invkilospiezas,
                    "precioGen" => $precioGen,
                    "precioRev" => $precioRev,
                    "activo" => $activo

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




    function obtenerRecepciones($idProducto)
    {


        $query = "SELECT  r.usuarioRecibe,r.idRecepcion,r.cantidad,r.peso,u.unidad,p.idUnidad,
            a.almacen,r.fechaRecibe FROM  recepciones r
            inner join almacenes a on a.idAlmacen=r.idAlmacen
            inner join productos p on p.idProducto = r.idProducto
            inner join unidades u on u.idUnidad=r.idUnidad
            where r.idProducto = " . $idProducto . "
                order by r.fechaRecibe desc ";



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
                    "usuario" => $usuarioRecibe,
                    "id" => "R" . $idRecepcion,
                    "idMov" => "R" . $idRecepcion,
                    "cantidad" => $cantidad,
                    "peso" => $peso,
                    "unidad" => $unidad,
                    "idUnidad" => $idUnidad,
                    "tipo" => "R",
                    "almacen" => $almacen,
                    "fecha" => $fechaRecibe

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


    function obtenerTraspasos($idProducto)
    {


        $query = "SELECT  t.idTraspaso, us.nombre usuario,r.idRecepcion,0 cantidad,0 peso,u.unidad,
            a.almacen,t.fecha,t.tipo,p.idUnidad FROM  traspasos t
            inner join usuarios us on us.idUsuario = t.idUsuario
            inner join recepciones r on r.idRecepcion = t.idRecepcion
            inner join productos p on p.idProducto=r.idProducto
            inner join almacenes a on a.idAlmacen=t.idAlmacen
            inner join unidades u on u.idUnidad=r.idUnidad
            where r.idProducto = " . $idProducto . "
                order by t.fecha desc ";



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
                    "id" => "R" . $idRecepcion,
                    "idMov" => "T" . $idTraspaso,
                    "cantidad" => $cantidad,
                    "peso" => $peso,
                    "unidad" => $unidad,
                    "idUnidad" => $idUnidad,
                    "tipo" => $tipo,
                    "almacen" => $almacen,
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



    function obtenerRecepcionesGlobal()
    {


        $query = "SELECT  r.usuarioRecibe,pr.producto,c.calibre,an.ancho,pr.largo,pr.sku,
        t.tipo,r.idRecepcion,r.cantidad,r.peso,r.cantidad,u.unidad,
            a.almacen,r.fechaRecibe,pr.idUnidad FROM  recepciones r
            inner join productos pr on pr.idProducto = r.idProducto
            inner join anchos an on an.idAncho = pr.idAncho
        inner join calibres c on c.idCalibre = pr.idCalibre
        inner join tipos t on t.idTipo =pr.idTipo
            inner join almacenes a on a.idAlmacen=r.idAlmacen
            inner join unidades u on u.idUnidad=r.idUnidad
            
                order by r.fechaRecibe desc ";



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

                $detalle = $this->obtenerdetalleUsoRecepcion($idRecepcion)->registros;

                $kilosUsados = 0;
                foreach ($detalle as $det) {
                    $kilosUsados = $kilosUsados + $det['kilos'];
                }

                if ($peso == null) {
                    $restante = $cantidad - $kilosUsados;
                } else {
                    $restante = $peso - $kilosUsados;
                }

                $registro_item = array(
                    "usuario" => $usuarioRecibe,
                    "id" => $idRecepcion,
                    "idRecepcion" => $idRecepcion,
                    "cantidad" => $cantidad,
                    "peso" => $peso,
                    "unidad" => $unidad,
                    "idUnidad" => $idUnidad,
                    "largo" => $largo,
                    "ancho" => $ancho,
                    "sku" => $sku,
                    "tipo" => $tipo,
                    "producto" => $producto,
                    "calibre" => $calibre,
                    "almacen" => $almacen,
                    "detalle" => $detalle,
                    "kilosUsados" => $kilosUsados,
                    "restante" => $restante,

                    "fecha" => $fechaRecibe

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

    function obtenerdetalleUsoRecepcion($idRecepcion)
    {


        $query = "SELECT  p.*,pr.producto,c.calibre,t.tipo from producciones p
        inner join productos pr on pr.idProducto = p.idProductoRecepcion
        inner join calibres c on c.idCalibre = pr.idCalibre
        inner join tipos t on t.idTipo =pr.idTipo  where idRecepcion = " . $idRecepcion . " ";



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
                    "calibre" => $calibre,
                    "tipo" =>  $tipo,
                    "producto" => $producto,
                    "kilos" => $kilos,
                    "usuario" => $usuario,
                    "fecha" => $fecha,
                    "kilosUsuario" => $kilosUsuario,
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



    function obtenerSalidas($idProducto)
    {


        $query = "SELECT  remd.idRemision,pr.idUnidad,s.idSalida,s.usuario,s.fecha,p.kilos,p.cantidad,u.unidad,a.almacen,re.idRecepcion FROM  salidas s
            inner join producciones p on p.idProduccion = s.idProduccion
            inner join recepciones re on re.idRecepcion = p.idRecepcion 
            inner join remisiondetalle remd on remd.idProduccion = s.idProduccion
            inner join productos pr on pr.idProducto = re.idProducto
            inner join almacenes a on a.idAlmacen=p.idAlmacen
            inner join cotizaciondetalle cd on cd.idCotizacionDet=p.idCotizacionDetalle
            inner join unidades u on u.idUnidad=cd.idUnidad
            where re.idProducto=" . $idProducto . "

                order by s.fecha desc ";



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
                    "id" => "R" . $idRecepcion,
                    "idMov" => "REM" . $idRemision,
                    "cantidad" => $cantidad,
                    "peso" => $kilos,
                    "unidad" => $unidad,
                    "tipo" => "S",
                    "almacen" => $almacen,
                    "idUnidad" => $idUnidad,
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



    function obtenerClientes()
    {


        $query = "SELECT  c.*,u.uso from clientes c
        left join cfdiusos u on u.idUso=c.idUso
           order by rfc ";


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
                    "idCliente" => $idCliente,
                    "cliente" => $cliente,
                    "rfc" => $rfc,
                    "representante" => $representante,
                    "direccion" => $direccion,
                    "telefono" => $telefono,
                    "mail" => $mail,
                    "idUso" => $idUso,
                    "idVendedor" => $idVendedor,
                    "uso" => $uso,
                    "direccionentrega" => $direccionentrega,
                    "tipoprecio" => $tipoprecio,
                    "comentarios" => $comentarios,
                    "activo" => $activo



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


    function obtenerClientesActivos()
    {


        $query = "SELECT  c.*,u.uso from clientes c
        left join cfdiusos u on u.idUso=c.idUso
        where c.activo = 1
           order by cliente ";


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
                    "idCliente" => $idCliente,
                    "cliente" => $cliente,
                    "rfc" => $rfc,
                    "representante" => $representante,
                    "direccion" => $direccion,
                    "telefono" => $telefono,
                    "mail" => $mail,
                    "idUso" => $idUso,
                    "idVendedor" => $idVendedor,
                    "uso" => $uso,
                    "direccionentrega" => $direccionentrega,
                    "tipoprecio" => $tipoprecio,
                    "comentarios" => $comentarios,
                    "activo" => $activo



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


    function obtenerTipos()
    {


        $query = "SELECT  * from tipos 
           order by tipo ";


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
                    "idTipo" => $idTipo,
                    "tipo" => $tipo,
                    "activo" => $activo

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




    function obtenerChoferes()
    {


        $query = "SELECT  * from choferes 
           order by chofer ";


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
                    "idChofer" => $idChofer,
                    "chofer" => $chofer,
                    "activo" => $activo

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




    function obtenerCamiones()
    {


        $query = "SELECT  * from camiones 
           order by camion ";


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
                    "idCamion" => $idCamion,
                    "camion" => $camion,
                    "placas" => $placas,
                    "activo" => $activo

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


    function obtenerUsos()
    {


        $query = "SELECT  * from cfdiusos 
           order by uso ";


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
                    "idUso" => $idUso,
                    "uso" => $uso,
                    "activo" => $activo

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





    function obtenerLargos($activos)
    {

        if ($activos) {
            $query = "SELECT  * from largos  where activo = 1
            order by largo ";
        } else {

            $query = "SELECT  * from largos 
           order by largo ";
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
                    "idLargo" => $idLargo,
                    "largo" => $largo,
                    "activo" => $activo

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


    function obtenerAnchos($activos)
    {

        if ($activos) {
            $query = "SELECT  * from anchos  where activo = 1
            order by ancho ";
        } else {

            $query = "SELECT  * from anchos 
           order by ancho ";
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
                    "idAncho" => $idAncho,
                    "ancho" => $ancho,
                    "activo" => $activo

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


    function obtenerFormasPago($activos)
    {

        if ($activos) {
            $query = "SELECT  * from formaspago  where activo = 1
            order by formapago ";
        } else {

            $query = "SELECT  * from formaspago 
           order by formapago ";
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
                    "idFormaPago" => $idFormaPago,
                    "formaPago" => $formaPago,
                    "activo" => $activo

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


    function obtenerTiposActivos()
    {


        $query = "SELECT  * from tipos where activo=1
           order by tipo ";


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
                    "idTipo" => $idTipo,
                    "tipo" => $tipo,
                    "activo" => $activo

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



    function obtenerCalibres()
    {


        $query = "SELECT  * from calibres 
           order by calibre ";


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
                    "idCalibre" => $idCalibre,
                    "calibre" => $calibre,
                    "activo" => $activo

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



    function obtenerAlmacenes()
    {


        $query = "SELECT  * from almacenes 
           order by almacen ";


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
                    "almacen" => $almacen,
                    "activo" => $activo

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






    function obtenerAlmacenesDisponibles($idRecepcion)
    {


        $query = "SELECT  * from almacenes  where idAlamcen is not in 
        (select idAlmacen from recepciones where idRecepcion = " . $idRecepcion . ")
           order by almacen ";


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
                    "almacen" => $almacen,
                    "activo" => $activo

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



    function obtenerProveedores()
    {


        $query = "SELECT  * from proveedores 
           order by proveedor ";


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
                    "idProveedor" => $idProveedor,
                    "proveedor" => $proveedor,
                    "rfc" => $rfc,
                    "direccion" => $direccion,
                    "telefono" => $telefono,
                    "comentarios" => $comentarios,
                    "activo" => $activo

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



    function obtenerProveedoresActivos()
    {


        $query = "SELECT  * from proveedores 
        where activo = 1
           order by proveedor ";


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
                    "idProveedor" => $idProveedor,
                    "proveedor" => $proveedor,
                    "rfc" => $rfc,
                    "direccion" => $direccion,
                    "telefono" => $telefono,
                    "comentarios" => $comentarios,
                    "activo" => $activo

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



    function obtenerProducto($idProducto)
    {


        $query = "SELECT  * FROM  productos 
                where idProducto=" . $idProducto;


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
                    "producto" => $producto,
                    "idCalibre" => $idCalibre,
                    "idTipo" => $idTipo,
                    "idUnidad" => $idUnidad,
                    "entrada" => $entrada,
                    "salida" => $salida,
                    "idMateriaPrima" => $idMateriaPrima,
                    "largo" => $largo,
                    "idUnidadFactura" => $idUnidadFactura,
                    "precioGen" => $precioGen,
                    "precioRev" => $precioRev,
                    "pesoTeorico" => $pesoTeorico,
                    "activo" => $activo


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



    function obtenerProductoPORsku($sku)
    {


        $query = "SELECT  * FROM  productos 
                where sku='" . $sku . "'";


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
                    "producto" => $producto,
                    "idCalibre" => $idCalibre,
                    "idTipo" => $idTipo,
                    "idUnidad" => $idUnidad,
                    "entrada" => $entrada,
                    "salida" => $salida,
                    "largo" => $largo,
                    "idUnidadFactura" => $idUnidadFactura,
                    "precioGen" => $precioGen,
                    "precioRev" => $precioRev,
                    "pesoTeorico" => $pesoTeorico,
                    "activo" => $activo


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





    function obtenerUnidades()
    {


        $query = "SELECT  *  FROM  unidades   order by unidad";


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
                    "idUnidad" => $idUnidad,
                    "unidad" => $unidad

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



        $respuesta = new RespuestaBD();


        $productos = $this->obtenerProductoPORsku($sku)->registros;
        if (count($productos) > 0) {
            $respuesta->exito = false;
            $respuesta->mensaje = "ya existe un producto con el mismo SKU, favor de validar";
            return $respuesta;
        } else {



            $query = "INSERT INTO
                    productos
                SET
                producto=:producto,idCalibre=:idCalibre,sku=:sku,idAncho=:idAncho,
                precioGen=:precioGen,precioRev=:precioRev,largo=:largo,idUnidadFactura=:idUnidadFactura,
                salida=:chkSalida,entrada=:chkEntrada,medidasreves=:medidas,idMateriaPrima=:idMateriaPrima,
                pesoTeorico=:pesoTeorico,idTipo=:idTipo,idUnidad=:idUnidad,activo='1'";


            // prepare query
            $stmt = $this->conn->prepare($query);

            // sanitize
            $producto = htmlspecialchars(strip_tags($producto));

            if ($medidas) {
                $medidas = 1;
            } else {
                $medidas = 0;
            }

            // bind values
            $stmt->bindParam(":precioRev", $precioRev);
            $stmt->bindParam(":precioGen", $precioGen);
            $stmt->bindParam(":sku", $sku);
            $stmt->bindParam(":producto", $producto);
            $stmt->bindParam(":idCalibre", $idCalibre);
            $stmt->bindParam(":idAncho", $idAncho);

            $stmt->bindParam(":idTipo", $idTipo);
            $stmt->bindParam(":idUnidad", $idUnidad);
            $stmt->bindParam(":largo", $largo);
            $stmt->bindParam(":medidas", $medidas);
            $stmt->bindParam(":idUnidadFactura", $idUnidadFactura);
            $stmt->bindParam(":pesoTeorico", $pesoTeorico);
            $stmt->bindParam(":chkSalida", $chkSalida);
            $stmt->bindParam(":chkEntrada", $chkEntrada);
            $stmt->bindParam(":idMateriaPrima", $idMateriaPrima);




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
    }




    function agregarCliente($cliente, $rfc, $direccion, $representante, $telefono, $mail, $tipoprecio, $comentarios, $idUso, $direccionentrega, $idVendedor)
    {

        $respuesta = new RespuestaBD();


        $query = "INSERT INTO
                    clientes
                SET
                cliente=:cliente,rfc=:rfc,direccion=:direccion,tipoprecio=:tipoprecio,
                telefono=:telefono,mail=:mail,idUso=:idUso,
                comentarios=:comentarios,direccionentrega=:direccionentrega,idVendedor=:idVendedor,
                representante=:representante,activo=1";


        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $cliente = htmlspecialchars(strip_tags($cliente));
        $rfc = htmlspecialchars(strip_tags($rfc));
        $direccion = htmlspecialchars(strip_tags($direccion));
        $representante = htmlspecialchars(strip_tags($representante));
        $telefono = htmlspecialchars(strip_tags($telefono));
        $mail = htmlspecialchars(strip_tags($mail));
        $direccionentrega = htmlspecialchars(strip_tags($direccionentrega));


        // bind values
        $stmt->bindParam(":cliente", $cliente);
        $stmt->bindParam(":rfc", $rfc);
        $stmt->bindParam(":direccion", $direccion);
        $stmt->bindParam(":representante", $representante);
        $stmt->bindParam(":telefono", $telefono);
        $stmt->bindParam(":mail", $mail);
        $stmt->bindParam(":direccionentrega", $direccionentrega);
        $stmt->bindParam(":idUso", $idUso);
        $stmt->bindParam(":tipoprecio", $tipoprecio);
        $stmt->bindParam(":comentarios", $comentarios);
        $stmt->bindParam(":idVendedor", $idVendedor);






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


    function agregarCalibre($calibre)
    {

        $respuesta = new RespuestaBD();


        $query = "INSERT INTO
                    calibres
                SET
                calibre=:calibre,activo=1";


        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $calibre = htmlspecialchars(strip_tags($calibre));



        // bind values
        $stmt->bindParam(":calibre", $calibre);


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


    function agregarTipo($tipo)
    {

        $respuesta = new RespuestaBD();


        $query = "INSERT INTO
                    tipos
                SET
                tipo=:tipo,activo=1";


        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $tipo = htmlspecialchars(strip_tags($tipo));



        // bind values
        $stmt->bindParam(":tipo", $tipo);


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



    function agregarChofer($chofer)
    {

        $respuesta = new RespuestaBD();


        $query = "INSERT INTO
                    choferes
                SET
                chofer=:chofer,activo=1";


        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $chofer = htmlspecialchars(strip_tags($chofer));



        // bind values
        $stmt->bindParam(":chofer", $chofer);


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



    function agregarCamion($camion, $placas)
    {

        $respuesta = new RespuestaBD();


        $query = "INSERT INTO
                    camiones
                SET
                camion=:camion,placas=:placas,activo=1";


        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $camion = htmlspecialchars(strip_tags($camion));
        $placas = htmlspecialchars(strip_tags($placas));



        // bind values
        $stmt->bindParam(":camion", $camion);
        $stmt->bindParam(":placas", $placas);


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


    function agregarLargo($tipo)
    {

        $respuesta = new RespuestaBD();


        $query = "INSERT INTO
                    largos  
                SET
                largo=:largo,activo=1";


        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $tipo = htmlspecialchars(strip_tags($tipo));



        // bind values
        $stmt->bindParam(":largo", $tipo);


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



    function agregarAncho($tipo)
    {

        $respuesta = new RespuestaBD();


        $query = "INSERT INTO
                    anchos  
                SET
                ancho=:ancho,activo=1";


        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $tipo = htmlspecialchars(strip_tags($tipo));



        // bind values
        $stmt->bindParam(":ancho", $tipo);


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



    function agregarProveedor($proveedor, $telefono, $comentarios, $rfc, $direccion)
    {

        $respuesta = new RespuestaBD();


        $query = "INSERT INTO
                    proveedores
                SET
                proveedor=:proveedor,telefono=:telefono,
                comentarios=:comentarios,rfc=:rfc,
                direccion=:direccion,activo=1";


        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $proveedor = htmlspecialchars(strip_tags($proveedor));

        // bind values
        $stmt->bindParam(":proveedor", $proveedor);
        $stmt->bindParam(":telefono", $telefono);
        $stmt->bindParam(":comentarios", $comentarios);
        $stmt->bindParam(":rfc", $rfc);
        $stmt->bindParam(":direccion", $direccion);


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

        $respuesta = new RespuestaBD();



        // check if more than 0 record found


        $query = "UPDATE
                    productos
                SET
                producto=:producto,idCalibre=:idCalibre,
                precioGen=:precioGen,precioRev=:precioRev,idAncho=:idAncho,
                largo=:largo,idUnidadFactura=:idUnidadFactura,
                entrada=:entrada,salida=:salida,medidasreves=:medidasreves,
                idTipo=:idTipo,idUnidad=:idUnidad,pesoTeorico=:pesoTeorico,
                idMateriaPrima=:idMateriaPrima
                 where idProducto=" . $idProducto;




        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $producto = htmlspecialchars(strip_tags($producto));


        $stmt->bindParam(":producto", $producto);
        $stmt->bindParam(":idCalibre", $idCalibre);
        $stmt->bindParam(":idTipo", $idTipo);
        $stmt->bindParam(":idAncho", $idAncho);
        $stmt->bindParam(":idUnidad", $idUnidad);
        $stmt->bindParam(":salida", $salida);
        $stmt->bindParam(":idMateriaPrima", $idMateriaPrima);
        $stmt->bindParam(":entrada", $entrada);
        $stmt->bindParam(":pesoTeorico", $pesoTeorico);
        $stmt->bindParam(":largo", $largo);
        $stmt->bindParam(":idUnidadFactura", $idUnidadFactura);
        $stmt->bindParam(":medidasreves", $medidasreves);





        if ($precioGen == "") {
            $precioGen = "0";
        }
        $stmt->bindParam(":precioGen", $precioGen);
        if ($precioRev == "") {
            $precioRev = "0";
        }
        $stmt->bindParam(":precioRev", $precioRev);


        // execute query
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




    function actualizarCliente(
        $cliente,
        $idCliente,
        $rfc,
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

        $respuesta = new RespuestaBD();

        $query = "UPDATE
                    clientes
                SET
                cliente=:cliente,rfc=:rfc,telefono=:telefono,comentarios=:comentarios,
                idUso=:idUso,
                mail=:mail,tipoprecio=:tipoprecio,direccionentrega=:direccionentrega,idVendedor=:idVendedor,
                direccion=:direccion,representante=:representante where idCliente=" . $idCliente;
        $stmt = $this->conn->prepare($query);



        // sanitize
        $cliente = htmlspecialchars(strip_tags($cliente));
        $rfc = htmlspecialchars(strip_tags($rfc));
        $representante = htmlspecialchars(strip_tags($representante));
        $direccion = htmlspecialchars(strip_tags($direccion));
        $comentarios = htmlspecialchars(strip_tags($comentarios));
        $telefono = htmlspecialchars(strip_tags($telefono));
        $mail = htmlspecialchars(strip_tags($mail));
        $direccionentrega = htmlspecialchars(strip_tags($direccionentrega));





        $stmt->bindParam(":idVendedor", $idVendedor);
        $stmt->bindParam(":comentarios", $comentarios);
        $stmt->bindParam(":idUso", $idUso);
        $stmt->bindParam(":cliente", $cliente);
        $stmt->bindParam(":rfc", $rfc);
        $stmt->bindParam(":representante", $representante);
        $stmt->bindParam(":direccion", $direccion);

        $stmt->bindParam(":telefono", $telefono);
        $stmt->bindParam(":mail", $mail);
        $stmt->bindParam(":tipoprecio", $tipoprecio);
        $stmt->bindParam(":direccionentrega", $direccionentrega);




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


    function actualizarCalibre($calibre, $idCalibre)
    {

        $respuesta = new RespuestaBD();

        $query = "UPDATE
                    calibres
                SET
                calibre=:calibre where idCalibre=" . $idCalibre;
        $stmt = $this->conn->prepare($query);

        // sanitize
        $calibre = htmlspecialchars(strip_tags($calibre));



        $stmt->bindParam(":calibre", $calibre);



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


    function actualizarTipo($tipo, $idTipo)
    {

        $respuesta = new RespuestaBD();

        $query = "UPDATE
                    tipos
                SET
                tipo=:tipo where idTipo=" . $idTipo;
        $stmt = $this->conn->prepare($query);

        // sanitize
        $tipo = htmlspecialchars(strip_tags($tipo));



        $stmt->bindParam(":tipo", $tipo);



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



    function actualizarAncho($ancho, $idAncho)
    {

        $respuesta = new RespuestaBD();

        $query = "UPDATE
                    anchos
                SET
                ancho=:ancho where idAncho=" . $idAncho;
        $stmt = $this->conn->prepare($query);

        // sanitize
        $ancho = htmlspecialchars(strip_tags($ancho));



        $stmt->bindParam(":ancho", $ancho);



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



    function eliminarProducto($idProducto)
    {

        $respuesta = new RespuestaBD();

        $estaUsado = $this->validarProductoUsado($idProducto);

        if (!$estaUsado) {

            $query = "delete from productos where idProducto =" . $idProducto;
            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            $respuesta->exito = true;
            $respuesta->mensaje = "";
            return $respuesta;
        } else {
            $respuesta->exito = false;
            $respuesta->mensaje = "El producto ya esta siendo usado ";
            return $respuesta;
        }
    }



    function actualizarChofer($chofer, $idChofer)
    {

        $respuesta = new RespuestaBD();

        $query = "UPDATE
                    choferes
                SET
                chofer=:chofer where idChofer=" . $idChofer;
        $stmt = $this->conn->prepare($query);

        // sanitize
        $chofer = htmlspecialchars(strip_tags($chofer));



        $stmt->bindParam(":chofer", $chofer);



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



    function actualizarCamion($camion, $placas, $idCamion)
    {

        $respuesta = new RespuestaBD();

        $query = "UPDATE
                    camiones
                SET
                camion=:camion,placas=:placas where idCamion=" . $idCamion;
        $stmt = $this->conn->prepare($query);

        // sanitize
        $camion = htmlspecialchars(strip_tags($camion));
        $placas = htmlspecialchars(strip_tags($placas));



        $stmt->bindParam(":placas", $placas);
        $stmt->bindParam(":camion", $camion);



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



    function actualizarProveedor($proveedor, $idProveedor, $telefono, $comentarios, $rfc, $direccion)
    {

        $respuesta = new RespuestaBD();

        $query = "UPDATE
                    proveedores
                SET
                proveedor=:proveedor, telefono=:telefono, 
                comentarios=:comentarios, rfc=:rfc, direccion=:direccion where idProveedor=" . $idProveedor;
        $stmt = $this->conn->prepare($query);

        // sanitize
        $proveedor = htmlspecialchars(strip_tags($proveedor));



        $stmt->bindParam(":proveedor", $proveedor);
        $stmt->bindParam(":telefono", $telefono);
        $stmt->bindParam(":comentarios", $comentarios);
        $stmt->bindParam(":rfc", $rfc);
        $stmt->bindParam(":direccion", $direccion);



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

    function toggleProveedor($idProveedor, $activo)
    {

        $respuesta = new RespuestaBD();

        // check if more than 0 record found

        // query to insert record
        $query = "UPDATE
                   proveedores
                SET
                    activo=" . $activo . " where idProveedor=" . $idProveedor;

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


    function toggleTipo($idTipo, $activo)
    {

        $respuesta = new RespuestaBD();

        // check if more than 0 record found

        // query to insert record
        $query = "UPDATE
                   tipos
                SET
                    activo=" . $activo . " where idTipo=" . $idTipo;

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



    function toggleCamion($idCamion, $activo)
    {

        $respuesta = new RespuestaBD();

        // check if more than 0 record found

        // query to insert record
        $query = "UPDATE
                   camiones
                SET
                    activo=" . $activo . " where idCamion=" . $idCamion;

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


    function toggleChofer($idChofer, $activo)
    {

        $respuesta = new RespuestaBD();

        // check if more than 0 record found

        // query to insert record
        $query = "UPDATE
                   choferes
                SET
                    activo=" . $activo . " where idChofer=" . $idChofer;

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



    function toggleLargo($idTipo, $activo)
    {

        $respuesta = new RespuestaBD();

        // check if more than 0 record found

        // query to insert record
        $query = "UPDATE
                   largos
                SET
                    activo=" . $activo . " where idLargo=" . $idTipo;

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



    function toggleAncho($idTipo, $activo)
    {

        $respuesta = new RespuestaBD();

        // check if more than 0 record found

        // query to insert record
        $query = "UPDATE
                   anchos
                SET
                    activo=" . $activo . " where idAncho=" . $idTipo;

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


    function toggleCalibre($idCalibre, $activo)
    {

        $respuesta = new RespuestaBD();

        // check if more than 0 record found

        // query to insert record
        $query = "UPDATE
                   calibres
                SET
                    activo=" . $activo . " where idCalibre=" . $idCalibre;

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

    function toggleProducto($idProducto, $activo)
    {

        $respuesta = new RespuestaBD();

        // check if more than 0 record found

        // query to insert record
        $query = "UPDATE
                   productos
                SET
                    activo=" . $activo . " where idProducto=" . $idProducto;

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


    function toggleCliente($idCliente, $activo)
    {

        $respuesta = new RespuestaBD();

        // check if more than 0 record found

        // query to insert record
        $query = "UPDATE
                   clientes
                SET
                    activo=" . $activo . " where idCliente=" . $idCliente;

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
}
