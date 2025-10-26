<?php
// Include necessary files - use TCPDF directly
require_once('html2pdf/_tcpdf_5.0.002/tcpdf.php');

// Crear un código QR de prueba con datos fijos
$test_data = array(
    'ver' => 1,
    'fecha' => '2024-05-30',
    'cuit' => 30716710072,
    'ptoVta' => 1,
    'tipoCmp' => 1,
    'nroCmp' => 1234,
    'importe' => 10000.00,
    'moneda' => 'PES',
    'ctz' => 1,
    'tipoDocRec' => 0,
    'nroDocRec' => 27066460199,
    'tipoCodAut' => 'E',
    'codAut' => 74492344531527
);

// URL del QR
$qr_url = "https://www.afip.gob.ar/fe/qr/?p=" . base64_encode(json_encode($test_data));

// Create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('Revision Alpha');
$pdf->SetAuthor('Revision Alpha');
$pdf->SetTitle('QR Code Test');
$pdf->SetSubject('AFIP QR Code');

// Remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Set font
$pdf->SetFont('helvetica', '', 12);

// Add a page
$pdf->AddPage();

// Set some content
$html = '<h1 style="text-align:center;">QR Code Test</h1>
<p style="text-align:center;">This is a test QR code for AFIP invoices using TCPDF native functionality</p>';

// Output HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// Generate QR code directly with TCPDF
// The 3rd parameter is error correction level: L=7%, M=15%, Q=25%, H=30%
// The 4th parameter is the QR code size
// The 5th parameter is the frame size (margin)
$style = array(
    'border' => 2,
    'vpadding' => 'auto',
    'hpadding' => 'auto',
    'fgcolor' => array(0, 0, 0),
    'bgcolor' => array(255, 255, 255),
    'module_width' => 1, // width of a single module in points
    'module_height' => 1 // height of a single module in points
);

// Center position
$x = 70;
$y = 100;

// Add QR Code
$pdf->write2DBarcode($qr_url, 'QRCODE,L', $x, $y, 70, 70, $style, 'N');

// Add URL text below QR code
$pdf->SetY($y + 75);
$pdf->Cell(0, 10, 'QR Code URL:', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 8);
$pdf->MultiCell(0, 10, $qr_url, 0, 'C');

// Close and output PDF document
$pdf->Output('test_qr_tcpdf.pdf', 'I');
?> 