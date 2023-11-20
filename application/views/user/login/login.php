<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>revision alpha CMS+</title>
    <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('assets/font-awesome/css/font-awesome.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('assets/css/animate.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('assets/css/style.css'); ?>" rel="stylesheet" type="text/css">
</head>

<body class="gray-bg">
    <div class="loginColumns animated fadeInDown">
        <div class="row">
            <div class="col-md-6">
                <h2 class="font-bold">
	                <?php if ($this->session->userdata('lang') == 'english') { ;?>
	                	Welcome to CMS+
					<?php } else { ?>
						Bienvenido a CMS+
					<?php } ?>
	            </h2>
	            
	            <?php if ($this->session->userdata('lang') == 'english') { ;?>
	                <p>Designed so you can manage the content of your website quickly and easily.</p>
					<p>Create Email Marketing campaigns in simple steps.</p>
					<p>Establish a fluid and effective communication with users who visit your website.</p>
					<p><small>Content Management System based on revision alpha CMS 5</small></p>
				<?php } else { ?>
					<p>Diseñado para que puedas gestionar el contenido de tu sitio web en forma rápida y sencilla.</p>
					<p>Crear campañas de Email Marketing en simples pasos.</p>
					<p>Establecer una comunicación fluida y eficaz con los usuarios que visitan tu sitio web.</p>
					<p><small>Content Management System basado en revision alpha CMS 5</small></p>
				<?php } ?>
                
            </div>

            <div class="col-md-6">
                <div class="ibox-content">
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
                            <input type="text" class="form-control" style="border-color: #CCCCCC !important;" id="username" name="username" placeholder="<?php echo $this->lang->line('cms_login-username'); ?>" value="<?php echo (isset($detalle['username'])) ? $detalle['username'] : null; ?>">
                        </div>

                        <div class="form-group">
                            <input type="password" class="form-control" style="border-color: #CCCCCC !important;" id="password" name="password" placeholder="<?php echo $this->lang->line('cms_login-password'); ?>">
                        </div>
                        <button type="submit" value="Login" class="btn btn-default block full-width m-b" value="Login"><?php echo $this->lang->line('cms_login-entrar'); ?></button>
<!--                         <input type="submit" class="btn btn-default" value="Login"> -->
                        
                         <a href="<?php echo base_url('user/password-reset/'); ?>"><small><?php echo $this->lang->line('cms_login-olvidaste_tu_contrasena'); ?></small></a>

<!--                         <p class="text-muted text-center"><small>¿Aún no estás registrado?</small></p><a class="btn btn-sm btn-white btn-block" href="#">Crear cuenta</a> -->
                    </form>

<!--                     <p class="m-t"><small>Al crear una cuenta de cliente de revision alpha, estarás aceptando los <a href="http://www.revisionalpha.com/terminos-y-condiciones/" target="_blank">Términos y Condiciones de Uso del Servicio.</a></small></p> -->
                </div>
            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col-md-6">
                <small><strong>Copyright</strong> revision alpha &copy;2002-2017</small>
            </div>

            <div class="col-md-6 text-right">
                <strong>CMS+</strong> ☰
            </div>
        </div>
    </div>
</body>
</html>
