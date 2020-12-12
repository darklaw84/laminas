<?php

include 'SimpleXLSXGen.php';

include_once './controllers/CotizacionController.php';
$controller = new CotizacionController();




$respuesta = $controller->obtenerCotizaciones("P", 0, 0);
$registros = $respuesta->registros;



$books = [
    [
        'Pedido', 'Cliente', 'Usuario', 'Fecha Entrega', 'Forma Pago',
        'Total', 'Abonos', 'Partidas', 'Fase', 'Pagado', '# Abono', 'Monto',
        'Forma de Pago', 'Fecha', 'Usuario'
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
        if (count($reg['abonos']) > 0) {
            foreach ($reg['abonos'] as $abns) {

                $registro = [
                    $reg['idCotizacion'], $reg['cliente'], $reg['usuario'], $reg['fechaEntrega'], $reg['formapago'],
                    $total, $reg['totalAbonos'], count($reg['productos']), $fase, $pagado, $abns['idAbono'],
                    $abns['monto'], $abns['formapago'], $abns['fecha'], $abns['usuario']
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

        if (count($reg['abonos']) > 0) {
            foreach ($reg['abonos'] as $abns) {

                $registro = [
                    $reg['idCotizacion'], $reg['cliente'], $reg['usuario'], $reg['fechaEntrega'], $reg['formapago'],
                    $total, $reg['totalAbonos'], count($reg['productos']), $fase, $pagado, $abns['idAbono'],
                    $abns['monto'], $abns['formapago'], $abns['fecha'], $abns['usuario']
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
