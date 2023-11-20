<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="Askbootstrap">
		<meta name="author" content="Askbootstrap">
		<title>revision alpha CMS+ | Login</title>
		<!-- Favicon Icon -->
		<link rel="icon" type="image/png" href="<?php echo base_url('assets/vidoe/img/favicon.png'); ?>">
		<!-- Bootstrap core CSS-->
		<link href="<?php echo base_url('assets/vidoe/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
		<!-- Custom fonts for this template-->
		<link href="<?php echo base_url('assets/vidoe/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet" type="text/css">
		<!-- Custom styles for this template-->
		<link href="<?php echo base_url('assets/vidoe/css/osahan.css'); ?>" rel="stylesheet">
		<!-- Owl Carousel -->
		<link rel="stylesheet" href="<?php echo base_url('assets/vidoe/owl-carousel/owl.carousel.css'); ?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/vidoe/owl-carousel/owl.theme.css'); ?>">
	</head>
	<body class="login-main-body">
	<section class="login-main-wrapper">
		<div class="container-fluid pl-0 pr-0">
			<div class="row no-gutters">
				<div class="col-md-5 p-5 bg-white full-height">
					<div class="login-main-left">
						<div class="text-center mb-5 login-main-left-header pt-4">
							<img src="<?php echo base_url('assets/vidoe/img/favicon-rocoto.png'); ?>" class="img-fluid">
							<?php if ($this->session->userdata('lang') == 'english') { ;?>
			                	<h5 class="mt-3 mb-3">Welcome to CMS+</h5>
								<p>Designed so you can manage the content<br> of your website quickly and easily..</p>
							<?php } else { ?>
								<h5 class="mt-3 mb-3">Bienvenido a CMS+</h5>
								<p>Diseñado para que puedas gestionar el contenido<br> de tu sitio web en forma rápida y sencilla.</p>
							<?php } ?>
						</div>
						<?php if (validation_errors()) : ?>
							<div class="col-md-12">
								<div class="alert alert-danger" role="alert">
									<?php echo validation_errors(); ?>
								</div>
							</div>
						<?php endif; ?>
						<?php if (isset($error)) : ?>
							<div class="col-md-12">
								<div class="alert alert-danger" role="alert">
									<?php echo $error; ?>
								</div>
							</div>
						<?php endif; ?>
						<?php echo form_open(); ?>
							<input type="hidden" name="redirect" value="<?php echo (isset($detalle['redirect'])) ? $detalle['redirect'] : null; ?>">
							<div class="form-group">
								<label><?php echo $this->lang->line('cms_login-username'); ?></label>
								<input type="text" class="form-control" id="username" name="username" value="<?php echo (isset($detalle['username'])) ? $detalle['username'] : null; ?>">
							</div>
							<div class="form-group">
								<label><?php echo $this->lang->line('cms_login-password'); ?></label>
								<input type="password" class="form-control" id="password" name="password">
							</div>
							<div class="mt-4">
								<div class="row">
									<div class="col-12">
										<button type="submit" class="btn btn-outline-primary btn-block btn-lg"><?php echo $this->lang->line('cms_login-entrar'); ?></button>
									</div>
								</div>
							</div>
						</form>
						<div class="text-center mt-5">
<!-- 							<p class="light-gray"><?php echo $this->lang->line('cms_login-no_tienes_cuenta'); ?> <a href="register.html"><?php echo $this->lang->line('cms_login-registrate'); ?></a></p> -->
							<a href="<?php echo base_url('user/password-reset/'); ?>"><small><?php echo $this->lang->line('cms_login-olvidaste_tu_contrasena'); ?></small></a>
						</div>
					</div>
				</div>
				<div class="col-md-7">
					<div class="login-main-right bg-white p-5 mt-5 mb-5">
						<div class="owl-carousel owl-carousel-login">
							<div class="item">
								<div class="carousel-login-card text-center">
									<img src="<?php echo base_url('assets/vidoe/img/login_01.png'); ?>" class="img-fluid" alt="LOGO">
									<h5 class="mt-5 mb-3">​Administración Online de Archvios</h5>
									<p class="mb-4">Administración completa y robusta de archivos multimedia diseñada para profesionales y empresas que crean y distribuyen contenido digital</p>
								</div>
							</div>
							<div class="item">
								<div class="carousel-login-card text-center">
									<img src="<?php echo base_url('assets/vidoe/img/login_02.png'); ?>" class="img-fluid" alt="LOGO">
									<h5 class="mt-5 mb-3">Trabajo Colaborativo</h5>
									<p class="mb-4">Carga instantánea de archivos para compartir, reproducir y descargar desde cualquier navegador mediante la gestión de diferentes perfiles de usuario</p>
								</div>
							</div>
							<div class="item">
								<div class="carousel-login-card text-center">
									<img src="<?php echo base_url('assets/vidoe/img/login_03.png'); ?>" class="img-fluid" alt="LOGO">
									<h5 class="mt-5 mb-3">Seguridad y Confiabilidad</h5>
									<p class="mb-4">La plataforma de video compartido garantiza la protección de los derechos de autor y el control del contenido aportando mayor seguridad y privacidad</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	
	<!-- Bootstrap core JavaScript-->
	<script src="<?php echo base_url('assets/vidoe/jquery/jquery.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/vidoe/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
	<!-- Core plugin JavaScript-->
	<script src="<?php echo base_url('assets/vidoe/jquery-easing/jquery.easing.min.js'); ?>"></script>
	<!-- Owl Carousel -->
	<script src="<?php echo base_url('assets/vidoe/owl-carousel/owl.carousel.js'); ?>"></script>
	<!-- Custom scripts for all pages-->
	<script src="<?php echo base_url('assets/vidoe/js/custom.js'); ?>"></script>
</body>
</html>