<?php

include 'SimpleXLSXGen.php';

include_once './controllers/CatalogosController.php';
$controller = new CatalogosController();



$respuesta = $controller->obtenerRecepcionesGlobal();
$recepciones = $respuesta->registros;




$books = [
    [
        '# Recepción', 'Producto','Unidad', 'Cantidad Total', 'Usados', 'Disponibles',
        'Almacen'
    ]
];

foreach ($recepciones as $det) {

    if (is_numeric($det['largo'])) {
        $largo = $det['largo'];
        $mostrarbotonMetros = false;
    } else {
        $largo = "";
    }

    if ($det['peso'] == null) {
        $total = $det['cantidad'];
    } else {
        $total = $det['peso'];
    }

    $registro = [
        $det['id'], $det['sku'] . " " . $det['producto'] . " " . $largo . " " . $det['ancho'] . " " . $det['calibre'] . " " . $det['tipo'],
         $det['unidad'], $total, $det['kilosUsados'],$det['restante'],$det['almacen']
    ];
    array_push($books, $registro);
}


$xlsx = SimpleXLSXGen::fromArray($books);
$xlsx->saveAs('books.xlsx');

$file = "books.xlsx";

header("Content-Description: InventarioMP");
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=\"" . "InventarioMP.xlsx" . "\"");

readfile($file);
exit();
