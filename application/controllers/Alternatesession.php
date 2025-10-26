<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Alternate Session Controller
 * Controlador que usa las sesiones nativas de PHP para probar si el problema está en CodeIgniter
 */
class Alternatesession extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Index
     */
    public function index()
    {
        echo "<h1>Sesión Alternativa</h1>";
        
        echo "<h2>1. Sesión actual</h2>";
        echo "<pre>";
        echo "Session ID: " . session_id() . "\n";
        echo "SESSION Data: \n";
        var_dump($_SESSION);
        echo "</pre>";
        
        echo "<h2>2. Opciones</h2>";
        echo "<ul>";
        echo "<li><a href='" . base_url('alternatesession/set') . "'>Crear datos de sesión</a></li>";
        echo "<li><a href='" . base_url('alternatesession/read') . "'>Leer datos de sesión</a></li>";
        echo "<li><a href='" . base_url('alternatesession/clear') . "'>Limpiar sesión</a></li>";
        echo "</ul>";
    }
    
    /**
     * Set - Crea datos de sesión con PHP nativo
     */
    public function set()
    {
        // Crear datos de sesión
        $_SESSION['alt_timestamp'] = time();
        $_SESSION['alt_data'] = [
            'nombre' => 'Usuario de prueba',
            'id' => 123,
            'role' => 'tester'
        ];
        
        // Forzar escritura
        session_write_close();
        session_start();
        
        echo "<h1>Datos de sesión creados</h1>";
        echo "<pre>";
        echo "Session ID: " . session_id() . "\n";
        echo "Datos almacenados en la sesión: \n";
        var_dump($_SESSION);
        echo "</pre>";
        
        // Mostrar contenido del archivo de sesión
        $session_file = session_save_path() . '/ci_session' . session_id();
        if (file_exists($session_file)) {
            echo "<h2>Archivo de sesión</h2>";
            echo "<pre>";
            echo "Contenido: \n" . htmlspecialchars(file_get_contents($session_file));
            echo "</pre>";
        }
        
        echo "<p><a href='" . base_url('alternatesession') . "'>Volver</a></p>";
    }
    
    /**
     * Read - Lee datos de sesión
     */
    public function read()
    {
        echo "<h1>Lectura de sesión</h1>";
        echo "<pre>";
        echo "Session ID: " . session_id() . "\n";
        echo "Datos en la sesión: \n";
        var_dump($_SESSION);
        echo "</pre>";
        
        // Verificar si los datos existen
        if (isset($_SESSION['alt_timestamp'])) {
            echo "<p>Timestamp encontrado: " . date('Y-m-d H:i:s', $_SESSION['alt_timestamp']) . "</p>";
        } else {
            echo "<p>No se encontró el timestamp.</p>";
        }
        
        if (isset($_SESSION['alt_data'])) {
            echo "<p>Datos encontrados:</p>";
            echo "<pre>";
            var_dump($_SESSION['alt_data']);
            echo "</pre>";
        } else {
            echo "<p>No se encontraron los datos.</p>";
        }
        
        echo "<p><a href='" . base_url('alternatesession') . "'>Volver</a></p>";
    }
    
    /**
     * Clear - Limpia la sesión
     */
    public function clear()
    {
        // Guardar el ID para mostrar
        $old_id = session_id();
        
        // Destruir la sesión
        session_destroy();
        
        // Iniciar una nueva sesión
        session_start();
        
        echo "<h1>Sesión limpiada</h1>";
        echo "<p>ID de sesión anterior: " . $old_id . "</p>";
        echo "<p>Nuevo ID de sesión: " . session_id() . "</p>";
        
        echo "<p><a href='" . base_url('alternatesession') . "'>Volver</a></p>";
    }
    
    /**
     * Store User - Simula guardar un usuario en la sesión
     */
    public function storeuser()
    {
        // Crear un objeto de usuario de prueba
        $user = new stdClass();
        $user->id = 999;
        $user->username = 'test_user';
        $user->perfil = 'admin';
        $user->nombre = 'Usuario Prueba';
        $user->email = 'test@example.com';
        $user->idioma = 'spanish';
        
        // Guardar en la sesión
        $_SESSION['usuario'] = $user;
        $_SESSION['logged_in'] = true;
        
        // Forzar escritura
        session_write_close();
        session_start();
        
        echo "<h1>Usuario almacenado en sesión</h1>";
        echo "<pre>";
        echo "Session ID: " . session_id() . "\n";
        echo "Datos de usuario en sesión: \n";
        var_dump($_SESSION['usuario']);
        echo "</pre>";
        
        echo "<p><a href='" . base_url('alternatesession/checkuser') . "'>Verificar persistencia</a></p>";
        echo "<p><a href='" . base_url('alternatesession') . "'>Volver</a></p>";
    }
    
    /**
     * Check User - Verifica si el usuario persiste en la sesión
     */
    public function checkuser()
    {
        echo "<h1>Verificación de usuario en sesión</h1>";
        echo "<pre>";
        echo "Session ID: " . session_id() . "\n";
        echo "Usuario en sesión: \n";
        
        if (isset($_SESSION['usuario'])) {
            var_dump($_SESSION['usuario']);
            echo "\nEl usuario existe en la sesión.";
        } else {
            echo "¡NO SE ENCONTRÓ EL USUARIO EN LA SESIÓN!";
        }
        echo "</pre>";
        
        // Mostrar contenido del archivo de sesión
        $session_file = session_save_path() . '/ci_session' . session_id();
        if (file_exists($session_file)) {
            echo "<h2>Archivo de sesión</h2>";
            echo "<pre>";
            echo "Contenido: \n" . htmlspecialchars(file_get_contents($session_file));
            echo "</pre>";
        }
        
        echo "<p><a href='" . base_url('alternatesession') . "'>Volver</a></p>";
    }
} 