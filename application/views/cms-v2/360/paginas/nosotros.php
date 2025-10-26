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
									$item = $this->Paginas_model->getPaginaDetalleIdioma($detalle['id'], $idioma['extension']);
								}
							?>
	                            <div class="panel-body">
								 <div class="row">
	
					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Contenido</h2>
					                 	<div class="form-group m-b-md pull-left full-width m-t-md">
											<label class="col-sm-2 control-label">T&iacute;tulo </label>
											<div class="col-sm-4">
												<div class="input-group"><input type="text" name="titulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título general de la sección." title=""> <i class="fa fa-question"></i></button></span></div>
											</div>
					                 	</div>
					                </div>

					                <?php 
						                if(!empty($categorias)) {
										foreach($categorias as $categoria) { ?>

					                    <div class="col-sm-12 p-xxs">
						                    <div class="pull-left full-width">
							                	<h3 class="bg-muted p-xs pull-left full-width"><?php echo $categoria['seccion']; ?><a title="Ordenar" href="<?php echo base_url('cms-v2/paginas/ordenar/'.$detalle['id'].'/'.$categoria['id'].'/'.$idioma['extension']);?>" class="sepV_a btn btn-primary btn-sm red pull-right m-l-sm"><i class="fa fa-sort-circle"></i> Ordenar</a> 
							                	<a title="Ingresar" id="item" href="#" data-toggle="modal" data-id="<?php echo $categoria['id']; ?>" data-id_contenido="<?php echo $detalle['id'];?>" data-idioma="<?php echo $idioma['extension'];?>" data-target="#myModalIngresarInformacion" class="sepV_a btn btn-primary btn-sm red pull-right"><i class="fa fa-plus-circle"></i> Ingresar</a></h3>

							                <?php 
												$parametros['id'] = $detalle['id'];
												$parametros['id_categoria'] = $categoria['id']; 
												$parametros['idioma'] = $idioma['extension'];
												$miembros= $CI->Paginas_model->getContenidoAdicionalIdioma($parametros);

								               if(!empty($miembros)) {
												foreach($miembros as $miembro) { ?>	
								                <div class="col-md-6 col-lg-3 box-items">
													<div class="contact-box<?php echo ($miembro['estado'] == 1) ? ' bg-inactiva' : '';?>">
									                    <div class="col-sm-12">
									                        <div class="text-center">
									                            <?php if($miembro['imagen']) { ?>
									                            	<img src="<?php echo base_url('multimedia/thumbs/'.$miembro['imagen']);?>" title="<?php echo $miembro['titulo']; ?>" alt="<?php echo $miembro['titulo'];?>" class="m-b-xs">
									                            <?php } else { ?>
									                            <p>Sin imagen</p>
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
     
                                                 