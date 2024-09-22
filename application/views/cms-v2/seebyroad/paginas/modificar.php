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
        <?php 
        	switch($item['id_tipo'])
        	{
            	case '8': $medidas ='1920x600';break; //slide
            	case '605': $medidas ='1680x700';break; //1680x700 (banners home)
            	case '359': $medidas ='120x55';break; //120x55 (commitments)
            	case '362': $medidas ='55x55';break; //55x55 (offers)
            	case '365': $medidas ='140x140';break; //140x140 (testimonials)
            	case '331': $medidas ='465x380';break; //465x380 (experiences)
            	case '626': $medidas ='465x380';break; //465x380 (experiences)
            	case '305': $medidas ='1000x680';break; //1000x680 (destinos)
            	case '533': $medidas ='840x570';break; //840x570 (blog)
        	}
        	if($detalle['padre_seccion']) { $medidas ='1000x680'; }
        ?>
       	<input type="hidden" name="medidas" value="<?php echo $medidas; ?>">

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
							
							<div class="form-group">
                            	<label class="col-sm-2 control-label">Categor&iacute;a<br></label>
			                    <div class="col-sm-4">
		                            <select name="id_tipo" class="form-control m-b">
		                                <option value="">Seleccione</option>
		                                <?php if($item['id_tipo'] == 8) { ?>
		                                	<option value="8" selected>Slides</option>
		                                <?php } else { ?>
		                                <?php foreach($categorias as $categoria) { ?>
		                                <option value="<?php echo $categoria['id'];?>" <?php echo ($categoria['id'] == $item['id_tipo']) ? 'selected' :'';?>><?php echo $categoria['seccion'];?></option>
		                                <?php } } ?>
		                            </select>
			                    </div>
			                    <label class="text-right col-sm-2 control-label"><?php echo ($item['id_tipo'] == 365) ? 'Nombre': 'Titulo';?></label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Nombre del ítem según idioma." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
			                </div>
                            <div class="hr-line-dashed"></div>
                            
			                <!-- Home Slides -->
			                <?php if(($detalle['id_con_secciones'] == 291) && ($item['id_tipo'] == 8)) { ?>
                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Link</label>
			                    <div class="col-sm-3 col-md-3">
                                    <div class="input-group">
                                    	<input type="text" name="subtitulo" class="form-control" value="<?php echo (isset($item['subtitulo'])) ? $item['subtitulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Link en caso de requerir." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
			                    <label class="text-right col-sm-1 control-label">Texto</label>
			                    <div class="col-sm-4 col-md-5">
								    <textarea class="form-control summernote2" name="contenido1" rows="4"><?php echo (isset($item['contenido1'])) ? $item['contenido1']: null; ?></textarea></div>
				                <div class="col-lg-6 p-xxs">
									<?php if((isset($item['titulo'])) && $item['imagen']) { ?>
									<div class="form-group m-b-md pull-left full-width m-t-md">
	                            		<label class="text-right col-sm-4 control-label">Imagen Actual</label>
	                            		<div class="col-sm-8">
	                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$item['imagen']);?>" style="height:auto;width:250px;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
									</div>
									<?php } ?>
									<div class="form-group m-b-md pull-left full-width m-t-md">
	                            		<label class="text-right col-sm-3 control-label">Imagen</label>
						                <div class="col-sm-8">
	                                        <div class="input-group">
		                                       <input type="file" name="imagen" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen asociada, tamaño según selección." title=""> <i class="fa fa-question"></i></button>
		                                    </div>
						                </div>
						            </div>
						        </div>
		                    </div>

			                <!-- Home Banners -->
			                <?php } elseif(($detalle['id_con_secciones'] == 291) && ($item['id_tipo'] == 605)) { ?>
                            <div class="form-group">
			                    <label class="text-right col-sm-1 control-label">Texto</label>
			                    <div class="col-sm-4 col-md-5">
								    <textarea class="form-control summernote2" name="contenido1" rows="4"><?php echo (isset($item['contenido1'])) ? $item['contenido1']: null; ?></textarea></div>
			                    <label class="text-right col-sm-1 control-label">Texto Adicional </label>
			                    <div class="col-sm-4 col-md-5">
								    <textarea class="form-control summernote2" name="contenido2" rows="4"><?php echo (isset($item['contenido2'])) ? $item['contenido2']: null; ?></textarea></div>
		                    </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
			                    <label class="text-right col-sm-1 control-label">Link</label>
			                    <div class="col-sm-4 col-md-5">
                                    <div class="input-group">
                                    	<input type="text" name="subtitulo" class="form-control" value="<?php echo (isset($item['subtitulo'])) ? $item['subtitulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Link en caso de requerir." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
				                <div class="col-lg-6 p-xxs">
									<?php if((isset($item['titulo'])) && $item['imagen']) { ?>
									<div class="form-group m-b-md pull-left full-width m-t-md">
	                            		<label class="text-right col-sm-4 control-label">Imagen Actual</label>
	                            		<div class="col-sm-8">
	                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$item['imagen']);?>" style="height:auto;width:250px;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
									</div>
									<?php } ?>
									<div class="form-group m-b-md pull-left full-width m-t-md">
	                            		<label class="text-right col-sm-3 control-label">Imagen</label>
						                <div class="col-sm-8">
	                                        <div class="input-group">
		                                       <input type="file" name="imagen" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen asociada, tamaño según selección." title=""> <i class="fa fa-question"></i></button>
		                                    </div>
						                </div>
						            </div>
						        </div>
                            </div>

			                <!-- Home Commiments and Offers -->
			                <?php } elseif(($detalle['id_con_secciones'] == 291) && (($item['id_tipo'] == 359) || ($item['id_tipo'] == 362))) { ?>
                            <div class="form-group">
			                    <label class="text-right col-sm-1 control-label">Texto</label>
			                    <div class="col-sm-4 col-md-5">
								    <textarea class="form-control summernote2" name="contenido1" rows="4"><?php echo (isset($item['contenido1'])) ? $item['contenido1']: null; ?></textarea></div>
				                <div class="col-lg-6 p-xxs">
									<?php if((isset($item['titulo'])) && $item['imagen']) { ?>
									<div class="form-group m-b-md pull-left full-width m-t-md">
	                            		<label class="text-right col-sm-4 control-label">Imagen Actual</label>
	                            		<div class="col-sm-8">
	                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$item['imagen']);?>" style="height:auto;width:250px;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
									</div>
									<?php } ?>
									<div class="form-group m-b-md pull-left full-width m-t-md">
	                            		<label class="text-right col-sm-2 control-label">Imagen</label>
						                <div class="col-sm-8">
	                                        <div class="input-group">
		                                       <input type="file" name="imagen" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen asociada, tamaño según selección." title=""> <i class="fa fa-question"></i></button>
		                                    </div>
						                </div>
						            </div>
						        </div>
		                    </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
			                    <label class="text-right col-sm-1 control-label">Link</label>
			                    <div class="col-sm-4 col-md-5">
                                    <div class="input-group">
                                    	<input type="text" name="subtitulo" class="form-control" value="<?php echo (isset($item['subtitulo'])) ? $item['subtitulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Link en caso de requerir." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
                            </div>

			                <!-- Home Testimonials -->
			                <?php } elseif(($detalle['id_con_secciones'] == 291) && ($item['id_tipo'] == 365)) { ?>
                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Ocupación</label>
			                    <div class="col-sm-4">
                                    <div class="input-group">
                                    	<input type="text" name="subtitulo" class="form-control" value="<?php echo (isset($item['subtitulo'])) ? $item['subtitulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Link en caso de requerir." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
			                    <label class="text-right col-sm-2 control-label">Estrellas</label>
			                    <div class="col-sm-4">
                                    <div class="input-group">
                                    	<input type="text" name="texto_adicional" class="form-control" value="<?php echo (isset($item['texto_adicional'])) ? $item['texto_adicional']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Número entero, no obligatorio." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
			                    <label class="text-right col-sm-1 control-label">Texto</label>
			                    <div class="col-sm-4 col-md-5">
								    <textarea class="form-control summernote2" name="contenido1" rows="4"><?php echo (isset($item['contenido1'])) ? $item['contenido1']: null; ?></textarea></div>
				                <div class="col-lg-6 p-xxs">
									<?php if((isset($item['titulo'])) && $item['imagen']) { ?>
									<div class="form-group m-b-md pull-left full-width m-t-md">
	                            		<label class="text-right col-sm-4 control-label">Imagen Actual</label>
	                            		<div class="col-sm-8">
	                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$item['imagen']);?>" style="height:auto;width:250px;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
									</div>
									<?php } ?>
									<div class="form-group m-b-md pull-left full-width m-t-md">
	                            		<label class="text-right col-sm-3 control-label">Imagen</label>
						                <div class="col-sm-8">
	                                        <div class="input-group">
		                                       <input type="file" name="imagen" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen asociada, tamaño según selección." title=""> <i class="fa fa-question"></i></button>
		                                    </div>
						                </div>
						            </div>
						        </div>
                            </div>

			                <!-- Experiencias -->
			                <?php } elseif(($detalle['id_con_secciones'] == 295) || ($detalle['id_con_secciones'] == 299)) { ?>
                            <div class="form-group">
			                    <label class="text-right col-sm-1 control-label">Texto</label>
			                    <div class="col-sm-4 col-md-5">
								    <textarea class="form-control summernote2" name="contenido1" rows="4"><?php echo (isset($item['contenido1'])) ? $item['contenido1']: null; ?></textarea></div>
			                    <label class="text-right col-sm-1 control-label">Texto Adicional</label>
			                    <div class="col-sm-4 col-md-5">
								    <textarea class="form-control summernote2" name="contenido2" rows="4"><?php echo (isset($item['contenido2'])) ? $item['contenido2']: null; ?></textarea></div>
		                    </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
				                <div class="col-lg-6 p-xxs">
									<?php if((isset($item['titulo'])) && $item['imagen']) { ?>
									<div class="form-group m-b-md pull-left full-width m-t-md">
	                            		<label class="text-right col-sm-4 control-label">Imagen Actual</label>
	                            		<div class="col-sm-8">
	                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$item['imagen']);?>" style="height:auto;width:250px;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
									</div>
									<?php } ?>
									<div class="form-group m-b-md pull-left full-width m-t-md">
	                            		<label class="text-right col-sm-3 control-label">Imagen</label>
						                <div class="col-sm-8">
	                                        <div class="input-group">
		                                       <input type="file" name="imagen" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen asociada, tamaño según selección." title=""> <i class="fa fa-question"></i></button>
		                                    </div>
						                </div>
						            </div>
						        </div>
                            </div>
                            <div class="hr-line-dashed"></div>

			                <!-- Destinos y Blog -->
			                <?php } elseif(($detalle['padre_seccion'] == 297) || ($detalle['id_con_secciones'] == 303)) { ?>
                            <div class="form-group">
                            	<label class="col-sm-2 control-label">Galería</label>
			                    <div class="col-sm-3"><?php echo form_dropdown('media_proyecto', $media_proyectos, (isset($item['id_proyecto'])) ? $item['id_proyecto'] : null, 'class="form-control m-b"'); ?></div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                            	<?php if($detalle['id_con_secciones'] != 303) { ?>
                            	<input type="hidden" name="medidas2" value="1540x230">
                            	<input type="hidden" name="id_imagen_tipo2" value="12">
				                <div class="col-lg-6 p-xxs">
									<?php if((isset($item['titulo'])) && $bgencabezado['imagen']) { ?>
									<div class="form-group m-b-md pull-left full-width m-t-md">
	                            		<label class="text-right col-sm-4 control-label">Imagen Encabezado</label>
	                            		<div class="col-sm-8">
	                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$bgencabezado['imagen']);?>" style="height:auto;width:250px;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
									</div>
									<?php } ?>
									<div class="form-group m-b-md pull-left full-width m-t-md">
	                            		<label class="text-right col-sm-3 control-label">Imagen</label>
						                <div class="col-sm-8">
	                                        <div class="input-group">
		                                       <input type="file" name="imagen2" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen del encabezado, medidas 1540x230 píxeles." title=""> <i class="fa fa-question"></i></button>
		                                    </div>
						                </div>
						            </div>
						        </div>
                            	<?php } ?>
						        <div class="col-lg-6 p-xxs">
									<?php if((isset($item['titulo'])) && $item['imagen']) { ?>
									<div class="form-group m-b-md pull-left full-width m-t-md">
	                            		<label class="text-right col-sm-4 control-label">Imagen Actual</label>
	                            		<div class="col-sm-8">
	                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$item['imagen']);?>" style="height:auto;width:250px;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
									</div>
									<?php } ?>
									<div class="form-group m-b-md pull-left full-width m-t-md">
	                            		<label class="text-right col-sm-3 control-label">Imagen</label>
						                <div class="col-sm-8">
	                                        <div class="input-group">
		                                       <input type="file" name="imagen" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen asociada, tamaño según selección." title=""> <i class="fa fa-question"></i></button>
		                                    </div>
						                </div>
						            </div>
						        </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
			                    <label class="text-right col-sm-1 control-label">Texto</label>
			                    <div class="col-sm-4 col-md-5">
								    <textarea class="form-control summernote2" name="contenido1" rows="4"><?php echo (isset($item['contenido1'])) ? $item['contenido1']: null; ?></textarea></div>
			                    <label class="text-right col-sm-1 control-label">Texto Adicional</label>
			                    <div class="col-sm-4 col-md-5">
								    <textarea class="form-control summernote2" name="contenido2" rows="4"><?php echo (isset($item['contenido2'])) ? $item['contenido2']: null; ?></textarea></div>
		                    </div>
                            <div class="hr-line-dashed"></div>
		                    <?php } ?>

                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Orden</label>
			                    <div class="col-sm-4">
                                    <div class="input-group">
                                    	<input type="text" name="orden" class="form-control" value="<?php echo (isset($item['orden'])) ? $item['orden']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en el que se mostrará el ítem." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
	                            <label class="col-sm-2 control-label text-right">Estado</label>
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
                                	<input type="hidden" name="id_imagen_tipo" value="13">
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
        height: 200,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link']],
          ['misc', ['codeview']]
        ]
});
$('[data-toggle="tooltip"]').tooltip(); 
</script>
      
      
                                       