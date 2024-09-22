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
			<input type="hidden" name="id_imagen_tipo2" value="13">
			<input type="hidden" name="id_imagen_tipo3" value="20">
			<input type="hidden" name="medidas3" value="640x500">
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
									$imagen_seccion = $this->Paginas_model->getMedia($detalle['id'], $idioma['extension'], 13);
									$imagen_seccion_2 = $this->Paginas_model->getMedia($detalle['id'], $idioma['extension'], 20);
								}
							?>
	                            <div class="panel-body">
								 <div class="row">
								 <input type="hidden" name="titulo_<?php echo $idioma['extension'];?>" value="Home">
					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Encabezado</h2>
					                 	<div class="form-group m-b-md pull-left full-width m-t-md">
											<label class="col-sm-1 control-label">Tipo</label>
						                 	<div class="col-sm-4">
							                 	<div class="input-group">
								                 	<select name="subtitulo_<?php echo $idioma['extension'];?>" class="form-control">
								                 		<option value="1">Nuestros Programas</option>
								                 		<option value="2">Programas Compartidos</option>
								                 	</select>
													<span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Selector de tipo de programa, que define si se muestra en Nuestros Programas o Programas Compartidos." title=""> <i class="fa fa-question"></i></button></span>
							                 	</div>
						                 	</div>
					                 	</div>
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
					                 	<div class="form-group m-b-md pull-left full-width">
											<label class="col-sm-1 control-label">Miniatura</label>
					                        <div class="col-sm-5">
			                                   <div class="fileinput fileinput-new input-group" data-provides="fileinput"><input type="file" class="form-control" name="imagen_seccion3_<?php echo $idioma['extension'];?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen en jpg, gif y png, tamaño recomendado 640x500 píxeles. Recuerde que el peso debe ser el mínimo posible para no afectar la descarga del site." title=""> <i class="fa fa-question"></i></button></div>
				                            <?php if($imagen_seccion_2) { ?>
				                            	<img src="<?php echo base_url('multimedia/thumbs/'.$imagen_seccion_2['imagen_breadcrumb']);?>" class="m-b-xs" style="width:auto;max-height:100px;">
				                            <?php } ?>
					                        </div>
											<label class="col-sm-1 control-label">Logo</label>
					                        <div class="col-sm-5">
			                                   <div class="fileinput fileinput-new input-group" data-provides="fileinput"><input type="file" class="form-control" name="imagen_seccion_<?php echo $idioma['extension'];?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Logo del programa, imagen en jpg, gif y png, tamaño recomendado 220x75 píxeles." title=""> <i class="fa fa-question"></i></button></div>
					                        
				                            <?php if($imagen_seccion) { ?>
				                            	<img src="<?php echo base_url('multimedia/thumbs/'.$imagen_seccion['imagen_breadcrumb']);?>" class="m-b-xs" style="width:auto;max-height:100px;">
				                            <?php } ?>
					                        </div>

											<div class="col-sm-12">
												<div class="ibox-title bg-muted m-t-md"><h5>Texto</h5><button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Información sobre el programa." title=""> <i class="fa fa-question"></i></button></div>
												<div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido1_<?php echo $idioma['extension'];?>" rows="3"><?php echo(isset($item['contenido1'])) ? $item['contenido1']: null?></textarea></div>
											</div>
					                 	</div>
					                </div>

					                <?php 
						                if(!empty($categorias)) {
										foreach($categorias as $categoria) { ?>
					                    <div class="col-sm-12 p-xxs">
						                    <div class="pull-left full-width">
							                	<h2 class="bg-muted p-xs pull-left full-width"><?php echo $categoria['seccion']; ?><a title="Ordenar" href="<?php echo base_url('cms-v2/paginas/ordenar/'.$detalle['id'].'/'.$categoria['id'].'/'.$idioma['extension']);?>" class="sepV_a btn btn-primary btn-sm red pull-right m-l-sm"><i class="fa fa-sort-circle"></i> Ordenar ítems</a> 
							                	<a title="Ingresar" id="item" href="#" data-toggle="modal" data-categoria="<?php echo $categoria['seccion']; ?>" data-seccion="<?php echo $categoria['id']; ?>" data-id_contenido="<?php echo $detalle['id'];?>" data-idioma="<?php echo $idioma['extension'];?>" data-target="<?php echo($categoria['url'] != 'videos') ? '#myModalIngresarC' : '#myModalIngresarV'; ?>" class="sepV_a btn btn-primary btn-sm red pull-right"><i class="fa fa-plus-circle"></i> Ingresar ítem</a></h2>
											<div class="col-sm-12 m-t-md">
												<?php if($categoria['url'] != 'videos'){ ?>
												<label class="col-sm-1 control-label">Título</label>
												<div class="col-sm-5">
													<div class="input-group"><input type="text" name="contenido2_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['contenido2'])) ? $item['contenido2']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del contenido Campañas." title=""> <i class="fa fa-question"></i></button></div>
												</div>
												<?php } else { ?>
												<label class="col-sm-1 control-label">Título</label>
												<div class="col-sm-5">
													<div class="input-group"><input type="text" name="contenido4_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['contenido4'])) ? $item['contenido4']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del contenido Videos." title=""> <i class="fa fa-question"></i></button></div>
												</div>
												<?php } ?>
											</div>

							                <?php 
												$parametros['id'] = $detalle['id'];
												$parametros['idioma'] = $idioma['extension'];
												$parametros['id_tipo'] = $categoria['id'];
												$miembros= $CI->Paginas_model->getContenidoAdicionalIdioma($parametros);
								               if(!empty($miembros)) {
												foreach($miembros as $miembro) { ?>	
								                <div class="col-md-6 col-lg-3 box-items m-t-md">
													<div class="contact-box<?php echo ($miembro['estado'] == 1) ? ' bg-inactiva' : '';?>">
									                    <div class="col-sm-12">
									                        <div class="text-center">
									                            <?php if($miembro['imagen']) { ?>
									                            	<img src="<?php echo base_url('multimedia/thumbs/'.$miembro['imagen']);?>" title="<?php echo $miembro['titulo']; ?>" alt="<?php echo $miembro['titulo'];?>" class="m-b-xs" style="height:200px;max-width:100%;">
									                            <?php } ?>
									                        </div>
									                    </div>
									                    <div class="col-sm-12">
									                        <h3><strong><?php echo ellipsize($miembro['titulo'],25, 1);?></strong></h3>
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
											<?php } } ?>
												</div>
											</div>
				                    <?php } } ?>	
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
<div class="modal inmodal" id="myModalIngresarC" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
   		<div class="modal-content animated">
	        <div class="modal-body p-xs pull-left full-width">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title full-width text-center">Ingresar Campaña</h4>
		        <form name="ingresar" class="form_ingresar" method="post" enctype="multipart/form-data" action="<?php echo base_url('cms-v2/paginas/ingresar_informacion/'); ?>">
		            <input type="hidden" name="idioma" id="idioma" value="">
		            <input type="hidden" name="id_contenido" id="id_contenido" value="">
                    <input type="hidden" name="id_tipo" id="seccion" value="">

                    <label class="control-label col-sm-3">Título</label>
                    <div class="col-sm-9 m-b-sm">
	                    <div class="input-group"><input type="text" name="titulo" id="titulo" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de la campaña." title=""> <i class="fa fa-question"></i></button></span></div>
                    </div>
                    <label class="control-label col-sm-3">Texto<button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Texto de la campaña." title=""> <i class="fa fa-question"></i></button></label>
                    <div class="col-sm-9 m-b-sm">
						<div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido1" id="contenido1" rows="4"  value=""></textarea></div>
                    </div>
                    <label class="control-label col-sm-3">Texto Link</label>
                    <div class="col-sm-9 m-b-sm">
	                    <div class="input-group"><input type="text" name="texto_adicional" id="texto_adicional" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Texto que se mostrará en el botón." title=""> <i class="fa fa-question"></i></button></span></div>
                    </div>

	                <label class="control-label col-sm-3">Link</label>
                    <div class="col-sm-9 m-b-sm">
	                    <div class="input-group"><input type="text" name="contenido2" id="contenido2" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Link externo optativo. Debe ingresar url completa, por ej.: https://fundacionbomberos.org.ar/quienes-somos." title=""> <i class="fa fa-question"></i></button></span></div>
                    </div>

	                <label class="control-label col-sm-3">Galería</label>
                    <div class="col-sm-9 m-b-sm">
						<div class="input-group"><?php echo form_dropdown('subtitulo', $media_proyectos, null, 'class="form-control m-b-sm"'); ?><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Selector de galería de imágenes que se mostrará al lado del texto de la campaña. El contenido de la misma se administra desde el módulo Multimedia." title=""> <i class="fa fa-question"></i></button></span></div>
                    </div>
                    
	                <label class="control-label col-sm-3">Orden</label>
                    <div class="col-sm-9 m-b-sm">
	                    <div class="input-group"><input type="text" name="orden" id="orden" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en que se mostrará el campaña." title=""> <i class="fa fa-question"></i></button></span></div>
	                    
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
<div class="modal inmodal" id="myModalIngresarV" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
   		<div class="modal-content animated">
	        <div class="modal-body p-xs pull-left full-width">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title full-width text-center">Ingresar Video</h4>
		        <form name="ingresar" class="form_ingresar" method="post" enctype="multipart/form-data" action="<?php echo base_url('cms-v2/paginas/ingresar_informacion/'); ?>">
		            <input type="hidden" name="idioma" id="idioma" value="">
		            <input type="hidden" name="id_contenido" id="id_contenido" value="">
                    <input type="hidden" name="id_tipo" id="seccion" value="">
                    <label class="control-label col-sm-3">Título</label>
                    <div class="col-sm-9 m-b-sm">
	                    <div class="input-group"><input type="text" name="titulo" id="titulo" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del ítem." title=""> <i class="fa fa-question"></i></button></span></div>
                    </div>
                    <label class="control-label col-sm-3">Texto<button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Texto del ítem." title=""> <i class="fa fa-question"></i></button></label>
                    <div class="col-sm-9 m-b-sm">
						<div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido1" id="contenido1" rows="4"  value=""></textarea></div>
                    </div>
	                <label class="control-label col-sm-3">Galería</label>
                    <div class="col-sm-9 m-b-sm">
						<div class="input-group"><?php echo form_dropdown('subtitulo', $media_proyectos, null, 'class="form-control m-b-sm"'); ?><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Selector de galería de imágenes que se mostrará al lado del texto del ítem. El contenido de la misma se administra desde el módulo Multimedia." title=""> <i class="fa fa-question"></i></button></span></div>
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

<script src="<?php echo base_url('assets/js/plugins/summernote/summernote.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/summernote-grid.js'); ?>"></script>
<script>
$('.summernote').summernote({
  height: 150,   
  placeholder: 'Ingresar texto ...'});

$('.summernote2').summernote({
	placeholder: 'Ingrese texto...',
	height: 120,
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
 var seccion = $(e.relatedTarget).data().seccion;
 var categoria = $(e.relatedTarget).data().categoria;
 var id_contenido = $(e.relatedTarget).data().id_contenido;
 var contenido = $(e.relatedTarget).data().contenido;
 var titulo = $(e.relatedTarget).data().titulo;
 var idioma = $(e.relatedTarget).data().idioma;
  $(e.currentTarget).find('#id').val(id);
  $(e.currentTarget).find('#seccion').val(seccion);
  $(e.currentTarget).find('#categoria').val(categoria);
  $(e.currentTarget).find('#titulo').val(titulo);
  $(e.currentTarget).find('#id_contenido').val(id_contenido);
  $(e.currentTarget).find('#contenido').val(contenido);
  $(e.currentTarget).find('#idioma').val(idioma);
  $('#texto').html(seccion); 
});
</script>


