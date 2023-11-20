<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>revision alpha CMS+ | <?php echo $this->lang->line('cms_login-recupero_tu_contrasena'); ?></title>
    <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('assets/font-awesome/css/font-awesome.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('assets/css/animate.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('assets/css/style.css'); ?>" rel="stylesheet" type="text/css">
</head>

<body class="gray-bg">
    <div class="passwordBox animated fadeInDown">
        <div class="row">
            <div class="col-md-12">
                <div class="ibox-content">
                    <h2 class="font-bold"><?php echo $this->lang->line('cms_login-recupero_tu_contrasena'); ?></h2>
                    <p><?php echo $this->lang->line('cms_login-recupero_tu_contrasena_escribe_y_confirma'); ?></p>
                    
                    <div class="row">
                        <div class="col-lg-12">
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
                                    <input type="password" class="form-control" style="border-color: #CCCCCC !important;" name="password" placeholder="<?php echo $this->lang->line('cms_login-password'); ?>">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" style="border-color: #CCCCCC !important;" name="passconf" placeholder="<?php echo $this->lang->line('cms_login-password_confirm'); ?>">
                                </div>
                                <button type="submit" class="btn btn-default block full-width m-b"><?php echo $this->lang->line('cms_login-enviar'); ?></button>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-6">
                <small><strong>Copyright</strong> revision alpha</small>
            </div>
            <div class="col-md-6 text-right">
               <small>&copy;2002-2017</small>
            </div>
        </div>
    </div>
</body>
</html>