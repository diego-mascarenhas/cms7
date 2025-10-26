<?php defined('BASEPATH') or exit('No direct script access allowed');

class Template_model extends CI_Model {

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	/**
	 * Obtener todos los templates
	 * 
	 * @param array $params Parámetros opcionales para filtrar
	 * @return array Lista de templates
	 */
	public function getTemplates($params = array())
	{
		// Verificar si la tabla existe, si no, crearla
		$this->_createTemplateTableIfNotExists();
		
		$this->db->select('*');
		$this->db->from('templates');
		
		// Aplicar filtros opcionales
		if (isset($params['search'])) {
			$this->db->like('name', $params['search']);
		}
		
		// Ordenar por fecha de actualización, descendente
		$this->db->order_by('date_updated', 'DESC');
		
		$query = $this->db->get();
		return $query->result_array();
	}
	
	/**
	 * Obtener un template específico
	 * 
	 * @param int $id ID del template
	 * @return array Datos del template
	 */
	public function getTemplate($id)
	{
		// Verificar si la tabla existe, si no, crearla
		$this->_createTemplateTableIfNotExists();
		
		$this->db->where('id', $id);
		$query = $this->db->get('templates');
		
		return $query->row_array();
	}
	
	/**
	 * Guardar un nuevo template
	 * 
	 * @param array $data Datos del template
	 * @return int|bool ID del template o false en caso de error
	 */
	public function saveTemplate($data)
	{
		// Verificar si la tabla existe, si no, crearla
		$this->_createTemplateTableIfNotExists();
		
		// Verificar si ya existe un template con la misma URL fuente
		$this->db->where('url_source', $data['url_source']);
		$query = $this->db->get('templates');
		
		if ($query->num_rows() > 0) {
			// Actualizar template existente
			$existing = $query->row_array();
			$data['date_updated'] = date('Y-m-d H:i:s');
			
			$this->db->where('id', $existing['id']);
			$this->db->update('templates', $data);
			
			return $existing['id'];
		} else {
			// Insertar nuevo template
			$this->db->insert('templates', $data);
			
			return $this->db->insert_id();
		}
	}
	
	/**
	 * Eliminar un template
	 * 
	 * @param int $id ID del template
	 * @return bool Resultado de la operación
	 */
	public function deleteTemplate($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete('templates');
	}
	
	/**
	 * Método privado para crear la tabla de templates si no existe
	 */
	private function _createTemplateTableIfNotExists()
	{
		// Verificar si la tabla existe
		if (!$this->db->table_exists('templates')) {
			// Crear la tabla
			$this->load->dbforge();
			
			$fields = [
				'id' => [
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'auto_increment' => true
				],
				'name' => [
					'type' => 'VARCHAR',
					'constraint' => 255,
					'null' => false
				],
				'url_source' => [
					'type' => 'VARCHAR',
					'constraint' => 512,
					'null' => true
				],
				'content' => [
					'type' => 'TEXT',
					'null' => false
				],
				'description' => [
					'type' => 'TEXT',
					'null' => true
				],
				'date_created' => [
					'type' => 'DATETIME',
					'null' => true
				],
				'date_updated' => [
					'type' => 'DATETIME',
					'null' => true
				]
			];
			
			$this->dbforge->add_field($fields);
			$this->dbforge->add_key('id', true);
			$this->dbforge->create_table('templates', true);
		}
	}
} 