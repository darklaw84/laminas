$(document).ready(function () {

    $(document).on('click', 'a[data-role=llenarProduccion]', function () {
        var id = $(this).data('id');
        var producto = $('#' + id).children('td[data-target=producto]').text();
        var idProducto = $('#' + id).children().find('.idProducto').val();
        var unidad = $('#' + id).children().find('.unidad').val();
        var metros = $('#' + id).children().find('.metros').val();
        var idUnidad = $('#' + id).children().find('.idUnidad').val();
        var cantidad = $('#' + id).children().find('.cantidad').val();
        var cantidadProcesada = $('#' + id).children().find('.cantidadProcesada').val();
        var pesoTeorico = $('#' + id).children().find('.pesoTeorico').val();
        var idProducto = $('#' + id).children().find('.idProducto').val();
        $('#lblProducto').text('Producto Generado: ' + producto);
        $('#idCotizacionDetM').val(id);
        $('#idProductoDet').val(idProducto);
        $('#pesoTeoricoProd').val(pesoTeorico);
        $('#idUnidadProd').val(idUnidad);
        $('#metrosProd').val(metros);
        if (idUnidad == 1) {
            $('#lblCantMetros').text('Piezas Generadas');
        }
        else if (idUnidad == 2) {
            $('#lblCantMetros').text('Piezas Generadas');
        }
        else if (idUnidad == 3) {
            $('#lblCantMetros').text('Kilos Generados');
        }

        $('#lblProcesado').text('Se ha generado ' + cantidadProcesada + ' de ' + cantidad + ' requeridos');
        if (pesoTeorico != "") {
            if (metros == 0) {
                metros = 1;
            }
            var restanteKilos = (cantidad - cantidadProcesada) * metros * pesoTeorico;
            $('#lblPesoTeorico').text('el Restante de kilos(Teórico) es: ' + restanteKilos.toFixed(2));
        }



        $('#modalGenerarProduccion').modal('toggle');
    });





    $('#modalGenerarProduccion').on('shown.bs.modal', function () {
        $('#codigoBarrasProd').focus();
    })



    $('#generarMateria').click(function () {
        var idUnidad = $('#idUnidadProd').val();
        var metros = $('#metrosProd').val();
        var idCotizacionDetM = $('#idCotizacionDetM').val();
        var utilizadosM = $('#utilizadosM').val();
        var utilizadosUsM = $('#utilizadosUsM').val();
        var idProducto = $('#idProductoDet').val();
        var idAlmacen = $('#idAlmacenProd').val();
        var pesoTeorico = $('#pesoTeoricoProd').val();
        var codigo = $('#codigoBarrasProd').val();


        if (codigo.includes("D")) {
            codigo = codigo.replace('D', '');

            var kilos = 0;

            if (idUnidad == 2)//metro
            {
                kilos = pesoTeorico * metros * utilizadosM;
            }
            else if (idUnidad == 3) //kilos
            {
                kilos = utilizadosM;
            }
            else {
                kilos = utilizadosM;
            }

            //si es una devolucion 

            $.ajax({
                url: 'generarMateriaDevolucion.php',
                type: 'post',
                data: {
                    idCotizacionDetM: idCotizacionDetM,
                    kilos: kilos,
                    idProducto: idProducto,
                    codigo: codigo,
                    utilizadosUsM: utilizadosUsM,
                    idAlmacen: idAlmacen,
                    utilizadosM: utilizadosM
                },
                dataType: 'json',
                success: function (response) {

                    if (response.exito) {

                        $('#modalProducirMateria').modal('hide');

                        window.location = 'index.php?p=producciones'
                    }
                    else {
                        $('#modalMensajeError').find('.modal-body').text(response.mensaje).end().modal('show');
                    }



                },
                error: function (request, status, error) {
                    alert(request.responseText);
                }
            });


        }
        else if (codigo.includes("R")) {

            codigo = codigo.replace('R', '');


            if (idAlmacen == 0) {
                $('#modalMensajeError').find('.modal-body').text('Seleccione un almacen').end().modal('show');
            }
            else {

                if (utilizadosM <= 0) {
                    $('#modalMensajeError').find('.modal-body').text('El valor debe de ser mayor a 0').end().modal('show');
                }
                else {


                    var kilos = 0;

                    if (idUnidad == 2)//metro
                    {
                        kilos = pesoTeorico * metros * utilizadosM;
                    }
                    else if (idUnidad == 3) //kilos
                    {
                        kilos = utilizadosM;
                    }
                    else {
                        kilos = utilizadosM;
                    }


                    if (idUnidad == 1) {
                        $.ajax({
                            url: 'generarMateria.php',
                            type: 'post',
                            data: {
                                idCotizacionDetM: idCotizacionDetM,
                                kilos: kilos,
                                idProducto: idProducto,
                                codigo: codigo,
                                utilizadosUsM: utilizadosUsM,
                                idAlmacen: idAlmacen,
                                utilizadosM: utilizadosM
                            },
                            dataType: 'json',
                            success: function (response) {

                                if (response.exito) {

                                    $('#modalProducirMateria').modal('hide');

                                    window.location = 'index.php?p=producciones'
                                }
                                else {
                                    $('#modalMensajeError').find('.modal-body').text(response.mensaje).end().modal('show');
                                }



                            },
                            error: function (request, status, error) {
                                alert(request.responseText);
                            }
                        });
                    }
                    else {




                        $.ajax({
                            url: 'validaCodigo.php',
                            type: 'post',
                            data: {
                                codigo: codigo,
                                idProducto: idProducto,
                                utilizadosM: kilos
                            },
                            dataType: 'json',
                            success: function (response) {



                                if (response.exito) {
                                    $.ajax({
                                        url: 'generarMateria.php',
                                        type: 'post',
                                        data: {
                                            idCotizacionDetM: idCotizacionDetM,
                                            kilos: kilos,
                                            idProducto: idProducto,
                                            codigo: codigo,
                                            utilizadosUsM: utilizadosUsM,
                                            idAlmacen: idAlmacen,
                                            utilizadosM: utilizadosM
                                        },
                                        dataType: 'json',
                                        success: function (response) {



                                            $('#modalProducirMateria').modal('hide');

                                            window.location = 'index.php?p=producciones'




                                        },
                                        error: function (request, status, error) {
                                            alert(request.responseText);
                                        }
                                    });
                                }
                                else {
                                    $('#modalMensajeError').find('.modal-body').text(response.mensaje).end().modal('show');
                                }




                            },
                            error: function (request, status, error) {
                                alert(request.responseText);
                            }
                        });
                    }









                }
            }
        }
        else {
            $('#modalMensajeError').find('.modal-body').text('El código escaneado debe ser una Recepción "R" o una Devolución "D"').end().modal('show');
        }

    });

    $(document).on('click', 'a[data-role=reimprimeProduccion]', function () {
        var id = $(this).data('id');

        $.ajax({
            url: 'reimprimeProduccion.php',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function (response) {

                $('#modalMensajeError').find('.modal-body').text('Se mandó imprimir la Etiqueta P' + id).end().modal('show');

            },
            error: function (request, status, error) {
                alert(request.responseText);
            }
        });

    });


    $(document).on('click', 'a[data-role=cancelarProduccion]', function () {
        var id = $(this).data('id');


        $('#idProduccionCancelar').val(id);



        $('#modalCancelarProduccion').modal('toggle');
    });

    $('#cancelarProduccion').click(function () {

        var id = $('#idProduccionCancelar').val();
        $.ajax({
            url: 'cancelarProduccion.php',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function (response) {

                if (response.exito) {
                    $('#modalCancelarProduccion').modal('hide');

                    window.location = 'index.php?p=producciones'
                }
                else {
                    $('#modalMensajeError').find('.modal-body').text(response.mensaje).end().modal('show');
                }

            },
            error: function (request, status, error) {
                alert(request.responseText);
            }
        });


    });


});



