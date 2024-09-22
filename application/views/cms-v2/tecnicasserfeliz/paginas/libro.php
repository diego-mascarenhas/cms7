<style>
.note-editor.note-frame { border:0;}
.tooltip-inner {max-width: 250px;width: 250px;}
.bg-inactiva {color: #a94442;background: #f2dede !important;border-color: #ebccd1;}
.box-items {display:flex; flex-direction: column;}
.box-items { padding-left:5px; padding-right:5px;}
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

            <form action="<?php echo base_url('cms-v2/paginas/modificar/'.$detalle['id']); ?>" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<input type="hidden" name="id_con_secciones" value="<?php echo $detalle['id_con_secciones']; ?>">
			<input type="hidden" name="id_imagen_tipo2" value="13">
			<input type="hidden" name="medidas2" value="1600x593">
			<input type="hidden" name="id_imagen_tipo3" value="3">
			<input type="hidden" name="medidas3" value="414x555">
            <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
                <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
                <button class="btn btn-primary" type="submit">Guardar cambios</button>
            </div>
        </div>


        <div class="wrapper wrapper-content animated fadeInRight">
            <!-- Titulo Mensajes -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox-title ibox-title-custom"><h5>Subir contenido para<a> <?php echo $detalle['seccion']; ?></a></h5></div>
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
                    <div class="tabs-container m-b-md">
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
									$imagen = $this->Paginas_model->getMedia2($detalle['id'], $idioma['extension'], 12);
									$imagen2 = $this->Paginas_model->getMedia2($detalle['id'], $idioma['extension'], 13);
									$imagen3 = $this->Paginas_model->getMedia2($detalle['id'], $idioma['extension'], 3);
								}
							?>
	                            <div class="panel-body">
								 <div class="row">
	
								 <input type="hidden" name="titulo_<?php echo $idioma['extension'];?>" value="Libro">
									 <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Encabezado</h2>
					                 	<div class="form-group pull-left">
											<label class="col-md-1 control-label">T&iacute;tulo</label>
											<div class="col-md-5">
		                                        <div class="input-group">
													<input type="text" name="titulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo'] : null; ?>"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de la sección, que se mostrará sobre la imagen del encabezado." title=""> <i class="fa fa-question"></i></button>
			                                    </div>
			                                  </div>
											<label class="col-md-1 control-label m-t-sm">Subtítulo</label>
											<div class="col-md-5">
		                                        <div class="input-group">
													<input type="text" name="subtitulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['subtitulo'])) ? $item['subtitulo'] : null; ?>"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Subítulo de la sección, que se mostrará sólo en caso de ser necesario sobre la imagen del encabezado." title=""> <i class="fa fa-question"></i></button>
												</div>
											</div>
										</div>

										<div class="form-group pull-left">
										    <!-- Imagenes Generales -->
											<label class="col-md-1 control-label m-t-sm">Imagen</label>
						                    <div class="col-md-5">
					                            <?php if(!empty($imagen)) { ?>
					                            <div class="col-md-12">
					                            	<img src="/multimedia/thumbs/<?php echo $imagen['imagen'];?>" style="height:auto;width:100%;float: left;padding-bottom: 24px;padding-right: 25px;"/>
					                            </div>
				                            	<?php } ?>
					                            <div class="col-md-12 no-padding">
		                                        	<div class="input-group">
						                            	<input type="file" name="imagen_<?php echo $idioma['extension'];?>" class="form-control"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen asociada a la sección, en caso de requerir. Tamaño 1600x168 píxeles." title=""> <i class="fa fa-question"></i></button>
						                            </div>
					                            </div>
											</div>
											<label class="col-md-1 control-label m-t-sm">Url</label>
											<div class="col-md-5">
		                                        <div class="input-group">
													<input type="text" name="url_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['url'])) ? $item['url'] : null; ?>"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Url de la sección." title=""> <i class="fa fa-question"></i></button>
												</div>
											</div>

					                 	</div>
					                </div>

					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Contenido Libro</h2>
										<div class="form-group pull-left full-width m-t-sm">
											<label class="col-md-1 control-label">Título</label>
											<div class="col-md-5">
		                                        <div class="input-group">
													<input type="text" name="contenido4_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['contenido4'])) ? $item['contenido4'] : null; ?>"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del contenido libro." title=""> <i class="fa fa-question"></i></button>
												</div>
											</div>
										</div>
										<div class="form-group pull-left full-width">
						                    <div class="col-md-6">
												<div class="ibox-title m-t-sm no-borders"><h5>Texto</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Contenido de la sección." title=""> <i class="fa fa-question"></i></button></div>
												<div class="ibox-content no-padding">
												    <textarea class="form-control summernote" name="contenido3_<?php echo $idioma['extension'];?>" style="min-height:340px;"><?php echo(isset($item['contenido3'])) ? $item['contenido3']: null?></textarea></div>
						                    </div>
						                    <div class="col-md-6">
												<div class="ibox-title m-t-sm no-borders"><h5>Imagen</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen de libro asociada al conenido. Tamaño 410x550 píxeles." title=""> <i class="fa fa-question"></i></button></div>
												<div class="ibox-content no-padding no-borders">
						                            <?php if(!empty($imagen3)) { ?>
						                            <div class="col-md-12">
						                            	<img src="/multimedia/thumbs/<?php echo $imagen3['imagen'];?>" style="height:170px;width:auto; max-width:100%; float: left;padding-bottom: 24px;padding-right: 25px;"/>
						                            </div>
					                            	<?php } ?>
						                            <div class="col-md-12 no-padding">
			                                        	<div class="input-group">
							                            	<input type="file" name="imagen_seccion3_<?php echo $idioma['extension'];?>" class="form-control"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen de libro asociada al conenido. Tamaño 410x550 píxeles." title=""> <i class="fa fa-question"></i></button>
							                            </div>
						                            </div>
						                         </div>
											</div>
						                </div>

										<div class="form-group pull-left full-width">
						                    <div class="col-md-12">
												<div class="ibox-title m-t-md no-borders"><h5>Texto Adicional</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Contenido de la sección." title=""> <i class="fa fa-question"></i></button></div>
												<div class="ibox-content no-padding">
												    <textarea class="form-control summernote" name="contenido1_<?php echo $idioma['extension'];?>" rows="3"><?php echo(isset($item['contenido1'])) ? $item['contenido1']: null?></textarea></div>
						                    </div>
						                </div>
						             </div>


					                <?php 
						                if(!empty($categorias)) {
										foreach($categorias as $categoria) { ?>

					                    <div class="col-md-12 p-xxs">
						                    <div class="pull-left full-width">
							                	<h3 class="bg-muted p-xs pull-left full-width"><?php echo $categoria['seccion']; ?><a title="Ordenar" href="<?php echo base_url('cms-v2/paginas/ordenar/'.$detalle['id'].'/'.$categoria['id'].'/'.$idioma['extension']);?>" class="sepV_a btn btn-primary btn-sm red pull-right m-l-sm"><i class="fa fa-sort-circle"></i> Ordenar</a> 
							                	<a title="Ingresar" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $detalle['seccion']; ?>" data-id_contenido="<?php echo $detalle['id'];?>" data-idioma="<?php echo $idioma['extension'];?>" data-target="#myModalIngresarInformacion" class="sepV_a btn btn-primary btn-sm red pull-right"><i class="fa fa-plus-circle"></i> Ingresar</a></h3>
							                <?php 
												$parametros['id'] = $detalle['id'];
												$parametros['idioma'] = $idioma['extension'];
												$parametros['id_tipo'] = $categoria['id'];
												$miembros= $CI->Paginas_model->getContenidoAdicionalIdioma($parametros);

								               if(!empty($miembros)) {
												foreach($miembros as $miembro) { ?>	
								                <div class="col-sm-6 col-lg-4 box-items">
													<div class="contact-box<?php echo ($miembro['estado'] == 1) ? ' bg-inactiva' : '';?>">
									                    <div class="col-md-12">
									                        <div class="text-center">
									                            <?php if($miembro['imagen']) { ?>
									                            	<img src="<?php echo base_url('multimedia/thumbs/'.$miembro['imagen']);?>" title="<?php echo $miembro['titulo']; ?>" alt="<?php echo $miembro['titulo'];?>" class="m-b-xs">
									                            <?php } ?>
									                        </div>
									                    </div>
									                    <div class="col-md-12">
									                        <h3><strong><?php echo ellipsize($miembro['titulo'],25, 1);?></strong></h3>
									                        <p><i class="fa fa-calendar"></i> Subida: <?php echo $miembro['fecha_alta'];?></p>
									                        <address>
									                            <div><?php echo character_limiter($miembro['contenido1'], 58, '...');?></div>
									                        </address>
									                    </div>
									                    <div class="col-md-12 pull-right text-right	">
				                                            <a title="Modificar" href="<?php echo base_url('cms-v2/paginas/modificar_informacion/'.$detalle['id'].'/'.$miembro['id']);?>" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i> Modificar</a>
				                                            <a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $miembro['titulo'];?>?" data-estado="<?php echo $miembro['estado'];?>" data-id_contenido="<?php echo $detalle['id'];?>" data-id="<?php echo $miembro['id'];?>" data-target="#myModalEliminarInformacion" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i> Eliminar</a>
									                    </div>
									                </div>
									             </div>
											<?php } } else { echo 'No se encontraron resultados';} ?>
												</div>
											</div>
				                    <?php } } ?>	

					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Pie</h2>
					                 	<div class="form-group m-b-md pull-left full-width">
											<div class="col-md-6">
												<div class="ibox-title no-borders"><h5>Frase</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Frase final de la sección." title=""> <i class="fa fa-question"></i></button></div>
												<div class="ibox-content no-padding">
												    <textarea class="form-control summernote" name="contenido2_<?php echo $idioma['extension'];?>" rows="3"><?php echo(isset($item['contenido2'])) ? $item['contenido2']: null?></textarea></div>
						                    </div>
											<div class="col-md-6 m-t-md">

												<label class="col-md-3 control-label">Código Video</label>
												<div class="col-md-9">
			                                        <div class="input-group">
														<input type="text" name="texto_adicional_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['texto_adicional'])) ? $item['texto_adicional'] : null; ?>"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Código de video de YouTube, en caso de requerir." title=""> <i class="fa fa-question"></i></button></span></div>
												 </div>

												<div class="ibox-title pull-left no-borders m-t-sm"><h5>Imagen</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen del pie asociada al conenido. Tamaño 1600x593 píxeles." title=""> <i class="fa fa-question"></i></button></div>
												<div class="ibox-content no-padding no-borders">
						                            <?php if(!empty($imagen2)) { ?>
						                            <div class="col-md-12">
						                            	<img src="/multimedia/thumbs/<?php echo $imagen2['imagen'];?>" style="height:170px;width:auto; max-width:100%; float: left;padding-bottom: 24px;padding-right: 25px;"/>
						                            </div>
					                            	<?php } ?>
						                            <div class="col-md-12 no-padding">
			                                        	<div class="input-group">
							                            	<input type="file" name="imagen_seccion2_<?php echo $idioma['extension'];?>" class="form-control"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen del pie asociada al conenido. Tamaño 1600x593 píxeles." title=""> <i class="fa fa-question"></i></button></div>
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
     </div>     

<!-- SUMMERNOTE -->
<script src="<?php echo base_url('assets/js/plugins/summernote/summernote.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/summernote-grid.js'); ?>"></script>
<script>
$('[data-toggle="tooltip"]').tooltip(); 

$('.summernote').summernote({
  height: 150,   
  placeholder: 'Ingresar texto ...'});

$('.summernote2').summernote({
  height: 120,
  toolbar: [
    // [groupName, [list of button]]
    ['style', ['style']],
    ['font', ['bold', 'italic', 'underline', 'clear']],
    ['color', ['color']],
    ['para', ['ul', 'paragraph']],
    ['insert', ['grid' ,'picture']]
  ],
  placeholder: 'Ingresar texto ...'});
</script>
                                       