$(document).ready(function () {



    $('#btnFinalizarOrden').click(function () {

       var idOrden =  $('#idOrden').val();

       $('#modalFinalizaOrden').modal('toggle');

    });



    $('#finalizaOrden').click(function () {

        var idOrden =  $('#idOrden').val();
 
        $.ajax({
         url: 'finalizarOrden.php',
         type: 'post',
         data: {
             idOrden: idOrden
         },
         dataType: 'json',
         success: function (response) {
 
             if (response.exito) {
                $('#modalFinalizaOrden').modal('toggle');
                 window.location = 'index.php?p=ordenescompraact&idOrden='+idOrden;
                
             }
             else {
                $('#modalFinalizaOrden').modal('toggle');
                 $('#modalMensajeError').find('.modal-body').text(response.mensaje).end().modal('show');
 
             }
 
         }
     });
 
 
     });


    $(document).on('click', 'a[data-role=eliminaOCompra]', function () {
        var id = $(this).data('id');


        $('#idOrdenM').val(id);


        $('#modalEliminaOCompra').modal('toggle');
    });


    $('#btnEliminarOrden').click(function () {

        var id = $('#idOrdenM').val();


        $.ajax({
            url: 'eliminarOCompra.php',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function (response) {

                if (response.exito) {

                    $('#' + id).remove();

                    $('#modalEliminaOCompra').modal('hide');
                }
                else {
                    $('#modalMensajeError').find('.modal-body').text(response.mensaje).end().modal('show');

                }

            }
        });



    });



    $('#idUnidad').on('change', function () {
        ajustarUnidades();
    });

    function ajustarUnidades()
    {
        var idUnidadSel=$('#idUnidad').val();
        if (idUnidadSel == 1 || idUnidadSel == 4) {
            $('#divPesoTeorico').hide();

            $("#lblPrecio").text("Precio x Pieza");

        }
        else if (idUnidadSel == 3) {
            $('#divPesoTeorico').hide();

            $("#lblPrecio").text("Precio UM");

        }
        else if (idUnidadSel == 2) {
            $('#divPesoTeorico').show();
            $("#lblPrecio").text("Precio X Metro");
        }
        else
        {
            $('#divPesoTeorico').hide();
            $("#lblPrecio").text("Precio X Uni. de Peso");
        }


        var cantidad = $('#cantProd').val();
      
        var pesoTeoricoBase = $('#pesoTeoricoF').val();

        if (cantidad != "" && idUnidadSel == 2 && pesoTeoricoBase != "") {
            var pesoTeoricoOrden = cantidad * pesoTeoricoBase;
            var resultado = Math.round((pesoTeoricoOrden + Number.EPSILON) * 100) / 100
            $('#pesoTeorico').val(resultado);

        }
        else {
            $('#pesoTeorico').val('');
        }
    }


    $('#cantProd').on('input',function(e){
        var cantidad = $('#cantProd').val();
        var idUnidad = $('#idUnidad').val();
        var pesoTeoricoBase = $('#pesoTeoricoF').val();

        if (cantidad != "" && idUnidad == 2 && pesoTeoricoBase != "") {
            var pesoTeoricoOrden = cantidad * pesoTeoricoBase;
            $('#pesoTeorico').val(pesoTeoricoOrden);

        }
        else {
            $('#pesoTeorico').val('');
        }
    });


});

var my_function = null;

$(function () {

    function traerProducto(id) {
        var idProducto = id;

        if (idProducto > 0) {
            $.ajax({
                url: 'consultaProd.php',
                method: 'post',
                data: {
                    idProducto: idProducto,

                },
                dataType: 'json',
                success: function (data) {
                    var datos = data;
                    var idUnidad=data.idUnidad;
                    $('#pesoTeoricoF').val(datos.pesoTeorico);

                    $("#idUnidad").empty();
                    if (idUnidad == 1) {
                        $("#idUnidad").append("<option value='1'>PIEZA</option>");
                        
            
                    }
                    else if (idUnidad == 2) {
                        // si es metro ponemos kilos y metros
                        $("#idUnidad").append("<option value='0'>- Seleccione -</option>");
                        $("#idUnidad").append("<option value='2'>METRO</option>");
                        $("#idUnidad").append("<option value='3'>KILO</option>");
                
                    }
                    else if (idUnidad == 3) {
                        // si es KILO ponemos SOLO kilos 
                        $("#idUnidad").append("<option value='3'>KILO</option>");
                       
                    }

                    ajustarUnidades();
                    ajustarPesoTeorico();


                }
            });
        }
        else {
            $('#pesoTeoricoF').val('');
        }
    }

    function ajustarPesoTeorico() {
        var cantidad = $('#cantProd').val();
        var idUnidad = $('#idUnidad').val();
        var pesoTeoricoBase = $('#pesoTeoricoF').val();

        if (cantidad != "" && idUnidad == 2 && pesoTeoricoBase != "") {
            var pesoTeoricoOrden = cantidad * pesoTeoricoBase;
            $('#pesoTeorico').val(pesoTeoricoOrden);

        }
        else {
            $('#pesoTeorico').val('');
        }

    }


    function ajustarUnidades()
    {
        var idUnidadSel=$('#idUnidad').val();
        if (idUnidadSel == 1 || idUnidadSel == 4) {
            $('#divPesoTeorico').hide();

            $("#lblPrecio").text("Precio x Pieza");

        }
        else if (idUnidadSel == 3) {
            $('#divPesoTeorico').hide();

            $("#lblPrecio").text("Precio UM");

        }
        else if (idUnidadSel == 2) {
            $('#divPesoTeorico').show();
            $("#lblPrecio").text("Precio X Uni. de Peso");
        }
        else
        {
            $('#divPesoTeorico').hide();
            $("#lblPrecio").text("Precio X Uni. de Peso");
        }


        var cantidad = $('#cantProd').val();
        var idUnidad = this.value;
        var pesoTeoricoBase = $('#pesoTeoricoF').val();

        if (cantidad != "" && idUnidad == 2 && pesoTeoricoBase != "") {
            var pesoTeoricoOrden = cantidad * pesoTeoricoBase;
            $('#pesoTeorico').val(pesoTeoricoOrden);

        }
        else {
            $('#pesoTeorico').val('');
        }
    }

    my_function = traerProducto;
}) 