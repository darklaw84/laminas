<?php

include 'SimpleXLSXGen.php';

include_once './controllers/OrdenesController.php';
$controller = new OrdenesController();




$respuesta = $controller->obtenerMateriales();
$ordenes = $respuesta->registros;



$books = [
    [
        'Orden', 'Producto', 'Calibre', 'Tipo', 'Unidad',
        'Peso Teórico', 'Recibido', 'Fecha Recepción', 'Usuario', 'Recepcion', 'SKU', 'Producto',
        'Unidad', 'Cantidad Ordenada', 'Cantidad Recibida', 'Usuario', 'Fecha', 'Almacen'
    ]
];

foreach ($ordenes as $ord) {
    foreach ($ord['orden'][0]['productos'] as $det) {
        foreach ($det['recepciones'] as $ins) {
            $orde = $ord['orden'][0];
            if (is_numeric($det['largo'])) {
                $largo = $det['largo'];
            } else {
                $largo = "";
            }


            $pesoTeoricoFinal = 0;

            if ($det['idUnidad'] == 3) {
                $pesoTeoricoFinal = $det['cantidad'];
            } else if ($det['idUnidad'] == 1) {
                if ($det['prodPesoTeorico'] > 0) {
                    $pesoTeoricoFinal = $det['prodPesoTeorico'] * $det['cantidad'];
                } else {
                    $pesoTeoricoFinal = $det['pesoTeorico'] * $det['cantidad'];
                }
            } else {
                if ($metrosLineales == 0) {
                    $pesoTeoricoFinal = $det['pesoTeorico'] * $det['cantidad'];
                } else {
                    $pesoTeoricoFinal = $det['pesoTeorico'] * $metrosLineales;
                }
            }

            $recibido = "NO";
            if ($det['recibido'] == "1") {
                $recibido = "SI";
            }



            if (is_numeric($det['largo'])) {
                $metrosLineales = $largo * $det['cantidad'];
            } else {
                $metrosLineales = 0;
            }



            $ordenada=0;
            $recibida=0;
            if($ins['idUnidad']==3)
            {
                $ordenada=$det['pesoTeorico'];
                $recibida=$ins['peso'];
            }
            else if ($ins['idUnidad']==2)
            {
                $ordenada=$det['cantidad'];
                $recibida=$ins['cantidad'];
            }
            else 
            {
                $ordenada=$det['cantidad'];
                $recibida=$ins['cantidad'];
            }

            $productoDet=$ins['producto'] . " " . $ins['calibre'] . " " . $ins['tipo'];
            $productoDet=str_replace("&quot;","''",$productoDet);
            

            $productoHea=$det['sku'] . " " . $det['producto'] . " " . $largo . " " . $det['ancho'];
            $productoHea=str_replace("&quot;","''",$productoHea);


            $registro = [
                $orde['idOrden'], $productoHea,
                $det['calibre'], $det['tipo'], $det['unidad'],
                $pesoTeoricoFinal, $recibido, $det['fechaUltimaRecepcion'], $orde['usuario'],
                $ins['idRecepcion'],$det['sku'],$productoDet, $ins['unidad'] ,$ordenada,$recibida,
                $ins['usuarioRecibe'], $ins['fechaRecibe'], $ins['almacen']
            ];
            array_push($books, $registro);
        }
    }
}




$xlsx = SimpleXLSXGen::fromArray($books);
$xlsx->saveAs('books.xlsx');

$file = "books.xlsx";

header("Content-Description: Materiales-Detalle");
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=\"" . "Materiales-Detalle.xlsx" . "\"");

readfile($file);
exit();
