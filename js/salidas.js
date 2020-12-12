$(document).ready(function () {

    //lenarDatosModal

    $(document).on('click', 'a[data-role=imprimeCartaPorte]', function () {
        var id = $(this).data('id');

        if (('' + id).includes('R')) {
            $('#imprimirCartaPorte').text('Imprimir Remisión');
        }
        else {
            $('#imprimirCartaPorte').text('Imprimir Carta Porte');
        }

        var idCorrecto = (''+ id).replace('R', '');

        $.ajax({
            url: 'obtenerDatosCartaPorte.php',
            type: 'post',
            data: {
                id: idCorrecto
            },
            dataType: 'json',
            success: function (response) {
                $('#placasM').val(response.placas);
                $('#operadorM').val(response.operador);
                $('#tipoUnidadM').val(response.tipoUnidad);
                $('#contenedorM').val(response.contenedor);
                $('#idRemisionM').val(id);

                $('#modalCartaPorte').modal('toggle');

            }
        });


    });


    $(document).on('click', 'a[data-role=cancelarRemision]', function () {
        var id = $(this).data('id');

        $('#idRemisionCancelar').val(id);

        $('#modalCancelarRemision').modal('show');

    });


    $('#cancelarRemision').click(function () {


        var id = $('#idRemisionCancelar').val();



        $.ajax({
            url: 'cancelarRemision.php',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            complete: function () {
                $('#modalCancelarRemision').modal('hide');
                window.location.href = 'index.php?p=remisiones';
            }
        });

    });



    $(document).on('click', 'a[data-role=imprimeRemision]', function () {
        var id = $(this).data('id');

        if (('' + id).includes('R')) {
            $('#imprimirCartaPorte').text('Imprimir Remisión');
        }
        else {
            $('#imprimirCartaPorte').text('Imprimir Carta Porte');
        }

        var idCorrecto = (''+id).replace('R', '');

        $.ajax({
            url: 'obtenerDatosCartaPorte.php',
            type: 'post',
            data: {
                id: idCorrecto
            },
            dataType: 'json',
            success: function (response) {
                $('#placasM').val(response.placas);
                $('#operadorM').val(response.operador);
                $('#tipoUnidadM').val(response.tipoUnidad);
                $('#contenedorM').val(response.contenedor);
                $('#idRemisionM').val(id);

                $('#modalCartaPorte').modal('toggle');

            }
        });


    });



    $(document).on('click', 'a[data-role=hacerTraspaso]', function () {
        var id = $(this).data('id');

        $('#idRecepcionM').val(id);

        $.ajax({
            url: 'obtenerAlmacenesDisponibles.php',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function (almacenes) {

                $("#idAlmacenTraspaso").empty();

                almacenes.forEach(almacen => {
                    $("#idAlmacenTraspaso").append("<option value='" + almacen.idAlmacen + "'>" + almacen.almacen + "</option>");
                });






                $('#modalTraspaso').modal('toggle');

            }
        });


    });

    $('#realizarTraspaso').click(function () {

        var id = $('#idRecepcionM').val();
        var idProd = $('#idProd').val();
        var idAlmacenTraspaso = $('#idAlmacenTraspaso').val();
        var idChofer = $('#idChoferTraspaso').val();

        $.ajax({
            url: 'realizarTraspaso.php',
            type: 'post',
            data: {
                id: id,
                idAlmacenTraspaso: idAlmacenTraspaso,
                idChofer:idChofer
            },
            dataType: 'json',
            success: function (almacenes) {

                if (idProd > 0) {
                    window.location = 'index.php?p=inventario&idProd=' + idProd;
                }
                else {
                    window.location = 'index.php?p=inventario';
                }


                $('#modalTraspaso').modal('toggle');

            }
        });


    });


    $('#imprimirCartaPorte').click(function () {

        var placas = $('#idCamionCP').val();
        var operador = $('#idChoferCP').val();
        var unidad = $('#tipoUnidadM').val();
        var contenedor = $('#contenedorM').val();
        var id = $('#idRemisionM').val();

        var idCorrecto = id.replace('R', '');

        var conPesoM = 0;
        if ($('#conPesoM').prop("checked") == true) {
            conPesoM = 1;
        }



        $.ajax({
            url: 'guardarDatosCartaPorte.php',
            type: 'post',
            data: {
                id: idCorrecto,
                placas: placas,
                contenedor: contenedor,
                tipoUnidad: unidad,
                operador: operador
            },
            dataType: 'json',
            success: function (response) {

                if (response.exito) {
                    if (id.includes('R')) {
                        window.open('imprimirRemision.php?idRemision=' + idCorrecto, '_blank');
                    }
                    else {
                        window.open('imprimirCartaPorte.php?idRemision=' + idCorrecto + '&conPeso=' + conPesoM, '_blank');
                    }

                    $('#modalCartaPorte').modal('toggle');
                }
                else {
                    $('#modalMensajeError').find('.modal-body').text(response.mensaje).end().modal('show');
                }

            }
        });

    });


    $('#btnDarSalida').click(function () {

        var id = $('#idProduccion').val();

        id = id.replace('P', '');

        $.ajax({
            url: 'darSalida.php',
            type: 'post',
            data: {
                idProduccion: id
            },
            dataType: 'json',
            success: function (response) {

                if (response.exito) {

                    $('#modalMensajeError').find('.modal-body').text('Se dió la salida exitosa del material').end().modal('show');

                    window.location = 'index.php?p=salidas';

                }
                else {
                    $('#modalMensajeError').find('.modal-body').text(response.mensaje).end().modal('show');

                }

            }
        });



    });


    $('#btnSelTodas').click(function () {

        var rows = $('#detalleProd tbody').children();
        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            var td = row.children[0];
            td.children.agregar.checked = true;


        }

    });


    $('#btnGenerarRemision').click(function () {

        var rows = $('#detalleProd tbody').children();

        var ids = [];

        var idPedido = $('#idCotizacion').val();


        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            var td = row.children[0];
            if (td.children.agregar.checked) {
                ids.push(row.id);
            }


        }


        if (ids.length > 0) {

            $.ajax({
                url: 'generarRemision.php',
                type: 'post',
                data: {
                    ids: ids,
                    idPedido: idPedido
                },
                dataType: 'json',
                success: function (response) {

                    if (response.exito) {

                        window.location = 'index.php?p=remisiones';

                    }
                    else {
                        $('#modalMensajeError').find('.modal-body').text(response.mensaje).end().modal('show');

                    }

                }
            });


        }
        else {
            $('#modalMensajeError').find('.modal-body').text('Debe seleccionar al menos un registro para generar la remisión').end().modal('show');
        }

    });




});


