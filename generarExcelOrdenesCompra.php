<?php

include 'SimpleXLSXGen.php';

include_once './controllers/OrdenesController.php';
$controller = new OrdenesController();



$respuesta = $controller->obtenerOrdenes();
$registros = $respuesta->registros;



$books = [
    [
        'Id', 'Proveedor', 'Fecha', 'Fecha Requerida', 'Estatus',
        'Total', 'Partidas', 'Usuario', 'Cantidad', 'Unidad', 'SKU','Producto','Calibre','Material', 'Peso Teórico',
        'Precio Unitario', 'Importe', 'Recibido'
    ]
];

foreach ($registros as $reg) {

    $estatus = "No Recibido";
    if ($reg['icono'] == "correcto.png") {
        $estatus = "Recibido";
    }

    foreach ($reg['productos'] as $ins) {
        if (is_numeric($ins['largo'])) {
            $largo = $ins['largo'];
        } else {
            $largo = "";
        }

        $pesoTeorico = 0;
        if ($ins['pesoTeorico'] > 0) {
            if ($ins['idUnidad'] == 3) {
                $pesoTeorico = $ins['cantidad'];
            } else {
                $pesoTeorico = $ins['cantidad'] * $ins['pesoTeorico'];
            }
        } else if ($ins['prodPesoTeorico'] > 0) {
            if ($ins['idUnidad'] == 3) {
                $pesoTeorico = $ins['cantidad'];
            } else {
                $pesoTeorico = $ins['cantidad'] * $ins['prodPesoTeorico'];
            }
        }
        $recibido = "NO";
        if ($ins['recibido'] == "1") {
            $recibido = "SI";
        }

        $productoDet= $ins['producto'] . " " . $largo . " " . $ins['ancho'];
        $productoDet=str_replace("&quot;","''",$productoDet);

        $registro = [
            $reg['idOrden'], $reg['proveedor'], $reg['fecha'], $reg['fechaRequerida'],
            $estatus, $reg['total'], count($reg['productos']), $reg['usuario'],
            $ins['cantidad'], $ins['unidad'],$ins['sku'], $productoDet  , $ins['calibre'] , $ins['tipo'],
            $pesoTeorico, $ins['precioUnidadPeso'], $ins['precio']
        ];
        array_push($books, $registro);
    }
}

$xlsx = SimpleXLSXGen::fromArray($books);
$xlsx->saveAs('books.xlsx');

$file = "books.xlsx";

header("Content-Description: OrdenesCompra-Detalle");
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=\"" . "OrdenesCompra-Detalle.xlsx" . "\"");

readfile($file);
exit();
