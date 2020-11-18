$(document).ready(function () {

    //lenarDatosModal

    $(document).on('click', 'a[data-role=updateAdministradores]', function () {
        var id = $(this).data('id');
        var clientes = $('#' + id).children().find('.clientes').val();
        var proveedores = $('#' + id).children().find('.proveedores').val();
        var calibres = $('#' + id).children().find('.calibres').val();
        var tipos = $('#' + id).children().find('.tipos').val();

        var eliminaCotizacion = $('#' + id).children().find('.eliminaCotizacion').val();
        var cambiarPrecios = $('#' + id).children().find('.cambiarPrecios').val();
        var devoluciones = $('#' + id).children().find('.devoluciones').val();
        var eliminaOCompra = $('#' + id).children().find('.eliminaOCompra').val();
        var salidaInventario = $('#' + id).children().find('.salidaInventario').val();

        var inventarios = $('#' + id).children().find('.inventarios').val();
        var verCotizaciones = $('#' + id).children().find('.verCotizaciones').val();
        var traspasos = $('#' + id).children().find('.traspasos').val();

        


        var productos = $('#' + id).children().find('.productos').val();
        var producciones = $('#' + id).children().find('.producciones').val();
        var usuarios = $('#' + id).children().find('.usuarios').val();
        var ordCompra = $('#' + id).children().find('.ordCompra').val();
        var creaCot = $('#' + id).children().find('.creaCot').val();
        var recMat = $('#' + id).children().find('.recMat').val();
        var genRem = $('#' + id).children().find('.genRem').val();
        var nombre = $('#' + id).children('td[data-target=nombre]').text();
        var correo = $('#' + id).children('td[data-target=correo]').text();
        var apellidos = $('#' + id).children('td[data-target=apellidos]').text();
        var telefono = $('#' + id).children('td[data-target=telefono]').text();

        var editarProductos = $('#' + id).children().find('.editarProductos').val();
        var autorizarPedidos = $('#' + id).children().find('.autorizarPedidos').val();


        $('#nombreAdmin').val(nombre);
        $('#apellidosAdmin').val(apellidos);
        $('#correoAdmin').val(correo);
        $('#telefonoAdmin').val(telefono);
        $('#idAdmin').val(id);
        if (clientes === "1") {
            $('#clientesM').prop("checked", true);
        }
        else {
            $('#clientesM').prop("checked", false);
        }

        if (usuarios === "1") {
            $('#usuariosM').prop("checked", true);
        }
        else {
            $('#usuariosM').prop("checked", false);
        }
        if (proveedores === "1") {
            $('#proveedoresM').prop("checked", true);
        }
        else {
            $('#proveedoresM').prop("checked", false);
        }

        if (producciones === "1") {
            $('#produccionesM').prop("checked", true);
        }
        else {
            $('#produccionesM').prop("checked", false);
        }

        if (calibres === "1") {
            $('#calibresM').prop("checked", true);
        }
        else {
            $('#calibresM').prop("checked", false);
        }

        if (tipos === "1") {
            $('#tiposM').prop("checked", true);
        }
        else {
            $('#tiposM').prop("checked", false);
        }

        if (productos === "1") {
            $('#productosM').prop("checked", true);
        }
        else {
            $('#productosM').prop("checked", false);
        }

        if (editarProductos === "1") {
            $('#editarProductosM').prop("checked", true);
        }
        else {
            $('#editarProductosM').prop("checked", false);
        }

        if (autorizarPedidos === "1") {
            $('#autorizarPedidosM').prop("checked", true);
        }
        else {
            $('#autorizarPedidosM').prop("checked", false);
        }




        if (ordCompra === "1") {
            $('#ordCompraM').prop("checked", true);
        }
        else {
            $('#ordCompraM').prop("checked", false);
        }
        if (creaCot === "1") {
            $('#creaCotM').prop("checked", true);
        }
        else {
            $('#creaCotM').prop("checked", false);
        }
        if (recMat === "1") {
            $('#recMatM').prop("checked", true);
        }
        else {
            $('#recMatM').prop("checked", false);
        }
        if (genRem === "1") {
            $('#genRemM').prop("checked", true);
        }
        else {
            $('#genRemM').prop("checked", false);
        }
        if (salidaInventario === "1") {
            $('#salidaInventarioM').prop("checked", true);
        }
        else {
            $('#salidaInventarioM').prop("checked", false);
        }
        if (eliminaOCompra === "1") {
            $('#eliminaOCompraM').prop("checked", true);
        }
        else {
            $('#eliminaOCompraM').prop("checked", false);
        }
        if (devoluciones === "1") {
            $('#devolucionesM').prop("checked", true);
        }
        else {
            $('#devolucionesM').prop("checked", false);
        }
        if (cambiarPrecios === "1") {
            $('#cambiarPreciosM').prop("checked", true);
        }
        else {
            $('#cambiarPreciosM').prop("checked", false);
        }
        if (eliminaCotizacion === "1") {
            $('#eliminaCotizacionM').prop("checked", true);
        }
        else {
            $('#eliminaCotizacionM').prop("checked", false);
        }

        if (traspasos === "1") {
            $('#traspasosM').prop("checked", true);
        }
        else {
            $('#traspasosM').prop("checked", false);
        }

        if (verCotizaciones === "1") {
            $('#verCotizacionesM').prop("checked", true);
        }
        else {
            $('#verCotizacionesM').prop("checked", false);
        }


        if (inventarios === "1") {
            $('#inventariosM').prop("checked", true);
        }
        else {
            $('#inventariosM').prop("checked", false);
        }


        

        $('#modalAdministradoresUpdate').modal('toggle');
    });


    $('#guardarAdmin').click(function () {

        var idAdmin = $('#idAdmin').val();
        var correoAdmin = $('#correoAdmin').val();
        var nombreAdmin = $('#nombreAdmin').val();
        var apellidosAdmin = $('#apellidosAdmin').val();
        var telefonoAdmin = $('#telefonoAdmin').val();
        var genRem = 0;
        if ($('#genRemM').prop("checked") == true) {
            genRem = 1;
        }
        var recMat = 0;
        if ($('#recMatM').prop("checked") == true) {
            recMat = 1;
        }
        var creaCot = 0;
        if ($('#creaCotM').prop("checked") == true) {
            creaCot = 1;
        }
        var ordCompra = 0;
        if ($('#ordCompraM').prop("checked") == true) {
            ordCompra = 1;
        }
        var producciones = 0;
        if ($('#produccionesM').prop("checked") == true) {
            producciones = 1;
        }
        var productos = 0;
        if ($('#productosM').prop("checked") == true) {
            productos = 1;
        }
        var proveedores = 0;
        if ($('#proveedoresM').prop("checked") == true) {
            proveedores = 1;
        }
        var clientes = 0;
        if ($('#clientesM').prop("checked") == true) {
            clientes = 1;
        }
        var usuarios = 0;
        if ($('#usuariosM').prop("checked") == true) {
            usuarios = 1;
        }
        var calibres = 0;
        if ($('#calibresM').prop("checked") == true) {
            calibres = 1;
        }

        var autorizarPedidos = 0;
        if ($('#autorizarPedidosM').prop("checked") == true) {
            autorizarPedidos = 1;
        }

        var editarProductos = 0;
        if ($('#editarProductosM').prop("checked") == true) {
            editarProductos = 1;
        }





        var tipos = 0;
        if ($('#tiposM').prop("checked") == true) {
            tipos = 1;
        }
        var salidaInventario = 0;
        if ($('#salidaInventarioM').prop("checked") == true) {
            salidaInventario = 1;
        }
        var eliminaOCompra = 0;
        if ($('#eliminaOCompraM').prop("checked") == true) {
            eliminaOCompra = 1;
        }
        var devoluciones = 0;
        if ($('#devolucionesM').prop("checked") == true) {
            devoluciones = 1;
        }
        var cambiarPrecios = 0;
        if ($('#cambiarPreciosM').prop("checked") == true) {
            cambiarPrecios = 1;
        }
        var eliminaCotizacion = 0;
        if ($('#eliminaCotizacionM').prop("checked") == true) {
            eliminaCotizacion = 1;
        }

        var inventarios = 0;
        if ($('#inventariosM').prop("checked") == true) {
            inventarios = 1;
        }

        var verCotizaciones = 0;
        if ($('#verCotizacionesM').prop("checked") == true) {
            verCotizaciones = 1;
        }

        var traspasos = 0;
        if ($('#traspasosM').prop("checked") == true) {
            traspasos = 1;
        }


        


        if (correoAdmin === "" || nombreAdmin === "" || apellidosAdmin === "" || telefonoAdmin === "") {
            $('#modalMensajeError').find('.modal-body').text('Los campos son obligatorios').end().modal('show');
        } else {

            $.ajax({
                url: 'administradorescrud.php',
                method: 'post',
                data: {
                    idAdmin: idAdmin,
                    correoAdmin: correoAdmin,
                    nombreAdmin: nombreAdmin,
                    apellidosAdmin: apellidosAdmin,
                    telefonoAdmin: telefonoAdmin,
                    genRem: genRem,
                    recMat: recMat,
                    creaCot: creaCot,
                    ordCompra: ordCompra,
                    productos: productos,
                    calibres: calibres,
                    tipos: tipos,
                    proveedores: proveedores,
                    clientes: clientes,
                    producciones: producciones,
                    usuarios: usuarios,
                    salidaInventario: salidaInventario,
                    eliminaOCompra: eliminaOCompra,
                    devoluciones: devoluciones,
                    cambiarPrecios: cambiarPrecios,
                    editarProductos: editarProductos,
                    autorizarPedidos: autorizarPedidos,
                    eliminaCotizacion: eliminaCotizacion,
                    inventarios: inventarios,
                    verCotizaciones: verCotizaciones,
                    traspasos: traspasos,
                    tipo: 'update'
                    

                },
                success: function (data) {
                    //aqui recibes el json y haces lo que quieras con el


                    $('#' + idAdmin).children().find('.eliminaCotizacion').val(eliminaCotizacion);
                    $('#' + idAdmin).children().find('.cambiarPrecios').val(cambiarPrecios);
                    $('#' + idAdmin).children().find('.devoluciones').val(devoluciones);
                    $('#' + idAdmin).children().find('.eliminaOCompra').val(eliminaOCompra);
                    $('#' + idAdmin).children().find('.salidaInventario').val(salidaInventario);

                    $('#' + idAdmin).children().find('.ordCompra').val(ordCompra);
                    $('#' + idAdmin).children().find('.genRem').val(genRem);
                    $('#' + idAdmin).children().find('.creaCot').val(creaCot);
                    $('#' + idAdmin).children().find('.recMat').val(recMat);
                    $('#' + idAdmin).children().find('.productos').val(productos);
                    $('#' + idAdmin).children().find('.proveedores').val(proveedores);
                    $('#' + idAdmin).children().find('.producciones').val(producciones);
                    $('#' + idAdmin).children().find('.usuarios').val(usuarios);
                    $('#' + idAdmin).children().find('.calibres').val(calibres);
                    $('#' + idAdmin).children().find('.editarProductos').val(editarProductos);
                    $('#' + idAdmin).children().find('.autorizarPedidos').val(autorizarPedidos);

                    $('#' + idAdmin).children().find('.inventarios').val(inventarios);
                    $('#' + idAdmin).children().find('.verCotizaciones').val(verCotizaciones);
                    $('#' + idAdmin).children().find('.traspasos').val(traspasos);


                    

                    $('#' + idAdmin).children().find('.tipos').val(tipos);
                    $('#' + idAdmin).children().find('.clientes').val(clientes);
                    $('#' + idAdmin).children('td[data-target=correo]').text(correoAdmin);
                    $('#' + idAdmin).children('td[data-target=nombre]').text(nombreAdmin);
                    $('#' + idAdmin).children('td[data-target=telefono]').text(telefonoAdmin);
                    $('#' + idAdmin).children('td[data-target=apellidos]').text(apellidosAdmin);
                    $('#modalAdministradoresUpdate').modal('toggle');
                }
            });
        }

    });







    $(document).on('click', 'a[data-role=updateClientes]', function () {
        var id = $(this).data('id');
        var cliente = $('#' + id).children('td[data-target=cliente]').text();
        var rfc = $('#' + id).children('td[data-target=rfc]').text();
        var direccion = $('#' + id).children('td[data-target=direccion]').text();
        var representante = $('#' + id).children('td[data-target=representante]').text();
        var telefono = $('#' + id).children('td[data-target=telefono]').text();
        var mail = $('#' + id).children('td[data-target=mail]').text();
        var direccionentrega = $('#' + id).children('td[data-target=direccionentrega]').text();

        var tipoprecio = $('#' + id).children('td[data-target=precio]').text();

        var comentarios = $('#' + id).children().find('.comentarios').val();
        var idUso = $('#' + id).children().find('.idUso').val();

        $('#clienteM').val(cliente);
        $('#rfcM').val(rfc);
        $('#representanteM').val(representante);
        $('#direccionM').val(direccion);
        $('#rfcM').val(rfc);
        $('#idCliente').val(id);
        $('#telefonoM').val(telefono);
        $('#mailM').val(mail);
        $('#comentariosM').val(comentarios);
        $('#idUsoM').val(idUso);
        $('#direccionentregaM').val(direccionentrega);

        if (tipoprecio === "General") {
            $('#tipoprecioM').val('G');
        }
        else {
            $('#tipoprecioM').val('R');
        }








        $('#modalClientesUpdate').modal('toggle');
    });



    $('#guardarCliente').click(function () {

        var idCliente = $('#idCliente').val();
        var cliente = $('#clienteM').val();
        var rfc = $('#rfcM').val();
        var direccion = $('#direccionM').val();
        var representante = $('#representanteM').val();

        var telefono = $('#telefonoM').val();
        var comentarios = $('#comentariosM').val();
        var idUso = $('#idUsoM').val();
        var mail = $('#mailM').val();
        var direccionentrega = $('#direccionentregaM').val();
        var usoText = $('#idUsoM option:selected').html();
        var tipoprecio = $('#tipoprecioM').val();



        if (cliente === "" || rfc === "" || direccion === "" || representante === ""
            || direccionentrega === "" || telefono === "" || mail === "") {
            $('#modalMensajeError').find('.modal-body').text('Los campos son obligatorios').end().modal('show');
        } else {




            $.ajax({
                url: 'clientescrud.php',
                method: 'post',
                data: {
                    idCliente: idCliente,
                    cliente: cliente,
                    direccion: direccion,
                    telefono: telefono,
                    mail: mail,
                    rfc: rfc,
                    idUso: idUso,
                    tipoprecio: tipoprecio,
                    comentarios: comentarios,
                    direccion: direccion,
                    direccionentrega: direccionentrega,
                    representante: representante,
                    tipo: 'update'
                },
                success: function (data) {

                    $('#' + idCliente).children('td[data-target=cliente]').text(cliente);
                    $('#' + idCliente).children('td[data-target=rfc]').text(rfc);
                    $('#' + idCliente).children('td[data-target=representante]').text(representante);
                    $('#' + idCliente).children('td[data-target=direccion]').text(direccion);

                    $('#' + idCliente).children('td[data-target=mail]').text(mail);
                    $('#' + idCliente).children('td[data-target=telefono]').text(telefono);
                    $('#' + idCliente).children('td[data-target=uso]').text(usoText);
                    $('#' + idCliente).children('td[data-target=direccionentrega]').text(direccionentrega);
                    $('#' + idCliente).children().find('.comentarios').val(comentarios);
                    var tipoPrecioS = "Revendedor";
                    if (tipoprecio === "G") {
                        tipoPrecioS = "General";
                    }
                    $('#' + idCliente).children('td[data-target=precio]').text(tipoPrecioS);




                    $('#modalClientesUpdate').modal('toggle');
                }
            });
        }

    });



    $(document).on('click', 'a[data-role=updateProveedores]', function () {
        var id = $(this).data('id');
        var proveedor = $('#' + id).children('td[data-target=proveedor]').text();
        var rfc = $('#' + id).children('td[data-target=rfc]').text();
        var direccion = $('#' + id).children('td[data-target=direccion]').text();
        var telefono = $('#' + id).children('td[data-target=telefono]').text();
        var comentarios = $('#' + id).children('td[data-target=comentarios]').text();


        $('#proveedorM').val(proveedor);
        $('#telefonoPM').val(telefono);
        $('#direccionPM').val(direccion);
        $('#rfcPM').val(rfc);
        $('#comentariosPM').val(comentarios);

        $('#idProveedor').val(id);

        $('#modalProveedoresUpdate').modal('toggle');
    });



    $('#guardarProveedor').click(function () {

        var idProveedor = $('#idProveedor').val();
        var proveedor = $('#proveedorM').val();
        var telefono = $('#telefonoPM').val();
        var direccion = $('#direccionPM').val();
        var rfc = $('#rfcPM').val();
        var comentarios = $('#comentariosPM').val();





        if (proveedor === "") {
            $('#modalMensajeError').find('.modal-body').text('Los campos son obligatorios').end().modal('show');
        } else {




            $.ajax({
                url: 'proveedorescrud.php',
                method: 'post',
                data: {
                    idProveedor: idProveedor,
                    proveedor: proveedor,
                    telefono: telefono,
                    direccion: direccion,
                    rfc: rfc,
                    comentarios: comentarios,
                    tipo: 'update'
                },
                success: function (data) {

                    $('#' + idProveedor).children('td[data-target=proveedor]').text(proveedor);
                    $('#' + idProveedor).children('td[data-target=telefono]').text(telefono);
                    $('#' + idProveedor).children('td[data-target=direccion]').text(direccion);
                    $('#' + idProveedor).children('td[data-target=rfc]').text(rfc);
                    $('#' + idProveedor).children('td[data-target=comentarios]').text(comentarios);

                    $('#modalProveedoresUpdate').modal('toggle');
                }
            });
        }

    });




    $(document).on('click', 'a[data-role=updateProductos]', function () {
        var id = $(this).data('id');
        var producto = $('#TR' + id).children('td[data-target=producto]').text();
        var sku = $('#TR' + id).children('td[data-target=sku]').text();

        var pesoTeorico = $('#TR' + id).children('td[data-target=pesoTeorico]').text();
        var precioGen = $('#TR' + id).children('td[data-target=precioGen]').text();
        var precioRev = $('#TR' + id).children('td[data-target=precioRev]').text();
        var largo = $('#TR' + id).children('td[data-target=largo]').text();


        var idCalibre = $('#TR' + id).children().find('.idCalibre').val();
        var idAncho = $('#TR' + id).children().find('.idAncho').val();
        var idTipo = $('#TR' + id).children().find('.idTipo').val();
        var entrada = $('#TR' + id).children().find('.entrada').val();
        var salida = $('#TR' + id).children().find('.salida').val();
        var idUnidad = $('#TR' + id).children().find('.idUnidad').val();
        var idUnidadFactura = $('#TR' + id).children().find('.idUnidadFactura').val();
        var cambiarPrecios = $('#cambiarPrecios').val();
        var editarProductos = $('#editarProductos').val();

        if (cambiarPrecios != 1) {
            $('#precioGenM').prop('readonly', true);
            $('#precioRevM').prop('readonly', true);
        }

        if (cambiarPrecios != 1) {
            $('#guardarProducto').hide();
        }
        else {
            $('#guardarProducto').show();
        }



        $('#productoM').val(producto);

        $('#productosku').text(sku);

        $('#idProducto').val(id);
        $('#idUnidadM').val(idUnidad);
        $('#idUnidadFacturaM').val(idUnidadFactura);

        $('#idTipoM').val(idTipo);
        $('#idCalibreM').val(idCalibre);
        $('#idAnchoM').val(idAncho);
        $('#pesoTeoricoM').val(pesoTeorico);
        $('#largoM').val(largo);
        if (entrada === "1") {
            $('#chkEntradaM').prop("checked", true);
        }

        if (salida === "1") {
            $('#chkSalidaM').prop("checked", true);
        }

        $('#precioRevM').val(precioRev);
        $('#precioGenM').val(precioGen);

        if (idUnidad == 2 || idUnidad == 1) {
            $('#formFactorM').show();
        }
        else {
            $('#formFactorM').hide();
        }

        $('#modalProductosUpdate').modal('toggle');
    });



    $('#guardarProducto').click(function () {

        var idProducto = $('#idProducto').val();
        var producto = $('#productoM').val();
        var pesoTeorico = $('#pesoTeoricoM').val();
        var idCalibre = $('#idCalibreM').val();
        var idTipo = $('#idTipoM').val();
        var idUnidad = $('#idUnidadM').val();
        var idUnidadFactura = $('#idUnidadFacturaM').val();
        var idAncho = $('#idAnchoM').val();
        var precioRev = $('#precioRevM').val();
        var precioGen = $('#precioGenM').val();
        var largo = $('#largoM').val();

        var entrada = 0;
        if ($('#chkEntradaM').prop("checked") == true) {
            entrada = 1;
        }

        var salida = 0;
        if ($('#chkSalidaM').prop("checked") == true) {
            salida = 1;
        }



        var calibreText = $('#idCalibreM option:selected').html();
        var tipoText = $('#idTipoM option:selected').html();
        var unidadText = $('#idUnidadM option:selected').html();
        var unidadFacturaText = $('#idUnidadFacturaM option:selected').html();
        var anchoText = $('#idAnchoM option:selected').html();


        if (salida == 0 && entrada == 0) {
            $('#modalMensajeError').find('.modal-body').text('Debes asignar el producto a entrada, a salida o a ambos').end().modal('show');
        }
        else {

            if (idUnidad == 2 && (pesoTeorico === "" || pesoTeorico <= 0)) {
                $('#modalMensajeError').find('.modal-body').text('Si el producto es en metros, debes agregar un peso teórico').end().modal('show');
            }
            else {

                if (producto === "") {
                    $('#modalMensajeError').find('.modal-body').text('Los campos son obligatorios').end().modal('show');
                } else {




                    $.ajax({
                        url: 'productoscrud.php',
                        method: 'post',
                        data: {
                            idProducto: idProducto,
                            idCalibre: idCalibre,
                            idTipo: idTipo,
                            idUnidad: idUnidad,
                            idAncho: idAncho,
                            entrada: entrada,
                            salida: salida,
                            pesoTeorico: pesoTeorico,
                            producto: producto,
                            precioGen: precioGen,
                            largo: largo,
                            idUnidadFactura: idUnidadFactura,
                            precioRev: precioRev,
                            tipo: 'update'
                        },
                        success: function (data) {

                            $('#TR' + idProducto).children('td[data-target=producto]').text(producto);
                            $('#TR' + idProducto).children('td[data-target=precioGen]').text(precioGen);
                            $('#TR' + idProducto).children('td[data-target=precioRev]').text(precioRev);
                            $('#TR' + idProducto).children('td[data-target=largo]').text(largo);
                            $('#TR' + idProducto).children('td[data-target=calibre]').text(calibreText);
                            $('#TR' + idProducto).children('td[data-target=ancho]').text(anchoText);
                            $('#TR' + idProducto).children('td[data-target=tipo]').text(tipoText);
                            $('#TR' + idProducto).children('td[data-target=unidad]').text(unidadText);
                            $('#TR' + idProducto).children('td[data-target=unidadFactura]').text(unidadFacturaText);

                            $('#TR' + idProducto).children('td[data-target=pesoTeorico]').text(pesoTeorico);
                            $('#TR' + idProducto).children().find('.idTipo').val(idTipo);
                            $('#TR' + idProducto).children().find('.idCalibre').val(idCalibre);
                            $('#TR' + idProducto).children().find('.idAncho').val(idAncho);
                            $('#TR' + idProducto).children().find('.entrada').val(entrada);
                            $('#TR' + idProducto).children().find('.salida').val(salida);
                            $('#TR' + idProducto).children().find('.idUnidad').val(idUnidad);
                            $('#TR' + idProducto).children().find('.idUnidadFactura').val(idUnidadFactura);
                            if (entrada == 1) {
                                $('#TR' + idProducto).children().find('.imgEntrada').css("display", "block");
                            }
                            else {
                                $('#TR' + idProducto).children().find('.imgEntrada').css("display", "none");
                            }


                            if (salida == 1) {
                                $('#TR' + idProducto).children().find('.imgSalida').css("display", "block");
                            }
                            else {
                                $('#TR' + idProducto).children().find('.imgSalida').css("display", "none");
                            }





                            $('#modalProductosUpdate').modal('toggle');
                        }
                    });
                }
            }
        }

    });






    $(document).on('click', 'a[data-role=updateCalibres]', function () {
        var id = $(this).data('id');
        var calibre = $('#' + id).children('td[data-target=calibre]').text();


        $('#calibreM').val(calibre);

        $('#idCalibre').val(id);

        $('#modalCalibresUpdate').modal('toggle');
    });



    $('#guardarCalibre').click(function () {

        var idCalibre = $('#idCalibre').val();
        var calibre = $('#calibreM').val();




        if (calibre === "") {
            $('#modalMensajeError').find('.modal-body').text('Los campos son obligatorios').end().modal('show');
        } else {




            $.ajax({
                url: 'calibrecrud.php',
                method: 'post',
                data: {
                    idCalibre: idCalibre,
                    calibre: calibre,

                    tipo: 'update'
                },
                success: function (data) {

                    $('#' + idCalibre).children('td[data-target=calibre]').text(calibre);



                    $('#modalCalibresUpdate').modal('toggle');
                }
            });
        }

    });





    $(document).on('click', 'a[data-role=updateTipos]', function () {
        var id = $(this).data('id');
        var tipo = $('#' + id).children('td[data-target=tipo]').text();


        $('#tipoM').val(tipo);

        $('#idTipo').val(id);

        $('#modalTiposUpdate').modal('toggle');
    });



    $('#guardarTipo').click(function () {

        var idTipo = $('#idTipo').val();
        var tipoM = $('#tipoM').val();




        if (tipoM === "") {
            $('#modalMensajeError').find('.modal-body').text('Los campos son obligatorios').end().modal('show');
        } else {




            $.ajax({
                url: 'tipocrud.php',
                method: 'post',
                data: {
                    idTipo: idTipo,
                    tipoM: tipoM,

                    tipo: 'update'
                },
                success: function (data) {

                    $('#' + idTipo).children('td[data-target=tipo]').text(tipoM);



                    $('#modalTiposUpdate').modal('toggle');
                }
            });
        }

    });


    $(document).on('click', 'a[data-role=updateAnchos]', function () {
        var id = $(this).data('id');
        var ancho = $('#' + id).children('td[data-target=ancho]').text();


        $('#anchoM').val(ancho);

        $('#idAncho').val(id);

        $('#modalAnchosUpdate').modal('toggle');
    });


    $('#guardarAncho').click(function () {

        var idAncho = $('#idAncho').val();
        var anchoM = $('#anchoM').val();




        if (anchoM === "") {
            $('#modalMensajeError').find('.modal-body').text('Los campos son obligatorios').end().modal('show');
        } else {




            $.ajax({
                url: 'anchocrud.php',
                method: 'post',
                data: {
                    idAncho: idAncho,
                    anchoM: anchoM
                },
                success: function (data) {

                    $('#' + idAncho).children('td[data-target=ancho]').text(anchoM);



                    $('#modalAnchosUpdate').modal('toggle');
                }
            });
        }

    });


    $('#idUnidad').on('change', function () {
        if (this.value == 2 || this.value == 1) {
            $('#formFactor').show();
        }
        else {
            $('#formFactor').hide();
        }
    });


    $('#idUnidadM').on('change', function () {
        if (this.value == 2 || this.value == 1) {
            $('#formFactorM').show();
        }
        else {
            $('#formFactorM').hide();
        }
    });



});