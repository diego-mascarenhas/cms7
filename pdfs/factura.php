<?php
// Include the HTML2PDF library
require_once('html2pdf/html2pdf.class.php');

// Sample data that would normally come from a form or database
$_POST = [
    'numero_talonario' => '0001',
    'numero_factura' => '00000123',
    'vencimiento' => '30/11/2023',
    'fecha' => date('d/m/Y'),
    'razon_social' => 'Cliente Ejemplo S.A.',
    'domicilio' => 'Av. Corrientes 1234, CABA',
    'condicion_iva' => 'Responsable Inscripto',
    'id_documento_tipo' => '80',
    'documento_numero' => '30123456789',
    'items' => json_encode([
        ['descripcion' => 'Servicio de desarrollo web', 'valor' => 50000],
        ['descripcion' => 'Mantenimiento mensual', 'valor' => 25000],
    ]),
    'bruto' => 75000,
    'descuento' => 0,
    'subtotal210' => 75000,
    'imp210' => 15750,
    'total_neto' => 90750,
    'CAE' => '71234567890123',
    'CAEFchVto' => '10/12/2023',
    'numeroCodigoBarras' => '307167100720001000001237123456789012320231210'
];

try
{
    // Initialize HTML2PDF with Portrait orientation, A4 format, Spanish language
    $html2pdf = new HTML2PDF('P', 'A4', 'es', true, 'UTF-8');
    
    // Set default font
    $html2pdf->setDefaultFont('Arial');
    
    // Buffer the template output
    ob_start();
    include('templates/revisionalpha/30716710072_01_0001.php');
    $content = ob_get_clean();
    
    // Add the template content to the PDF
    $html2pdf->writeHTML($content);
    
    // Output the PDF (D: force download, I: display in browser)
    $html2pdf->Output('factura-ejemplo.pdf', 'I');
}
catch(HTML2PDF_exception $e)
{
    echo $e->getMessage();
}
