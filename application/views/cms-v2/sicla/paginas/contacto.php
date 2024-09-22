<style>
.note-editor.note-frame { border:0;}
.note-editable .row {margin: 0px;}
.note-editable .row div {border: 1px dotted;}
.tooltip-inner {max-width: 250px;width: 250px;}
.contact-box { min-height: 220px;max-height: 300px; padding:20px 10px;display: flex;flex-direction: column;justify-content: center;}
.contact-box2 { min-height: 320px;max-height: 380px; }
.contact-box img { height: 100px; width:auto;}
.bg-inactiva {color: #a94442;background: #f2dede !important;border-color: #ebccd1;}
.box-items {display:flex; flex-direction: column;}
.box-items { padding-left:5px; padding-right:5px;}
.imagen-bc { height:200px;float: left; max-width:100%;padding-bottom: 24px;padding-right: 25px;}

@media (max-width:768px) { 
.imagen-bc { float: none;}
}
@media (max-width:1270px) { 
.contact-box .col-sm-12 { padding-left:5px;  padding-right:5px;}
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
            <form action="<?php echo base_url('cms-v2/paginas/modificar/'.$detalle['id']); ?>/" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
			<input type="hidden" name="id" value="<?php echo $detalle['id']; ?>">
			<input type="hidden" name="id_con_secciones" value="<?php echo $detalle['id_con_secciones']; ?>">
			<input type="hidden" name="id_imagen_tipo" value="13">
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
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Contenido General</h2>
					                 	<div class="form-group">
											<label class="col-sm-1 control-label">T&iacute;tulo</label>
											<div class="col-sm-5">
		                                        <div class="input-group">
													<input type="text" name="titulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo'] : null; ?>"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de la sección."> <i class="fa fa-question"></i></button>
			                                    </div>
											</div>
											<label class="col-sm-1 control-label">Subt&iacute;tulo</label>
											<div class="col-sm-5">
		                                        <div class="input-group">
													<input type="text" name="subtitulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['subtitulo'])) ? $item['subtitulo'] : null; ?>"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Subtítulo de la sección."> <i class="fa fa-question"></i></button>
			                                    </div>
											</div>
					                 	</div>

					                 	<?php if($detalle['id'] != 788){
					                 	 if($detalle['id'] != 794){?>
					                 	<div class="form-group pull-left m-t-md">
										    <!-- Imagenes Generales -->
											<label class="col-sm-12 col-md-12 col-lg-1 control-label">Imagen</label>
						                    <div class="col-sm-12 col-md-12 col-lg-5">
					                            <?php if(!empty($imagen)) { ?>
					                            <div class="col-sm-12">
					                            	<img src="/multimedia/thumbs/<?php echo $imagen['imagen_breadcrumb'];?>" class="imagen-bc">
					                            </div>
				                            	<?php } ?>
					                            <div class="col-sm-12">
		                                        	<div class="input-group">
						                            	<input type="file" name="imagen_<?php echo $idioma['extension'];?>" class="form-control"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen de la sección, en caso de corresponder."> <i class="fa fa-question"></i></button>
						                            </div>
					                            </div>
											</div>
										
										<?php } if($detalle['id_con_secciones'] == 554) { ?>
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <div class="col-md-12 col-lg-6">
												<div class="ibox-title bg-muted m-t-md"><h5>Introducción</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Introducción de la sección."> <i class="fa fa-question"></i></button></div>
												<div class="ibox-content no-padding">
												    <textarea class="form-control summernote" name="contenido1_<?php echo $idioma['extension'];?>" rows="3"><?php echo(isset($item['contenido1'])) ? $item['contenido1']: null?></textarea></div>
						                    </div>
						                    <div class="col-md-12 col-lg-6">
												<div class="ibox-title bg-muted m-t-md"><h5>Contenido</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Contenido de la sección."> <i class="fa fa-question"></i></button></div>
												<div class="ibox-content no-padding">
												    <textarea class="form-control summernote" name="contenido2_<?php echo $idioma['extension'];?>" rows="3"><?php echo(isset($item['contenido2'])) ? $item['contenido2']: null?></textarea></div>
						                    </div>
										</div>
										<?php } elseif($detalle['id_con_secciones'] == 548) { ?>
										<div class="form-group m-b-md pull-left full-width m-t-md">
						                    <div class="col-md-12 col-lg-6">
												<div class="ibox-title bg-muted m-t-md"><h5>Texto Números</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Contenido de la sección."> <i class="fa fa-question"></i></button></div>
												<div class="ibox-content no-padding">
												    <textarea class="form-control summernote" name="contenido1_<?php echo $idioma['extension'];?>" rows="3"><?php echo(isset($item['contenido1'])) ? $item['contenido1']: null?></textarea></div>
						                    </div>
						                    <div class="col-md-12 col-lg-6">
												<div class="ibox-title bg-muted m-t-md"><h5>Texto Steel Frame</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Introducción de la sección."> <i class="fa fa-question"></i></button></div>
												<div class="ibox-content no-padding">
												    <textarea class="form-control summernote" name="contenido2_<?php echo $idioma['extension'];?>" rows="3"><?php echo(isset($item['contenido2'])) ? $item['contenido2']: null?></textarea></div>
						                    </div>
										</div>

											<?php } else { ?>
						                    <div class="col-sm-12 col-md-12 col-lg-6">
												<div class="ibox-title bg-muted m-t-md"><h5>Contenido</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Texto de la sección, en caso de corresponder."> <i class="fa fa-question"></i></button></div>
												<div class="ibox-content no-padding">
												    <textarea class="form-control summernote" name="contenido1_<?php echo $idioma['extension'];?>" rows="3"><?php echo(isset($item['contenido1'])) ? $item['contenido1']: null?></textarea></div>
						                    </div>
										</div>
											<?php }  } ?>
					                  
					                <?php 
						                if(!empty($categorias)) {
										foreach($categorias as $categoria) { ?>

					                    <div class="col-sm-12 p-xxs m-t-md">
						                    <div class="pull-left full-width">
							                	<h3 class="bg-muted p-xs pull-left full-width">Fábricas<a title="Ordenar" href="<?php echo base_url('cms-v2/paginas/ordenar/'.$detalle['id'].'/'.$categoria['id'].'/'.$idioma['extension']);?>" class="sepV_a btn btn-primary btn-sm red pull-right m-l-sm"><i class="fa fa-sort-circle"></i> Ordenar</a> 
							                	<a title="Ingresar" id="item" href="#" data-toggle="modal" data-id_contenido="<?php echo $detalle['id'];?>" data-idioma="<?php echo $idioma['extension'];?>" data-target="#myModalIngresarFabricas" class="sepV_a btn btn-primary btn-sm red pull-right"><i class="fa fa-plus-circle"></i> Ingresar</a></h3>

							                <?php 
												$parametros['id'] = $detalle['id'];
												$parametros['id_tipo'] = $categoria['id'];
												$parametros['idioma'] = $idioma['extension'];
												$miembros= $CI->Paginas_model->getContenidoAdicionalIdioma($parametros);

								               if(!empty($miembros)) {
												foreach($miembros as $miembro) { ?>	
								                <div class="col-md-6 col-lg-3 box-items">
													<div class="contact-box<?php echo ($miembro['estado'] == 1) ? ' bg-inactiva' : ''; echo($detalle['id_con_secciones'] == 545) ? ' contact-box2': ''; ?>">
									                    <div class="col-sm-12">
									                        <div class="text-center">
									                            <?php if($miembro['imagen']) { ?>
									                            	<img src="<?php echo base_url('multimedia/thumbs/'.$miembro['imagen']);?>" title="<?php echo $miembro['titulo']; ?>" alt="<?php echo $miembro['titulo'];?>" class="m-b-xs">
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
									                    <div class="col-sm-12">
					                                            <a title="Modificar" href="<?php echo base_url('cms-v2/paginas/modificar_informacion/'.$detalle['id'].'/'.$miembro['id']);?>" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i> Modificar</a>
					                                            <a title="Eliminar" id="item" href="#" data-toggle="modal" data-estado="<?php echo $miembro['estado'];?>" data-id_contenido="<?php echo $detalle['id'];?>" data-id="<?php echo $miembro['id'];?>" data-target="#myModalEliminarInformacion" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i> Eliminar</a>
									                    </div>
									                </div>
									             </div>
											<?php } } else { echo 'No se encontraron resultados';} ?>
				                    
												</div>
											</div>
				                    <?php } }?>	
				                    <?php if($detalle['id_con_secciones'] == 554) { echo '</div>'; } ?>
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

<!-- Modal Ingresar Miembros -->
    <div class="modal inmodal" id="myModalIngresarFabricas" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog modal-lg">
	   		<div class="modal-content animated">
		        <div class="modal-header">
		            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
		            <h4 class="modal-title">Ingresar Fábricas</h4>
		        </div>
		
		        <div class="modal-body p-xs pull-left full-width">
			        <form name="ingresar" class="form_ingresar" method="post" enctype="multipart/form-data" action="<?php echo base_url('cms-v2/paginas/ingresar_informacion/'); ?>">
	                    <div class="col-sm-6">
		                    <label class="control-label pull-left">País</label>
                           	 <?php echo form_dropdown('contenido2', $paises, null, 'class="form-control m-b"'); ?>
		                </div>
	                    <div class="col-sm-6">
	                    	<label class="control-label pull-left">Estado</label>
                            <select name="estado" id="estado" class="form-control m-b">
                                <option value="1">Inactivo</option>
                                <option value="3">Activo</option>
                            </select>
	                    </div>
		                <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Lugar</label>
		                    <input type="text" name="titulo" id="titulo" value="" class="form-control">
		                </div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Empresa</label>
		                    <input type="text" name="subtitulo" id="subtitulo" value="" class="form-control">
						</div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Nombre</label>
		                    <input type="text" name="texto_adicional" id="texto_adicional" value="" class="form-control">
						</div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Teléfono</label>
		                    <input type="text" name="contenido1" id="contenido1" value="" class="form-control">
						</div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">E-mail</label>
		                    <input type="text" name="contenido3" id="contenido3" value="" class="form-control">
						</div>
	                    <div class="col-sm-12 m-b-sm">
		                    <label class="control-label pull-left">Imagen</label>
                            <div class="fileinput fileinput-new input-group p-xs form-control" data-provides="fileinput">
	                            <input type="file" name="imagen">
	                    	</div>
						</div>
		                
	                    <div class="col-sm-6 m-b-sm">
		                    <label class="control-label pull-left">Orden</label>
		                    <input type="text" name="orden" id="orden" value="" class="form-control">
		                </div>

	                    <div class="col-sm-12 m-t-sm">
			            	<input type="hidden" name="id" id="id" value="">
			            	<input type="hidden" name="id_tipo" id="id_tipo" value="843">
			            	<input type="hidden" name="idioma" id="idioma" value="">
			            	<input type="hidden" name="id_contenido" id="id_contenido" value="">
		            		<input type="hidden" name="medidas" value="230x130">
		            		<input type="hidden" name="id_imagen_tipo" value="13">
			                <input type="submit" class="btn btn-primary pull-right" value="Ingresar">
	                    </div>
		            </form>
		        </div>
	  		</div>
		</div>
	</div>

<!-- SUMMERNOTE -->
<script src="/assets/js/plugins/summernote/summernote.min.js"></script>
<script src="/assets/js/summernote-grid.js"></script>
<script src="/assets/js/summernote-ext-addclass.js"></script>
<script>
  $('#myModalIngresarFabricas').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var id_contenido = $(e.relatedTarget).data().id_contenido;
     var idioma = $(e.relatedTarget).data().idioma;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#id_contenido').val(id_contenido);
      $(e.currentTarget).find('#idioma').val(idioma);
     });

$('.summernote').summernote({
    	addclass: {
        	debug: false,
	        classTags: [{title:"Button","value":"btn btn-success"},"jumbotron", "lead","img-rounded","img-circle", "img-responsive","btn", "btn btn-success","btn btn-danger","text-muted", "text-primary", "text-warning", "text-danger", "text-success", "table-bordered", "table-responsive", "alert", "alert alert-success", "alert alert-info", "alert alert-warning", "alert alert-danger", "visible-sm", "hidden-xs", "hidden-md", "hidden-lg", "hidden-print"]
	    },
	    styleTags: [
    'p',
        { title: 'Blockquote', tag: 'blockquote', className: 'blockquote', value: 'blockquote' },
        'div', 'p','h1', 'h2', 'h3', 'h4', 'h5', 'h6'
	],

        placeholder: 'Ingrese contenido...',
        tabsize: 2,
        toolbar: [
          ['style', ['style', 'addclass']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['insert', ['link', 'picture']],
          ['insert', ['grid']],
          ['view', ['codeview']]
        ]

	});
$('[data-toggle="tooltip"]').tooltip(); 
</script>
      
                                       