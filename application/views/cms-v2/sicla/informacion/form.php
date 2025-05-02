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
                <h2>Información</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('cms-v2/informacion');?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('cms-v2/informacion');?>">Información</a>
                    </li>
                    <li class="active">
                        <strong><?php echo (empty($detalle['id'])) ? 'Crear nuevo' : 'Modificar'; ?></strong>
                    </li>
                </ol>
            </div>

            <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
			<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
			<input type="hidden" name="id_tipo_imagen2" value="19">
			<input type="hidden" name="destacado" value="0">
			<input type="hidden" name="destacado_slide" value="0">
			<input type="hidden" name="id_con_secciones" value="891">
			<input type="hidden" name="medidas_imagen2" value="1200x800">
			<input type="hidden" name="medidas_miniatura_imagen2" value="480x320">
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
						                    <label class="text-right col-sm-1 control-label">Título</label>
											<div class="col-sm-5">
		                                        <div class="input-group">
		                                        	<input type="text" name="imagen_adicional" class="form-control" value='<?php echo (isset($detalle['imagen_adicional'])) ? $detalle['imagen_adicional']: null; ?>'><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de la información, no que se mostrará en el sitio." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
											</div>

						                    <label class="text-right col-sm-1 control-label">Fecha</label>
											<div class="col-sm-5">
				                                <div class="input-group date dia">
				                                	<span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" name="color" value="<?php if(isset($detalle['color'])) { $date = date_create($detalle['color']); echo date_format($date, 'd-m-Y'); } ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Fecha de la noticia, sino se completa se toma la fecha de alta de la noticia para mostrar en la web." title=""> <i class="fa fa-question"></i></button></span>
				                                </div>
											</div>
										</div>
					                </div>
					                <hr class="hr-line-dashed pull-left full-width">

					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
				                            <label class="col-sm-1 control-label text-right">Estado</label>
				                            <div class="col-sm-3">
					                            <div class="radio radio-inline"><input type="radio" name="estado" value="3" <?php if (isset($detalle['estado']) && $detalle['estado'] == '3') echo 'checked="checked"'; ?>> <label> Activo </label></div>
					                            <div class="radio radio-inline"><input type="radio" name="estado" value="1" <?php if (isset($detalle['estado']) && $detalle['estado'] == '1') echo 'checked="checked"'; ?>><label> Inactivo </label></div>
					                            <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Determina si el contenido se mostrará en el sitio." title=""> <i class="fa fa-question"></i></button>
					                         </div>

						                    <label class="text-right col-sm-1 control-label">Orden</label>
						                    <div class="col-sm-1">
		                                        <div class="input-group">
			                                        <input type="text" name="orden" class="form-control" value="<?php echo (isset($detalle['orden'])) ? $detalle['orden']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en el que se mostrará. Se puede dejar vacío y luego acomodar el orden accediendo a Ordenar desde el listado de información." title=""> <i class="fa fa-question"></i></button>
			                                    </div>
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
								if(!empty($detalle['id']))
								{
									$CI =& get_instance();
									$CI->load->model("Informacion_model");
									$item = $this->Informacion_model->getContenidoDetalleIdioma($detalle['id'], $idioma['extension']);
									if($item['id_item'])
									{
										$imagen2 = $this->Informacion_model->getMedia($detalle['id'], $idioma['extension'], 14);
									}
								}
							?>
	                            <div class="panel-body">
								 <div class="row">
					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <label class="text-right col-sm-2 control-label">T&iacute;tulo</label>
											<div class="col-sm-4">
		                                        <div class="input-group">
		                                        	<input type="text" name="titulo_<?php echo $idioma['extension'];?>" class="form-control" value='<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>'><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de la información que se mostrará en el sitio." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
											</div>

						                    <label class="text-right col-sm-2 control-label">Nombre (url)</label>
						                    <div class="col-sm-4">
		                                        <div class="input-group">
		                                        	<input type="text" name="url_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['url'])) ? $item['url']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Url de la noticia, si se deja vacía toma el título sanitizado como url." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
		                                     </div>
				                 		</div>
					                </div>
	
					                <div class="col-lg-12 p-xxs">
										<?php if((isset($item['titulo'])) && $imagen2['archivo']) { ?>
										<div class="form-group m-b-md pull-left full-width m-t-md">
		                            		<label class="text-right col-sm-2 control-label">Imagen Actual</label>
		                            		<div class="col-sm-4">
		                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$imagen2['archivo']);?>" style="height:auto;width:250px;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
										</div>
										<?php } ?>
										<div class="form-group m-b-md pull-left full-width m-t-md">
		                            		<label class="text-right col-sm-2 control-label">Imagen Miniatura/Detalle</label>
							                <div class="col-sm-4">
		                                        <div class="input-group">
			                                       <input type="file" name="imagen2_<?php echo $idioma['extension'];?>" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Debe tener 1200x800 píxeles o proporcionales. Obligatoria, de esta imagen se generan las miniaturas de listado y la imagen detalle de la nota. Extensión .jpg, .png o .gif." title=""> <i class="fa fa-question"></i></button>
			                                    </div>
							                </div>

						                    <label class="text-right col-sm-2 control-label">Video</label>
						                    <div class="col-sm-4">
		                                        <div class="input-group">
		                                        	<input type="text" name="subtitulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['subtitulo'])) ? $item['subtitulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Sólo código del video, por ej: 86oTbLsP2OM?si=Li76roUKPCaElAl3 (YouTube) o 783839292?h=843dfd1cd8 (Vimeo)." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
		                                     </div>
										</div>
									</div>

					                <div class="col-lg-12 p-xxs">
										<div class="form-group m-b-md pull-left full-width m-t-md">
											<div class="col-lg-12 p-xxs">
												<div class="ibox-title bg-muted"><h5>Contenido Total</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Si no se redacta directamente en el siguiente campo, se recomienda pegar el texto sin formato y aplicarle el formato deseado en cada caso." title=""> <i class="fa fa-question"></i></button></div>
												<textarea class="form-control summernote" name="contenido2_<?php echo $idioma['extension'];?>" rows="5"><?php echo(isset($item['contenido2'])) ? $item['contenido2']: null?></textarea>
											</div>
										</div>
					                </div>

					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">SEO</h2>
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <div class="col-md-4">
						                    	<div class="ibox-title" style="background:#F5EFEF;"><h5>Título</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de la página, deberá contener no más de 70 caracteres." title=""> <i class="fa fa-question"></i></button></div>
							                    <div class="ibox-content no-padding"><textarea class="form-control" name="seo_titulo_<?php echo $idioma['extension'];?>" rows="5"><?php echo(isset($item['seo_titulo'])) ? $item['seo_titulo']: null?></textarea></div>
						                    </div>
						                    <div class="col-md-4">
						                    	<div class="ibox-title" style="background:#F5EFEF;"><h5>Descripci&oacute;n</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Descripción de la página, entre 70 y 250 caracteres (incluyendo espacios y signos de puntuación)." title=""> <i class="fa fa-question"></i></button></div>
							                    <div class="ibox-content no-padding"><textarea class="form-control" name="seo_descripcion_<?php echo $idioma['extension'];?>" rows="5"><?php echo(isset($item['seo_descripcion'])) ? $item['seo_descripcion']: null?></textarea></div>
						                    </div>
						                    <div class="col-md-4">
						                    	<div class="ibox-title" style="background:#F5EFEF;"><h5>Keywords</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Si bien están prácticamente en desuso, son palabras o frases que se asocian al contenido de la página. Máximo 10, separadas por coma y no más de 600 caracteres (incluyendo espacios y signos de puntuación)." title=""> <i class="fa fa-question"></i></button></div>
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
<script src="<?php echo base_url('assets/js/plugins/datapicker/bootstrap-datepicker.js'); ?>"></script>

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