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
							

							<div class="form-group">
                            	<label class="col-sm-2 control-label">Categor&iacute;a<br></label>
			                    <div class="col-sm-3">
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

			                </div>
                            <div class="hr-line-dashed"></div>
                            
                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Titulo</label>
			                    <div class="col-sm-3 col-md-3">
                                    <div class="input-group">
                                    	<input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Nombre del ítem según idioma." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>

			                    <label class="text-right col-sm-2 control-label">Link</label>
			                    <div class="col-sm-4 col-md-4">
                                    <div class="input-group">
                                    	<input type="text" name="subtitulo" class="form-control" value="<?php echo (isset($item['subtitulo'])) ? $item['subtitulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Link en caso de requerir." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
			                    <label class="text-right col-sm-2 control-label">Texto</label>
			                    <div class="col-sm-4 col-md-8">
								    <textarea class="form-control summernote2" name="contenido1" rows="4"><?php echo (isset($item['contenido1'])) ? $item['contenido1']: null; ?></textarea></div>
		                    </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group">
			                    <label class="control-label col-sm-2 pull-left">Tamaño Imagen</label>
			                    <div class="col-sm-3 col-md-3">
                                    <div class="input-group">
                                    	<select name="medidas" class="form-control m-b">
		                                <option value="">Sin imagen</option>
		                                <option value="1920x600" <?php echo($item['id_tipo'] == 8) ? 'selected':'';?>>1920x600 (slides)</option>
		                                <option value="120x120" <?php echo($item['id_tipo'] == 283) ? 'selected':'';?>>120x120 (íconos)</option>
		                                <option value="300x240" <?php echo($item['id_tipo'] == 275) ? 'selected':'';?>>300x240 (servicios)</option>
		                                <option value="300x300" <?php echo($item['id_tipo'] == 277) ? 'selected':'';?>>300x300 (clientes)</option>
		                                <option value="300x300" <?php echo($item['id_tipo'] == 279) ? 'selected':'';?>>300x300 (clientes)</option>
		                                <option value="250x250" <?php echo($item['id_tipo'] == 285 || $item['id_tipo'] == 287) ? 'selected':'';?>>250x250 (equipo/quiénes somos)</option>
		                            </select>
		                            <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Tamaño de la imagen, campo obligatorio." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>

			                    </div>

			                    <!-- Imagenes Generales -->
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
			                    <label class="text-right col-sm-2 control-label">Orden</label>
			                    <div class="col-sm-2">
                                    <div class="input-group">
                                    	<input type="text" name="orden" class="form-control" value="<?php echo (isset($item['orden'])) ? $item['orden']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en el que se mostrará el ítem." title=""> <i class="fa fa-question"></i></button></span>
                                    </div>
			                    </div>
	                            <label class="col-sm-3 control-label text-right">Estado</label>
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
        height: 200,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['insert', ['link']]
        ]
});
$('[data-toggle="tooltip"]').tooltip(); 
</script>
      
      
                                       