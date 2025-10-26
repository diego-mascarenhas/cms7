<?php
// Incluimos archivos necesarios
require_once('config.php');
require_once('funciones.php');
require_once('html2pdf/_tcpdf_5.0.002/qrcode.php');

// Datos de ejemplo para el QR
$datos = array(
    'ver' => 1,
    'fecha' => date("Y-m-d"),
    'cuit' => 30716710072,
    'ptoVta' => 1,
    'tipoCmp' => 1,
    'nroCmp' => 12345,
    'importe' => 1000.00,
    'moneda' => 'PES',
    'ctz' => 1,
    'tipoDocRec' => 80,
    'nroDocRec' => 30123456789,
    'tipoCodAut' => 'E',
    'codAut' => 12345678901234
);

// Directorio donde guardar QR
$qr_dir = '/home/forge/cms.revisionalpha.com/pdfs/qr_images/';

// Para desarrollo, usamos una ruta relativa si la absoluta no existe
if (!file_exists($qr_dir)) {
    $qr_dir = 'qr_images/';
}

// Generar QR HTML
echo "<h1>Test QR como HTML</h1>";
echo generarQR($datos);

// Generar QR como imagen guardada
echo "<h1>Test QR como imagen guardada</h1>";
$html_img = generarQR($datos, 'png', $qr_dir);
echo $html_img;

// Mostrar información del directorio
echo "<h2>Información del directorio</h2>";
echo "<p>Directorio: " . realpath($qr_dir) . "</p>";

if (is_dir($qr_dir)) {
    echo "<p>El directorio existe.</p>";
    
    // Listar archivos
    echo "<h3>Archivos en el directorio:</h3>";
    echo "<ul>";
    $files = scandir($qr_dir);
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            echo "<li>" . $file . " - " . filesize($qr_dir . '/' . $file) . " bytes</li>";
        }
    }
    echo "</ul>";
    
    // Verificar permisos
    echo "<p>Permisos del directorio: " . substr(sprintf('%o', fileperms($qr_dir)), -4) . "</p>";
} else {
    echo "<p>El directorio NO existe.</p>";
    echo "<p>Intentando crear el directorio...</p>";
    
    if (mkdir($qr_dir, 0755, true)) {
        echo "<p>Directorio creado con éxito.</p>";
    } else {
        echo "<p>Error al crear el directorio.</p>";
    }
} 