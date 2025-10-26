<style>
.note-editor.note-frame { border:0;}
.note-editable .row {margin: 0px;}
.note-editable .row div {border: 1px dotted;}
.tooltip-inner {max-width: 250px;width: 250px;}
</style>
         <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-8 col-sm-8 col-xs-8">
                <h2>eLearning</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('cms-v2/');?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('cms-v2/elearning/cursos');?>">Cursos</a>
                    </li>
                    <li class="active">
                        <strong><?php echo (empty($detalle['id'])) ? 'Crear nuevo' : 'Modificar'; ?></strong>
                    </li>
                </ol>
            </div>
            <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
			<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
			<?php echo (empty($detalle['id'])) ? '<input type="hidden" name="codigo" value="'.rand(9999, 99999999).'">' : null; ?>
			<input type="hidden" name="medidas1" value="720x550">
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
        
		<?php if ($this->session->flashdata('mensaje')) { ?>
		<div class="col-md-12">
			<?php if ($this->session->flashdata('mensaje') == 'error') { ?>
			<div class="alert alert-danger alert-dismissable" role="alert">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <?php echo $error; ?></div>
			<?php } else { ?>
			<div class="alert alert-success alert-dismissable" role="alert">
				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
				<?php echo $this->session->flashdata('mensaje');?></div>
			<?php } ?>
		</div>
		<?php } ?>


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
	                        <div id="tab-0" class="tab-pane active">
	                            <div class="panel-body">
								 <div class="row">
					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <label class="text-right col-sm-1 control-label">Categoría</label>
						                    <div class="col-sm-3"><?php echo (isset($detalle['id_categoria'])) ? form_dropdown('id_categoria', $categorias, $detalle['id_categoria'], array('class'=>'form-control m-b')) : form_dropdown('id_categoria', $categorias, null, array('class'=>'form-control m-b')); ?></div>
											<div class="col-sm-2 pull-left text-left"><a class="btn btn-warning" href="<?php echo base_url('cms-v2/elearning/categorias');?>">Gestionar categorías</a></div>
				                 		</div>
					                </div>
					                <hr class="hr-line-dashed pull-left full-width">

					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <label class="text-right col-sm-1 control-label">Título</label>
											<div class="col-sm-3">
		                                        <div class="input-group">
		                                        	<input type="text" name="titulo" class="form-control" value="<?php echo (isset($detalle['titulo'])) ? $detalle['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de la información que se mostrará en el sitio." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
											</div>
						                    <label class="text-right col-sm-1 control-label">Orden</label>
						                    <div class="col-sm-1">
		                                        <div class="input-group">
			                                        <input type="text" name="orden" class="form-control" value="<?php echo (isset($detalle['orden'])) ? $detalle['orden']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en el que se mostrará. Se puede dejar vacío y luego acomodar el orden accediendo a Ordenar desde el listado de información." title=""> <i class="fa fa-question"></i></button>
			                                    </div>
		                                    </div>
				                            <label class="col-sm-2 control-label text-right">Estado</label>
				                            <div class="col-sm-3">
					                            <div class="radio radio-inline"><input type="radio" name="estado" value="3" <?php if (isset($detalle['estado']) && $detalle['estado'] == '3') echo 'checked="checked"'; ?>> <label> Activo </label></div>
					                            <div class="radio radio-inline"><input type="radio" name="estado" value="1" <?php if (isset($detalle['estado']) && $detalle['estado'] == '1') echo 'checked="checked"'; ?>><label> Inactivo </label></div>
					                            <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Determina si el contenido se mostrará en el sitio." title=""> <i class="fa fa-question"></i></button>
					                         </div>
										</div>
					                </div>
	                            </div>
	                        </div>
	                        </div>
	
	                        <!-- Items Idiomas -->
                        	<?php foreach($idiomas as $idioma) { ?>
	                        <div id="tab-<?php echo $idioma['orden'];?>" class="tab-pane">
                        	<?php 
								$CI =& get_instance();
								$CI->load->model("Elearning_model");
								$profesores = $this->Elearning_model->comboProfesores(970,667,$idioma['extension']);
									
								if(!empty($detalle['id']))
								{
									$item = $this->Elearning_model->getCursoDetalleIdioma($detalle['id'], $idioma['extension']);
									if($item['id_item'])
									{
										$imagen= $this->Elearning_model->getMedia($detalle['id'], 37, $idioma['extension']);
										$archivo = $this->Elearning_model->getMedia($detalle['id'], 9, $idioma['extension']);
									}
									$relacionados = json_decode($item['profesores'], true);
								}
							?>
	                            <div class="panel-body">
								 <div class="row">
					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Contenido</h2>
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <label class="text-right col-sm-1 control-label">Título</label>
											<div class="col-sm-4">
		                                        <div class="input-group">
		                                        	<input type="text" name="titulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de la información que se mostrará en el sitio." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
											</div>

						                    <label class="text-right col-sm-2 control-label">Nombre (url)</label>
						                    <div class="col-sm-4">
		                                        <div class="input-group">
		                                        	<input type="text" name="url_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['uri'])) ? $item['uri']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Url de la noticia, si se deja vacía toma el título sanitizado como url." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
		                                     </div>
				                 		</div>
					                </div>
	
					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <label class="text-right col-sm-1 control-label">Duración</label>
											<div class="col-sm-4">
		                                        <div class="input-group">
		                                        	<input type="text" name="duracion_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['duracion'])) ? $item['duracion']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Duración del curso." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
											</div>

						                    <label class="text-right col-sm-2 control-label">Fecha</label>
											<div class="col-sm-4">
				                                <div class="input-group date dia">
				                                	<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" name="fecha_<?php echo $idioma['extension'];?>" value="<?php if(isset($item['fecha'])) { $date = date_create($item['fecha']); echo date_format($date, 'd-m-Y'); } ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Fecha del curso." title=""> <i class="fa fa-question"></i></button></span>
				                                </div>
											</div>
										</div>
					                </div>

					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <label class="text-right col-sm-1 control-label">Contenido</label>
											<div class="col-lg-10 p-xxs">
												<textarea class="form-control summernote" name="contenido1_<?php echo $idioma['extension'];?>" rows="10"><?php echo(isset($item['contenido1'])) ? $item['contenido1']: null?></textarea>
											</div>
										</div>
					                </div>
					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <label class="text-right col-sm-1 control-label">Certificado</label>
				                            <div class="col-sm-3">
					                            <div class="radio radio-inline"><input type="radio" name="certificado_<?php echo $idioma['extension'];?>" value="1" <?php if (isset($item['certificado']) && $item['certificado'] == '1') echo 'checked="checked"'; ?>> <label> Sí </label></div>
					                            <div class="radio radio-inline"><input type="radio" name="certificado_<?php echo $idioma['extension'];?>" value="0" <?php if (isset($item['certificado']) && $item['certificado'] == '0') echo 'checked="checked"'; ?>><label> No </label></div>
					                            <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Determina si el curso se puede certificar." title=""> <i class="fa fa-question"></i></button>
					                         </div>

						                    <label class="text-right col-sm-1 control-label">Destacada</label>
				                            <div class="col-sm-3">
					                            <div class="radio radio-inline"><input type="radio" name="destacado_<?php echo $idioma['extension'];?>" value="1" <?php if (isset($item['destacado']) && $item['destacado'] == '1') echo 'checked="checked"'; ?>> <label> Sí </label></div>
					                            <div class="radio radio-inline"><input type="radio" name="destacado_<?php echo $idioma['extension'];?>" value="0" <?php if (isset($item['destacado']) && $item['destacado'] == '0') echo 'checked="checked"'; ?>><label> No </label></div>
					                            <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Determina si el contenido se mostrará en el Home en el carousel." title=""> <i class="fa fa-question"></i></button>
					                         </div>
			                            </div>
								   </div>

					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Profesores</h2>
										<div class="form-group m-b-md pull-left full-width m-t-md">
											<?php if(!empty($profesores)) { foreach($profesores as $profesor) { ?>	
											<div class="col-lg-2">
							                    <h4><input type="checkbox" name="profesores_<?php echo $idioma['extension'];?>[]" value="<?php echo $profesor['id'];?>" <?php if(isset($relacionados)) { foreach($relacionados as $rela) { if($profesor['id'] == $rela) { echo ' checked'; } } }?>>
												<?php echo $profesor['titulo'];?> </h4>
											</div>
											<?php } } else { echo 'No se encontraron resultados'; } ?>	
				                 		</div>
					                </div>

					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Media</h2>
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <label class="text-right col-sm-1 control-label">Código Video</label>
											<div class="col-sm-4">
		                                        <div class="input-group">
		                                        	<input type="text" name="video_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['video'])) ? $item['video']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Sólo código del video de Vimeo, por ejemplo: 763805701?h=801a1884f3" title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
											</div>

						                    <label class="text-right col-sm-2 control-label">Canal Videos</label>
											<div class="col-sm-4">
		                                        <div class="input-group">
		                                        <?php echo form_dropdown('id_proyecto_'.$idioma['extension'], $media_proyectos, (isset($item['id_proyecto'])) ? $item['id_proyecto'] : null, 'class="form-control m-b"'); ?><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Canal Asociado al curso." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
											</div>
				                 		</div>
					                </div>

					                <div class="col-lg-12 p-xxs">
										<?php if((isset($item['titulo'])) && $imagen['archivo']) { ?>
										<div class="form-group m-b-md pull-left full-width m-t-md">
		                            		<label class="m-l-md" >Imagen Actual</label><br>
		                            		<div class="col-sm-12 m-t-md m-l-md"><img src="<?php echo base_url('multimedia/thumbs/'.$imagen['archivo']);?>" style="height:auto;width:250px;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
										</div>
										<?php } ?>
											<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <label class="text-right col-sm-1 control-label">Imagen</label>
							                <div class="col-sm-4">
		                                        <div class="input-group">
			                                       <input type="file" name="imagen_<?php echo $idioma['extension'];?>" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Debe tener 620x480 píxeles o proporcionales. Obligatoria. Extensión .jpg, .png o .gif." title=""> <i class="fa fa-question"></i></button>
			                                    </div>
							                </div>
						                    <label class="text-right col-sm-2 control-label">Link</label>
											<div class="col-sm-4">
		                                        <div class="input-group">
		                                        	<input type="text" name="link_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['link'])) ? $item['link']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Link externo del curso." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
											</div>
										</div>
									</div>

					                <div class="col-lg-12 p-xxs">
										<?php if((isset($item['titulo'])) && $archivo['archivo']) { ?>
   										<div class="form-group m-b-md pull-left full-width m-t-md">
       							<label class="text-right col-sm-4 control-label">Archivo Actual</label>
        						<div class="col-sm-8">
         						<a href="<?php echo base_url('multimedia/'.$this->usuario->grupo.'/'.$this->usuario->id_empresa.'/'.$archivo['archivo']); ?>" target="_blank" class="btn btn-success">
         			      				 Ver o descargar archivo PDF
           						</a>
      							</div>
   							</div>
							<?php } ?>
											<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <label class="text-right col-sm-1 control-label">Archivo</label>
							                <div class="col-sm-4">
		                                        <div class="input-group">
			                                       <input type="file" name="archivo_<?php echo $idioma['extension'];?>" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Archivo del curso para descargar. No obligatorio." title=""> <i class="fa fa-question"></i></button>
			                                    </div>
							                </div>
								</div>
		                    </div>


					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Precios</h2>
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <label class="text-right col-sm-1 control-label">Precio</label>
											<div class="col-sm-4">
		                                        <div class="input-group">
		                                        	<input type="text" name="precio_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['precio'])) ? $item['precio']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Precio original." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
											</div>

						                    <label class="text-right col-sm-2 control-label">Precio oferta</label>
											<div class="col-sm-4">
		                                        <div class="input-group">
		                                        	<input type="text" name="precio_oferta_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['precio_oferta'])) ? $item['precio_oferta']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Precio con oferta." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
											</div>
					                 	</div>
									</div>

					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">SEO</h2>
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <div class="col-md-4">
						                    	<div class="ibox-title" style="background:#F5EFEF;"><h5>Título</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de la página." title=""> <i class="fa fa-question"></i></button></div>
							                    <div class="ibox-content no-padding"><textarea class="form-control" name="seo_titulo_<?php echo $idioma['extension'];?>" rows="5"><?php echo(isset($item['seo_titulo'])) ? $item['seo_titulo']: null?></textarea></div>
						                    </div>
						                    <div class="col-md-4">
						                    	<div class="ibox-title" style="background:#F5EFEF;"><h5>Descripci&oacute;n</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Descripción de la página." title=""> <i class="fa fa-question"></i></button></div>
							                    <div class="ibox-content no-padding"><textarea class="form-control" name="seo_descripcion_<?php echo $idioma['extension'];?>" rows="5"><?php echo(isset($item['seo_descripcion'])) ? $item['seo_descripcion']: null?></textarea></div>
						                    </div>
						                    <div class="col-md-4">
						                    	<div class="ibox-title" style="background:#F5EFEF;"><h5>Keywords</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Si bien están prácticamente en desuso, son palabras o frases que se asocian al contenido de la página." title=""> <i class="fa fa-question"></i></button></div>
							                    <div class="ibox-content no-padding"><textarea class="form-control" name="seo_keywords_<?php echo $idioma['extension'];?>" rows="5"><?php echo(isset($item['seo_keywords'])) ? $item['seo_keywords']: null?></textarea></div>
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
<!-- Data picker -->
<script src="<?php echo base_url('assets/js/plugins/datapicker/bootstrap-datepicker.js'); ?>"></script>

<script>
$('.summernote').summernote({
        placeholder: 'Ingrese contenido...',
        tabsize: 2,
        height: 180,
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

$('.dia.input-group.date').datepicker({
    todayBtn: "linked",
    keyboardNavigation: false,
    forceParse: false,
    calendarWeeks: true,
    autoclose: true,
    format: "dd-mm-yyyy",
    todayHighlight: true
});
</script>