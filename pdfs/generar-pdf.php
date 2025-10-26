<?php
// Este archivo puede ser usado directamente
function generarPDF($factura, $cae_data) {
    // Incluir la biblioteca HTML2PDF
    require_once('html2pdf/html2pdf.class.php');

    try {
        // Inicializar HTML2PDF
        $html2pdf = new HTML2PDF('P', 'A4', 'es', true, 'UTF-8');
        $html2pdf->setDefaultFont('helvetica');
        
        // Determinar qué plantilla usar
        $plantilla = $factura['plantilla'] ?? 'templates/revisionalpha/30716710072_01_0001.php';
        
        if (file_exists($plantilla)) {
            // Capturar la salida de la plantilla
            ob_start();
            include($plantilla);
            $content = ob_get_clean();
            
            // Generar el PDF
            $html2pdf->writeHTML($content);
            
            // Nombre del archivo
            $prefijo = ($factura['factura_tipo'] == 'A') ? 'COMPROBANTE A' : 'COMPROBANTE';
            $nombreArchivo = $prefijo . ' Nº ' . 
                            str_pad($factura['numero_talonario'], 4, '0', STR_PAD_LEFT) . '-' . 
                            str_pad($factura['numero_factura'], 8, '0', STR_PAD_LEFT) . '.PDF';
            
            // Salida del PDF
            $html2pdf->Output($nombreArchivo, 'I');
            return true;
        } else {
            return "Error: Plantilla no encontrada: " . $plantilla;
        }
    } catch (HTML2PDF_exception $e) {
        return $e->getMessage();
    }
}

// Si este archivo se ejecuta directamente, verificar parámetros
if (!isset($factura) || !isset($cae_data)) {
    echo "Error: Datos insuficientes para generar el PDF";
    exit;
}
?>
