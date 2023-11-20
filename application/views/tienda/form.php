<style>
.skin-1 .ibox-content:last-child {border-style: solid solid solid solid;}
.ibox-title,.ibox-content {border-width: 1px;}
.note-editor.note-frame { border: none;}
.p_b_25 { padding-bottom:25px !important;}
</style>

	        <div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-lg-8">
	                <h2>Tienda</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="/cms">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('tienda/');?>">Tienda</a>
	                    </li>
	                    <li class="active">
	                        <strong><?php echo (empty($detalle['id'])) ? 'Crear nueva' : 'Modificar'; ?></strong>
	                    </li>
	                </ol>
	            </div>
	
	            <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
				<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
	            <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
	                <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
	                <button class="btn btn-primary" type="submit">Guardar cambios</button>
	            </div>
	        </div>
	                       
	        <div class="row wrapper animated fadeInRight">
	            <!-- Titulo Mensajes -->
	                <?php if (validation_errors()) : ?>
					<div class="col-md-12" style="margin-top:25px;">
						<div class="alert alert-danger" role="alert"><?php echo validation_errors(); ?></div>
					</div>
					<?php endif; ?>
					<?php if (isset($error)) : ?>
					<div class="col-md-12" style="margin-top:25px;">
						<div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
					</div>
					<?php endif; ?>
	        </div>
	        
	       	<!-- Comienzo Tabs -->        
	        <div class="wrapper wrapper-content animated fadeInRight p_b_25">
	            <div class="row">
					<div class="col-lg-12">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title"><h5>Información de la tienda</h5>
		                        <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
		                    </div>
		                    
		                    <?php echo form_open(null, array('class'=>'form-horizontal')); ?>
                            	<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
		                    
		                    <div class="ibox-content" style="float:left;width:100%;">
			                 	<div class="form-group">
				                    <label class="text-right col-sm-1 control-label">T&iacute;tulo</label>
				                    <div class="col-sm-3 col-md-3"><input type="text" name="titulo" class="form-control" value="<?php echo (isset($detalle['titulo'])) ? $detalle['titulo']: null; ?>"></div>
				                    <label class="text-right col-sm-1 control-label">Url</label>
				                    <div class="col-sm-3 col-md-3"><input type="text" name="url" class="form-control" value="<?php echo (isset($detalle['url'])) ? $detalle['url']: null; ?>"></div>
				                    <label class="text-right col-sm-1 control-label">Estado</label>
				                    <div class="col-sm-3">
					                    <?php echo (isset($detalle['id'])) ? form_dropdown('estado', $estados, $detalle['estado'], array('class'=>'form-control m-b')) : form_dropdown('estado', $estados, null, array('class'=>'form-control m-b')); ?></div>
					            </div>
					            <br><br><br>
		                    </div>
	                    </div>
	                </div>
	        
	          <!-- Contenido -->
				<div class="col-lg-12 p_b_25" style="margin-top:25px;">
	                <div class="ibox float-e-margins">
	                    <div class="ibox-title"><h5>Contenido</h5>
	                        <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
	                    </div>
	                    <div class="ibox-content" style="float: left; width:100%;">
							<div class="col-lg-6">
			                    <div class="ibox-title" style="background:#f7f7f7;"><h5>Contenido</h5></div>
		                    	<div class="ibox-content no-padding">
			                    	<textarea cols="80" id="codigo" name="codigo" rows="10" class="ckeditor"><?php echo (isset($detalle['codigo'])) ? htmlspecialchars($detalle['codigo']): null; ?></textarea>
			                    </div>
							</div>
							<div class="col-lg-6">
			                    <div class="ibox-title" style="background:#f7f7f7;"><h5>Contenido Thank You Page</h5></div>
		                    	<div class="ibox-content no-padding">
			                    	<textarea cols="80" id="codigo_gracias" name="codigo_gracias" rows="10" class="ckeditor"><?php echo (isset($detalle['codigo_gracias'])) ? htmlspecialchars($detalle['codigo_gracias']): null; ?></textarea>
			                    </div>
							</div>
	                	</div>
					</div>
				</div>
	          <!-- Fin Contenido -->
			<?php echo form_close(); ?>
			<!-- Fin Contenido -->
	       
			<br><br></div></div>
						 	

			<!-- CKEDITOR -->
			<script src="https://cdn.ckeditor.com/4.13.1/standard-all/ckeditor.js"></script>
			
			<script>
			    CKEDITOR.replace('codigo', {
			      fullPage: true,
			      extraPlugins: 'docprops',
			      // Disable content filtering because if you use full page mode, you probably
			      // want to  freely enter any HTML content in source mode without any limitations.
			      allowedContent: true,
			      height: 340
			    });
			    
			    CKEDITOR.replace('codigo_gracias', {
			      fullPage: true,
			      extraPlugins: 'docprops',
			      allowedContent: true,
			      height: 340
			    });
			  </script>
