<?php


include_once './controllers/OrdenesController.php';
include_once './utils/convertirnumeroletra.php';
$contOrdenes = new OrdenesController();
$idOrden = $_GET['idOrden'];

if (isset($idOrden)) {

    $respPro = $contOrdenes->obtenerOrden($idOrden);
    if ($respPro->exito) {
        $proveedor = $respPro->registros[0]['proveedor'];

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

    public  $bor = 0;



    function Header()
    {
        // Logo
        $this->Image('./imagenes/logo.jpg', 10, 6, 30, 15);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 15);
        // Move to the right
        $this->Cell(130);
        // Title
        $this->Cell(30, 10, utf8_decode('ORDEN DE COMPRA'), 0, 0, 'C');
        // Line break
        $this->Ln(3);
        $this->SetFont('Arial', '', 10);
        $this->SetXY(180, 20);
        $this->Cell(50, 7, utf8_decode('NO.'), $this->bor, 0, 'R');
        $this->Cell(40, 7, strtoupper(utf8_decode(str_pad($this->idOrden, 5, "0", STR_PAD_LEFT))), $this->bor, 0, 'R');
        $this->SetXY(180, 27);
        $this->Cell(50, 7, utf8_decode('FECHA DE ELABORACION:'), $this->bor, 0, 'R');
        $this->Cell(40, 7, date("Y-m-d"), $this->bor, 0, 'R');
        $this->SetXY(10, 39);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 7, utf8_decode('PROVEEDOR:'), $this->bor, 0, 'R');
        $this->SetFont('Arial', '', 10);
        $this->Cell(40, 7, strtoupper(utf8_decode($this->proveedor)), $this->bor, 0, 'L');
        $this->SetXY(180, 34);
        $this->Cell(50, 7, utf8_decode('FECHA REQUERIDA:'), $this->bor, 0, 'R');
        $this->Cell(40, 7, $this->fechaRequerida, $this->bor, 0, 'R');
        $this->SetXY(10, 46);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 7, utf8_decode('SOLICITA:'), $this->bor, 0, 'R');
        $this->SetFont('Arial', '', 10);
        $this->Cell(200, 7, strtoupper(utf8_decode($this->usuario)), $this->bor, 0, 'L');
        $this->SetXY(10, 53);
        $this->SetXY(5, 70);
        $this->SetFont('Arial', 'b', 6);
        $this->Cell(10, 10, utf8_decode('#'), 1, 0, 'C');
        $this->Cell(175, 10, utf8_decode('CONCEPTO'), 1, 0, 'C');
        $this->Cell(12, 10, utf8_decode('CANT'), 1, 0, 'C');
        $this->Cell(12, 10, utf8_decode('UNIDAD'), 1, 0, 'C');
        $this->SetXY(214, 70);
        $this->MultiCell(18, 5, utf8_decode('PESO TEÓRICO'), 1, 'C');
        $this->SetXY(232, 70);
        $this->MultiCell(18, 5, utf8_decode('PRECIO X UNIDAD'), 1, 'C');
        $this->SetXY(250, 70);
        $this->Cell(20, 10, utf8_decode('MONTO'), 1, 0, 'C');


        $this->Ln(10);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Instanciation of inherited class
$bor = 1;
$pdf = new PDF();
$pdf->idOrden = $idOrden;
$pdf->proveedor = $proveedor;
$pdf->fechaRequerida = $fechaRequerida;
$pdf->usuario = $usuario;

$pdf->AliasNbPages();
$pdf->AddPage('L');
$pdf->SetFont('Arial', '', 6);
$contador = 1;


foreach ($productosOrden as $kit) {
    $calibre = str_replace("&quot;", "\"", $kit['calibre']);
    $pdf->SetX(5);
    $pdf->Cell(10, 6, $contador, $bor, 0, 'C');
    $pdf->Cell(175, 6, strtoupper(utf8_decode($kit['producto'] . " " . $calibre . " " . $kit['tipo'])), $bor, 0, 'C');
    $pdf->Cell(12, 6, strtoupper(utf8_decode($kit['cantidad'])), $bor, 0, 'C');
    $pdf->Cell(12, 6, strtoupper(utf8_decode($kit['unidad'])), $bor, 0, 'C');
    if ($kit['pesoTeorico'] != "") {
        $pdf->Cell(18, 6, number_format($kit['pesoTeorico'], 2, '.', ''), $bor, 0, 'C');
    } else {
        $pdf->Cell(18, 6, "", $bor, 0, 'C');
    }
    $pdf->Cell(18, 6, "$ " . number_format($kit['precioUnidadPeso'], 2, '.', ''), $bor, 0, 'C');
    $pdf->Cell(20, 6, "$ " . number_format($kit['precio'], 2, '.', ','), $bor, 1, 'C');
    $contador++;
}
$pdf->SetX(232);
$pdf->Cell(18, 6, "TOTAL", $bor, 0, 'C');
$pdf->Cell(20, 6, "$ " . number_format($total, 2, '.', ','), $bor, 1, 'C');

$pdf->Ln(15);
$bori = 0;
$izq = 8;
$letras = new CifrasEnLetras();
$pdf->SetFont('Arial', '', 7);
$pdf->SetX($izq);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(45, 6, utf8_decode("COMENTARIOS:"), $bori, 0, 'R');
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(155, 6, strtoupper(utf8_decode($comentarios)), $bori, 1, 'L');


$pdf->Output();
