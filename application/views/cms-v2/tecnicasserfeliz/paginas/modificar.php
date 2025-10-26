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
        </div>
        
       <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
	   <input type="hidden" name="id_con_secciones" value="<?php echo $detalle['id_con_secciones']; ?>">
        <?php 
	    	switch($item['id_tipo'])
	    	{
		    	case '8': $medidas = '1600x855'; $id_imagen_tipo = 18; $alto = 120; break; /* Slide */
		    	case '511': $medidas = '120x120'; $id_imagen_tipo = 13; $alto = 180; break; /* íconos Cursos */
		    	case '514': $medidas = '200x80'; $id_imagen_tipo = 13; $alto = 100; break; /* librerías */
		    	case '279': $medidas = '410x550'; $id_imagen_tipo = 13; $alto = 70; break; /* slide Libro */
		    	default: $medidas = '870x400'; $id_imagen_tipo = 13; $alto = 150; break; /* aplicaciones - beneficios */
	    	}
    	?>
    	<?php if (isset($medidas)) { echo '<input type="hidden" name="medidas" value="'.$medidas.'">'; } ?>
    	<?php if (isset($id_imagen_tipo)) { echo '<input type="hidden" name="id_imagen_tipo" value="'.$id_imagen_tipo.'">'; } ?>

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
                            	<label class="col-md-2 control-label">Categor&iacute;a<br></label>
			                    <div class="col-md-3">
		                            <select name="id_tipo" class="form-control m-b">
		                                <option value="">Seleccione</option>
		                                <?php if($item['id_tipo'] == 8) { ?>
		                                <option value="8" selected>Slides</option>
		                                <?php } else { foreach($categorias as $categoria) { ?>
		                                <option value="<?php echo $categoria['id'];?>" <?php echo ($categoria['id'] == $item['id_tipo']) ? 'selected' :'';?>><?php echo $categoria['seccion'];?></option>
		                                <?php } } ?>
		                            </select>
			                    </div>
	                            <label class="col-md-3 control-label text-right">Estado</label>
	                            <div class="col-md-4">
		                            <div class="radio radio-inline"><input type="radio" name="estado" value="3" <?php if (isset($item['estado']) && $item['estado'] == '3') echo 'checked="checked"'; ?>> <label> Activo </label></div>
		                            <div class="radio radio-inline"><input type="radio" name="estado" value="1" <?php if (isset($item['estado']) && $item['estado'] == '1') echo 'checked="checked"'; ?>><label> Inactivo </label></div>
		                            <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Determina si el ítem se mostrará en el sitio." title=""> <i class="fa fa-question"></i></button>
		                         </div>
			                </div>
                            <div class="hr-line-dashed"></div>
                            
			                <?php if($item['id_tipo'] == 8 || $item['id_tipo'] == 511 || $item['id_tipo'] == 514) { ?>
                            <div class="form-group">
			                    <label class="text-right col-md-2 control-label">Titulo</label>
			                    <div class="col-md-3 col-md-3">
                                    <div class="input-group">
                                    	<input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Titulo del contenido." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>

			                    <label class="text-right col-md-2 control-label">Link</label>
			                    <div class="col-md-4 col-md-4">
                                    <div class="input-group">
                                    	<?php if($item['id_tipo'] == 8) { ?>
                                    	<input type="text" name="subtitulo" class="form-control" value="<?php echo (isset($item['subtitulo'])) ? $item['subtitulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Link en caso de requerir." title=""> <i class="fa fa-question"></i></button></span>
                                    	<?php } elseif($item['id_tipo'] == 511 || $item['id_tipo'] == 514) { ?>
	                                    <input type="text" name="contenido3" class="form-control" value="<?php echo (isset($item['contenido3'])) ? $item['contenido3']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Link de compra del curso." title=""> <i class="fa fa-question"></i></button></span>
                                    	<?php }  ?>
                                    </div>
			                    </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
			                    <label class="text-right col-md-2 control-label">Orden</label>
			                    <div class="col-md-3">
                                    <div class="input-group">
                                    	<input type="text" name="orden" class="form-control" value="<?php echo (isset($item['orden'])) ? $item['orden']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en el que se mostrará el ítem." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
			                    <div class="col-md-1"></div>
                            </div>
	                        
	                        <div class="hr-line-dashed"></div>
	                            <div class="form-group">
	                        <?php if($item['id_tipo'] == 511) { ?>
				                    <div class="col-md-5">
										<div class="ibox-title no-borders"><h5>Texto</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Contenido de la sección." title=""> <i class="fa fa-question"></i></button></div>
										<div class="ibox-content no-padding">
										    <textarea class="form-control summernote2" name="contenido1"><?php echo(isset($item['contenido1'])) ? $item['contenido1']: null?></textarea></div>
				                    </div>
			                        <div class="col-md-1"></div>
			                        <?php } ?>
					                <div class="col-md-5 p-xxs">
										<?php if((isset($item['titulo'])) && $item['imagen']) { ?>
										<div class="ibox-title no-borders"><h5>Imagen Actual</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen asociada al conenido. Tamaño <?php echo $medidas;?> píxeles." title=""> <i class="fa fa-question"></i></button></div>
										<div class="ibox-content no-padding no-borders">
				                            <div class="col-md-12">
		                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$item['imagen']);?>" style="height:<?php echo $alto;?>px;width:auto;float: left;padding-bottom: 24px;padding-right: 25px;"/>
		                            		</div>
		                            	</div>
				                         <?php }  ?>
			                            <div class="col-md-12 no-padding">
                                        	<div class="input-group">
				                            	<input type="file" name="imagen" class="form-control"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen asociada al conenido. Tamaño <?php echo $medidas;?> píxeles." title=""> <i class="fa fa-question"></i></button>
				                            </div>
			                            </div>
									</div>
	                            </div>
	                           <div class="hr-line-dashed"></div>
	                         <?php if($item['id_tipo'] == 511) { ?>	                        

				            <?php } } elseif($item['id_tipo'] == 527) { ?>
                            <div class="form-group">
			                    <label class="text-right col-md-2 control-label">Titulo</label>
			                    <div class="col-md-3 col-md-3">
                                    <div class="input-group">
                                    	<input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Titulo del contenido." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>

			                    <label class="text-right col-md-2 control-label">Link</label>
			                    <div class="col-md-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="subtitulo" class="form-control" value="<?php echo (isset($item['subtitulo'])) ? $item['subtitulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Link en caso de requerir." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
                            </div>
                            <div class="hr-line-dashed"></div>

	                         <div class="form-group">
				                <div class="col-lg-12 p-xxs">
				                    <div class="col-md-6 no-paddings">
										<div class="ibox-title no-borders p-xxs"><h5>Texto</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Contenido de la sección." title=""> <i class="fa fa-question"></i></button></div>
										<div class="ibox-content no-padding">
										    <textarea class="form-control summernote2" name="contenido1"><?php echo(isset($item['contenido1'])) ? $item['contenido1']: null?></textarea></div>
				                    </div>
				                    <label class="text-right col-md-1 control-label m-l-sm">Orden</label>
				                    <div class="col-md-4">
	                                    <div class="input-group">
	                                    	<input type="text" name="orden" class="form-control" value="<?php echo (isset($item['orden'])) ? $item['orden']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en el que se mostrará el ítem." title=""> <i class="fa fa-question"></i></button></span>
	                                    </div>
				                    </div>
	                            </div>
				            </div>
				            <div class="hr-line-dashed"></div>
				            <?php } elseif($item['id_tipo'] == 490 || $item['id_tipo'] == 493) { ?>
                            <div class="form-group">
			                    <label class="text-right col-md-2 control-label">Titulo</label>
			                    <div class="col-md-3 col-md-3">
                                    <div class="input-group">
                                    	<input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Titulo del contenido." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>

			                    <label class="text-right col-md-2 control-label">Link</label>
			                    <div class="col-md-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="subtitulo" class="form-control" value="<?php echo (isset($item['subtitulo'])) ? $item['subtitulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Link en caso de requerir." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
                            </div>
                            <div class="hr-line-dashed"></div>

	                         <div class="form-group">
				                <div class="col-lg-12 p-xxs">
				                    <div class="col-md-6 no-paddings">
										<div class="ibox-title no-borders p-xxs"><h5>Texto</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Contenido de la sección." title=""> <i class="fa fa-question"></i></button></div>
										<div class="ibox-content no-padding">
										    <textarea class="form-control summernote2" name="contenido1"><?php echo(isset($item['contenido1'])) ? $item['contenido1']: null?></textarea></div>
				                    </div>
					                <div class="col-md-5 m-l-md">
										<?php if((isset($item['titulo'])) && $item['imagen']) { ?>
										<div class="ibox-title no-borders"><h5>Imagen Actual</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen asociada al conenido. Tamaño <?php echo $medidas;?> píxeles." title=""> <i class="fa fa-question"></i></button></div>
										<div class="ibox-content no-padding no-borders">
				                            <div class="col-md-12">
		                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$item['imagen']);?>" style="height:<?php echo $alto;?>px;width:auto;float: left;padding-bottom: 24px;padding-right: 25px;"/>
		                            		</div>
		                            	</div>
				                         <?php }  ?>
			                            <div class="col-md-12 no-padding">
                                        	<div class="input-group">
				                            	<input type="file" name="imagen" class="form-control"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen asociada al conenido. Tamaño <?php echo $medidas;?> píxeles." title=""> <i class="fa fa-question"></i></button>
				                            </div>
			                            </div>
									</div>
				                    
				                </div>
	                         </div>
				            <div class="hr-line-dashed"></div>
				                
	                         <div class="form-group">
				                <div class="col-lg-12 p-xxs">
				                    <div class="col-md-6 no-paddings">
										<div class="ibox-title no-borders p-xxs"><h5>Texto Adicional</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Contenido de la sección." title=""> <i class="fa fa-question"></i></button></div>
										<div class="ibox-content no-padding">
										    <textarea class="form-control summernote2" name="contenido2" rows="4"><?php echo (isset($item['contenido2'])) ? $item['contenido2']: null; ?></textarea></div>
				                    </div>
				                    <label class="text-right col-md-1 control-label m-l-sm m-t-sm">Orden</label>
				                    <div class="col-md-4 m-t-sm">
	                                    <div class="input-group">
	                                    	<input type="text" name="orden" class="form-control" value="<?php echo (isset($item['orden'])) ? $item['orden']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en el que se mostrará el ítem." title=""> <i class="fa fa-question"></i></button></span>
	                                    </div>
				                    </div>
				                </div>
                            </div>

				            <?php } else { ?>
                            <div class="form-group">
			                    <label class="text-right col-md-2 control-label">Titulo</label>
			                    <div class="col-md-3">
                                    <div class="input-group">
                                    	<input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Titulo del contenido." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>

			                    <label class="text-right col-md-2 control-label">Url</label>
			                    <div class="col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="subtitulo" class="form-control" value="<?php echo (isset($item['subtitulo'])) ? $item['subtitulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Link en caso de requerir." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
                            </div>
                            <div class="hr-line-dashed"></div>

	                            <div class="form-group">
				                    <div class="col-md-6">
										<div class="ibox-title no-borders"><h5>Texto</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Contenido de la sección." title=""> <i class="fa fa-question"></i></button></div>
										<div class="ibox-content no-padding">
										    <textarea class="form-control summernote2" name="contenido1"><?php echo(isset($item['contenido1'])) ? $item['contenido1']: null?></textarea></div>
				                    </div>
				                    <label class="text-right col-md-2 control-label">Texto Adicional</label>
				                    <div class="col-sm-4 col-md-8">
									    <textarea class="form-control summernote2" name="contenido2" rows="4"><?php echo (isset($item['contenido2'])) ? $item['contenido2']: null; ?></textarea></div>
			                    </div>
	                            <div class="hr-line-dashed"></div>
	
	                            <div class="form-group">
				                    <label class="text-right col-md-2 control-label">Link</label>
				                    <div class="col-md-4">
	                                    <div class="input-group">
		                                    <input type="text" name="contenido3" class="form-control" value="<?php echo (isset($item['contenido3'])) ? $item['contenido3']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Link en caso de requerir." title=""> <i class="fa fa-question"></i></button></span>
	                                    </div>
				                    </div>
	
				                    <label class="text-right col-md-2 control-label">Código Video</label>
				                    <div class="col-md-4">
	                                    <div class="input-group">
	                                    <input type="text" name="texto_adicional" class="form-control" value="<?php echo (isset($item['texto_adicional'])) ? $item['texto_adicional']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Código de video de YouTube en caso de requerir." title=""> <i class="fa fa-question"></i></button></span>
	                                    </div>
				                    </div>
			                    </div>
	                            <div class="hr-line-dashed"></div>
	
	                            <div class="form-group">
					                <div class="col-md-6 p-xxs">
										<?php if((isset($item['titulo'])) && $item['imagen']) { ?>
											<div class="form-group m-b-md pull-left full-width m-t-md">
			                            		<label class="text-right col-md-4 control-label">Imagen Actual</label>
			                            		<div class="col-md-8">
			                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$item['imagen']);?>" style="height:<?php echo $alto;?>px;width:auto;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
											</div>
										<?php } ?>
											<div class="form-group m-b-md pull-left full-width m-t-md">
		                            		<label class="text-right col-md-3 control-label">Imagen</label>
							                <div class="col-md-8">
		                                        <div class="input-group">
			                                       <input type="file" name="imagen" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen asociada, tamaño según selección." title=""> <i class="fa fa-question"></i></button>
			                                    </div>
							                </div>
										</div>
									</div>
	                            </div>
	                           <div class="hr-line-dashed"></div>

				                <?php } if($item['id_tipo'] == 490 || $item['id_tipo'] == 493) { ?>
				                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">SEO</h2>
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <div class="col-md-4">
						                    	<div class="ibox-title no-borders"><h5>Título</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de la página." title=""> <i class="fa fa-question"></i></button></div>
							                    <div class="ibox-content no-padding"><textarea class="form-control" name="seo_titulo" rows="5"><?php echo(isset($item['seo_titulo'])) ? $item['seo_titulo']: null?></textarea></div>
						                    </div>
						                    <div class="col-md-4">
						                    	<div class="ibox-title no-borders"><h5>Descripci&oacute;n</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Descripción de la página." title=""> <i class="fa fa-question"></i></button></div>
							                    <div class="ibox-content no-padding"><textarea class="form-control" name="seo_descripcion" rows="5"><?php echo(isset($item['seo_descripcion'])) ? $item['seo_descripcion']: null?></textarea></div>
						                    </div>
						                    <div class="col-md-4">
						                    	<div class="ibox-title no-borders"><h5>Keywords</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Keywords de la página." title=""> <i class="fa fa-question"></i></button></div>
							                    <div class="ibox-content no-padding"><textarea class="form-control" name="seo_keywords" rows="5"><?php echo(isset($item['seo_keywords'])) ? $item['seo_keywords']: null?></textarea></div>
						                    </div>
										</div>
				                 	</div>
				               <?php } ?>

                            <div class="form-group">
                                <div class="col-md-4 col-md-offset-2">
                                	<input type="hidden" name="idioma" value="<?php echo (!empty($item['idioma'])) ? $item['idioma'] : null; ?>">
                                	<input type="hidden" name="id_imagen_tipo" value="25">
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
        height: <?php echo $alto;?>,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['color', ['color']],
          ['insert', ['link']]
        ]
});
$('[data-toggle="tooltip"]').tooltip(); 
</script>
      
      
                                       