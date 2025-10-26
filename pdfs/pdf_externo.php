<?php
// Habilitamos la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// HTML simple para la factura
$html = '<html><body style="font-family: Arial, sans-serif; font-size: 12pt; padding: 20px;">
<h1 style="text-align: center;">FACTURA SIMPLIFICADA</h1>
<p><strong>Fecha:</strong> ' . date('d/m/Y') . '</p>
<p><strong>Empresa:</strong> Revision Alpha S.A.S.</p>
<p><strong>CUIT:</strong> 30-71671007-2</p>
<p><strong>Cliente:</strong> Sio Technology SRL</p>
<hr/>
<h3>Detalle de servicios:</h3>
<ul>
    <li>Plan de Hosting Enterprise: $14.250,00</li>
    <li>Plan de Hosting Premium: $23.970,00</li>
</ul>
<hr/>
<p><strong>Subtotal:</strong> $38.220,00</p>
<p><strong>Descuento:</strong> $9.588,00</p>
<p><strong>IVA 21%:</strong> $6.012,72</p>
<p><strong>Importe Total:</strong> $34.644,72</p>
<p><strong>CAE N°:</strong> 74232210882731</p>
</body></html>';

// URL para convertir HTML a PDF usando un servicio gratuito
$apiUrl = 'https://api.html2pdf.app/v1/generate';

// Configuración para la solicitud
$data = [
    'html' => $html,
    'apiKey' => 'free', // Clave gratuita
    'options' => [
        'margin' => '15mm',
        'filename' => 'factura.pdf'
    ]
];

// Intentar generar el PDF usando el servicio
function generarPDFExterno($apiUrl, $data) {
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        // El servicio devuelve directamente el PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="factura.pdf"');
        echo $response;
        return true;
    }
    
    return false;
}

// Intentar generar el PDF con el servicio externo
if (!generarPDFExterno($apiUrl, $data)) {
    // Si no se puede generar el PDF, mostrar el HTML
    echo '<html>
    <head>
        <title>Vista HTML de Factura</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; }
            .error { color: red; background: #ffeeee; padding: 10px; border: 1px solid red; }
        </style>
    </head>
    <body>
        <div class="error">
            <p>No se pudo generar el PDF usando el servicio externo.</p>
        </div>
        <hr/>
        ' . $html . '
    </body>
    </html>';
}
?> 