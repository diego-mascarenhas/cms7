<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="<?php echo ($this->session->userdata('lang') == 'english') ? 'en' : 'es'; ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>revision alpha CMS+</title>
    <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('assets/font-awesome/css/font-awesome.css'); ?>" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url('assets/css/animate.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('assets/css/style.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('assets/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css'); ?>" rel="stylesheet" type="text/css">

    <?php if (isset($css)) foreach ($css as $obj) echo '<link href="' . $obj . '" rel="stylesheet" type="text/css">'; ?>

	<!-- Skin de grupo -->
	<?php if (file_exists(FCPATH . 'multimedia/' . $this->usuario->grupo . '/style.css')) echo '<link href="' . base_url('multimedia/' . $this->usuario->grupo . '/style.css') . '" rel="stylesheet" type="text/css">'; ?>
     
    <!-- Summernote -->
	<link href="<?php echo base_url('assets/css/plugins/summernote/summernote.css'); ?>" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url('assets/css/plugins/summernote/summernote-bs3.css'); ?>" rel="stylesheet" type="text/css">

    <!-- Carga de Imagenes -->
	<link href="<?php echo base_url('assets/css/plugins/dropzone/basic.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('assets/css/plugins/dropzone/dropzone.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('assets/css/plugins/jasny/jasny-bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">

</head>

<body>
	<!-- Mainly scripts -->
    <script src="<?php echo base_url('assets/js/jquery-2.1.1.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/metisMenu/jquery.metisMenu.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/slimscroll/jquery.slimscroll.min.js'); ?>"></script>

    <!-- Custom and plugin javascript -->
    <script src="<?php echo base_url('assets/js/inspinia.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/plugins/pace/pace.min.js'); ?>"></script>
    

  <script type="text/javascript">
		function setMiniNavBar() {
			if($('body').hasClass('mini-navbar')) {
				localStorage.setItem('miniNavbar', 0);
			} else {
				localStorage.setItem('miniNavbar', 1);
			}
		}
		
		(function() {
			(typeof localStorage.miniNavbar === 'undefined') ? localStorage.setItem('miniNavbar', 0) : null;
		
			if(localStorage.miniNavbar == 1) {
				$('body').addClass('mini-navbar');
			}	
		}) ();		
	</script>
    
    <div id="wrapper">
    	<nav class="navbar-default navbar-static-side" role="navigation">
	        <div class="sidebar-collapse">
	            <ul class="nav metismenu" id="side-menu">
	                <li class="nav-header">
	                    <div class="dropdown profile-element"> 
		                    <?php if (file_exists(FCPATH . 'multimedia/' . $this->usuario->grupo . '/logo.png')) echo  '<span><a href="' . base_url() . '"><img width="170px" src="' . base_url('multimedia/' . $this->usuario->grupo . '/logo.png') . '" /></a></span>'; ?>
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            	<span class="clear"> 
                            		<span class="block m-t-xs"> <strong class="font-bold"><?php echo $this->usuario->contacto; ?></strong> </span> 
									<span class="text-muted text-xs block"><?php echo $this->usuario->perfil; ?> <b class="caret"></b></span> 
								</span> 
							</a>
							
							<ul class="dropdown-menu animated fadeInRight m-t-xs">
                            	<?php if ($this->usuario->perfil == 'admin' || $this->usuario->perfil == 'user') : ?>
                            		<li><a href="<?php echo base_url('micuenta/perfil'); ?>">Perfil</a></li>
                            	<?php endif; ?>
                            	<li><a href="<?php echo base_url('micuenta/perfil/idioma'); ?>">Idioma</a></li>
								<li><a href="<?php echo base_url('micuenta/perfil/password'); ?>">Contraseña</a></li>
								<li class="divider"></li>
								<li><a href="<?php echo base_url('user/logout'); ?>">Logout</a></li>
                        	</ul>
	                    </div>
	                    <div class="logo-element">
	                        CMS+
	                    </div>
	                </li>
	                
	                <?php
		                
						function menuVista($menu, $nivel=null)
						{
							$CI =& get_instance();
							
							?>
							<?php foreach($menu as $obj): ?>
								<li <?php if ($CI->uri->slash_segment($obj['nivel']) == $obj['item'] . '/') echo 'class="active"'; ?>>
			                    	<a href="<?php echo (isset($obj['uri'])) ? base_url($obj['uri']) : '#'; ?>">
				                    	<?php if (isset($obj['ui_class'])) { ?><i class="<?php echo $obj['ui_class']; ?>"></i><?php } ?>
				                    	<?php if (!isset($nivel)) { ?>
				                    		<span class="nav-label"><?php echo ($texto = $CI->lang->line('cms_' . $obj['item'], $CI->usuario->contacto)) ? $texto : $obj['item']; ?></span>
				                    	<?php } else { ?>
				                    		<?php echo ($texto = $CI->lang->line('cms_' . $obj['item'], $CI->usuario->contacto)) ? $texto : $obj['item']; ?>
				                    	<?php } ?>
				                    	<?php if (isset($obj['hijos'])): ?><span class="fa arrow"></span><?php endif; ?>
				                	</a>
				                	<?php if (isset($obj['hijos'])): ?>
				                		<?php
					                		switch ($obj['nivel'])
					                		{
					                			case 2:
					                				$level_ui_class = 'nav-third-level';
					                				break;
					                			case 3:
					                				$level_ui_class = 'nav-fourth-level';
					                				break;
					                			default:
					                				$level_ui_class = 'nav-second-level';
					                				break;
					                		}
				                		?>
				                		<ul class="nav <?php echo $level_ui_class; ?> collapse <?php if ($CI->uri->slash_segment($obj['nivel']) == $obj['item'] . '/') echo 'in'; ?>">
				                			<?php menuVista($obj['hijos'], $obj['nivel']); ?>
				                		</ul>
				                	<?php endif; ?>
								</li>
							<?php endforeach; ?>
							<?php 
						}
	                
	                	if ($menu = $this->session->userdata('menu')) menuVista($menu);
	                ?>
	                <!-- Para agregar en views/header.php seccion Landings  -->
                    <li<?php if($this->uri->segment(1) == 'landings') { echo ' class="active"';}?>>
	                    <a href="/cms-v2/dashboard"><i class="fa fa-desktop"></i> <span class="nav-label">Sitio web</span></a>
                    </li>
                	<!-- Fin para agregar en views/header.php seccion Landings  -->

	                <!-- Sitio web Nardelli -->
	                <?php if ($this->usuario->id_empresa == 7325 || $this->usuario->id_empresa ==  7358) { ?>
                    <li<?php if($this->uri->segment(1) == 'cms-v2') { echo ' class="active"';}?>>
	                    <a><i class="fa fa-globe"></i> <span class="nav-label">Sitio web</span> <span class="fa arrow"></span></a>
	                    <ul class="nav nav-second-level">
		                    <li<?php if($this->uri->segment(2) == 'dashboard') { echo ' class="active"';}?>>
			                    <a href="/cms-v2/dashboard"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span> </a>
			                </li>
		                    <li<?php if($this->uri->segment(2) == 'cursos') { echo ' class="active"';}?>>
			                    <a href="/cms-v2/cursos"><i class="fa fa-book"></i> <span class="nav-label">Cursos</span> </a>
			                </li>
		
		                    <li<?php if($this->uri->segment(2) == 'contenidos') { echo ' class="active"';}?>>
			                    <a href="/cms-v2/contenidos"><i class="fa fa-edit"></i> <span class="nav-label">Contenidos</span> </a>
			                </li>
		
		                    <li<?php if($this->uri->segment(2) == 'secciones') { echo ' class="active"';}?>>
			                    <a href="/cms-v2/secciones"><i class="fa fa-sitemap"></i> <span class="nav-label">Secciones</span> </a>
			                </li>
		
		                    <li<?php if($this->uri->segment(2) == 'blog') { echo ' class="active"';}?>>
			                    <a href="/cms-v2/noticias"><i class="fa fa-calendar"></i> <span class="nav-label">Blog</span> </a>
			                </li>
		
		                    <li<?php if($this->uri->segment(2) == 'configuracion') { echo ' class="active"';}?>>
			                    <a href="/cms-v2/configuracion/ingresar/1"><i class="fa fa-cog"></i> <span class="nav-label">Configuración</span> </a>
			                </li>
		
		                    <li<?php if($this->uri->segment(2) == 'cupones') { echo ' class="active"';}?>>
			                    <a href="/cms-v2/cupones"><i class="fa fa-dollar"></i> <span class="nav-label">Cupones</span> </a>
			                </li>
		                    <li<?php if($this->uri->segment(2) == 'pedidos') { echo ' class="active"';}?>>
			                    <a href="/cms-v2/pedidos"><i class="fa fa-shopping-cart"></i> <span class="nav-label">Pedidos</span> </a>
			                </li>
		
		                    <li<?php if($this->uri->segment(2) == 'usuarios') { echo ' class="active"';}?>>
			                    <a href="/cms-v2/usuarios"><i class="fa fa-users"></i> <span class="nav-label">Usuarios</span> </a>
			                </li>
		                </ul>
	                </li>
	                <?php } ?>
	                <!-- Fin Sitio web Nardelli -->

	            </ul>
	
	        </div>
	    </nav>

        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
            	<nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
			        <div class="navbar-header">
			            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary" href="#" onclick="setMiniNavBar();"><i class="fa fa-bars"></i> </a>
			            <?php if (isset($buscador)) { ?>
			            <form role="search" class="navbar-form-custom">
			                <div class="form-group">
			                    <input type="text" placeholder="Buscar..." class="form-control" name="search" id="top-search" value="<?php echo $this->input->get('search'); ?>">
			                </div>
			            </form>
			            <?php } ?>
			        </div>
			            
			        <ul class="nav navbar-top-links navbar-right">
			            <li>
			                <span class="m-r-sm text-muted welcome-message"><?php echo sprintf($this->lang->line('cms_bienvenido'), $this->usuario->contacto); ?></span>
			            </li>
			            
			            <?php if ($this->session->userdata('alertas')) { ?>
						<li class="dropdown">
			                <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
			                    <i class="fa fa-bell"></i>  <span class="label label-danger"><?php echo count($this->session->userdata('alertas')); ?></span>
			                </a>
			                <ul class="dropdown-menu dropdown-alerts">
				                <?php if (isset($this->session->userdata('alertas')['contacto'])) { ?>
			                    <li>
			                        <a href="<?php echo base_url('micuenta/perfil/password'); ?>">
			                            <div>
			                                <i class="fa fa-lock fa-fw"></i> Usuario sin contraseña
			                            </div>
			                        </a>
			                    </li>
			                    <?php } ?>
			                    <?php if (isset($this->session->userdata('alertas')['empresa'])) { ?>
			                    <li>
			                        <a href="<?php echo base_url('micuenta/perfil'); ?>">
			                            <div>
			                                <i class="fa fa-archive fa-fw"></i> Datos de perfil incompletos
			                            </div>
			                        </a>
			                    </li>
			                    <?php } ?>
			                    <?php if (isset($this->session->userdata('alertas')['saldo'])) { ?>
			                    <li>
			                        <a href="<?php echo base_url('micuenta'); ?>">
			                            <div>
			                                <i class="fa fa-usd fa-fw"></i> Pagos pendientes
			                            </div>
			                        </a>
			                    </li>
			                    <?php } ?>
			                    <?php if (isset($this->session->userdata('alertas')['ip'])) { ?>
			                    <li>
			                        <a href="<?php echo base_url('ayuda/desbloquear-ip'); ?>">
			                            <div>
			                                <i class="fa fa-globe fa-fw"></i> Tu conexión se encuentra bloqueada
			                            </div>
			                        </a>
			                    </li>
			                    <?php } ?>
			                </ul>
			            </li>
			            <?php } ?>
			
			            <li>
			                <a href="<?php echo base_url('user/logout'); ?>">
			                    <i class="fa fa-sign-out"></i> Log out
			                </a>
			            </li>
			            <?php if ($this->usuario->perfil == 'reseller') { ?>
				        <li>
			                <a class="right-sidebar-toggle count-info">
			                    <i class="fa fa-tasks"></i>
			                    <?php if ($this->session->userdata('notas')) { ?>
				                    <span class="label label-danger"><?php echo count($this->session->userdata('notas')); ?></span>
				                <?php } ?>
			                </a>
			            </li>
						<?php } ?>
			        </ul>
			    </nav>
            </div>