<?php


include_once './controllers/OrdenesController.php';
include_once './utils/convertirnumeroletra.php';
$contOrdenes = new OrdenesController();
$idOrden = $_GET['idOrden'];

if (isset($idOrden)) {

    $respPro = $contOrdenes->obtenerOrden($idOrden);
    if ($respPro->exito) {
        $proveedor = $respPro->registros[0]['proveedor'];
        $telefono = $respPro->registros[0]['telefono'];

        $fechaRequerida = $respPro->registros[0]['fechaRequerida'];
        $comentarios = $respPro->registros[0]['comentarios'];

        $total = $respPro->registros[0]['total'];
        $usuario = $respPro->registros[0]['usuario'];
        $productosOrden = $respPro->registros[0]['productos'];
    }
}

require('fpdf.php');

class PDF extends FPDF
{
    // Page header
    public $idOrden;
    public $proveedor;
    public $fechaRequerida;
    public $usuario;
    public $telefono;

    public  $bor = 0;



    function Header()
    {
        // Logo
        $this->Image('./imagenes/logo.jpg', 10, 6, 30, 20);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Move to the right
        $this->Cell(130);
        // Title
        $this->Cell(30, 10, utf8_decode('ORDEN DE COMPRA'), 0, 0, 'C');
        // Line break
        $letrasa = new CifrasEnLetras();
        $alto = 5;
        $tamLetra = 8;
        $this->Ln(3);
        $this->SetFont('Arial', '', $tamLetra);
        $this->SetXY(185, 20);
        $this->Cell(50, $alto, utf8_decode('FOLIO:'), $this->bor, 0, 'R');
        $this->Cell(50, $alto, strtoupper(utf8_decode(str_pad($this->idOrden, 3, "0", STR_PAD_LEFT))), $this->bor, 0, 'R');
        $this->SetXY(185, 27);
        $this->Cell(50, $alto, utf8_decode('FECHA DE ELABORACIÓN:'), $this->bor, 0, 'R');
        $this->Cell(50, $alto, date("d") . " de " . $letrasa->convertirMes(date('m')) . " de " . date('Y'), $this->bor, 0, 'R');
        $this->SetXY(145, 33);
        $this->Cell(90, $alto, utf8_decode('LUGAR EXPEDICIÓN:'), $this->bor, 0, 'R');
        $this->Cell(50, $alto, utf8_decode('SANTIAGO DE QUERÉTARO'), $this->bor, 0, 'R');

        $this->SetXY(10, 33);
        $this->SetFont('Arial', 'B', $tamLetra);
        $this->Cell(40, $alto, utf8_decode('PROVEEDOR:'), $this->bor, 0, 'R');
        $this->SetFont('Arial', '', $tamLetra);
        $this->Cell(200, $alto, strtoupper(utf8_decode($this->proveedor)), $this->bor, 0, 'L');
        $this->SetXY(10, 39);



        $this->SetXY(10, 40);
        $this->SetFont('Arial', 'B', $tamLetra);
        $this->Cell(40, $alto, utf8_decode('TELÉFONO:'), $this->bor, 0, 'R');
        $this->SetFont('Arial', '', $tamLetra);
        $this->Cell(50, $alto, strtoupper(utf8_decode($this->telefono)), $this->bor, 0, 'L');
        $this->SetFont('Arial', 'B', $tamLetra);

        $this->SetXY(145, 40);
        $this->Cell(90, $alto, utf8_decode('USUARIO:'), $this->bor, 0, 'R');
        $this->Cell(50, $alto, strtoupper(utf8_decode($this->usuario)), $this->bor, 0, 'R');

        $this->SetXY(10, 47);
        $this->SetFont('Arial', 'b', 8);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(15, $alto + 1, utf8_decode('#'), 1, 0, 'C', true);
        $this->Cell(20, $alto + 1, utf8_decode('CANTIDAD'), 1, 0, 'C', true);
        $this->Cell(20, $alto + 1, utf8_decode('UM'), 1, 0, 'C', true);
        $this->Cell(160, $alto + 1, utf8_decode('DESCRIPCIÓN'), 1, 0, 'C', true); //165

        // $this->Cell(40, $alto + 1, utf8_decode('PESO TEÓRICO'), 1, 0, 'C', true);
        $this->Cell(30, $alto + 1, utf8_decode('PRECIO UNITARIO'), 1, 0, 'C', true);

        $this->Cell(30, $alto + 1, utf8_decode('TOTAL'), 1, 0, 'C', true);
        $this->SetTextColor(0, 0, 0);

        $this->Ln(5);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-18);
        // Arial italic 8
        $this->SetFont('Arial', 'B', 7);
        // Page number
        //  $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');

        $this->Cell(0, 4, utf8_decode('Central de Láminas Querétaro '), 0, 1, 'C');
        $this->SetFont('Arial', '', 7);
        $this->Cell(0, 4, utf8_decode('Carretera México - Querétaro Km 199 + 500, El Carmen, El Marqués, Querétaro. CP 76240 '), 0, 1, 'C');
        $this->Cell(0, 4, utf8_decode('Tel. 442 221 5971 | 442 221 5972 '), 0, 1, 'C');
        $this->Cell(0, 4, utf8_decode('www.centraldelamina.com'), 0, 1, 'C');
    }
}

// Instanciation of inherited class
$bor = 1;
$pdf = new PDF();
$pdf->idOrden = $idOrden;
$pdf->proveedor = $proveedor;
$pdf->fechaRequerida = $fechaRequerida;
$pdf->usuario = $usuario;
$pdf->telefono = $telefono;



$pdf->AliasNbPages();
$pdf->AddPage('L');
$pdf->SetFont('Arial', '', 7);
$contador = 1;
$altodetalle = 5;
$grantotal = 0;
$cantidadTotal = 0;
foreach ($productosOrden as $reg) {
    $cantidadTotal = $cantidadTotal + $reg['cantidad'];

    if (is_numeric($reg['largo'])) {
        if ($reg['medidasreves'] == "1") {
            $largoancho = $reg['ancho'] . " " . $reg['largo'] . " M";;
        } else {
            $largoancho = $reg['largo'] . " " . $reg['ancho'];;
        }
    } else {
        $largoancho = $reg['ancho'];
    }


    $totalPartida = $reg['precioUnidadPeso'] * $reg['cantidad'];
    $grantotal = $grantotal + $totalPartida;

    if ($contador % 2 == 0) {
        $pdf->SetFillColor(230, 230, 230);
    } else {
        $pdf->SetFillColor(255, 255, 255);
    }
    $pdf->SetX(10);
    $pdf->Cell(15, $altodetalle, $contador, $bor, 0, 'C', true);
    $pdf->Cell(20, $altodetalle, number_format($reg['cantidad'], 2, '.', ','), $bor, 0, 'C', true);
    $pdf->Cell(20, $altodetalle, $reg['unidad'], $bor, 0, 'C', true);
    if (is_numeric($reg['largo'])) {
        $largo = $reg['largo'];
    } else {
        $largo = "";
    }
    $descripcion = strtoupper($reg['producto'] . " " . $largo . " " . $reg['ancho'] . " " . $reg['calibre'] . " " . $reg['tipo']);
    $descripcion = str_replace("N/A", "", $descripcion);
    $descripcion = str_replace('&QUOT;', '"', $descripcion);
    $pdf->Cell(160, $altodetalle, utf8_decode($descripcion), $bor, 0, 'C', true);



    if ($reg['pesoTeorico'] > 0) {
        $pesoTeoricos = $reg['cantidad'] * $reg['pesoTeorico'];
    } else if ($reg['prodPesoTeorico'] > 0) {
        $pesoTeoricos = $reg['cantidad'] * $reg['prodPesoTeorico'];
    }


    //$pdf->Cell(40, $altodetalle, number_format($pesoTeoricos, 2, '.', ','), $bor, 0, 'C', true);
    $pdf->Cell(3, $altodetalle, "$ ", 'LBT', 0, 'C', true);
    $pdf->Cell(27, $altodetalle, number_format($reg['precioUnidadPeso'], 2, '.', ','), 'BTR', 0, 'R', true);
    $pdf->Cell(3, $altodetalle, "$ ", 'LBT', 0, 'C', true);
    $pdf->Cell(27, $altodetalle, number_format($totalPartida, 2, '.', ','), 'BTR', 1, 'R', true);

    $contador++;
}

if ($contador % 2 == 0) {
    $pdf->SetFillColor(230, 230, 230);
} else {
    $pdf->SetFillColor(255, 255, 255);
}


$gris = 230;
$pdf->SetX(244);






$base = $grantotal / 1.16;
$iva = $grantotal * .16 / 1.16;
$subtotal = $grantotal - $iva;




$pdf->SetFont('Arial', 'B', 7);
$pdf->SetFillColor($gris);
$pdf->SetX(10);
$pdf->Cell(15, $altodetalle, "TOTAL", $bor, 0, 'C', true);
$pdf->Cell(20, $altodetalle,  number_format($cantidadTotal, 2, '.', ','), $bor, 0, 'C', true);
$pdf->SetX(225);
$pdf->Cell(30,  $altodetalle, "SUBTOTAL", $bor, 0, 'C', true);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(3, $altodetalle, "$ ", 'LBT', 0, 'C', true);
$pdf->Cell(27,  $altodetalle, number_format($subtotal, 2, '.', ','), 'BTR', 1, 'R', true);

$pdf->SetX(225);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(30,  $altodetalle, "I.V.A.", $bor, 0, 'C');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(3, $altodetalle, "$ ", 'LBT', 0, 'C', false);
$pdf->Cell(27,  $altodetalle, number_format($iva, 2, '.', ','), 'BTR', 1, 'R');

$pdf->SetFont('Arial', 'B', 7);
$pdf->SetX(225);
$pdf->SetFillColor(0);
$pdf->SetTextColor(255);
$pdf->Cell(30,  $altodetalle, "VALOR TOTAL", $bor, 0, 'C', true);
$pdf->Cell(3, $altodetalle, "$ ", 'LBT', 0, 'C', true);

$pdf->Cell(27,  $altodetalle, number_format($grantotal, 2, '.', ','), 'BTR', 1, 'R', true);
$pdf->SetTextColor(0);
$pdf->Ln(1);
$bori = 0;
$izq = 8;
$letra = 8;
$letrac = 5;
$letras = new CifrasEnLetras();
$pdf->SetFont('Arial', '', $letra);

$pdf->SetX($izq);
$pdf->SetFont('Arial', 'B', $letra);
$pdf->Cell(30, 6, utf8_decode("IMPORTE TOTAL:"), $bori, 0, 'R');

$pdf->SetFont('Arial', '', $letra);
$msjLetras = $letras->convertirPesosEnLetras($grantotal, 2);
if (strlen($msjLetras) > 80) {
    $pdf->SetFont('Arial', '', 5);
}


$pdf->Cell(155, 6, utf8_decode(strtoupper($msjLetras)) . " M.N.", $bori, 1, 'L');


$alto = 4;
$alto2 = 3;


$pdf->Image('./imagenes/datosfacturacion.jpg', 225, 160, 60, 30);




$pdf->Output();
