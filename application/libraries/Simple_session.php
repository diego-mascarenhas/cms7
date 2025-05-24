<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Simple Session Library
 * 
 * Una implementación simplificada de sesión que almacena datos en archivos planos
 */
class Simple_session {
    
    protected $session_id;
    protected $session_path;
    protected $session_file;
    protected $data = array();
    protected $CI;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->CI =& get_instance();
        
        // Configurar ruta de sesión
        $this->session_path = FCPATH . 'application/sessions/simple/';
        
        // Crear directorio si no existe
        if (!is_dir($this->session_path)) {
            mkdir($this->session_path, 0777, TRUE);
        }
        
        // Obtener o crear ID de sesión
        $this->session_id = isset($_COOKIE['simple_session']) ? $_COOKIE['simple_session'] : '';
        
        if (empty($this->session_id) || !$this->validate_session_id($this->session_id)) {
            $this->session_id = $this->generate_session_id();
            setcookie('simple_session', $this->session_id, time() + 86400, '/');
        }
        
        // Configurar archivo de sesión
        $this->session_file = $this->session_path . 'sess_' . $this->session_id;
        
        // Cargar datos de sesión
        $this->load_session();
    }
    
    /**
     * Generar ID de sesión
     * 
     * @return string
     */
    protected function generate_session_id()
    {
        return md5(uniqid(rand(), TRUE));
    }
    
    /**
     * Validar ID de sesión
     * 
     * @param string $id
     * @return bool
     */
    protected function validate_session_id($id)
    {
        return (bool) preg_match('/^[a-zA-Z0-9]{32}$/', $id);
    }
    
    /**
     * Cargar datos de sesión
     */
    protected function load_session()
    {
        if (file_exists($this->session_file)) {
            $contents = file_get_contents($this->session_file);
            $this->data = unserialize($contents);
        }
    }
    
    /**
     * Guardar datos de sesión
     */
    public function save_session()
    {
        $contents = serialize($this->data);
        file_put_contents($this->session_file, $contents);
    }
    
    /**
     * Establecer datos de sesión
     * 
     * @param string|array $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value = NULL)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->data[$k] = $v;
            }
        } else {
            $this->data[$key] = $value;
        }
        
        $this->save_session();
    }
    
    /**
     * Obtener datos de sesión
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key = NULL, $default = NULL)
    {
        if ($key === NULL) {
            return $this->data;
        }
        
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }
    
    /**
     * Verificar si existe un dato en la sesión
     * 
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this->data[$key]);
    }
    
    /**
     * Eliminar datos de sesión
     * 
     * @param string $key
     * @return void
     */
    public function remove($key)
    {
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
            $this->save_session();
        }
    }
    
    /**
     * Destruir sesión
     * 
     * @return void
     */
    public function destroy()
    {
        $this->data = array();
        
        if (file_exists($this->session_file)) {
            unlink($this->session_file);
        }
        
        setcookie('simple_session', '', time() - 3600, '/');
    }
    
    /**
     * Obtener ID de sesión
     * 
     * @return string
     */
    public function id()
    {
        return $this->session_id;
    }
    
    /**
     * Obtener ruta del archivo de sesión
     * 
     * @return string
     */
    public function get_session_file()
    {
        return $this->session_file;
    }
    
    /**
     * Obtener contenido del archivo de sesión
     * 
     * @return string
     */
    public function get_session_file_content()
    {
        if (file_exists($this->session_file)) {
            return file_get_contents($this->session_file);
        }
        return '';
    }
} 