<?php


include_once './controllers/AdministradorController.php';
include_once './controllers/CotizacionController.php';
include_once './utils/convertirnumeroletra.php';
$contCotizaciones = new CotizacionController();
$idRemision = $_GET['idRemision'];


if (isset($idRemision)) {

   

    $respPro = $contCotizaciones->obtenerRemision($idRemision);
    if ($respPro->exito) {


        $idCotizacion = $respPro->registros[0]['idPedido'];
        
        $detalleRemision = $respPro->registros[0]['detalle'];


        
    }
}


if (isset($idCotizacion)) {

   

    $respPro = $contCotizaciones->obtenerCotizacion($idCotizacion);
    if ($respPro->exito) {


        $descuento = $respPro->registros[0]['descuento'];
        $grantotal = $respPro->registros[0]['grantotal'];

        $subtotal = $respPro->registros[0]['montototal'];
        $fecha = $respPro->registros[0]['fecha'];
        $uso = $respPro->registros[0]['uso'];
        $costoEnvio = $respPro->registros[0]['costoEnvio'];


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
    public $uso;
    public $clave;
    public $idRemision;

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
        $this->Image('./imagenes/logo.jpg', 10, 6, 40, 22);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Move to the right
        $this->Cell(130);
        // Title
        $this->Cell(30, 10, utf8_decode('REMISIÓN'), 0, 0, 'C');
        // Line break
        $this->Ln(3);
        $this->SetFont('Arial', '', 10);
        $this->SetXY(200, 20);
        $this->Cell(50, 7, utf8_decode('FOLIO:'), $this->bor, 0, 'R');
        $this->Cell(40, 7, str_pad($this->idRemision, 3, "0", STR_PAD_LEFT), $this->bor, 0, 'R');
        $this->SetXY(200, 27);
        $this->Cell(50, 7, utf8_decode('FECHA DE ELABORACIÓN:'), $this->bor, 0, 'R');
        $this->Cell(40, 7, date("Y-m-d"), $this->bor, 0, 'R');
        $this->SetXY(150, 35);
        $this->Cell(90, 7, utf8_decode('LUGAR EXPEDICIÓN:'), $this->bor, 0, 'R');
        $this->Cell(50, 7, utf8_decode('SANTIAGO DE QUERÉTARO'), $this->bor, 0, 'R');
        
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
        $this->Cell(16, 10, utf8_decode('CANTIDAD'), 1, 0, 'C', true);
        $this->Cell(18, 10, utf8_decode('UM'), 1, 0, 'C', true);
        $this->Cell(168, 10, utf8_decode('DESCRIPCIÓN'), 1, 0, 'C', true); //165
        
        $this->Cell(27, 10, utf8_decode('MEDIDAS'), 1, 0, 'C', true);
        $this->Cell(26, 10, utf8_decode('PRECIO UNITARIO'), 1, 0, 'C', true);
        //$this->Cell(18, 10, utf8_decode('METROS'), 1, 0, 'C', true);
        //$this->Cell(22, 10, utf8_decode('$ X PIEZA'), 1, 0, 'C', true);
        $this->Cell(22, 10, utf8_decode('TOTAL'), 1, 0, 'C', true);
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
$pdf->cotnum = $idCotizacion;
$pdf->idRemision = $idRemision;
$pdf->cliente = $respPro->registros[0]['cliente'];
$pdf->uso = $respPro->registros[0]['uso'];
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


foreach ($detalleRemision as $reg) {

    if (is_numeric($reg['largo'])) {
        $largoancho = $reg['largo'] . " " . $reg['ancho'];;
    } else {
        $largoancho = $reg['metros'] . " " . $reg['ancho'];
    }

  
        $totalPartida = $reg['preciounitario'] * $reg['cantidad'];
        $precioPorPieza = $reg['preciounitario'] ;
    
    if ($contador % 2 == 0) {
        $pdf->SetFillColor(230, 230, 230);
    } else {
        $pdf->SetFillColor(255, 255, 255);
    }
    $pdf->SetX(5);
    $pdf->Cell(10, 6, $contador, $bor, 0, 'C', true);
    $pdf->Cell(16, 6, $reg['cantidad'], $bor, 0, 'C', true);
    $pdf->Cell(18, 6, $reg['unidadFactura'], $bor, 0, 'C', true);
    $pdf->Cell(168, 6, strtoupper($reg['producto'] . " " . $reg['calibre'] . " " . $reg['tipo']), $bor, 0, 'C', true);
  


    $pdf->Cell(27, 6, strtoupper($largoancho), $bor, 0, 'C', true);
   
    $pdf->Cell(26, 6, "$ " . number_format($precioPorPieza, 2, '.', ','), $bor, 0, 'C', true);
    // $pdf->Cell(18, 6,  number_format($reg['metros'], 2, '.', ''), $bor, 0, 'C', true);
    // $pdf->Cell(22, 6, "$ " . number_format($precioPorPieza, 2, '.', ','), $bor, 0, 'C', true);
    $pdf->Cell(22, 6, "$ " . number_format($totalPartida, 2, '.', ','), $bor, 1, 'C', true);

    $contador++;
}

if($costoEnvio>0)
{
    $pdf->SetX(5);
    $pdf->Cell(10, 6, $contador, $bor, 0, 'C', true);
    $pdf->Cell(16, 6, "1", $bor, 0, 'C', true);
    $pdf->Cell(18, 6, "", $bor, 0, 'C', true);
    $pdf->Cell(168, 6, utf8_decode("Envío"), $bor, 0, 'C', true);
  


    $pdf->Cell(27, 6, "", $bor, 0, 'C', true);
   
    $pdf->Cell(26, 6, "$ " . number_format($costoEnvio, 2, '.', ','), $bor, 0, 'C', true);
    // $pdf->Cell(18, 6,  number_format($reg['metros'], 2, '.', ''), $bor, 0, 'C', true);
    // $pdf->Cell(22, 6, "$ " . number_format($precioPorPieza, 2, '.', ','), $bor, 0, 'C', true);
    $pdf->Cell(22, 6, "$ " . number_format($costoEnvio, 2, '.', ','), $bor, 1, 'C', true);
    $subtotal=$subtotal+$costoEnvio;
}

$pdf->SetX(244);
$pdf->Cell(26, 6, "GRAN TOTAL", $bor, 0, 'C');
$pdf->Cell(22, 6, "$ " . number_format($subtotal, 2, '.', ','), $bor, 1, 'C');
$pdf->SetX(244);
$pdf->Cell(26, 6, "DESCUENTO", $bor, 0, 'C');


if ($descuento > 0) {
    $descuento = $descuento * $subtotal / 100;
}
$base = $subtotal - $descuento;
$iva = $base * 16 / 100;
$grantotal = $base + $iva;

$grantotal = round($grantotal);
$base = $grantotal / 1.16;
$iva = $grantotal * .16/1.16;


$pdf->Cell(22, 6, "$ " . number_format($descuento, 2, '.', ','), $bor, 1, 'C');




$pdf->SetX(244);
$pdf->Cell(26, 6, "SUBTOTAL", $bor, 0, 'C');
$pdf->Cell(22, 6, "$ " . number_format($subtotal - $descuento, 2, '.', ','), $bor, 1, 'C');

$pdf->SetX(244);
$pdf->Cell(26, 6, "I.V.A.", $bor, 0, 'C');
$pdf->Cell(22, 6, "$ " . number_format($iva, 2, '.', ','), $bor, 1, 'C');

$pdf->SetFont('Arial', 'B', 7);
$pdf->SetX(244);
$pdf->Cell(26, 6, "VALOR TOTAL", $bor, 0, 'C');
$pdf->Cell(22, 6, "$ " . number_format($grantotal, 2, '.', ','), $bor, 1, 'C');
$pdf->Ln(1);
$bori = 0;
$izq = 8;
$letra = 10;
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
$alto = 5;

if ($observaciones != "") {
    $pdf->SetX($izq);
    $pdf->SetFont('Arial', 'B', $letra);
    $pdf->Cell(30, $alto, utf8_decode("OBSERVACIONES:"), $bori, 0, 'R');
    $pdf->SetFont('Arial', '', $letra);
    $pdf->Cell(155, $alto, strtoupper(utf8_decode($observaciones)), $bori, 1, 'L');
}
if ($condiciones != "") {
    $pdf->SetX($izq);
    $pdf->SetFont('Arial', 'B', $letra);
    $pdf->Cell(30, $alto, utf8_decode("CONDICIONES:"), $bori, 0, 'R');
    $pdf->SetFont('Arial', '', $letra);
    $pdf->Cell(155, $alto, strtoupper(utf8_decode($condiciones)), $bori, 1, 'L');
}
if ($vigencia != "") {
    $pdf->SetX($izq);
    $pdf->SetFont('Arial', 'B', $letra);
    $pdf->Cell(30, $alto, utf8_decode("VIG. DE PRECIOS:"), $bori, 0, 'R');
    $pdf->SetFont('Arial', '', $letra);
    $pdf->Cell(155, $alto, strtoupper(utf8_decode($vigencia)), $bori, 1, 'L');
}
if ($formapago != "") {
    $pdf->SetX($izq);
    $pdf->SetFont('Arial', 'B', $letra);
    $pdf->Cell(30, $alto, utf8_decode("FORMA DE PAGO:"), $bori, 0, 'R');
    $pdf->SetFont('Arial', '', $letra);
    $pdf->Cell(155, $alto, strtoupper(utf8_decode($formapago)), $bori, 1, 'L');
}
if ($uso != "") {
    $pdf->SetX($izq);
    $pdf->SetFont('Arial', 'B', $letra);
    $pdf->Cell(30, $alto, utf8_decode("CFDI:"), $bori, 0, 'R');
    $pdf->SetFont('Arial', '', $letra);
    $pdf->Cell(155, $alto, strtoupper(utf8_decode($uso)), $bori, 1, 'L');
}
if ($fechaEntrega != "") {
    $pdf->SetX($izq);
    $pdf->SetFont('Arial', 'B', $letra);
    $pdf->Cell(30, $alto, utf8_decode("FECHA ENTREGA:"), $bori, 0, 'R');
    $pdf->SetFont('Arial', '', $letra);
    $pdf->Cell(155, $alto, strtoupper(utf8_decode($fechaEntrega)), $bori, 1, 'L');
}
if ($lugarentrega != "") {
    $pdf->SetX($izq);
    $pdf->SetFont('Arial', 'B', $letra);
    $pdf->Cell(30, $alto, utf8_decode("LUG. DE ENTREGA:"), $bori, 0, 'R');
    $pdf->SetFont('Arial', '', $letra);
    $pdf->Cell(155, $alto, strtoupper(utf8_decode($lugarentrega)), $bori, 1, 'L');
}

$pdf->SetX($izq);
$pdf->SetFont('Arial', 'B', $letra);
$pdf->Cell(30, $alto, utf8_decode("NOTA:"), $bori, 0, 'R');
$pdf->SetFont('Arial', '', $letra);
$pdf->Cell(155, $alto, utf8_decode('Precios netos, sujetos a cambio sin previo aviso'), $bori, 1, 'L');


$pdf->Cell(155, $alto, strtoupper(utf8_decode('')), $bori, 1, 'L');
$pdf->Cell(155, $alto, strtoupper(utf8_decode('')), $bori, 1, 'L');
$pdf->SetX($izq);
$pdf->SetFont('Arial', 'B', $letra);
$pdf->Cell(30, $alto, utf8_decode(""), $bori, 0, 'R');
$pdf->SetFont('Arial', '', $letra);
$pdf->Cell(155, $alto, utf8_decode('Sin más por el momento y confiando recibir una respuesta favorable, quedo atent@ a sus comentarios.'), $bori, 1, 'L');

$pdf->Cell(155, $alto, strtoupper(utf8_decode('')), $bori, 1, 'L');
$pdf->SetX($izq);
$pdf->SetFont('Arial', 'B', $letra);
$pdf->Cell(30, $alto, utf8_decode(""), $bori, 0, 'R');
$pdf->SetFont('Arial', '', $letra);
$pdf->Cell(155, $alto, utf8_decode('Saludos cordiales,'), $bori, 1, 'L');



$pdf->SetX($izq);
$pdf->SetFont('Arial', 'B', 7);



$pdf->Output();
