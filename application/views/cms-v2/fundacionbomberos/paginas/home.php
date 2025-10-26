<style>
.note-editor.note-frame { border:0;}
.tooltip-inner {max-width: 250px;width: 250px;  z-index: 100000000;}
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

		<?php if ($this->session->flashdata('mensaje')) { ?>
		<div class="col-md-12">
			<?php if ($this->session->flashdata('mensaje') == 'error') { ?>
			<div class="alert alert-danger alert-dismissable" role="alert"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <p>Ha habido un problema, por favor intenta más tarde</div>
			<?php } else { ?>
			<div class="alert alert-success alert-dismissable" role="alert">
				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
				<p>El contenido fue modificado correctamente.</div>
			<?php } ?>
		</div>
		<?php } ?>

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
									$parametros['id'] = $detalle['id'];
									$parametros['idioma'] = $idioma['extension'];
									$parametros['id_tipo'] = 8;
									$CI =& get_instance();
									$CI->load->model("Paginas_model");
									$item = $this->Paginas_model->getPaginaDetalleIdioma($detalle['id'], $idioma['extension']);
									$imagen = $this->Paginas_model->getMedia($detalle['id'], $idioma['extension'], 12);
									$slides = $this->Paginas_model->getContenidoAdicionalIdioma($parametros);

									$parametros1['id'] = $detalle['id'];
									$parametros1['idioma'] = $idioma['extension'];
									$parametros1['id_tipo'] = 753;
									$boxes = $this->Paginas_model->getContenidoAdicionalIdioma($parametros1);

									$parametros2['id'] = $detalle['id'];
									$parametros2['idioma'] = $idioma['extension'];
									$parametros2['id_tipo'] = 855;
									$participes = $this->Paginas_model->getContenidoAdicionalIdioma($parametros2);
								}
							?>
	                            <div class="panel-body">
								 <div class="row">
	
								 <input type="hidden" name="titulo_<?php echo $idioma['extension'];?>" value="Home">
					                <div class="col-lg-12 p-xxs">
									<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Listado de Slides 
										<?php if($slides) { ?> <a title="Ordenar" href="<?php echo base_url('cms-v2/paginas/ordenar/'.$detalle['id'].'/8/'.$idioma['extension']);?>" class="sepV_a btn btn-primary btn-sm red pull-right m-l-sm"><i class="fa fa-plus-circle"></i> Ordenar slides</a> <?php }?>
									 <a title="Ingresar" id="item" href="#" data-toggle="modal" data-seccion="Slide" data-id_contenido="<?php echo $detalle['id'];?>" data-idioma="<?php echo $idioma['extension'];?>" data-target="#myModalSlide" class="sepV_a btn btn-primary btn-sm red pull-right"><i class="fa fa-plus-circle"></i> Ingresar slide</a></h2>
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
					                                        <td>
					                                    	<?php if($slide['imagen']) { ?>
					                                    		<img src="<?php echo base_url('multimedia/thumbs/'.$slide['imagen']);?>" title="<?php echo $slide['titulo']?>" alt="<?php echo $slide['titulo']?>" style="height:52px;"/>
					                                    	<?php } ?>
					                                       </td>
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
						            	<div class="pull-left full-width">
											<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Listado de Boxes <?php if($boxes) { ?> <a title="Ordenar" href="<?php echo base_url('cms-v2/paginas/ordenar/'.$detalle['id'].'/753/'.$idioma['extension']);?>" class="sepV_a btn btn-primary btn-sm red pull-right m-l-sm"><i class="fa fa-plus-circle"></i> Ordenar Boxes</a> <?php }?><a title="Ingresar" id="item" href="#" data-toggle="modal" data-seccion="Slide" data-id_contenido="<?php echo $detalle['id'];?>" data-idioma="<?php echo $idioma['extension'];?>" data-target="#myModalBoxes" class="sepV_a btn btn-primary btn-sm red pull-right"><i class="fa fa-plus-circle"></i> Ingresar Box</a></h2>
							                <?php  if($boxes) { foreach($boxes as $box) { ?>	
								                <div class="col-md-6 col-lg-3 box-items">
													<div class="contact-box<?php echo ($box['estado'] == 1) ? ' bg-inactiva' : '';?>">
									                    <div class="col-sm-12">
									                        <div class="text-center">
									                            <?php if($box['imagen']) { ?>
									                            	<img src="<?php echo base_url('multimedia/thumbs/'.$box['imagen']);?>" title="<?php echo $box['titulo']; ?>" alt="<?php echo $box['titulo'];?>" class="m-b-xs" style="max-width: 52%;">
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

					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Contenido Programas</h2>
					                 	<div class="form-group m-b-md pull-left full-width m-t-md">
											<label class="col-sm-1 control-label text-right">Título</label>
											<div class="col-sm-5">
												<div class="input-group"><input type="text" name="titulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título que se mostrará previo a los boxes de programas del home." title=""> <i class="fa fa-question"></i></button></div>
											</div>
											<label class="col-sm-1 control-label text-right">Subtítulo</label>
											<div class="col-sm-5">
												<div class="input-group"><input type="text" name="subtitulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['subtitulo'])) ? $item['subtitulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Texto que se mostrará debajo del título de los boxes de programas del home." title=""> <i class="fa fa-question"></i></button></div>
											</div>
					                 	</div>
					                </div>
					                
					                <div class="col-lg-12 p-xxs">
						            	<div class="pull-left full-width">
											<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Contenido Participación <?php if($participes) { ?> <a title="Ordenar" href="<?php echo base_url('cms-v2/paginas/ordenar/'.$detalle['id'].'/855/'.$idioma['extension']);?>" class="sepV_a btn btn-primary btn-sm red pull-right m-l-sm"><i class="fa fa-plus-circle"></i> Ordenar Items</a> <?php }?><a title="Ingresar" id="item" href="#" data-toggle="modal" data-seccion="Items" data-id_contenido="<?php echo $detalle['id'];?>" data-idioma="<?php echo $idioma['extension'];?>" data-target="#myModalParti" class="sepV_a btn btn-primary btn-sm red pull-right"><i class="fa fa-plus-circle"></i> Ingresar Item</a></h2>

							                <?php if($participes) { foreach($participes as $participe) { ?>	
								                <div class="col-md-6 col-lg-3 box-items">
													<div class="contact-box<?php echo ($participe['estado'] == 1) ? ' bg-inactiva' : '';?>">
									                    <div class="col-sm-12">
									                        <div class="text-center">
									                            <?php if($participe['imagen']) { ?>
									                            	<img src="<?php echo base_url('multimedia/thumbs/'.$participe['imagen']);?>" title="<?php echo $participe['titulo']; ?>" alt="<?php echo $participe['titulo'];?>" class="m-b-xs" style="max-width: 52%;">
									                            <?php } ?>
									                        </div>
									                    </div>
									                    <div class="col-sm-12">
									                        <h3><strong><?php echo ellipsize($participe['titulo'],25, 1);?></strong></h3>
									                        <address>
									                            <div><?php echo character_limiter($participe['contenido1'], 58, '...');?></div>
									                        </address>
									                    </div>
									                    <div class="col-sm-12 pull-right text-right">
				                                            <a title="Modificar" href="<?php echo base_url('cms-v2/paginas/modificar_informacion/'.$detalle['id'].'/'.$participe['id']);?>" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i> Modificar</a>
				                                            <a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $participe['titulo'];?>?" data-estado="<?php echo $participe['estado'];?>" data-id_contenido="<?php echo $detalle['id'];?>" data-id="<?php echo $participe['id'];?>" data-target="#myModalEliminarInformacion" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i> Eliminar</a>
									                    </div>
									                </div>
									             </div>
											<?php } } else { echo '<p class="p-xs">No se encontraron resultados</p>';} ?>
											</div>
					                </div>
					                
					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Contenido Novedades</h2>
					                 	<div class="form-group m-b-md pull-left full-width m-t-md">
											<label class="col-sm-1 control-label text-right">Título</label>
											<div class="col-sm-5">
												<div class="input-group"><input type="text" name="contenido2_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['contenido2'])) ? $item['contenido2']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título que se mostrará previo a los boxes de noticias destacadas del home." title=""> <i class="fa fa-question"></i></button></div>
											</div>
											<label class="col-sm-1 control-label text-right">Subtítulo</label>
											<div class="col-sm-5">
												<div class="input-group"><input type="text" name="contenido3_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['contenido3'])) ? $item['contenido3']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Texto que se mostrará debajo del título de los boxes de noticias destacadas del home." title=""> <i class="fa fa-question"></i></button></div>
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
                                       
<!-- Modal Ingresar Participacion -->
<div class="modal inmodal" id="myModalParti" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
   		<div class="modal-content animated">
	        <div class="modal-body p-xs pull-left full-width">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title text-center">Ingresar Items de Participación</h4>
		        <form name="ingresar" class="form_ingresar" method="post" enctype="multipart/form-data" action="<?php echo base_url('cms-v2/paginas/ingresar_informacion/'); ?>">
                   <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Título</label>
	                    <div class="input-group col-sm-9"><input type="text" name="titulo" id="titulo" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del ítem (se muestra en color blanco)." title=""> <i class="fa fa-question"></i></button></span></div>
	                </div>
					<div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Tipo</label>
	                    <div class="input-group col-sm-9"><input type="texto_adicional" name="titulo" id="texto_adicional" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Tipo de participante del título, por ej.: bombero, comunidad, etc (en color azul)." title=""> <i class="fa fa-question"></i></button></span></div>
					</div>
                    <div class="col-sm-12 m-b-sm">
	                    <label class="control-label pull-left">Texto<button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Texto del ítem." title=""> <i class="fa fa-question"></i></button></label>
						<div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido1" id="contenido1" rows="4"  value=""></textarea></div>
					</div>
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
                    <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Imagen</label>
                    	<div class="input-group col-sm-9">
                            <div class="fileinput fileinput-new form-control" data-provides="fileinput"><input type="file" name="imagen"><span class="input-group-btn" style="position: absolute;right: 28px;top: 0;"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Imagen jpg, gif o png. Tamaño recomendado 570x390 píxeles. Recuerde que el peso debe ser el mínimo posible para no afectar la descarga del site." title=""> <i class="fa fa-question"></i></button></span></div>
                    	</div>
                    </div>


		            <input type="hidden" name="idioma" id="idioma" value="">
		            <input type="hidden" name="id_contenido" id="id_contenido" value="">
                    <input type="hidden" name="medidas" value="570x390">
                    <input type="hidden" name="id_tipo" value="855">
                    <input type="hidden" name="id_imagen_tipo" value="13">
	                <input type="submit" class="btn btn-primary pull-right" value="Ingresar">
	            </form>
	        </div>
  		</div>
	</div>
</div>

<!-- Modal Ingresar Boxes -->
<div class="modal inmodal" id="myModalBoxes" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
   		<div class="modal-content animated">
	        <div class="modal-body p-xs pull-left full-width">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title text-center">Ingresar contenido de Items bajo Slide</h4>
		        <form name="ingresar" class="form_ingresar" method="post" enctype="multipart/form-data" action="<?php echo base_url('cms-v2/paginas/ingresar_informacion/'); ?>">
                   <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Título</label>
	                    <div class="input-group col-sm-9"><input type="text" name="titulo" id="titulo" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Texto del box." title=""> <i class="fa fa-question"></i></button></span></div>
	                </div>
                    <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Texto Link</label>
	                    <div class="input-group col-sm-9"><input type="text" name="texto_adicional" id="texto_adicional" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Texto del botón del link." title=""> <i class="fa fa-question"></i></button></span></div>
	                </div>
                    <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Link</label>
	                    <div class="input-group col-sm-9"><input type="text" name="subtitulo" id="subtitulo" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Url absoluta del link, por ej.: https://fundacionbomberos.org.ar/beneficios" title=""> <i class="fa fa-question"></i></button></span></div>
	                </div>
                    <div class="col-sm-12 m-b-sm">
					    <label class="control-label col-sm-3">Seleccione color</label>
	                    <div class="input-group col-sm-9"><input type="text" name="contenido3" id="contenido3" value="" class="form-control demo1 colorpicker-element"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Color de fondo del box." title=""> <i class="fa fa-question"></i></button></span></div>
					</div>
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
                    <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Imagen</label>
                    	<div class="input-group col-sm-9">
                            <div class="fileinput fileinput-new form-control" data-provides="fileinput"><input type="file" name="imagen"><span class="input-group-btn" style="position: absolute;right: 28px;top: 0;"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Imagen jpg, gif o png, alto y ancho de acuerdo a box, recomendado no más de 250 píxeles de ancho y alto. Recuerde que el peso debe ser el mínimo posible para no afectar la descarga del site." title=""> <i class="fa fa-question"></i></button></span></div>
                    	</div>
                    </div>

		            <input type="hidden" name="idioma" id="idioma" value="">
		            <input type="hidden" name="id_contenido" id="id_contenido" value="">
                    <input type="hidden" name="id_tipo" value="753">
                    <input type="hidden" name="id_imagen_tipo" value="13">
	                <input type="submit" class="btn btn-primary pull-right" value="Ingresar">
	            </form>
	        </div>
  		</div>
	</div>
</div>

<!-- Modal Slide -->
    <div class="modal inmodal" id="myModalSlide" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog modal-lg">
	   		<div class="modal-content animated">
		        <div class="modal-body p-xs pull-left full-width">
		            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
		            <h4 class="modal-title">Ingresar contenido de Slide</h4>
			        <form name="ingresar" class="form_ingresar" method="post" enctype="multipart/form-data" action="<?php echo base_url('cms-v2/paginas/ingresar_informacion/'); ?>">
	                   <div class="col-sm-12 m-b-sm">
		                    <label class="control-label col-sm-3">Título</label>
		                    <div class="input-group col-sm-9"><input type="text" name="titulo" id="titulo" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de la novedad que se mostrará en el slide." title=""> <i class="fa fa-question"></i></button></span></div>
		                </div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Texto<button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Texto breve de introducción de la novedad" title=""> <i class="fa fa-question"></i></button></label>
							<div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido1" id="contenido1" rows="4"  value=""></textarea></div>
						</div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label col-sm-3">Texto Link</label>
		                    <div class="input-group col-sm-9"><input type="text" name="subtitulo" id="subtitulo" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Texto del botón del link." title=""> <i class="fa fa-question"></i></button></span></div>
		                </div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label col-sm-3">Link</label>
		                    <div class="input-group col-sm-9"><input type="text" name="texto_adicional" id="texto_adicional" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Url absoluta del link, por ej.: https://fundacionbomberos.org.ar/beneficios" title=""> <i class="fa fa-question"></i></button></span></div>
		                </div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label col-sm-3">Orden</label>
		                    <div class="input-group col-sm-9"><input type="text" name="orden" id="orden" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en que se mostrarán" title=""> <i class="fa fa-question"></i></button></span></div>
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
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label col-sm-3">Imagen</label>
	                    	<div class="input-group col-sm-9">
	                            <div class="fileinput fileinput-new form-control" data-provides="fileinput"><input type="file" name="imagen"><span class="input-group-btn" style="position: absolute;right: 28px;top: 0;"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Imagen jpg, gif o png. Tamaño recomendado 1920x780 píxeles. Recuerde que el peso debe ser el mínimo posible para no afectar la descarga del site." title=""> <i class="fa fa-question"></i></button></span></div>
	                    	</div>
	                    </div>
	                    <div class="col-sm-12 m-t-sm">
			            	<input type="hidden" name="id" id="id" value="" />
			            	<input type="hidden" name="id_tipo" id="id_tipo" value="8" />
			            	<input type="hidden" name="id_imagen_tipo" id="tipo" value="18" />
			            	<input type="hidden" name="idioma" id="idioma" value="" />
			            	<input type="hidden" name="medidas" value="1920x780" />
			            	<input type="hidden" name="id_contenido" id="id_contenido" value="" />
			                <input type="submit" class="btn btn-primary pull-right" value="Ingresar">
	                    </div>
		            </form>
		        </div>
	  		</div>
		</div>
	</div>


<!-- Modal Eliminar -->
<div class="modal inmodal" id="myModalEliminarInformacion" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content animated">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
            <h4 class="modal-title">Eliminar contenido</h4>
            </div>
            <div class="modal-body">
            <p>&iquest;Est&aacute; seguro de querer eliminar el contenido <strong> <input type="text" name="seccion" id="seccion" value="" style="border:1px solid #f8fafb; background:#f8fafb; width:auto !important;"/></strong>?</p>
                <div class="modal-footer">
	                <form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('cms-v2/paginas/eliminar_informacion/'); ?>">
                    	<input type="hidden" name="id" id="id" value="" />
                    	<input type="hidden" name="estado" id="estado" value="" />
                    	<input type="hidden" name="id_contenido" id="id_contenido" value="" />
                    	<input type="submit" class="btn btn-primary" value="Eliminar">
                    </form>
                </div>
           </div>
        </div>
     </div>
</div>


<link href="/assets/css/plugins/colorpicker/bootstrap-colorpicker.min.css" rel="stylesheet">
<script src="<?php echo base_url('assets/js/plugins/colorpicker/bootstrap-colorpicker.min.js'); ?>"></script>
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
    ['style', ['style']],
    ['font', ['bold', 'italic', 'underline', 'clear']],
    ['color', ['color']],
    ['para', ['ul', 'paragraph']],
    ['insert', ['grid' ,'picture']]
  ],
  placeholder: 'Ingresar texto ...'});
  $('.inmodal').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var seccion = $(e.relatedTarget).data().seccion;
     var id_contenido = $(e.relatedTarget).data().id_contenido;
     var contenido = $(e.relatedTarget).data().contenido;
     var titulo = $(e.relatedTarget).data().titulo;
     var idioma = $(e.relatedTarget).data().idioma;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#seccion').val(seccion);
      $(e.currentTarget).find('#titulo').val(titulo);
      $(e.currentTarget).find('#id_contenido').val(id_contenido);
      $(e.currentTarget).find('#contenido').val(contenido);
      $(e.currentTarget).find('#idioma').val(idioma);
	  //paso variables por id
	  $('#texto').html(seccion); 
  });
</script>

<script src="<?php echo base_url('assets/js/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>
<script>
  $('.demo1').colorpicker();
</script>