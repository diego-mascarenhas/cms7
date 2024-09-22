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
                <h2>Paquetes</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('cms-v2/paginas');?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('cms-v2/servicios');?>">Paquetes</a>
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
			<input type="hidden" name="id_tipo" value="19">
			<input type="hidden" name="medidas1" value="840x570">
			<input type="hidden" name="medidas2" value="800x570">
			
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
						                    <label class="text-right col-sm-1 control-label">Categor&iacute;a</label>
						                    <div class="col-sm-3">
							                    <?php echo form_dropdown('id_categoria', $categorias, (isset($detalle['id_categoria'])) ? $detalle['id_categoria'] : null, 'class="required form-control m-b"'); ?>
						                   </div>
											<div class="col-sm-2 pull-left text-right"><a class="btn btn-warning" href="<?php echo base_url('cms-v2/servicios/categorias');?>">Gestionar categor&iacute;as</a></div>
						                    <label class="text-right col-sm-2 control-label">T&iacute;tulo</label>
											<div class="col-sm-4">
		                                        <div class="input-group">
		                                        	<input type="text" name="titulo" class="form-control" value="<?php echo (isset($detalle['titulo'])) ? $detalle['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título general, no se mostrará en el sitio." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
											</div>
				                 		</div>
					                </div>
					                <hr class="hr-line-dashed pull-left full-width">
					                
					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <label class="text-right col-sm-1 control-label">Orden</label>
						                    <div class="col-sm-1">
		                                        <div class="input-group">
			                                        <input type="text" name="orden" class="form-control" value="<?php echo (isset($detalle['orden'])) ? $detalle['orden']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en el que se mostrará. Se puede dejar vacío y luego acomodar el orden accediendo a Ordenar desde el listado de información." title=""> <i class="fa fa-question"></i></button>
			                                    </div>
		                                    </div>
				                            
						                    <label class="text-right col-sm-2 control-label">Estado</label>
				                            <div class="col-sm-4">
		                                        <div class="input-group">
						                            <select name="estado" class="required form-control m-b">
							                            <option value="3"<?php if (isset($detalle['estado']) && $detalle['estado'] == '3') echo ' selected'; ?>>Activo</option>
							                            <option value="0"<?php if (isset($detalle['estado']) && $detalle['estado'] == '0') echo ' selected'; ?>>Inactivo</option>
						                            </select>
						                            <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Determina si el contenido se mostrará en el sitio." title=""> <i class="fa fa-question"></i></button></span>
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
									$CI->load->model("Servicios_model");
									$parametros['id'] = $detalle['id'];
									$parametros['idioma'] = $idioma['extension'];
									$item = $this->Servicios_model->getServicioDetalleIdioma($parametros);
									
									if($item['id_item'])
									{
										$parametros['id_tipo'] = 32;
										$imagen1 = $this->Servicios_model->getMedia($parametros);
										$parametros2['id'] = $detalle['id'];
										$parametros2['idioma'] = $idioma['extension'];
										$parametros2['id_tipo'] = 35;
										$imagen2 = $this->Servicios_model->getMedia($parametros2);
									}
								}
							?>
	                            <div class="panel-body">
								 <div class="row">
									 <?php echo $imagen1['id'];?>
									 <br>
									 <?php //echo $imagen2['id'];?>
					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <label class="text-right col-sm-1 control-label">T&iacute;tulo</label>
											<div class="col-sm-5">
		                                        <div class="input-group">
		                                        	<input type="text" name="titulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del paquete que se mostrará en el sitio." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
											</div>

						                    <label class="text-right col-sm-1 control-label">Nombre (url)</label>
						                    <div class="col-sm-5">
		                                        <div class="input-group">
		                                        	<input type="text" name="url_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['uri'])) ? $item['uri']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Url del paquete, si se deja vacía toma el título sanitizado como url." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
		                                     </div>
										</div>
					                </div>
					                <hr class="hr-line-dashed pull-left full-width">
					                
					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <label class="text-right col-sm-1 control-label">Países</label>
						                    <div class="col-sm-5">
		                                        <div class="input-group">
		                                        	<input type="text" name="texto_adicional_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['texto_adicional'])) ? $item['texto_adicional']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Países del paquete" title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
		                                     </div>

						                    <label class="text-right col-sm-1 control-label">Distancia</label>
						                    <div class="col-sm-5">
		                                        <div class="input-group">
		                                        	<input type="text" name="contenido6_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['contenido6'])) ? $item['contenido6']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Distancia." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
		                                     </div>
				                 		</div>
					                </div>
					                <hr class="hr-line-dashed pull-left full-width">

					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
                                         	<label class="col-sm-1 control-label text-right">Galería</label>
                                         	<div class="col-sm-5"><?php echo form_dropdown('id_proyecto_'.$idioma['extension'], $media_proyectos, (isset($item['id_proyecto'])) ? $item['id_proyecto'] : null, 'class="form-control m-b"'); ?></div>
                                         	<label class="col-sm-1 control-label text-right">Días</label>
                                         	<div class="col-sm-5">
                                         		<div class="input-group">
		                                        	<input type="text" name="subtitulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['subtitulo'])) ? $item['subtitulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Días y noches del paquete" title=""> <i class="fa fa-question"></i></button></span></div>
                                         	</div>
				                 		</div>
					                </div>
					                <hr class="hr-line-dashed pull-left full-width">
	
					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <label class="text-right col-sm-1 control-label">Puntaje</label>
											<div class="col-sm-5">
		                                        <div class="input-group">
		                                        	<input type="text" name="puntaje_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['puntaje'])) ? $item['puntaje']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Puntaje en estrellas" title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
											</div>

						                    <label class="text-right col-sm-1 control-label">Precio</label>
						                    <div class="col-sm-5">
		                                        <div class="input-group">
		                                        	<input type="text" name="precio_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['precio'])) ? $item['precio']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Precio del paquete" title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
		                                     </div>
				                 		</div>
					                </div>
					                <hr class="hr-line-dashed pull-left full-width">

					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <label class="text-right col-sm-2 control-label">Destacada</label>
				                            <div class="col-sm-2">
		                                        <div class="input-group">
						                            <select name="destacado_<?php echo $idioma['extension'];?>" class="required form-control m-b">
							                            <option value="1"<?php if (isset($item['destacado']) && $item['destacado'] == '1') echo ' selected'; ?>>Sí</option>
							                            <option value="0"<?php if (isset($item['destacado']) && $item['destacado'] == '0') echo ' selected'; ?>>No</option>
						                            </select>
						                            <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Determina si el contenido se mostrará entre las tres primeras destacadas del Home y Miembros. Si hay más de 3 destacadas se mostrarán las últimas cargadas." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
					                         </div>

						                    <label class="text-right col-sm-2 control-label">Publicar en Slide</label>
				                            <div class="col-sm-2">
		                                        <div class="input-group">
						                            <select name="destacado_slide_<?php echo $idioma['extension'];?>" class="required form-control m-b">
							                            <option value="1"<?php if (isset($item['destacado_slide']) && $item['destacado_slide'] == '1') echo ' selected'; ?>>Sí</option>
							                            <option value="0"<?php if (isset($item['destacado_slide']) && $item['destacado_slide'] == '0') echo ' selected'; ?>>No</option>
						                            </select>
						                            <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Determina si el contenido se mostrará en el slideshow principal." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
					                         </div>

						                    <label class="text-right col-sm-2 control-label">Estado</label>
				                            <div class="col-sm-2">
		                                        <div class="input-group">
						                            <select name="estado_<?php echo $idioma['extension'];?>" class="required form-control m-b">
							                            <option value="3"<?php if (isset($item['estado']) && $item['estado'] == '3') echo ' selected'; ?>>Activo</option>
							                            <option value="0"<?php if (isset($item['estado']) && $item['estado'] == '0') echo ' selected'; ?>>Inactivo</option>
						                            </select>
						                            <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Determina si el contenido se mostrará en el sitio." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
					                         </div>
			                            </div>
					                </div>
					                <hr class="hr-line-dashed pull-left full-width">

					                <div class="col-lg-6 p-xxs">
										<?php if((isset($item['titulo'])) && $imagen1['archivo']) { ?>
											<div class="form-group m-b-md pull-left full-width m-t-md">
			                            		<label class="text-right col-sm-4 control-label">Imagen Actual</label>
			                            		<div class="col-sm-8">
				                            		<input type="hidden" name="imagen1_<?php echo $idioma['extension'];?>" value="<?php echo $imagen1['archivo'];?>">
			                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$imagen1['archivo']);?>" style="height:auto;width:250px;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
											</div>
										<?php } ?>

		                            		<label class="text-right col-sm-4 control-label">Imagen</label>
							                <div class="col-sm-8">
		                                        <div class="input-group">
			                                       <input type="file" name="imagen1_<?php echo $idioma['extension'];?>" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Si la información se va a publicar en el slide principal, debe tener 1200x400 píxeles o proporcionales mayores. En caso de que no se publique en ese slideshow, puede tener un tamaño proporcional a ese menor." title=""> <i class="fa fa-question"></i></button>
			                                    </div>
							                </div>
										</div>

					                <div class="col-lg-6 p-xxs">
										<?php if((isset($item['titulo'])) && $imagen2['archivo']) { ?>
											<div class="form-group m-b-md pull-left full-width m-t-md">
			                            		<label class="text-right col-sm-4 control-label">Mapa Actual</label>
			                            		<div class="col-sm-8">
				                            		<input type="hidden" name="imagen2_<?php echo $idioma['extension'];?>" value="<?php echo $imagen2['archivo'];?>">
			                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$imagen2['archivo']);?>" style="height:auto;width:250px;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
											</div>
										<?php } ?>

		                            		<label class="text-right col-sm-4 control-label">Mapa</label>
							                <div class="col-sm-8">
		                                        <div class="input-group">
			                                       <input type="file" name="imagen2_<?php echo $idioma['extension'];?>" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Mapa en imagen, con medidas." title=""> <i class="fa fa-question"></i></button>
			                                    </div>
							                </div>
										</div>
									</div>
					                <hr class="hr-line-dashed pull-left full-width">

					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
											<div class="col-lg-6 p-xxs">
												<div class="ibox-title bg-muted"><h5>Introduccción</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Texto de introduccción del paquete, máximo 200 caracteres" title=""> <i class="fa fa-question"></i></button></div>
												<textarea class="form-control summernote2" name="contenido5_<?php echo $idioma['extension'];?>" rows="10"><?php echo(isset($item['contenido5'])) ? $item['contenido5']: null?></textarea>
											</div>
											<div class="col-lg-6 p-xxs">
												<div class="ibox-title bg-muted"><h5>Qué esperar/Highlights</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Si no se redacta en el siguiente campo, se recomienda pegar el texto sin formato y aplicarle el formato deseado en cada caso." title=""> <i class="fa fa-question"></i></button></div>
												<textarea class="form-control summernote" name="contenido1_<?php echo $idioma['extension'];?>" rows="10"><?php echo(isset($item['contenido1'])) ? $item['contenido1']: null?></textarea>
											</div>
				                 		</div>
					                </div>


					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
											<div class="col-lg-6 p-xxs">
												<div class="ibox-title bg-muted"><h5>Hoteles/Precio</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Si no se redacta en el siguiente campo, se recomienda pegar el texto sin formato y aplicarle el formato deseado en cada caso." title=""> <i class="fa fa-question"></i></button></div>
												<textarea class="form-control summernote" name="contenido2_<?php echo $idioma['extension'];?>" rows="10"><?php echo(isset($item['contenido2'])) ? $item['contenido2']: null?></textarea>
											</div>
											<div class="col-lg-6 p-xxs">
												<div class="ibox-title bg-muted"><h5>Itinerario</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Si no se redacta en el siguiente campo, se recomienda pegar el texto sin formato y aplicarle el formato deseado en cada caso." title=""> <i class="fa fa-question"></i></button></div>
												<textarea class="form-control summernote" name="contenido3_<?php echo $idioma['extension'];?>" rows="10"><?php echo(isset($item['contenido3'])) ? $item['contenido3']: null?></textarea>
											</div>
										</div>
					                </div>


					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
											<div class="col-lg-6 p-xxs">
												<div class="ibox-title bg-muted"><h5>Opcionales</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Si no se redacta en el siguiente campo, se recomienda pegar el texto sin formato y aplicarle el formato deseado en cada caso." title=""> <i class="fa fa-question"></i></button></div>
												<textarea class="form-control summernote" name="contenido4_<?php echo $idioma['extension'];?>" rows="10"><?php echo(isset($item['contenido4'])) ? $item['contenido4']: null?></textarea>
											</div>
											<div class="col-lg-6 p-xxs">
												<div class="ibox-title bg-muted"><h5>Detalles</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Si no se redacta en el siguiente campo, se recomienda pegar el texto sin formato y aplicarle el formato deseado en cada caso." title=""> <i class="fa fa-question"></i></button></div>
												<textarea class="form-control summernote" name="contenido7_<?php echo $idioma['extension'];?>" rows="10"><?php echo(isset($item['contenido7'])) ? $item['contenido7']: null?></textarea>
											</div>
										</div>
					                </div>

					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">SEO</h2>
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <div class="col-md-6">
						                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>Título</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de la página." title=""> <i class="fa fa-question"></i></button></div>
							                    <div class="ibox-content no-padding"><textarea class="form-control" name="seo_titulo_<?php echo $idioma['extension'];?>" rows="5"><?php echo(isset($item['seo_titulo'])) ? $item['seo_titulo']: null?></textarea></div>
						                    </div>
						                    <div class="col-md-6">
						                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>Descripci&oacute;n</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Descripción de la página." title=""> <i class="fa fa-question"></i></button></div>
							                    <div class="ibox-content no-padding"><textarea class="form-control" name="seo_descripcion_<?php echo $idioma['extension'];?>" rows="5"><?php echo(isset($item['seo_descripcion'])) ? $item['seo_descripcion']: null?></textarea></div>
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
        height: 250,
        toolbar: [
          ['insert', ['file'], ['image']],
          ['style', ['style']],
          ['font', ['bold', 'italic', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link']],
          ['view', ['codeview']],
          ['insert', ['grid']]
        ],
        styleTags: ['p', 'code', 'span', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6']
  }).on("summernote.enter", function(we, e) {
      $(this).summernote('pasteHTML', '<br>&VeryThinSpace;');
      e.preventDefault();
});

$('.summernote2').summernote({
        placeholder: 'Ingrese contenido...',
        tabsize: 2,
        height: 120,
        toolbar: [
          ['insert', ['file'], ['image']],
          ['style', ['style']],
          ['font', ['bold', 'italic', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link']],
          ['view', ['codeview']],
          ['insert', ['grid']]
        ],
        styleTags: ['p', 'code', 'span', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6']
  }).on("summernote.enter", function(we, e) {
      $(this).summernote('pasteHTML', '<br>&VeryThinSpace;');
      e.preventDefault();
});

$('[data-toggle="tooltip"]').tooltip(); 
</script>