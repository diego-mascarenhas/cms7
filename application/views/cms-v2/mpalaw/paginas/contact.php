<style>
.note-editor.note-frame { border:0;}
.contact-box { min-height: 300px;max-height: 300px; padding:20px 10px;display: flex;flex-direction: column;justify-content: center;}
.contact-box img { height: 100px; width:auto;}
.tooltip-inner {max-width: 250px;width: 250px;}
.bg-inactiva {color: #a94442;background: #f2dede !important;border-color: #ebccd1;}
.box-items {display:flex; flex-direction: column;}
.box-items { padding-left:5px; padding-right:5px;}
.modal-title { line-height: 1;}
@media (min-width:992px) {
	.modal-lg {width:800px;}
}
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

            <form action="/cms-v2/paginas/modificar/<?php echo $detalle['id']; ?>/" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<input type="hidden" name="id_con_secciones" value="<?php echo $detalle['id_con_secciones']; ?>">
			<input type="hidden" name="id_imagen_tipo" value="13">
			<input type="hidden" name="medidas" value="1200x800" />
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
									$imagen2 = $this->Paginas_model->getMedia($detalle['id'], $idioma['extension'], 12);
								}
							?>
	                            <div class="panel-body">
								 <div class="row">
	
					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Contenido</h2>
					                 	<div class="form-group">
											<label class="col-sm-1 control-label">Título</label>
											<div class="col-sm-5">
		                                        <div class="input-group">
													<input type="text" name="titulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo'] : null; ?>"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de la sección, que se mostrará sólo en caso de ser necesario." title=""> <i class="fa fa-question"></i></button></span>
												</div>
											</div>
											<label class="col-sm-2 control-label text-right">Imagen</label>
											<div class="col-md-4">
					                            <?php if(!empty($imagen)) { ?>
					                            <div class="col-sm-12">
					                            	<img src="/multimedia/thumbs/<?php echo $imagen['imagen_breadcrumb'];?>" style="height:auto; max-height:100px;width:100%;float: left;padding-bottom: 24px;padding-right: 25px;"/>
					                            </div>
				                            	<?php } ?>
					                            <div class="col-sm-12">
		                                        	<div class="input-group">
						                            	<input type="file" name="imagen_<?php echo $idioma['extension'];?>" class="form-control"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen de fondo del encabezado. El tamaño debe ser 1200x500 píxeles o proporcionales y debe tener el menor peso posible para mejorar la carga de la sección."> <i class="fa fa-question"></i></button></span>
						                            </div>
					                            </div>
											</div>
					                 	</div>
					                 </div>
					                <div class="col-lg-12 p-xxs m-t-md">
			                            <div class="form-group">
											<label class="col-sm-1 control-label">Dirección</label>
											<div class="col-sm-5">
		                                        <div class="input-group">
													<input type="text" name="subtitulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['subtitulo'])) ? $item['subtitulo'] : null; ?>"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Dirección que se mostrará sobre el mapa, debe incluir una barra (/) para salto de línea." title=""> <i class="fa fa-question"></i></button>
												</div>
											</div>
											<label class="col-sm-1 control-label">Mapa</label>
											<input type="hidden" name="id_imagen_tipo2" value="12">
						                    <div class="col-sm-5">
					                            <?php if(!empty($imagen2)) { ?>
					                            <div class="col-sm-12">
					                            	<img src="/multimedia/thumbs/<?php echo $imagen2['imagen_breadcrumb'];?>" style="height:auto;width:auto; max-width:200px;float: left;padding-bottom: 24px;padding-right: 25px;"/>
					                            </div>
				                            	<?php } ?>
					                            <div class="col-sm-12">
		                                        	<div class="input-group">
						                            	<input type="file" name="imagen_seccion_<?php echo $idioma['extension'];?>" class="form-control"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen del mapa. El tamaño debe ser 1200x500 píxeles o proporcionales y debe tener el menor peso posible para mejorar la carga de la sección."> <i class="fa fa-question"></i></button>
						                            </div>
					                            </div>
											</div>
					                    </div>
				                    </div>
					                <div class="col-lg-12 p-xxs m-t-md">
			                            <div class="form-group">
											<label class="col-sm-1 control-label">Link Google Maps</label>
											<div class="col-sm-5">
		                                        <div class="input-group">
													<input type="text" name="texto_adicional_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['texto_adicional'])) ? $item['texto_adicional'] : null; ?>"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Link que se copia desde Google Maps > Compartir." title=""> <i class="fa fa-question"></i></button>
												</div>
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


<!-- SUMMERNOTE -->
<script src="<?php echo base_url('assets/js/plugins/summernote/summernote.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/summernote-grid.js'); ?>"></script>
<script>
$('.summernote2').summernote({
        placeholder: 'Ingrese contenido...',
        dialogsInBody: true,
        height: 100,
		toolbar: [
		    // [groupName, [list of button]]
		    ['style', ['style']],
		    ['font', ['bold', 'italic','underline', 'clear']],
		    ['fontsize', ['fontsize']],
		    ['color', ['color']],
		    ['para', ['ul', 'ol', 'paragraph']],
		    ['insert', ['link']]
		  ]
});
$('[data-toggle="tooltip"]').tooltip(); 
</script>            
                                       