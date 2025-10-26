<?php
// Habilitamos la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Contenido HTML simplificado para el PDF
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Factura Simple</title>
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            margin: 20mm;
        }
        h1 {
            font-size: 24pt;
            text-align: center;
            margin-bottom: 10mm;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10mm;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 5pt;
        }
        th {
            background-color: #f2f2f2;
        }
        .totals {
            text-align: right;
            margin-top: 10mm;
        }
    </style>
</head>
<body>
    <h1>FACTURA SIMPLIFICADA</h1>
    
    <p><strong>Fecha:</strong> ' . date('d/m/Y') . '</p>
    <p><strong>Empresa:</strong> Revision Alpha S.A.S.</p>
    <p><strong>CUIT:</strong> 30-71671007-2</p>
    <p><strong>Cliente:</strong> Sio Technology SRL</p>
    
    <table>
        <tr>
            <th>Descripción</th>
            <th>Importe</th>
        </tr>
        <tr>
            <td>Plan de Hosting Enterprise</td>
            <td>$14.250,00</td>
        </tr>
        <tr>
            <td>Plan de Hosting Premium</td>
            <td>$23.970,00</td>
        </tr>
    </table>
    
    <div class="totals">
        <p><strong>Subtotal:</strong> $38.220,00</p>
        <p><strong>Descuento:</strong> $9.588,00</p>
        <p><strong>IVA 21%:</strong> $6.012,72</p>
        <p><strong>Importe Total:</strong> $34.644,72</p>
    </div>
    
    <p><strong>CAE N°:</strong> 74232210882731</p>
</body>
</html>';

// Función para crear un PDF básico usando FPDF (que debería estar incluido en el servidor)
function generarPDFBasico($html, $filename = 'factura.pdf') {
    // Intentar usar la biblioteca actual
    if (class_exists('HTML2PDF')) {
        try {
            require_once 'html2pdf/html2pdf.class.php';
            $html2pdf = new HTML2PDF('P', 'A4', 'es', true, 'UTF-8');
            $html2pdf->writeHTML($html);
            $html2pdf->Output($filename, 'I');
            return true;
        } catch (Exception $e) {
            echo "<p>Error con HTML2PDF: " . $e->getMessage() . "</p>";
        }
    }
    
    // Si no funciona HTML2PDF, crear un PDF simple con texto
    if (class_exists('FPDF')) {
        try {
            require_once('fpdf/fpdf.php');
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(40, 10, 'FACTURA SIMPLIFICADA');
            $pdf->Ln(10);
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(40, 10, 'Fecha: ' . date('d/m/Y'));
            $pdf->Ln(10);
            $pdf->Cell(40, 10, 'Empresa: Revision Alpha S.A.S.');
            $pdf->Ln(10);
            $pdf->Cell(40, 10, 'Cliente: Sio Technology SRL');
            $pdf->Ln(15);
            $pdf->Cell(40, 10, 'Importe Total: $34.644,72');
            $pdf->Ln(10);
            $pdf->Cell(40, 10, 'CAE N°: 74232210882731');
            $pdf->Output('I', $filename);
            return true;
        } catch (Exception $e) {
            echo "<p>Error con FPDF: " . $e->getMessage() . "</p>";
        }
    }
    
    return false;
}

// Intentar generar el PDF
if (!generarPDFBasico($html)) {
    // Si no se puede generar el PDF, mostrar el HTML directamente
    echo '<!DOCTYPE html>
    <html>
    <head>
        <title>Vista HTML de Factura</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
            .container { max-width: 800px; margin: 0 auto; }
            .alert { background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
            .preview { border: 1px solid #ddd; padding: 20px; margin-top: 20px; }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="alert">
                <h3>No se pudo generar el PDF</h3>
                <p>Las bibliotecas de generación de PDF no están disponibles o son incompatibles.</p>
                <p>Se muestra el contenido HTML a continuación.</p>
            </div>
            
            <div class="preview">
                ' . $html . '
            </div>
        </div>
    </body>
    </html>';
}
?> 