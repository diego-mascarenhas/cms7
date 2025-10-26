<style>
.note-editor.note-frame { border:0;}
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
									$parametros1['id_tipo'] = 831;
									$donaciones = $this->Paginas_model->getContenidoAdicionalIdioma($parametros1);

									$parametros2['id'] = $detalle['id'];
									$parametros2['idioma'] = $idioma['extension'];
									$parametros2['id_tipo'] = 885;
									$acciones = $this->Paginas_model->getContenidoAdicionalIdioma($parametros2);
								}
							?>
	                            <div class="panel-body">
								 <div class="row">
								 <input type="hidden" name="titulo_<?php echo $idioma['extension'];?>" value="Home">
					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Encabezado</h2>
					                 	<div class="form-group m-b-md pull-left full-width m-t-md">
											<label class="col-sm-1 control-label">Título</label>
											<div class="col-sm-4">
												<div class="input-group"><input type="text" name="titulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título que se mostrará sobre la imagen del encabezado." title=""> <i class="fa fa-question"></i></button></div>
											</div>
											<label class="col-sm-2 control-label text-right">Imagen</label>
					                        <div class="col-sm-5">
			                                   <div class="fileinput fileinput-new input-group" data-provides="fileinput"><input type="file" class="form-control" name="imagen_<?php echo $idioma['extension'];?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen de fondo del encabezado, sobre la cual se muestra el título de la sección. Tamaño recomendado 1780x430 píxeles o proporcionales." title=""> <i class="fa fa-question"></i></button></div>

				                            <?php if($imagen) { ?>
				                            	<img src="<?php echo base_url('multimedia/thumbs/'.$imagen['imagen_breadcrumb']);?>" class="m-b-xs" style="width:100%;">
				                            <?php } ?>
					                        </div>
					                 	</div>
					                </div>
					                
					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Contenido</h2>
					                 	<div class="form-group m-b-md pull-left full-width m-t-md">
											<label class="col-sm-1 control-label">Título</label>
											<div class="col-sm-5">
												<div class="input-group"><input type="text" name="texto_adicional_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['texto_adicional'])) ? $item['texto_adicional']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del contenido." title=""> <i class="fa fa-question"></i></button></span></div>
											</div>

					                 	<div class="form-group m-b-md pull-left full-width">
											<div class="col-sm-12">
												<div class="ibox-title bg-muted m-t-md"><h5>Texto</h5><button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Texto del contenido." title=""> <i class="fa fa-question"></i></button></div>
												<div class="ibox-content no-padding"><textarea class="form-control summernote" name="contenido4_<?php echo $idioma['extension'];?>" rows="3"><?php echo(isset($item['contenido4'])) ? $item['contenido4']: null?></textarea></div>
											</div>
					                 	</div>
					                </div>
					                <div class="col-lg-12 p-xxs">
						            	<div class="pull-left full-width">
											<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Items donaciones<?php if($donaciones) { ?> <a title="Ordenar" href="<?php echo base_url('cms-v2/paginas/ordenar/'.$detalle['id'].'/831/'.$idioma['extension']);?>" class="sepV_a btn btn-primary btn-sm red pull-right m-l-sm"><i class="fa fa-plus-circle"></i> Ordenar Items</a> <?php }?><a title="Ingresar" id="item" href="#" data-toggle="modal" data-seccion="Ingresar ítem para donaciones" data-subtitulo="Cargo" data-id="759" data-idioma="<?php echo $idioma['extension'];?>" data-target="#myModalIngresar" class="sepV_a btn btn-primary btn-sm red pull-right"><i class="fa fa-plus-circle"></i> Ingresar Item</a></h2>
							                <?php  if($donaciones) { foreach($donaciones as $donacion) { ?>	
								                <div class="col-md-6 col-lg-3 box-items">
													<div class="contact-box<?php echo ($donacion['estado'] == 1) ? ' bg-inactiva' : '';?>">
									                    <div class="col-sm-12">
									                        <div class="text-center">
									                            <?php if($donacion['imagen']) { ?>
									                            	<img src="<?php echo base_url('multimedia/thumbs/'.$donacion['imagen']);?>" title="<?php echo $donacion['titulo']; ?>" alt="<?php echo $donacion['titulo'];?>" class="m-b-xs">
									                            <?php } ?>
									                        </div>
									                    </div>
									                    <div class="col-sm-12">
									                        <h3><strong><?php echo ellipsize($donacion['titulo'],25, 1).' '.$donacion['subtitulo'];?></strong></h3>
									                    </div>
									                    <div class="col-sm-12 pull-right text-right">
				                                            <a title="Modificar" href="<?php echo base_url('cms-v2/paginas/modificar_informacion/'.$detalle['id'].'/'.$donacion['id']);?>" class="btn btn-sm btn-danger"><i class="fa fa-edit"></i> Modificar</a>
				                                            <a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $donacion['titulo'];?>?" data-estado="<?php echo $donacion['estado'];?>" data-id_contenido="<?php echo $detalle['id'];?>" data-id="<?php echo $donacion['id'];?>" data-target="#myModalEliminarInformacion" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i> Eliminar</a>
									                    </div>
									                </div>
									             </div>
											<?php } } else { echo '<p class="p-xs">No se encontraron resultados</p>';} ?>
										</div>
					                </div>

					                <div class="col-lg-12 p-xxs">
						            	<div class="pull-left full-width">
											<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Acciones<?php if($donaciones) { ?> <a title="Ordenar" href="<?php echo base_url('cms-v2/paginas/ordenar/'.$detalle['id'].'/885/'.$idioma['extension']);?>" class="sepV_a btn btn-primary btn-sm red pull-right m-l-sm"><i class="fa fa-plus-circle"></i> Ordenar Items</a> <?php }?><a title="Ingresar" id="item" href="#" data-toggle="modal" data-seccion="Ingresar acciones" data-subtitulo="Acciones" data-id="885" data-idioma="<?php echo $idioma['extension'];?>" data-target="#myModalIngresar885" class="sepV_a btn btn-primary btn-sm red pull-right"><i class="fa fa-plus-circle"></i> Ingresar Item</a></h2>
					                 	<div class="form-group m-b-md pull-left full-width m-t-md">
											<label class="col-sm-2 control-label text-right">Texto acciones <button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Título de las acciones." title=""> <i class="fa fa-question"></i></button></label>
											<div class="col-sm-4 m-b-md">
												<div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido6_<?php echo $idioma['extension'];?>" rows="2"><?php echo(isset($item['contenido6'])) ? $item['contenido6']: null?></textarea></div>
											</div>

											<label class="col-sm-2 control-label text-right">Texto final <button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Texto final de la sección." title=""> <i class="fa fa-question"></i></button></label>
											<div class="col-sm-4 m-b-md">
												<div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido7_<?php echo $idioma['extension'];?>" rows="2"><?php echo(isset($item['contenido7'])) ? $item['contenido7']: null?></textarea></div>
											</div>
										</div>

							                <?php  if($acciones) { foreach($acciones as $accion) { ?>	
								                <div class="col-md-6 col-lg-3 box-items">
													<div class="contact-box<?php echo ($accion['estado'] == 1) ? ' bg-inactiva' : '';?>">
									                    <div class="col-sm-12">
									                        <div class="text-center">
									                            <?php if($accion['imagen']) { ?>
									                            	<img src="<?php echo base_url('multimedia/thumbs/'.$accion['imagen']);?>" title="<?php echo $accion['titulo']; ?>" alt="<?php echo $accion['titulo'];?>" class="m-b-xs" style="max-width:40%;">
									                            <?php } ?>
									                        </div>
									                    </div>
									                    <div class="col-sm-12">
									                        <h3 style="height:50px;"><strong><?php echo ellipsize($accion['titulo'],25, 1).' '.$accion['subtitulo'];?></strong></h3>
									                    </div>
									                    <div class="col-sm-12 pull-right text-right	">
				                                            <a title="Modificar" href="<?php echo base_url('cms-v2/paginas/modificar_informacion/'.$detalle['id'].'/'.$accion['id']);?>" class="btn btn-sm btn-danger"><i class="fa fa-edit"></i> Modificar</a>
				                                            <a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $accion['titulo'];?>?" data-estado="<?php echo $accion['estado'];?>" data-id_contenido="<?php echo $detalle['id'];?>" data-id="<?php echo $accion['id'];?>" data-target="#myModalEliminarInformacion" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i> Eliminar</a>
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
                     <?php echo form_close();?>
                     </div>
                 </div>
             </div>                 
         </div>
     </div>     

<!-- Modal Ingresar -->
<div class="modal inmodal" id="myModalIngresar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
   		<div class="modal-content animated">
	        <div class="modal-body p-xs pull-left full-width">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title">Ingresar Item</h4>
		        <form name="ingresar" class="form_ingresar" method="post" enctype="multipart/form-data" action="<?php echo base_url('cms-v2/paginas/ingresar_informacion/'); ?>">
		            <input type="hidden" name="idioma" id="idioma" value="es">
		            <input type="hidden" name="id_contenido" id="id_contenido" value="1155">
                    <input type="hidden" name="id_tipo" id="seccion" value="831">
                    <input type="hidden" name="id_imagen_tipo" value="13">
                    <input type="hidden" name="medidas" value="200x240">
                    <label class="control-label col-sm-3">Título 1</label>
                    <div class="col-sm-9 m-b-sm">
	                    <div class="input-group"><input type="text" name="titulo" id="titulo" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del ítem en primer color." title=""> <i class="fa fa-question"></i></button></span></div>
                    </div>

                    <label class="control-label col-sm-3">Título 2</label>
                    <div class="col-sm-9 m-b-sm">
	                    <div class="input-group"><input type="text" name="subtitulo" id="subtitulo" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del ítem en segundo color." title=""> <i class="fa fa-question"></i></button></span></div>
                    </div>
                    <label class="control-label col-sm-3">Texto Link</label>
                    <div class="col-sm-9 m-b-sm">
	                    <div class="input-group"><input type="text" name="texto_adicional" id="texto_adicional" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Texto que se mostrará en el botón." title=""> <i class="fa fa-question"></i></button></span></div>
                    </div>

	                <label class="control-label col-sm-3">Link</label>
                    <div class="col-sm-9 m-b-sm">
	                    <div class="input-group"><input type="text" name="contenido1" id="contenido1" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Link externo optativo. Debe ingresar url completa, por ej.: https://fundacionbomberos.org.ar/dona." title=""> <i class="fa fa-question"></i></button></span></div>
                    </div>

	                <label class="control-label col-sm-3">Imagen</label>
                    <div class="col-sm-9 m-b-sm">
                        <div class="fileinput fileinput-new form-control" data-provides="fileinput"><input type="file" name="imagen"><span class="input-group-btn" style="position: absolute;right: 43px;top: 0;"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Imagen jpg, gif o png, recomendado 200x240 píxeles." title=""> <i class="fa fa-question"></i></button></span></div>
                    </div>

	                <label class="control-label col-sm-3">Orden</label>
                    <div class="col-sm-9 m-b-sm">
	                    <div class="input-group"><input type="text" name="orden" id="orden" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en que se mostrará el ítem." title=""> <i class="fa fa-question"></i></button></span></div>
                    </div>
	                <label class="control-label col-sm-3">Estado</label>
                    <div class="col-sm-9 m-b-md">
                        <div class="input-group">
	                        <select name="estado" id="estado" class="form-control m-b">
	                            <option value="1">Inactivo</option>
	                            <option value="3">Activo</option>
	                        </select>
	                        <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Activo, se muestra en la web, Inactivo, no se muestra." title=""> <i class="fa fa-question"></i></button></span>
	                    </div>
                    </div>

                    <div class="col-sm-12">
		                <input type="submit" class="btn btn-primary pull-right" value="Ingresar">
                    </div>
	            </form>
	        </div>
  		</div>
	</div>
</div>

<!-- Modal Ingresar -->
<div class="modal inmodal" id="myModalIngresar885" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
   		<div class="modal-content animated">
	        <div class="modal-body p-xs pull-left full-width">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title">Ingresar Item</h4>
		        <form name="ingresar" class="form_ingresar" method="post" enctype="multipart/form-data" action="<?php echo base_url('cms-v2/paginas/ingresar_informacion/'); ?>">
		            <input type="hidden" name="idioma" id="idioma" value="es">
		            <input type="hidden" name="id_contenido" id="id_contenido" value="1155">
                    <input type="hidden" name="id_tipo" id="seccion" value="885">
                    <input type="hidden" name="id_imagen_tipo" value="13">
                    <input type="hidden" name="medidas" value="400x400">
                    <label class="control-label col-sm-3">Título</label>
                    <div class="col-sm-9 m-b-sm">
	                    <div class="input-group"><input type="text" name="titulo" id="titulo" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del ítem." title=""> <i class="fa fa-question"></i></button></span></div>
                    </div>

                    <label class="control-label col-sm-3">Categoría</label>
                    <div class="col-sm-9 m-b-sm">
	                    <div class="input-group"><input type="text" name="subtitulo" id="subtitulo" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Categoría del ítem." title=""> <i class="fa fa-question"></i></button></span></div>
                    </div>
                    <label class="control-label col-sm-3">Fondo Categoría</label>
                    <div class="col-sm-9 m-b-sm">
                    	<div class="input-group"><input type="text" name="texto_adicional" id="texto_adicional" value="" class="form-control demo1 colorpicker-element"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Color de fondo de la categoría." title=""> <i class="fa fa-question"></i></button></span></div>
                    </div>
                    <label class="control-label col-sm-3">Texto <button type="button" class="btn btn-primary m-l-md btn-sm btn-circle" data-toggle="tooltip" data-placement="top" data-original-title="Información sobre la acción." title=""> <i class="fa fa-question"></i></button></label>
                    <div class="col-sm-9 m-b-sm">
						<div class="ibox-content no-padding">
						<textarea class="form-control summernote2" name="contenido1" rows="4"  value=""></textarea></div>
					</div>
	                <label class="control-label col-sm-3">Imagen</label>
                    <div class="col-sm-9 m-b-sm">
                        <div class="fileinput fileinput-new form-control" data-provides="fileinput"><input type="file" name="imagen"><span class="input-group-btn" style="position: absolute;right: 43px;top: 0;"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Imagen jpg, gif o png, recomendado 400x400 píxeles." title=""> <i class="fa fa-question"></i></button></span></div>
                    </div>

	                <label class="control-label col-sm-3">Orden</label>
                    <div class="col-sm-9 m-b-sm">
	                    <div class="input-group"><input type="text" name="orden" id="orden" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en que se mostrará el ítem." title=""> <i class="fa fa-question"></i></button></span></div>
                    </div>
	                <label class="control-label col-sm-3">Estado</label>
                    <div class="col-sm-9 m-b-md">
                        <div class="input-group">
	                        <select name="estado" id="estado" class="form-control m-b">
	                            <option value="1">Inactivo</option>
	                            <option value="3">Activo</option>
	                        </select>
	                        <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Activo, se muestra en la web, Inactivo, no se muestra." title=""> <i class="fa fa-question"></i></button></span>
	                    </div>
                    </div>

                    <div class="col-sm-12">
		                <input type="submit" class="btn btn-primary pull-right" value="Ingresar">
                    </div>
	            </form>
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
     var contenido = $(e.relatedTarget).data().contenido;
     var idioma = $(e.relatedTarget).data().idioma;
      $(e.currentTarget).find('#id').val(id);
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
                                      
