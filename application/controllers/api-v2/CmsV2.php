<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';

class CmsV2 extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        
        // models
        $this->load->model('user_model');
        
        $this->usuario = ($this->rest->user_id > 40000) ? $this->user_model->getUserInfo($this->rest->user_id) : $this->user_model->getServicioInfo($this->rest->user_id);

        // Configure limits on our controller methods
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    //Modulo Agenda
    public function listadofechas_get($id, $pais)
    {
    	// models agenda
    	$this->load->model('agenda_model');
    	$data = $this->agenda_model->getFechasPublico($id, $pais);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    

    public function detallereunion_get($id)
    {
	    // models
		$this->load->model('agenda_model');
		$data = $this->agenda_model->getReunionPublico($id);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    

	public function reunion_post()
	{
		// models
		$this->load->model('agenda_model');
		
		// helpers and libraries
		$this->load->library('form_validation');
		//$this->config->set_item('language', $this->usuario->idioma);
		
		// data
		$this->form_validation->set_data($this->post());

		// set validation rules
		$this->form_validation->set_rules('id_empresa', 'Empresa', 'trim|integer');
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('telefono', 'Teléfono', 'trim');
		$this->form_validation->set_rules('email', 'Email', 'trim|min_length[3]', array('required' => 'Debe ingresar un usuario de máximo 8 caracteres.', 'min_length' => 'Debe ingresar un usuario de al menos 3 caracteres.'));
		$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
		
		if ($this->form_validation->run() === false)
		{
			$data['error'] = str_replace(array("\r", "\n"), '', validation_errors());
			$data['form_errors'] = $this->form_validation->error_array();
		}
		else
		{
			$this->db->trans_begin();

			$data = $this->agenda_model->ingresarReunionPublico($this->post());

			if ($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				
				$data['error'] = 'Ha habido un problema y no se pudo crear la reunión, por favor intenta más tarde';
			}
			else
			{
				$this->db->trans_commit();
			}
		}
		
		$this->response($data);
	}
    //Fin Modulo Agenda

    //Modulo Sitio Web OBA
    public function configuraciondos_get($id = null)
    {
        $this->load->model('cms-v2/configuracion_model');
		$data = $this->configuracion_model->detalleConfiguracionDos($id);		
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function secciones_get($padre = null)
    {
		$this->load->model('cms-v2/paginas_model');
		$data = $this->paginas_model->menu($padre);
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function listadosadicionales_get($id_contenido, $id_categoria = null, $idioma = null)
    {
	    // models
		$this->load->model('cms-v2/paginas_model');
		$parametros['id'] = $id_contenido;
		$parametros['id_tipo'] = $id_categoria;
		$parametros['idioma'] = $idioma;
		$parametros['estado'] = 3;

		$data = $this->paginas_model->getContenidoAdicionalIdioma($parametros);
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function encabezado_get($id_contenido, $idioma = null, $id_imagen)
    {
		$this->load->model('cms-v2/paginas_model');
		$parametros['estado'] = 3;
		
		$data = $this->paginas_model->getEncabezado($id_contenido,$idioma,$id_imagen, $parametros);
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function contenido_get($id_contenido, $idioma = null)
    {
	    // models
		$this->load->model('cms-v2/paginas_model');
		$parametros['estado'] = 3;
		
		$data = $this->paginas_model->getPaginaDetalleIdioma($id_contenido,$idioma,$parametros);
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function contenidourl_get($url, $idioma = null)
    {
	    // models
		$this->load->model('cms-v2/paginas_model');
		$parametros['estado'] = 3;
		
		$data = $this->paginas_model->getPaginaDetalleUrlIdioma($url,$idioma,$parametros);
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function contenidoadicionalurl_get($url, $idioma = null)
    {
	    // models
		$this->load->model('cms-v2/paginas_model');
		$parametros['url'] = $url;
		$parametros['idioma'] = $idioma;
		$parametros['estado'] = 3;
		
		$data = $this->paginas_model->getContenidoAdicionalUrlIdioma($parametros);
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function contenidoadicional_get($id_contenido, $idioma)
    {
	    // models
		$this->load->model('cms-v2/paginas_model');
		$parametros['estado'] = 3;
		$parametros['id_contendio'] = $id_contenido;
		$parametros['idioma'] = $idioma;
		
		$data = $this->paginas_model->getDetalleAdicionalIdioma($parametros);
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    
    public function encabezadoadicional_get($id, $id_contenido, $idioma = null, $id_imagen)
    {
	    // models
		$this->load->model('cms-v2/paginas_model');
		$parametros['id'] = $id;
		$parametros['id_contenido'] = $id_contenido;
		$parametros['idioma'] = $idioma;
		$parametros['id_imagen'] = $id_imagen;
		$parametros['estado'] = 3;
		
		$data = $this->paginas_model->getEncabezadoAdicional($parametros);
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function archivoinformacion_get($id, $idioma)
    {
		$this->load->model('cms-v2/paginas_model');
		
		$data = $this->Paginas_model->getArchivo($id, $idioma);

		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function noticiastotal_get($id_tipo, $idioma, $categoria=null, $filtro1 = null)
    {
	    // models
		$this->load->model('cms-v2/informacion_model');
		$parametros['tipo'] = $id_tipo;
		$parametros['idioma'] = $idioma;
		$parametros['estado'] = 3;

		if($categoria)
		{
			$parametros['categoria'] = $categoria;
		}

		if($filtro1)
		{
			$parametros['filtro1'] = $filtro1;
		}

		$data = $this->informacion_model->getContenidosTotalesPublic($parametros);
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
    }
   
    public function noticiastest_get($id_tipo, $idioma, $offset=null)
    {
	    // models
		$this->load->model('cms-v2/informacion_model');
		$parametros['tipo'] = $id_tipo;
		$parametros['idioma'] = $idioma;
		
		if($offset)
		{
			$parametros['offset'] = $offset;
		}
		$parametros['estado'] = 3;


		$data = $this->informacion_model->getContenidosPublicDos($parametros);
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
/*
        else
        {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
*/
    }
        
    public function noticias_get($id_tipo, $idioma, $categoria=null, $limit = null, $start = null)
    {
	    // models
		$this->load->model('cms-v2/informacion_model');
		$parametros['tipo'] = $id_tipo;
		$parametros['idioma'] = $idioma;
		$parametros['estado'] = 3;

		if($categoria != 'null')
		{
			$parametros['categoria'] = $categoria;
		}

		if($start)
		{
			$parametros['start'] = $start;
		}
		
		if($limit)
		{
			$parametros['limit'] = $limit;
		}
		
		$data = $this->informacion_model->getContenidosPublic($parametros);
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
/*
        else
        {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
*/
    }

    public function destacados_get($id_tipo, $idioma, $limit = null)
    {
	    // models
		$this->load->model('cms-v2/informacion_model');
		$parametros['tipo'] = $id_tipo;
		$parametros['idioma'] = $idioma;
		$parametros['estado'] = 3;
		$parametros['limit'] = $limit;
		$parametros['destacado'] = 1;
		
		$data = $this->informacion_model->getContenidosPublic($parametros);
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
/*
        else
        {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
*/
    }


    public function filtros_get($id_tipo)
    {
	    // models
		$this->load->model('cms-v2/informacion_model');
		
		$parametros['tipo'] = $id_tipo;
		$parametros['estado'] = 3;
		
		$data = $this->informacion_model->getCategorias($parametros);
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function eventos_get($idioma, $imagen, $destacado=null)
    {
	    // models
		$this->load->model('cms-v2/eventos_model');
		$parametros['idioma'] = $idioma;
		$parametros['estado'] = 3;
		if($imagen == 1) { $parametros['imagen'] = 1; }
		if($destacado == 1) { $parametros['destacado'] = 1; }

		$data = $this->eventos_model->getEventosPublic($parametros);
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    
    public function modal_get($idioma)
    {
	    // models
		$this->load->model('cms-v2/eventos_model');
		$parametros['idioma'] = $idioma;
		$parametros['estado'] = 3;
		$parametros['modal'] = 1;

		$data = $this->eventos_model->getEventosPublic($parametros);
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function detalleinformacion_get($idioma, $url)
    {
	    // models
		$this->load->model('cms-v2/informacion_model');
		
		$data = $this->informacion_model->getDetallePublic($idioma, $url);
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function imageninformacion_get($id, $idioma, $tipo)
    {
	    // models
		$this->load->model('cms-v2/informacion_model');
		
		$data = $this->informacion_model->getMedia($id, $idioma, $tipo, 3);
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function buscar_post()
    {
	    // models
    	$this->load->model('cms-v2/paginas_model');
		$parametros['estado'] = 3;
		$parametros['idioma'] = $this->input->post('idioma');
		$parametros['busqueda'] = $this->input->post('busqueda');

    	$data = $this->paginas_model->getBusqueda($parametros);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }
    /* Fin versión para OBA */

    public function configuracion_get($id = null)
    {
	    // models
        $this->load->model('cms-v2/configuracion_model');
        
		$data = $this->configuracion_model->detalleConfiguracion(7358);
		
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }
         
    public function listado_get($id = null)
    {
		if(isset($id))
	    {
	    	if($id == 3)
	    	{
		    	// models
		    	$this->load->model('cms-v2/cursos_model');
		    	$data = $this->cursos_model->listadoContenidos(3);
		    }
			elseif($id == 5)
		    {
		    	// models
		    	$this->load->model('cms-v2/noticias_model');
		    	$data = $this->noticias_model->listadoContenidos(3);
			}
			elseif($id == 1)
		    {
		    	// models
		    	$this->load->model('cms-v2/noticias_model');
		    	$data = $this->noticias_model->listadoCategorias(3);
			}
			elseif($id == 100)
		    {
		    	// models charlas
		    	$this->load->model('cms-v2/contenidos_model');
		    	$data = $this->contenidos_model->listadoItemsPublic(5);
			}
			elseif($id == 200)
		    {
		    	// models dudas
		    	$this->load->model('cms-v2/contenidos_model');
		    	$data = $this->contenidos_model->listadoItemsPublic(6);
			}
		}
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    
    
    public function detallecurso_get($id = null)
    {
	    // models
        $this->load->model('cms-v2/cursos_model');
		$data = $this->cursos_model->detalleContenidoUrl($id);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    

    public function detallecursoperfil_get($id = null)
    {
	    // models
        $this->load->model('cms-v2/cursos_model');
		$data = $this->cursos_model->detalleContenido($id);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    

    public function miscursos_get($id = null)
    {
	    // models
        $this->load->model('cms-v2/pedidos_model');
		$data = $this->pedidos_model->listadoMisCursos($id);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    

    public function detallecategorias_get($id)
    {
	    // models
        $this->load->model('cms-v2/noticias_model');
		$data = $this->noticias_model->detalleCategoria($id);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    

    public function categorias_get($filtro)
    {
	    // models
        $this->load->model('cms-v2/noticias_model');
		$data = $this->noticias_model->listadoCategoriasItems($filtro);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    

   public function favoritos_get($usuario, $curso)
    {
	    // models
        $this->load->model('cms-v2/cursos_model');
		$data = $this->cursos_model->detalleFavorito($usuario, $curso);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    

 	public function ingresarfavorito_post()
	{		
		// models
		$this->load->model('cms-v2/cursos_model');
		$this->db->trans_begin();
		$data = $this->cursos_model->ingresarFavorito($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$data['error'] = 'Ha habido un problema y no se pudo crear el pedido, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
	}

   public function detallenoticia_get($id = null)
    {
	    // models
        $this->load->model('cms-v2/noticias_model');
		$data = $this->noticias_model->detalleContenidoUrl($id);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    

    public function detallecharla_get($id = null)
    {
	    // models
    	$this->load->model('cms-v2/contenidos_model');
		$data = $this->contenidos_model->detalleItemUrl($id);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    
       
    public function listadoitemscurso_get($id,$id_tipo)
    {
    	// models
    	$this->load->model('cms-v2/cursos_model');
    	$data = $this->cursos_model->listadoContenidosAdicionales($id,$id_tipo,'es',3);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    

	public function detallebusqueda_post()
	{
	    // models
    	$this->load->model('cms-v2/buscador_model');
    	$data = $this->buscador_model->listadoContenidos($this->post());
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
	}


    public function listadohome_get($id)
    {
    	if($id == 29)
    	{
	    	// models
	    	$this->load->model('cms-v2/contenidos_model');
	    	$data = $this->contenidos_model->listadoContenidosAdicionales($id,3,'es',3);
    	}
    	else
    	{
	    	// models
	    	$this->load->model('cms-v2/contenidos_model');
	    	$data = $this->contenidos_model->listadoContenidosAdicionales(29,4,'es',3);
	    }
    			
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    


    public function listadoimagenesdos_get($id,$cantidad)
    {
    	if($id == 36)
    	{
	    	// models
	    	$this->load->model('cms-v2/contenidos_model');
	    	$data = $this->contenidos_model->listadoMediaCantidad(36, 2);
    	}
    	else
    	{
	    	// models
	    	$this->load->model('cms-v2/cursos_model');
	    	$data = $this->cursos_model->listadoMediaApi($id);
	    }
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    


    public function listadoimagenes_get($id)
    {
    	if($id == 29)
    	{
	    	// models
	    	$this->load->model('cms-v2/contenidos_model');
	    	$data = $this->contenidos_model->listadoMedia($id, 3);
    	}
    	elseif($id == 229)
    	{
	    	// models
	    	$this->load->model('cms-v2/contenidos_model');
	    	$data = $this->contenidos_model->listadoMedia(29, 2);
    	}
    	elseif($id == 36)
    	{
	    	// models
	    	$this->load->model('cms-v2/contenidos_model');
	    	$data = $this->contenidos_model->listadoMedia(36, 2);
    	}
    	else
    	{
	    	// models
	    	$this->load->model('cms-v2/cursos_model');
	    	$data = $this->cursos_model->listadoMediaApi($id);
	    }
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    

    public function relacionados_get($id = null)
    {
    	// models
    	$this->load->model('cms-v2/cursos_model');
    	$data = $this->cursos_model->listadoRelacionadosApi($id);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    

    public function detallecontenido_get($id = null)
    {
	    // models
        $this->load->model('cms-v2/contenidos_model');
		$data = $this->contenidos_model->detalleItem($id);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    


	public function login_post()
    {
	    $this->load->model('cms-v2/contacto_model');
	    
	    $username = $this->input->post('user');
	    $password = $this->input->post('pass');
	    
	    if ($this->contacto_model->userLogin($username, $password))
		{
			$id = $this->contacto_model->getUserIdFromUsername($username);
			$data = (array) $this->contacto_model->getContactoDetalle($id);
		}
		else
		{
			$data = 'Error';
		}
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }

	public function user_get($id = null)
	{
		$this->load->model('cms-v2/contacto_model');

		if ($id)
		{
			$data = $this->contacto_model->getContactoDetalle($id);
		}
		else
		{
			$parametros['search'] = $this->input->get('search');
			$parametros['estado'] = $this->input->get('estado');
			
			$parametros['order_by'] = $this->input->get('order_by');
			$parametros['order'] = $this->input->get('order');
			
			$parametros['page'] = $this->input->get('page');
			$parametros['per_page'] = $this->input->get('per_page');
			
			$data = $this->contacto_model->getContactos($parametros);
		}

		$this->response($data);
	}

	public function user_post()
	{
		// models
		$this->load->model('cms-v2/contacto_model');
		
		// helpers and libraries
		$this->load->library('form_validation');
		//$this->config->set_item('language', $this->usuario->idioma);
		
		// data
		$this->form_validation->set_data($this->post());

		// set validation rules
		$this->form_validation->set_rules('id_empresa', 'Empresa', 'trim|integer');
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|required|min_length[3]');
		$this->form_validation->set_rules('apellido', 'Apellido', 'trim|min_length[3]');
		$this->form_validation->set_rules('sexo', 'Sexo', 'trim|in_list[M,F]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('telefono', 'Teléfono', 'trim');
		$this->form_validation->set_rules('celular', 'Celular', 'trim');
		
		$this->form_validation->set_rules('area_privada', 'Area privada', 'trim|integer');
		$this->form_validation->set_rules('email', 'Usuario', 'trim|min_length[3]|is_unique[con_contactos.username]', array('required' => 'Debe ingresar un usuario de máximo 8 caracteres.', 'min_length' => 'Debe ingresar un usuario de al menos 3 caracteres.', 'is_unique' => 'El usuario ya está registrado. Ingrese un usuario diferente.'));
		$this->form_validation->set_rules('password', 'Contraseña', 'trim|min_length[3]');
		$this->form_validation->set_rules('timezone', 'Zona horaria', 'trim');
		$this->form_validation->set_rules('idioma', 'Idioma', 'trim|min_length[4]');
		
		$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
		
		if ($this->form_validation->run() === false)
		{
			$data['error'] = str_replace(array("\r", "\n"), '', validation_errors());
			$data['form_errors'] = $this->form_validation->error_array();
		}
		else
		{
			$this->db->trans_begin();

			$data = $this->contacto_model->ingresarContacto($this->post());

			if ($this->db->trans_status() === false)
			{
				$this->db->trans_rollback();
				
				$data['error'] = 'Ha habido un problema y no se pudo crear el contacto, por favor intenta más tarde';
			}
			else
			{
				$this->db->trans_commit();
			}
		}
		
		$this->response($data);
	}


	public function user_put($id = null)
	{
		// models
		$this->load->model('cms-v2/contacto_model');

		// helpers and libraries
		$this->load->library('form_validation');
		$this->config->set_item('language', $this->usuario->idioma);
		
		// data
		$this->form_validation->set_data($this->put());
			
		// set validation rules
		$this->form_validation->set_rules('id_empresa', 'Empresa', 'trim|integer');
		$this->form_validation->set_rules('nombre', 'Nombre', 'trim|min_length[3]');
		$this->form_validation->set_rules('apellido', 'Apellido', 'trim|min_length[3]');
		$this->form_validation->set_rules('sexo', 'Sexo', 'trim|in_list[M,F]');
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
		$this->form_validation->set_rules('telefono', 'Teléfono', 'trim');
		$this->form_validation->set_rules('celular', 'Celular', 'trim');
		
		$this->form_validation->set_rules('area_privada', 'Area privada', 'trim|integer');
		$this->form_validation->set_rules('username', 'Usuario', 'trim|min_length[3]');
		$this->form_validation->set_rules('password', 'Contraseña', 'trim|min_length[3]');
		$this->form_validation->set_rules('timezone', 'Zona horaria', 'trim');
		$this->form_validation->set_rules('idioma', 'Idioma', 'trim|min_length[4]');
		
		$this->form_validation->set_rules('estado', 'Estado', 'trim|integer');
		
		if ($this->form_validation->run() === false)
		{
			$data['error'] = str_replace(array("\r", "\n"), '', validation_errors());
			$data['form_errors'] = $this->form_validation->error_array();
		}
		else
		{
			$data = $this->contacto_model->modificarContacto($id, $this->put());
		}
		
		$this->response($data);
	}
    
	public function detalleemail_post()
	{
	    // models
		$this->load->model('cms-v2/contacto_model');
		$data = $this->contacto_model->traerUserFromUsername($this->post());
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
	}

	public function tester_post()
	{		
		// models
		$this->load->model('cms-v2/regalos_model');
		$this->db->trans_begin();
		$data = $this->regalos_model->ingresarPedido($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$data['error'] = 'Ha habido un problema y no se pudo crear el pedido, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
	}

	public function ingresarpedido_post()
	{		
		// models
		$this->load->model('cms-v2/pedidos_model');
		$this->db->trans_begin();
		$data = $this->pedidos_model->ingresarPedido($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$data['error'] = 'Ha habido un problema y no se pudo crear el pedido, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
	}
	

	public function duplicarpedido_post()
	{		
		// models
		$this->load->model('cms-v2/pedidos_model');
		$this->db->trans_begin();
		$data = $this->pedidos_model->duplicarPedido($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$data['error'] = 'Ha habido un problema y no se pudo crear el pedido, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
	}

	public function finalizarpedido_post()
	{		
		// models
		$this->load->model('cms-v2/pedidos_model');
		$this->db->trans_begin();
		$data = $this->pedidos_model->finalizarPedido($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$data['error'] = 'Ha habido un problema y no se pudo crear el pedido, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
	}

	public function finalizarpedidobonificado_post()
	{		
		// models
		$this->load->model('cms-v2/pedidos_model');
		$this->db->trans_begin();
		$data = $this->pedidos_model->finalizarPedidoBonificado($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$data['error'] = 'Ha habido un problema y no se pudo crear el pedido, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
	}

    public function detallepedido_get($id = null)
    {
	    // models
        $this->load->model('cms-v2/pedidos_model');
		$data = $this->pedidos_model->detallePedido($id);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    

    public function detallepedidoid_get($id = null)
    {
	    // models
        $this->load->model('cms-v2/pedidos_model');
		$data = $this->pedidos_model->detallePedidoId($id);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    

    public function pedidobenid_get($id = null)
    {
	    // models
        $this->load->model('cms-v2/pedidos_model');
		$data = $this->pedidos_model->PedidoBeneficiarioId($id);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    } 

    public function detallepedidoitemsid_get($id = null)
    {
	    // models
        $this->load->model('cms-v2/pedidos_model');
		$data = $this->pedidos_model->listadoPedidoItems($id);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    } 
       
    public function detalleitemcompra_get($id = null)
    {
	    // models
        $this->load->model('cms-v2/pedidos_model');
		$data = $this->pedidos_model->detalleItemCompra($id);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    

    public function listadopedidos_get($id = null)
    {
	    // models
        $this->load->model('cms-v2/pedidos_model');
		$data = $this->pedidos_model->listadoPedidos($id);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    

    public function listadofavoritos_get($id = null)
    {
	    // models
        $this->load->model('cms-v2/contacto_model');
		$data = $this->contacto_model->listadoFavoritos($id);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    

    public function listadopedidoitems_get($id = null)
    {
	    // models
        $this->load->model('cms-v2/pedidos_model');
		$data = $this->pedidos_model->listadoPedidoItemsUser($id);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    

    public function totalpedido_get($id = null)
    {
	    // models
        $this->load->model('cms-v2/pedidos_model');
		$data = $this->pedidos_model->totalPedido($id);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    

	public function ingresaritem_post()
	{		
		// models
		$this->load->model('cms-v2/pedidos_model');
		$this->db->trans_begin();
		$data = $this->pedidos_model->ingresarItem($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$data['error'] = 'Ha habido un problema y no se pudo crear el pedido, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
	}

	public function certificar_post()
	{		
		// models
		$this->load->model('cms-v2/pedidos_model');
		$this->db->trans_begin();
		$data = $this->pedidos_model->ingresarCertificado($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$data['error'] = 'Ha habido un problema y no se pudo crear el pedido, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
	}

	public function eliminaritem_post()
	{		
		// models
		$this->load->model('cms-v2/pedidos_model');
		$this->db->trans_begin();
		$data = $this->pedidos_model->eliminarItem($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$data['error'] = 'Ha habido un problema y no se pudo eliminar el pedido, por favor intenta más tarde';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
	}

	public function detallecupon_get($id = NULL)
	{		
	    // models
        $this->load->model('cms-v2/pedidos_model');
		$data = $this->pedidos_model->detalleCupon($id);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    

	public function ingresarcupon_post()
	{		
		// models
		$this->load->model('cms-v2/pedidos_model');
		$this->db->trans_begin();
		$data = $this->pedidos_model->ingresarCupon($this->post());

		if ($this->db->trans_status() === false)
		{
			$this->db->trans_rollback();
			$data['error'] = 'Ha habido un problema y no se pudo ingresar el cupón, por favor intenta más tarde.';
		}
		else
		{
			$this->db->trans_commit();
		}
		$this->response($data);
	}
	
	//LISTADO SERVICIOS
    public function listadoservicios_get($seccion, $idioma = null, $padre = null, $id_tipo = null)
    {
	    // models
		$this->load->model('cms-v2/servicios/servicios_model');
		$parametros['seccion'] = $seccion;
		$parametros['idioma'] = $idioma;
		$parametros['estado'] = 3;
		$parametros['id_tipo'] = $id_tipo;
		if($padre > 0)
		{
			$parametros['padre'] = $padre;
		}
		
		$data = $this->servicios_model->getServicios($parametros);
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

	//DETALLE SERVICIOS
    public function servicio_get($id_servicio, $idioma = null)
    {
	    // models
		$this->load->model('cms-v2/servicios/servicios_model');
		$parametros['id'] = $id_servicio;
		$parametros['idioma'] = $idioma;
		$parametros['estado'] = 3;
		
		$data = $this->servicios_model->getServicioDetalleIdioma($parametros);
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

	//DETALLE SERVICIOS
    public function serviciofiltro_get($filtro1, $filtro2 = null, $idioma = null)
    {
		$this->load->model('cms-v2/servicios/servicios_model');
		$parametros['filtro1'] = $filtro1;
		$parametros['filtro2'] = $filtro2;
		$parametros['estado'] = 3;
		
		$data = $this->servicios_model->getServicioDetalleFiltros($parametros);
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

	//SERVICIOS DESTACADOS
    public function serviciosdestacados_get($limit, $idioma)
    {
	    // models
		$this->load->model('cms-v2/servicios/servicios_model');
		$parametros['idioma'] = $idioma;
		$parametros['estado'] = 3;
		$parametros['limit'] = 3;

		$data = $this->servicios_model->getServiciosDestacados($parametros);
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

	//SERVICIOS IMAGEN
    public function serviciosimagen_get($id, $id_tipo, $idioma)
    {
		$this->load->model('cms-v2/servicios/servicios_model');
		$parametros['id'] = $id;
		$parametros['id_tipo'] = $id_tipo;
		$parametros['idioma'] = $idioma;
		
		$data = $this->servicios_model->getMedia($parametros);
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

 	//SERVICIOS ARCHIVO
    public function serviciosarchivo_get($id, $idioma)
    {
		$this->load->model('cms-v2/servicios/servicios_model');
		$parametros['id'] = $id;
		$parametros['idioma'] = $idioma;
		
		$data = $this->servicios_model->getArchivo($parametros);
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

   public function listadosserviciosadicionales_get($id_contenido, $id_categoria = null, $idioma = null)
   {
		$this->load->model('cms-v2/servicios/servicios_model');
		$parametros['id'] = $id_contenido;
		$parametros['id_tipo'] = $id_categoria;
		$parametros['idioma'] = $idioma;
		$parametros['estado'] = 3;

		$data = $this->servicios_model->getServicioAdicionalIdioma($parametros);
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

	//LISTADO CONTENIDOS 
    public function listadopaginas_get($padre, $idioma)
    {
	    // models
		$this->load->model('cms-v2/paginas_model');
		$parametros['padre'] = $padre;
		$parametros['idioma'] = $idioma;
		
		$data = $this->paginas_model->getPaginasPublic($parametros);
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function listadointernas_get($padre, $imagen_tipo, $idioma)
    {
		$this->load->model('cms-v2/paginas_model');
		$parametros['padre'] = $padre;
		$parametros['idioma'] = $idioma;
		$parametros['imagen_tipo'] = $imagen_tipo;
		
		$data = $this->paginas_model->getPaginasInternas($parametros);
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function relserviciosadicionales_get($id, $idioma)
    {
    	$this->load->model('cms-v2/servicios/servicios_model');
		$parametros['id'] = $id;
		$parametros['idioma'] = $idioma;
		$parametros['estado'] = 3;
    	
    	$data = $this->servicios_model->listadoRelacionadosAdicionales($parametros);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    

    public function relsinformacionadicionales_get($id, $idioma)
    {
    	$this->load->model('cms-v2/paginas_model');
		$parametros['id'] = $id;
		$parametros['idioma'] = $idioma;
		$parametros['estado'] = 3;
    	
		$data = $this->paginas_model->getInformacionRelacionados($parametros);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    

    public function paises_get($estado = null)
    {
    	$this->load->model('cms-v2/paginas_model');
    	
		$data = $this->paginas_model->comboPaises($estado);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    

    public function provincias_get($pais)
    {
    	$this->load->model('sys_model');
    	
		$data = $this->sys_model->comboProvincias($pais);
		
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response(null, REST_Controller::HTTP_NO_CONTENT);
        }
    }    
    
    public function imagencontenido_get($id, $idioma, $tipo)
    {
	    // models
		$this->load->model('cms-v2/paginas_model');
		
		$data = $this->paginas_model->getMedia($id, $idioma, $tipo);
		if (isset($data['error']))
        {
	        $this->response([
                'status' => false,
                'message' => $data['error']
            ], REST_Controller::HTTP_FORBIDDEN);
            
        }
        elseif (isset($data))
        {
            $this->response($data, REST_Controller::HTTP_OK);
        }
        else
        {
            $this->response([
                'status' => false,
                'message' => 'No se encontraron registros'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
}