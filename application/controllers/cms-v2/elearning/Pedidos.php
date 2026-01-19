<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pedidos extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('cms-v2/elearning/Pedidos_model');
		$this->load->model('cms-v2/elearning/Elearning_model');
		$this->load->model('cms-v2/elearning/Contacto_model');
		$this->load->model('cms-v2/Configuracion_model');
		$this->load->model('contacto_model');
	}
	
	public function index()
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			if($data['configuracion']['id'])
			{
				$data['listado'] = $this->Pedidos_model->getPedidos();
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/pedidos/index', $data);
				$this->load->view('/footer');
			}
			else
			{
				$data['mensaje'] = 'Usted no tiene privilegios para estar aquí.';
				$this->load->view('/header', array('buscador'=>true));
				$this->load->view('cms-v2/sin-permisos', $data);
				$this->load->view('/footer');
			}			
		}
		else
		{
			redirect(base_url('/user/login/'));
		}
	}

	public function subir($id)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('observaciones', 'Referencia', 'required', array('required' => 'El campo Referencia es obligatorio.'));
			$this->form_validation->set_rules('items[]', 'Cursos', 'required', array('required' => 'Debe seleccionar al menos un curso.'));
			$this->form_validation->set_rules('estado', 'Estado', 'required', array('required' => 'Debe seleccionar un estado.'));
			
			if ($this->form_validation->run() === false)
			{
				$data['item'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				$data['cursos'] = $this->Elearning_model->getCursos();
				$data['estados'] = array(5 => 'Pendiente', 2 => 'Pagado', 8 => 'Cancelado', 7 => 'Bonificado');
				$data['contacto'] = $this->Contacto_model->detalleContacto($id);
				
				$this->load->view('header');
				$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/pedidos/ingresar', $data);
				$this->load->view('footer');
			}
			else
			{
				// Debug: Log de datos recibidos
				log_message('debug', 'Pedido - Datos POST: ' . print_r($this->input->post(), true));
				
				$datos = $this->Pedidos_model->ingresarPedido($this->input->post());
				
				// Debug: Log del resultado
				log_message('debug', 'Pedido - Resultado ingresarPedido: ' . print_r($datos, true));
				
				if ($datos && isset($datos['id']))
		        {
					$this->session->set_flashdata('resultado', '1');
					$this->session->set_flashdata('mensaje', 'El pedido fue creado correctamente con ID: ' . $datos['id']);
					redirect(base_url('cms-v2/elearning/pedidos/detalle/'.$datos['id']));
		        }
		        else
		        {
					$this->session->set_flashdata('resultado', '0');
					$this->session->set_flashdata('mensaje', 'No se pudo crear el pedido. Revise los datos e intente nuevamente.');
					redirect(base_url('cms-v2/elearning/usuarios/empresas'));
		        }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function ingresar()
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			$data['contacto'] = $this->Contacto_model->detalleContacto($this->session->flashdata('contacto'));
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('observaciones', 'Nombre', 'required');
			$this->form_validation->set_rules('items[]', 'Cursos', 'required');
			
			if ($this->form_validation->run() === false)
			{
				$data['item'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				$data['cursos'] = $this->Elearning_model->getCursos();
				$data['estados'] = array(1 => 'Inactivo', 2 => 'Activo');
				$this->session->set_flashdata('contacto', $data['contacto']['id']);
	
				$this->load->view('header');
				$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/pedidos/ingresar', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($datos = $this->Pedidos_model->ingresarPedido($this->input->post()))
		        {
			        $estado['estado'] = 7;
			        $estado['id'] = $datos['id'];
			        $estado = $this->Pedidos_model->cambiarEstadoPedido($estado);
					redirect(base_url('cms-v2/elearning/pedidos/modificar/'.$datos['id']));
		        }
		        else
		        {
					$data['mensaje'] = array("mensaje" =>"No se pudo modificar el pedido", "link" =>"pedidos", "texto_link" => "Volver a Pedidos");
					$this->load->view('header');
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/error', $data);
					$this->load->view('footer');
		        }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function modificar($id)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('id', 'Pedido', 'required', array('required' => 'Debe ingresar un pedido.'));
			$this->form_validation->set_rules('estado', 'ESTADO', 'required', array('required' => 'Debe ingresar un ESTADO.'));
			
			if ($this->form_validation->run() === false)
			{
				$this->load->model('cms-v2/elearning/Cupones_model');
				$data['item'] = ($this->input->post()) ? $this->input->post() : $this->input->get();
				$parametros['id_pedido'] = $id;
				$data['detalle'] = $this->Pedidos_model->detallePedido($parametros);
				$data['items'] = $this->Pedidos_model->listadoPedidoItems($parametros);
				$data['cupones'] = $this->Cupones_model->getCupones($parametros);
				$data['cantidaditems'] = $this->Pedidos_model->cantidadPedidoItems($id);
				$data['cursos'] = $this->Elearning_model->getCursos();
				$data['contacto'] = $this->Contacto_model->detalleContacto($data['detalle']['id_contacto']);
				$data['estados'] = array(1 => 'Inactivo', 2 => 'Activo');
				$data['estados_pedido'] = array(5 => 'Pendiente', 2 => 'Pagado', 8 => 'Cancelado', 7 => 'Bonificado');

/* 				$data['estados'] = $this->Pedidos_model->comboEstados(); */
				$data['usuarios'] = $this->Pedidos_model->getContactosPedido($id);

				$this->load->view('header');
				$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/pedidos/modificar', $data);
				$this->load->view('footer');
			}
			else
			{
				$variables['id'] = $id;
				$variables['estado'] = $this->input->post('estado');
				if ($datos = $this->Pedidos_model->cambiarEstadoPedido($variables))
		        {
					$this->session->set_flashdata('resultado', '1');
					$this->session->set_flashdata('mensaje', 'El pedido fue modificado correctamente.');
					redirect(base_url('cms-v2/elearning/pedidos/detalle/'.$id));
		        }
		        else
		        {
					$this->session->set_flashdata('resultado', '0');
					$this->session->set_flashdata('mensaje', 'No se pudo modificar el pedido.');
					$this->load->view('header');
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/pedidos/detalle', $data);
					$this->load->view('footer');
		        }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
	
	public function detalle($id)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->form_validation->set_rules('id', 'Pedido', 'required', array('required' => 'Debe ingresar un pedido.'));
			$this->form_validation->set_rules('estado', 'ESTADO', 'required', array('required' => 'Debe ingresar un ESTADO.'));

			if ($this->form_validation->run() === false)
			{
				$this->load->model('cms-v2/elearning/Cupones_model');
				$parametros['id_pedido'] = $id;
				$data['detalle'] = $this->Pedidos_model->detallePedido($parametros);
				$data['contacto'] = $this->contacto_model->detalleContacto($data['detalle']['id_contacto']);
				$data['items'] = $this->Pedidos_model->listadoPedidoItems($parametros);
				$data['cupones'] = $this->Cupones_model->getCupones($parametros);
				$data['cantidaditems'] = $this->Pedidos_model->cantidadPedidoItems($id);
				$data['estados'] = $this->Pedidos_model->comboEstados();
				$data['usuarios'] = $this->Pedidos_model->getContactosPedido($id);

				$this->load->view('header');
				$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/pedidos/detalle', $data);
				$this->load->view('footer');
			}
			else
			{
				if ($datos = $this->pedidos_model->cambiarEstadoPedido($id, $this->input->post('estado')))
		        {
					redirect(base_url('cms-v2/elearning/pedidos/'));
		        }
		        else
		        {
					$data['mensaje'] = array("mensaje" =>"No se pudo modificar el pedido", "link" =>"pedidos", "texto_link" => "Volver a Pedidos");
					$this->load->view('header');
					$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/error', $data);
					$this->load->view('footer');
		        }
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function agregar_item()
	{
		if ($datos = $this->Pedidos_model->ingresarPedido($this->input->post()))
		{
			$this->session->set_flashdata('resultado', '1');
			$this->session->set_flashdata('mensaje', 'El ítem del pedido fue agregado correctamente.');
			redirect(base_url('cms-v2/elearning/pedidos/detalle/'.$this->input->post('id_pedido')));
		}
	}

	public function eliminar_item($id, $pedido)
	{
		$variables['id'] = $id;
		$variables['id_pedido'] = $pedido;
		
		if ($datos = $this->Pedidos_model->eliminarItemPedido($variables))
		{
			$this->session->set_flashdata('resultado', '1');
			$this->session->set_flashdata('mensaje', 'El ítem del pedido fue eliminado correctamente.');
			redirect(base_url('cms-v2/elearning/pedidos/detalle/'.$pedido));
		}
	}

	public function ingresar_usuario()
	{
        $valores = $this->input->post();
        
        $verificar = $this->Contacto_model->verificarSiExiste($valores['email']);
        if(!$verificar['id'])
		{
			$datos = $this->Contacto_model->ingresarContacto($valores);
			$valores['id'] = $datos;
	        $variables['id'] = $datos;
	        $variables['username'] = $this->usuario->id_empresa.$valores['id'];
			$variables['area_privada'] = 5;
	        $modificar = $this->Contacto_model->modificarContacto($variables);
	        
	        $relacionar = $this->Pedidos_model->relacionarContactoPedido($valores);
			$this->session->set_flashdata('resultado', '1');
			$this->session->set_flashdata('mensaje', 'El usuario fue ingresado y asociado al pedido correctamente.');
	    }
	    else
	    {
			$tipo = $this->Contacto_model->getTipoContacto($verificar['id']);
			if($tipo['tipo_contacto'] != 2)
			{
				$this->session->set_flashdata('resultado', '0');
				$this->session->set_flashdata('mensaje', 'El usuario que intenta asociar al pedido se encuentra registrado con un perfil no habilitado para esta acción.');
			}
			else
			{
				$valores['id'] = $verificar['id'];
				$relacionar = $this->Pedidos_model->relacionarContactoPedido($valores);
				$this->session->set_flashdata('resultado', '1');
				$this->session->set_flashdata('mensaje', 'El usuario fue asociado al pedido correctamente.');
			}
	    }
	    
		redirect(base_url('cms-v2/elearning/pedidos/detalle/'.$valores['id_pedido']));
    }

	public function modificar_usuario($id)
	{
		if ($datos = $this->Contacto_model->modificarContacto($this->input->post()))
		{
			redirect(base_url('cms-v2/elearning/pedidos/detalle/'.$id));
		}
	}
	
	public function eliminar_usuario($id)
	{
		if ($datos = $this->Pedidos_model->eliminarContactoPedido($this->input->post()))
		{
			$this->session->set_flashdata('resultado', '1');
			$this->session->set_flashdata('mensaje', 'El usuario fue eliminado para este pedido.');
			redirect(base_url('cms-v2/elearning/pedidos/detalle/'.$id));
		}
	}
	
	public function generar_certificado($id_pedido, $id_contacto)
	{
		if ($this->is_logged_in())
		{
			// Obtener datos del pedido
			$parametros['id_pedido'] = $id_pedido;
			$detalle_pedido = $this->Pedidos_model->detallePedido($parametros);
			
			// Obtener items del pedido para obtener el ID del curso
			$items = $this->Pedidos_model->listadoPedidoItems($parametros);
			
			if (isset($items[0]['id_producto']))
			{
				$id_curso = $items[0]['id_producto'];
				$id_item = $items[0]['id'];
				
				// Generar hash de seguridad: md5(id_curso . id_item . id_contacto . clave_secreta)
				$clave_secreta = 'lizama2024cert';
				$hash = md5($id_curso . $id_item . $id_contacto . $clave_secreta);
				
				// Redirigir a la URL pública de generación de certificados con hash de seguridad
				$url_certificado = 'https://academializama.cl/certificado_publico/' . $id_curso . '/' . $id_item . '?contacto=' . $id_contacto . '&hash=' . $hash;
				redirect($url_certificado);
			}
			else
			{
				// Si no hay items en el pedido, mostrar error
				$this->session->set_flashdata('resultado', '0');
				$this->session->set_flashdata('mensaje', 'No se encontró información del curso para generar el certificado.');
				redirect(base_url('cms-v2/elearning/pedidos/detalle/' . $id_pedido));
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function descargar_csv($id_pedido)
	{
		if ($this->is_logged_in())
		{
			// Obtener datos del pedido y usuarios
			$parametros['id_pedido'] = $id_pedido;
			$detalle_pedido = $this->Pedidos_model->detallePedido($parametros);
			$usuarios = $this->Pedidos_model->getContactosPedido($id_pedido);
			$items = $this->Pedidos_model->listadoPedidoItems($parametros);
			
			if (!$usuarios)
			{
				$this->session->set_flashdata('resultado', '0');
				$this->session->set_flashdata('mensaje', 'No hay usuarios para exportar.');
				redirect(base_url('cms-v2/elearning/pedidos/detalle/' . $id_pedido));
				return;
			}
			
			// Obtener ID del curso para generar los links
			$id_curso = isset($items[0]['id_producto']) ? $items[0]['id_producto'] : 0;
			$id_item = isset($items[0]['id']) ? $items[0]['id'] : 0;
			$clave_secreta = 'lizama2024cert';
			
			// Preparar el CSV con fecha en formato español
			$fecha_esp = date('d-m-Y');
			$filename = 'Listado_Usuarios_Pedido_' . $id_pedido . '_' . $fecha_esp . '.csv';
			
			// Headers para forzar descarga
			header('Content-Type: text/csv; charset=utf-8');
			header('Content-Disposition: attachment; filename="' . $filename . '"');
			
			// Abrir output
			$output = fopen('php://output', 'w');
			
			// BOM para UTF-8
			fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
			
			// Encabezados del CSV
			fputcsv($output, array('Nombre', 'Apellido', 'Email', 'Última Visita', 'Estado', 'Link Certificado'), ';');
			
			// Datos de los usuarios
			foreach ($usuarios as $usuario)
			{
				// Generar hash de seguridad para el link del certificado
				$hash = md5($id_curso . $id_item . $usuario['id'] . $clave_secreta);
				$link_certificado = 'https://academializama.cl/certificado_publico/' . $id_curso . '/' . $id_item . '?contacto=' . $usuario['id'] . '&hash=' . $hash;
				
				// Formatear última visita en formato español
				$ultima_visita = (isset($usuario['ultima_visita']) && $usuario['ultima_visita']) 
					? date('d-m-Y H:i', strtotime($usuario['ultima_visita'])) . ' hs'
					: 'Nunca';
				
				fputcsv($output, array(
					$usuario['nombre'],
					$usuario['apellido'],
					$usuario['email'],
					$ultima_visita,
					$usuario['estado'],
					$link_certificado
				), ';');
			}
			
			fclose($output);
			exit();
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function subir_archivo($id)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			
			// Verificar que la configuración existe
			if (!$data['configuracion'] || !isset($data['configuracion']['id']))
			{
				$this->session->set_flashdata('resultado', '0');
				$this->session->set_flashdata('mensaje', 'No se encontró la configuración de su empresa. Contacte al administrador.');
				redirect(base_url('cms-v2/elearning/pedidos/'));
				return;
			}
			
			$this->load->helper('form');
			$this->load->library('form_validation');
			$this->config->set_item('language', $this->usuario->idioma);
			$this->form_validation->set_rules('subir', 'subir', 'required', array('required' => 'Debe ingresar un archivo csv.'));

			if (empty($_FILES['archivo']['name']))
			{
			    $this->form_validation->set_rules('archivo', 'Archivo', 'required', array('required' => 'Debe ingresar un archivo csv.'));
			}
			else
			{
				if($_FILES['archivo']['type'] != 'text/csv')
				{
				    $this->form_validation->set_rules('archivo', 'Archivo', 'required', array('required' => 'Debe ingresar un archivo csv.'));
				}
				elseif($_FILES['archivo']['size'] > 1000000)
				{
				    $this->form_validation->set_rules('archivo', 'Archivo', 'required', array('required' => 'El archivo es muy pesado, debe pesar menos de 1MB.'));
				}
			}
			
			if ($this->form_validation->run() === false)
			{
				$parametros['id_pedido'] = $id;
				$data['detalle'] = $this->Pedidos_model->detallePedido($parametros);
				
				// Verificar que el pedido existe
				if (!$data['detalle'] || !isset($data['detalle']['id_contacto']))
				{
					$this->session->set_flashdata('resultado', '0');
					$this->session->set_flashdata('mensaje', 'No se encontró el pedido solicitado.');
					redirect(base_url('cms-v2/elearning/pedidos/'));
					return;
				}
				
				$data['contacto'] = $this->contacto_model->detalleContacto($data['detalle']['id_contacto']);
				
				// Verificar que el contacto existe
				if (!$data['contacto'])
				{
					$this->session->set_flashdata('resultado', '0');
					$this->session->set_flashdata('mensaje', 'No se encontró el contacto asociado al pedido.');
					redirect(base_url('cms-v2/elearning/pedidos/'));
					return;
				}
				
				$this->load->view('header', array('buscador'=>true));
				$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/pedidos/subir_archivo', $data);
				$this->load->view('footer');
			}
			else
			{
		         $fname = $_FILES['archivo']['name'];
		         $chk_ext = explode(".",$fname);
		         
		         if(strtolower(end($chk_ext)) == "csv")
		         {
					$filename = $_FILES['archivo']['tmp_name'];
					$handle = fopen($filename, "r");
					
					// Detectar el separador sin consumir la primera línea
					$primera_linea = fgets($handle);
					if(strpos($primera_linea, ';'))
					{
						$separador = ';';
					}
					else
					{
						$separador = ',';
					}
					
					// Volver al inicio del archivo para procesar todas las líneas
					rewind($handle);

					while (($data = fgetcsv($handle, 1000, $separador)) !== FALSE)
					{ 
						$valores['grupo'] = $this->usuario->grupo;
						$valores['id_empresa'] = $this->usuario->id_empresa;
						$valores['nombre'] = trim($data[0]);
						$valores['apellido'] = trim($data[1]);
						$valores['email'] = trim($data[2]);
						$valores['password'] = trim($data[3]);
						$valores['area_privada'] = 5;
						$valores['estado'] = 2;
						$valores['fecha_alta'] = now();
						$valores['username_alta'] = $this->usuario->id;
				        $valores['tipo_contacto'] = 2;
				        $valores['empresa'] = $this->input->post('razon_social');
				        $valores['id_contacto_padre'] = $this->input->post('id_contacto_padre');

				        $verificar = $this->Contacto_model->verificarSiExiste($valores['email']);
				        if(!$verificar['id'])
						{
							$datos = $this->Contacto_model->ingresarContacto($valores);
							$valores['id'] = $datos;
					        $variables['id'] = $datos;
					        $variables['username'] = $valores['id_empresa'].$variables['id'];
					        $modificar = $this->Contacto_model->modificarContacto($variables);
					        $valores['id_pedido'] = $id;
							$relacionar = $this->Pedidos_model->relacionarContactoPedido($valores);
					    }
					    else
					    {
							$tipo = $this->Contacto_model->getTipoContacto($verificar['id']);
							if($tipo['tipo_contacto'] != 2)
							{
								$this->session->set_flashdata('upload', '0');
							}
							else
							{
								$valores['id'] = $verificar['id'];
								$valores['id_pedido'] = $id;
								$relacionar = $this->Pedidos_model->relacionarContactoPedido($valores);
							}
					    }
					} 
					
					if($this->session->flashdata('upload') == '0') 
					{
						$this->session->set_flashdata('resultado', '2');
						$this->session->set_flashdata('mensaje', 'El archivo se subió correctamente, pero hubo usuarios que no se asociaron al pedido por encuentrarse registrados con un perfil no habilitado para esta acción .');
					}
					else
					{
						$this->session->set_flashdata('resultado', '1');
						$this->session->set_flashdata('mensaje', 'El archivo se subió correctamente.');
					}
					$this->session->set_flashdata('empresa', $this->usuario->id_empresa);
					redirect(base_url('cms-v2/elearning/pedidos/detalle/'.$id));
				}
				else
				{
					$this->session->set_flashdata('mensaje', 'error');
					redirect(base_url('cms-v2/elearning/pedidos/subir_archivo/'.$id));
				}
			}
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}

	public function seguimiento($id)
	{
		if ($this->is_logged_in())
		{
			$data['configuracion'] = $this->Configuracion_model->detalleConfiguracion($this->usuario->id_empresa);
			
			// Verificar configuración
			if (!$data['configuracion'] || !isset($data['configuracion']['id']))
			{
				$this->session->set_flashdata('resultado', '0');
				$this->session->set_flashdata('mensaje', 'No se encontró la configuración de su empresa.');
				redirect(base_url('cms-v2/elearning/pedidos/'));
				return;
			}

			// Obtener detalle del pedido
			$parametros['id_pedido'] = $id;
			$data['detalle'] = $this->Pedidos_model->detallePedido($parametros);
			
			if (!$data['detalle'] || !isset($data['detalle']['id']))
			{
				$this->session->set_flashdata('resultado', '0');
				$this->session->set_flashdata('mensaje', 'No se encontró el pedido solicitado.');
				redirect(base_url('cms-v2/elearning/pedidos/'));
				return;
			}

		// Obtener progreso de usuarios
		$data['progreso'] = $this->Pedidos_model->obtenerProgresoUsuarios($id);
		
		// Cargar vista
		$this->load->view('header');
		$this->load->view('cms-v2/'.$data['configuracion']['template'].'/elearning/pedidos/seguimiento', $data);
		$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login/'));
		}
	}
}