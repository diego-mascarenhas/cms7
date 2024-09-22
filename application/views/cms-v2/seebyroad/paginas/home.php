<style>
.note-editor.note-frame { border:0;}
.tooltip-inner {max-width: 250px;width: 250px;}
.bg-inactiva {color: #a94442;background: #f2dede !important;border-color: #ebccd1;}
.box-items {display:flex; flex-direction: column;}
.box-items { padding-left:5px; padding-right:5px;}
.inmodal .modal-header {padding: 15px 20px;border-radius: 0 !important;}
.modal-content { border-radius:0;}
.inmodal .modal-title {font-size: 23px; line-height:26px;} 
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

					                <?php 
						                if(!empty($categorias)) {
										foreach($categorias as $categoria) { ?>
	
					                    <div class="col-sm-12 p-xxs">
						                    <div class="pull-left full-width">
							                	<h3 class="bg-muted p-xs pull-left full-width"><?php echo $categoria['seccion']; ?><a title="Ordenar" href="<?php echo base_url('cms-v2/paginas/ordenar/'.$detalle['id'].'/'.$categoria['id'].'/'.$idioma['extension']);?>" class="sepV_a btn btn-primary btn-sm red pull-right m-l-sm"><i class="fa fa-sort-circle"></i> Ordenar</a> 
							                	<a title="Ingresar" id="item" href="#" data-toggle="modal" data-id_tipo="<?php echo $categoria['id']; ?>" data-seccion="<?php echo $detalle['seccion'].'/'.$categoria['seccion']; ?>" data-id_contenido="<?php echo $detalle['id'];?>" data-idioma="<?php echo $idioma['extension'];?>" data-target="#myModalIngresarInformacion<?php echo $categoria['id'];?>" class="sepV_a btn btn-primary btn-sm red pull-right"><i class="fa fa-plus-circle"></i> Ingresar</a></h3>
							                <?php 
												$parametros['id'] = $detalle['id'];
												$parametros['idioma'] = $idioma['extension'];
												$parametros['id_tipo'] = $categoria['id'];
												$miembros= $CI->Paginas_model->getContenidoAdicionalIdioma($parametros);
								               if(!empty($miembros)) {
												foreach($miembros as $miembro) { ?>	
								                <div class="col-md-6 col-lg-3 box-items">
													<div class="contact-box<?php echo ($miembro['estado'] == 1) ? ' bg-inactiva' : '';?>">
									                    <div class="col-sm-12">
									                        <div class="text-center">
									                            <?php if($miembro['imagen']) { ?>
									                            	<img src="<?php echo base_url('multimedia/thumbs/'.$miembro['imagen']);?>" title="<?php echo $miembro['titulo']; ?>" alt="<?php echo $miembro['titulo'];?>" class="m-b-xs" style="width:auto; max-width:100%;">
									                            <?php } ?>
									                        </div>
									                    </div>
									                    <div class="col-sm-12">
									                        <h3><strong><?php echo ellipsize($miembro['titulo'],25, 1);?></strong></h3>
									                        <p><i class="fa fa-calendar"></i> Subida: <?php echo $miembro['fecha_alta'];?></p>
									                        <address>
									                            <div><?php echo character_limiter($miembro['contenido1'], 58, '...');?></div>
									                        </address>
									                    </div>
									                    <div class="col-sm-12 pull-right text-right	">
				                                            <a title="Modificar" href="<?php echo base_url('cms-v2/paginas/modificar_informacion/'.$detalle['id'].'/'.$miembro['id']);?>" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i> Modificar</a>
				                                            <a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $miembro['titulo'];?>?" data-estado="<?php echo $miembro['estado'];?>" data-id_contenido="<?php echo $detalle['id'];?>" data-id="<?php echo $miembro['id'];?>" data-target="#myModalEliminarInformacion" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i> Eliminar</a>
									                    </div>
									                </div>
									             </div>
											<?php } } else { echo 'No se encontraron resultados';} ?>
												</div>
											</div>
				                    <?php } } ?>	

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

<!-- Modal Slide -->
    <div class="modal inmodal" id="myModalSlide" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog modal-lg">
	   		<div class="modal-content animated">
		        <div class="modal-header">
		            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
		            <h4 class="modal-title">Ingresar contenido de Home/Slides</h4>
		        </div>
		
		        <div class="modal-body p-xs pull-left full-width">
			        <form name="ingresar" class="form_ingresar" method="post" enctype="multipart/form-data" action="<?php echo base_url('cms-v2/paginas/ingresar_informacion/'); ?>">
	                   <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Título</label>
		                    <input type="text" name="titulo" id="titulo" value="" class="form-control">
		                </div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Link</label>
		                    <input type="text" name="subtitulo" id="subtitulo" value="" class="form-control">
		                </div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Texto</label>
								<div class="ibox-content no-padding">
								    <textarea class="form-control summernote2" name="contenido1" id="contenido1" rows="4"  value=""></textarea></div>
						</div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Orden</label>
		                    <input type="text" name="orden" id="orden" value="" class="form-control">
		                </div>
	                    <div class="col-sm-6">
	                    	<label class="control-label pull-left">Estado</label>
                            <select name="estado" class="form-control m-b">
                                <option value="1">Inactivo</option>
                                <option value="3">Activo</option>
                            </select>
	                    </div>
	                    <div class="col-sm-6">
	                    	<label class="control-label pull-left">Texto</label>
                            <select name="sin_texto" class="form-control m-b">
                                <option value="1">Publicar</option>
                                <option value="0">No publicar</option>
                            </select>
	                    </div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Imagen</label>
                            <div class="fileinput fileinput-new input-group p-xs form-control" data-provides="fileinput">
	                            <input type="file" name="imagen">
	                    	</div>
	                    	<small class="font-italic">Recomendado 1920 x 600 píxeles. Recuerde que el peso debe ser el mínimo posible para no afectar la descarga del site.</small>
	                    </div>

	                    <div class="col-sm-12 m-t-sm">
			            	<input type="hidden" name="id" id="id" value="">
			            	<input type="hidden" name="id_tipo" id="id_tipo" value="8">
			            	<input type="hidden" name="id_imagen_tipo" id="tipo" value="18">
			            	<input type="hidden" name="idioma" id="idioma" value="">
			            	<input type="hidden" name="medidas" value="1920x600">
			            	<input type="hidden" name="id_contenido" id="id_contenido" value="">
			                <input type="submit" class="btn btn-primary pull-right" value="Ingresar">
	                    </div>
		            </form>
		        </div>
	  		</div>
		</div>
	</div>

<!-- Modal Ingresar Banners Internos -->
    <div class="modal inmodal" id="myModalIngresarInformacion605" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog modal-lg">
	   		<div class="modal-content animated">
		        <div class="modal-header">
		            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
		            <h4 class="modal-title">Ingresar contenido de<br>Home/Banners Internos</h4>
		        </div>
		        <div class="modal-body p-xs pull-left full-width">
			        <form name="ingresar" class="form_ingresar" method="post" enctype="multipart/form-data" action="<?php echo base_url('cms-v2/paginas/ingresar_informacion/'); ?>">
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Título</label>
		                    <input type="text" name="titulo" id="titulo" value="" class="form-control">
		                </div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Texto</label>
		                    <div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido1" rows="4"  value=""></textarea></div>
						</div>

	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Texto Adicional</label>
		                    <div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido2" rows="4"  value=""></textarea></div>
						</div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Link</label>
		                    <input type="text" name="subtitulo" id="subtitulo" value="" class="form-control">
		                </div>
	                    <div class="col-sm-6">
	                    	<label class="control-label pull-left">Estado </label>
                            <select name="estado" id="estado" class="form-control m-b">
                                <option value="1">Inactivo</option>
                                <option value="3">Activo</option>
                            </select>
	                    </div>
		                <div class="col-sm-6 m-b-sm">
		                    <label class="control-label pull-left">Orden</label>
		                    <input type="text" name="orden" id="orden" value="" class="form-control">
		                </div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Imagen</label>
                            <div class="fileinput fileinput-new input-group p-xs form-control" data-provides="fileinput"><input type="file" name="imagen"></div>
						</div>
	                    <div class="col-sm-12 m-t-sm">
			            	<input type="hidden" name="id" id="id" value="">
			            	<input type="hidden" name="id_tipo" id="id_tipo" value="605">
			            	<input type="hidden" name="idioma" id="idioma" value="">
			            	<input type="hidden" name="medidas" value="1680x700">
			            	<input type="hidden" name="id_imagen_tipo" value="13">
			            	<input type="hidden" name="id_contenido" id="id_contenido" value="">
			                <input type="submit" class="btn btn-primary pull-right" value="Ingresar">
	                    </div>
		            </form>
		        </div>
	  		</div>
		</div>
	</div>

<!-- Modal Ingresar Our commitments -->
    <div class="modal inmodal" id="myModalIngresarInformacion359" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog modal-lg">
	   		<div class="modal-content animated">
		        <div class="modal-header">
		            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
		            <h4 class="modal-title">Ingresar contenido de<br>Home/Our commitments</h4>
		        </div>
		        <div class="modal-body p-xs pull-left full-width">
			        <form name="ingresar" class="form_ingresar" method="post" enctype="multipart/form-data" action="<?php echo base_url('cms-v2/paginas/ingresar_informacion/'); ?>">
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Título</label>
		                    <input type="text" name="titulo" id="titulo" value="" class="form-control">
		                </div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Texto</label>
		                    <div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido1" rows="4"  value=""></textarea></div>
						</div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Link</label>
		                    <input type="text" name="subtitulo" id="subtitulo" value="" class="form-control">
		                </div>
	                    <div class="col-sm-6">
	                    	<label class="control-label pull-left">Estado</label>
                            <select name="estado" id="estado" class="form-control m-b">
                                <option value="1">Inactivo</option>
                                <option value="3">Activo</option>
                            </select>
	                    </div>
		                <div class="col-sm-6 m-b-sm">
		                    <label class="control-label pull-left">Orden</label>
		                    <input type="text" name="orden" id="orden" value="" class="form-control">
		                </div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Imagen</label>
                            <div class="fileinput fileinput-new input-group p-xs form-control" data-provides="fileinput"><input type="file" name="imagen"></div>
						</div>
	                    <div class="col-sm-12 m-t-sm">
			            	<input type="hidden" name="id" id="id" value="">
			            	<input type="hidden" name="id_tipo" id="id_tipo" value="359">
			            	<input type="hidden" name="idioma" id="idioma" value="">
			            	<input type="hidden" name="medidas" value="120x55">
			            	<input type="hidden" name="id_imagen_tipo" value="13">
			            	<input type="hidden" name="id_contenido" id="id_contenido" value="">
			                <input type="submit" class="btn btn-primary pull-right" value="Ingresar">
	                    </div>
		            </form>
		        </div>
	  		</div>
		</div>
	</div>

<!-- Modal Ingresar Our commitments -->
    <div class="modal inmodal" id="myModalIngresarInformacion362" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog modal-lg">
	   		<div class="modal-content animated">
		        <div class="modal-header">
		            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
		            <h4 class="modal-title">Ingresar contenido de<br>Home/Suggested accommodation</h4>
		        </div>
		        <div class="modal-body p-xs pull-left full-width">
			        <form name="ingresar" class="form_ingresar" method="post" enctype="multipart/form-data" action="<?php echo base_url('cms-v2/paginas/ingresar_informacion/'); ?>">
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Título</label>
		                    <input type="text" name="titulo" id="titulo" value="" class="form-control">
		                </div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Texto</label>
		                    <div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido1" rows="4"  value=""></textarea></div>
						</div>
	                    <div class="col-sm-6 m-b-sm">
		                    <label class="control-label pull-left">Link</label>
		                    <input type="text" name="subtitulo" id="subtitulo" value="" class="form-control">
		                </div>
	                    <div class="col-sm-6 m-b-sm">
		                    <label class="control-label pull-left">Icono</label>
                            <select name="subtitulo" id="subtitulo" class="form-control m-b">
                                <option value="fa-bed">Cama</option>
                                <option value="fa-car-side">Auto</option>
                                <option value="fa-hands">Manos</option>
                                <option value="fa-suitcase">Maletín</option>
                                <option value="fa-hospital">Hospital</option>
                                <option value="fa-user">Usuario</option>
                                <option value="fa-tree">Árbol</option>
                                <option value="fa-pizza-slice">Pizza</option>
                                <option value="fa-music">Música</option>
                                <option value="fa-microphone">Micrófono</option>
                                <option value="fa-subway">Subte</option>
                                <option value="fa-sun-cloud">Clima</option>
                            </select>
						</div>
	                    <div class="col-sm-6">
	                    	<label class="control-label pull-left">Estado</label>
                            <select name="estado" id="estado" class="form-control m-b">
                                <option value="1">Inactivo</option>
                                <option value="3">Activo</option>
                            </select>
	                    </div>
		                <div class="col-sm-6 m-b-sm">
		                    <label class="control-label pull-left">Orden</label>
		                    <input type="text" name="orden" id="orden" value="" class="form-control">
		                </div>
	                    <div class="col-sm-12 m-t-sm">
			            	<input type="hidden" name="id" id="id" value="">
			            	<input type="hidden" name="id_tipo" id="id_tipo" value="362">
			            	<input type="hidden" name="idioma" id="idioma" value="">
			            	<input type="hidden" name="id_contenido" id="id_contenido" value="">
			                <input type="submit" class="btn btn-primary pull-right" value="Ingresar">
	                    </div>
		            </form>
		        </div>
	  		</div>
		</div>
	</div>

<!-- Modal Ingresar Customer Testimonials -->
    <div class="modal inmodal" id="myModalIngresarInformacion365" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog modal-lg">
	   		<div class="modal-content animated">
		        <div class="modal-header">
		            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
		            <h4 class="modal-title">Ingresar contenido de<br>Home/Customer Testimonials</h4>
		        </div>
		        <div class="modal-body p-xs pull-left full-width">
			        <form name="ingresar" class="form_ingresar" method="post" enctype="multipart/form-data" action="<?php echo base_url('cms-v2/paginas/ingresar_informacion/'); ?>">
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Nombre</label>
		                    <input type="text" name="titulo" id="titulo" value="" class="form-control">
		                </div>
	                    <div class="col-sm-6 m-b-sm">
		                    <label class="control-label pull-left">Ocupación</label>
		                    <input type="text" name="subtitulo" id="subtitulo" value="" class="form-control">
		                </div>
	                    <div class="col-sm-6 m-b-sm">
		                    <label class="control-label pull-left">Estrellas</label>
		                    <input type="text" name="texto_adicional" id="texto_adicional" value="" class="form-control">
		                </div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Texto</label>
		                    <div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido1" rows="4"  value=""></textarea></div>
						</div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Imagen</label>
                            <div class="fileinput fileinput-new input-group p-xs form-control" data-provides="fileinput"><input type="file" name="imagen"></div>
						</div>
	                    <div class="col-sm-6">
	                    	<label class="control-label pull-left">Estado</label>
                            <select name="estado" id="estado" class="form-control m-b">
                                <option value="1">Inactivo</option>
                                <option value="3">Activo</option>
                            </select>
	                    </div>
		                <div class="col-sm-6 m-b-sm">
		                    <label class="control-label pull-left">Orden</label>
		                    <input type="text" name="orden" id="orden" value="" class="form-control">
		                </div>
	                    <div class="col-sm-12 m-t-sm">
			            	<input type="hidden" name="id" id="id" value="">
			            	<input type="hidden" name="id_tipo" id="id_tipo" value="365">
			            	<input type="hidden" name="idioma" id="idioma" value="">
			            	<input type="hidden" name="medidas" value="140x140">
			            	<input type="hidden" name="id_imagen_tipo" value="13">
			            	<input type="hidden" name="id_contenido" id="id_contenido" value="">
			                <input type="submit" class="btn btn-primary pull-right" value="Ingresar">
	                    </div>
		            </form>
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
                                       