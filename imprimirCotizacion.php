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
        $this->SetXY(190, 20);
        $this->Cell(50, $alto, utf8_decode('FOLIO:'), $this->bor, 0, 'R');
        $this->Cell(50, $alto, strtoupper(utf8_decode(str_pad($this->cotnum, 3, "0", STR_PAD_LEFT))), $this->bor, 0, 'R');
        $this->SetXY(190, 27);
        $this->Cell(50, $alto, utf8_decode('FECHA DE ELABORACIÓN:'), $this->bor, 0, 'R');
        $this->Cell(50, $alto, date("d") . " de " . $letrasa->convertirMes(date('m')) . " de " . date('Y'), $this->bor, 0, 'R');
        $this->SetXY(150, 33);
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
        $this->SetXY(5, 58);
        $this->SetFont('Arial', 'b', 8);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(10, $alto + 1, utf8_decode('#'), 1, 0, 'C', true);
        $this->Cell(16, $alto + 1, utf8_decode('CANTIDAD'), 1, 0, 'C', true);
        $this->Cell(18, $alto + 1, utf8_decode('UM'), 1, 0, 'C', true);
        $this->Cell(168, $alto + 1, utf8_decode('DESCRIPCIÓN'), 1, 0, 'C', true); //165

        $this->Cell(27, $alto + 1, utf8_decode('MEDIDAS'), 1, 0, 'C', true);
        $this->Cell(26, $alto + 1, utf8_decode('PRECIO UNITARIO'), 1, 0, 'C', true);
        //$this->Cell(18, 10, utf8_decode('METROS'), 1, 0, 'C', true);
        //$this->Cell(22, 10, utf8_decode('$ X PIEZA'), 1, 0, 'C', true);
        $this->Cell(22, $alto + 1, utf8_decode('TOTAL'), 1, 0, 'C', true);
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
        $largoancho = $reg['largo'] . " " . $reg['ancho'];;
    } else {
        $largoancho = $reg['metros'] . " " . $reg['ancho'];
    }


    $totalPartida = $reg['preciounitario'] * $reg['cantidad'];


    if ($contador % 2 == 0) {
        $pdf->SetFillColor(230, 230, 230);
    } else {
        $pdf->SetFillColor(255, 255, 255);
    }
    $pdf->SetX(5);
    $pdf->Cell(10, $altodetalle, $contador, $bor, 0, 'C', true);
    $pdf->Cell(16, $altodetalle, $reg['cantidad'], $bor, 0, 'C', true);
    $pdf->Cell(18, $altodetalle, $reg['unidadFactura'], $bor, 0, 'C', true);
    $descripcion = strtoupper($reg['producto'] . " " . $reg['calibre'] . " " . $reg['tipo']);
    $descripcion = str_replace("N/A", "", $descripcion);
    $pdf->Cell(168, $altodetalle, $descripcion, $bor, 0, 'C', true);


    $largoancho = str_replace('&quot;', '"', $largoancho);
    $largoancho = str_replace('N/A', '', $largoancho);
    $pdf->Cell(27, $altodetalle, strtoupper($largoancho), $bor, 0, 'C', true);
    $pdf->Cell(3, $altodetalle, "$ ", 'BT', 0, 'C', true);
    $pdf->Cell(23, $altodetalle, number_format($reg['preciounitario'], 2, '.', ','), 'BTR', 0, 'R', true);
    // $pdf->Cell(18, 6,  number_format($reg['metros'], 2, '.', ''), $bor, 0, 'C', true);
    // $pdf->Cell(22, 6, "$ " . number_format($precioPorPieza, 2, '.', ','), $bor, 0, 'C', true);
    $pdf->Cell(3, $altodetalle, "$ ", 'BT', 0, 'C', true);
    $pdf->Cell(19, $altodetalle, number_format($totalPartida, 2, '.', ','), 'BTR', 1, 'R', true);

    $contador++;
}

if ($costoEnvio > 0) {
    $pdf->SetX(5);
    $pdf->Cell(10, $altodetalle, $contador, $bor, 0, 'C', true);
    $pdf->Cell(16, $altodetalle, "1", $bor, 0, 'C', true);
    $pdf->Cell(18, $altodetalle, "", $bor, 0, 'C', true);
    $pdf->Cell(168, $altodetalle, utf8_decode("ENVÍO"), $bor, 0, 'C', true);



    $pdf->Cell(27, $altodetalle, "", $bor, 0, 'C', true);
    $pdf->Cell(3, $altodetalle, "$ ", 'BT', 0, 'C', true);
    $pdf->Cell(23, $altodetalle, number_format($costoEnvio, 2, '.', ','), 'BTR', 0, 'R', true);

    $pdf->Cell(3, $altodetalle, "$ ", 'BT', 0, 'C', true);
    $pdf->Cell(19, $altodetalle, number_format($costoEnvio, 2, '.', ','), 'BTR', 1, 'R', true);
    $subtotal = $subtotal + $costoEnvio;
}

$gris = 230;
$pdf->SetX(244);
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetFillColor($gris);
$pdf->Cell(26,  $altodetalle, "GRAN TOTAL", $bor, 0, 'C', true);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(3, $altodetalle, "$ ", 'BT', 0, 'C', true);
$pdf->Cell(19,  $altodetalle, number_format($subtotal, 2, '.', ','), 'BTR', 1, 'R', true);
$pdf->SetFillColor(255);
$pdf->SetX(244);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(26,  $altodetalle, "DESCUENTO", $bor, 0, 'C');


if ($descuento > 0) {
    $descuento = $descuento * $subtotal / 100;
}
$base = $subtotal - $descuento;
$iva = $base * 16 / 100;
$grantotal = $base + $iva;

$grantotal = round($grantotal);
$base = $grantotal / 1.16;
$iva = $grantotal * .16 / 1.16;
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(3, $altodetalle, "$ ", 'BT', 0, 'C', false);
$pdf->Cell(19,  $altodetalle, number_format($descuento, 2, '.', ','), 'BTR', 1, 'R');




$pdf->SetX(244);
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetFillColor($gris);
$pdf->Cell(26,  $altodetalle, "SUBTOTAL", $bor, 0, 'C', true);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(3, $altodetalle, "$ ", 'BT', 0, 'C', true);
$pdf->Cell(19,  $altodetalle, number_format($subtotal - $descuento, 2, '.', ','), 'BTR', 1, 'R', true);

$pdf->SetX(244);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(26,  $altodetalle, "I.V.A.", $bor, 0, 'C');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(3, $altodetalle, "$ ", 'BT', 0, 'C', false);
$pdf->Cell(19,  $altodetalle, number_format($iva, 2, '.', ','), 'BTR', 1, 'R');

$pdf->SetFont('Arial', 'B', 7);
$pdf->SetX(244);
$pdf->SetFillColor(0);
$pdf->SetTextColor(255);
$pdf->Cell(26,  $altodetalle, "VALOR TOTAL", $bor, 0, 'C', true);
$pdf->Cell(3, $altodetalle, "$ ", 'BT', 0, 'C', true);

$pdf->Cell(19,  $altodetalle, number_format($grantotal, 2, '.', ','), 'BTR', 1, 'R', true);
$pdf->SetTextColor(0);
$pdf->Ln(1);
$bori = 0;
$izq = 8;
$letra = 8;
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
$pdf->Cell(155, $alto, utf8_decode('Precios pueden cambiar sin previo aviso'), $bori, 1, 'L');


$pdf->Cell(155, $alto2, strtoupper(utf8_decode('')), $bori, 1, 'L');

$pdf->SetX($izq);
$pdf->SetFont('Arial', 'B', $letra);
$pdf->Cell(30, $alto, utf8_decode(""), $bori, 0, 'R');
$pdf->SetFont('Arial', '', $letra);
$pdf->Cell(155, $alto, utf8_decode('Sin más por el momento y confiando recibir una respuesta favorable, quedo atent@ a sus comentarios.'), $bori, 1, 'L');

$pdf->Cell(155, $alto2, strtoupper(utf8_decode('')), $bori, 1, 'L');
$pdf->SetX($izq);
$pdf->SetFont('Arial', 'B', $letra);
$pdf->Cell(30, $alto, utf8_decode(""), $bori, 0, 'R');
$pdf->SetFont('Arial', '', $letra);
$pdf->Cell(155, $alto, utf8_decode('Saludos cordiales,'), $bori, 1, 'L');

$pdf->Cell(155, $alto2, strtoupper(utf8_decode('')), $bori, 1, 'L');
$pdf->SetX($izq);
$pdf->SetFont('Arial', 'B', $letra);
$pdf->Cell(30, $alto, utf8_decode(""), $bori, 0, 'R');
$pdf->SetFont('Arial', 'B', $letra);
$pdf->Cell(155, $alto, utf8_decode($usuario['nombre'] . " " . $usuario['apellidos']), $bori, 1, 'L');

$pdf->SetX($izq);
$pdf->SetFont('Arial', 'B', $letra);
$pdf->Cell(30, $alto, utf8_decode(""), $bori, 0, 'R');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(155, $alto, utf8_decode($usuario['correo'] . "    -     " . $usuario['telefono']), $bori, 1, 'L');



$pdf->SetX($izq);
$pdf->SetFont('Arial', 'B', 7);



$pdf->Output();
