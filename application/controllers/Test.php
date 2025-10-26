<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Solo permitir en ambiente de desarrollo
        if (ENVIRONMENT !== 'development') {
            show_error('Test controllers are only available in development environment');
        }
    }

    public function email() {
        // Cargar configuración SMTP
        $this->load->config('smtp');
        $this->load->library('email', $this->config->item('smtp'));

        // Configurar el email
        $this->email->set_newline("\r\n");
        $this->email->from('administracion@revisionalpha.com', 'REVISION ALPHA');
        $this->email->to('diego@revisionalpha.com');
        $this->email->subject('Test de Email - ' . date('Y-m-d H:i:s'));

        $this->email->set_header('Content-Transfer-Encoding', 'base64');

        // Mensaje HTML simple
        $message = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Test de Email</title>
    <style type="text/css">
        body {font-family: Arial, sans-serif; margin: 0; padding: 20px;}
        p {margin: 10px 0; word-break: keep-all; white-space: nowrap;}
        .img-container {margin: 15px 0;}
    </style>
</head>
<body>
    <h1>Test de Email</h1>
    <p>Este es un email de prueba enviado el ' . date('Y-m-d H:i:s') . '</p>
    
    <p><a href="https://cms.revisionalpha.com/revision-alpha.svg">Ver imagen</a></p>
    
    <p>Si recibes este email, la configuración de email está funcionando correctamente.</p>
    
    <div class="img-container">
        <img src="https://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50" alt="Test" height="50" style="display:block;">
    </div>
    
    <div class="img-container">
        <img src="https://cms.revisionalpha.com/revision-alpha.svg" alt="revision alpha" height="50" style="display:block;">
    </div>
</body>
</html>';

        $this->email->message($message);

        // Comenzar el buffer de salida
        ob_start();
        
        // Intentar enviar el email
        if ($this->email->send()) {
            $result = "<div style='color: green;'>Email enviado correctamente</div>";
            if (!$this->config->item('smtp')['nodebug']) {
                $result .= "<pre>" . $this->email->print_debugger() . "</pre>";
            }
        } else {
            $result = "<div style='color: red;'>Error al enviar el email</div>";
            $result .= "<pre>" . $this->email->print_debugger() . "</pre>";
        }

        // Limpiar cualquier salida previa
        ob_clean();

        // Enviar respuesta HTML
        $this->output
            ->set_content_type('text/html')
            ->set_output('<!DOCTYPE html>
                <html>
                <head>
                    <title>Test de Email</title>
                    <meta charset="UTF-8">
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
                    </style>
                </head>
                <body>
                    <h1>Resultado del Test de Email</h1>
                    ' . $result . '
                </body>
                </html>');
    }
} 