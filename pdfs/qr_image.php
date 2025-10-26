<?php
// Include necessary files
require_once('html2pdf/_tcpdf_5.0.002/tcpdf.php');
require_once('html2pdf/_tcpdf_5.0.002/qrcode.php');

// Get the QR data from URL parameter
$data = isset($_GET['data']) ? $_GET['data'] : '';
if (empty($data)) {
    // Default test data if none provided
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
    $data = "https://www.afip.gob.ar/fe/qr/?p=" . base64_encode(json_encode($test_data));
}

// Create QR code object
$qrcode = new QRcode($data, 'L');

// Get the QR code as PNG image
$qr_image = $qrcode->getBarcodeImage(10, 10, array(0,0,0));

// Set content type header
header('Content-Type: image/png');

// Output the image
imagepng($qr_image);
imagedestroy($qr_image);
?> 