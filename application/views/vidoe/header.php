<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="Askbootstrap">
		<meta name="author" content="Askbootstrap">
		<title>revision alpha CMS+</title>
		<!-- Favicon Icon -->
		<link rel="icon" type="image/png" href="<?php echo base_url((file_exists(FCPATH . 'multimedia/' . $this->usuario->grupo . '/favicon.png')) ? '/multimedia/' . $this->usuario->grupo . '/favicon.png' : 'assets/vidoe/img/favicon.png'); ?>">
		<!-- Bootstrap core CSS-->
		<link href="<?php echo base_url('assets/vidoe/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
		<!-- Custom fonts for this template-->
		<link href="<?php echo base_url('assets/vidoe/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet" type="text/css">
		<!-- Custom styles for this template-->
		<link href="<?php echo base_url('assets/vidoe/css/osahan.css'); ?>" rel="stylesheet">
		<!-- Owl Carousel -->
		<link rel="stylesheet" href="<?php echo base_url('assets/vidoe/owl-carousel/owl.carousel.css'); ?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/vidoe/owl-carousel/owl.theme.css'); ?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/vidoe/css/custom.css'); ?>">
		
		<?php if (!empty($this->session->userdata('color_principal'))) { ?><style>.sidebar { background: <?php echo $this->session->userdata('color_principal'); ?> }</style><?php } ?>
	</head>
<body id="page-top">
	<nav class="navbar navbar-expand navbar-light bg-white static-top osahan-nav sticky-top">&nbsp;&nbsp;
		<button class="btn btn-link btn-sm text-secondary order-1 order-sm-0" id="sidebarToggle" onclick="setMiniNavBar();">
			<i class="fas fa-bars"></i>
		</button> &nbsp;&nbsp;
		<a class="navbar-brand mr-1" href="<?php echo base_url(); ?>">
			<img class="img-fluid" src="<?php echo (isset($this->session->userdata('config')['logo'])) ? $this->session->userdata('config')['logo'] : base_url((file_exists(FCPATH . 'multimedia/' . $this->usuario->grupo . '/logo-vidoe.png')) ? 'multimedia/' . $this->usuario->grupo . '/logo-vidoe.png' : 'assets/vidoe/img/logo.png'); ?>"></a>
		<!-- Navbar Search -->
		<form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-5 my-2 my-md-0 osahan-navbar-search" action="<?php echo base_url('multimedia/'); ?>">
			<div class="input-group">
				<input type="text" name="search" class="form-control" placeholder="<?php echo $this->lang->line('cms_users-buscar'); ?>..." value="<?php echo $this->input->get('search'); ?>">
				<div class="input-group-append">
					<button class="btn btn-light" type="button">
						<i class="fas fa-search"></i>
						</button>
				</div>
			</div>
		</form>

		<ul class="navbar-nav ml-auto ml-md-0 osahan-right-navbar">
			<li class="nav-item mx-1">
				<a class="nav-link" href="<?php echo base_url('multimedia/upload-sajax'); ?>">
					<i class="fas fa-plus-circle fa-fw"></i> <?php echo $this->lang->line('cms_media-subir_archivo'); ?>
				</a>
			</li>
			<li class="nav-item dropdown no-arrow osahan-right-navbar-user">
				<a class="nav-link dropdown-toggle user-dropdown-link" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<img alt="Avatar" src="<?php echo base_url((file_exists(FCPATH . 'multimedia/' . $this->usuario->grupo . '/avatar.png')) ? 'multimedia/' . $this->usuario->grupo . '/avatar.png' : 'assets/vidoe/img/user.png'); ?>"> <?php echo $this->usuario->contacto; ?>
				</a>
				<div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
<!-- 					<a class="dropdown-item" href="<?php echo base_url('micuenta/perfil'); ?>"><i class="fas fa-fw fa-user-circle"></i> &nbsp; <?php echo $this->lang->line('cms_users-perfil'); ?></a> -->
					<a class="dropdown-item" href="<?php echo base_url('micuenta/perfil/idioma'); ?>"><i class="fas fa-fw fa-globe"></i> &nbsp; <?php echo $this->lang->line('cms_users-idioma'); ?></a>
					<a class="dropdown-item" href="<?php echo base_url('micuenta/perfil/password'); ?>"><i class="fas fa-fw fa-lock"></i> &nbsp; <?php echo $this->lang->line('cms_users-clave'); ?></a>
					<?php if ($this->usuario->perfil == 'admin') { ?>
						<a class="dropdown-item" href="<?php echo base_url('multimedia/empresa-banner-upload'); ?>"><i class="fas fa-fw fa-cog"></i> &nbsp; Banner</a>
					<?php } ?>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal"><i class="fas fa-fw fa-sign-out-alt"></i> &nbsp; <?php echo $this->lang->line('cms_users-logout'); ?></a>
				</div>
			</li>
		</ul>
	</nav>

	<div id="wrapper">
		<!-- Sidebar -->
		<ul class="sidebar navbar-nav">
			<?php if ($this->usuario->perfil == 'admin') { ?>
			<li class="nav-item" >
				<a class="nav-link" href="<?php echo base_url('micuenta'); ?>">
					<i class="fas fa fa-th-large"></i>
					<span><?php echo $this->lang->line('cms_dashboard'); ?></span>
				</a>
			</li>
			<?php } ?>
			
			<?php if ($this->usuario->perfil == 'admin' || $this->usuario->perfil == 'user') { ?>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo base_url('multimedia/proyectos'); ?>">
					<i class="fas fa-fw <?php echo (!empty($this->session->userdata('icono_proyectos'))) ? $this->session->userdata('icono_proyectos') : 'fa-rss'; ?>"></i>
					<span><?php echo (!empty($this->session->userdata('menu_canales'))) ? $this->session->userdata('menu_canales') : $this->lang->line('cms_media-canales'); ?></span>
				</a>
			</li>
			<?php } ?>
			
			<li class="nav-item">
				<a class="nav-link" href="<?php echo base_url('multimedia'); ?>">
					<i class="fas fa-fw <?php echo (!empty($this->session->userdata('icono_media'))) ? $this->session->userdata('icono_media') : 'fa-images'; ?>"></i>
					<span><?php echo (!empty($this->session->userdata('menu_media'))) ? $this->session->userdata('menu_media') : $this->lang->line('cms_media-multimedia'); ?></span>
				</a>
			</li>
			
			<?php if (isset($this->session->userdata('config')['cms_vidoe_show'])) { ?>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo base_url('cms'); ?>">
					<i class="fas fa-fw <?php echo (!empty($this->session->userdata('icono_cms'))) ? $this->session->userdata('icono_cms') : 'fa-globe'; ?>"></i>
					<span><?php echo (!empty($this->session->userdata('menu_cms'))) ? $this->session->userdata('menu_cms') : $this->lang->line('cms_cms'); ?></span>
				</a>
			</li>
			<?php } ?>
		</ul>