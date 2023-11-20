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
}</style>                        
         <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-8 col-sm-8 col-xs-8">
                <h2>Sitio web</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('cms-v2/eventos');?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('cms-v2/eventos');?>">Eventos</a>
                    </li>
                    <li class="active">
                        <strong><?php echo (empty($detalle['id'])) ? 'Crear nuevo' : 'Modificar'; ?></strong>
                    </li>
                </ol>
            </div>

            <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
			<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
			<?php if (!empty($detalle['id'])) { ?>
			<input type="hidden" name="fecha_alta" value="<?php echo $detalle['fecha_alta'];?>">
			<?php } ?>
			<input type="hidden" name="id_tipo" value="11">
			<input type="hidden" name="id_con_secciones" value="107">
			<input type="hidden" name="slide_id_contenido" value="76">
			<input type="hidden" name="slide_tipo" value="8">
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
				                            <label class="col-sm-2 control-label text-right">Estado</label>
				                            <div class="col-sm-4">
					                            <div class="radio radio-inline"><input type="radio" name="estado" value="3" <?php if (isset($detalle['estado']) && $detalle['estado'] == '3') echo 'checked="checked"'; ?>> <label> Activo </label></div>
					                            <div class="radio radio-inline"><input type="radio" name="estado" value="1" <?php if (isset($detalle['estado']) && $detalle['estado'] == '1') echo 'checked="checked"'; ?>><label> Inactivo </label></div>
					                            <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="auto right" data-original-title="Determina si el contenido se mostrará en el sitio." title=""> <i class="fa fa-question"></i></button>
					                        </div>

						                    <label class="text-right col-sm-2 control-label">En Modal</label>
				                            <div class="col-sm-4">
					                            <div class="radio radio-inline"><input type="radio" name="destacado_modal" value="1" <?php if (isset($detalle['destacado_modal']) && $detalle['destacado_modal'] == '1') echo 'checked="checked"'; ?>> <label> Sí </label></div>
					                            <div class="radio radio-inline"><input type="radio" name="destacado_modal" value="0" <?php if (isset($detalle['destacado_modal']) && $detalle['destacado_modal'] == '0') echo 'checked="checked"'; ?>><label> No </label></div>
					                            <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="auto right" data-original-title="Determina si el contenido se mostrará en el botón lateral del sitio, Academia Virtual OBA." title=""> <i class="fa fa-question"></i></button>
					                         </div>
							            </div>
					                </div>
					                <hr class="hr-line-dashed pull-left full-width">
					                
					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <label class="text-right col-sm-2 control-label">Destacada</label>
				                            <div class="col-sm-4">
					                            <div class="radio radio-inline"><input type="radio" name="destacado" value="1" <?php if (isset($detalle['destacado']) && $detalle['destacado'] == '1') echo 'checked="checked"'; ?>> <label> Sí </label></div>
					                            <div class="radio radio-inline"><input type="radio" name="destacado" value="0" <?php if (isset($detalle['destacado']) && $detalle['destacado'] == '0') echo 'checked="checked"'; ?>><label> No </label></div>
					                            <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="auto right" data-original-title="Determina si el contenido estará visible en el carousel de eventos del Home." title=""> <i class="fa fa-question"></i></button>
					                         </div>

						                    <label class="text-right col-sm-2 control-label">Publicar en Slide</label>
				                            <div class="col-sm-4">
					                            <div class="radio radio-inline"><input type="radio" name="destacado_slide" value="1" <?php if (isset($detalle['destacado_slide']) && $detalle['destacado_slide'] == '1') echo 'checked="checked"'; ?>> <label> Sí </label></div>
					                            <div class="radio radio-inline"><input type="radio" name="destacado_slide" value="0" <?php if (isset($detalle['destacado_slide']) && $detalle['destacado_slide'] == '0') echo 'checked="checked"'; ?>><label> No </label></div>
					                            <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="auto right" data-original-title="Determina si el contenido estará visible en el slideshow de imágenes del Home." title=""> <i class="fa fa-question"></i></button>
					                         </div>
			                            </div>
								    </div>
					                <hr class="hr-line-dashed pull-left full-width">
					                
					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
											<label class="text-right col-sm-2 control-label">Estado/Tipo</label>
						                    <div class="col-sm-3">
		                                        <div class="input-group">
								                    <select name="filtro1" class="form-control m-b">
														<option value="1"<?php if ((isset($detalle['filtro1']) && $detalle['filtro1'] == 1)) { echo ' selected';} ?>>Suscribirse</option>
														<option value="2"<?php if ((isset($detalle['filtro1']) && $detalle['filtro1'] == 2)){ echo ' selected';} ?>>Lista de Espera</option>
														<option value="3"<?php if ((isset($detalle['filtro1']) && $detalle['filtro1'] == 3)){ echo ' selected';} ?>>Suspendido</option>
														<option value="4"<?php if ((isset($detalle['filtro1']) && $detalle['filtro1'] == 4)){ echo ' selected';} ?>>Finalizado</option>
														<option value="5"<?php if ((isset($detalle['filtro1']) && $detalle['filtro1'] == 5)){ echo ' selected';} ?>>Evento</option>
													</select>
													<span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Estado que determina el botón que se mostrará. Si es un Evento que no pertenece a AVO, como por ejemplo Asambela Anual, se debe seleccionar Evento." title=""> <i class="fa fa-question"></i></button>
			                                    </div>
						                    </div>
							                <label class="text-right col-sm-3 control-label">Orden</label>
						                    <div class="col-sm-2">
		                                        <div class="input-group">
			                                        <input type="text" name="orden" class="form-control" value="<?php echo (isset($detalle['orden'])) ? $detalle['orden']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en el que se mostrará. Se puede dejar vacío y luego acomodar el orden accediendo a Ordenar desde el listado de eventos." title=""> <i class="fa fa-question"></i></button>
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
									$CI->load->model("Eventos_model");
									$item = $this->Eventos_model->getContenidoDetalleIdioma($detalle['id'], $idioma['extension']);
									if($item['id']) { $imagen = $this->Eventos_model->getMedia($detalle['id'], $idioma['extension']); }
								}
							?>
							
	                            <div class="panel-body">
								 <div class="row">

					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <label class="text-right col-md-2 control-label">T&iacute;tulo</label>
											<div class="col-md-4">
		                                        <div class="input-group">
		                                        	<input type="text" name="titulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del evento/curso que se mostrará en el sitio." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
											</div>
											
						                    <label class="text-right col-md-2 control-label">Tipo</label>
						                    <div class="col-md-4">
		                                        <div class="input-group">
		                                        	<input type="text" name="subtitulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['subtitulo'])) ? $item['subtitulo']: null; ?>"><span class="input-group-btn"> <button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Tipo de evento: si es Evento, Curso Gratuito, etc. Debe incluirse CURSO GRATUITO para que en el carousel del home se muestre con diferente fondo." title=""> <i class="fa fa-question"></i></span>
		                                        </div>
						                    </div>
				                 		</div>
					                </div>
	
					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <label class="text-right col-md-2 control-label">Fecha</label>
						                    <div class="col-md-4">
		                                        <div class="input-group">
			                                        <input type="text" name="texto_adicional_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['texto_adicional'])) ? $item['texto_adicional']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Fecha del evento/curso." title=""> <i class="fa fa-question"></i></button>
			                                    </div>
		                                    </div>
		                                    
						                    <label class="text-right col-md-2 control-label">Lugar</label>
						                    <div class="col-md-4">
		                                        <div class="input-group">
		                                        	<input type="text" name="contenido1_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['contenido1'])) ? $item['contenido1']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Determina si el contenido se mostrará en el sitio." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
					                    	</div>
				                 		</div>
					                </div>

					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <label class="text-right col-md-2 control-label">Valor</label>
											<div class="col-md-4">
		                                        <div class="input-group">
			                                        <input type="text" name="precio_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['precio'])) ? $item['precio']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Valor del curso que figura debajo del título en el listado de cursos del sitio." title=""> <i class="fa fa-question"></i></button>
			                                    </div>
											</div>
											
						                    <label class="text-right col-md-2 control-label">Dictado por</label>
						                    <div class="col-md-4">
		                                        <div class="input-group">
			                                        <input type="text" name="contenido3_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['contenido3'])) ? $item['contenido3']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Quien dicta el curso, si es evento se deja sin contenido." title=""> <i class="fa fa-question"></i></button>
			                                    </div>
						                    </div>
				                 		</div>
					                </div>

					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
											<?php if(isset($imagen['archivo'])) { ?>
		                            		<label class="text-right col-md-2 control-label">Imagen Actual</label>
		                            		<div class="col-md-4">
			                            		<input type="hidden" name="imagen_slide_<?php echo $idioma['extension'];?>" value="<?php echo $imagen['archivo'];?>">
		                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$imagen['archivo']);?>" style="height:auto;width:250px;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
			                            	<?php } ?>
										</div>
										
										<div class="form-group m-b-md pull-left full-width m-t-md">
		                            		<label class="text-right col-md-2 control-label">Imagen</label>
							                <div class="col-md-4">
		                                        <div class="input-group">
			                                        <input type="file" name="imagen_<?php echo $idioma['extension'];?>" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Si el curso/evento se va a promocionar en el slide principal, debe tener 1200x400 píxeles o proporcionales mayores. En caso de que no se publique en ese slideshow, puede tener un tamaño proporcional a ese menor." title=""> <i class="fa fa-question"></i></button>
			                                    </div>
							                </div>

						                    <label class="text-right col-md-2 control-label">Link</label>
						                    <div class="col-md-4">
		                                        <div class="input-group">
			                                        <input type="text" name="contenido4_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['contenido4'])) ? $item['contenido4']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Link externo del curso, para los botones de suscripción, etc. y para el acceso directo desde el carousel del home. Si se deja sin link desde el carousel del home y no es EVENTO, se linkea a la sección de AVO." title=""> <i class="fa fa-question"></i></button>
			                                    </div>
			                                </div>
										</div>
									</div>

					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
											<div class="ibox-title bg-muted"><h5>Contenido</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Si no se redacta en el siguiente campo, se recomienda pegar el texto sin formato y aplicarle el formato deseado en cada caso." title=""> <i class="fa fa-question"></i></button></div>
											<textarea class="form-control summernote" name="contenido2_<?php echo $idioma['extension'];?>" rows="10"><?php echo(isset($item['contenido2'])) ? $item['contenido2']: null?></textarea>
										</div>
					                </div>

					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">SEO</h2>
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <div class="col-md-4">
						                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>Título</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de la página." title=""> <i class="fa fa-question"></i></button></div>
							                    <div class="ibox-content no-padding"><textarea class="form-control" name="seo_titulo_<?php echo $idioma['extension'];?>" rows="5"><?php echo(isset($item['seo_titulo'])) ? $item['seo_titulo']: null?></textarea></div>
						                    </div>
						                    <div class="col-md-4">
						                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>Descripci&oacute;n</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Descripción de la página." title=""> <i class="fa fa-question"></i></button></div>
							                    <div class="ibox-content no-padding"><textarea class="form-control" name="seo_descripcion_<?php echo $idioma['extension'];?>" rows="5"><?php echo(isset($item['seo_descripcion'])) ? $item['seo_descripcion']: null?></textarea></div>
						                    </div>
						                    <div class="col-md-4">
						                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>Keywords</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Si bien están prácticamente en desuso, son palabras o frases que se asocian al contenido de la página." title=""> <i class="fa fa-question"></i></button></div>
							                    <div class="ibox-content no-padding"><textarea class="form-control" name="seo_keywords_<?php echo $idioma['extension'];?>" rows="5"><?php echo(isset($item['seo_keywords'])) ? $item['seo_keywords']: null?></textarea></div>
						                    </div>
					                 	</div>
									</div>
			                    </div>
								<input type="hidden" name="url_slide_<?php echo $idioma['extension'];?>" value="http://bomberosamericanos.org/<?php echo $idioma['extension'];?>/academia-virtual-oba">
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
        height: 250,
        toolbar: [
          ['insert', ['file'], ['image']],
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
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