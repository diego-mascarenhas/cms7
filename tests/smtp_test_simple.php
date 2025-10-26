<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Configuración de headers
$to = 'revisionalpha@gmail.com';
$subject = 'Test de Email - ' . date('Y-m-d H:i:s');
$from = 'administracion@revisionalpha.com';

// Headers para envío HTML
$headers = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
$headers .= 'From: ' . $from . "\r\n";
$headers .= 'Reply-To: ' . $from . "\r\n";
$headers .= 'X-Mailer: PHP/' . phpversion();

// Mensaje HTML simple
$message = "
<html>
<head>
    <title>Test de Email</title>
</head>
<body>
    <h1>Test de Email</h1>
    <p>Este es un email de prueba enviado el " . date('Y-m-d H:i:s') . "</p>
    <p>Si recibes este email, la configuración de email está funcionando correctamente.</p>
</body>
</html>";

// Intentar enviar el email
if(mail($to, $subject, $message, $headers)) {
    echo "Email enviado correctamente\n";
} else {
    echo "Error al enviar el email\n";
    echo "Último error: " . error_get_last()['message'] . "\n";
} 