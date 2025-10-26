<style>
.html5buttons { position: absolute;top: 9px;right: 25px;z-index: 10000; }
.html5buttons a { background: #5402b2; border-color: #5402b2; color:#fff; }
.html5buttons a:hover { background:rgba(84, 2, 178, 0.8) !important; border-color:rgba(84, 2, 178, 0.8) !important;color:#fff; }
pre, .alert-primary  { border:1px solid #5402b2; background:#ebdff9; font-size:10px;}
.alert-primary  { font-size:13px;}
pre code { white-space: pre-line; }
</style>
<link href="<?php echo base_url('assets/css/tienda.css'); ?>" rel="stylesheet" type="text/css">
        <div class="row wrapper border-bottom white-bg page-heading">
        	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
                <h2>Tienda</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('tienda/dashboard'); ?>">Home</a>
                    </li>
                    <li>
                         <a href="<?php echo base_url('tienda/productos'); ?>">Productos </a>
                    </li>
                    <li>
                        <strong>Importar productos</strong>
                    </li>
                </ol>
            </div>
        </div>
            
        <div class="wrapper wrapper-content animated fadeInRight">
            <!-- Titulo Mensajes -->
            <div class="row">
                <?php if (validation_errors()) : ?>
				<div class="col-md-12">
					<div class="alert alert-danger" role="alert"><?php echo validation_errors(); ?></div>
				</div>
				<?php endif; ?>
				<?php if (isset($error)) : ?>
				<div class="col-md-12">
					<div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
				</div>
				<?php endif; ?>
            </div>

	        <div class="wrapper wrapper-content">
	            <div class="row">
	                <div class="col-lg-12">
		                <div class="ibox float-e-margins">
	                    	<div class="ibox-title">
	                    		<h2>Importar productos desde Bruler</h2>
	                    	</div>
		                    
		                    <div class="ibox-content pull-left full-width">
	                            <div class="form-group pull-left full-width">
		                            <div class="col-sm-7 m-l-md m-t-md">
	                            		<h3>¿Cómo proceder?</h3>
	                            		<p class="alert alert-primary pull-left">Al presionar se traerán los productos desde Bruler, con todas las propiedades de los mismos.</p>
		                            </div>
	                            </div>
	                            <div class="form-group pull-left full-width">
		                            <div class="col-sm-3 m-l-md m-t-md">
		                            	<a class="btn btn-primary" style="display:block;" href="<?php echo base_url('tienda/productos/bruler_importar'); ?>">Importar</a>
		                            </div>
	                            </div>
		                    </div>
	                	</div>
	            	</div>
	            </div>
	        </div>
            </div>
        </div>