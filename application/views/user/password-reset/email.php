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
                    <p><?php echo $this->lang->line('cms_login-recupero_tu_contrasena_envio_de_email'); ?>
                    <br>
                    <small><?php echo $this->lang->line('cms_login-recupero_tu_contrasena_verificar_spam'); ?></small></p>
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