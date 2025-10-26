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
									$parametros['id'] = $detalle['id'];
									$parametros['idioma'] = $idioma['extension'];
									$parametros['id_tipo'] = 8;
									$CI =& get_instance();
									$CI->load->model("Paginas_model");
									$item = $this->Paginas_model->getPaginaDetalleIdioma($detalle['id'], $idioma['extension']);
									$imagen = $this->Paginas_model->getMedia($detalle['id'], $idioma['extension'], 12);
									$slides= $this->Paginas_model->getContenidoAdicionalIdioma($parametros);
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
					                                            <a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion='<?php echo $slide['titulo'];?>?' data-estado="<?php echo $slide['estado'];?>" data-id_contenido="<?php echo $detalle['id'];?>" data-id="<?php echo $slide['id'];?>" data-target="#myModalEliminarInformacion" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i> Eliminar</a>
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
	        <div class="modal-body p-xs pull-left full-width">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title">Ingresar contenido de Slide</h4>
		        <form name="ingresar" class="form_ingresar" method="post" enctype="multipart/form-data" action="<?php echo base_url('cms-v2/paginas/ingresar_informacion/'); ?>">
                   <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Título</label>
	                    <div class="input-group col-sm-9"><input type="text" name="titulo" id="titulo" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título que se mostrará sobre la imagen con recuadro blanco. Debe ser una palabra breve." title=""> <i class="fa fa-question"></i></button></span></div>
	               </div>
                   <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Orden</label>
	                    <div class="input-group col-sm-9"><input type="text" name="orden" id="orden" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en que se mostrará el ítem." title=""> <i class="fa fa-question"></i></button></span></div>
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
	                        <div class="fileinput fileinput-new form-control" data-provides="fileinput"><input type="file" name="imagen"><span class="input-group-btn" style="position: absolute;right: 28px;top: 0;"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Imagen en jpg, gif o png de 2000x1000 píxeles. Recuerde que el peso debe ser el mínimo posible para no afectar la descarga del site." title=""> <i class="fa fa-question"></i></button></span></div></div>
                    </div>
                    <div class="col-sm-12 m-t-sm">
		            	<input type="hidden" name="id" id="id" value="" />
		            	<input type="hidden" name="id_tipo" id="id_tipo" value="8" />
		            	<input type="hidden" name="id_imagen_tipo" id="tipo" value="18" />
		            	<input type="hidden" name="idioma" id="idioma" value="" />
		            	<input type="hidden" name="medidas" value="2000x1000" />
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
	        <div class="modal-body p-xs pull-left full-width">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title">Eliminar contenido</h4>
	            <p class="text-center">&iquest;Est&aacute; seguro de querer eliminar el contenido <em> <input type="text" name="seccion" id="seccion" value="" style="border:1px solid #f8fafb; background:#f8fafb; width:auto !important;"/></em></p>
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
<!-- Fin Modal Eliminar -->

<!-- Mainly scripts -->
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


<script>
$('[data-toggle="tooltip"]').tooltip(); 
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