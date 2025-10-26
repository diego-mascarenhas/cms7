<style>
.note-editor.note-frame { border:0;}
.tooltip-inner {max-width: 250px;width: 250px;}
.bg-inactiva {color: #a94442;background: #f2dede !important;border-color: #ebccd1;}
.box-items {display:flex; flex-direction: column;}
.box-items { padding-left:5px; padding-right:5px;}
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
					                                    	<?php } else { ?>
					                                    	Sin imagen
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
                                       