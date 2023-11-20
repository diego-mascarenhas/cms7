<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="Askbootstrap">
		<meta name="author" content="Askbootstrap">
		<title>revision alpha CMS+ | <?php echo $this->lang->line('cms_login-recupero_tu_contrasena'); ?></title>
		<!-- Favicon Icon -->
		<link rel="icon" type="image/png" href="<?php echo base_url('assets/vidoe/img/favicon.png'); ?>">
		<!-- Bootstrap core CSS-->
		<link href="<?php echo base_url('assets/vidoe/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
		<!-- Custom fonts for this template-->
		<link href="<?php echo base_url('assets/vidoe/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet" type="text/css">
		<!-- Custom styles for this template-->
		<link href="<?php echo base_url('assets/vidoe/css/osahan.css'); ?>" rel="stylesheet">
	</head>
	<body class="login-main-body">
	<section class="login-main-wrapper">
		<div class="container-fluid pl-0 pr-0">
			<div class="row no-gutters">
				<div class="col-md-12 bg-white full-height">
					<div class="login-main-left">
						<div class="text-center mb-5 login-main-left-header pt-4">
							<img src="<?php echo base_url('assets/vidoe/img/favicon-rocoto.png'); ?>" class="img-fluid">
			                <h5 class="mt-3 mb-3"><?php echo $this->lang->line('cms_login-recupero_tu_contrasena'); ?></h5>
							<p>
								<?php echo $this->lang->line('cms_login-recupero_tu_contrasena_envio_de_email'); ?>
								<br>
								<small><?php echo $this->lang->line('cms_login-recupero_tu_contrasena_verificar_spam'); ?></small>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	
	<!-- Bootstrap core JavaScript-->
	<script src="<?php echo base_url('assets/vidoe/jquery/jquery.min.js'); ?>"></script>
	<script src="<?php echo base_url('assets/vidoe/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
</body>
</html>