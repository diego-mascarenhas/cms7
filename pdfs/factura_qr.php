<?php
// Include necessary files
require_once('html2pdf/_tcpdf_5.0.002/tcpdf_barcodes_2d.php');

// Get the QR data from URL parameter
$data = isset($_GET['data']) ? $_GET['data'] : '';
if (empty($data)) {
    die('No QR data provided');
}

// Create QR code object
$barcode = new TCPDF2DBarcode($data, 'QRCODE,L');

// Set content type header
header('Content-Type: image/png');
header('Cache-Control: max-age=86400'); // Cache for 24 hours

// Output the image
echo $barcode->getBarcodePngData(4, 4);
?> 