<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Session Fix Controller
 * Controlador para diagnosticar y solucionar problemas de sesión
 */
class Sessionfix extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Index - Muestra información de la sesión actual
     */
    public function index()
    {
        echo "<h1>Diagnóstico de Sesión</h1>";
        
        echo "<h2>1. Información básica</h2>";
        echo "<pre>";
        echo "Session ID: " . session_id() . "\n";
        echo "Session Name: " . session_name() . "\n";
        echo "Session Save Path: " . session_save_path() . "\n";
        echo "</pre>";
        
        echo "<h2>2. Contenido de la sesión</h2>";
        echo "<pre>";
        echo "PHP SESSION: \n";
        var_dump($_SESSION);
        echo "\nCI SESSION: \n";
        var_dump($this->session->userdata());
        echo "</pre>";
        
        echo "<h2>3. Archivo de sesión</h2>";
        $session_file = session_save_path() . '/ci_session' . session_id();
        echo "<pre>";
        echo "Path: " . $session_file . "\n";
        echo "Exists: " . (file_exists($session_file) ? 'YES' : 'NO') . "\n";
        
        if (file_exists($session_file)) {
            echo "Size: " . filesize($session_file) . " bytes\n";
            echo "Content: \n" . htmlspecialchars(file_get_contents($session_file)) . "\n";
        }
        echo "</pre>";
        
        echo "<h2>4. Opciones de sesión</h2>";
        echo "<pre>";
        echo "Session save_handler: " . ini_get('session.save_handler') . "\n";
        echo "Session save_path: " . ini_get('session.save_path') . "\n";
        echo "Session gc_maxlifetime: " . ini_get('session.gc_maxlifetime') . "\n";
        echo "Session use_cookies: " . ini_get('session.use_cookies') . "\n";
        echo "Session use_only_cookies: " . ini_get('session.use_only_cookies') . "\n";
        echo "</pre>";
        
        echo "<h2>5. Acciones</h2>";
        echo "<ul>";
        echo "<li><a href='" . base_url('sessionfix/test') . "'>Probar escritura de sesión</a></li>";
        echo "<li><a href='" . base_url('sessionfix/fix') . "'>Intentar arreglar la sesión</a></li>";
        echo "<li><a href='" . base_url('sessionfix/clear') . "'>Limpiar sesión</a></li>";
        echo "</ul>";
    }
    
    /**
     * Test - Prueba la escritura en la sesión
     */
    public function test()
    {
        // Intentar escribir en la sesión
        $_SESSION['test_value'] = 'Valor de prueba: ' . time();
        $this->session->set_userdata('ci_test_value', 'Valor CI de prueba: ' . time());
        
        // Forzar escritura
        session_write_close();
        session_start();
        
        echo "<h1>Prueba de escritura en sesión</h1>";
        echo "<p>Se han escrito valores de prueba en la sesión.</p>";
        echo "<p><a href='" . base_url('sessionfix') . "'>Volver al diagnóstico</a> para verificar si los valores se guardaron.</p>";
    }
    
    /**
     * Fix - Intenta arreglar la sesión
     */
    public function fix()
    {
        // Crear directorio de sesiones si no existe
        $session_path = $this->config->item('sess_save_path');
        if (!is_dir($session_path)) {
            mkdir($session_path, 0777, TRUE);
        }
        
        // Verificar permisos
        $perms = substr(sprintf('%o', fileperms($session_path)), -4);
        if ($perms != '0777') {
            chmod($session_path, 0777);
        }
        
        // Reiniciar la sesión
        session_destroy();
        session_start();
        
        // Crear una nueva sesión de prueba
        $_SESSION['fixed_time'] = time();
        $this->session->set_userdata('ci_fixed_time', time());
        
        // Forzar escritura
        session_write_close();
        session_start();
        
        echo "<h1>Intento de arreglo de sesión</h1>";
        echo "<p>Se ha reiniciado la sesión y se han verificado los permisos del directorio.</p>";
        echo "<pre>";
        echo "Session Path: " . $session_path . "\n";
        echo "Session Path Permissions: " . $perms . "\n";
        echo "</pre>";
        echo "<p><a href='" . base_url('sessionfix') . "'>Volver al diagnóstico</a> para verificar el resultado.</p>";
    }
    
    /**
     * Clear - Limpia la sesión
     */
    public function clear()
    {
        session_destroy();
        
        echo "<h1>Sesión limpiada</h1>";
        echo "<p>Se ha destruido la sesión actual.</p>";
        echo "<p><a href='" . base_url('sessionfix') . "'>Volver al diagnóstico</a> para verificar el resultado.</p>";
    }
} 