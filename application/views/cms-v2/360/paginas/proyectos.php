<style>
.note-editor.note-frame { border:0;}
.contact-box { min-height: 300px;max-height: 300px; padding:20px 10px;display: flex;flex-direction: column;justify-content: center;}
.contact-box img { height: 100px; width:auto;}
.tooltip-inner {max-width: 250px;width: 250px;}
.bg-inactiva {color: #a94442;background: #f2dede !important;border-color: #ebccd1;}
.box-items {display:flex; flex-direction: column;}
.box-items { padding-left:5px; padding-right:5px;}
.modal-title { text-align: center;margin: 20px 0 30px;border-bottom: 1px solid #e5e6e7;padding-bottom: 5px;}
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
			<input type="hidden" name="id_imagen_tipo" value="13">
			<input type="hidden" name="id_con_secciones" value="<?php echo $detalle['id_con_secciones']; ?>">
            <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
                <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
                <button class="btn btn-primary" type="submit">Guardar cambios</button>
            </div>
        </div>

        <div class="wrapper wrapper-content animated fadeInRight">
            <!-- Titulo Mensajes -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox-title ibox-title-custom"><h5>Subir contenido para <a><?php echo $detalle['seccion']; ?></a></h5><a href="https://staging.cms.revisionalpha.com/cms-v2/servicios" class="btn btn-sm btn-primary pull-right" style="margin-top: -5px;">Ver/Actualizar obras de Arquitectura</a></div>
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
        
		<?php if ($this->session->flashdata('mensaje')) { ?>
		<div class="col-md-12">
			<?php if ($this->session->flashdata('mensaje') == 'error') { ?>
			<div class="alert alert-danger alert-dismissable" role="alert"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><p>Ha habido un problema, por favor intenta más tarde</div>
			<?php } else { ?>
			<div class="alert alert-success alert-dismissable" role="alert"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><p>El contenido fue modificado correctamente.</div>
			<?php } ?>
		</div>
		<?php } ?>

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
								}
							?>
	                            <div class="panel-body">
								 <div class="row">
	
					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Contenido</h2>
					                 	<div class="form-group m-b-md pull-left full-width m-t-md">
											<label class="col-sm-1 control-label">Título</label>
											<div class="col-sm-4">
												<div class="input-group"><input type="text" name="titulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título general de la sección." title=""> <i class="fa fa-question"></i></button></span></div>
											</div>
					                 	</div>
					                 	
					                 	<div class="form-group m-b-md pull-left full-width m-t-md">
											<label class="col-sm-1 control-label">Título Servicios</label>
											<div class="col-sm-4">
												<div class="input-group"><input type="text" name="texto_adicional_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['texto_adicional'])) ? $item['texto_adicional']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del texto de servicios." title=""> <i class="fa fa-question"></i></button></span></div>
											</div>

											<label class="col-sm-2 control-label text-right">Galería</label>
											<div class="col-md-4 col-lg-4">
						                    	<div class="input-group"><?php echo form_dropdown('subtitulo_'.$idioma['extension'], $media_proyectos, $item['subtitulo'], 'class="form-control m-b-md"'); ?><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Galería asociada al texto de servicios, previamente cargada desde Multimedia." title=""> <i class="fa fa-question"></i></button></span></div>
											</div>
					                 	</div>

					                 	<div class="form-group m-b-md pull-left full-width m-t-md">
											<div class="col-sm-12">
												<div class="ibox-title bg-muted m-t-md"><h5>Texto</h5><button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Texto de servicios." title=""> <i class="fa fa-question"></i></button></div>
												<div class="ibox-content no-padding"><textarea class="form-control summernote" name="contenido1_<?php echo $idioma['extension'];?>" rows="3"><?php echo(isset($item['contenido1'])) ? $item['contenido1']: null?></textarea></div>
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

<script src="<?php echo base_url('assets/js/plugins/summernote/summernote.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/summernote-grid.js'); ?>"></script>
<script>
$('.summernote').summernote({
        placeholder: 'Ingrese contenido...',
        tabsize: 2,
        height: 140,
        toolbar: [
          ['insert', ['file'], ['image']],
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link']],
          ['view', ['fullscreen', 'codeview', 'help']],
          ['insert', ['grid']],
          ['misc', ['codeview']]
        ]
});
$('[data-toggle="tooltip"]').tooltip(); 
</script>
                                           