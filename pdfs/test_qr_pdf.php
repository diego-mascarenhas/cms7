<?php
// Include necessary files
require_once('html2pdf/html2pdf.class.php');
require_once('funciones.php');
require_once('html2pdf/_tcpdf_5.0.002/qrcode.php');

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

// Generar HTML para el PDF (muy simple, solo el QR)
$html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Test QR Code</title>
    <style>
        body { font-family: helvetica; }
        .container { text-align: center; margin: 50px auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>QR Code Test</h1>
        <p>This is a test QR code for AFIP invoices</p>
        <div style="margin: 20px auto;">
            ' . generarQR($qr_url) . '
        </div>
        <p>QR Code URL: ' . $qr_url . '</p>
    </div>
</body>
</html>';

try {
    // Initialize HTML2PDF
    $html2pdf = new HTML2PDF('P', 'A4', 'es');
    
    // Set default font
    $html2pdf->setDefaultFont('helvetica');
    
    // Write HTML to PDF
    $html2pdf->writeHTML($html);
    
    // Output PDF
    $html2pdf->Output('test_qr.pdf', 'I');
} catch (HTML2PDF_exception $e) {
    echo $e->getMessage();
}
?> 