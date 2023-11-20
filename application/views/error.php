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
    
    <?php if (isset($css)) foreach ($css as $obj) echo '<link href="' . $obj . '" rel="stylesheet" type="text/css">'; ?>

	<!-- Skin de grupo -->
	<?php if (file_exists(FCPATH . 'multimedia/' . $this->usuario->grupo . '/style.css')) echo '<link href="' . base_url('multimedia/' . $this->usuario->grupo . '/style.css') . '" rel="stylesheet" type="text/css">'; ?>
</head>

<body class="gray-bg">
    <div class="middle-box text-center animated fadeInDown">
        <h1><?php echo $codigo; ?></h1>
        <h3 class="font-bold"><?php echo $error; ?></h3>

        <div class="error-desc">
            <?php echo $mensaje; ?>
            <br>
            <a class="btn btn-primary m-t" type="submit" href="javascript:window.history.go(-1);">Volver</a>
        </div>
    </div>
</body>
</html>