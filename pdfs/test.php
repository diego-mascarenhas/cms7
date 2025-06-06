<?php
// Include the HTML2PDF library
require_once('html2pdf/html2pdf.class.php');

try
{
    // Initialize HTML2PDF with Portrait orientation, A4 format, Spanish language
    $html2pdf = new HTML2PDF('P', 'A4', 'es', true, 'UTF-8');
    
    // Set default font
    $html2pdf->setDefaultFont('Arial');
    
    // Add HTML content
    $html2pdf->writeHTML('<h1 style="text-align: center; margin-top: 100px;">Hola Mundo!</h1>');
    
    // Output the PDF (D: force download, I: display in browser)
    $html2pdf->Output('hola-mundo.pdf', 'I');
}
catch(HTML2PDF_exception $e)
{
    echo $e->getMessage();
}
