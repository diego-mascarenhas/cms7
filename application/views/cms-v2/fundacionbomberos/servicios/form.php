<style>
.note-editor.note-frame { border:0;}
.note-editable .row {margin: 0px;}
.note-editable .row div {border: 1px dotted;}
.tooltip-inner {max-width: 250px;width: 250px;}
.control-label, .input-group { margin-top:10px;}
.hr-line-dashed {margin: 10px 0 20px;}
.box-items { display: flex; }
.contact-box  { display: flex; flex-direction:column; justify-content:space-between; width:100%;}
@media(max-width:768px){
.control-label { margin-top:18px;}	
.input-group { margin-top:0;}	
}

</style>
         <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-8 col-sm-8 col-xs-8">
                <h2>Servicios y Beneficios</h2>
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url('cms-v2/paginas');?>">Home</a></li>
                    <li><a href="<?php echo base_url('cms-v2/servicios');?>">Servicios y Beneficios</a></li>
                    <li class="active"><strong><?php echo (empty($detalle['id'])) ? 'Crear nuevo' : 'Modificar'; ?></strong></li>
                </ol>
            </div>

            <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
			<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
			<input type="hidden" name="id_tipo" value="32">
			<input type="hidden" name="medidas1" value="300x180">
			<input type="hidden" name="destacado_es" value="0">
			
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
	                        <!-- Item Generales -->
	                        <div id="tab-0" class="tab-pane active">
	                            <div class="panel-body">
								 <div class="row">
					                <div class="col-lg-12 p-xxs">
										<div class="form-group pull-left full-width">
						                    <label class="col-xs-12 col-sm-1 col-md-1 control-label">Título</label>
				                            <div class="col-xs-12 col-sm-5 col-md-4">
		                                        <div class="input-group">
		                                        	<input type="text" name="titulo" class="form-control" value="<?php echo (isset($detalle['titulo'])) ? $detalle['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título general, no se mostrará en el sitio." title=""> <i class="fa fa-question"></i></button></span></div>
		                                    </div>
						                    <label class="text-right col-sm-1 control-label">Categoría</label>
						                    <div class="col-sm-3"><?php echo form_dropdown('id_categoria', $categorias, (isset($detalle['id_categoria'])) ? $detalle['id_categoria'] : null, 'class="required form-control m-b"'); ?></div>
										</div>
				                            
										<div class="form-group pull-left full-width">
						                    <label class="col-xs-12 col-sm-1 col-md-1 control-label">Estado</label>
				                            <div class="col-xs-12 col-sm-5 col-md-4">
		                                        <div class="input-group">
						                            <select name="estado" class="required form-control m-b">
							                            <option value="3"<?php if (isset($detalle['estado']) && $detalle['estado'] == '3') echo ' selected'; ?>>Activo</option>
							                            <option value="1"<?php if (isset($detalle['estado']) && $detalle['estado'] == '1') echo ' selected'; ?>>Inactivo</option>
						                            </select>
						                            <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Determina si el contenido se mostrará en el sitio." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
					                         </div>
						                    <label class="text-right col-sm-1 control-label">Orden</label>
				                            <div class="col-xs-12 col-sm-5 col-md-3">
		                                        <div class="input-group">
			                                        <input type="text" name="orden" class="form-control" value="<?php echo (isset($detalle['orden'])) ? $detalle['orden']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en el que se mostrará. Se puede dejar vacío y luego acomodar el orden accediendo a Ordenar desde el listado general." title=""> <i class="fa fa-question"></i></button>
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
									$CI->load->model("Servicios_model");
									$parametros['id'] = $detalle['id'];
									$parametros['idioma'] = $idioma['extension'];
									$item = $this->Servicios_model->getServicioDetalleIdioma($parametros);
									$parametros['id_tipo'] = 32;
									$imagen = $this->Servicios_model->getMedia($parametros);
									$archivo = $this->Servicios_model->getArchivo($parametros);
								}
							?>
	                            <div class="panel-body">
								 <div class="row">

					                <div class="col-lg-12 p-xxs">
										<div class="form-group pull-left full-width">
						                    <label class="text-right col-sm-1 control-label">Título</label>
											<div class="col-sm-5">
		                                        <div class="input-group">
		                                        	<input type="text" name="titulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del ítem que se mostrará en el sitio." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
											</div>
		                                    <label class="col-sm-2 control-label text-right">Galería</label>
                                         	<div class="col-sm-4">
                                         		<div class="input-group">
                                         		<?php echo form_dropdown('id_proyecto_'.$idioma['extension'], $media_proyectos, (isset($item['id_proyecto'])) ? $item['id_proyecto'] : null, 'class="form-control m-b"'); ?><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Sólo para Servicios, galería de imágenes que se mostrará al lado del texto del ítem." title=""> <i class="fa fa-question"></i></button></span></div>
                                         	</div>
										</div>
										<div class="form-group pull-left full-width">
		                            		<label class="text-right col-sm-1 control-label">Imagen</label>
							                <?php if(!empty($imagen['archivo'])) { ?>
							                <div class="col-sm-1">
								                <img src="<?php echo base_url('/multimedia/thumbs/'.$imagen['archivo']);?>" alt="<?php echo $item['titulo'];?>" width="70">
							                </div>
							                <?php } ?>
							                <div class="<?php echo (!empty($imagen['archivo'])) ? "col-sm-4" : "col-sm-5"; ?>">
		                                        <div class="input-group">
			                                       <input type="file" name="imagen1_<?php echo $idioma['extension'];?>" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="A modo ilustrativo para Servicios y a publicar en la web para el caso de Beneficios. Imagen jpg, png o gif de 300x180 píxeles o proporcionales mayores." title=""> <i class="fa fa-question"></i></button>
			                                    </div>
							                </div>

		                            		<label class="text-right col-sm-2 control-label">PDF</label>
							                <?php if(!empty($archivo['archivo'])) { ?>
							                <div class="col-sm-1">
								                <?php echo $archivo['nombre'];?>
							                </div>
							                <?php } ?>
							                <div class="<?php echo (!empty($archivo['archivo'])) ? "col-sm-4" : "col-sm-4"; ?>">
		                                        <div class="input-group">
			                                       <input type="file" name="archivo_<?php echo $idioma['extension'];?>" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Archivo PDF, para el caso de beneficios será el que incluya las bases y condiciones." title=""> <i class="fa fa-question"></i></button>
			                                    </div>
							                </div>
										</div>
					                </div>

					                <hr class="hr-line-dashed pull-left full-width">
					                <div class="col-lg-12 p-xxs">
										<div class="form-group pull-left full-width">
											<div class="col-lg-12 p-xxs">
												<div class="ibox-title bg-muted"><h5>Texto</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Contenido del ítem." title=""> <i class="fa fa-question"></i></button></div>
												<textarea class="form-control summernote2" name="contenido1_<?php echo $idioma['extension'];?>" rows="10"><?php echo(isset($item['contenido1'])) ? $item['contenido1']: null?></textarea>
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