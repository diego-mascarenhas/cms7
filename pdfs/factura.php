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

// Add custom fonts and styling based on revisionalpha brand styles
$customStyles = <<<EOD
<style type="text/css">
    @font-face {
        font-family: 'proxima-l';
        src: url('https://cms.revisionalpha.com/assets/fonts/proxima-nova-light.otf') format('opentype');
        font-weight: normal;
        font-style: normal;
    }
    
    @font-face {
        font-family: 'proxima-r';
        src: url('https://cms.revisionalpha.com/assets/fonts/proxima-nova-regular.otf') format('opentype');
        font-weight: normal;
        font-style: normal;
    }
    
    @font-face {
        font-family: 'proxima-sb';
        src: url('https://cms.revisionalpha.com/assets/fonts/proxima-nova-semibold.otf') format('opentype');
        font-weight: normal;
        font-style: normal;
    }
    
    @font-face {
        font-family: 'proxima-b';
        src: url('https://cms.revisionalpha.com/assets/fonts/proxima-nova-bold.otf') format('opentype');
        font-weight: normal;
        font-style: normal;
    }
    
    .tw-light { font-family: 'proxima-l'; }
    .tw-regular { font-family: 'proxima-r'; }
    .tw-bold { font-family: 'proxima-b'; }
    .tw-semibold, strong { font-family: 'proxima-sb'; font-weight: normal; }
    
    .tc-red-5 { color: #FF1A1D !important; }
    .bc-red-5 { background-color: #FF1A1D !important; }
    
    h1, h2, h3, h4 { font-family: 'proxima-sb'; }
    body { font-family: 'proxima-r'; color: #808080; }
    
    .section-title { display: block; font-family: 'proxima-l'; font-size: 40px; line-height: 1.3; margin: 0; }
    .section-title.section-title-small { font-size: 26px; line-height: 1.5; }
    .section-title.section-title-full { font-size: 26px; font-family: 'proxima-sb'; border-bottom: 3px solid #FF1A1D; text-transform: uppercase; }
    .section-title > span { display: inline-block; border-bottom: 3px solid #FF1A1D; }
    
    .general-title { display: block; font-family: 'proxima-sb'; font-size: 40px; line-height: 1.3; margin: 0; }
    .general-title > span { display: inline-block; border-bottom: 3px solid lightgrey; padding-bottom: 15px; }
    
    .form-subtitle { font-size: 20px; font-family: 'proxima-l'; text-transform: uppercase; margin-bottom: 15px; color: #5CA7D7; }
</style>
EOD;

try
{
    // Initialize HTML2PDF with Portrait orientation, A4 format, Spanish language
    $html2pdf = new HTML2PDF('P', 'A4', 'es', true, 'UTF-8');
    
    // Set default font to helvetica instead of Arial (helvetica is included by default in TCPDF)
    $html2pdf->setDefaultFont('helvetica');
    
    // Buffer the template output with custom styles
    ob_start();
    echo $customStyles;
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
