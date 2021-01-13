$(document).ready(function () {


    $('#inventarioT').DataTable({
        scrollY:        '35vh',
        scrollCollapse: true,
        paging:         false,
        "order": [[0, "desc"]], "lengthMenu": [
            [-1, 100, 200],
            ["Todos", 100, 200]
        ],
        "footerCallback": function (row, data, start, end, display) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function (i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Total over all pages
            total = api
                .column(5)
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Total over this page
            pageTotal = api
                .column(5, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Update footer
            $(api.column(5).footer()).html(
                '' + parseFloat(pageTotal).toFixed(2) + ' ( ' + parseFloat(total).toFixed(2) + ' total)'
            );
        }
    });

    

 


    $('#recepcionesT').DataTable({
        scrollY:        '35vh',
        scrollCollapse: true,
        paging:         false,
        "order": [[0, "desc"]], "lengthMenu": [
            [-1, 100, 200],
            ["Todos", 100, 200]
        ],
        "footerCallback": function (row, data, start, end, display) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function (i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Total over all pages
            total = api
                .column(5)
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Total over this page
            pageTotal = api
                .column(5, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Update footer
            $(api.column(5).footer()).html(
                '' + parseFloat(pageTotal).toFixed(2) 
            );
        }
    });


    $(document).on('click', 'a[data-role=reimprimeRecepcion]', function () {
        var id = $(this).data('id');

        $.ajax({
            url: 'reimprimeRecepcion.php',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function (response) {

                $('#modalMensajeError').find('.modal-body').text('Se mandó imprimir la Recepción R'+id ).end().modal('show');

            },
            error: function (request, status, error) {
                alert(request.responseText);
            }
        });

    });



    $(document).on('click', 'a[data-role=recibirMateria]', function () {
        var id = $(this).data('id');

        $('#idMateria').val(id);
        var idUnidad = $('#' + id).children().find('.idUnidad').val();
        var prodPesoTeorico = $('#' + id).children().find('.prodPesoTeorico').val();
        var idProducto = $('#' + id).children().find('.idProducto').val();
        $('#recibirMateria').prop('disabled', false);
        $('#recibirMateria').text('Recibir Material');

        $('#productoPesoTeoricoM').val(prodPesoTeorico);
        $('#divPesoTeorico').hide();
        $("#divCantidadRecM").show();
        //si es piezas solo ponemos piezas
        $("#idUnidadRec").empty();
        if (idUnidad == 1) {
            $("#idUnidadRec").append("<option value='1'>PIEZA</option>");
            $('#lblCantidadM').text("Cant Piezas");

        }
        else if (idUnidad == 2) {
            // si es metro ponemos kilos y metros
            $("#idUnidadRec").append("<option value='0'>- Seleccione -</option>");
            $("#idUnidadRec").append("<option value='2'>METRO</option>");
            $("#idUnidadRec").append("<option value='3'>KILO</option>");
            $("#divCantidadRecM").hide();
            $('#divPesoTeorico').show();
            if (prodPesoTeorico === "") {
                //quiere decir que no tiene, no lo podemos dejar pasar

                $('#recibirMateria').prop("disabled", true);
                $('#recibirMateria').text('No tiene Peso Teorico el producto, no se puede recibir');
            }


        }
        else if (idUnidad == 3) {
            // si es KILO ponemos SOLO kilos 
            $("#idUnidadRec").append("<option value='3'>KILO</option>");
            $('#lblCantidadM').text("Cant Kilos");

        }
        var producto = $('#' + id).children('td[data-target=producto]').text();
        $('#productoRecibirM').val(producto);

        $('#idProductoM').val(idProducto);
        


        $('#modalRecibirMateria').modal('toggle');
       
    });

    $('#modalRecibirMateria').on('shown.bs.modal', function () {
        $('#codigoBarras').focus();
    })


    $('#recibirMateria').click(function () {

        var idMateria = $('#idMateria').val();
        var idAlmacen = $('#idAlmacenRec').val();
        
        var idUnidad = $("#idUnidadRec").val();
        var cantidad = $("#cantidadRecM").val();

        var idProducto = $('#idProductoM').val();
        var pesoTeorico = $('#productoPesoTeoricoM').val();
        var idOrden = $('#idOrdenRecMat').val();
      
            if (idAlmacen > 0) {
                if (idUnidad > 0) {
                    if (cantidad === "" || cantidad <= 0) {
                        $('#modalMensajeError').find('.modal-body').text('La cantidad debe de ser un valor númerico válido').end().modal('show');
                    }
                    else {



                        $.ajax({
                            url: 'recibirMateria.php',
                            type: 'post',
                            data: {
                                idMateriaF: idMateria,
                                idAlmacenF: idAlmacen,
                                cantidadF: cantidad,
                                idUnidadF: idUnidad,
                                idProductoF: idProducto,
                                idOrden: idOrden,
                                pesoTeoricoF: pesoTeorico
                            },
                            dataType: 'json',
                            success: function (response) {

                                $('#modalRecibirMateria').modal('hide');

                                window.location = 'index.php?p=recMat&entro=1&idOrden=' + idOrden;


                               
                            },
                            error: function (request, status, error) {
                              
                            }
                        });


                    }
                }
                else {
                    $('#modalMensajeError').find('.modal-body').text('Seleccione una unidad válida').end().modal('show');
                }
            }
            else {
                $('#modalMensajeError').find('.modal-body').text('Seleccione un almacen válido').end().modal('show');
            }
        

    });


    $('#idUnidadRec').on('change', function () {
        if (this.value == 0) {
            $("#divCantidadRecM").hide();


        }
        else if (this.value == 2) {
            $('#divCantidadRecM').show();

            $("#lblCantidadM").text("Cant Metros");

        }
        else {
            $('#divCantidadRecM').show();

            $("#lblCantidadM").text("Cant Kilos");
        }
    });


});