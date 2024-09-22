<style>
.html5buttons { position: absolute;top: 9px;right: 25px;z-index: 10000; }
.html5buttons a { background: #5402b2; border-color: #5402b2; color:#fff; }
.html5buttons a:hover { background:rgba(84, 2, 178, 0.8) !important; border-color:rgba(84, 2, 178, 0.8) !important;color:#fff; }
.tooltip-inner {max-width: 250px;width: 250px;}
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
	                    		<h2>Cargar archivo csv</h2>
	                    	</div>
		                    <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
		                    
		                    <div class="ibox-content pull-left full-width">
	                            <div class="form-group pull-left full-width">
		                            <div class="col-sm-4 m-l-md m-t-md">
		                            	<h3>Archivo</h3>
		                            	<input type="file" name="archivo" class="form-control">
		                            </div>
		                            <div class="col-sm-7 m-l-md m-t-md">
		                            	<h3>¿Cómo cargar el archivo?</h3>
		                            	<p class="alert alert-primary pull-left">Debe subir un archivo CSV con los siguientes datos: ID de categoría, categoría, producto, descripción, link, código, precio, precio oferta, precio local, precio local oferta, galería (SI/NO), destacado (SI/NO), consultar (SI/NO) y publicado (SI/NO) en ese orden.<br>Recuerde dejar la fila con el nombre de los campos antes de importar el archivo.</p>
		                            
		                            </div>
	                            </div>

	                            <div class="hr-line-dashed pull-left full-width"></div>
		                            
	                            <div class="form-group">
	                                <div class="col-sm-4 col-sm-offset-2">
					                	<a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
	                                    <button class="btn btn-primary" type="submit">Subir</button>
	                                </div>
	                            </div>

		                    </div>

	                	</div>
	            	</div>
	            </div>
	        </div>
            </div>
        </div>
<script>
	$('[data-toggle="tooltip"]').tooltip(); 
</script>