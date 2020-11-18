<?php

include_once 'RespuestaBD.php';



class AdministradorModel
{

    // database connection and table name
    private $conn;
    private $table_name = "usuarios";


    public function __construct($db)
    {
        $this->conn = $db;
    }


    function obtenerAdministradores()
    {


        $query = "SELECT  *  FROM   " . $this->table_name .
            " where tipo='A' order by nombre, apellidos";


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
                    "idUsuario" => $idUsuario,
                    "nombre" => $nombre,
                    "apellidos" => $apellidos,
                    "correo" => $correo,
                    "telefono" => $telefono,
                    "clientes" => $clientes,
                    "proveedores" => $proveedores,
                    "productos" => $productos,
                    "producciones" => $producciones,
                    "ordCompra" => $ordCompra,
                    "creaCot" => $creaCot,
                    "recMat" => $recMat,
                    "calibres" => $calibres,
                    "usuarios" => $usuarios,
                    "tipos" => $tipos,
                    "genRem" => $genRem,
                    "salidaInventario" => $salidaInventario,
                    "eliminaOCompra" => $eliminaOCompra,
                    "devoluciones" => $devoluciones,
                    "cambiarPrecios" => $cambiarPrecios,
                    "eliminaCotizacion" => $eliminaCotizacion,
                    "editarProductos" => $editarProductos,
                    "autorizarPedidos" => $autorizarPedidos,
                    "inventarios" => $inventarios,
                    "verCotizaciones" => $verCotizaciones,
                    "traspasos" => $traspasos,
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


    function obtenerAdministrador($idAdministrador)
    {


        $query = "SELECT  *  FROM   " . $this->table_name .
            " where idUsuario = ".$idAdministrador;


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
                    "idUsuario" => $idUsuario,
                    "nombre" => $nombre,
                    "apellidos" => $apellidos,
                    "correo" => $correo,
                    "telefono" => $telefono,
                    "clientes" => $clientes,
                    "proveedores" => $proveedores,
                    "productos" => $productos,
                    "producciones" => $producciones,
                    "ordCompra" => $ordCompra,
                    "creaCot" => $creaCot,
                    "recMat" => $recMat,
                    "calibres" => $calibres,
                    "usuarios" => $usuarios,
                    "tipos" => $tipos,
                    "genRem" => $genRem,
                    "salidaInventario" => $salidaInventario,
                    "eliminaOCompra" => $eliminaOCompra,
                    "devoluciones" => $devoluciones,
                    "cambiarPrecios" => $cambiarPrecios,
                    "eliminaCotizacion" => $eliminaCotizacion,
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


    function obtenerOrganizadores()
    {


        $query = "SELECT  u.*,e.estado  FROM   " . $this->table_name .
            " u inner join estados e on e.idEstado=u.idEstado 
            where tipo='O' order by nombre, apellidos";


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
                    "idUsuario" => $idUsuario,
                    "nombre" => $nombre,
                    "apellidos" => $apellidos,
                    "organizacion" => $organizacion,
                    "correo" => $correo,
                    "estado" => $estado,
                    "idEstado" => $idEstado,
                    "telefono" => $telefono,
                    "activo" => $activo,

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



    function obtenerValorParametro($nombreParametro)
    {


        $query = "SELECT  valorParametro from parametros where nombreParametro='" . $nombreParametro . "'";


        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        $num = $stmt->rowCount();
        $respuesta = new RespuestaBD();

        if ($num > 0) {

            $valor = "";

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['name'] to
                // just $name only
                extract($row);


                $valor = $valorParametro;
            }
            $respuesta->mensaje = "";
            $respuesta->exito = true;

            $respuesta->valor = $valor;
        } else {
            $respuesta->mensaje = "No se encontraron datos ";
            $respuesta->exito = false;
        }


        return $respuesta;
    }





    function validaExistenciaAdministrador($correo)
    {

        // select all query
        $query = "SELECT  *  FROM   " . $this->table_name . "  where 
        correo = '" . $correo . "' and tipo='A' ";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        $num = $stmt->rowCount();

        $existe = false;

        if ($num > 0) {

            $existe = true;
        }
        return $existe;
    }


    function validaPasswordAnterior($idUsuario, $passanterior)
    {

        // select all query
        $query = "SELECT  *  FROM   usuarios   where 
        idUsuario = " . $idUsuario . " and password='" . $passanterior . "' ";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();
        $num = $stmt->rowCount();

        $existe = false;

        if ($num > 0) {

            $existe = true;
        }
        return $existe;
    }



    function agregarAdministrador(
        $nombre,
        $apellidos,
        $correo,
        $telefono,
        $password,
        $clientes,
        $proveedores,
        $productos,
        $ordCompra,
        $creaCot,
        $recMat,
        $calibres,
        $tipos,
        $producciones,
        $usuarios,
        $eliminaCotizacion,
        $cambiarPrecios,
        $devoluciones,
        $eliminaOCompra,
        $salidaInventario,$editarProductos,$autorizarPedidos,
        $genRem,$inventarios,$verCotizaciones,$traspasos
    ) {

        $respuesta = new RespuestaBD();
        $existe = $this->validaExistenciaAdministrador($correo);
        if ($clientes) {
            $clientes = "1";
        } else {
            $clientes = "0";
        }

        if ($usuarios) {
            $usuarios = "1";
        } else {
            $usuarios = "0";
        }

        if ($proveedores) {
            $proveedores = "1";
        } else {
            $proveedores = "0";
        }
        if ($productos) {
            $productos = "1";
        } else {
            $productos = "0";
        }
        if ($ordCompra) {
            $ordCompra = "1";
        } else {
            $ordCompra = "0";
        }
        if ($creaCot) {
            $creaCot = "1";
        } else {
            $creaCot = "0";
        }
        if ($recMat) {
            $recMat = "1";
        } else {
            $recMat = "0";
        }
        if ($genRem) {
            $genRem = "1";
        } else {
            $genRem = "0";
        }

        if ($calibres) {
            $calibres = "1";
        } else {
            $calibres = "0";
        }

        if ($tipos) {
            $tipos = "1";
        } else {
            $tipos = "0";
        }

        if ($producciones) {
            $producciones = "1";
        } else {
            $producciones = "0";
        }
        if ($cambiarPrecios) {
            $cambiarPrecios = "1";
        } else {
            $cambiarPrecios = "0";
        }
        if ($salidaInventario) {
            $salidaInventario = "1";
        } else {
            $salidaInventario = "0";
        }
        if ($eliminaOCompra) {
            $eliminaOCompra = "1";
        } else {
            $eliminaOCompra = "0";
        }
        if ($devoluciones) {
            $devoluciones = "1";
        } else {
            $devoluciones = "0";
        }
        if ($eliminaCotizacion) {
            $eliminaCotizacion = "1";
        } else {
            $eliminaCotizacion = "0";
        }

        if ($editarProductos) {
            $editarProductos = "1";
        } else {
            $editarProductos = "0";
        }

        if ($autorizarPedidos) {
            $autorizarPedidos = "1";
        } else {
            $autorizarPedidos = "0";
        }

        if ($inventarios) {
            $inventarios = "1";
        } else {
            $inventarios = "0";
        }

        if ($verCotizaciones) {
            $verCotizaciones = "1";
        } else {
            $verCotizaciones = "0";
        }


        if ($traspasos) {
            $traspasos = "1";
        } else {
            $traspasos = "0";
        }



       
        

        // check if more than 0 record found
        if (!$existe) {

            // query to insert record
            $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                nombre=:nombre,apellidos=:apellidos,
                correo=:correo,telefono=:telefono, password=:password,activo=1,
                clientes=:clientes,proveedores=:proveedores,productos=:productos,
                ordCompra=:ordCompra,creaCot=:creaCot,calibres=:calibres,tipos=:tipos,
                eliminaCotizacion=:eliminaCotizacion,devoluciones=:devoluciones,eliminaOCompra=:eliminaOCompra,
                salidaInventario=:salidaInventario,cambiarPrecios=:cambiarPrecios,
                editarProductos=:editarProductos,autorizarPedidos=:autorizarPedidos,
                inventarios=:inventarios,verCotizaciones=:verCotizaciones,traspasos=:traspasos,
                recMat=:recMat,usuarios=:usuarios,producciones=:producciones,genRem=:genRem,                tipo='A'";


                

                
            // prepare query
            $stmt = $this->conn->prepare($query);
            // sanitize
            $nombre = htmlspecialchars(strip_tags($nombre));
            $password = htmlspecialchars(strip_tags($password));
            $apellidos = htmlspecialchars(strip_tags($apellidos));
            $correo = htmlspecialchars(strip_tags($correo));
            $telefono = htmlspecialchars(strip_tags($telefono));
            // bind values
            $stmt->bindParam(":nombre", $nombre);
            $stmt->bindParam(":usuarios", $usuarios);
            $stmt->bindParam(":password", $password);
            $stmt->bindParam(":apellidos", $apellidos);
            $stmt->bindParam(":correo", $correo);
            $stmt->bindParam(":telefono", $telefono);
            $stmt->bindParam(":clientes", $clientes);
            $stmt->bindParam(":proveedores", $proveedores);
            $stmt->bindParam(":productos", $productos);
            $stmt->bindParam(":ordCompra", $ordCompra);
            $stmt->bindParam(":creaCot", $creaCot);
            $stmt->bindParam(":recMat", $recMat);
            $stmt->bindParam(":calibres", $calibres);
            $stmt->bindParam(":tipos", $tipos);
            $stmt->bindParam(":producciones", $producciones);
            $stmt->bindParam(":genRem", $genRem);
            $stmt->bindParam(":eliminaCotizacion", $eliminaCotizacion);
            $stmt->bindParam(":devoluciones", $devoluciones);
            $stmt->bindParam(":eliminaOCompra", $eliminaOCompra);
            $stmt->bindParam(":salidaInventario", $salidaInventario);
            $stmt->bindParam(":cambiarPrecios", $cambiarPrecios);
            $stmt->bindParam(":editarProductos", $editarProductos);
            $stmt->bindParam(":autorizarPedidos", $autorizarPedidos);
            $stmt->bindParam(":inventarios", $inventarios);
            $stmt->bindParam(":verCotizaciones", $verCotizaciones);
            $stmt->bindParam(":traspasos", $traspasos);
            
            


            // execute query
            if ($stmt->execute()) {
                $idInsertado = $this->conn->lastInsertId();
                $respuesta->exito = true;
                $respuesta->mensaje = "";
                $respuesta->valor = $idInsertado;
                return $respuesta;
            }
        } else {
            $respuesta->exito = false;
            $respuesta->mensaje = "Ya existe el administrador";

            return $respuesta;
        }
    }


    function actualizarAdministrador(
        $nombre,
        $apellidos,
        $correo,
        $telefono,
        $idUsuario,
        $clientes,
        $proveedores,
        $productos,
        $ordCompra,
        $creaCot,
        $recMat,
        $calibres,
        $tipos,
        $producciones,
        $usuarios,$eliminaCotizacion,
        $cambiarPrecios,$devoluciones,$eliminaOCompra,$salidaInventario,$editarProductos,$autorizarPedidos,
        $genRem,$inventarios,$verCotizaciones,$traspasos
    ) {

        $respuesta = new RespuestaBD();



        // check if more than 0 record found


        // query to insert record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                nombre=:nombre,apellidos=:apellidos,
                correo=:correo,telefono=:telefono,
                clientes=:clientes,proveedores=:proveedores,productos=:productos,
                eliminaCotizacion=:eliminaCotizacion,cambiarPrecios=:cambiarPrecios,devoluciones=:devoluciones,
                eliminaOCompra=:eliminaOCompra,salidaInventario=:salidaInventario,
                ordCompra=:ordCompra,producciones=:producciones,creaCot=:creaCot,calibres=:calibres,tipos=:tipos,
                recMat=:recMat, inventarios=:inventarios, verCotizaciones=:verCotizaciones, traspasos=:traspasos,
                editarProductos=:editarProductos,autorizarPedidos=:autorizarPedidos,usuarios=:usuarios,genRem=:genRem where idUsuario=:idUsuario";

               
               
                
        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $nombre = htmlspecialchars(strip_tags($nombre));
        $apellidos = htmlspecialchars(strip_tags($apellidos));
        $correo = htmlspecialchars(strip_tags($correo));
        $telefono = htmlspecialchars(strip_tags($telefono));



        // bind values
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":apellidos", $apellidos);
        $stmt->bindParam(":correo", $correo);
        $stmt->bindParam(":telefono", $telefono);
        $stmt->bindParam(":idUsuario", $idUsuario);
        $stmt->bindParam(":clientes", $clientes);
        $stmt->bindParam(":proveedores", $proveedores);
        $stmt->bindParam(":productos", $productos);
        $stmt->bindParam(":calibres", $calibres);
        $stmt->bindParam(":tipos", $tipos);
        $stmt->bindParam(":ordCompra", $ordCompra);
        $stmt->bindParam(":creaCot", $creaCot);
        $stmt->bindParam(":recMat", $recMat);
        $stmt->bindParam(":producciones", $producciones);
        $stmt->bindParam(":usuarios", $usuarios);
        $stmt->bindParam(":genRem", $genRem);
        $stmt->bindParam(":salidaInventario", $salidaInventario);
        $stmt->bindParam(":eliminaOCompra", $eliminaOCompra);
        $stmt->bindParam(":devoluciones", $devoluciones);
        $stmt->bindParam(":cambiarPrecios", $cambiarPrecios);
        $stmt->bindParam(":eliminaCotizacion", $eliminaCotizacion);
        $stmt->bindParam(":editarProductos", $editarProductos);
        $stmt->bindParam(":autorizarPedidos", $autorizarPedidos);
        $stmt->bindParam(":inventarios", $inventarios);
        $stmt->bindParam(":verCotizaciones", $verCotizaciones);
        $stmt->bindParam(":traspasos", $traspasos);

      
        

        // execute query
        if ($stmt->execute()) {
            $respuesta->exito = true;
            $respuesta->mensaje = "";
            return $respuesta;
        } else {
            $respuesta->exito = false;
            $respuesta->mensaje = "Ocurrió un problema actualizando";
            return $respuesta;
        }
    }



    function actualizarParametro($nombreParametro, $valorParametro)
    {

        $respuesta = new RespuestaBD();



        // check if more than 0 record found


        // query to insert record
        $query = "UPDATE
                   parametros
                SET
                valorParametro=:valorParametro where nombreParametro=:nombreParametro";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // bind values
        $stmt->bindParam(":valorParametro", $valorParametro);
        $stmt->bindParam(":nombreParametro", $nombreParametro);

        // execute query
        if ($stmt->execute()) {
            $respuesta->exito = true;
            $respuesta->mensaje = "";
            return $respuesta;
        } else {
            $respuesta->exito = false;
            $respuesta->mensaje = "Ocurrió un problema actualizando";
            return $respuesta;
        }
    }



    function actualizarPassword($idUsuario, $passanterior, $passnuevo)
    {

        $respuesta = new RespuestaBD();

        if ($this->validaPasswordAnterior($idUsuario, $passanterior)) {


            $query = "UPDATE
                    usuarios 
                SET
                password=:password where idUsuario=:idUsuario";

            // prepare query
            $stmt = $this->conn->prepare($query);




            // bind values
            $stmt->bindParam(":password", $passnuevo);

            $stmt->bindParam(":idUsuario", $idUsuario);




            // execute query
            if ($stmt->execute()) {
                $respuesta->exito = true;
                $respuesta->mensaje = "";
                return $respuesta;
            } else {
                $respuesta->exito = false;
                $respuesta->mensaje = "Ocurrió un problema actualizando";
                return $respuesta;
            }
        } else {
            $respuesta->exito = false;
            $respuesta->mensaje = "La contraseña anterior no coincide";
            return $respuesta;
        }
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

        $respuesta = new RespuestaBD();



        // check if more than 0 record found


        // query to insert record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                nombre=:nombre,apellidos=:apellidos,
                correo=:correo,telefono=:telefono,idEstado=:idEstado,organizacion=:organizacion where idUsuario=:idUsuario";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $nombre = htmlspecialchars(strip_tags($nombre));
        $apellidos = htmlspecialchars(strip_tags($apellidos));
        $correo = htmlspecialchars(strip_tags($correo));
        $telefono = htmlspecialchars(strip_tags($telefono));
        $organizacion = htmlspecialchars(strip_tags($organizacion));



        // bind values
        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":apellidos", $apellidos);
        $stmt->bindParam(":correo", $correo);
        $stmt->bindParam(":telefono", $telefono);
        $stmt->bindParam(":idUsuario", $idUsuario);
        $stmt->bindParam(":idEstado", $idEstado);
        $stmt->bindParam(":organizacion", $organizacion);




        // execute query
        if ($stmt->execute()) {
            $respuesta->exito = true;
            $respuesta->mensaje = "";
            return $respuesta;
        } else {
            $respuesta->exito = false;
            $respuesta->mensaje = "Ocurrió un problema actualizando";
            return $respuesta;
        }
    }

    function toggleAdministrador($idAdministrador, $activo)
    {

        $respuesta = new RespuestaBD();

        // check if more than 0 record found

        // query to insert record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    activo=" . $activo . " where idUsuario=" . $idAdministrador;

        // prepare query
        $stmt = $this->conn->prepare($query);




        // execute query
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
