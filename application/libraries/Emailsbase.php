<?php

class emailsbase {

  public static function enviamailventas($direcciones, $direccionesocultas, $subject, $body, $idpedido, $idusuario, $adjuntos = null, $adjuntosfactura = false)
  {

    $CI =& get_instance();
    $CI->load->library('phpmailer_lib');

    // PHPMailer object
    $mail = $CI->phpmailer_lib->load();
    $mail->IsSMTP();                           // telling the class to use SMTP
    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->Host       = "mail.elreferente.com.ar"; // set the SMTP server
    $mail->Port       = 26;                    // set the SMTP port
    $mail->Username   = "enviomail@elreferente.com.ar"; // SMTP account username
    $mail->Password   = "Envio_plataf.2658";        // SMTP account password

    $mail->setFrom('enviomail@elreferente.com.ar', 'BoxShows');

    $destinatarios = '';
    foreach ($direcciones as $direccion) {
      if ( $mail->validateAddress($direccion) ){
        $mail->addAddress($direccion, 'Pedido');
        $destinatarios .= $direccion.';';
      }

    }

    $mail->Subject = $subject;
    $mail->isHTML(true);
    $mail->Body     = $body;

    if ( $adjuntos ){
      foreach ($adjuntos as $value) {
        if ( $adjuntosfactura ){
          $mail->addAttachment('./assets/facturas/'.$value);
        }else{
          $mail->addAttachment('./assets/tickets/'.$value);
        }
      }

    }


    if(!$mail->send()) {
      $resultado = 'Error en Envio: '. $mail->ErrorInfo;
    }else{
      // guarda pedido en base
      $fecha = date("Y/m/d"); 
      $data = array(
        'fecharegistro' => $fecha,
        'subject' => $subject,
        'body' => $body,
        'destinatarios' => $destinatarios,
        'idpedido' => $idpedido,
        'idusuario' => $idusuario
      );

      $CI->db->insert("historiamails", $data);

      $resultado = 'OK';
    }
    return $resultado;
  }

  public static function enviamailerror($subject, $body)
  {

      $CI =& get_instance();

      $CI->load->library('phpmailer_lib');

      // PHPMailer object
      $mail = $CI->phpmailer_lib->load();
      $mail->IsSMTP();                           // telling the class to use SMTP
      $mail->SMTPAuth   = true;                  // enable SMTP authentication
      $mail->Host       = "mail.elreferente.com.ar"; // set the SMTP server
      $mail->Port       = 26;                    // set the SMTP port
      $mail->Username   = "enviomail@elreferente.com.ar"; // SMTP account username
      $mail->Password   = "Envio_plataf.2658";        // SMTP account password

      $mail->setFrom('enviomail@elreferente.com.ar', 'BoxShows');


      $mail->addAddress('romanmolinero@gmail.com', 'ERROR Boxshows');

      $mail->Subject = $subject;

      $mail->isHTML(true);
      $mail->Body     = $body;

      if(!$mail->send()) {
        $resultado = 'Error en Envio: '. $mail->ErrorInfo;
      }else{
        $resultado = 'OK';
        // guarda pedido en base
        $fecha = date("Y/m/d"); 

        $data = array(
          'fecharegistro' => $fecha,
          'subject' => $subject,
          'body' => $body,
          'destinatarios' => ''
        );
        $CI->db->insert("historiamails_errores", $data);

      }
      return $resultado;
  }



}


?>
