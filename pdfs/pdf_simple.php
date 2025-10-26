<?php
// Habilitamos la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Contenido HTML para el PDF
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Factura Simplificada</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .info {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .totals {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>FACTURA SIMPLIFICADA</h1>
        <p>Fecha: ' . date('d/m/Y') . '</p>
    </div>
    
    <div class="info">
        <p><strong>Empresa:</strong> Revision Alpha S.A.S.</p>
        <p><strong>CUIT:</strong> 30-71671007-2</p>
        <p><strong>Dirección:</strong> Vuelta de Obligado 2443 Of. 403, CABA</p>
    </div>
    
    <div class="info">
        <p><strong>Cliente:</strong> Sio Technology SRL</p>
        <p><strong>CUIT:</strong> 30708397268</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Descripción</th>
                <th>Importe</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Plan de Hosting Enterprise TEXLO.COM.AR mes 06-2024 al 08-2024.</td>
                <td>$14.250,00</td>
            </tr>
            <tr>
                <td>Plan de Hosting Premium SIOCONSULTING.COM.AR mes 06-2024 al 08-2024.</td>
                <td>$23.970,00</td>
            </tr>
        </tbody>
    </table>
    
    <div class="totals">
        <p><strong>Subtotal:</strong> $38.220,00</p>
        <p><strong>Descuento:</strong> $9.588,00</p>
        <p><strong>Subtotal con descuento:</strong> $28.632,00</p>
        <p><strong>IVA 21%:</strong> $6.012,72</p>
        <p><strong>Importe Total:</strong> $34.644,72</p>
    </div>
    
    <div class="info">
        <p><strong>CAE N°:</strong> 74232210882731</p>
        <p><strong>Vto. de CAE:</strong> 11/06/2024</p>
    </div>
</body>
</html>
';

// Opciones para la generación del PDF (formato JSON)
$options = json_encode([
    'format' => 'A4',
    'orientation' => 'portrait',
    'margin' => [
        'top' => '10mm',
        'right' => '10mm',
        'bottom' => '10mm',
        'left' => '10mm'
    ]
]);

// Usaremos un servicio de generación de PDF en línea
// Opciones del servicio API2PDF
$apiKey = ''; // Reemplaza con tu API key si la tienes
$endpoint = 'https://v2.api2pdf.com/chrome/html';

// Si no hay API key, mostraremos el HTML directamente
if (empty($apiKey)) {
    // Verificar si se solicita descarga o visualización
    if (isset($_GET['download']) && $_GET['download'] === '1') {
        // Intentar generar PDF con dompdf directamente
        if (class_exists('Dompdf\Dompdf')) {
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="factura_simple.pdf"');
            echo $dompdf->output();
            exit;
        } else {
            // Si no hay dompdf, descarga como HTML
            header('Content-Type: text/html; charset=utf-8');
            header('Content-Disposition: attachment; filename="factura_simple.html"');
            echo $html;
            exit;
        }
    }
    
    // Si no se solicita descarga, mostrar HTML
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html>
    <html>
    <head>
        <title>Factura HTML</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <div style="max-width: 800px; margin: 0 auto; padding: 20px;">
            <div style="text-align: center; margin-bottom: 20px;">
                <h2>Vista previa HTML (no PDF)</h2>
                <p>No se ha configurado un servicio de generación de PDF</p>
                <p><a href="?download=1" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 4px;">Descargar HTML</a></p>
            </div>
            <div style="border: 1px solid #ddd; padding: 20px; border-radius: 5px;">' 
            . $html . 
            '</div>
        </div>
    </body>
    </html>';
    exit;
}

// Si hay una API key, usar el servicio de API2PDF
$payload = [
    'html' => $html,
    'apiKey' => $apiKey,
    'options' => json_decode($options, true)
];

$ch = curl_init($endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200) {
    $result = json_decode($response, true);
    
    if (isset($result['pdf']) && !empty($result['pdf'])) {
        // Redireccionar al PDF generado
        header('Location: ' . $result['pdf']);
        exit;
    }
}

// Si falló la generación con API2PDF, mostrar el HTML
header('Content-Type: text/html; charset=utf-8');
echo '<!DOCTYPE html>
<html>
<head>
    <title>Factura HTML</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <div style="max-width: 800px; margin: 0 auto; padding: 20px;">
        <div style="text-align: center; margin-bottom: 20px;">
            <h2>Vista previa HTML (no PDF)</h2>
            <p>No se pudo generar el PDF: ' . ($httpCode !== 200 ? 'Error HTTP ' . $httpCode : 'Respuesta inválida del servidor') . '</p>
            <p><a href="?download=1" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 4px;">Descargar HTML</a></p>
        </div>
        <div style="border: 1px solid #ddd; padding: 20px; border-radius: 5px;">' 
        . $html . 
        '</div>
    </div>
</body>
</html>';
?> 