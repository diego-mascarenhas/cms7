<style>
.note-editor.note-frame { border:0;}
.contact-box { min-height: 300px;max-height: 300px; padding:20px 10px;display: flex;flex-direction: column;justify-content: center;}
.contact-box img { height: 100px; width:auto;}
.tooltip-inner {max-width: 250px;width: 250px;}
.bg-inactiva {color: #a94442;background: #f2dede !important;border-color: #ebccd1;}
.box-items {display:flex; flex-direction: column; padding-left:5px; padding-right:5px;}
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
									$imagen = $this->Paginas_model->getMedia($detalle['id'], $idioma['extension'], 13);

									$parametros['id'] = $detalle['id'];
									$parametros['idioma'] = $idioma['extension'];
									$parametros['id_tipo'] = $detalle['id_con_secciones'];
									$boxes = $this->Paginas_model->getContenidoAdicionalIdioma($parametros);
								}
							?>
	                            <div class="panel-body">
								 <div class="row">
					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Contenido</h2>
					                 	<div class="form-group pull-left full-width m-t-md">
											<div class="col-sm-12">
												<label class="col-sm-1 control-label">Título</label>
												<div class="col-sm-8">
													<div class="input-group"><input type="text" name="titulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del contenido." title=""> <i class="fa fa-question"></i></button></div>
												</div>

												<div class="col-sm-12">
													<div class="ibox-title bg-muted m-t-md"><h5>Texto Introducción</h5><button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Introducción del contenido." title=""> <i class="fa fa-question"></i></button></div>
													<div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido1_<?php echo $idioma['extension'];?>" rows="3"><?php echo(isset($item['contenido1'])) ? $item['contenido1']: null?></textarea></div>
												</div>
											</div>
					                 	</div>
					                 	<?php if($detalle['id_con_secciones'] != 729) { ?>
					                 	<div class="form-group m-b-md pull-left full-width">
											<div class="col-sm-6">
			                            		<label class="col-sm-2 control-label m-t-md">Imagen</label>
								                <?php if($imagen['imagen_breadcrumb']) { ?>
								                <div class="col-sm-4 m-t-md">
									                <img src="<?php echo base_url('/multimedia/thumbs/'.$imagen['imagen_breadcrumb']);?>" alt="<?php echo $item['titulo'];?>" width="100%">
								                </div>
								                <?php } ?>
								                <div class="<?php echo ($imagen['imagen_breadcrumb']) ? "col-sm-6" : "col-sm-7"; ?>">
			                                        <div class="input-group m-t-md"><input type="file" name="imagen_<?php echo $idioma['extension'];?>" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen lateral del contenido de Texto Adicional. Tamaño recomendado 950x640 píxeles o proporcionales." title=""> <i class="fa fa-question"></i></button></div>
								                </div>
							                </div>

											<div class="col-sm-6">
												<div class="ibox-title bg-muted m-t-md"><h5>Texto Adicional</h5><button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Texto del contenido." title=""> <i class="fa fa-question"></i></button></div>
												<div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido2_<?php echo $idioma['extension'];?>" rows="3"><?php echo(isset($item['contenido2'])) ? $item['contenido2']: null?></textarea></div>
											</div>
					                 	</div>
					                 	<?php } ?>
					                </div>

					                <div class="col-lg-12 p-xxs">
						            	<div class="pull-left full-width">
											<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Listado de <?php echo ($detalle['id_con_secciones'] == 729) ? 'Informes' : 'Items' ; ?><?php if($boxes) { ?> <a title="Ordenar" href="<?php echo base_url('cms-v2/paginas/ordenar/'.$detalle['id'].'/753/'.$idioma['extension']);?>" class="sepV_a btn btn-primary btn-sm red pull-right m-l-sm"><i class="fa fa-plus-circle"></i> Ordenar Items</a> <?php }?><a title="Ingresar" id="item" href="#" data-toggle="modal" data-idioma="<?php echo $idioma['extension'];?>" data-target="#myModalIngresar756" class="sepV_a btn btn-primary btn-sm red pull-right"><i class="fa fa-plus-circle"></i> Ingresar Item</a></h2>
							                <?php  if($boxes) { foreach($boxes as $box) { ?>	
								                <div class="col-md-6 col-lg-3 box-items">
													<div class="contact-box<?php echo ($box['estado'] == 1) ? ' bg-inactiva' : '';?>">
									                    <div class="col-sm-12">
									                        <div class="text-center">
									                            <?php if($box['imagen']) { ?>
									                            	<img src="<?php echo base_url('multimedia/thumbs/'.$box['imagen']);?>" title="<?php echo $box['titulo']; ?>" alt="<?php echo $box['titulo'];?>" class="m-b-xs">
									                            <?php } ?>
									                        </div>
									                    </div>
									                    <div class="col-sm-12">
									                        <h3><strong><?php echo ellipsize($box['titulo'],25, 1);?></strong></h3>
									                        <address>
									                            <div><?php echo character_limiter($box['contenido1'], 58, '...');?></div>
									                        </address>
									                    </div>
									                    <div class="col-sm-12 pull-right text-right	">
				                                            <a title="Modificar" href="<?php echo base_url('cms-v2/paginas/modificar_informacion/'.$detalle['id'].'/'.$box['id']);?>" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i> Modificar</a>
				                                            <a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $box['titulo'];?>?" data-estado="<?php echo $box['estado'];?>" data-id_contenido="<?php echo $detalle['id'];?>" data-id="<?php echo $box['id'];?>" data-target="#myModalEliminarInformacion" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i> Eliminar</a>
									                    </div>
									                </div>
									             </div>
											<?php } } else { echo '<p class="p-xs">No se encontraron resultados</p>';} ?>
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

<!-- Modal Ingresar -->
<div class="modal inmodal" id="myModalIngresar756" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
   		<div class="modal-content animated">
	        <div class="modal-body p-xs pull-left full-width">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title text-center">Ingresar <?php echo($detalle['id_con_secciones'] == 729) ? "informe" : "ítems de Valores"; ?></h4>
		        <form name="ingresar" class="form_ingresar" method="post" enctype="multipart/form-data" action="<?php echo base_url('cms-v2/paginas/ingresar_informacion/'); ?>">
		            <input type="hidden" name="idioma" id="idioma" value="">
		            <input type="hidden" name="id_contenido" id="id_contenido" value="<?php echo $detalle['id'];?>">
                    <input type="hidden" name="id_tipo" value="<?php echo $detalle['id_con_secciones'];?>">
                    <input type="hidden" name="id_imagen_tipo" value="13">
                    <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Título</label>
	                    <div class="input-group col-sm-9"><input type="text" name="titulo" id="titulo" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del ítem." title=""> <i class="fa fa-question"></i></button></span></div>
	                </div>

                    <?php if($detalle['id_con_secciones'] == 729) { ?>
                    <div class="col-sm-12 m-b-sm">
	                    <input type="hidden" name="medidas" value="230x300">
	                    <label class="control-label col-sm-3">Imagen</label>
                    	<div class="input-group col-sm-9">
                            <div class="fileinput fileinput-new form-control" data-provides="fileinput"><input type="file" name="imagen"><span class="input-group-btn" style="position: absolute;right: 28px;top: 0;"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Imagen jpg, gif o png, recomendado 230x300 píxeles. Recuerde que el peso debe ser el mínimo posible para no afectar la descarga del site." title=""> <i class="fa fa-question"></i></button></span></div>
                    	</div>
                    </div>
                    <div class="col-sm-12 m-b-sm">
	                    <input type="hidden" name="medidas" value="230x300">
	                    <label class="control-label col-sm-3">Archivo</label>
                    	<div class="input-group col-sm-9">
                            <div class="fileinput fileinput-new form-control" data-provides="fileinput"><input type="file" name="archivo"><span class="input-group-btn" style="position: absolute;right: 28px;top: 0;"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Archivo en PDF" title=""> <i class="fa fa-question"></i></button></span></div>
                    	</div>
                    </div>

<!--
                    <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Nombre Archivo</label>
                    	<div class="input-group col-sm-9">
                    	<input type="text" name="archivo" id="archivo" value="" class="form-control"><span class="input-group-btn" style="position: absolute;right: 28px;top: 0;"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Nombre del archivo cargado en el Canal Balances de Multimedia." title=""> <i class="fa fa-question"></i></button></span></div>
                    </div>
-->
                    <?php } else { ?>
                    <div class="col-sm-12 m-b-sm">
	                    <label class="control-label pull-left">Texto<button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Texto del ítem." title=""> <i class="fa fa-question"></i></button></label>
						<div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido1" id="contenido1" rows="4"  value=""></textarea></div>
					</div>

					<?php if($detalle['id_con_secciones'] != 723) { ?>
                     <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Link</label>
	                    <div class="input-group col-sm-9"><input type="text" name="subtitulo" id="subtitulo" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Url absoluta del link, por ej.: https://fundacionbomberos.org.ar/beneficios" title=""> <i class="fa fa-question"></i></button></span></div>
	                </div>
					<?php } ?>

                    <div class="col-sm-12 m-b-sm">
	                    <input type="hidden" name="medidas" value="120x120">
	                    <label class="control-label col-sm-3">Imagen</label>
                    	<div class="input-group col-sm-9">
                            <div class="fileinput fileinput-new form-control" data-provides="fileinput"><input type="file" name="imagen"><span class="input-group-btn" style="position: absolute;right: 28px;top: 0;"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Imagen jpg, gif o png, recomendado 120x120 píxeles. Recuerde que el peso debe ser el mínimo posible para no afectar la descarga del site." title=""> <i class="fa fa-question"></i></button></span></div>
                    	</div>
                    </div>
                    <?php } ?>

                    <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Orden</label>
	                    <div class="input-group col-sm-9"><input type="text" name="orden" id="orden" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en que se mostrará." title=""> <i class="fa fa-question"></i></button></span></div>
	                </div>
                    <div class="col-sm-12 m-b-sm">
                    	<label class="control-label col-sm-3">Estado</label>
                    	<div class="input-group col-sm-9">
                        <select name="estado" class="form-control m-b">
                            <option value="1">Inactivo</option>
                            <option value="3">Activo</option>
                        </select><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Si es activo se muestra en la web, si es inactivo no se muestra en la web." title=""> <i class="fa fa-question"></i></button></span>
                    	</div>
                    </div>
		            <input type="submit" class="btn btn-primary pull-right" value="Ingresar">
	            </form>
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

$('.summernote2').summernote({
        placeholder: 'Ingrese contenido...',
        tabsize: 2,
        height: 100,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['insert', ['link']]
        ]
});
$('[data-toggle="tooltip"]').tooltip(); 

  $('.inmodal').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var contenido = $(e.relatedTarget).data().contenido;
     var idioma = $(e.relatedTarget).data().idioma;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#contenido').val(contenido);
      $(e.currentTarget).find('#idioma').val(idioma);
	  //paso variables por id
	  $('#texto').html(seccion); 
  });
</script>