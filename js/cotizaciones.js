$(document).ready(function () {

    //lenarDatosModal



    $(document).on('click', 'a[data-role=cancelarAbono]', function () {
        var id = $(this).data('id');


        $('#idAbonoCancelar').val(id);


        $('#modalCancelarAbono').modal('toggle');
    });



    $('#cancelarAbono').click(function () {

        var id = $('#idAbonoCancelar').val();



        $.ajax({
            url: 'cancelarAbono.php',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function (response) {

                if (response.exito) {

                    window.location = 'index.php?p=pedidos'

                    $('#modalCancelarAbono').modal('hide');
                }
                else {
                    $('#modalMensajeError').find('.modal-body').text(response.mensaje).end().modal('show');

                }

            }
        });


    });


    $(document).on('click', 'a[data-role=abonarPedido]', function () {
        var id = $(this).data('id');


        $('#idCotizacionAbono').val(id);


        $('#modalAbono').modal('toggle');
    });



    $('#realizarAbono').click(function () {

        var id = $('#idCotizacionAbono').val();
        var montoAbono = $('#montoAbono').val();

        var idFormaPago = $('#idFormaPagoAbono').val();

        var fechaAbono = $('#fechaAbono').val();



        if (montoAbono != "" && fechaAbono != "") {

            $.ajax({
                url: 'realizarAbono.php',
                type: 'post',
                data: {
                    id: id,
                    idFormaPago: idFormaPago,
                    fechaAbono:fechaAbono,
                    montoAbono: montoAbono
                },
                dataType: 'json',
                success: function (response) {

                    if (response.exito) {

                        window.location = 'index.php?p=pedidos'

                        $('#modalAbono').modal('hide');
                    }
                    else {
                        $('#modalMensajeError').find('.modal-body').text(response.mensaje).end().modal('show');

                    }

                }
            });
        }
        else {
            $('#modalMensajeError').find('.modal-body').text('El monto debe de ser un valor númerico válido y la fecha debe de ser válida').end().modal('show');
        }



    });




    $(document).on('click', 'a[data-role=eliminaCotizacion]', function () {
        var id = $(this).data('id');


        $('#idCotizacionM').val(id);


        $('#modalEliminaCotizacion').modal('toggle');
    });



    $(document).on('click', 'a[data-role=eliminaRecepcion]', function () {
        var id = $(this).data('id');


        $('#idRecepcionMod').val(id);


        $('#modalEliminaRecepcion').modal('toggle');
    });




    $('#btnEliminarRecepcion').click(function () {

        var id = $('#idRecepcionMod').val();


        $.ajax({
            url: 'eliminarRecepcion.php',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function (response) {

                if (response.exito) {

                    $('#' + id).remove();

                    window.location='index.php?p=materiales'
                }
                else {
                    $('#modalMensajeError').find('.modal-body').text(response.mensaje).end().modal('show');

                }

            }
        });



    });


    $('#btnEliminarCotizacion').click(function () {

        var id = $('#idCotizacionM').val();


        $.ajax({
            url: 'eliminarCotizacion.php',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function (response) {

                if (response.exito) {

                    $('#' + id).remove();

                    $('#modalEliminaCotizacion').modal('hide');
                }
                else {
                    $('#modalMensajeError').find('.modal-body').text(response.mensaje).end().modal('show');

                }

            }
        });



    });


    $('#btnAgregarKit').click(function () {

        var psc = $('#porsobcos').val();
        if (psc === "") {
            psc = 0;
        }
        $('#porcentajeSCKit').val(psc);

        $('#AgregarKitsForm').submit();
    });


    $('#btnAgregarProd').click(function () {

        var psc = $('#porsobcos').val();
        if (psc === "") {
            psc = 0;
        }
        $('#porcentajeSCProd').val(psc);

        $('#ProdsForm').submit();
    });



    $('#btnExtras').click(function () {
        var idCotizacion = $('#idCotizacionModal').val();

        $.ajax({
            url: 'obtenerextras.php',
            type: 'post',
            data: {
                idCotizacion: idCotizacion
            },
            dataType: 'json',
            success: function (response) {


                var observaciones = response.observaciones;
                var condiciones = response.condiciones;
                var vigencia = response.vigencia;
                var formapago = response.idFormaPago;
                var lugarentrega = response.lugarentrega;
                var fechaentrega = response.fechaentrega;

                $('#observacionesM').val(observaciones);
                $('#condicionesM').val(condiciones);
                $('#vigenciaM').val(vigencia);
                $('#lugarM').val(lugarentrega);
                $('#formaM').val(formapago);
                $('#fechaentregaM').val(fechaentrega);
                $('#idCotizacionM').val(idCotizacion);





                $('#modalExtrasUpdate').modal('show');

            }
        });




    });

    $('#costoEnvio').blur(function () {
        var costoEnvio = $('#costoEnvio').val();
        var idCotizacion = $('#idCotizacion').val();

        if (costoEnvio >= 0) {



            $.ajax({
                url: 'actualizarCostoEnvio.php',
                type: 'post',
                data: {
                    costo: costoEnvio,
                    id: idCotizacion
                },
                dataType: 'json',
                success: function (response) {

                    window.location = 'index.php?p=cotizacionesact&idCotizacion=' + idCotizacion;


                }
            });
        }
        else {
            $('#modalMensajeError').find('.modal-body').text('El valor del costo del envío debe de ser numérico, para poderlo almacenar').end().modal('show');
        }
    });


    $('#costoEnvioPed').blur(function () {
        var costoEnvio = $('#costoEnvioPed').val();
        var idCotizacion = $('#idCotizacionMobil').val();

        if (costoEnvio >= 0) {



            $.ajax({
                url: 'actualizarCostoEnvio.php',
                type: 'post',
                data: {
                    costo: costoEnvio,
                    id: idCotizacion
                },
                dataType: 'json',
                success: function (response) {

                    window.location = 'index.php?p=pedidosact&idCotizacion=' + idCotizacion;


                }
            });
        }
        else {
            $('#modalMensajeError').find('.modal-body').text('El valor del costo del envío debe de ser numérico, para poderlo almacenar').end().modal('show');
        }
    });

    $(document).on('click', 'a[data-role=autorizarProduccion]', function () {
        var id = $(this).data('id');

        var valores = id.split("-");

        if (valores[1] == "1") {
            // queire decir que ya esta autorizado y lo quieren cancelar 

            $.ajax({
                url: 'autorizarPedido.php',
                type: 'post',
                data: {
                    id: valores[0],
                    activo: '0',
                    numAlmacen: 0

                },
                dataType: 'json',
                complete: function () {
                    window.location = 'index.php?p=pedidos'
                }
            });
        }
        else {
            // quiere decir que no esta autorizado y tenemos que mostrar el pop up con los almacenes
            $('#idCotizacionAutorizarProduccion').val(valores[0]);
            $('#modalAutorizarProduccion').modal('show');
        }


    });

    $('#autorizarProduccion').click(function () {

        var idCotizacion = $('#idCotizacionAutorizarProduccion').val();
        var numAlmacen = $('#numAlmacenProd').val();


        $.ajax({
            url: 'autorizarPedido.php',
            type: 'post',
            data: {
                id: idCotizacion,
                activo: '1',
                numAlmacen: numAlmacen

            },
            dataType: 'json',
            complete: function () {
                window.location = 'index.php?p=pedidos'
            }
        });



    });


    $('#guardarExtras').click(function () {

        var observaciones = $('#observacionesM').val();
        var condiciones = $('#condicionesM').val();
        var vigencia = $('#vigenciaM').val();
        var forma = $('#formaM').val();
        var lugar = $('#lugarM').val();
        var fechaentrega = $('#fechaentregaM').val();
        var idCotizacion = $('#idCotizacionM').val();

        if (forma == null || forma=='') {
            $('#modalMensajeError').find('.modal-body').text('Seleccione una forma de pago válida').end().modal('show');
        }
        else {

            $.ajax({
                url: 'guardarextras.php',
                type: 'post',
                data: {
                    idCotizacion: idCotizacion,
                    observaciones: observaciones,
                    vigencia: vigencia,
                    condiciones: condiciones,
                    fechaentrega: fechaentrega,
                    lugar: lugar,
                    forma: forma

                },
                dataType: 'json',
                complete: function () {
                    $('#modalMensajeError').find('.modal-body').text('Extras actualizados con éxito').end().modal('show');
                    $('#modalExtrasUpdate').modal('hide');
                }
            });
        }

    });



    $('#btnHacerPedido').click(function () {

        var idCotizacionMobil = $('#idCotizacionMobil').val();
        $('#idCotizacionM').val(idCotizacionMobil);

        $('#modalPedidoUpdate').modal('show');

    });


    $('#generarPedido').click(function () {



        var idCotizacion = $('#idCotizacionM').val();

        $.ajax({
            url: 'generarpedido.php',
            type: 'post',
            data: {
                idCotizacion: idCotizacion
            },
            dataType: 'json',
            complete: function () {
                $('#modalPedidoUpdate').modal('hide');
                window.location.href = 'index.php?p=pedidosact&idCotizacion=' + idCotizacion;
            }
        });

    });



    $(document).on('click', 'a[data-role=cancelarPedido]', function () {
        var id = $(this).data('id');

        $('#idPedidoCancelar').val(id);

        $('#modalCancelarPedido').modal('show');

    });


    $('#cancelarPedido').click(function () {


        var id = $('#idPedidoCancelar').val();



        $.ajax({
            url: 'cancelarPedido.php',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            complete: function () {
                $('#modalCancelarPedido').modal('hide');
                window.location.href = 'index.php?p=pedidos';
            }
        });

    });


    $(document).on('click', 'a[data-role=ponerRojo]', function () {
        var id = $(this).data('id');



        $.ajax({
            url: 'actualizaSemaforo.php',
            type: 'post',
            data: {
                idCotizacion: id,
                color: 'R'
            },
            dataType: 'json',
            complete: function () {
                $('#' + id).children().find('.verde').attr('src', './imagenes/verdeg.jpg');
                $('#' + id).children().find('.amarillo').attr('src', './imagenes/amarillog.jpg');
                $('#' + id).children().find('.rojo').attr('src', './imagenes/rojo.jpg');
            }
        });





    });


    $(document).on('click', 'a[data-role=ponerVerde]', function () {
        var id = $(this).data('id');



        $.ajax({
            url: 'actualizaSemaforo.php',
            type: 'post',
            data: {
                idCotizacion: id,
                color: 'V'
            },
            dataType: 'json',
            complete: function () {
                $('#' + id).children().find('.verde').attr('src', './imagenes/verde.jpg');
                $('#' + id).children().find('.amarillo').attr('src', './imagenes/amarillog.jpg');
                $('#' + id).children().find('.rojo').attr('src', './imagenes/rojog.jpg');
            }
        });





    });


    $(document).on('click', 'a[data-role=ponerAmarillo]', function () {
        var id = $(this).data('id');



        $.ajax({
            url: 'actualizaSemaforo.php',
            type: 'post',
            data: {
                idCotizacion: id,
                color: 'A'
            },
            dataType: 'json',
            complete: function () {
                $('#' + id).children().find('.verde').attr('src', './imagenes/verdeg.jpg');
                $('#' + id).children().find('.amarillo').attr('src', './imagenes/amarillo.jpg');
                $('#' + id).children().find('.rojo').attr('src', './imagenes/rojog.jpg');
            }
        });





    });



    $('#idUnidadCot').on('change', function () {
        ajustarUnidades();
    });



    function ajustarUnidades() {
        var idUnidadSel = $('#idUnidadCot').val();

        var tipoPrecio = $('#tipoPrecio').val();

        var precioGen = $('#precioGen').val();
        var precioRev = $('#precioRev').val();
        var idUnidadProdSel = $("#idUnidadProdSeleccionado").val();

        var pesoTeoricoProductoSel = $("#pesoTeoricoProductoSel").val();



        var precio = 0;

        if (tipoPrecio === "G") {
            precio = precioGen;
        }
        else {
            precio = precioRev;
        }



        if (idUnidadSel == 1 || idUnidadSel == 4) {
            $('#divMetros').hide();

            $("#lblPrecio").text("Precio x Pieza");
            $("#precioUnitarioProd").val(precio);


        }
        else if (idUnidadSel == 3) {
            $('#divMetros').hide();

            $("#lblPrecio").text("Precio UM");

            if (idUnidadProdSel == 2) {
                //si es metro quiere decir que el precio esta en metros
                //entonces tenemos que sacar los kilos del metro
                var precioAjustado = precio / pesoTeoricoProductoSel;

                $("#precioUnitarioProd").val(parseFloat(precioAjustado).toFixed(2));
            }
            else if (idUnidadProdSel == 3) {
                //si es kilo es el mismo
                $("#precioUnitarioProd").val(precio);
            }


        }
        else if (idUnidadSel == 2) {
            $('#divMetros').show();
            $("#lblPrecio").text("Precio Unitario");

            $("#precioUnitarioProd").val(precio);
            var largo = $('#largo').val();
            var valor = parseFloat(largo);

            if (valor > 0) {
                $('#divMetros').hide();
            }


        }
        else {
            $('#divMetros').show();
            $("#lblPrecio").text("Precio Unitario");
        }



    }


});




var miFuncion = null;


$(function () {

    function traerProductoCotizacion(id) {
        var idProducto = id;

        var tipoPrecio = $('#tipoPrecio').val();

        if (idProducto > 0) {
            $.ajax({
                url: 'consultaProd.php',
                method: 'post',
                data: {
                    idProducto: idProducto,

                },
                dataType: 'json',
                success: function (data) {

                    var idUnidad = data.idUnidad;

                    $("#pesoTeoricoProductoSel").val(data.pesoTeorico);
                    $("#precioGen").val(data.precioGen);
                    $("#largo").val(data.largo);
                    $("#precioRev").val(data.precioRev);
                    $("#idUnidadProdSeleccionado").val(data.idUnidad);

                    $('#divMetros').show();

                    var precio = 0;

                    if (tipoPrecio === "G") {
                        precio = data.precioGen;
                    }
                    else {
                        precio = data.precioRev;
                    }



                    $("#idUnidadCot").empty();
                    if (idUnidad == 1) {
                        $("#idUnidadCot").append("<option value='1'>PIEZA</option>");
                        $("#precioUnitarioProd").val(precio);



                    }
                    else if (idUnidad == 2) {
                        // si es metro ponemos kilos y metros
                        $("#idUnidadCot").append("<option value='0'>- Seleccione -</option>");
                        $("#idUnidadCot").append("<option value='2'>METRO</option>");
                        $("#idUnidadCot").append("<option value='3'>KILO</option>");

                    }
                    else if (idUnidad == 3) {
                        // si es KILO ponemos SOLO kilos 
                        $("#idUnidadCot").append("<option value='3'>KILO</option>");
                        $("#precioUnitarioProd").val(precio);
                    }

                    var valor = parseFloat(data.largo);

                    if (valor > 0) {
                        $('#divMetros').hide();
                    }



                }
            });
        }
        else {
            $('#pesoTeoricoF').val('');
        }
    }



    miFuncion = traerProductoCotizacion;
}) 