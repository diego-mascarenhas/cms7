<style>
.note-editor.note-frame { border:0;}
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
			<input type="hidden" name="id_tipo" value="13">
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
									$imagen = $this->Paginas_model->getMedia($detalle['id'], $idioma['extension'], 12);

									$parametros1['id'] = $detalle['id'];
									$parametros1['idioma'] = $idioma['extension'];
									$parametros1['id_tipo'] = 8;
									$slides= $this->Paginas_model->getContenidoAdicionalIdioma($parametros1);
								}
							?>
	                            <div class="panel-body">
								 <div class="row">
	
								 <input type="hidden" name="titulo_<?php echo $idioma['extension'];?>" value="Home">
					                <div class="col-lg-12 p-xxs">
									<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Listado de Slides 
										<?php if($slides) { ?> <a title="Ordenar" href="<?php echo base_url('cms-v2/paginas/ordenar/'.$detalle['id'].'/8/'.$idioma['extension']);?>" class="sepV_a btn btn-primary btn-sm red pull-right m-l-sm"><i class="fa fa-plus-circle"></i> Ordenar slides</a> <?php }?>
									 <a title="Ingresar" id="item" href="#" data-toggle="modal" data-seccion="Slide" data-idioma='<?php echo $idioma['extension'];?>' data-target="#myModalSlide" class="sepV_a btn btn-primary btn-sm red pull-right"><i class="fa fa-plus-circle"></i> Ingresar slide</a></h2>
										<div class="ibox-content no-borders">
											<div class="table-responsive">
				                           		<table class="footable table p-md table-stripped toggle-arrow-tiny bg-muted">
								                    <thead>
									                    <tr>
									                        <th>Imagen</th>
									                        <th>Título</th>
									                        <th>Estado</th>
									                        <th>Acciones</th>
									                    </tr>
								                    </thead>
								                    <tbody>

							                <?php 
												if($slides) { foreach($slides as $slide) { ?>	
							                   	<tr class="gradeX">
			                                    <td><img src="<?php echo base_url('multimedia/thumbs/'.$slide['imagen']);?>" title="<?php echo $slide['titulo']?>" alt="<?php echo $slide['titulo']?>" style="height:52px;"/></td>
							                       <td><?php echo $slide['titulo']; ?></td>
							                       <td><?php echo ($slide['estado'] == 3) ? 'Activo' : 'Inactivo'; ?></td>
							                       <td>
					                                    <a title="Modificar" href="<?php echo base_url('cms-v2/paginas/modificar_informacion/'.$detalle['id'].'/'.$slide['id']);?>" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i> Modificar</a>
			                                            <a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion='<?php echo $slide['titulo'];?>' data-estado="<?php echo $slide['estado'];?>" data-id_contenido="<?php echo $detalle['id'];?>" data-id="<?php echo $slide['id'];?>" data-target="#myModalEliminarInformacion" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i> Eliminar</a>
							                       </td>
							                    </tr>
						                    <?php } } else { ?>	
							                   	<tr class="gradeX">
				                                    <td colspan="4">No se encontraron resultados</td>
							                   	</tr>
						                    <?php } ?>	
							                   </tbody>
					                    </table>
				                    </div>
								</div>
			                </div>

					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Boxes Home</h2>
					                 	<div class="form-group m-b-md pull-left full-width m-t-md">
											<label class="col-sm-1 control-label">T&iacute;tulo</label>
											<div class="col-sm-5">
												<input type="text" name="texto_adicional_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['texto_adicional'])) ? $item['texto_adicional']: null; ?>"></div>
					                 	</div>

					                 	<div class="form-group m-b-md pull-left full-width">
											<div class="col-sm-4">
												<div class="col-sm-12">
													<div class="ibox-title bg-muted m-t-md"><h5>Contenido Box 1</h5></div>
													<div class="ibox-content no-padding">
													    <textarea class="form-control summernote2" name="contenido1_<?php echo $idioma['extension'];?>" rows="3"><?php echo(isset($item['contenido1'])) ? $item['contenido1']: null?></textarea></div>
												</div>
											</div>

											<div class="col-sm-4">
												<div class="col-sm-12">
													<div class="ibox-title bg-muted m-t-md"><h5>Contenido Box 2</h5></div>
													<div class="ibox-content no-padding">
													    <textarea class="form-control summernote2 no-borders" name="contenido2_<?php echo $idioma['extension'];?>" rows="3"><?php echo(isset($item['contenido2'])) ? $item['contenido2']: null?></textarea></div>
												</div>
											</div>

											<div class="col-sm-4">
												<div class="col-sm-12">
													<div class="ibox-title bg-muted m-t-md"><h5>Contenido Box 3</h5></div>
													<div class="ibox-content no-padding">
													    <textarea class="form-control summernote2" name="contenido3_<?php echo $idioma['extension'];?>" rows="3"><?php echo(isset($item['contenido3'])) ? $item['contenido3']: null?></textarea></div>
												</div>
											</div>

					                 	</div>
					                </div>

					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Contenido Noticias
											<a title="Ver Noticias" id="item" href="<?php echo base_url('cms-v2/informacion?tipo=9');?>" class="sepV_a btn btn-primary btn-sm red pull-right"><i class="fa fa-eye"></i> Ver Noticias</a></h2>
											<div class="form-group m-b-md pull-left full-width m-t-md">
											<label class="col-sm-1 control-label">T&iacute;tulo</label>
											<div class="col-sm-5">
												<input type="text" name="contenido4_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['contenido4'])) ? $item['contenido4']: null; ?>"></div>
					                 	</div>
					                </div>

					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Contenido Agenda
											<a title="Ver Agenda" id="item" href="<?php echo base_url('cms-v2/eventos');?>" class="sepV_a btn btn-primary btn-sm red pull-right"><i class="fa fa-eye"></i> Ver Agenda</a></h2>										
					                 	<div class="form-group m-b-md pull-left full-width m-t-md">
											<label class="col-sm-1 control-label">T&iacute;tulo</label>
											<div class="col-sm-5">
												<input type="text" name="contenido5_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['contenido5'])) ? $item['contenido5']: null; ?>"></div>
					                 	</div>
					                </div>

					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Contenido Organizaciones/Patrocinadores
											<a title="Ver Patrocinadores" id="item" href="<?php echo base_url('cms-v2/paginas/modificar/84');?>" class="sepV_a btn btn-primary btn-sm red pull-right m-l-xs"><i class="fa fa-eye"></i> Ver Patrocinadores</a> <a title="Ver Organizaciones" id="item" href="<?php echo base_url('cms-v2/paginas/modificar/83');?>" class="sepV_a btn btn-primary btn-sm red pull-right"><i class="fa fa-eye"></i> Ver Organizaciones</a> </h2>										
					                 	<div class="form-group m-b-md pull-left full-width m-t-md">
											<label class="col-sm-2 control-label">T&iacute;tulo Organizaciones</label>
											<div class="col-sm-4">
												<input type="text" name="contenido6_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['contenido6'])) ? $item['contenido6']: null; ?>"></div>
											<label class="col-sm-2 control-label">T&iacute;tulo Patrocinadores</label>
											<div class="col-sm-4">
												<input type="text" name="contenido7_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['contenido7'])) ? $item['contenido7']: null; ?>"></div>
					                 	</div>
											
					                 	<div class="form-group m-b-md pull-left full-width m-t-md">
										    <!-- Imagenes Generales -->
						                    <div class="col-sm-6">
												<label class="col-sm-1 control-label">Imagen</label>
					                            <?php if(!empty($imagen)) { ?>
					                            <div class="col-sm-12">
					                            	<img src="<?php echo base_url('multimedia/thumbs/'.$imagen['imagen_breadcrumb']);?>" style="height:auto;width:200px;float: left;padding-bottom: 24px;padding-right: 25px;"/>
					                            </div>
				                            	<?php } ?>
					                            <div class="col-sm-12">
						                            <input type="file" name="imagen_<?php echo $idioma['extension'];?>" class="form-control">
							                        <input type="hidden" name="medidas" value="360x500" />
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
                                       