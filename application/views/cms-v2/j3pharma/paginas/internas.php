<style>
.note-editor.note-frame { border:0;}
.note-editable .row {margin: 0px;}
.note-editable .row div {border: 1px dotted;}
.tooltip-inner {max-width: 250px;width: 250px;}
</style>

       <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-xs-8 col-sm-8 col-lg-8">
                <h2>Sitio web Páginas</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="/cms-v2/paginas">Home</a>
                    </li>
                    <li>
                        <a href="/cms-v2/paginas">Páginas</a>
                    </li>
                    <li class="active">
                        <strong><?php echo (empty($detalle['id'])) ? 'Crear nuevo' : 'Modificar'; ?></strong>
                    </li>
                </ol>
            </div>

            <form action="<?php echo base_url('cms-v2/paginas/modificar/'.$detalle['id']); ?>/" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">

			<input type="hidden" name="id" value="<?php echo $detalle['id']; ?>">
			<input type="hidden" name="id_con_secciones" value="<?php echo $detalle['id_con_secciones']; ?>">
			<input type="hidden" name="id_imagen_tipo" value="13">
			<input type="hidden" name="medidas" value="1920x600">
			
            <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
                <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
                <button class="btn btn-primary" type="submit">Guardar cambios</button>
            </div>
        </div>


        <div class="wrapper wrapper-content animated fadeInRight">
            <!-- Titulo Mensajes -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox-title ibox-title-custom"><h5>Subir contenido para <a><?php echo $detalle['seccion']; ?></a></h5></div>
                </div>
                <?php if (validation_errors()) : ?>
				<div class="col-md-12">
					<div class="alert alert-danger" role="alert"><?php echo validation_errors(); ?></div>
				</div>
				<?php endif; ?>
				<?php if (isset($error)) : ?>
				<div class="col-md-12">
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
                        	<?php foreach($idiomas as $idioma) { ?>
                            <li class="<?php if($idioma['orden'] == 1) { echo 'active';};?>"><a data-toggle="tab" href="#tab-<?php echo $idioma['orden'];?>"> <?php echo $idioma['idioma'];?></a></li>
                        	<?php } ?>
                        </ul>

                        <div class="tab-content">
	                        <!-- Items Idiomas -->
                        	<?php foreach($idiomas as $idioma) { ?>
	                        <div id="tab-<?php echo $idioma['orden'];?>" class="tab-pane<?php if($idioma['orden'] == 1) { echo ' active';};?>">
                        	<?php 
								if(!empty($detalle['id']))
								{
									$CI =& get_instance();
									$CI->load->model("Paginas_model");
									$item = $this->Paginas_model->getPaginaDetalleIdioma($detalle['id'], $idioma['extension']);
									$imagen = $this->Paginas_model->getMedia($detalle['id'], $idioma['extension'], 13);
								}
							?>
	                            <div class="panel-body">
								 <div class="row">

					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Encabezado</h2>
					                 	<div class="form-group">
											<label class="col-sm-1 control-label">T&iacute;tulo</label>
											<div class="col-sm-5">
		                                        <div class="input-group">
													<input type="text" name="titulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo'] : null; ?>"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de la sección, que se mostrará sobre la imagen del encabezado." title=""> <i class="fa fa-question"></i></button>
			                                    </div>
			                                  </div>
										    <!-- Imagenes Generales -->
											<label class="col-sm-1 control-label">Imagen</label>
						                    <div class="col-sm-5">
					                            <?php if(!empty($imagen)) { ?>
					                            <div class="col-sm-12">
					                            	<img src="/multimedia/thumbs/<?php echo $imagen['imagen_breadcrumb'];?>" style="height:auto;width:100%;float: left;padding-bottom: 24px;padding-right: 25px;"/>
					                            </div>
				                            	<?php } ?>
					                            <div class="col-sm-12">
		                                        	<div class="input-group">
						                            	<input type="file" name="imagen_<?php echo $idioma['extension'];?>" class="form-control"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen asociada a la sección, en caso de requerir. El tamaño depende de la misma: 580x400 para Inicio, 200x100 en Quiénes Somos, 560x400 para Contacto, 500x500 para servicios y por default." title=""> <i class="fa fa-question"></i></button>
						                            </div>
					                            </div>
											</div>
					                 	</div>
					                </div>

					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Contenido General</h2>
					                 	<div class="form-group">
											<label class="col-sm-1 control-label">Subt&iacute;tulo</label>
											<div class="col-sm-5">
		                                        <div class="input-group">
													<input type="text" name="subtitulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['subtitulo'])) ? $item['subtitulo'] : null; ?>"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de la sección, que se mostrará antes del conteniod." title=""> <i class="fa fa-question"></i></button>
			                                    </div>
			                                  </div>
			                               </div>
			                                 
											<div class="form-group m-b-md pull-left full-width m-t-md">
							                    <div class="col-sm-12">
													<div class="ibox-title bg-muted m-t-md"><h5>Contenido</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Contenido de la sección." title=""> <i class="fa fa-question"></i></button></div>
													<div class="ibox-content no-padding">
													    <textarea class="form-control summernote" name="contenido1_<?php echo $idioma['extension'];?>" rows="3"><?php echo(isset($item['contenido1'])) ? $item['contenido1']: null?></textarea></div>
							                    </div>
					                 	  </div>
					                  </div>
						        </div>
						    </div>
						</div>
                       	<?php } ?>
		                <!-- Fin Item Idiomas -->
			        <?php echo form_close();?>
                    </div>
                </div>
             </div>                 
         </div>
     </div>     

<!-- SUMMERNOTE -->
<script src="/assets/js/plugins/summernote/summernote.min.js"></script>
<script src="/assets/js/summernote-grid.js"></script>
<script src="/assets/js/summernote-ext-addclass.js"></script>
<script>
$('.summernote').summernote({
    	addclass: {
        	debug: false,
	        classTags: [{title:"Button","value":"btn btn-success"},"jumbotron", "lead","img-rounded","img-circle", "img-responsive","btn", "btn btn-success","btn btn-danger","text-muted", "text-primary", "text-warning", "text-danger", "text-success", "table-bordered", "table-responsive", "alert", "alert alert-success", "alert alert-info", "alert alert-warning", "alert alert-danger", "visible-sm", "hidden-xs", "hidden-md", "hidden-lg", "hidden-print"]
	    },
	    styleTags: [
    'p',
        { title: 'Blockquote', tag: 'blockquote', className: 'blockquote', value: 'blockquote' },
        'div', 'p','h1', 'h2', 'h3', 'h4', 'h5', 'h6'
	],

        placeholder: 'Ingrese contenido...',
        tabsize: 2,
        toolbar: [
          ['style', ['style', 'addclass']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['insert', ['link', 'picture']],
          ['insert', ['grid']],
          ['view', ['codeview']]
        ]

	});
$('[data-toggle="tooltip"]').tooltip(); 
</script>
      
                                       