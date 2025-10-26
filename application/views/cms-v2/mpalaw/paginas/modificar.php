<style>
.note-editor.note-frame { border:1px solid #ebebeb; border-radius:0;}
.contact-box { min-height: 210px;max-height: 210px; }
.tooltip-inner {max-width: 250px;width: 250px;}
</style>

       <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-xs-8 col-sm-8 col-lg-8">
                <h2>Sitio web Páginas</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="/cms-v2">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('cms-v2/paginas/');?>">Páginas</a>
                    </li>
                    <li class="active">
                        <strong><?php echo (empty($detalle['id'])) ? 'Crear nuevo' : 'Modificar'; ?></strong>
                    </li>
                </ol>
            </div>
            <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
            </div>
        </div>
        
       <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
	   <input type="hidden" name="id_con_secciones" value="<?php echo $detalle['id_con_secciones']; ?>">
        <div class="wrapper wrapper-content animated fadeInRight">
	        <div class="row">
	            <div class="col-lg-12">

	                <div class="ibox float-e-margins">
	                    <div class="ibox-title">
	                        <h5>Modificar contenido de <a href="<?php echo base_url('cms-v2/paginas/modificar/'.$detalle['id']);?>"><?php echo $detalle['seccion']; ?></a></h5>
	                    </div>
	                    <div class="ibox-content">
	                        <?php if (validation_errors()) : ?>
								<div class="col-md-12">
									<div class="alert alert-danger" role="alert">
										<?php echo validation_errors(); ?>
									</div>
								</div>
							<?php endif; ?>
							<?php if (isset($error)) : ?>
								<div class="col-md-12">
									<div class="alert alert-danger" role="alert">
										<?php echo $error; ?>
									</div>
								</div>
							<?php endif; ?>

							<?php if($detalle['id_con_secciones'] == 960) { ?>
							<input type="hidden" name="medidas" value="430x540">
                           	<input type="hidden" name="id_imagen_tipo" value="13">
                           	
                            <div class="form-group">
			                    <label class="text-right col-sm-1 control-label">Nombre</label>
			                    <div class="col-sm-4 col-md-5">
                                    <div class="input-group">
                                    	<input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Nombre." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
			                    <label class="text-right col-sm-1 control-label">Cargo</label>
			                    <div class="col-sm-4 col-md-5">
                                    <div class="input-group">
                                    	<input type="text" name="subtitulo" class="form-control" value="<?php echo (isset($item['subtitulo'])) ? $item['subtitulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Cargo." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
			                    <label class="text-right col-sm-1 control-label">Email y teléfono</label>
			                    <div class="col-sm-4 col-md-5">
                                    <div class="input-group">
                                    	<input type="text" name="texto_adicional" class="form-control" value="<?php echo (isset($item['texto_adicional'])) ? $item['texto_adicional']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Email y teléfono separado por /." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
			                    <label class="text-right col-sm-1 control-label">Linkedin</label>
			                    <div class="col-sm-4 col-md-5">
                                    <div class="input-group">
                                    	<input type="text" name="contenido7" class="form-control" value="<?php echo (isset($item['contenido7'])) ? $item['contenido7']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Linkedin." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
				                <div class="col-lg-6 p-xxs">
									<?php if((isset($item['titulo'])) && $item['imagen']) { ?>
										<div class="form-group pull-left full-width">
		                            		<label class="text-right col-sm-4 control-label">Imagen Actual</label>
		                            		<div class="col-sm-8">
		                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$item['imagen']);?>" style="height:auto;width:120px;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
										</div>
									<?php } ?>
										<div class="form-group pull-left full-width m-t-none m-b-none">
	                            		<label class="text-right col-sm-3 control-label">Imagen</label>
						                <div class="col-sm-8">
	                                        <div class="input-group">
		                                       <input type="file" name="imagen" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen en jpg, gif o png de 430x540 píxeles. Recuerde que el peso debe ser el mínimo posible para no afectar la descarga del site." title=""> <i class="fa fa-question"></i></button>
		                                    </div>
						                </div>
									</div>
								</div>
			                    
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
			                    <label class="text-right col-sm-1 control-label">Intro <button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Texto de introducción, al lado de la imagen. Redactar en el box o copiar y pegar como texto plano y luego editar." title=""> <i class="fa fa-question"></i></button></label>
			                    <div class="col-sm-5">
								    <textarea class="form-control summernote2" name="contenido1" rows="4"><?php echo (isset($item['contenido1'])) ? $item['contenido1']: null; ?></textarea></div>
								    
			                    <label class="text-right col-sm-1 control-label">Texto <button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Texto. Redactar en el box o copiar y pegar como texto plano y luego editar." title=""> <i class="fa fa-question"></i></button></label>
			                    <div class="col-sm-5">
								    <textarea class="form-control summernote2" name="contenido2" rows="4"><?php echo (isset($item['contenido2'])) ? $item['contenido2']: null; ?></textarea></div>
		                    </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
			                    <label class="text-right col-sm-1 control-label">Texto destacado<button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Texto destacado en fondo bordó. Redactar en el box o copiar y pegar como texto plano y luego editar, el título como H4 y el texto como párrafo." title=""> <i class="fa fa-question"></i></button></label>
			                    <div class="col-sm-5">
								    <textarea class="form-control summernote2" name="contenido3" rows="4"><?php echo (isset($item['contenido3'])) ? $item['contenido3']: null; ?></textarea></div>
			                    <label class="text-right col-sm-1 control-label">Tabs de info detallada <button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Tabs con Awards and Honors e info detallada. Redactar en el box o copiar y pegar como texto plano y luego editar: el título como H4 y el texto como Lista desordenada." title=""> <i class="fa fa-question"></i></button></label>
			                    <div class="col-sm-5">
								    <textarea class="form-control summernote2" name="contenido4" rows="4"><?php echo (isset($item['contenido4'])) ? $item['contenido4']: null; ?></textarea></div>
		                    </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
			                    <label class="text-right col-sm-1 control-label">Educación <button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Texto de educación. Redactar en el box o copiar y pegar como texto plano y luego editar: el texto como Lista desordenada." title=""> <i class="fa fa-question"></i></button></label>
			                    <div class="col-sm-5">
								    <textarea class="form-control summernote2" name="contenido5" rows="4"><?php echo (isset($item['contenido5'])) ? $item['contenido5']: null; ?></textarea></div>
			                    <label class="text-right col-sm-1 control-label">COURT ADMISSIONS <button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Texto de COURT ADMISSIONS. Redactar en el box o copiar y pegar como texto plano y luego editar: el texto como Lista desordenada." title=""> <i class="fa fa-question"></i></button></label>
			                    <div class="col-sm-5">
								    <textarea class="form-control summernote2" name="contenido6" rows="4"><?php echo (isset($item['contenido6'])) ? $item['contenido6']: null; ?></textarea></div>
		                    </div>
                            <div class="hr-line-dashed"></div>

							<?php } elseif($detalle['id_con_secciones'] == 954) { ?>
                            <div class="form-group">
			                    <label class="text-right col-sm-1 control-label">Título</label>
			                    <div class="col-sm-4 col-md-10">
                                    <div class="input-group">
                                    	<input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título que se mostrará en color bordó arriba del texto principal." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
                            </div>
                            <div class="form-group">
			                    <label class="text-right col-sm-1 control-label">Texto <button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Texto del slide. Se debe copiar sin formato, seleccionarlo cada línea y aplicar H3 (se encuentra en el botón de varita mágica). Para el subrayado: luego de aplicar H3 seleccionar el texto a subrayar y aplicar U." title=""> <i class="fa fa-question"></i></button></label>
			                    <div class="col-sm-10">
								    <textarea class="form-control summernote2" name="contenido1" rows="4"><?php echo (isset($item['contenido1'])) ? $item['contenido1']: null; ?></textarea></div>
                            </div>
                            <div class="hr-line-dashed"></div>

							<?php } elseif($detalle['id_con_secciones'] == 957) { ?>
							<input type="hidden" name="id_imagen_tipo" value="13">
							<input type="hidden" name="medidas" value="100x100">
                           	
                            <div class="form-group">
			                    <label class="text-right col-sm-1 control-label">Título</label>
			                    <div class="col-sm-4 col-md-5">
                                    <div class="input-group">
                                    	<input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del ítem." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
			                    <label class="text-right col-sm-1 control-label">Subtítulo</label>
			                    <div class="col-sm-4 col-md-5">
                                    <div class="input-group">
                                    	<input type="text" name="subtitulo" class="form-control" value="<?php echo (isset($item['subtitulo'])) ? $item['subtitulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Subtítulo en color bordó." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
			                    <label class="text-right col-sm-1 control-label">Texto <button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Texto del ítem." title=""> <i class="fa fa-question"></i></button></label>
			                    <div class="col-sm-5">
								    <textarea class="form-control summernote2" name="contenido1" rows="4"><?php echo (isset($item['contenido1'])) ? $item['contenido1']: null; ?></textarea></div>

				                <div class="col-lg-6 p-xxs">
									<?php if((isset($item['titulo'])) && $item['imagen']) { ?>
										<div class="form-group pull-left full-width">
		                            		<label class="text-right col-sm-4 control-label">Imagen Actual</label>
		                            		<div class="col-sm-8">
		                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$item['imagen']);?>" style="height:auto;width:120px;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
										</div>
									<?php } ?>
										<div class="form-group pull-left full-width m-t-none m-b-none">
	                            		<label class="text-right col-sm-3 control-label">Imagen</label>
						                <div class="col-sm-8">
	                                        <div class="input-group">
		                                       <input type="file" name="imagen" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen en jpg, gif o png de 100x100 píxeles. Recuerde que el peso debe ser el mínimo posible para no afectar la descarga del site." title=""> <i class="fa fa-question"></i></button>
		                                    </div>
						                </div>
									</div>
								</div>
                            </div>
                            <div class="hr-line-dashed"></div>

							<?php } elseif($detalle['id_con_secciones'] == 963) { ?>
							<input type="hidden" name="id_imagen_tipo" value="13">
							<input type="hidden" name="medidas" value="70x70">
                           	
                            <div class="form-group">
			                    <label class="text-right col-sm-1 control-label">Título</label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de la sección, deberá incluir un barra (/) como salto de línea." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
				                <div class="col-lg-6 p-xxs">
									<?php if((isset($item['titulo'])) && $item['imagen']) { ?>
										<div class="form-group pull-left full-width">
		                            		<label class="text-right col-sm-4 control-label">Imagen Actual</label>
		                            		<div class="col-sm-8">
		                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$item['imagen']);?>" style="height:auto;width:120px;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
										</div>
									<?php } ?>
										<div class="form-group pull-left full-width m-t-none m-b-none">
	                            		<label class="text-right col-sm-3 control-label">Imagen</label>
						                <div class="col-sm-8">
	                                        <div class="input-group">
		                                       <input type="file" name="imagen" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen en jpg, gif o png de 70x70 píxeles. Recuerde que el peso debe ser el mínimo posible para no afectar la descarga del site." title=""> <i class="fa fa-question"></i></button>
		                                    </div>
						                </div>
									</div>
								</div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
			                    <label class="text-right col-sm-1 control-label">Texto <button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Texto del ítem." title=""> <i class="fa fa-question"></i></button></label>
			                    <div class="col-sm-10">
								    <textarea class="form-control summernote2" name="contenido1" rows="4"><?php echo (isset($item['contenido1'])) ? $item['contenido1']: null; ?></textarea></div>

                            </div>
                            <div class="hr-line-dashed"></div>

							<?php } elseif($detalle['id_con_secciones'] == 966) { ?>
                            <div class="form-group">
			                    <label class="text-right col-sm-1 control-label">Título</label>
			                    <div class="col-sm-4 col-md-10">
                                    <div class="input-group">
                                    	<input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del ítem." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
			                    <label class="text-right col-sm-1 control-label">Texto <button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Texto del ítem." title=""> <i class="fa fa-question"></i></button></label>
			                    <div class="col-sm-10">
								    <textarea class="form-control summernote2" name="contenido1" rows="4"><?php echo (isset($item['contenido1'])) ? $item['contenido1']: null; ?></textarea></div>

                            </div>
                            <div class="hr-line-dashed"></div>

							<?php } ?>
                            <div class="form-group">
			                    <label class="text-right col-sm-1 control-label">Orden</label>
			                    <div class="col-sm-5">
                                    <div class="input-group">
                                    	<input type="text" name="orden" class="form-control" value="<?php echo (isset($item['orden'])) ? $item['orden']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en el que se mostrará el ítem." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
	                            <label class="col-sm-1 control-label text-right">Estado</label>
	                            <div class="col-sm-4">
		                            <div class="radio radio-inline"><input type="radio" name="estado" value="3" <?php if (isset($item['estado']) && $item['estado'] == '3') echo 'checked="checked"'; ?>> <label> Activo </label></div>
		                            <div class="radio radio-inline"><input type="radio" name="estado" value="1" <?php if (isset($item['estado']) && $item['estado'] == '1') echo 'checked="checked"'; ?>><label> Inactivo </label></div>
		                            <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Determina si el ítem se mostrará en el sitio." title=""> <i class="fa fa-question"></i></button>
		                         </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                	<input type="hidden" name="idioma" value="<?php echo (!empty($item['idioma'])) ? $item['idioma'] : null; ?>">
                                	<input type="hidden" name="id" value="<?php echo (!empty($item['id'])) ? $item['id'] : null; ?>">
					                <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
					                <button class="btn btn-primary" type="submit">Guardar cambios</button>
                                </div>
                            </div>
		                </div>
		            </div>
		        </div>
		    </div>
        </div>
	    <?php echo form_close();?>

<!-- SUMMERNOTE -->
<script src="<?php echo base_url('assets/js/plugins/summernote/summernote.min.js'); ?>"></script>
<script>

$('.summernote2').summernote({
        placeholder: 'Ingrese contenido...',
        tabsize: 2,
        height: 180,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['insert', ['link']]
        ]
});
$('[data-toggle="tooltip"]').tooltip(); 
</script>
      
      
                                       