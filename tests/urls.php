<?php
// Configuración inicial
$imageUrl = 'https://cms.revisionalpha.com/multimedia/thumbs/thumb_860x380-dae1c1b2b89f79bed268a459bd34bc39.png';
$logFile = 'curl_debug.log';
$outputFile = 'downloaded_image.png';

// Inicializar cURL
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL => $imageUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_VERBOSE => true,        // Habilita logging detallado
    CURLOPT_STDERR => fopen($logFile, 'w+'),  // Guarda log en archivo
    CURLOPT_HEADER => true,          // Incluir headers en la salida
    CURLOPT_FOLLOWLOCATION => true,  // Seguir redirecciones
    CURLOPT_SSL_VERIFYPEER => false, // Solo para testing (no usar en producción)
    CURLOPT_FAILONERROR => true,     // Fallar en códigos HTTP >=400
    CURLOPT_BINARYTRANSFER => true   // Para transferencia binaria
]);

// Ejecutar la solicitud
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    // Registrar error de cURL
    file_put_contents($logFile, "\n\ncURL ERROR: " . curl_error($ch), FILE_APPEND);
} elseif ($httpCode >= 400) {
    // Registrar error HTTP
    file_put_contents($logFile, "\n\nHTTP ERROR: $httpCode", FILE_APPEND);
} else {
    // Extraer cuerpo de la respuesta (omitir headers)
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $imageData = substr($response, $headerSize);
    
    // Guardar imagen
    file_put_contents($outputFile, $imageData);
    file_put_contents($logFile, "\n\nIMAGEN DESCARGADA CORRECTAMENTE", FILE_APPEND);
}

curl_close($ch);

echo "Proceso completado. Verifica:\n";
echo "- Log completo: $logFile\n";
echo "- Imagen descargada: $outputFile (si tuvo éxito)\n";
?>
