<?php


include_once './controllers/AdministradorController.php';
include_once './controllers/CotizacionController.php';
include_once './utils/convertirnumeroletra.php';
$contCotizaciones = new CotizacionController();
$contAdmin = new AdministradorController();
$idCotizacion = $_GET['idCotizacion'];
$idUsuario = $_GET['idU'];

if (isset($idCotizacion)) {

    $usuario = $contAdmin->obtenerAdministrador($idUsuario)->registros[0];

    $respPro = $contCotizaciones->obtenerCotizacion($idCotizacion);
    if ($respPro->exito) {


        $descuento = $respPro->registros[0]['descuento'];
        $grantotal = $respPro->registros[0]['grantotal'];

        $subtotal = $respPro->registros[0]['montototal'];
        $fecha = $respPro->registros[0]['fecha'];


        $productosCotizacion = $respPro->registros[0]['productos'];
    }



    $extras = $contCotizaciones->obtenerExtrasCotizacion($idCotizacion)->registros[0];
    $observaciones = $extras['observaciones'];
    $condiciones = $extras['condiciones'];
    $vigencia = $extras['vigencia'];
    $formapago = $extras['formapago'];
    $lugarentrega = $extras['lugarentrega'];
    $fechaEntrega = $extras['fechaentrega'];
}

require('fpdf.php');

class PDF extends FPDF
{
    // Page header
    public $cliente;
    public $clave;

    public $direccion;
    public $cotnum;
    public $fechaEntrega;
    public $representante;
    public $direccionentrega;
    public $telefono;
    public $mail;

    public  $bor = 0;



    function Header()
    {
        // Logo
        $this->Image('./imagenes/logo.png', 10, 6, 40, 30);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Move to the right
        $this->Cell(130);
        // Title
        $this->Cell(30, 10, utf8_decode('PEDIDO'), 0, 0, 'C');
        // Line break
        $this->Ln(3);
        $this->SetFont('Arial', '', 10);
        $this->SetXY(200, 20);
        $this->Cell(50, 7, utf8_decode('PEDIDO NO.'), $this->bor, 0, 'R');
        $this->Cell(40, 7, strtoupper(utf8_decode(str_pad($this->cotnum, 3, "0", STR_PAD_LEFT))), $this->bor, 0, 'R');
        $this->SetXY(200, 27);
        $this->Cell(50, 7, utf8_decode('FECHA DE ELABORACION:'), $this->bor, 0, 'R');
        $this->Cell(40, 7, date("Y-m-d"), $this->bor, 0, 'R');
        $this->SetXY(10, 35);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(40, 7, utf8_decode('ATENCIÓN A:'), $this->bor, 0, 'R');
        $this->SetFont('Arial', '', 10);
        $this->Cell(200, 7, strtoupper(utf8_decode($this->representante)), $this->bor, 0, 'L');
        $this->SetXY(10, 41);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(40, 7, utf8_decode('EMPRESA:'), $this->bor, 0, 'R');
        $this->SetFont('Arial', '', 10);
        $this->Cell(200, 7, strtoupper(utf8_decode($this->cliente)), $this->bor, 0, 'L');

        $this->SetXY(10, 47);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(40, 7, utf8_decode('DIRECCIÓN ENTREGA:'), $this->bor, 0, 'R');
        $this->SetFont('Arial', '', 10);
        $this->Cell(200, 7, strtoupper(utf8_decode($this->direccionentrega)), $this->bor, 0, 'L');

        $this->SetXY(10, 53);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(40, 7, utf8_decode('TELÉFONO:'), $this->bor, 0, 'R');
        $this->SetFont('Arial', '', 10);
        $this->Cell(50, 7, strtoupper(utf8_decode($this->telefono)), $this->bor, 0, 'L');
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(40, 7, utf8_decode('MAIL:'), $this->bor, 0, 'R');
        $this->SetFont('Arial', 'U', 10);
        $this->Cell(200, 7, strtoupper(utf8_decode($this->mail)), $this->bor, 0, 'L');
        $this->SetXY(5, 60);
        $this->SetFont('Arial', 'b', 8);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(10, 10, utf8_decode('#'), 1, 0, 'C', true);
        $this->Cell(165, 10, utf8_decode('DESCRIPCIÓN'), 1, 0, 'C', true);
        $this->Cell(16, 10, utf8_decode('UNIDAD'), 1, 0, 'C', true);
        $this->Cell(16, 10, utf8_decode('CANTIDAD'), 1, 0, 'C', true);
        $this->Cell(18, 10, utf8_decode('$ X U DE M'), 1, 0, 'C', true);
        $this->Cell(18, 10, utf8_decode('METROS'), 1, 0, 'C', true);
        $this->Cell(22, 10, utf8_decode('$ X PIEZA'), 1, 0, 'C', true);
        $this->Cell(22, 10, utf8_decode('MONTO'), 1, 0, 'C', true);
        $this->SetTextColor(0, 0, 0);

        $this->Ln(10);
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

        $this->Cell(0, 4, utf8_decode('Central de láminas Querétaro '), 0, 1, 'C');
        $this->SetFont('Arial', '', 7);
        $this->Cell(0, 4, utf8_decode('Carretera México - Querétaro Km 199 + 500, El Carmen, El Marquéz, Querétaro. CP 76240 '), 0, 1, 'C');
        $this->Cell(0, 4, utf8_decode('Tel. 442 221 5971 | 442 221 5972 '), 0, 1, 'C');
        $this->Cell(0, 4, utf8_decode('www.centraldelamina.com'), 0, 1, 'C');
    }
}

// Instanciation of inherited class
$bor = 1;
$pdf = new PDF();
$pdf->cotnum = $idCotizacion;
$pdf->cliente = $respPro->registros[0]['cliente'];
$pdf->direccion = $respPro->registros[0]['direccion'];
$pdf->fechaEntrega = $respPro->registros[0]['fechaEntrega'];
$pdf->representante = $respPro->registros[0]['representante'];
$pdf->direccionentrega = $respPro->registros[0]['direccionentrega'];
$pdf->telefono = $respPro->registros[0]['telefono'];
$pdf->mail = $respPro->registros[0]['mail'];



$pdf->AliasNbPages();
$pdf->AddPage('L');
$pdf->SetFont('Arial', '', 7);
$contador = 1;


foreach ($productosCotizacion as $reg) {
    if ($reg['metros'] > 0) {
        $totalPartida = round($reg['metros'] * $reg['preciounitario'] * $reg['cantidad']);
        $precioPorPieza = round($totalPartida / $reg['cantidad']);
    } else {
        $totalPartida = round($reg['preciounitario'] * $reg['cantidad']);
        $precioPorPieza = "0.00";
    }
    if ($contador % 2 == 0) {
        $pdf->SetFillColor(230, 230, 230);
    } else {
        $pdf->SetFillColor(255, 255, 255);
    }
    $pdf->SetX(5);
    $pdf->Cell(10, 6, $contador, $bor, 0, 'C', true);
    $pdf->Cell(165, 6, strtoupper($reg['producto'] . " " . $reg['calibre'] . " " . $reg['tipo']), $bor, 0, 'C', true);
    $pdf->Cell(16, 6, $reg['unidad'], $bor, 0, 'C', true);
    $pdf->Cell(16, 6, $reg['cantidad'], $bor, 0, 'C', true);
    $pdf->Cell(18, 6, "$ " . number_format($reg['preciounitario'], 2, '.', ','), $bor, 0, 'C', true);
    $pdf->Cell(18, 6,  number_format($reg['metros'], 2, '.', ''), $bor, 0, 'C', true);
    $pdf->Cell(22, 6, "$ " . number_format($precioPorPieza, 2, '.', ','), $bor, 0, 'C', true);
    $pdf->Cell(22, 6, "$ " . number_format($totalPartida, 2, '.', ','), $bor, 1, 'C', true);

    $contador++;
}
$pdf->SetX(248);
$pdf->Cell(22, 6, "GRAN TOTAL", $bor, 0, 'C');
$pdf->Cell(22, 6, "$ " . number_format($subtotal, 2, '.', ','), $bor, 1, 'C');
$pdf->SetX(248);
$pdf->Cell(22, 6, "DESCUENTO", $bor, 0, 'C');
if ($descuento > 0) {
    $descuento = $descuento * $subtotal / 100;
}
$pdf->Cell(22, 6, "$ " . number_format($descuento, 2, '.', ','), $bor, 1, 'C');

$base = $subtotal - $descuento;
$pdf->SetX(248);
$pdf->Cell(22, 6, "SUBTOTAL", $bor, 0, 'C');
$pdf->Cell(22, 6, "$ " . number_format($subtotal - $descuento, 2, '.', ','), $bor, 1, 'C');
$iva = $base * 16 / 100;
$pdf->SetX(248);
$pdf->Cell(22, 6, "I.V.A.", $bor, 0, 'C');
$pdf->Cell(22, 6, "$ " . number_format($iva, 2, '.', ','), $bor, 1, 'C');
$grantotal = $base + $iva;
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetX(248);
$pdf->Cell(22, 6, "VALOR TOTAL", $bor, 0, 'C');
$pdf->Cell(22, 6, "$ " . number_format($grantotal, 2, '.', ','), $bor, 1, 'C');
$pdf->Ln(1);
$bori = 0;
$izq = 8;
$letras = new CifrasEnLetras();
$pdf->SetFont('Arial', '', 7);

$pdf->SetX($izq);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(45, 6, utf8_decode("IMPORTE TOTAL DEL PEDIDO:"), $bori, 0, 'R');

$pdf->SetFont('Arial', '', 7);
$msjLetras = $letras->convertirPesosEnLetras($grantotal, 2);
if (strlen($msjLetras) > 80) {
    $pdf->SetFont('Arial', '', 5);
}


$pdf->Cell(155, 6, utf8_decode(strtoupper($msjLetras)) . " M.N.", $bori, 1, 'L');
$alto=4;
if ($observaciones != "") {
    $pdf->SetX($izq);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(45,$alto , utf8_decode("OBSERVACIONES:"), $bori, 0, 'R');
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(155, $alto, strtoupper(utf8_decode($observaciones)), $bori, 1, 'L');
}
if ($condiciones != "") {
    $pdf->SetX($izq);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(45, $alto, utf8_decode("CONDICIONES:"), $bori, 0, 'R');
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(155, $alto, strtoupper(utf8_decode($condiciones)), $bori, 1, 'L');
}
if ($vigencia != "") {
    $pdf->SetX($izq);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(45, $alto, utf8_decode("VIGENCIA DE PRECIOS:"), $bori, 0, 'R');
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(155, $alto, strtoupper(utf8_decode($vigencia)), $bori, 1, 'L');
}
if ($formapago != "") {
    $pdf->SetX($izq);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(45, $alto, utf8_decode("FORMA DE PAGO:"), $bori, 0, 'R');
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(155, $alto, strtoupper(utf8_decode($formapago)), $bori, 1, 'L');
}
if ($fechaEntrega != "") {
    $pdf->SetX($izq);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(45, $alto, utf8_decode("FECHA DE ENTREGA:"), $bori, 0, 'R');
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(155, $alto, strtoupper(utf8_decode($fechaEntrega)), $bori, 1, 'L');
}
if ($lugarentrega != "") {
    $pdf->SetX($izq);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(45, $alto, utf8_decode("LUGAR DE ENTREGA:"), $bori, 0, 'R');
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(155, $alto, strtoupper(utf8_decode($lugarentrega)), $bori, 1, 'L');
}



$pdf->SetX($izq);
$pdf->SetFont('Arial', 'B', 7);



$pdf->Output();
