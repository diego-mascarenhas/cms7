<?php defined('BASEPATH') OR exit('No direct script access allowed');

$lang['email_must_be_array'] = "ENGLISH => El método de validación de correo debe ser pasado como un arreglo.";
$lang['email_invalid_address'] = "ENGLISH => Dirección de correo no válida: %s";
$lang['email_attachment_missing'] = "ENGLISH => No se ha podido localizar el fichero adjunto: %s";
$lang['email_attachment_unreadable'] = "ENGLISH => No se ha podido abrir el fichero adjunto: %s";
$lang['email_no_recipients'] = "ENGLISH => Debe incluir receptores: Para, CC, o BCC";
$lang['email_send_failure_phpmail'] = "ENGLISH => No se puede enviar el correo usando la función mail() de PHP.  Su servidor puede no estar configurado para usar este método de envío.";
$lang['email_send_failure_sendmail'] = "ENGLISH => No se puede enviar el correo usando SendMail. Su servidor puede no estar configurado para usar este método de envío.";
$lang['email_send_failure_smtp'] = "ENGLISH => No puedo enviar el correo usando SMTP PHP. Su servidor puede no estar configurado para usar este método de envío.";
$lang['email_sent'] = "ENGLISH => Su mensaje ha sido enviado satisfactoriamente usando el siguiente protocolo: %s";
$lang['email_no_socket'] = "ENGLISH => No se puede abrir un socket para Sendmail. Por favor revise las configuraciones.";
$lang['email_no_hostname'] = "ENGLISH => No has especificado un servidor SMTP.";
$lang['email_smtp_error'] = "ENGLISH => Los siguientes errores SMTP han sido encontrados: %s";
$lang['email_no_smtp_unpw'] = "ENGLISH => Error: Debes asignar un usuario y contraseña para el servidor SMTP."; 
$lang['email_failed_smtp_login'] = "ENGLISH => Falló enviando el comando AUTH LOGIN. Error: %s";
$lang['email_smtp_auth_un'] = "ENGLISH => Falló autentificando el usuario. Error: %s";
$lang['email_smtp_auth_pw'] = "ENGLISH => Falló usando la contraseña. Error: %s";
$lang['email_smtp_data_failure'] = "ENGLISH => No se han podido enviar los datos: %s";
/* New in 1.6 or Higher  */
$lang['email_exit_status'] = "ENGLISH => Código de estado de salida: %s";