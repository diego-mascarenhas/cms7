<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Media extends MY_Controller {

	public $table = 'media';

	/**
	 * Constructor, initializes media model     
	 */
	public function __construct()
	{
		parent::__construct();

		// redirect user to login page if not logged-in
		if (!$this->is_logged_in())
		{
			redirect(base_url('user/login'));
    	}
    	elseif (!$this->usuario->id_empresa)
    	{
	    	redirect(base_url('/errors/'));
    	}
		else
		{
			$this->trackUri();
			
    		$this->session->set_userdata('auth_user', $this->usuario->grupo . '/' . $this->usuario->id_empresa);
    	}
    	
		$this->load->library('base');
		$this->load->helpers(array('directory', 'file', 'form', 'url'));

		// create constant for media controller relative path
		$cn_dir = realpath(FCPATH.'application/controllers');
		$fl_dir = realpath(dirname(__FILE__));
		$path = str_replace($cn_dir, '', $fl_dir);	
		$cn_base = $path . '/media/';		
		define('CN_BASE', $cn_base);

		// app parameters
		$this->load->config('media');
		$media_path = $this->config->item('media_path');
		
		// create constant for user media base directory
		$user_id = $this->session->userdata('auth_user');
		$mm_base = FCPATH . $media_path . '/' . $user_id;
		$mm_base = str_replace(DIRECTORY_SEPARATOR, '/', $mm_base . '/');			
		define('MM_BASE', $mm_base);	

		// create folder to save user media
		if (!is_dir($mm_base))
		{
	    	if (!mkdir($mm_base, 0777, TRUE))
	    	{
	    		exit('Could not create user media directory.');									
	    	}
		}	

		// load model
		$this->load->model('media_model');
	}

	/**
	 * Get media manager settings, load folder structure 
	 * media list of path selected and display the page
	 */
	public function index()
	{
		// get media folder path from POST
		$path = $this->input->post('path');

		if (!$path)
		{
			// if not set then get from session
			$path = $this->session->userdata('path');
		}
		else
		{
			if ($path == 'home')
			{
				$path = null;
				$this->session->unset_userdata('path');
			}
			else
			{
				// switch to specified media folder				
				$this->session->set_userdata('path', $path);
			}
		}

		// get folder tree structure
		$data['foldertree'] = $this->media_model->get_folder_tree();

		// get folders list
		$data['folders'] = $this->media_model->get_folders_list($path);

		// get media files
		$data['media'] = $this->media_model->get_media_list($path);

		// load view
		$data['page'] = 'media/manager';	

		// get logged-in user details
		//$data['user'] = $this->user_model->get_item($this->session->userdata('auth_user'));
		$data['user'] = $this->usuario->grupo;
		
		// set notifications if file(s) uploaded previously
		$this->set_upload_notifications();

		$header['css'] = array(	base_url('assets/css/media.css'),
								base_url('assets/css/plugins/magnificpopup/magnific-popup.css'),
								base_url('assets/css/plugins/mediaelementplayer/mediaelementplayer.min.css'),
								base_url('assets/css/plugins/mediaelementplayer/mejs-skins.min.css')
							);
			
		$this->load->view('header', $header);
		$this->load->view('media/index', $data);
		$this->load->view('footer');
	}

	/**
	 * Method to upload media files
	 */
	public function do_upload()
	{	
		// check if files received				
		if (isset($_FILES['filedata']) && !empty($_FILES['filedata']['tmp_name'])) 
		{    
			// upload files														
			$this->media_model->upload_files($_FILES['filedata']);			
		}
		else
		{
			$this->base->set_message('No files found to upload.', 'error');
		}		

		redirect(CN_BASE.'index', 'refresh');		
	}

	/**
	 * Method to set upload notifications
	 */
	public function set_upload_notifications()
	{
		$count = (int) $this->session->userdata('upload_count');
		$errors = (array) $this->session->userdata('upload_errors');
		
		if ($count) 
		{
			$no_files = ($count > 1) ? 'files' : 'file';
			$message = $count . ' ' . $no_files . ' uploaded successfully';
			$this->base->set_message($message, 'success');
		}

		if ($errors) 
		{
			$message = implode('<br>', $errors);
			$this->base->set_message($message, 'error');
		}

		// Clear session for uploaded file count on every redirect
		$this->session->unset_userdata('upload_count');
		$this->session->unset_userdata('upload_errors');
	}

	/**
	 * Method to create folder in specified media directory
	 */
	public function create_folder()
	{
		// media base path
		$basepath = MM_BASE;

		// get media path
		if ($this->session->userdata('path'))
		{
			$basepath .= '/' . $this->session->userdata('path') . '/'; 
		}

		// get folder name
		$foldername = trim(strip_tags($this->input->post('foldername')));

		// sanitize folder name for . .. ... strings
		$foldername = str_replace('\\', '/', $foldername);
		$tmp = explode('/', $foldername);			
		$tmp = array_filter($tmp);
		$tmp = array_diff($tmp, array('.', '..', '...'));
		$foldername = implode('/', $tmp);

		if ($foldername)
		{
			if ($foldername != 'thumb')
			{ 
				$dir = $basepath . '/' . $foldername;

				// create folder
				if (!is_dir($dir))
				{ 
					if (mkdir($dir, 0777, TRUE))
					{
						$message = 'Folder with name <strong>'.$foldername.'</strong> created successfully';
						$type = 'success';									
					}
					else
					{ 
						$message = 'Could not create folder.';
						$type = 'error';
					}
				}
				else 
				{ 
					$message = 'Folder already exists.';
					$type = 'error';
				}
			}
		}
		else
		{ 
			$message = 'Choose appropriate name for folder.';
			$type = 'warning';
		}

		$this->base->set_message($message, $type);

		redirect(CN_BASE.'index', 'refresh');
	}

	/**
	 * Method to rename file or folder of specified directory
	 */
	public function rename_media()
	{	
		// logged-in user's ID
		$user_id = $this->session->userdata('auth_user');

		// name
		$path = $this->input->post('path');
		$edited_name = $this->input->post('edited_name');
		
		$msg = $type = '';

		if ($path && $edited_name)
		{
			$realpath = realpath(MM_BASE . '/' . $path);
			
			// If absolute path exists
			if ($realpath)
			{
				if (is_file($realpath)) // if file
				{
					// get file name and path	
					$tmp = explode('/', $path);
					$name = end($tmp);
					$mediapath = str_replace($name, '', $path);					

					// get new file name
					$tmp = explode('.', $name);				
					$ext = end($tmp);			
					$newname = $edited_name . '.' . $ext;

					// new file path
					$newpath = MM_BASE . $mediapath . $newname;				

					// thumb path in case file is image	
					$old_thumb_path = MM_BASE . $mediapath . 'thumb/' . $name;
					$new_thumb_path = MM_BASE . $mediapath . 'thumb/' . $newname;
					
					if (!file_exists($newpath))
					{
						$update = array('raw_name' => $edited_name, 'file_name' => $newname);

						// rename file from database
						$result = $this->db->where('user_id',$user_id)
															 ->where('file_path', '/' . $mediapath)
															 ->where('file_name', $name)
															 ->update($this->table, $update);
						
						// rename file from database
						$return = rename($realpath, $newpath);

						if ($result && $return)
						{
							if (file_exists($old_thumb_path)) 
							{
								rename($old_thumb_path, $new_thumb_path);
							}

							$msg = 'Media file renamed successfully';
							$type = 'success';
						} 
						else 
						{
							$msg = 'Unable to rename media file';
							$type = 'danger';
						}											
					} 
					else 
					{						
						$msg = 'Media file already exists';
						$type = 'danger';
					}												
				} 
				elseif (is_dir($realpath)) // if folder
				{	
					// get file name and path	
					$tmp = explode('/', $path);
					array_pop($tmp);
					$tmp[] = $edited_name;
					$mediapath = implode('/', $tmp);
					$newpath = MM_BASE . '/' . $mediapath;					

					if (!file_exists($newpath))
					{
						// rename folder from database							
						$n = strlen($path);						
						$query = "UPDATE " . $this->table . " SET file_path = CONCAT(REPLACE(LEFT(file_path,INSTR(file_path,'/" . $path . "/') + " . ($n+1) . "),
								 '/" . $path . "/','/" . $mediapath . "/'),SUBSTRING(file_path,INSTR(file_path,'/" . $path . "/') + " . ($n+2) . ")) 
								 WHERE file_path LIKE '/" . $path . "/%' AND user_id = " . $user_id . "";						

						$result = $this->db->query($query);

						// rename folder												
						$return = rename($realpath, $newpath);	

						if ($result && $return)
						{
							$msg = 'Media folder renamed successfully';
							$type = 'success';
						} 
						else 
						{
							$msg = 'Unable to rename media folder';
							$type = 'danger';
						}
					} 
					else 
					{
						$msg = 'Folder already exists';
						$type = 'danger';
					}
				}
			} 
			else 
			{
				$msg = 'Invalid media path';
				$type = 'danger';
			}
		} 
		else 
		{
			$msg = 'Invalid path or invalid new name';
			$type = 'warning';
		}

		$this->base->set_message($msg, $type);
	}

	/**
	 * Method to delete media of folders from specified directory
	 */
	public function remove_media()
	{	
		// logged-in user's ID
		$user_id = $this->session->userdata('auth_user');

		$rm_media = $this->input->post('rm');
		
		foreach ($rm_media as $rm)
		{
			// Sanitize file or folder name
			$rm = str_replace('\\', '/', $rm);
			$tmp = explode('/', $rm);			
			$tmp = array_filter($tmp);
			$tmp = array_diff($tmp, array('.','..'));
			$rm = implode('/', $tmp);						

			// If name exists
			if ($rm)
			{				
				$path = realpath(MM_BASE . '/' . $rm);

				// If absolute path exists
				if ($path)
				{
					if (is_file($path)) // if file
					{
						// get file name and path	
						$tmp = explode('/', $rm);
						$file = end($tmp);
						$file_path = str_replace($file, '', $rm);
						$file_path = '/' . $file_path;

						// remove file from database
						$this->db->where('user_id', $user_id);
						$this->db->where('file_path', $file_path);
						$this->db->where('file_name', $file);
						$this->db->delete($this->table);

						if (unlink($path))
						{
							// path to thumb folder file
							$n = count($tmp) - 1;
							$last_el = $tmp[$n];
							$rm_thumb = str_replace($last_el, 'thumb/' . $last_el, $rm);
							$path = MM_BASE . '/' . $rm_thumb;
							unlink($path);

							$msg = 'Media file(s) deleted successfully.';
							$type = 'success';
						}
						else
						{
							$msg = 'Could not delete media file(s).';
							$type = 'danger';
						}	
					} 
					elseif (is_dir($path)) // if folder
					{ 
						// remove folder media from database			
						$file_path = '/' . $rm . '/';

						$this->db->where('user_id', $user_id);
						$this->db->like('file_path', $file_path, 'after');
						$this->db->delete($this->table);

						delete_files($path, TRUE, TRUE);

						if(rmdir($path)) {
							$msg = 'Media folder(s) and its content deleted successfully.';
							$type = 'success';
						}
						else 
						{
							$msg = 'Could not delete media folder(s).';
							$type = 'danger';
						}
					}
				}
				else
				{
					$msg = 'Media does not exists';
					$type = 'danger';
				}	
			}
			else
			{
				$msg = 'Invalid media file or folder name supplied.';
				$type = 'danger';
			}													
		}

		$this->base->set_message($msg, $type);
	}

	
	public function detalle($id)
	{
		if ($this->is_logged_in())
		{
			// helpers
			$this->load->helper('number');
		
			$data['detalle'] = $this->media_model->getMediaDetalle($id);
			$data['detalle']['media_path'] = $this->config->item('media_path');
			
			// validation not ok, send validation errors to the view
			$header['css'] = array(	base_url('assets/css/jwplaye.css')
							);
							
			$this->load->view('header');
			$this->load->view('media/detalle', $data);
			$this->load->view('footer');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function ver($id)
	{
		if ($this->is_logged_in())
		{
			// helpers
			$this->load->helper('number');
		
			$data['detalle'] = $this->media_model->getMediaDetalle($id);
			$data['detalle']['media_path'] = $this->config->item('media_path');
			
							
			$this->load->view('media/ver', $data);
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
	public function test()
	{
		if ($this->is_logged_in())
		{
			$this->load->view('media/test');
		}
		else
		{
			redirect(base_url('user/login'));
		}
	}
	
	
}

/* End of file Media.php */
/* Location: ./application/controllers/Media.php */
