       <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-xs-8 col-sm-8 col-lg-8">
                <h2>Sitio web Páginas</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('cms-v2/paginas/');?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('cms-v2/paginas/');?>">Páginas</a>
                    </li>
                    <li class="active">
                        <strong><?php echo (empty($detalle['id'])) ? 'Crear nueva' : 'Modificar'; ?></strong>
                    </li>
                </ol>
            </div>


            <form action="<?php echo base_url('cms-v2/paginas/configuracion/'.$detalle['id']);?>/" class="form-horizontal" method="post" accept-charset="utf-8">
            <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
                <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
                <button class="btn btn-primary" type="submit">Guardar cambios</button>
            </div>
        </div>
            
        <div class="wrapper wrapper-content animated fadeInRight">
            <!-- Titulo Mensajes -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox-title ibox-title-custom"><h5>Configuraci&oacute;n de p&aacute;gina <?php echo $detalle['seccion']; ?></h5></div>
                </div>
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
        </div>

       	<!-- Comienzo Tabs -->
        <div class="wrapper wrapper-content" style="padding-top:0 !important;">
            <div class="row">
                <div class="col-lg-12">
                    <div class="tabs-container">
                        <ul class="nav nav-tabs">
                        	<?php foreach($idiomas as $idioma) { ?>
                            <li class="<?php if($idioma['orden'] == 1) { echo 'active';};?>"><a data-toggle="tab" href="#tab-<?php echo $idioma['orden'];?>"> <?php echo $idioma['idioma'];?></a></li>
                        	<?php } ?>
                        </ul>

                        <div class="tab-content">
	                        <!-- Items Idiomas -->
                        	<?php foreach($idiomas as $idioma) { ?>
	                        <div id="tab-<?php echo $idioma['orden'];?>" class="tab-pane<?php if($idioma['orden'] == 1) { echo ' active';};?>">
                        	<?php 
								if(!empty($detalle['id']))
								{
									$CI =& get_instance();
									$CI->load->model("Paginas_model");
									$item = $this->Paginas_model->getPaginaDetalleIdioma($detalle['id'], $idioma['extension']);
								}
							?>
	                            <div class="panel-body">
								 <div class="row">
		
					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">SEO</h2>
					                 	<div class="form-group">
											<label class="col-sm-2 control-label">T&iacute;tulo (de la p&aacute;gina)</label>
											<div class="col-sm-4">
		                                        <div class="input-group">
			                                        <input type="text" name="seo_titulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['seo_titulo'])) ? $item['seo_titulo']: null; ?>""><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de la página." title=""> <i class="fa fa-question"></i></button>
			                                    </div>
			                                </div>
					                 	</div>
					                </div>
					                
					                <div class="col-lg-12 p-xxs">
					                 	<div class="form-group">
						                    <div class="col-sm-6">
						                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>Descripci&oacute;n</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Descripción de la página." title=""> <i class="fa fa-question"></i></button></div>
							                    <div class="ibox-content no-padding">
								                    <textarea class="form-control" name="seo_descripcion_<?php echo $idioma['extension'];?>" rows="5"><?php echo (isset($item['seo_descripcion'])) ? $item['seo_descripcion']: null?></textarea>
							                    </div>
						                    </div>
						                    <div class="col-sm-6">
						                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>Keywords</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Si bien están prácticamente en desuso, son palabras o frases que se asocian al contenido de la página." title=""> <i class="fa fa-question"></i></button></div>
							                    <div class="ibox-content no-padding">
								                    <textarea class="form-control" name="seo_keywords_<?php echo $idioma['extension'];?>" rows="5"><?php echo (isset($item['seo_keywords'])) ? $item['seo_keywords']: null?></textarea>
							                    </div>
						                    </div>
					                 	</div>
					                </div>

					            </div>
				            </div>
		                </div>
                       	<?php } ?>
		                <!-- Fin Item Idioma 1 -->
		            <?php echo form_close();?>
                 </div>
             </div>                 
         </div>
     </div>
     
<script>
$('[data-toggle="tooltip"]').tooltip(); 
</script>     