<?php

// Configuración SMTP
$config = array(
    'protocol' => 'smtp',
    'smtp_host' => 'mail.revisionalpha.com',
    'smtp_port' => 587,
    'smtp_crypto' => 'tls',
    'smtp_user' => 'administracion@revisionalpha.com',
    'smtp_pass' => 'vRptd2rqBn1',
    'mailtype' => 'html',
    'charset' => 'utf-8',
    'wordwrap' => true
);

// Cargar la librería de email de CodeIgniter
require_once(__DIR__ . '/../system/libraries/Email.php');
$email = new CI_Email($config);

// Configurar el email
$email->set_newline("\r\n");
$email->from('administracion@revisionalpha.com', 'Administración Revision Alpha');
$email->to('revisionalpha@gmail.com');
$email->subject('Test de Email - ' . date('Y-m-d H:i:s'));

// Mensaje HTML simple
$message = "
<html>
<head>
    <title>Test de Email</title>
</head>
<body>
    <h1>Test de Email</h1>
    <p>Este es un email de prueba enviado el " . date('Y-m-d H:i:s') . "</p>
    <p>Si recibes este email, la configuración SMTP está funcionando correctamente.</p>
</body>
</html>";

$email->message($message);

// Intentar enviar el email
if($email->send()) {
    echo "Email enviado correctamente\n";
    echo "Debug:\n";
    print_r($email->print_debugger());
} else {
    echo "Error al enviar el email\n";
    echo "Debug:\n";
    print_r($email->print_debugger());
} 