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
									$parametros['id_tipo'] = 759;
									$comites = $this->Paginas_model->getContenidoAdicionalIdioma($parametros);

									$parametros1['id'] = $detalle['id'];
									$parametros1['idioma'] = $idioma['extension'];
									$parametros1['id_tipo'] = 762;
									$vocales = $this->Paginas_model->getContenidoAdicionalIdioma($parametros1);

									$parametros2['id'] = $detalle['id'];
									$parametros2['idioma'] = $idioma['extension'];
									$parametros2['id_tipo'] = 765;
									$equipos = $this->Paginas_model->getContenidoAdicionalIdioma($parametros2);
								}
							?>
	                            <div class="panel-body">
								 <div class="row">
					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Contenido</h2>
					                 	<div class="form-group pull-left full-width m-t-md">
												<label class="col-sm-1 control-label">Título</label>
												<div class="col-sm-5">
													<div class="input-group"><input type="text" name="titulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del contenido." title=""> <i class="fa fa-question"></i></button></div>
												</div>
												

												<div class="col-sm-12">
													<div class="ibox-title bg-muted m-t-md"><h5>Texto Introducción</h5><button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Contenido." title=""> <i class="fa fa-question"></i></button></div>
													<div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido1_<?php echo $idioma['extension'];?>" rows="3"><?php echo(isset($item['contenido1'])) ? $item['contenido1']: null?></textarea></div>
												</div>
											</div>
					                 	</div>
					                </div>

					                <div class="col-lg-12 p-xxs">
						            	<div class="pull-left full-width">
											<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Comité Ejecutivo<?php if($comites) { ?> <a title="Ordenar" href="<?php echo base_url('cms-v2/paginas/ordenar/'.$detalle['id'].'/759/'.$idioma['extension']);?>" class="sepV_a btn btn-primary btn-sm red pull-right m-l-sm"><i class="fa fa-plus-circle"></i> Ordenar Items</a> <?php }?><a title="Ingresar" id="item" href="#" data-toggle="modal" data-seccion="Ingresar ítem para Comité Ejecutivo" data-subtitulo="Cargo" data-id="759" data-idioma="<?php echo $idioma['extension'];?>" data-target="#myModalIngresar759" class="sepV_a btn btn-primary btn-sm red pull-right"><i class="fa fa-plus-circle"></i> Ingresar Item</a></h2>
							                <?php  if($comites) { foreach($comites as $comite) { ?>	
								                <div class="col-md-6 col-lg-3 box-items">
													<div class="contact-box<?php echo ($comite['estado'] == 1) ? ' bg-inactiva' : '';?>">
									                    <div class="col-sm-12">
									                        <div class="text-center">
									                            <?php if($comite['imagen']) { ?>
									                            	<img src="<?php echo base_url('multimedia/thumbs/'.$comite['imagen']);?>" title="<?php echo $comite['titulo']; ?>" alt="<?php echo $comite['titulo'];?>" class="m-b-xs">
									                            <?php } ?>
									                        </div>
									                    </div>
									                    <div class="col-sm-12">
									                        <h3><strong><?php echo ellipsize($comite['titulo'],25, 1);?></strong></h3>
									                        <address>
									                            <div><?php echo character_limiter($comite['contenido1'], 58, '...');?></div>
									                        </address>
									                    </div>
									                    <div class="col-sm-12 pull-right text-right	">
				                                            <a title="Modificar" href="<?php echo base_url('cms-v2/paginas/modificar_informacion/'.$detalle['id'].'/'.$comite['id']);?>" class="btn btn-sm btn-danger"><i class="fa fa-edit"></i> Modificar</a>
				                                            <a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $comite['titulo'];?>?" data-estado="<?php echo $comite['estado'];?>" data-id_contenido="<?php echo $detalle['id'];?>" data-id="<?php echo $comite['id'];?>" data-target="#myModalEliminarInformacion" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i> Eliminar</a>
									                    </div>
									                </div>
									             </div>
											<?php } } else { echo '<p class="p-xs">No se encontraron resultados</p>';} ?>
										</div>
					                </div>

					                <div class="col-lg-12 p-xxs">
						            	<div class="pull-left full-width">
											<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Vocales<?php if($vocales) { ?> <a title="Ordenar" href="<?php echo base_url('cms-v2/paginas/ordenar/'.$detalle['id'].'/762/'.$idioma['extension']);?>" class="sepV_a btn btn-primary btn-sm red pull-right m-l-sm"><i class="fa fa-plus-circle"></i> Ordenar Items</a> <?php }?><a title="Ingresar" id="item" href="#" data-toggle="modal" data-seccion="Ingresar ítem para Vocales" data-id="762" data-subtitulo="Institución" data-idioma="<?php echo $idioma['extension'];?>" data-target="#myModalIngresar762" class="sepV_a btn btn-primary btn-sm red pull-right"><i class="fa fa-plus-circle"></i> Ingresar Item</a></h2>
							                <?php  if($vocales) { foreach($vocales as $vocal) { ?>	
								                <div class="col-md-6 col-lg-3 box-items">
													<div class="contact-box<?php echo ($vocal['estado'] == 1) ? ' bg-inactiva' : '';?>">
									                    <div class="col-sm-12">
									                        <div class="text-center">
									                            <?php if($vocal['imagen']) { ?>
									                            	<img src="<?php echo base_url('multimedia/thumbs/'.$vocal['imagen']);?>" title="<?php echo $vocal['titulo']; ?>" alt="<?php echo $vocal['titulo'];?>" class="m-b-xs">
									                            <?php } ?>
									                        </div>
									                    </div>
									                    <div class="col-sm-12">
									                        <h3><strong><?php echo ellipsize($vocal['titulo'],25, 1);?></strong></h3>
									                        <address>
									                            <div><?php echo character_limiter($vocal['contenido1'], 58, '...');?></div>
									                        </address>
									                    </div>
									                    <div class="col-sm-12 pull-right text-right	">
				                                            <a title="Modificar" href="<?php echo base_url('cms-v2/paginas/modificar_informacion/'.$detalle['id'].'/'.$vocal['id']);?>" class="btn btn-sm btn-danger"><i class="fa fa-edit"></i> Modificar</a>
				                                            <a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $vocal['titulo'];?>?" data-estado="<?php echo $vocal['estado'];?>" data-id_contenido="<?php echo $detalle['id'];?>" data-id="<?php echo $vocal['id'];?>" data-target="#myModalEliminarInformacion" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i> Eliminar</a>
									                    </div>
									                </div>
									             </div>
											<?php } } else { echo '<p class="p-xs">No se encontraron resultados</p>';} ?>
										</div>
					                </div>

					                <div class="col-lg-12 p-xxs">
						            	<div class="pull-left full-width">
											<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Equipo de Trabajo<?php if($equipos) { ?> <a title="Ordenar" href="<?php echo base_url('cms-v2/paginas/ordenar/'.$detalle['id'].'/765/'.$idioma['extension']);?>" class="sepV_a btn btn-primary btn-sm red pull-right m-l-sm"><i class="fa fa-plus-circle"></i> Ordenar Items</a> <?php }?><a title="Ingresar" id="item" href="#" data-toggle="modal" data-id="765" data-subtitulo="Cargo" data-seccion="Ingresar ítem para Equipo de Trabajo" data-idioma="<?php echo $idioma['extension'];?>" data-target="#myModalIngresar765" class="sepV_a btn btn-primary btn-sm red pull-right"><i class="fa fa-plus-circle"></i> Ingresar Item</a></h2>
							                <?php  if($equipos) { foreach($equipos as $equipo) { ?>	
								                <div class="col-md-6 col-lg-3 box-items">
													<div class="contact-box<?php echo ($equipo['estado'] == 1) ? ' bg-inactiva' : '';?>">
									                    <div class="col-sm-12">
									                        <div class="text-center">
									                            <?php if($equipo['imagen']) { ?>
									                            	<img src="<?php echo base_url('multimedia/thumbs/'.$equipo['imagen']);?>" title="<?php echo $equipo['titulo']; ?>" alt="<?php echo $equipo['titulo'];?>" class="m-b-xs">
									                            <?php } ?>
									                        </div>
									                    </div>
									                    <div class="col-sm-12">
									                        <h3><strong><?php echo ellipsize($equipo['titulo'],25, 1);?></strong></h3>
									                        <address>
									                            <div><?php echo character_limiter($equipo['contenido1'], 58, '...');?></div>
									                        </address>
									                    </div>
									                    <div class="col-sm-12 pull-right text-right	">
				                                            <a title="Modificar" href="<?php echo base_url('cms-v2/paginas/modificar_informacion/'.$detalle['id'].'/'.$equipo['id']);?>" class="btn btn-sm btn-danger"><i class="fa fa-edit"></i> Modificar</a>
				                                            <a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $equipo['titulo'];?>?" data-estado="<?php echo $equipo['estado'];?>" data-id_contenido="<?php echo $detalle['id'];?>" data-id="<?php echo $equipo['id'];?>" data-target="#myModalEliminarInformacion" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i> Eliminar</a>
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
<div class="modal inmodal" id="myModalIngresar759" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
   		<div class="modal-content animated">
	        <div class="modal-body p-xs pull-left full-width">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title text-center" id="texto"></h4>
		        <form name="ingresar" class="form_ingresar" method="post" enctype="multipart/form-data" action="<?php echo base_url('cms-v2/paginas/ingresar_informacion/'); ?>">
		            <input type="hidden" name="idioma" id="idioma" value="">
		            <input type="hidden" name="id_contenido" id="id_contenido" value="<?php echo $detalle['id'];?>">
                    <input type="hidden" name="id_tipo" id="id">
                    <input type="hidden" name="id_imagen_tipo" value="13">
                    <input type="hidden" name="medidas" value="200x200">
                    <div class="col-sm-12 m-t-md">
	                    <label class="control-label col-sm-2">Nombre</label>
	                    <div class="input-group col-sm-10"><input type="text" name="titulo" id="titulo" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Nombre del integrante." title=""> <i class="fa fa-question"></i></button></span></div>
                    </div>
                    <div class="col-sm-12 m-t-md">
	                    <label class="control-label col-sm-2" id="subtitulo"></label>
	                    <div class="input-group col-sm-10"><input type="text" name="subtitulo" id="subtitulo" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Cargo del integrante." title=""> <i class="fa fa-question"></i></button></span></div>
                    </div>

                    <div class="col-sm-12 m-b-sm">
	                    <label class="control-label pull-left">Texto<button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Texto del ítem." title=""> <i class="fa fa-question"></i></button></label>
						<div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido1" id="contenido1" rows="3"  value=""></textarea></div>
					</div>

                    <div class="col-sm-12 m-t-md">
	                    <label class="control-label col-sm-2">Imagen</label>
                    	<div class="input-group col-sm-10">
                            <div class="fileinput fileinput-new form-control" data-provides="fileinput"><input type="file" name="imagen"><span class="input-group-btn" style="position: absolute;right: 28px;top: 0;"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Imagen jpg, gif o png, recomendado 200x200 píxeles. Recuerde que el peso debe ser el mínimo posible para no afectar la descarga del site." title=""> <i class="fa fa-question"></i></button></span></div>
                    	</div>
                    </div>
                    <div class="col-sm-12 m-t-md">
	                    <label class="control-label col-sm-2">Orden</label>
	                    <div class="input-group col-sm-10"><input type="text" name="orden" id="orden" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en se mostrará el ítem." title=""> <i class="fa fa-question"></i></button></span></div>
                    </div>

                    <div class="col-sm-12 m-t-md">
                    	<label class="control-label col-sm-2">Estado</label>
                    	<div class="input-group col-sm-10">
                        <select name="estado" class="form-control m-b">
                            <option value="1">Inactivo</option>
                            <option value="3">Activo</option>
                        </select><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Si es activo se muestra en la web, si es inactivo no se muestra en la web." title=""> <i class="fa fa-question"></i></button></span>
                    	</div>
                    </div>

	                <input type="submit" class="btn btn-primary pull-right m-t-md" value="Ingresar">
	            </form>
	        </div>
  		</div>
	</div>
</div>

<div class="modal inmodal" id="myModalIngresar765" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
   		<div class="modal-content animated">
	        <div class="modal-body p-xs pull-left full-width">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title text-center" id="texto">Ingresar Equipo</h4>
		        <form name="ingresar" class="form_ingresar" method="post" enctype="multipart/form-data" action="<?php echo base_url('cms-v2/paginas/ingresar_informacion/'); ?>">
		            <input type="hidden" name="idioma" id="idioma" value="">
		            <input type="hidden" name="id_contenido" id="id_contenido" value="<?php echo $detalle['id'];?>">
                    <input type="hidden" name="id_tipo" id="id">
                    <input type="hidden" name="id_imagen_tipo" value="13">
                    <input type="hidden" name="medidas" value="200x200">
                    <div class="col-sm-12 m-t-md">
	                    <label class="control-label col-sm-2">Nombre</label>
	                    <div class="input-group col-sm-10"><input type="text" name="titulo" id="titulo" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Nombre del integrante." title=""> <i class="fa fa-question"></i></button></span></div>
                    </div>
                    <div class="col-sm-12 m-t-md">
	                    <label class="control-label col-sm-2" id="subtitulo"></label>
	                    <div class="input-group col-sm-10"><input type="text" name="subtitulo" id="subtitulo" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Cargo del integrante." title=""> <i class="fa fa-question"></i></button></span></div>
                    </div>

                    <div class="col-sm-12 m-b-sm">
	                    <label class="control-label pull-left">Texto<button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Texto del ítem." title=""> <i class="fa fa-question"></i></button></label>
						<div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido1" id="contenido1" rows="3"  value=""></textarea></div>
					</div>
                    
                    <div class="col-sm-12 m-t-md">
	                    <label class="control-label col-sm-2">Email</label>
	                    <div class="input-group col-sm-10"><input type="text" name="texto_adicional" id="texto_adicional" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Email del integrante." title=""> <i class="fa fa-question"></i></button></span></div>
                    </div>

                    <div class="col-sm-12 m-t-md">
	                    <label class="control-label col-sm-2">Imagen</label>
                    	<div class="input-group col-sm-10">
                            <div class="fileinput fileinput-new form-control" data-provides="fileinput"><input type="file" name="imagen"><span class="input-group-btn" style="position: absolute;right: 28px;top: 0;"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Imagen jpg, gif o png, recomendado 200x200 píxeles. Recuerde que el peso debe ser el mínimo posible para no afectar la descarga del site." title=""> <i class="fa fa-question"></i></button></span></div>
                    	</div>
                    </div>
                    <div class="col-sm-12 m-t-md">
	                    <label class="control-label col-sm-2">Orden</label>
	                    <div class="input-group col-sm-10"><input type="text" name="orden" id="orden" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en se mostrará el ítem." title=""> <i class="fa fa-question"></i></button></span></div>
                    </div>

                    <div class="col-sm-12 m-t-md">
                    	<label class="control-label col-sm-2">Estado</label>
                    	<div class="input-group col-sm-10">
                        <select name="estado" class="form-control m-b">
                            <option value="1">Inactivo</option>
                            <option value="3">Activo</option>
                        </select><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Si es activo se muestra en la web, si es inactivo no se muestra en la web." title=""> <i class="fa fa-question"></i></button></span>
                    	</div>
                    </div>

	                <input type="submit" class="btn btn-primary pull-right m-t-md" value="Ingresar">
	            </form>
	        </div>
  		</div>
	</div>
</div>

<div class="modal inmodal" id="myModalIngresar762" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
   		<div class="modal-content animated">
	        <div class="modal-body p-xs pull-left full-width">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title text-center" id="texto">Ingresar Vocal</h4>
		        <form name="ingresar" class="form_ingresar" method="post" enctype="multipart/form-data" action="<?php echo base_url('cms-v2/paginas/ingresar_informacion/'); ?>">
		            <input type="hidden" name="idioma" id="idioma" value="">
		            <input type="hidden" name="id_contenido" id="id_contenido" value="<?php echo $detalle['id'];?>">
                    <input type="hidden" name="id_tipo" id="id">
                    <input type="hidden" name="id_imagen_tipo" value="13">
                    <input type="hidden" name="medidas" value="120x120">
                    <div class="col-sm-12 m-t-md">
	                    <label class="control-label col-sm-2">Nombre</label>
	                    <div class="input-group col-sm-10"><input type="text" name="titulo" id="titulo" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Nombre del integrante." title=""> <i class="fa fa-question"></i></button></span></div>
                    </div>
                    <div class="col-sm-12 m-t-md">
	                    <label class="control-label col-sm-2">Institución</label>
	                    <div class="input-group col-sm-10"><input type="text" name="subtitulo" id="subtitulo" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Cargo del integrante." title=""> <i class="fa fa-question"></i></button></span></div>
                    </div>
                    <div class="col-sm-12 m-t-md">
	                    <label class="control-label col-sm-2">Imagen</label>
                    	<div class="input-group col-sm-10">
                            <div class="fileinput fileinput-new form-control" data-provides="fileinput"><input type="file" name="imagen"><span class="input-group-btn" style="position: absolute;right: 28px;top: 0;"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Imagen jpg, gif o png, recomendado 120x120 píxeles. Recuerde que el peso debe ser el mínimo posible para no afectar la descarga del site." title=""> <i class="fa fa-question"></i></button></span></div>
                    	</div>
                    </div>
                    <div class="col-sm-12 m-t-md">
	                    <label class="control-label col-sm-2">Orden</label>
	                    <div class="input-group col-sm-10"><input type="text" name="orden" id="orden" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en se mostrará el ítem." title=""> <i class="fa fa-question"></i></button></span></div>
                    </div>

                    <div class="col-sm-12 m-t-md">
                    	<label class="control-label col-sm-2">Estado</label>
                    	<div class="input-group col-sm-10">
                        <select name="estado" class="form-control m-b">
                            <option value="1">Inactivo</option>
                            <option value="3">Activo</option>
                        </select><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Si es activo se muestra en la web, si es inactivo no se muestra en la web." title=""> <i class="fa fa-question"></i></button></span>
                    	</div>
                    </div>

	                <input type="submit" class="btn btn-primary pull-right m-t-md" value="Ingresar">
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
     var seccion = $(e.relatedTarget).data().seccion;
     var subtitulo = $(e.relatedTarget).data().subtitulo;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#contenido').val(contenido);
      $(e.currentTarget).find('#idioma').val(idioma);

	  //paso variables por id
	  $('#texto').html(seccion); 
	  $('#subtitulo').html(subtitulo); 
  });
</script>