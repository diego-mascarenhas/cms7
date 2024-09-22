<style>
.note-editor.note-frame { border:0;}
.contact-box { min-height: 300px;max-height: 300px; padding:20px 10px;display: flex;flex-direction: column;justify-content: center;}
.contact-box img { height: 100px; width:auto;}
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
									$imagen = $this->Paginas_model->getMedia($detalle['id'], $idioma['extension'], 13);
									$item = $this->Paginas_model->getPaginaDetalleIdioma($detalle['id'], $idioma['extension']);
								}
							?>
	                            <div class="panel-body m-b-lg">
								 <div class="row">
	
					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Contenido</h2>
					                 	<div class="form-group m-b-md pull-left full-width m-t-md">
											<div class="col-sm-12 no-padding">
												<label class="col-sm-2 col-md-1 control-label">Título</label>
												<div class="col-sm-4 col-md-5 m-b-md">
			                                        <div class="input-group">
														<input type="text" name="titulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo'] : null; ?>"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título general de la sección."> <i class="fa fa-question"></i></button></span>
				                                    </div>
												</div>
											</div>
					                 	</div>
					                 	<div class="form-group m-b-md pull-left full-width">
											<div class="col-sm-6">
												<div class="ibox-title bg-muted"><h5>Texto</h5><button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Contenido previo al formulario. No es obligatorio, se mostrará sólo si se agrega contenido en este box."> <i class="fa fa-question"></i></button></div>
												<div class="ibox-content no-padding"><textarea class="form-control summernote" name="contenido1_<?php echo $idioma['extension'];?>" rows="3"><?php echo(isset($item['contenido1'])) ? $item['contenido1']: null?></textarea></div>
											</div>
											<?php if(isset($imagen)) { ?>
											<div class="col-sm-6">
			                            		<label class="text-right col-sm-4 control-label">Imagen Actual</label>
			                            		<div class="col-sm-8">
			                            			<img src="<?php echo base_url('/multimedia/thumbs/'.$imagen['imagen_breadcrumb']);?>" style="height:auto;width:250px; max-width:100%;float: left;padding-bottom: 24px;padding-right: 25px;"/></div>
												<label class="col-sm-4 col-md-4 control-label text-right">Imagen</label>
			                            		<div class="col-sm-8">
			                                        <div class="input-group">
				                                       <input type="file" name="imagen_<?php echo $idioma['extension'];?>" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen en jpg, gif o png de 680x620 píxeles. Recuerde que el peso debe ser el mínimo posible para no afectar la descarga del site." title=""> <i class="fa fa-question"></i></button></span>
				                                    </div>
								                </div>
											</div>
											<?php } else { ?>
											<label class="col-sm-2 col-md-1 control-label text-right">Imagen</label>
											<div class="col-sm-4 col-md-5 m-b-md">
		                                        <div class="input-group">
			                                       <input type="file" name="imagen_<?php echo $idioma['extension'];?>" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen en jpg, gif o png de 680x620 píxeles. Recuerde que el peso debe ser el mínimo posible para no afectar la descarga del site." title=""> <i class="fa fa-question"></i></button></span>
			                                    </div>
							                </div>
											<?php } ?>
										</div>
				                 	</div>

					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Contenido Fondo Celeste</h2>
					                 	<div class="form-group pull-left full-width m-t-md">
											<label class="col-sm-2 col-md-1 control-label">Título</label>
											<div class="col-sm-4 col-md-5 m-b-md">
		                                        <div class="input-group">
													<input type="text" name="subtitulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['subtitulo'])) ? $item['subtitulo'] : null; ?>"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="texto breve para mostrar en una línea sin destacar."> <i class="fa fa-question"></i></button></span>
			                                    </div>
											</div>
											<label class="col-sm-2 col-md-1 control-label">Título Destacado</label>
											<div class="col-sm-4 col-md-5 m-b-md">
		                                        <div class="input-group">
													<input type="text" name="texto_adicional_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['texto_adicional'])) ? $item['texto_adicional'] : null; ?>"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="texto breve para mostrar en una línea destacado."> <i class="fa fa-question"></i></button></span>
			                                    </div>
											</div>
											<div class="col-sm-6 no-padding">
												<label class="col-md-6 col-lg-2 control-label">Nro. Box 1</label>
												<div class="col-md-6 col-lg-4 m-b-md">
			                                        <div class="input-group">
														<input type="text" name="contenido2_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['contenido2'])) ? $item['contenido2'] : null; ?>"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de la sección, que se mostrará con el subrayado."> <i class="fa fa-question"></i></button></span>
				                                    </div>
												</div>
												<label class="col-md-6 col-lg-2 control-label">Texto Box 1</label>
												<div class="col-md-6 col-lg-4 m-b-md">
			                                        <div class="input-group">
														<input type="text" name="contenido3_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['contenido3'])) ? $item['contenido3'] : null; ?>"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de la sección, que se mostrará con el subrayado."> <i class="fa fa-question"></i></button></span>
				                                    </div>
												</div>
											</div>
											<div class="col-sm-6 no-padding">
												<label class="col-md-6 col-lg-2 control-label">Nro. Box 2</label>
												<div class="col-md-6 col-lg-4 m-b-md">
			                                        <div class="input-group">
														<input type="text" name="contenido4_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['contenido4'])) ? $item['contenido4'] : null; ?>"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de la sección, que se mostrará con el subrayado."> <i class="fa fa-question"></i></button></span>
				                                    </div>
												</div>
												<label class="col-md-6 col-lg-2 control-label">Texto Box 2</label>
												<div class="col-md-6 col-lg-4 m-b-md">
			                                        <div class="input-group">
														<input type="text" name="contenido5_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['contenido5'])) ? $item['contenido5'] : null; ?>"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de la sección, que se mostrará con el subrayado."> <i class="fa fa-question"></i></button></span>
				                                    </div>
												</div>
											</div>
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