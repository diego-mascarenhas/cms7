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
        $this->email->from('administracion@revisionalpha.com', 'Administración Revision Alpha');
        $this->email->to('revisionalpha@gmail.com');
        $this->email->subject('Test de Email - ' . date('Y-m-d H:i:s'));

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