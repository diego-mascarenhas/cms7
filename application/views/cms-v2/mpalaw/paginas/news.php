<style>
.note-editor.note-frame { border:0;}
.contact-box { min-height: 300px;max-height: 300px; padding:20px 10px;display: flex;flex-direction: column;justify-content: center;}
.contact-box img { height: 100px; width:auto;}
.tooltip-inner {max-width: 250px;width: 250px;}
.bg-inactiva {color: #a94442;background: #f2dede !important;border-color: #ebccd1;}
.box-items {display:flex; flex-direction: column;}
.box-items { padding-left:5px; padding-right:5px;}
.modal-title { line-height: 1;}
@media (min-width:992px) {
	.modal-lg {width:800px;}
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

            <form action="/cms-v2/paginas/modificar/<?php echo $detalle['id']; ?>/" class="form-horizontal" method="post" accept-charset="utf-8">
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
									$CI->load->model("Informacion_model");
									$item = $this->Paginas_model->getPaginaDetalleIdioma($detalle['id'], $idioma['extension']);
									//INFO PARA NOTICIAS
									$parametros['idioma'] = $idioma['extension'];
									$parametros['tipo'] = 9;
									$parametros['template'] = 2;
									$listado_noticias = $this->Informacion_model->getContenidos($parametros);
								}
							?>
	                            <div class="panel-body">
								 <div class="row">
	
					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Encabezado</h2>
					                 	<div class="form-group">
											<label class="col-sm-1 control-label">Título</label>
											<div class="col-sm-5">
		                                        <div class="input-group">
													<input type="text" name="titulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo'] : null; ?>"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de la sección, que se mostrará sólo en caso de ser necesario." title=""> <i class="fa fa-question"></i></button>
												</div>
											</div>
					                 	</div>
					                 	</div>

									 <div class="col-lg-12">
										 <div class="ibox m-b-none">
										 <h2 class="b-r-sm bg-muted p-xs pull-left full-width">Novedades <a title="Ingresar" id="item" href="#" data-toggle="modal" data-idioma="<?php echo $idioma['extension'];?>" data-target="#myModalNoticia" class="sepV_a btn btn-primary btn-sm red pull-right"><i class="fa fa-plus-circle"></i> Ingresar nueva</a></h2>
										 <?php if ($this->session->flashdata('noticia') == 1) { ?>
											<div class="col-md-12">
												<?php if ($this->session->flashdata('resultado') == 'error') { ?>
												<div class="alert alert-danger alert-dismissable" role="alert"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><p><?php echo $this->session->flashdata('data'); ?></div>
												<?php } else { ?>
												<div class="alert alert-success alert-dismissable" role="alert"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><p><?php echo $this->session->flashdata('data'); ?></div>
												<?php } ?>
											</div>
										 <?php } ?>
			
											 <div class="ibox-content no-borders">
											 	<table class="table table-hover no-margins table-striped dataTables-example">
								                    <thead>
									                    <tr>
									                        <th>Imagen</th>
									                        <th>Fecha</th>
									                        <th>Título</th>
									                        <th>Destacado</th>
									                        <th>Estado</th>
									                        <th>Acciones</th>
									                    </tr>
								                    </thead>
								                    <tbody>
									                    <?php if(isset($listado_noticias)) { foreach($listado_noticias as $noticia) { ?>	
									                   	 <tr class="gradeX">
									                        <td><?php echo($noticia['imagen']) ? '<img src="/multimedia/thumbs/'.$noticia['imagen'].'" width="34" style="background: grey;">' : 'Sin imagen';?></td>
									                        <td><?php echo $noticia['subtitulo'];?></td>
									                        <td><?php echo $noticia['titulo'];?></td>
									                        <td><?php echo ($noticia['destacado'] == 1) ? 'Sí' : 'No';?></td>
									                        <td <?php if($noticia['estado'] == 1) { echo ' class="bg-danger"'; }?>><?php echo ($noticia['estado'] == 1) ? 'Inactivo' : 'Activo';?></td>
									                        <td>
																<div class="dropdown">
																  <button class="btn btn-default dropdown-toggle btn-sm" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Acciones <span class="caret"></span>
																  </button>
																  <ul class="dropdown-menu dropdown-clientes" aria-labelledby="dropdownMenu1">
																    <li>
																	    <a title="Modificar" id="item" href="<?php echo base_url('cms-v2/informacion/modificar/'.$noticia['id']); ?>" class="sepV_a btn btn-sm"><i class="fa fa-pencil"></i> Editar</a></li>

<!-- 																	    <a title="Modificar" id="item" href="#" data-toggle="modal" data-id="<?php echo $noticia['id'];?>" data-idioma="<?php echo $idioma['extension'];?>" data-titulo="<?php echo $noticia['titulo'];?>" data-subtitulo="<?php echo $noticia['subtitulo'];?>" data-contenido1="<?php echo strip_tags($noticia['contenido1']);?>" data-contenido2="<?php echo strip_tags($noticia['contenido2']);?>" data-url="<?php echo $noticia['url'];?>" data-categoria="<?php echo $noticia['id_con_secciones'];?>" data-imagen="<img src=/multimedia/thumbs/<?php echo $noticia['imagen'];?> height=52>" data-orden="<?php echo $detalle['orden'];?>" data-destacado="<?php echo $noticia['destacado'];?>" data-estado="<?php echo $noticia['id_estado'];?>" data-target="#modificarNoticia" class="sepV_a btn btn-sm"><i class="fa fa-pencil"></i> Editar</a></li> -->
																    <li><a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion='<?php echo $noticia['titulo'];?>' data-id="<?php echo $noticia['id'];?>" data-estado="<?php echo $noticia['id_estado'];?>" data-target="#modalEliminarNoticia" class="sepV_a btn btn-sm"><i class="fa fa-minus-circle"></i> Eliminar</a></li> 
			
																  </ul>
																</div>
															</td>
									                    </tr>
								                 <?php }  }?>	
						
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

<!-- Modal Ingresar Noticia -->
<div class="modal inmodal" id="myModalNoticia" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
   		<div class="modal-content animated">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title">Ingresar Noticia</h4>
	        </div>
	        <div class="modal-body p-xs pull-left full-width">
	           <div id="alert-msg-producto"></div>
	           <p id="extension"></p>
		       <form name="ingresar" class="form_ingresar m-t-sm" method="post" action="<?php echo base_url('cms-v2/informacion/ingresar'); ?>" enctype="multipart/form-data" id="form_ingresar_noticia">
		           <input type="hidden" name="url" id="url" value="<?php echo base_url('cms-v2/paginas/modificar/'.$detalle['id']);?>">
		           <input type="hidden" name="idioma" id="idioma" value="">
		           <input type="hidden" name="extension" id="extension" value="">
		           <input type="hidden" name="template" value="2">
				   <input type="hidden" name="destacado_slide" value="0">
				   <input type="hidden" name="id_con_secciones" value="996">
				   <input type="hidden" name="id_tipo_imagen2" id="id_tipo_imagen2" value="19">
<!-- 				   <input type="hidden" name="medidas" id="medidas" value="380x290"> -->
				   <input type="hidden" name="medidas_miniatura_imagen2" value="380x290">

                   <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Título</label>
	                    <div class="input-group col-sm-9"><input type="text" name="titulo_" id="titulo_" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de la noticia." title=""> <i class="fa fa-question"></i></button></span></div>
	               </div>
                   <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Fecha</label>
	                    <div class="input-group col-sm-9"><input type="text" name="subtitulo_" id="subtitulo_" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Fecha de la noticia." title=""> <i class="fa fa-question"></i></button></span></div>
	               </div>
                   <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">url</label>
	                    <div class="input-group col-sm-9"><input type="text" name="url_" id="url_" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="url de la noticia." title=""> <i class="fa fa-question"></i></button></span></div>
	               </div>
                   <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Intro <button type="button" class="btn btn-primary btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Texto de introducción, se mostrará en el listado de noticias y en el detalle sobre fondo gris."> <i class="fa fa-question"></i></button></label>
	                    <div class="input-group col-sm-9"><div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido2_" rows="3"></textarea></div></div>
	               </div>
                   <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Texto <button type="button" class="btn btn-primary btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Texto de la noticia."> <i class="fa fa-question"></i></button></label>
	                    <div class="input-group col-sm-9"><div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido1_" rows="3"></textarea></div></div>
	               </div>
                   <div class="col-sm-12 m-b-sm m-b-sm">
	                    <label class="control-label col-sm-3">Imagen</label>
                    	<div class="input-group col-sm-9">
	                        <div class="fileinput fileinput-new form-control" data-provides="fileinput"><input type="file" name="imagen_" id="imagen_"><span class="input-group-btn" style="position: absolute;right: 28px;top: 0;"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Imagen en jpg, gif o png de 380x290 píxeles o proporcionales. Recuerde que el peso debe ser el mínimo posible para no afectar la descarga del site." title=""> <i class="fa fa-question"></i></button></span></div></div>
                    </div>
                   
                   <div class="col-sm-12 m-b-sm m-b-sm">
	                    <label class="control-label col-sm-3">Archivo</label>
                    	<div class="input-group col-sm-9">
	                        <div class="fileinput fileinput-new form-control" data-provides="fileinput"><input type="file" name="archivo1_" id="archivo1_"><span class="input-group-btn" style="position: absolute;right: 28px;top: 0;"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Archivo PDF a ver/descargar." title=""> <i class="fa fa-question"></i></button></span></div></div>
                    </div>

                   <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Orden</label>
	                    <div class="input-group col-sm-9"><input type="text" name="orden" id="orden" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en que se mostrará el ítem." title=""> <i class="fa fa-question"></i></button></span></div>
	               </div>
                   <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Destacado</label>
                    	<div class="input-group col-sm-9">
	                        <select name="destacado" id="destacado" class="form-control m-b">
	                            <option value="0">No</option>
	                            <option value="1">Sí</option>
	                        </select><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Si es destacado se muestra en el home como contenido destacado." title=""> <i class="fa fa-question"></i></button></span>
                    	</div>
                    </div>
                   <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Estado</label>
                    	<div class="input-group col-sm-9">
	                        <select name="estado" id="estado" class="form-control m-b">
	                            <option value="1">Inactivo</option>
	                            <option value="3">Activo</option>
	                        </select><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Si es activo se muestra en la web, si es inactivo no se muestra en la web." title=""> <i class="fa fa-question"></i></button></span>
                    	</div>
                    </div>
                    <div class="col-sm-12">
                    	<input class="btn btn-primary pull-right" id="submit_noticia" name="submit" type="button" value="Ingresar" />
                    </div>
	            </form>
	        </div>
  		</div>
	</div>
</div>

<!-- Modal Modificar Noticia -->
<div class="modal inmodal" id="modificarNoticia" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
   		<div class="modal-content animated">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title">Modificar Noticia</h4>
	        </div>
	        <div class="modal-body p-xs pull-left full-width">
	           <div id="alert-msg-producto2"></div>
	           <p id="extension"></p>
	           <span id="form"></span>
		       <form name="ingresar" class="form_ingresar m-t-sm" method="post" action="<?php echo base_url('cms-v2/informacion/modificar'); ?>" enctype="multipart/form-data" id="form_modificar_noticia">
		           <input type="hidden" name="url" id="url" value="<?php echo base_url('cms-v2/paginas/modificar/'.$detalle['id']);?>">
		           <input type="hidden" name="idioma" id="idioma" value="">
		           <input type="hidden" name="extension" id="extension" value="">
		           <input type="hidden" name="template" value="2">
				   <input type="hidden" name="destacado_slide" value="0">
				   <input type="hidden" name="id" id="id" value="">
				   <input type="hidden" name="id_con_secciones" value="996">
				   <input type="hidden" name="id_tipo_imagen2" id="id_tipo_imagen2" value="19">
				   <input type="hidden" name="medidas_miniatura_imagen2" value="380x290">

                   <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Título</label>
	                    <div class="input-group col-sm-9"><input type="text" name="titulo_" id="titulo" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de la noticia." title=""> <i class="fa fa-question"></i></button></span></div>
	               </div>
                   <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Fecha</label>
	                    <div class="input-group col-sm-9"><input type="text" name="subtitulo_" id="subtitulo" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Fecha de la noticia." title=""> <i class="fa fa-question"></i></button></span></div>
	               </div>
                   <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">url</label>
	                    <div class="input-group col-sm-9"><input type="text" name="url_" id="url" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="url de la noticia." title=""> <i class="fa fa-question"></i></button></span></div>
	               </div>
                   <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Intro <button type="button" class="btn btn-primary btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Texto de introducción, se mostrará en el listado de noticias y en el detalle sobre fondo gris."> <i class="fa fa-question"></i></button></label>
	                    <div class="input-group col-sm-9"><div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido2" rows="3"><span id="contenido2"></span></textarea></div></div>
	               </div>
                   <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Texto <button type="button" class="btn btn-primary btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="Texto de la noticia."> <i class="fa fa-question"></i></button></label>
	                    <div class="input-group col-sm-9"><div class="ibox-content no-padding"><textarea class="form-control summernote2" name="contenido1" rows="3"><span id="contenido1"></span></textarea></div></div>
	               </div>
                   <div class="col-sm-12 m-b-sm m-b-sm">
                    	<label class="control-label col-sm-3">Imagen</label>
                    	<div class="input-group col-sm-9">
	                    	<div id="imagen" style="width:52px;"></div>
	                        <div class="fileinput fileinput-new form-control" data-provides="fileinput"><input type="file" name="imagen2_" id="imagen2_"><span class="input-group-btn" style="position: absolute;right: 28px;top: 0;"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Imagen en jpg, gif o png de 380x290 píxeles o proporcionales. Recuerde que el peso debe ser el mínimo posible para no afectar la descarga del site." title=""> <i class="fa fa-question"></i></button></span></div></div>
                    </div>
                   
                   <div class="col-sm-12 m-b-sm m-b-sm">
	                    <label class="control-label col-sm-3">Archivo</label>
                    	<div class="input-group col-sm-9">
	                        <div class="fileinput fileinput-new form-control" data-provides="fileinput"><input type="file" name="archivo1_" id="archivo1_"><span class="input-group-btn" style="position: absolute;right: 28px;top: 0;"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Archivo PDF a ver/descargar." title=""> <i class="fa fa-question"></i></button></span></div></div>
                    </div>

                   <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Orden</label>
	                    <div class="input-group col-sm-9"><input type="text" name="orden" id="orden" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en que se mostrará el ítem." title=""> <i class="fa fa-question"></i></button></span></div>
	               </div>
                   <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Destacado</label>
                    	<div class="input-group col-sm-9">
	                        <select name="destacado" id="destacado" class="form-control m-b">
	                            <option value="0">No</option>
	                            <option value="1">Sí</option>
	                        </select><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Si es destacado se muestra en el home como contenido destacado." title=""> <i class="fa fa-question"></i></button></span>
                    	</div>
                    </div>
                   <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Estado</label>
                    	<div class="input-group col-sm-9">
	                        <select name="estado" id="estado" class="form-control m-b">
	                            <option value="1">Inactivo</option>
	                            <option value="3">Activo</option>
	                        </select><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Si es activo se muestra en la web, si es inactivo no se muestra en la web." title=""> <i class="fa fa-question"></i></button></span>
                    	</div>
                    </div>
                    <div class="col-sm-12">
                    	<input class="btn btn-primary pull-right" id="submit_modificar_noticia" name="submit" type="button" value="Modificar">
                    </div>
	            </form>
	        </div>
  		</div>
	</div>
</div>

<!-- Modal Eliminar -->
<div class="modal inmodal" id="modalEliminarNoticia" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content animated">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
            <h4 class="modal-title">Eliminar contenido</h4>
            </div>
            <div class="modal-body">
            <p>&iquest;Est&aacute; seguro de querer eliminar el contenido <strong> <input type="text" name="seccion" id="seccion" value="" style="border:1px solid #f8fafb; background:#f8fafb; width:auto !important;"/></strong>?</p>
                <div class="modal-footer">
	                <form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('cms-v2/informacion/eliminar/'); ?>">
                    	<input type="hidden" name="url" id="url" value="<?php echo base_url('cms-v2/paginas/modificar/'.$detalle['id']);?>" />
                    	<input type="hidden" name="template" value="2">
                    	<input type="hidden" name="id" id="id" value="" />
                    	<input type="hidden" name="estado" id="estado" value="" />
                    	<input type="submit" class="btn btn-primary" value="Eliminar">
                    </form>
                </div>
           </div>
        </div>
     </div>
</div>
<!-- Fin Modal Eliminar -->

<!-- SUMMERNOTE -->
<script src="<?php echo base_url('assets/js/plugins/summernote/summernote.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/summernote-grid.js'); ?>"></script>
<script>

$('.summernote2').summernote({
        placeholder: 'Ingrese contenido...',
        dialogsInBody: true,
        height: 100,
		toolbar: [
		    // [groupName, [list of button]]
		    ['style', ['style']],
		    ['font', ['bold', 'italic','underline', 'clear']],
		    ['fontsize', ['fontsize']],
		    ['color', ['color']],
		    ['para', ['ul', 'ol', 'paragraph']],
		    ['insert', ['link']]
		  ]
});
$('[data-toggle="tooltip"]').tooltip(); 

$('#submit_noticia').click(function() {
const data = new FormData($('#form_ingresar_noticia')[0]);
    $.ajax({
        url: "<?php echo base_url('cms-v2/informacion/ingresar'); ?>",
        type: 'POST',
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function(msg) {
            if (msg == 'SI')
  	            $(location).attr("href", "<?php echo base_url('cms-v2/paginas/modificar/'.$detalle['id']);?>");
            else if (msg == 'NO')
                $('#alert-msg-producto').html('<div class="alert alert-danger text-center">Hubo un error en el formulario, intente nuevamente.</div>');
            else
                $('#alert-msg-producto').html('<div class="alert alert-danger">' + msg + '</div>');
        }
    });
    return false;
});

$('#submit_modificar_noticia').click(function() {
const data = new FormData($('#form_modificar_noticia')[0]);
    $.ajax({
        url: "https://staging.cms.revisionalpha.com/cms-v2/informacion/modificar",
        type: 'POST',
        data: data,
        contentType: false,
        cache: false,
        processData: false,
        success: function(msg) {
            if (msg == 'SI')
  	            $(location).attr("href", "<?php echo base_url('cms-v2/paginas/modificar/'.$detalle['id']);?>");
            else if (msg == 'NO')
                $('#alert-msg-producto2').html('<div class="alert alert-danger text-center">Hubo un error en el formulario, intente nuevamente.</div>');
            else
                $('#alert-msg-producto2').html('<div class="alert alert-danger">' + msg + '</div>');
        }
    });
    return false;
});

$('#myModalNoticia').on('show.bs.modal', function(e) {    
 var idioma = $(e.relatedTarget).data().idioma;
  $(e.currentTarget).find('#idioma').val(idioma);
  //cambio nombres de campos según idioma
  $("input[name=titulo_]").attr("name", "titulo_" + idioma);
  $("input[name=subtitulo_]").attr("name", "subtitulo_" + idioma);
  $("input[name=url_]").attr("name", "url_" + idioma);
  $("textarea[name=contenido1_]").attr("name", "contenido1_" + idioma);
  $("textarea[name=contenido2_]").attr("name", "contenido2_" + idioma);
  $("input[name=orden]").attr("name", "orden");
  $("input[name=imagen_]").attr("name", "imagen_" + idioma);
  $("input[name=archivo1_]").attr("name", "archivo1_" + idioma);
});

$('#modificarNoticia').on('show.bs.modal', function(e) {    
 var idioma = $(e.relatedTarget).data().idioma;
 var id = $(e.relatedTarget).data().id;
 var titulo = $(e.relatedTarget).data().titulo;
 var subtitulo = $(e.relatedTarget).data().subtitulo;
 var url = $(e.relatedTarget).data().url;
 var contenido1 = $(e.relatedTarget).data().contenido1;
 var contenido2 = $(e.relatedTarget).data().contenido2;
 var categoria = $(e.relatedTarget).data().categoria;
 var imagen = $(e.relatedTarget).data().imagen;
 var archivo = $(e.relatedTarget).data().archivo;
 var orden = $(e.relatedTarget).data().orden;
 var destacado = $(e.relatedTarget).data().destacado;
 var estado = $(e.relatedTarget).data().estado;
  $(e.currentTarget).find('#idioma').val(idioma);
  $(e.currentTarget).find('#extension').val(idioma);
  $(e.currentTarget).find('#id').val(id);
  $(e.currentTarget).find('#titulo').val(titulo);
  $(e.currentTarget).find('#subtitulo').val(subtitulo);
  $(e.currentTarget).find('#url').val(url);
  $(e.currentTarget).find('#contenido1').text(contenido1);
  $(e.currentTarget).find('#contenido2').text(contenido2);
  $(e.currentTarget).find('#categoria').val(categoria);
  $(e.currentTarget).find('#imagen').html(imagen);
  $(e.currentTarget).find('#archivo').html(archivo);
  $(e.currentTarget).find('#orden').val(orden);
  $(e.currentTarget).find('#destacado').val(destacado);
  $(e.currentTarget).find('#estado').val(estado);
  
  //cambio nombres de campos según idioma
  $("input[name=titulo_]").attr("name", "titulo_" + idioma);
  $("input[name=subtitulo_]").attr("name", "subtitulo_" + idioma);
  $("input[name=url_]").attr("name", "url_" + idioma);
  $("textarea[name=contenido1_]").attr("name", "contenido1_" + idioma);
  $("textarea[name=contenido2_]").attr("name", "contenido2_" + idioma);
  $("input[name=imagen2_]").attr("name", "imagen2_" + idioma);
  $("input[name=archivo1_]").attr("name", "archivo1_" + idioma);
});


  $('#modalEliminarNoticia').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var seccion = $(e.relatedTarget).data().seccion;
     var estado = $(e.relatedTarget).data().estado;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#seccion').val(seccion);
      $(e.currentTarget).find('#estado').val(estado);
  });

</script>      
      
                                       