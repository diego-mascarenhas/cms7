<?php defined('BASEPATH') or exit('No direct script access allowed');

class Templates extends MY_Controller {

	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('template_model');
		$this->load->library('curl');
	}

	/**
	 * Listado de templates
	 */
	public function index()
	{
		if ($this->is_logged_in('admin'))
		{
			$data['templates'] = $this->template_model->getTemplates();
			
			$this->load->view('/header');
			$this->load->view('/templates/index', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}

	/**
	 * Ver un template específico
	 * 
	 * @param int $id ID del template
	 */
	public function view($id)
	{
		if ($this->is_logged_in('admin'))
		{
			$data['template'] = $this->template_model->getTemplate($id);
			
			$this->load->view('/header');
			$this->load->view('/templates/view', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}

	/**
	 * Obtener contenido de template desde URL externa
	 * 
	 * @param string $url URL opcional del template
	 */
	public function fetch($url = '')
	{
		if ($this->is_logged_in('admin'))
		{
			// Si no se proporciona URL, usar la de tickets por defecto
			if (empty($url)) {
				$url = 'https://cms.revisionalpha.com/templates/502/comunicaciones/tickets.php';
			}
			
			// Obtener el contenido del template
			$content = $this->curl->simple_get($url);
			
			// Preparar datos para la vista
			$data['url'] = $url;
			$data['content'] = $content;
			$data['template_name'] = basename($url);
			
			$this->load->view('/header');
			$this->load->view('/templates/fetch', $data);
			$this->load->view('/footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}

	/**
	 * Guardar template desde URL externa
	 */
	public function save()
	{
		if ($this->is_logged_in('admin'))
		{
			$this->load->helper('form');
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('url', 'URL', 'required|valid_url');
			$this->form_validation->set_rules('name', 'Nombre', 'required');
			$this->form_validation->set_rules('content', 'Contenido', 'required');
			
			if ($this->form_validation->run() === false)
			{
				// Si hay errores de validación, mostrar el formulario nuevamente
				$data['url'] = $this->input->post('url');
				$data['content'] = $this->input->post('content');
				$data['template_name'] = $this->input->post('name');
				
				$this->load->view('/header');
				$this->load->view('/templates/fetch', $data);
				$this->load->view('/footer');
			}
			else
			{
				// Guardar el template en la base de datos
				$template_data = [
					'name' => $this->input->post('name'),
					'url_source' => $this->input->post('url'),
					'content' => $this->input->post('content'),
					'date_created' => date('Y-m-d H:i:s'),
					'date_updated' => date('Y-m-d H:i:s')
				];
				
				$result = $this->template_model->saveTemplate($template_data);
				
				if ($result) {
					$this->session->set_flashdata('success', 'Template guardado correctamente');
					redirect(base_url('templates'));
				} else {
					$this->session->set_flashdata('error', 'Error al guardar el template');
					redirect(base_url('templates/fetch'));
				}
			}
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}

	/**
	 * Obtener contenido de template como texto plano
	 * Útil para usar vía AJAX
	 * 
	 * @param string $url URL del template
	 */
	public function get_content($url = '')
	{
		if ($this->is_logged_in('admin'))
		{
			// Si no se proporciona URL, usar la de tickets por defecto
			if (empty($url)) {
				$url = 'https://cms.revisionalpha.com/templates/502/comunicaciones/tickets.php';
			}
			
			// Decodificar la URL si está codificada
			$url = urldecode($url);
			
			// Obtener el contenido del template
			$content = $this->curl->simple_get($url);
			
			// Devolver solo el contenido
			echo $content;
		}
		else
		{
			echo json_encode(['error' => 'Acceso denegado']);
		}
	}

	/**
	 * Eliminar un template
	 * 
	 * @param int $id ID del template a eliminar
	 */
	public function delete($id)
	{
		if ($this->is_logged_in('admin'))
		{
			// Obtener el template para mostrar mensaje de confirmación
			$template = $this->template_model->getTemplate($id);
			
			if (!empty($template)) {
				// Eliminar el template
				$result = $this->template_model->deleteTemplate($id);
				
				if ($result) {
					$this->session->set_flashdata('success', 'Template "' . $template['name'] . '" eliminado correctamente');
				} else {
					$this->session->set_flashdata('error', 'Error al eliminar el template');
				}
			} else {
				$this->session->set_flashdata('error', 'Template no encontrado');
			}
			
			redirect(base_url('templates'));
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
} 