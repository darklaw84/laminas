<?php

include 'SimpleXLSXGen.php';

include_once './controllers/CotizacionController.php';
$controller = new CotizacionController();



$respuesta = $controller->obtenerRemisiones();
$registros = $respuesta->registros;



$books = [
    [
        'Remisión',  'Pedido', 'Cliente', 'Usuario', 'Fecha', 'Operador',
        'Producto', 'Unidad', 'Cantidad'
    ]
];

foreach ($registros as $reg) {
    foreach ($reg['detalle'] as $det) {

        if (is_numeric($det['largo'])) {
            $largo = $det['largo'];
            $largoancho =  $largo . " " . $det['ancho'];
        } else {
            $largo = $det['metros'];
            $largoancho =  $largo . " " . $det['ancho'];
        }
        $producto = strtoupper($det['sku'] . " " . $det['producto'] . " " . $largo . " " . $det['ancho'] . " " . $det['calibre'] . " " . $det['tipo']);

        $producto=str_replace("&quot;","''",$producto);

        $registro = [
            $reg['idRemision'], $reg['idCotizacion'], $reg['cliente'],$reg['usuario'] ,$reg['fecha'], $reg['chofer'],
            $producto, $det['unidadFactura'], $det['cantidad'],
        ];
        array_push($books, $registro);
    }
}

$xlsx = SimpleXLSXGen::fromArray($books);
$xlsx->saveAs('books.xlsx');

$file = "books.xlsx";

header("Content-Description: Remisiones");
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=\"" . "Remisiones.xlsx" . "\"");

readfile($file);
exit();
