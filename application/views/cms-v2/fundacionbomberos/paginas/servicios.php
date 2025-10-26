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
								}
							?>
	                            <div class="panel-body">
								 <div class="row">
								 <input type="hidden" name="titulo_<?php echo $idioma['extension'];?>" value="Home">
					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Encabezado</h2>
					                 	<div class="form-group m-b-md pull-left full-width m-t-md">
											<label class="col-sm-1 control-label">T&iacute;tulo</label>
											<div class="col-sm-5">
												<div class="input-group"><input type="text" name="titulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título que se mostrará sobre la imagen del encabezado." title=""> <i class="fa fa-question"></i></button></div>
											</div>
											
											<label class="col-sm-1 control-label">Imagen</label>
					                        <div class="col-sm-5">
			                                   <div class="fileinput fileinput-new input-group" data-provides="fileinput"><input type="file" class="form-control" name="imagen_<?php echo $idioma['extension'];?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen de fondo del encabezado, sobre la cual se muestra el título de la sección. Tamaño recomendado 1780x430 píxeles o proporcionales." title=""> <i class="fa fa-question"></i></button></div>
				                            <?php if($imagen) { ?>
				                            	<img src="<?php echo base_url('multimedia/thumbs/'.$imagen['imagen_breadcrumb']);?>" class="m-b-xs" style="width:100%;">
				                            <?php } ?>
					                        </div>
					                 	</div>
					                </div>
					                <?php if($detalle['id_con_secciones'] != 738) { ?>
					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Contenido</h2>
					                 	<div class="form-group m-b-md pull-left full-width">
											<div class="col-sm-12">
												<div class="ibox-title bg-muted m-t-md"><h5>Intro</h5><button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Introducción al contenido." title=""> <i class="fa fa-question"></i></button></div>
												<div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido1_<?php echo $idioma['extension'];?>" rows="3"><?php echo(isset($item['contenido1'])) ? $item['contenido1']: null?></textarea></div>
											</div>
					                 	</div>
					                 	<div class="form-group m-b-md pull-left full-width">
											<div class="col-sm-6">
												<div class="ibox-title bg-muted m-t-md"><h5>Box 1</h5><button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Contenido del box derecho. Para el título debe seleccionarlo y aplicar Header 4 (se selecciona desde el ícono Varita Mágica) y el contenido es lista simple (se selecciona desde el ícono Lista)." title=""> <i class="fa fa-question"></i></button></div>
												<div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido2_<?php echo $idioma['extension'];?>" rows="3"><?php echo(isset($item['contenido2'])) ? $item['contenido2']: null?></textarea></div>
											</div>
											<div class="col-sm-6">
												<div class="ibox-title bg-muted m-t-md"><h5>Box 2</h5><button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Contenido del box izquierdo. Para el título debe seleccionarlo y aplicar Header 4 (se selecciona desde el ícono Varita Mágica) y el contenido es lista simple (se selecciona desde el ícono Lista)." title=""> <i class="fa fa-question"></i></button></div>
												<div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido3_<?php echo $idioma['extension'];?>" rows="3"><?php echo(isset($item['contenido3'])) ? $item['contenido3']: null?></textarea></div>
											</div>
					                 	</div>
					                 	<div class="form-group m-b-md pull-left full-width">
											<div class="col-sm-12">
												<div class="ibox-title bg-muted m-t-md"><h5>Texto</h5><button type="button" class="btn btn-primary m-l-sm btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Contenido general. Para el título debe seleccionarlo y aplicar Header 4 (se selecciona desde el ícono Varita Mágica) y el contenido es párrafo (se selecciona desde el ícono Varita Mágica)." title=""> <i class="fa fa-question"></i></button></div>
												<div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido4_<?php echo $idioma['extension'];?>" rows="3"><?php echo(isset($item['contenido4'])) ? $item['contenido4']: null?></textarea></div>
											</div>
					                 	</div>
					                </div>
						        </div>
					                <?php } ?>
						    </div>
						</div>
                        <?php } ?>
                     <?php echo form_close();?>
                     </div>
                 </div>
             </div>                 
         </div>
     </div>     

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
</script>
                                       
<!-- Modal Ingresar -->
<div class="modal inmodal" id="myModalIngresar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
   		<div class="modal-content animated">
	        <div class="modal-body p-xs pull-left full-width">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title">Ingresar Programa</h4>
		        <form name="ingresar" class="form_ingresar" method="post" enctype="multipart/form-data" action="<?php echo base_url('cms-v2/paginas/ingresar_informacion/'); ?>">
		            <input type="hidden" name="idioma" id="idioma" value="">
		            <input type="hidden" name="id_contenido" id="id_contenido" value="">
                    <input type="hidden" name="id_tipo" id="seccion" value="">
                    <input type="hidden" name="id_imagen_tipo" value="13">
                    <input type="hidden" name="medidas" value="1200x800">
                    <div class="col-sm-12">
	                    <label class="control-label pull-left">Título</label>
	                    <input type="text" name="titulo" id="titulo" value="" class="form-control">
	                    <label class="control-label pull-left">Texto</label>
						<div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido1" id="contenido1" rows="4"  value=""></textarea></div>
	                    <label class="control-label pull-left">Texto Adicional</label>
						<div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido2" id="contenido2" rows="4"  value=""></textarea></div>
	                    <label class="control-label pull-left">Imagen</label>
                        <div class="fileinput fileinput-new input-group p-xs form-control" data-provides="fileinput">
                            <input type="file" name="imagen">
                    	</div>
                    </div>
                    <div class="col-sm-6">
	                    <label class="control-label pull-left">Orden</label>
	                    <input type="text" name="orden" id="orden" value="" class="form-control">
                    </div>
                    <div class="col-sm-6">
                    	<label class="control-label pull-left">Estado</label>
                        <select name="estado" id="estado" class="form-control m-b">
                            <option value="1">Inactivo</option>
                            <option value="3">Activo</option>
                        </select>
                    </div>
                    <div class="col-sm-12">
		                <input type="submit" class="btn btn-primary pull-right" value="Ingresar">
                    </div>
	            </form>
	        </div>
  		</div>
	</div>
</div>
<!-- Fin Modal  -->

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
<!-- Fin Modal Eliminar -->

<!-- Mainly scripts -->
<script src="<?php echo base_url('assets/js/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>

<script>
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