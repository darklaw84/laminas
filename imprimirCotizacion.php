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
        $this->Image('./imagenes/logo.jpg', 10, 6, 30, 20);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Move to the right
        $this->Cell(130);
        // Title
        $this->Cell(30, 10, utf8_decode('COTIZACIÓN'), 0, 0, 'C');
        // Line break
        $letrasa = new CifrasEnLetras();
        $alto = 5;
        $tamLetra = 8;
        $this->Ln(3);
        $this->SetFont('Arial', '', $tamLetra);
        $this->SetXY(185, 20);
        $this->Cell(50, $alto, utf8_decode('FOLIO:'), $this->bor, 0, 'R');
        $this->Cell(50, $alto, strtoupper(utf8_decode(str_pad($this->cotnum, 3, "0", STR_PAD_LEFT))), $this->bor, 0, 'R');
        $this->SetXY(185, 27);
        $this->Cell(50, $alto, utf8_decode('FECHA DE ELABORACIÓN:'), $this->bor, 0, 'R');
        $this->Cell(50, $alto, date("d") . " de " . $letrasa->convertirMes(date('m')) . " de " . date('Y'), $this->bor, 0, 'R');
        $this->SetXY(145, 33);
        $this->Cell(90, $alto, utf8_decode('LUGAR EXPEDICIÓN:'), $this->bor, 0, 'R');
        $this->Cell(50, $alto, utf8_decode('SANTIAGO DE QUERÉTARO'), $this->bor, 0, 'R');

        $this->SetXY(10, 33);
        $this->SetFont('Arial', 'B', $tamLetra);
        $this->Cell(40, $alto, utf8_decode('ATENCIÓN A:'), $this->bor, 0, 'R');
        $this->SetFont('Arial', '', $tamLetra);
        $this->Cell(200, $alto, strtoupper(utf8_decode($this->representante)), $this->bor, 0, 'L');
        $this->SetXY(10, 39);
        $this->SetFont('Arial', 'B', $tamLetra);
        $this->Cell(40, $alto, utf8_decode('EMPRESA:'), $this->bor, 0, 'R');
        $this->SetFont('Arial', '', $tamLetra);
        $this->Cell(200, $alto, strtoupper(utf8_decode($this->cliente)), $this->bor, 0, 'L');

        $this->SetXY(10, 45);
        $this->SetFont('Arial', 'B', $tamLetra);
        $this->Cell(40, $alto, utf8_decode('DIRECCIÓN ENTREGA:'), $this->bor, 0, 'R');
        $this->SetFont('Arial', '', $tamLetra);
        $this->Cell(200, $alto, strtoupper(utf8_decode($this->direccionentrega)), $this->bor, 0, 'L');

        $this->SetXY(10, 51);
        $this->SetFont('Arial', 'B', $tamLetra);
        $this->Cell(40, $alto, utf8_decode('TELÉFONO:'), $this->bor, 0, 'R');
        $this->SetFont('Arial', '', $tamLetra);
        $this->Cell(50, $alto, strtoupper(utf8_decode($this->telefono)), $this->bor, 0, 'L');
        $this->SetFont('Arial', 'B', $tamLetra);
        $this->Cell(40, $alto, utf8_decode('MAIL:'), $this->bor, 0, 'R');
        $this->SetFont('Arial', 'U', $tamLetra);
        $this->Cell(200, $alto, strtoupper(utf8_decode($this->mail)), $this->bor, 0, 'L');
        $this->SetXY(10, 58);
        $this->SetFont('Arial', 'b', 8);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(15, $alto + 1, utf8_decode('#'), 1, 0, 'C', true);
        $this->Cell(20, $alto + 1, utf8_decode('CANTIDAD'), 1, 0, 'C', true);
        $this->Cell(20, $alto + 1, utf8_decode('UM'), 1, 0, 'C', true);
        $this->Cell(120, $alto + 1, utf8_decode('DESCRIPCIÓN'), 1, 0, 'C', true); //165

        $this->Cell(40, $alto + 1, utf8_decode('MEDIDAS'), 1, 0, 'C', true);
        $this->Cell(30, $alto + 1, utf8_decode('PRECIO UNITARIO'), 1, 0, 'C', true);
        //$this->Cell(18, 10, utf8_decode('METROS'), 1, 0, 'C', true);
        //$this->Cell(22, 10, utf8_decode('$ X PIEZA'), 1, 0, 'C', true);
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
$pdf->cotnum = $idCotizacion;
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
$altodetalle = 5;

foreach ($productosCotizacion as $reg) {

    if (is_numeric($reg['largo'])) {
        if ($reg['medidasreves'] == "1") {
            $largoancho = $reg['ancho'] . " " . $reg['largo'] . " M";;
        } else {
            $largoancho = $reg['largo'] . " " . $reg['ancho'];;
        }
    } else {
        $largoancho = $reg['metros'] . " " . $reg['ancho'];
    }


    $totalPartida = $reg['preciounitario'] * $reg['cantidad'];


    if ($contador % 2 == 0) {
        $pdf->SetFillColor(230, 230, 230);
    } else {
        $pdf->SetFillColor(255, 255, 255);
    }
    $pdf->SetX(10);
    $pdf->Cell(15, $altodetalle, $contador, $bor, 0, 'C', true);
    $pdf->Cell(20, $altodetalle, number_format($reg['cantidad'], 2, '.', ','), $bor, 0, 'C', true);
    $pdf->Cell(20, $altodetalle, $reg['unidadFactura'], $bor, 0, 'C', true);
    $descripcion = strtoupper($reg['producto'] . " " . $reg['calibre'] . " " . $reg['tipo']);
    $descripcion = str_replace("N/A", "", $descripcion);
    $pdf->Cell(120, $altodetalle, utf8_decode($descripcion), $bor, 0, 'C', true);


    $largoancho = str_replace('&quot;', '"', $largoancho);
    $largoancho = str_replace('N/A', '', $largoancho);
    $pdf->Cell(40, $altodetalle, strtoupper($largoancho), $bor, 0, 'C', true);
    $pdf->Cell(3, $altodetalle, "$ ", 'LBT', 0, 'C', true);
    $pdf->Cell(27, $altodetalle, number_format($reg['preciounitario'] / 1.16, 2, '.', ','), 'BTR', 0, 'R', true);
    // $pdf->Cell(18, 6,  number_format($reg['metros'], 2, '.', ''), $bor, 0, 'C', true);
    // $pdf->Cell(22, 6, "$ " . number_format($precioPorPieza, 2, '.', ','), $bor, 0, 'C', true);
    $pdf->Cell(3, $altodetalle, "$ ", 'LBT', 0, 'C', true);
    $pdf->Cell(27, $altodetalle, number_format($totalPartida / 1.16, 2, '.', ','), 'BTR', 1, 'R', true);

    $contador++;
}

if ($contador % 2 == 0) {
    $pdf->SetFillColor(230, 230, 230);
} else {
    $pdf->SetFillColor(255, 255, 255);
}

if ($costoEnvio > 0) {
    $pdf->SetX(10);
    $pdf->Cell(15, $altodetalle, $contador, $bor, 0, 'C', true);
    $pdf->Cell(20, $altodetalle, "1", $bor, 0, 'C', true);
    $pdf->Cell(20, $altodetalle, "", $bor, 0, 'C', true);
    $pdf->Cell(120, $altodetalle, utf8_decode("MANIOBRAS"), $bor, 0, 'C', true);



    $pdf->Cell(40, $altodetalle, "", $bor, 0, 'C', true);
    $pdf->Cell(3, $altodetalle, "$ ", 'LBT', 0, 'C', true);
    $pdf->Cell(27, $altodetalle, number_format($costoEnvio / 1.16, 2, '.', ','), 'BTR', 0, 'R', true);

    $pdf->Cell(3, $altodetalle, "$ ", 'LBT', 0, 'C', true);
    $pdf->Cell(27, $altodetalle, number_format($costoEnvio / 1.16, 2, '.', ','), 'BTR', 1, 'R', true);
    $subtotal = $subtotal + ($costoEnvio/1.16);
}

$gris = 230;
$pdf->SetX(244);





if ($descuento > 0) {
    $descuento = $descuento * $subtotal / 100;
}
$base = $subtotal - $descuento;
$iva = $base * 16 / 100;
$grantotal = $base + $iva;

$grantotal = round($grantotal);
$base = $grantotal / 1.16;
$iva = $grantotal * .16 / 1.16;



$pdf->SetX(225);
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetFillColor($gris);
$pdf->Cell(30,  $altodetalle, "SUBTOTAL", $bor, 0, 'C', true);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(3, $altodetalle, "$ ", 'LBT', 0, 'C', true);
$pdf->Cell(27,  $altodetalle, number_format($subtotal - $descuento, 2, '.', ','), 'BTR', 1, 'R', true);

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


$pdf->SetFont('Arial', 'B', $letrac);
$pdf->SetX(225);
$pdf->Cell(60, $alto, utf8_decode('NÚMEROS DE CUENTA PARA DEPÓSITO O TRANSFERENCIA'), 'TLR', 1, 'C');



$pdf->SetX($izq);
$pdf->SetFont('Arial', 'B', $letra);
$pdf->Cell(30, $alto, utf8_decode("NOTA:"), $bori, 0, 'R');




$pdf->SetFont('Arial', '', $letra);
$pdf->Cell(155, $alto, utf8_decode('Precios sujetos a cambio sin previo aviso'), $bori, 0, 'L');
$pdf->SetFont('Arial', 'BU', $letrac);
$pdf->SetX(225);
$pdf->Cell(60, $alto, utf8_decode('BANAMEX'), 'LR', 1, 'C');


$pdf->Cell(155, $alto2, strtoupper(utf8_decode('')), $bori, 0, 'L');

$pdf->SetFont('Arial', '', $letrac);
$pdf->SetX(225);
$pdf->Cell(60, $alto, utf8_decode('CUENTA: 28982 SUCURSAL:45-56'), 'LR', 1, 'C');

$pdf->SetX($izq);
$pdf->SetFont('Arial', 'B', $letra);
$pdf->Cell(30, $alto, utf8_decode(""), $bori, 0, 'R');
$pdf->SetFont('Arial', '', $letra);
$pdf->Cell(155, $alto, utf8_decode('Atentamente,'), $bori, 0, 'L');

$pdf->SetFont('Arial', '', $letrac);
$pdf->SetX(225);
$pdf->Cell(60, $alto, utf8_decode('CLABE: 002 680 455 600 289 825'), 'LR', 1, 'C');

$pdf->Cell(155, $alto2, strtoupper(utf8_decode('')), $bori, 0, 'L');

$pdf->SetFont('Arial', '', $letrac);
$pdf->SetX(225);
$pdf->Cell(60, $alto, utf8_decode('DEPOSITOS EN EFECTIVO: 881 109 104 006 6095'), 'LR', 1, 'C');







$pdf->SetX($izq);
$pdf->SetFont('Arial', 'B', $letra);
$pdf->Cell(30, $alto, utf8_decode(""), $bori, 0, 'R');
$pdf->SetFont('Arial', 'B', $letra);
$pdf->Cell(155, $alto, utf8_decode($usuario['nombre'] . " " . $usuario['apellidos']), $bori, 0, 'L');

$pdf->SetFont('Arial', 'BU', $letrac);
$pdf->SetX(225);
$pdf->Cell(60, $alto, utf8_decode('BANORTE'), 'LR', 1, 'C');




$pdf->SetX($izq);
$pdf->SetFont('Arial', 'B', $letra);
$pdf->Cell(30, $alto, utf8_decode(""), $bori, 0, 'R');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(155, $alto, utf8_decode($usuario['correo'] . "    -     " . $usuario['telefono']), $bori, 0, 'L');


$pdf->SetFont('Arial', '', $letrac);
$pdf->SetX(225);
$pdf->Cell(60, $alto, utf8_decode('CUENTA: 08 42 74 51 94'), 'LR', 1, 'C');



$pdf->SetFont('Arial', '', $letrac);
$pdf->SetX(225);
$pdf->Cell(60, $alto, utf8_decode('CLABE: 072 680 008 427 451 941'), 'LR', 1, 'C');



$pdf->SetFont('Arial', 'BU', $letrac);
$pdf->SetX(225);
$pdf->Cell(60, $alto, utf8_decode('BANBAJIO'), 'LR', 1, 'C');

$pdf->SetFont('Arial', '', $letrac);
$pdf->SetX(225);
$pdf->Cell(60, $alto, utf8_decode('CUENTA: 16042624-0201'), 'LR', 1, 'C');


$pdf->SetFont('Arial', '', $letrac);
$pdf->SetX(225);
$pdf->Cell(60, $alto, utf8_decode('CLABE: 030 680 900 007 867 765'), 'LRB', 1, 'C');


$pdf->Output();
