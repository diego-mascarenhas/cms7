<?php
// Habilitamos la visualización de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Intentar cargar la nueva versión de HTML2PDF
require_once __DIR__ . '/html2pdf/src/Html2Pdf.php';
require_once __DIR__ . '/html2pdf/src/Exception/Html2PdfException.php';
require_once __DIR__ . '/html2pdf/src/Exception/ExceptionFormatter.php';

try {
    // HTML sencillo para nuestro PDF
    $html = '
    <page backtop="10mm" backbottom="10mm" backleft="10mm" backright="10mm">
        <h1 style="text-align: center; color: #336699;">¡Hola Mundo!</h1>
        <p style="text-align: center; font-size: 14pt;">Esta es una prueba con la nueva biblioteca HTML2PDF</p>
        <p style="text-align: center;">Fecha y hora: ' . date('d/m/Y H:i:s') . '</p>
        
        <div style="text-align: center; margin-top: 30mm;">
            <h2 style="color: #339933;">La biblioteca funciona correctamente</h2>
        </div>
    </page>';
    
    // Crear una instancia de la nueva clase Html2Pdf
    $pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'es', true, 'UTF-8');
    
    // Escribir el HTML
    $pdf->writeHTML($html);
    
    // Enviar el PDF al navegador
    $pdf->Output('hola_mundo.pdf', 'I');
    
} catch (\Spipu\Html2Pdf\Exception\Html2PdfException $e) {
    echo '<h1>Error al generar el PDF</h1>';
    echo '<pre>' . $e->getMessage() . '</pre>';
    
    // Si hay un error, comprobar si es necesario incluir más archivos
    echo '<h2>Verificando estructura de archivos:</h2>';
    
    $srcDir = __DIR__ . '/html2pdf/src';
    if (is_dir($srcDir)) {
        echo '<p>Directorio src encontrado: ' . $srcDir . '</p>';
        $files = scandir($srcDir);
        echo '<pre>Archivos en src: ' . print_r($files, true) . '</pre>';
    } else {
        echo '<p>Directorio src no encontrado: ' . $srcDir . '</p>';
    }
    
    // Intentar el autoloader
    $autoloader = __DIR__ . '/html2pdf/vendor/autoload.php';
    if (file_exists($autoloader)) {
        echo '<p>Autoloader encontrado. Intentando cargar...</p>';
        require_once $autoloader;
        
        try {
            $pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'es');
            echo '<p>¡Autoloader funcionó! La clase Html2Pdf está disponible.</p>';
        } catch (Exception $e) {
            echo '<p>Error al usar autoloader: ' . $e->getMessage() . '</p>';
        }
    } else {
        echo '<p>Autoloader no encontrado: ' . $autoloader . '</p>';
    }
    
} catch (Exception $e) {
    echo '<h1>Error general</h1>';
    echo '<pre>' . $e->getMessage() . '</pre>';
}
?> 