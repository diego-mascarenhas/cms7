<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Simple Session Test Controller
 * Controlador para probar la implementación de sesión simplificada
 */
class Simplesession extends CI_Controller {

    protected $simple_session;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('simple_session');
        $this->simple_session = $this->simple_session;
    }
    
    /**
     * Index
     */
    public function index()
    {
        echo "<h1>Prueba de Sesión Simplificada</h1>";
        
        echo "<h2>1. Información básica</h2>";
        echo "<pre>";
        echo "Simple Session ID: " . $this->simple_session->id() . "\n";
        echo "Standard Session ID: " . session_id() . "\n";
        echo "</pre>";
        
        echo "<h2>2. Contenido de la sesión</h2>";
        echo "<pre>";
        echo "Simple Session Data: \n";
        var_dump($this->simple_session->get());
        echo "</pre>";
        
        echo "<h2>3. Opciones</h2>";
        echo "<ul>";
        echo "<li><a href='" . base_url('simplesession/set') . "'>Establecer datos de prueba</a></li>";
        echo "<li><a href='" . base_url('simplesession/storeuser') . "'>Guardar usuario de prueba</a></li>";
        echo "<li><a href='" . base_url('simplesession/clear') . "'>Limpiar sesión</a></li>";
        echo "</ul>";
    }
    
    /**
     * Set - Establece datos de prueba
     */
    public function set()
    {
        // Establecer datos de prueba
        $this->simple_session->set('test_time', time());
        $this->simple_session->set('test_array', [
            'name' => 'Test User',
            'role' => 'tester',
            'permissions' => ['read', 'write']
        ]);
        
        echo "<h1>Datos establecidos</h1>";
        echo "<pre>";
        echo "Session Data: \n";
        var_dump($this->simple_session->get());
        echo "</pre>";
        
        echo "<p><a href='" . base_url('simplesession') . "'>Volver</a></p>";
    }
    
    /**
     * Store User - Guarda un usuario de prueba
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
        
        // Guardar en la sesión simple
        $this->simple_session->set('usuario', $user);
        $this->simple_session->set('logged_in', true);
        
        echo "<h1>Usuario guardado</h1>";
        echo "<pre>";
        echo "Session Data: \n";
        var_dump($this->simple_session->get());
        echo "</pre>";
        
        echo "<p><a href='" . base_url('simplesession/checkuser') . "'>Verificar usuario</a></p>";
        echo "<p><a href='" . base_url('simplesession') . "'>Volver</a></p>";
    }
    
    /**
     * Check User - Verifica el usuario guardado
     */
    public function checkuser()
    {
        echo "<h1>Verificación de usuario</h1>";
        
        if ($this->simple_session->has('usuario')) {
            echo "<p>El usuario existe en la sesión.</p>";
            echo "<pre>";
            echo "Usuario: \n";
            var_dump($this->simple_session->get('usuario'));
            echo "</pre>";
        } else {
            echo "<p>NO se encontró el usuario en la sesión.</p>";
        }
        
        echo "<p><a href='" . base_url('simplesession') . "'>Volver</a></p>";
    }
    
    /**
     * Clear - Limpia la sesión
     */
    public function clear()
    {
        $this->simple_session->destroy();
        
        echo "<h1>Sesión limpiada</h1>";
        echo "<p>La sesión ha sido destruida.</p>";
        
        echo "<p><a href='" . base_url('simplesession') . "'>Volver</a></p>";
    }
    
    /**
     * Prototipo de login utilizando sesión simple
     */
    public function login()
    {
        // Crear un objeto de usuario de prueba (simulando login)
        $user = new stdClass();
        $user->id = 123;
        $user->username = 'usuario_real';
        $user->perfil = 'reseller';
        $user->nombre = 'Usuario Real';
        $user->email = 'usuario@example.com';
        $user->idioma = 'spanish';
        
        // Guardar en la sesión simple
        $this->simple_session->set('usuario', $user);
        $this->simple_session->set('logged_in', true);
        
        // También guardar en la sesión estándar como referencia
        $_SESSION['usuario'] = $user;
        $_SESSION['logged_in'] = true;
        
        echo "<h1>Login simulado</h1>";
        echo "<p>Se ha simulado un login exitoso.</p>";
        
        echo "<h2>Sesión simple</h2>";
        echo "<pre>";
        var_dump($this->simple_session->get());
        echo "</pre>";
        
        echo "<h2>Sesión estándar</h2>";
        echo "<pre>";
        var_dump($_SESSION);
        echo "</pre>";
        
        echo "<p><a href='" . base_url('simplesession/dashboard') . "'>Ir al dashboard</a></p>";
    }
    
    /**
     * Dashboard de prueba
     */
    public function dashboard()
    {
        echo "<h1>Dashboard</h1>";
        
        if (!$this->simple_session->has('usuario')) {
            echo "<p>No has iniciado sesión. <a href='" . base_url('simplesession/login') . "'>Iniciar sesión</a></p>";
            return;
        }
        
        $user = $this->simple_session->get('usuario');
        
        echo "<h2>Bienvenido, " . $user->nombre . "</h2>";
        echo "<p>Perfil: " . $user->perfil . "</p>";
        
        echo "<h3>Datos de sesión</h3>";
        echo "<pre>";
        var_dump($this->simple_session->get());
        echo "</pre>";
        
        echo "<p><a href='" . base_url('simplesession') . "'>Volver</a></p>";
    }
} 