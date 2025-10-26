<?php
// Test script to render an invoice template with AFIP QR code
require_once('html2pdf/html2pdf.class.php');

// Set template path
$template_path = 'templates/revisionalpha/30716710072_01_0001.php';

// Create sample invoice data
$_POST = array(
    'numero_talonario' => '0001',
    'numero_factura' => '00000123',
    'vencimiento' => '2024-07-30',
    'fecha' => '2024-06-30',
    'razon_social' => 'EMPRESA DE PRUEBA S.A.',
    'domicilio' => 'Av. Siempreviva 742, Springfield',
    'id_documento_tipo' => '80',
    'documento_numero' => '20111111112',
    'condicion_iva' => 'Responsable Inscripto',
    'bruto' => 10000,
    'descuento' => 0,
    'SUBTOTAL210' => 10000,
    'IMP210' => 2100,
    'total_neto' => 12100,
    'CAE' => '74492344531527',
    'CAEFchVto' => '2024-07-10',
    'numeroCodigoBarras' => '307167100720001001234742300000123720240710',
    'items' => json_encode([
        (object)[
            'descripcion' => 'Servicio de consultoría - Junio 2024',
            'valor' => 10000
        ]
    ])
);

// Create AFIP QR code data
$codigoqr = array(
    'ver' => 1,
    'fecha' => '2024-06-30',
    'cuit' => 30716710072,
    'ptoVta' => 1,
    'tipoCmp' => 1,
    'nroCmp' => 123,
    'importe' => 12100.00,
    'moneda' => 'PES',
    'ctz' => 1,
    'tipoDocRec' => 80,
    'nroDocRec' => 20111111112,
    'tipoCodAut' => 'E',
    'codAut' => 74492344531527
);

// Generate QR URL
$_POST['codigoqrjson_base64'] = "https://www.afip.gob.ar/fe/qr/?p=" . base64_encode(json_encode($codigoqr));

// Start output buffering to capture template output
ob_start();

// Include the template
include($template_path);

// Get buffered content
$content = ob_get_clean();

try {
    // Initialize HTML2PDF
    $html2pdf = new HTML2PDF('P', 'A4', 'es', true, 'UTF-8', array(10, 10, 10, 10));
    
    // Convert HTML to PDF
    $html2pdf->writeHTML($content);
    
    // Output PDF
    $html2pdf->Output('test_invoice.pdf', 'I');
} catch (HTML2PDF_exception $e) {
    // Display errors
    echo $e;
}
?> 