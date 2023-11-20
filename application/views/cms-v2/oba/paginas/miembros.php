<style>
.note-editor.note-frame { border:0;}
.contact-box { min-height: 210px;max-height: 210px; }
.tooltip-inner {
      max-width: 250px;
      width: 250px;
}
.bg-inactiva {color: #a94442;background: #f2dede !important;border-color: #ebccd1;}
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

            <form action="https://cms.revisionalpha.com/cms-v2/paginas/modificar/<?php echo $detalle['id']; ?>/" class="form-horizontal" enctype="multipart/form-data" method="post" accept-charset="utf-8">
				<input type="hidden" name="id_con_secciones" value="<?php echo $detalle['id_con_secciones']; ?>">
				<input type="hidden" name="medidas" value="1400x200" />
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
								}
							?>
	                            <div class="panel-body">
								 <div class="row">
	
					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Encabezado</h2>
					                 	<div class="form-group">
											<label class="col-sm-1 control-label">T&iacute;tulo</label>
											<div class="col-sm-5">
		                                        <div class="input-group">
													<input type="text" name="titulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo'] : null; ?>"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de la sección, que se mostrará debajo de la imagen, sobre fondo gris." title=""> <i class="fa fa-question"></i></button>
												</div>
											</div>
										    <!-- Imagenes Generales -->
						                    <div class="col-sm-6">
												<label class="col-sm-1 control-label">Imagen</label>
					                            <?php if(!empty($imagen)) { ?>
					                            <div class="col-sm-12">
					                            	<img src="<?php echo base_url('multimedia/thumbs/'.$imagen['imagen_breadcrumb']);?>" style="height:auto;width:100%;float: left;padding-bottom: 24px;padding-right: 25px;"/>
					                            </div>
				                            	<?php } ?>
					                            <div class="col-sm-12">
		                                        	<div class="input-group">
						                            	<input type="file" name="imagen_<?php echo $idioma['extension'];?>" class="form-control"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen superior de la sección. El tamaño recomendado para la misma es de 1400 x 200 píxeles o proporcionales. Recuerde que el peso debe ser el mínimo posible para no afectar la descarga del site." title=""> <i class="fa fa-question"></i></button>
						                            </div>
					                            </div>
											</div>
					                 	</div>
					                </div>
								  
					                <?php 
						                if(!empty($categorias)) {
										foreach($categorias as $categoria) { ?>

					                    <div class="col-sm-12 p-xxs">
						                    <div class="pull-left full-width">
							                	<h3 class="bg-muted p-xs pull-left full-width"><?php echo $categoria['seccion']; ?><a title="Ordenar" href="<?php echo base_url('cms-v2/paginas/ordenar/'.$detalle['id'].'/'.$categoria['id'].'/es');?>" class="sepV_a btn btn-primary btn-sm red pull-right m-l-sm"><i class="fa fa-sort-circle"></i> Ordenar</a> 
							                	<a title="Ingresar" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $detalle['seccion']; ?>" data-id_contenido="<?php echo $detalle['id'];?>" data-idioma="<?php echo $idioma['extension'];?>" data-target="#myModalIngresarInformacion" class="sepV_a btn btn-primary btn-sm red pull-right"><i class="fa fa-plus-circle"></i> Ingresar</a></h3>

							                <?php 
												$miembros= $CI->Paginas_model->getContenidoAdicionalIdioma($detalle['id'],$categoria['id'], $idioma['extension']);

								               if(!empty($miembros)) {
												foreach($miembros as $miembro) { ?>	
								                <div class="col-sm-4">
													<div class="contact-box<?php echo ($miembro['estado'] == 1) ? ' bg-inactiva' : '';?>">
									                    <div class="col-sm-4">
									                        <div class="text-center">
									                            <?php if($miembro['imagen']) { ?>
									                            	<img src="<?php echo base_url('multimedia/thumbs/'.$miembro['imagen']);?>" title="<?php echo $miembro['titulo']; ?>" alt="<?php echo $miembro['titulo'];?>" class="img-circle m-t-xs img-responsive">
									                            <?php } else { ?>
									                            <p>Sin imagen</p>
									                            <?php } ?>
									                        </div>
									                    </div>
									                    <div class="col-sm-8">
									                        <h3><strong><?php echo ellipsize($miembro['titulo'],25, 1);?></strong></h3>
									                        <p><i class="fa fa-calendar"></i> Subida: <?php echo $miembro['fecha_alta'];?></p>
									                        <address>
									                            <div><?php echo character_limiter($miembro['contenido1'], 61, '...');?></div>
					                                            <br>
					                                            <a title="Modificar" href="<?php echo base_url('cms-v2/paginas/modificar_informacion/'.$detalle['id'].'/'.$miembro['id']);?>" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i> Modificar</a>
					                                            <a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $miembro['titulo'];?>" data-estado="<?php echo $miembro['estado'];?>" data-id_contenido="<?php echo $detalle['id'];?>" data-id="<?php echo $miembro['id'];?>" data-target="#myModalEliminarInformacion" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i> Eliminar</a>
									                        </address>
									                    </div>
									                    <div class="clearfix"></div>
									                </div>
									             </div>
											<?php } } else { echo 'No se encontraron resultados';} ?>
				                    
												</div>
											</div>
				                    <?php } }?>	
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

<!-- SUMMERNOTE -->
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
        height: 230,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['insert', ['link']]
        ]
});
$('[data-toggle="tooltip"]').tooltip(); 
</script>
      
      
                                       