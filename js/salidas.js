$(document).ready(function () {

    //lenarDatosModal

    $(document).on('click', 'a[data-role=imprimeCartaPorte]', function () {
        var id = $(this).data('id');

        $.ajax({
            url: 'obtenerDatosCartaPorte.php',
            type: 'post',
            data: {
                id: id
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

        $.ajax({
            url: 'realizarTraspaso.php',
            type: 'post',
            data: {
                id: id,
                idAlmacenTraspaso: idAlmacenTraspaso
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

        var placas = $('#placasM').val();
        var operador = $('#operadorM').val();
        var unidad = $('#tipoUnidadM').val();
        var contenedor = $('#contenedorM').val();
        var id = $('#idRemisionM').val();

        var conPesoM = 0;
        if ($('#conPesoM').prop("checked") == true) {
            conPesoM = 1;
        }



        $.ajax({
            url: 'guardarDatosCartaPorte.php',
            type: 'post',
            data: {
                id: id,
                placas: placas,
                contenedor: contenedor,
                tipoUnidad: unidad,
                operador: operador
            },
            dataType: 'json',
            success: function (response) {

                if (response.exito) {
                    window.open('imprimirCartaPorte.php?idRemision=' + id + '&conPeso=' + conPesoM, '_blank');

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


