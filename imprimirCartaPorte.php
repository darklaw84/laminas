<?php


include_once './controllers/CotizacionController.php';
include_once './utils/convertirnumeroletra.php';
$contCotizaciones = new CotizacionController();
$idRemision = $_GET['idRemision'];
$conPeso = $_GET['conPeso'];


if (isset($idRemision)) {



    $respPro = $contCotizaciones->obtenerRemision($idRemision);
    if ($respPro->exito) {


        $idCotizacion = $respPro->registros[0]['idPedido'];
        $placas = $respPro->registros[0]['placas'];
        $operador = $respPro->registros[0]['operador'];
        $tipoUnidad = $respPro->registros[0]['tipoUnidad'];
        $contenedor = $respPro->registros[0]['contenedor'];

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
        $nombreUsuario = $respPro->registros[0]['nombreUsuario'];
        $apellidos = $respPro->registros[0]['apellidos'];


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
    public $nombreUsuario;
    public $apellidos;

    public $direccion;
    public $conPeso;
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
        $this->Cell(30, 10, utf8_decode('CARTA PORTE'), 0, 0, 'C');
        // Line break
        $this->Ln(3);
        $this->SetFont('Arial', '', 10);
        $this->SetXY(200, 20);
        $this->Cell(50, 7, utf8_decode('FOLIO:'), $this->bor, 0, 'R');
        $this->Cell(40, 7, str_pad($this->idRemision, 3, "0", STR_PAD_LEFT), $this->bor, 0, 'R');
        $letras = new CifrasEnLetras();

        $this->SetXY(10, 30);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(60, 7, utf8_decode('LUGAR Y FECHA DE EXPEDICIÓN:'), $this->bor, 0, 'R');
        $this->SetFont('Arial', '', 10);
        $this->Cell(200, 7, strtoupper(utf8_decode('SANTIAGO DE QUERÉTARO A ' . date("d") . " de " . $letras->convertirMes(date('m')) . " de " . date('Y'))), $this->bor, 0, 'L');
        $this->SetXY(5, 41);
        $this->SetTextColor(255, 255, 255);
        $this->SetFillColor(40);
        $this->Cell(143, 7, utf8_decode('Origen'), 1, 0, 'C', true);
        $this->SetFillColor(50);
        $this->Cell(144, 7, utf8_decode('Destino'), 1, 1, 'C', true);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('Arial', 'B', 10);
        $this->SetX(5);
        $this->Cell(23, 7, utf8_decode('Remitente:'), 'L', 0, 'L', false);
        $this->SetFont('Arial', '', 8);
        $this->Cell(118, 7, utf8_decode('CENTRAL DE LÁMINAS QUERÉTARO SA DE CV'), 'B', 0, 'L', false);
        $this->Cell(2, 7, utf8_decode(''), 'R', 0, 'L', false);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(25, 7, utf8_decode('Destinatario:'), 'L', 0, 'L', false);
        $this->SetFont('Arial', '', 8);
        $this->Cell(117, 7, utf8_decode($this->cliente), 'B', 0, 'L', false);
        $this->Cell(2, 7, utf8_decode(''), 'R', 1, 'L', false);

        $this->SetX(5);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(23, 7, utf8_decode('Dirección:'), 'L', 0, 'L', false);
        $this->SetFont('Arial', '', 8);
        $this->Cell(118, 7, utf8_decode('Carr. México-Querétaro Km. 199.500 Lote 84, El Marqués, Querétaro Qro. C.P. 76240'), 'B', 0, 'L', false);
        $this->Cell(2, 7, utf8_decode(''), 'R', 0, 'L', false);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(20, 7, utf8_decode('Dirección:'), 'L', 0, 'L', false);
        $this->SetFont('Arial', '', 8);
        $this->Cell(122, 7, utf8_decode($this->direccionentrega), 'B', 0, 'L', false);
        $this->Cell(2, 7, utf8_decode(''), 'R', 1, 'L', false);



        $this->SetX(5);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(23, 7, utf8_decode('Contacto:'), 'L', 0, 'L', false);
        $this->SetFont('Arial', '', 8);
        $this->Cell(118, 7, utf8_decode($this->nombreUsuario . " " . $this->apellidos), 'B', 0, 'L', false);
        $this->Cell(2, 7, utf8_decode(''), 'R', 0, 'L', false);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(20, 7, utf8_decode('Contacto:'), 'L', 0, 'L', false);
        $this->SetFont('Arial', '', 8);
        $this->Cell(122, 7, utf8_decode($this->representante), 'B', 0, 'L', false);
        $this->Cell(2, 7, utf8_decode(''), 'R', 1, 'L', false);

        $this->SetX(5);
        $this->Cell(287, 2, utf8_decode(''), 'LBR', 1, '', false);

        $this->SetXY(5, 73);
        $this->SetFont('Arial', 'b', 8);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(10, 8, utf8_decode('#'), 1, 0, 'C', true);
        $this->Cell(16, 8, utf8_decode('CANTIDAD'), 1, 0, 'C', true);
        $this->Cell(18, 8, utf8_decode('UM'), 1, 0, 'C', true);
        $this->Cell(161, 8, utf8_decode('DESCRIPCIÓN'), 1, 0, 'C', true); //165

        $this->Cell(27, 8, utf8_decode('MEDIDAS'), 1, 0, 'C', true);

        if ($this->conPeso == "1") {
            $this->Cell(30, 8, utf8_decode('METROS LINEALES'), 1, 0, 'C', true);
            $this->Cell(25, 8, utf8_decode('PESO EN KG'), 1, 0, 'C', true);
        } else {
            $this->Cell(55, 8, utf8_decode('METROS LINEALES'), 1, 0, 'C', true);
        }


        $this->SetTextColor(0, 0, 0);


        $this->Ln(8);
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
$pdf->conPeso = $conPeso;
$pdf->cliente = $respPro->registros[0]['cliente'];
$pdf->uso = $respPro->registros[0]['uso'];
$pdf->direccion = $respPro->registros[0]['direccion'];
$pdf->fechaEntrega = $respPro->registros[0]['fechaEntrega'];
$pdf->representante = $respPro->registros[0]['representante'];
$pdf->direccionentrega = $lugarentrega;
$pdf->telefono = $respPro->registros[0]['telefono'];
$pdf->mail = $respPro->registros[0]['mail'];
$pdf->nombreUsuario = $respPro->registros[0]['nombreUsuario'];
$pdf->apellidos = $respPro->registros[0]['apellidos'];



$pdf->AliasNbPages();
$pdf->AddPage('L');
$pdf->SetFont('Arial', '', 7);
$contador = 1;

$totalMetros = 0;
$kilosTotales = 0;
$cantidadTotal=0;

foreach ($detalleRemision as $reg) {
$cantidadTotal=$cantidadTotal+$reg['cantidad'];
    if (is_numeric($reg['largo'])) {
        $largo = $reg['largo'];
        $largoancho = $reg['largo'] . " " . $reg['ancho'];
        $metrosLineales = $reg['cantidad'] * $largo;
    } else {
        $largo = $reg['metros'];
        $largoancho = $reg['metros'] . " " . $reg['ancho'];
        $metrosLineales = $reg['cantidad'] * $largo;
    }

    if ($reg['metros'] > 0) {
        $totalPartida = $reg['metros'] * $reg['preciounitario'] * $reg['cantidad'];
        $precioPorPieza = $totalPartida / $reg['cantidad'];
    } else {
        $totalPartida = $reg['preciounitario'] * $reg['cantidad'];
        $precioPorPieza = $reg['preciounitario'];
    }
    if ($contador % 2 == 0) {
        $pdf->SetFillColor(230, 230, 230);
    } else {
        $pdf->SetFillColor(255, 255, 255);
    }
    $pdf->SetX(5);
    $pdf->Cell(10, 6, $contador, $bor, 0, 'C', true);
    $pdf->Cell(16, 6, $reg['cantidad'], $bor, 0, 'C', true);
    $pdf->Cell(18, 6, $reg['unidadFactura'], $bor, 0, 'C', true);

    $descripcion = strtoupper($reg['producto'] . " " . $reg['calibre'] . " " . $reg['tipo']);
    $descripcion = str_replace("N/A", "", $descripcion);
    $pdf->Cell(161, 6, utf8_decode(strtoupper($descripcion)), $bor, 0, 'C', true);


    $largoancho = str_replace('&quot;', '"', $largoancho);
    $pdf->Cell(27, 6, strtoupper($largoancho), $bor, 0, 'C', true);

    if ($conPeso == "1") {
        $pdf->Cell(30, 6, number_format($metrosLineales, 2, '.', ','), $bor, 0, 'C', true);

        $pdf->Cell(25, 6, number_format($reg['kilos'], 2, '.', ','), $bor, 1, 'C', true);
    } else {
        $pdf->Cell(55, 6, number_format($metrosLineales, 2, '.', ','), $bor, 1, 'C', true);
    }
    $totalMetros = $totalMetros + $metrosLineales;
    $kilosTotales = $kilosTotales + $reg['kilos'];
    $contador++;
}

for ($i = $contador; $i <= 10; $i++) {
    if ($i % 2 == 0) {
        $pdf->SetFillColor(230, 230, 230);
    } else {
        $pdf->SetFillColor(255, 255, 255);
    }
    $pdf->SetX(5);
    $pdf->Cell(10, 6, '', $bor, 0, 'C', true);
    $pdf->Cell(16, 6, '', $bor, 0, 'C', true);
    $pdf->Cell(18, 6, '', $bor, 0, 'C', true);
    $pdf->Cell(161, 6, '', $bor, 0, 'C', true);



    $pdf->Cell(27, 6, '', $bor, 0, 'C', true);
    if ($conPeso == "1") {
        $pdf->Cell(30, 6, '', $bor, 0, 'C', true);

        $pdf->Cell(25, 6, '', $bor, 1, 'C', true);
    } else {
        $pdf->Cell(55, 6, '', $bor, 1, 'C', true);
    }
}
$pdf->SetX(5);
$pdf->SetFillColor(50);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(10, 6, 'Tot:', $bor, 0, 'C', true);
$pdf->Cell(16, 6, $cantidadTotal, $bor, 0, 'C', true);
$pdf->Cell(18, 6, '', $bor, 0, 'C', true);
$pdf->Cell(161, 6, '', $bor, 0, 'C', true);



$pdf->Cell(27, 6, '', $bor, 0, 'C', true);
$pdf->SetFont('Arial', 'B', 10);

if ($conPeso == "1") {

    $pdf->Cell(30, 6, number_format($totalMetros, 2, '.', ''), $bor, 0, 'C', true);

    $pdf->Cell(25, 6, number_format($kilosTotales, 2, '.', ''), $bor, 1, 'C', true);
} else {
    $pdf->Cell(55, 6, number_format($totalMetros, 2, '.', ''), $bor, 1, 'C', true);
}
$pdf->SetTextColor(0, 0, 0);


$pdf->SetXY(5, 150);
$pdf->Cell(30, 7, utf8_decode('Tipo de Unidad:'), 'LT', 0, 'L', false);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(111, 7, utf8_decode($tipoUnidad), 'BT', 0, 'L', false);
$pdf->Cell(2, 7, utf8_decode(''), 'RT', 0, 'L', false);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(21, 7, utf8_decode('Operador:'), 'LT', 0, 'L', false);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(121, 7, utf8_decode($operador), 'BT', 0, 'L', false);
$pdf->Cell(2, 7, utf8_decode(''), 'RT', 1, 'L', false);



$pdf->SetX(5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 7, utf8_decode('No. Contenedor:'), 'L', 0, 'L', false);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(111, 7, utf8_decode($contenedor), 'B', 0, 'L', false);
$pdf->Cell(2, 7, utf8_decode(''), 'R', 0, 'L', false);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(21, 7, utf8_decode('Placas:'), 'L', 0, 'L', false);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(121, 7, utf8_decode($placas), 'B', 0, 'L', false);
$pdf->Cell(2, 7, utf8_decode(''), 'R', 1, 'L', false);
$pdf->SetX(5);
$pdf->Cell(287, 3, utf8_decode(''), 'RBL', 1, 'L', false);

$pdf->SetX(5);
$pdf->Cell(287, 5, utf8_decode(''), '', 1, 'L', false);

$pdf->SetX(5);
$pdf->Cell(287, 6, utf8_decode('PERMISO PARA LA OPERACIÓN Y EXPLOTACIÓN DE LOS SERVICIOS DE AUTOTRANSPORTE FEDERAL DE CARGA EN CAMINOS Y PUENTES DE JURISDICCIÓN FEDERAL
 DE LA SECRETARIA'), '', 1, 'L', false);
$pdf->SetX(5);
$pdf->Cell(150, 7, utf8_decode('DE COMUNICACIONES Y TRANSPORTES'), '', 0, 'L', false);

$pdf->Cell(30, 7, utf8_decode('REVISADO POR:'), '', 0, 'L', false);
$pdf->Cell(80, 7, utf8_decode(''), 'B', 0, '', false);




$pdf->Ln(1);
$bori = 0;
$izq = 8;
$letra = 10;



$alto = 5;




$pdf->SetX($izq);
$pdf->SetFont('Arial', 'B', 7);



$pdf->Output();
