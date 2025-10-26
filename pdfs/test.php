<?php
// Include the HTML2PDF library
require_once('html2pdf/html2pdf.class.php');
require_once('funciones.php');
require_once('html2pdf/_tcpdf_5.0.002/qrcode.php');

// URL para el código QR - puede ser cualquier página web
$url_web = "https://www.revisionalpha.com";

// Generar el código QR para la URL
$qr_code_html = generarQR($url_web);

try
{
    // Initialize HTML2PDF with Portrait orientation, A4 format, Spanish language
    $html2pdf = new HTML2PDF('P', 'A4', 'es', true, 'UTF-8', array(15, 15, 15, 15));
    
    // Set default font
    $html2pdf->setDefaultFont('helvetica');
    
    // Contenido HTML con el QR
    $html_content = '
    <page>
        <h1 style="text-align: center; margin-top: 50px; font-family: helvetica;">Código QR de ejemplo</h1>
        
        <div style="text-align: center; margin: 40px auto; padding: 20px; border: 1px solid #eee; width: 300px; background-color: white;">
            ' . $qr_code_html . '
        </div>
        
        <p style="text-align: center; margin-top: 20px; font-family: helvetica; color: #333;">
            Este código QR enlaza a: <a href="' . $url_web . '" style="color: #0066cc; text-decoration: none;">' . $url_web . '</a>
        </p>
        
        <p style="text-align: center; margin-top: 30px; font-family: helvetica; color: #666; font-size: 14px;">
            Escanea el código QR con tu teléfono para abrir la página web.
        </p>
    </page>
    ';
    
    // Add HTML content
    $html2pdf->writeHTML($html_content);
    
    // Output the PDF (D: force download, I: display in browser)
    $html2pdf->Output('qr-ejemplo.pdf', 'I');
}
catch(HTML2PDF_exception $e)
{
    echo $e->getMessage();
}
