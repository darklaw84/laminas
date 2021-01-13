<?php

include 'SimpleXLSXGen.php';

include_once './controllers/CotizacionController.php';
$controller = new CotizacionController();




$respuesta = $controller->obtenerCotizaciones("P", 0, 0);
$registros = $respuesta->registros;



$books = [
    [
        'Pedido', 'Cliente', 'Usuario', 'Fecha Entrega', 'Forma Pago',
        'Total', 'Abonos', 'Partidas', 'Fase', 'Pagado', 'Cantidad', 'UM',
        'SKU', 'Producto', 'Calibre', 'Material', 'Metros', 'Precio Unitario', 'Monto', 'Metros Lineales', 'Peso Teorico','Peso Real'
    ]
];

foreach ($registros as $reg) {

    $tieneProducciones = false;
    $todasConRemisiones = true;
    $tieneRemisiones = false;
    foreach ($reg['productos'] as $prod) {
        if (!$prod['todasConRemisiones']) {
            $todasConRemisiones = false;
        }
        if ($prod['tieneRemision']) {
            $tieneRemisiones = true;
        }
        if (count($prod['producciones']) > 0) {
            $tieneProducciones = true;
            break;
        }
    }
    if ($reg['costoEnvio'] == "") {
        $reg['costoEnvio'] = 0;
    }
    $total = $reg['grantotal'] + $reg['costoEnvio'];

    $fase = "";
    if (!$tieneProducciones) {
        if ($reg['produccion'] == 0) {
            $fase = " No Autorizado";
        } else {
            $fase = "Autorizado";
        }
    } else {
        if ($todasConRemisiones) {
            $fase = "Entregado";
        } else if ($tieneRemisiones) {
            $fase = "Parcialmente Entregado";
        } else {
            $fase =  "Con Producciones";
        }
    }

    $pagado = "";
    if ($reg['pedidoPagado']) {
        $pagado = "Pagado";
    } else {
        $pagado = "Pendiente de pagar";
    }


    if (!$todasConRemisiones && $reg['cancelado'] != "1") {
        if (count($reg['productos']) > 0) {
            foreach ($reg['productos'] as $abns) {
                if (is_numeric($abns['largo'])) {
                    $largo = $abns['largo'];
                } else {
                    $largo = "";
                }

                if ($abns['metros'] > 0) {

                    $metrosLineales = $abns['metros'] * $abns['cantidad'];
                } else {
                    if (is_numeric($abns['largo'])) {
                        $metrosLineales = $largo * $abns['cantidad'];
                    } else {
                        $metrosLineales = 0;
                    }
                }

                $pesoTeorico = 0;
                if ($abns['idUnidad'] == 3) {
                    $pesoTeorico = $abns['cantidad'];
                } else if ($abns['idUnidad'] == 1) {
                    $pesoTeorico = $abns['pesoTeorico'] * $abns['cantidad'];
                } else {
                    if ($metrosLineales == 0) {
                        $pesoTeorico = $abns['pesoTeorico'] * $abns['cantidad'];
                    } else {
                        $pesoTeorico = $abns['pesoTeorico'] * $metrosLineales;
                    }
                }

                $totalPartida = $abns['preciounitario'] * $abns['cantidad'];
                $producto = $abns['producto'] . " " . $largo . " " . $abns['ancho'];


                
                $producto=str_replace("&quot;","''",$producto);

                $registro = [
                    $reg['idCotizacion'], $reg['cliente'], $reg['usuario'], $reg['fechaEntrega'], $reg['formapago'],
                    $total, $reg['totalAbonos'], count($reg['productos']), $fase, $pagado, $abns['cantidad'],
                    $abns['unidad'], $abns['sku'], $producto, $abns['calibre'], $abns['tipo'], $abns['metros'], $abns['preciounitario'], $totalPartida, $metrosLineales, $pesoTeorico,$abns['kilosTotalesUsuario']
                ];
                array_push($books, $registro);
            }
        } else {
            $registro = [
                $reg['idCotizacion'], $reg['cliente'], $reg['usuario'], $reg['fechaEntrega'], $reg['formapago'],
                $total, $reg['totalAbonos'], count($reg['productos']), $fase, $pagado, '',
                '', '', '', ''
            ];
            array_push($books, $registro);
        }
    } else {

        $fase = "";

        if ($reg['cancelado']) {
            $fase = "Cancelado";
        } else {
            $fase = "Entregado";
        }

        if (count($reg['productos']) > 0) {
            foreach ($reg['productos'] as $abns) {
                if (is_numeric($abns['largo'])) {
                    $largo = $abns['largo'];
                } else {
                    $largo = "";
                }

                if ($abns['metros'] > 0) {

                    $metrosLineales = $abns['metros'] * $abns['cantidad'];
                } else {
                    if (is_numeric($abns['largo'])) {
                        $metrosLineales = $largo * $abns['cantidad'];
                    } else {
                        $metrosLineales = 0;
                    }
                }

                $pesoTeorico = 0;
                if ($abns['idUnidad'] == 3) {
                    $pesoTeorico = $abns['cantidad'];
                } else if ($abns['idUnidad'] == 1) {
                    $pesoTeorico = $abns['pesoTeorico'] * $abns['cantidad'];
                } else {
                    if ($metrosLineales == 0) {
                        $pesoTeorico = $abns['pesoTeorico'] * $abns['cantidad'];
                    } else {
                        $pesoTeorico = $abns['pesoTeorico'] * $metrosLineales;
                    }
                }

                $totalPartida = $abns['preciounitario'] * $abns['cantidad'];
                $producto =  $abns['producto'] . " " . $largo . " " . $abns['ancho'] ;

                $producto=str_replace("&quot;","''",$producto);

                $registro = [
                    $reg['idCotizacion'], $reg['cliente'], $reg['usuario'], $reg['fechaEntrega'], $reg['formapago'],
                    $total, $reg['totalAbonos'], count($reg['productos']), $fase, $pagado, $abns['cantidad'],
                    $abns['unidad'], $abns['sku'],$producto,$abns['calibre'],$abns['tipo'], $abns['metros'], $abns['preciounitario'], $totalPartida, $metrosLineales, $pesoTeorico,$abns['kilosTotalesUsuario']
                ];
                array_push($books, $registro);
            }
        } else {
            $registro = [
                $reg['idCotizacion'], $reg['cliente'], $reg['usuario'], $reg['fechaEntrega'], $reg['formapago'],
                $total, $reg['totalAbonos'], count($reg['productos']), $fase, $pagado, '',
                '', '', '', ''
            ];
            array_push($books, $registro);
        }
    }
}

$xlsx = SimpleXLSXGen::fromArray($books);
$xlsx->saveAs('books.xlsx');

$file = "books.xlsx";

header("Content-Description: Pedidos-Detalle");
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=\"" . "Pedidos-Detalle.xlsx" . "\"");

readfile($file);
exit();
