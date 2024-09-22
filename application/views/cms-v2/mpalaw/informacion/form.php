<style>
.note-editor.note-frame { border:0;}
        .note-editable .row {
            margin: 0px;
        }
        .note-editable .row div {
            border: 1px dotted;
        }
.tooltip-inner {
      max-width: 250px;
      width: 250px;
}
</style>

         <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-8 col-sm-8 col-xs-8">
                <h2>Sitio web</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('cms-v2/paginas');?>">Páginas</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('cms-v2/paginas/modificar/1836');?>">News</a>
                    </li>
                    <li class="active">
                        <strong>Modificar Noticia</strong>
                    </li>
                </ol>
            </div>

            <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
			<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
			<input type="hidden" name="sin_texto" value="1">
		    <input type="hidden" name="destacado_slide" value="0">
			<input type="hidden" name="id_con_secciones" value="996">
			<input type="hidden" name="id_tipo_imagen2" id="id_tipo_imagen2" value="19">
			<input type="hidden" name="medidas_miniatura_imagen2" value="380x290">

            <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
                <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
                <button class="btn btn-primary" type="submit">Guardar cambios</button>
            </div>
        </div>
        
        <div class="wrapper wrapper-content animated fadeInRight">
			<div class="row">
                <?php if (validation_errors()) : ?>
				<div class="col-md-12 m-t-sm">
					<div class="alert alert-danger" role="alert"><?php echo validation_errors(); ?></div>
				</div>
				<?php endif; ?>
				<?php if (isset($error)) : ?>
				<div class="col-md-12 m-t-sm">
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
                            <li class="active"><a data-toggle="tab" href="#tab-0"> Datos Generales</a></li>
                        	<?php foreach($idiomas as $idioma) { ?>
                            <li class=""><a data-toggle="tab" href="#tab-<?php echo $idioma['orden'];?>"> <?php echo $idioma['idioma'];?></a></li>
                        	<?php } ?>
                        </ul>

                        <div class="tab-content">
	                        
	                        <!-- Item Generales -->
	                        <div id="tab-0" class="tab-pane active">
	                            <div class="panel-body">
								 <div class="row">
					                
					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
				                            <label class="col-sm-1 control-label text-right">Estado</label>
				                            <div class="col-sm-3">
					                            <div class="radio radio-inline"><input type="radio" name="estado" value="3" <?php if (isset($detalle['estado']) && $detalle['estado'] == '3') echo 'checked="checked"'; ?>> <label> Activo </label></div>
					                            <div class="radio radio-inline"><input type="radio" name="estado" value="1" <?php if (isset($detalle['estado']) && $detalle['estado'] == '1') echo 'checked="checked"'; ?>><label> Inactivo </label></div>
					                            <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Si es activo se muestra en la web, si es inactivo no se muestra en la web." title=""> <i class="fa fa-question"></i></button>
					                         </div>
						                    <label class="text-right col-sm-1 control-label">Destacada</label>
				                            <div class="col-sm-3">
					                            <div class="radio radio-inline"><input type="radio" name="destacado" value="1" <?php if (isset($detalle['destacado']) && $detalle['destacado'] == '1') echo 'checked="checked"'; ?>> <label> Sí </label></div>
					                            <div class="radio radio-inline"><input type="radio" name="destacado" value="0" <?php if (isset($detalle['destacado']) && $detalle['destacado'] == '0') echo 'checked="checked"'; ?>><label> No </label></div>
					                            <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Si es destacado se muestra en el home como contenido destacado." title=""> <i class="fa fa-question"></i></button>
					                         </div>

						                    <label class="text-right col-sm-1 control-label">Orden</label>
						                    <div class="col-sm-3">
		                                        <div class="input-group">
			                                        <input type="text" name="orden" class="form-control" value="<?php echo (isset($detalle['orden'])) ? $detalle['orden']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en que se mostrará el ítem." title=""> <i class="fa fa-question"></i></button>
			                                    </div>
		                                    </div>
			                            </div>
								   </div>

	                            </div>
	                        </div>
	                        </div>
	                        <!-- Fin Item Generales -->
	
	                        <!-- Items Idiomas -->
                        	<?php foreach($idiomas as $idioma) { ?>
	                        <div id="tab-<?php echo $idioma['orden'];?>" class="tab-pane">

                        	<?php 
								if(!empty($detalle['id']))
								{
									$CI =& get_instance();
									$CI->load->model("Informacion_model");
									$item = $this->Informacion_model->getContenidoDetalleIdioma($detalle['id'], $idioma['extension']);
									if($item['id_item'])
									{
										$imagen = $this->Informacion_model->getMedia($detalle['id'], $idioma['extension'], 18);
										if(!$imagen)
										{
											$imagen= $this->Informacion_model->getMedia($detalle['id'], $idioma['extension'], 14);
										}
										$imagen2 = $this->Informacion_model->getMedia($detalle['id'], $idioma['extension'], 14);
										$archivo = $this->Informacion_model->getArchivo($detalle['id'], $idioma['extension']);
										$slide = $this->Informacion_model->getContenidoRelacionado($item['id_item'], $idioma['extension']);
										if($slide)
										{
											echo '<input type="hidden" name="id_contenido_relacionado" value="'.$slide['id_contenido_relacionado'].'">';
										}
									}
								}
							?>
	                            <div class="panel-body">
								 <div class="row">

					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <label class="text-right col-sm-2 control-label">Título</label>
											<div class="col-sm-4">
		                                        <div class="input-group">
		                                        	<input type="text" name="titulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de la noticia." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
											</div>

						                    <label class="text-right col-sm-2 control-label">Fecha</label>
											<div class="col-sm-4">
		                                        <div class="input-group">
		                                        	<input type="text" name="subtitulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['subtitulo'])) ? $item['subtitulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Fecha de la noticia." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
											</div>
										</div>

										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <label class="text-right col-sm-2 control-label">Nombre (url)</label>
						                    <div class="col-sm-4">
		                                        <div class="input-group">
		                                        	<input type="text" name="url_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['url'])) ? $item['url']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Url de la noticia." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
		                                     </div>
				                 		</div>
					                </div>
	
					                <div class="col-lg-6 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
											<div class="ibox-title bg-muted"><h5>Intro</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Texto de introducción, se mostrará en el listado de noticias y en el detalle sobre fondo gris." title=""> <i class="fa fa-question"></i></button></div>
											<textarea class="form-control summernote" name="contenido2_<?php echo $idioma['extension'];?>" rows="10"><?php echo(isset($item['contenido2'])) ? $item['contenido2']: null?></textarea>
										</div>
					                </div>

					                <div class="col-lg-6 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
											<div class="ibox-title bg-muted"><h5>Texto</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Texto de la noticia." title=""> <i class="fa fa-question"></i></button></div>
											<textarea class="form-control summernote" name="contenido1_<?php echo $idioma['extension'];?>" rows="10"><?php echo(isset($item['contenido1'])) ? $item['contenido1']: null?></textarea>
										</div>
					                </div>
					                <div class="col-lg-6 p-xxs">
										<?php if((isset($item['titulo'])) && $imagen2['archivo']) { ?>
											<div class="form-group m-b-md pull-left full-width m-t-md">
			                            		<label class="text-right col-sm-4 control-label">Imagen Actual</label>
			                            		<div class="col-sm-8">
			                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$imagen2['archivo']);?>" style="height:auto;width:180px;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
											</div>
										<?php } ?>
											<div class="form-group m-b-md pull-left full-width m-t-md">
		                            		<label class="text-right col-sm-4 control-label">Imagen</label>
							                <div class="col-sm-8">
		                                        <div class="input-group">
			                                       <input type="file" name="imagen2_<?php echo $idioma['extension'];?>" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen en jpg, gif o png de 380x290 píxeles o proporcionales. Recuerde que el peso debe ser el mínimo posible para no afectar la descarga del site." title=""> <i class="fa fa-question"></i></button>
			                                    </div>
							                </div>
										</div>
									</div>

					                <div class="col-lg-6 p-xxs">
										<?php if((isset($item['titulo'])) && $archivo['archivo']) { ?>
											<div class="form-group m-b-md pull-left full-width m-t-md">
			                            		<label class="text-right col-sm-4 control-label">Archivo Actual</label>
			                            		<div class="col-sm-8">
			                            			<img src="<?php echo base_url('/multimedia/' . $this->usuario->grupo . '/' . $this->usuario->id_empresa . '/'.$archivo['archivo']);?>" style="height:auto;width:180px;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
											</div>
										<?php } ?>
											
											<div class="form-group m-b-md pull-left full-width m-t-md">
		                            		<label class="text-right col-sm-4 control-label">Archivo</label>
							                <div class="col-sm-8">
		                                        <div class="input-group">
		                                        	<input type="file" name="archivo1_<?php echo $idioma['extension'];?>" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Archivo PDF a ver/descargar." title=""> <i class="fa fa-question"></i></button></span>
			                                    </div>
							                </div>
				                 		</div>
					                </div>


			                    </div>
			                </div>
						</div>
                       	<?php } ?>
					  <!-- Fin Items Idiomas -->
                     <?php echo form_close();?>
                     
                    </div>
                 </div>
             </div>                 
         </div>
     </div>     

<!-- SUMMERNOTE -->
<script src="<?php echo base_url('assets/js/plugins/summernote/summernote.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/summernote-grid.js'); ?>"></script>

<script>
$('.summernote').summernote({
        placeholder: 'Ingrese contenido...',
        tabsize: 2,
        height: 200,
        toolbar: [
          ['insert', ['file'], ['image']],
          ['style', ['style']],
          ['font', ['bold', 'italic', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link']],
          ['view', ['codeview']],
          ['insert', ['grid']],
          ['misc', ['codeview']]
        ]

});
$('[data-toggle="tooltip"]').tooltip(); 
</script>