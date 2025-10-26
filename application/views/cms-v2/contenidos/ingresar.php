<style>
.btn-file>input { position: absolute;top: 0;right: 0;margin: 0;opacity: 0;filter: alpha(opacity=0);font-size: 23px;height: 100%;width: 100%;direction: ltr;cursor: pointer;}
.skin-1 .ibox-content:last-child {border-style: solid solid solid solid;}
.ibox-title,.ibox-content {border-width: 1px;}
.b_bottom { border-bottom: 1px solid #e7eaec }
.note-editor.note-frame { border: none;}
.btn_eliminar_popup { border:0; background:none;}
.m_t_20 { margin-top:20px !important;}
.m_t_b_5 { margin:5px 0px !important;}
.p_b_25 { padding-bottom:25px !important;}
.note-editing-area, .panel-heading { text-align:left;}
</style>

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-xs-8 col-sm-8 col-lg-8">
                <h2>Sitio web Contenidos</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="/cms">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('cms-v2/contenidos/');?>">Contenidos</a>
                    </li>
                    <li class="active">
                        <strong><?php echo (empty($item['id_contenido'])) ? 'Crear nuevo' : 'Modificar'; ?></strong>
                    </li>
                </ol>
            </div>

            <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
			<input type="hidden" name="id" value="<?php echo (!empty($item['id_contenido'])) ? $item['id_contenido'] : null; ?>">
			<input type="hidden" name="categoria" value="<?php echo $categoria['id']; ?>">
            <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
                <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
                <button class="btn btn-primary" type="submit">Guardar cambios</button>
            </div>
        </div>
                       
        <div class="wrapper wrapper-content animated fadeInRight p_b_25">
            <!-- Titulo Mensajes -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox-title ibox-title-custom"><h5>Subir contenido para <a><?php echo $categoria['seccion']; ?></a></h5>
					</div>
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
        <div class="wrapper wrapper-content animated fadeInRight" style="padding-top:0 !important;">
            <div class="row">

		  <!-- Si es home -->
   		  <?php if($categoria['id'] == 1) { ?>
   		  <link href="<?php echo base_url('assets/css/plugins/colorpicker/bootstrap-colorpicker.min.css'); ?>" rel="stylesheet" type="text/css">

			<div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title"><h5>Información del contenido</h5>
                        <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                    </div>
                    <div class="ibox-content" style="float:left;width:100%;">
	                 	<div class="form-group">
		                    <label class="text-right col-sm-1 control-label">T&iacute;tulo</label>
		                    <div class="col-sm-5 col-md-4"><input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['id_contenido'])) ? $item['titulo']: null; ?>"></div>
		                    <label class="text-right col-sm-1 control-label">Nombre (url)</label>
		                    <div class="col-sm-5 col-md-3"><input type="text" name="url" class="form-control" value="<?php echo (isset($item['id_contenido'])) ? $item['url']: null; ?>"></div>
		                    <label class="text-right col-sm-1 control-label">Estado</label>
		                    <div class="col-sm-2">
			                    <?php echo (isset($item['id_contenido'])) ? form_dropdown('estado', $estados, $item['id_estado'], array('class'=>'form-control m-b')) : form_dropdown('estado', $estados, null, array('class'=>'form-control m-b')); ?></div>
			                 <br><br><br>
	                 	</div>

	                 	<div class="form-group">
							<div class="col-lg-7 col-lg-offset-1">
			                    <div class="ibox-title" style="background:#F5EFEF;"><h5>Contenido Sole y la cocina</h5></div>
			                    	<div class="ibox-content no-padding"><textarea class="form-control summernote" name="contenido1" rows="11"><?php echo (isset($item['id_contenido'])) ? $item['contenido1']: null; ?></textarea></div>
		                   	</div>
		                    <!-- Multimedia -->
                            <label class="col-sm-1 control-label">Canal Video</label>
                            <div class="col-sm-2">
                                <?php echo form_dropdown('media_proyecto', $media_proyectos, (isset($item['media_proyecto'])) ? $item['media_proyecto'] : null, 'class="form-control m-b"'); ?>
                            </div>
			                 <br><br><br>
	                 	</div>
                	</div>
				</div>
			</div>

			<div class="col-lg-12" style="margin-bottom:25px; margin-top:25px;">
                <div class="ibox float-e-margins collapsed">
                    <div class="ibox-title"><h5>SEO</h5>
                        <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                    </div>

                    <div class="ibox-content" style="float: left; width:100%;">
						<div class="col-lg-12">
		                 	<div class="form-group">
			                    <div class="col-md-4 sm-12 m_b_25">
			                    	<div class="ibox-title" style="background:#F5EFEF;"><h5>Título</h5></div>
				                    <div class="ibox-content no-padding"><textarea class="form-control" name="seo_titulo" rows="5"><?php echo (isset($item['seo_titulo'])) ? $item['seo_titulo']: null?></textarea></div>
			                    </div>
			                    <div class="col-md-4 sm-12 m_b_25">
			                    	<div class="ibox-title" style="background:#F5EFEF;"><h5>Descripci&oacute;n</h5></div>
				                    <div class="ibox-content no-padding"><textarea class="form-control" name="seo_descripcion" rows="5"><?php echo (isset($item['seo_descripcion'])) ? $item['seo_descripcion']: null?></textarea></div>
			                    </div>
			                    <div class="col-md-4 sm-12 m_b_25">
			                    	<div class="ibox-title" style="background:#F5EFEF;"><h5>Keywords</h5></div>
				                    <div class="ibox-content no-padding"><textarea class="form-control" name="seo_keywords" rows="5"><?php echo (isset($item['seo_keywords'])) ? $item['seo_keywords']: null?></textarea></div>
			                    </div>
		                 	</div>
						</div>

                    </div>
                </div>
			</div>

   		  <?php } elseif ($categoria['id'] == 2) { ?>
			<div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title"><h5>Información del contenido</h5>
                        <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                    </div>
                    <div class="ibox-content" style="float:left;width:100%;">
	                 	<div class="form-group">
		                    <label class="text-right col-sm-1 control-label">T&iacute;tulo</label>
		                    <div class="col-sm-5 col-md-2"><input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['id_contenido'])) ? $item['titulo']: null; ?>"></div>
		                    <label class="text-right col-sm-1 control-label">Nombre (url)</label>
		                    <div class="col-sm-5 col-md-2"><input type="text" name="url" class="form-control" value="<?php echo (isset($item['id_contenido'])) ? $item['url']: null; ?>"></div>
		                    <label class="text-right col-sm-1 control-label">Subt&iacute;tulo</label>
		                    <div class="col-sm-5 col-md-3"><input type="text" name="subtitulo" class="form-control" value="<?php echo (isset($item['id_contenido'])) ? $item['subtitulo']: null; ?>"></div>
		                    <label class="text-right col-sm-1 control-label">Estado</label>
		                    <div class="col-sm-1">
			                    <?php echo (isset($item['id_contenido'])) ? form_dropdown('estado', $estados, $item['id_estado'], array('class'=>'form-control m-b')) : form_dropdown('estado', $estados, null, array('class'=>'form-control m-b')); ?></div>
			                 <br><br><br>
	                 	</div>

	                 	<div class="form-group">
							<div class="col-lg-7 col-lg-offset-1">
			                    <div class="ibox-title" style="background:#F5EFEF;"><h5>Contenido</h5></div>
			                    	<div class="ibox-content no-padding"><textarea class="form-control summernote" name="contenido1" rows="11"><?php echo (isset($item['id_contenido'])) ? $item['contenido1']: null; ?></textarea></div>
		                   	</div>
		                    <!-- Multimedia -->
                            <label class="col-sm-1 control-label">Canal Video</label>
                            <div class="col-sm-2">
                                <?php echo form_dropdown('media_proyecto', $media_proyectos, (isset($item['media_proyecto'])) ? $item['media_proyecto'] : null, 'class="form-control m-b"'); ?>
                            </div>
			                 <br><br><br>
	                 	</div>
                	</div>
				</div>
			</div>

			<div class="col-lg-12" style="margin-bottom:25px; margin-top:25px;">
                <div class="ibox float-e-margins collapsed">
                    <div class="ibox-title"><h5>SEO</h5>
                        <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                    </div>

                    <div class="ibox-content" style="float: left; width:100%;">
						<div class="col-lg-12">
		                 	<div class="form-group">
			                    <div class="col-md-4 sm-12 m_b_25">
			                    	<div class="ibox-title" style="background:#F5EFEF;"><h5>Título</h5></div>
				                    <div class="ibox-content no-padding"><textarea class="form-control" name="seo_titulo" rows="5"><?php echo (isset($item['seo_titulo'])) ? $item['seo_titulo']: null?></textarea></div>
			                    </div>
			                    <div class="col-md-4 sm-12 m_b_25">
			                    	<div class="ibox-title" style="background:#F5EFEF;"><h5>Descripci&oacute;n</h5></div>
				                    <div class="ibox-content no-padding"><textarea class="form-control" name="seo_descripcion" rows="5"><?php echo (isset($item['seo_descripcion'])) ? $item['seo_descripcion']: null?></textarea></div>
			                    </div>
			                    <div class="col-md-4 sm-12 m_b_25">
			                    	<div class="ibox-title" style="background:#F5EFEF;"><h5>Keywords</h5></div>
				                    <div class="ibox-content no-padding"><textarea class="form-control" name="seo_keywords" rows="5"><?php echo (isset($item['seo_keywords'])) ? $item['seo_keywords']: null?></textarea></div>
			                    </div>
		                 	</div>
						</div>

                    </div>
                </div>
			</div>
   		  <?php } elseif ($categoria['id'] == 5) { ?>
            <!-- Imagenes Generales -->
			<div class="col-lg-12" style="margin-top:25px;margin-bottom:25px;">
                <div class="ibox float-e-margins">
                    <div class="ibox-title"><h5>Información del contenido</h5>
                        <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                    </div>
                    <div class="ibox-content" style="float:left;width:100%;">
	                 	<div class="form-group">
		                    <label class="text-right col-sm-1 control-label">T&iacute;tulo</label>
		                    <div class="col-sm-5 col-md-3"><input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['id_contenido'])) ? $item['titulo']: null; ?>"></div>
		                    <label class="text-right col-sm-1 control-label">Nombre (url)</label>
		                    <div class="col-sm-5 col-md-2"><input type="text" name="url" class="form-control" value="<?php echo (isset($item['id_contenido'])) ? $item['url']: null; ?>"></div>
		                    <label class="text-right col-sm-1 control-label">Estado</label>
		                    <div class="col-sm-1">
			                    <?php echo (isset($item['id_contenido'])) ? form_dropdown('estado', $estados, $item['id_estado'], array('class'=>'form-control m-b')) : form_dropdown('estado', $estados, null, array('class'=>'form-control m-b')); ?></div>
		                    <!-- Multimedia -->
                            <label class="col-sm-1 control-label">Canal Videos</label>
                            <div class="col-sm-2">
                                <?php echo form_dropdown('media_proyecto', $media_proyectos, (isset($item['media_proyecto'])) ? $item['media_proyecto'] : null, 'class="form-control m-b"'); ?>
                            </div>
			                 <br><br><br>

				            <div class="col-sm-5">
				            	<div class="ibox-title" style="background:#F5EFEF;"><h5>Imagen</h5></div>
								<div class="ibox-content b_bottom">
				                    <?php if(!empty($item['imagen'])) { ?>
				                	<p>Imagen Actual</p>
				                	<img src="<?php echo base_url('/multimedia/511/7358/'.$item['imagen']);?>" title="<?php echo $item['titulo'];?>" alt="<?php echo $item['titulo'];?>" style="height:100px;float: left;padding-bottom: 24px;padding-right: 25px;"/>
	                            <?php } ?>
									<br><br>
		                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
		                                <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
		                                <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Seleccionar imagen</span><input type="file" name="image"></span>
			                    	</div>
								</div>
				            </div>

				            <div class="col-sm-7">
			                    <div class="ibox-title" style="background:#F5EFEF;"><h5>Contenido</h5></div>
			                    	<div class="ibox-content no-padding"><textarea class="form-control summernote" name="contenido1" rows="11"><?php echo (isset($item['id_contenido'])) ? $item['contenido1']: null; ?></textarea></div>
		                   	</div>

			           </div>
					</div>
				</div>
            </div>            
   		  <?php } elseif ($categoria['id'] == 6) { ?>
			<div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title"><h5>Información del contenido</h5>
                        <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                    </div>
                    <div class="ibox-content" style="float:left;width:100%;">
	                 	<div class="form-group">
		                    <label class="text-right col-sm-1 control-label">T&iacute;tulo</label>
		                    <div class="col-sm-5 col-md-4"><input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['id_contenido'])) ? $item['titulo']: null; ?>"></div>
		                    <label class="text-right col-sm-1 control-label">Estado</label>
		                    <div class="col-sm-2">
			                    <?php echo (isset($item['id_contenido'])) ? form_dropdown('estado', $estados, $item['id_estado'], array('class'=>'form-control m-b')) : form_dropdown('estado', $estados, null, array('class'=>'form-control m-b')); ?></div>
			                 <br><br><br>
	                 	</div>
                    </div>
                </div>
            </div>

			<div class="col-lg-12" style="margin-top:25px; margin-bottom:25px;">
                <div class="ibox float-e-margins">
                    <div class="ibox-title"><h5>Contenido </h5></div>

                    <div class="ibox-content" style="float: left; width:100%;">
						<div class="col-lg-12">
		                    <div class="ibox-title" style="background:#F5EFEF;"><h5>Contenido</h5></div>
		                    	<div class="ibox-content no-padding"><textarea class="form-control summernote" name="contenido1" rows="11"><?php echo (isset($item['id_contenido'])) ? $item['contenido1']: null; ?></textarea></div>
	                   	</div>
                	</div>
				</div>
			</div>

   		  <?php } elseif ($categoria['id'] == 66) { ?>
			<div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title"><h5>Información del contenido</h5>
                        <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                    </div>
                    <div class="ibox-content" style="float:left;width:100%;">
	                 	<div class="form-group">
		                    <label class="text-right col-sm-1 control-label">T&iacute;tulo</label>
		                    <div class="col-sm-5 col-md-4"><input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['id_contenido'])) ? $item['titulo']: null; ?>"></div>
		                    <label class="text-right col-sm-1 control-label">Estado</label>
		                    <div class="col-sm-2">
			                    <?php echo (isset($item['id_contenido'])) ? form_dropdown('estado', $estados, $item['id_estado'], array('class'=>'form-control m-b')) : form_dropdown('estado', $estados, null, array('class'=>'form-control m-b')); ?></div>
			                 <br><br><br>
	                 	</div>
                    </div>
                </div>
            </div>

			<div class="col-lg-12" style="margin-top:25px; margin-bottom:25px;">
                <div class="ibox float-e-margins">
                    <div class="ibox-title"><h5>Contenido </h5></div>

                    <div class="ibox-content" style="float: left; width:100%;">
						<div class="col-lg-12">
		                    <div class="ibox-title" style="background:#F5EFEF;"><h5>Contenido</h5></div>
		                    	<div class="ibox-content no-padding"><textarea class="form-control summernote" name="contenido1" rows="11"><?php echo (isset($item['id_contenido'])) ? $item['contenido1']: null; ?></textarea></div>
	                   	</div>
                	</div>
				</div>
			</div>
   		  <?php } ?>

		<?php echo form_close(); ?>
		<!-- Fin Contenido -->

		  <!-- Si es home -->
   		  <?php if($categoria['id'] == 1) { ?>
         <!-- Imagenes Adicionales del Slide-->
			<div class="col-lg-12" style="margin-bottom:25px;">
                <div class="ibox float-e-margins">
                    <div class="ibox-title"><h5>Slideshow imágenes</h5>
				       <div class="ibox-tools">  <a class="collapse-link pull-right"><i class="fa fa-chevron-up"></i></a><?php if(!empty($slides)) { ?><a class="btn btn-primary pull-right btn-sm" type="submit" href="<?php echo base_url('cms-v2/contenidos/ordenar/'.$item['id_contenido'].'/?id_tipo=2');?>" style="margin-top:-8px; margin-right:10px;">Ordenar</a><?php } ?>	</div>
                    </div>


                    <div class="ibox-content" style="float: left; width:100%;">
						<div class="col-lg-12">
							<h2>Imágenes subidas</h2>
			                <?php 
				               if(!empty($slides)) {
								foreach($slides as $slide) { ?>	
						   		<div class="file-box" style="margin-top:20px; width: 250px;height: 250px !important;">
						   			<div class="file" style="height: 100%; background: #f8f8f8;">
						   				<a href="#">
						   					<span class="corner"></span>
		                                    <div class="image" style="height:140px;overflow: hidden;"><img src="<?php echo base_url('/multimedia/511/7358/'.$slide['imagen']);?>" title="<?php echo $slide['titulo']; ?>" alt="<?php echo $slide['titulo'];?>" style="width:100%; height: auto; overflow: hidden;"></div>
		                                <div class="file-name">
											<?php echo ellipsize($slide['titulo'], 25, .3); ?><br>
		                                    <small>Subida: <?php echo $slide['fecha_alta']; ?></small><br><br>
		                                    <a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $slide['titulo'];?>" data-id="<?php echo $slide['id'];?>" data-target="#myModalseis" class="btn btn-xs btn-danger"><i class="fa fa-minus-circle"></i> Eliminar</a>
		                                </div>
										</a>
									</div>
								</div>
		                   <?php } } else { echo 'No se encontraron resultados'; } ?>	

						 	<div class="hr-line-dashed" style="float: left; width:100%;"></div>
		                 	<div class="form-group">
								<?php if(!empty($item['id_contenido'])) { ?> <a title="Ingresar" id="item" href="#" data-toggle="modal" data-seccion='<?php echo $item['titulo']; ?>' data-id="<?php echo $item['id_contenido']; ?>" data-target="#myModalsiete" class="sepV_a btn btn-primary btn-sm red pull-left"><i class="fa fa-plus-circle"></i> Ingresar nueva</a><?php } ?><br><br>
							</div>
						</div>
                	</div>
				</div>
          </div>
          <!-- Fin Imagenes -->
          <!-- Boxes -->
          <div class="col-lg-12 m_t_25">
            <div class="ibox float-e-margins">
                <div class="ibox-title"><h5>Boxes</h5>
                    <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                 </div>
                <div class="ibox-content">
	                <?php if ( (isset($_GET['error'])) && ($_GET['error'] == 'cuenta')) : ?>
					<div class="col-md-12">
						<div class="alert alert-danger" role="alert"><?php echo 'Debe completar los campos'; ?></div>
					</div>
					<?php endif; ?>
                    <table class="table table-striped table-bordered table-hover dataTables-example">
	                    <thead>
	                    <tr>
	                        <th>Orden</th>
	                        <th>&Iacute;cono</th>
	                        <th>Color</th>
	                        <th>T&iacute;tulo</th>
	                        <th>Contenido</th>
	                        <th>Acciones</th>
	                    </tr>
	                    </thead>
	                    <tbody>
	                    
	                <?php if(!empty($boxes)) {
						foreach($boxes as $box) { ?>	
	                   	 <tr class="gradeX">
	                        <td><?php echo $box['orden'];?></td>
	                        <td><?php echo $box['subtitulo'];?></td>
	                        <td><?php echo $box['contenido2'];?></td>
	                        <td><?php echo $box['titulo'];?></td>
	                        <td><?php echo strip_tags($box['contenido1']);?></td>
	                        <td>
								<a title="Editar" id="titulo" href="#" data-toggle="modal" data-id_contenido="<?php echo $item['id_contenido'];?>" data-orden="<?php echo $box['orden'];?>" data-subtitulo="<?php echo strip_tags($box['subtitulo']);?>" data-contenido2="<?php echo strip_tags($box['contenido2']);?>" data-seccion='<?php echo strip_tags($box['titulo']);?>' data-contenido1='<?php echo strip_tags($box['contenido1']);?>'  data-id="<?php echo $box['id'];?>" data-target="#myModaldos" class="btn btn-info btn-circle"> <i class="fa fa-pencil"></i></a>
	                        </td>
	                    </tr>
	                   <?php } } else { echo 'No se encontraron resultados';}?>	
	                    </tbody>
                    </table>

<!--
                 	<div class="form-group">
						<?php if(!empty($item['id_contenido'])) { ?> <a title="Ingresar" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $item['titulo'];?>" data-id="<?php echo $item['id_contenido'];?>" data-target="#myModaldos" class="sepV_a btn btn-primary btn-sm red pull-left"><i class="fa fa-plus-circle"></i> Ingresar nueva	</a><?php } ?><br><br>
					</div>
-->
		   		</div>
		   	</div>
       </div>
       


          <!-- Informacion -->
          <div class="col-lg-12 m_t_25">
            <div class="ibox float-e-margins">
                <div class="ibox-title"><h5>Recomendaciones</h5>
                    <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                 </div>
                <div class="ibox-content">
	                <?php if ( (isset($_GET['error'])) && ($_GET['error'] == 'informacion')) : ?>
					<div class="col-md-12">
						<div class="alert alert-danger" role="alert"><?php echo 'Debe completar el campo recomendaci&oacute;n'; ?></div>
					</div>
					<?php endif; ?>

                   <table class="table table-striped table-bordered table-hover dataTables-example">
	                    <thead>
	                    <tr>
	                        <th>Orden</th>
	                        <th>T&iacute;tulo</th>
	                        <th>Firma</th>
	                        <th>Texto</th>
	                        <th>Acciones</th>
	                    </tr>
	                    </thead>
	                    <tbody>
                       <?php if(!empty($recomendaciones)) {
                       foreach($recomendaciones as $recomendacion) { ?> 
	                   	 <tr class="gradeX">
	                        <td><?php echo $recomendacion['orden'];?></td>
	                        <td><?php echo $recomendacion['titulo'];?></td>
	                        <td><?php echo $recomendacion['contenido2'];?></td>
	                        <td><?php echo $recomendacion['contenido1'];?></td>
	                        <td>
								<a title="Editar" id="titulo" href="#" data-toggle="modal" data-id_contenido="<?php echo $item['id_contenido'];?>" data-orden="<?php echo $recomendacion['orden'];?>" data-seccion='<?php echo strip_tags($recomendacion['titulo']);?>' data-contenido2="<?php echo strip_tags($recomendacion['contenido2']);?>" data-contenido1="<?php echo strip_tags($recomendacion['contenido1']);?>" data-id="<?php echo $recomendacion['id'];?>" data-target="#myModalEditarInformacion" class="btn btn-info btn-circle"> <i class="fa fa-pencil"></i></a>
								<a title="Eliminar" id="titulo" href="#" data-toggle="modal" data-seccion='<?php echo strip_tags($recomendacion['titulo']);?>' data-id="<?php echo $recomendacion['id'];?>" data-target="#myModalcinco" class="btn btn-danger btn-circle"> <i class="fa fa-times"></i></a>
	                        </td>
	                    </tr>
	                   <?php } } else { echo 'No se encontraron resultados';}?>	
	                    </tbody>
                    </table>



                 	<div class="form-group">
						<?php if(!empty($item['id_contenido'])) { ?> <a title="Ingresar" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $item['titulo'];?>" data-id="<?php echo $item['id_contenido'];?>" data-target="#myModalcuatro" class="sepV_a btn btn-primary btn-sm red pull-left"><i class="fa fa-plus-circle"></i> Ingresar nueva	</a><?php } ?><br><br>
					</div>
		   		</div>
		   	</div>
       </div>

          <!-- Imagenes -->
			<div class="col-lg-12" style="margin-top:25px;margin-bottom:25px;">
                <div class="ibox float-e-margins">
                    <div class="ibox-title"><h5>Partners</h5>
				       <div class="ibox-tools">  <a class="collapse-link pull-right"><i class="fa fa-chevron-up"></i></a><?php if(!empty($partners)) { ?>
                        <a class="btn btn-primary pull-right btn-sm" type="submit" href="<?php echo base_url('cms-v2/contenidos/ordenar/'.$item['id_contenido'].'/?id_tipo=3');?>" style="margin-top:-8px; margin-right:10px;">Ordenar</a>
  		                   <?php } ?>	</div>
                    </div>

                    <div class="ibox-content" style="float: left; width:100%;">
						<div class="col-lg-12">
							<h2>Logos subidos</h2>
			                <?php 
				               if(!empty($partners)) {
								foreach($partners as $lista) { ?>	
						   		<div class="file-box" style="margin-top:20px; width: 250px;height: 250px !important;">
						   			<div class="file" style="height: 100%; background: #f8f8f8;">
						   				<a href="#">
						   					<span class="corner"></span>
		                                    <div class="image" style="height:140px;overflow: hidden;"><img src="<?php echo base_url('/multimedia/511/7358/'.$lista['imagen']);?>" title="<?php echo $lista['titulo']; ?>" alt="<?php echo $lista['titulo'];?>" style="width:100%; height: auto; overflow: hidden;"></div>
		                                <div class="file-name">
											<?php echo ellipsize($lista['titulo'], 25, .3); ?><br>
		                                    <small>Subida: <?php echo $lista['fecha_alta'];?></small><br><br>
		                                    <a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $lista['titulo'];?>" data-id="<?php echo $lista['id'];?>" data-target="#myModal" class="btn btn-xs btn-danger"><i class="fa fa-minus-circle"></i> Eliminar</a>
		                                </div>
										</a>
									</div>
								</div>
		                   <?php } } else { echo 'No se encontraron resultados';} ?>	
						</div>
						  
						<?php if(!empty($item['id_contenido'])) { ?>
						<div class="col-lg-12">
						 	<br><br><div class="hr-line-dashed"></div>
							<h2>Subir Imágenes</h2>
							
							<div class="table-responsive" style="background: none; border:0;">
								<?php echo form_open('cms-v2/contenidos/upload', array('class' =>'dropzone'));?>
								<input type="hidden" name="id_contenido" value="<?php echo $item['id_contenido']; ?>">
								<?php echo form_close();?>
							</div>
						</div>
						<?php } ?>
                	</div>
				</div>
          </div>
          <!-- Fin Imagenes -->
   		  <?php } elseif($categoria['id'] == 2) { ?>
         <!-- Imagenes Adicionales del Slide-->
			<div class="col-lg-12" style="margin-bottom:25px;">
                <div class="ibox float-e-margins">
                    <div class="ibox-title"><h5>Slideshow imágenes</h5>
				       <div class="ibox-tools">  <a class="collapse-link pull-right"><i class="fa fa-chevron-up"></i></a><?php if(!empty($slides)) { ?><a class="btn btn-primary pull-right btn-sm" type="submit" href="<?php echo base_url('cms-v2/contenidos/ordenar/'.$item['id_contenido'].'/?id_tipo=2');?>" style="margin-top:-8px; margin-right:10px;">Ordenar</a><?php } ?>	</div>
                    </div>


                    <div class="ibox-content" style="float: left; width:100%;">
						<div class="col-lg-12">
							<h2>Imágenes subidas</h2>
			                <?php 
				               if(!empty($slides)) {
								foreach($slides as $slide) { ?>	
						   		<div class="file-box" style="margin-top:20px; width: 250px;height: 250px !important;">
						   			<div class="file" style="height: 100%; background: #f8f8f8;">
						   				<a href="#">
						   					<span class="corner"></span>
		                                    <div class="image" style="height:140px;overflow: hidden;"><img src="<?php echo base_url('/multimedia/511/7358/'.$slide['imagen']);?>" title="<?php echo $slide['titulo']; ?>" alt="<?php echo $slide['titulo'];?>" style="width:100%; height: auto; overflow: hidden;"></div>
		                                <div class="file-name">
											<?php echo ellipsize($slide['titulo'], 25, .3); ?><br>
		                                    <small>Subida: <?php echo $slide['fecha_alta']; ?></small><br><br>
		                                    <a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $slide['titulo'];?>" data-id="<?php echo $slide['id'];?>" data-target="#myModalseis" class="btn btn-xs btn-danger"><i class="fa fa-minus-circle"></i> Eliminar</a>
		                                </div>
										</a>
									</div>
								</div>
		                   <?php } } else { echo 'No se encontraron resultados'; } ?>	

						 	<div class="hr-line-dashed" style="float: left; width:100%;"></div>
		                 	<div class="form-group">
								<?php if(!empty($item['id_contenido'])) { ?> <a title="Ingresar" id="item" href="#" data-toggle="modal" data-seccion='<?php echo $item['titulo']; ?>' data-id="<?php echo $item['id_contenido']; ?>" data-target="#myModalsiete" class="sepV_a btn btn-primary btn-sm red pull-left"><i class="fa fa-plus-circle"></i> Ingresar nueva</a><?php } ?><br><br>
							</div>
						</div>
                	</div>
				</div>
          </div>
          <!-- Fin Imagenes -->
   		  <?php } ?>


		<br><br></div></div>
        <!-- Fin Tener en cuenta -->
<!-- Fin Contenido -->

          <!-- Footer -->
            <div class="footer">
	            <div class="pull-right">
	                <strong>CMS+</strong> ☰
	            </div>
	            <div>
	                <strong>Copyright</strong> revision alpha &copy;2002-2019
	            </div>
	        </div>
        </div>

        <div id="right-sidebar" class="animated">
            <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 100%;">
	            <div class="sidebar-container" style="overflow: hidden; width: auto; height: 100%;">

	                <ul class="nav nav-tabs navs-3">
	                    <li class="active">
	                    	<a data-toggle="tab" href="#tab-1">Notas</a>
	                    </li>
	                    <li>
	                    	<a data-toggle="tab" href="#tab-2">Tareas</a>
	                    </li>
	                    <li class="">
	                    	<a data-toggle="tab" href="#tab-3"><i class="fa fa-gear"></i></a>
	                    </li>
	                </ul>
	
	                <div class="tab-content">
						<div id="tab-1" class="tab-pane active">
							<div class="sidebar-title">
	                            <h3> <i class="fa fa-comments-o"></i> Ultimas Notas</h3>
	                            <small><i class="fa fa-tim"></i> Tenés <?php echo count($notas = $this->session->userdata('notas')); ?> <?php echo (count($notas) == 1) ? 'nueva nota' : 'nuevas notas'; ?>.</small>
	                        </div>
	                        <div>
		                        <?php if ($notas) { ?>
			                        <?php foreach ($notas as $nota) { ?>
		                            <div class="sidebar-message">
		                                <a href="<?php echo base_url($nota['uri'] . '/detalle/' . $nota['id_referencia']); ?>">
		                                    <div class="pull-left text-center">
		                                        <img alt="image" class="img-circle message-avatar" src="<?php echo base_url('multimedia/avatars/' . $nota['avatar']); ?>">
	<!--
		                                        <div class="m-t-xs">
		                                            <i class="fa fa-star text-warning"></i>
		                                            <i class="fa fa-star text-warning"></i>
		                                        </div>
	-->
		                                    </div>
		                                    <div class="media-body">
												<?php echo $nota['titulo']; ?>
		                                        <br>
		                                        <small class="text-muted"><?php echo formatear_fecha($nota['fecha_alta'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></small>
		                                    </div>
		                                </a>
		                            </div>
	                            	<?php } ?>
	                            <?php } ?>
	                        </div>
	                    </div>
	
	                    <div id="tab-2" class="tab-pane">
	                        <div class="sidebar-title">
	                            <h3> <i class="fa fa-cube"></i> Ultimas Tareas</h3>
	                            <small><i class="fa fa-tim"></i> Tenés <?php echo count($tareas = $this->session->userdata('tareas')); ?> tareas pendientes.<!--  10 no sin terminar. --></small>
	                        </div>
	                        <?php if ($tareas) { ?>
		                        <?php foreach ($tareas as $tarea) { ?>
		                        <ul class="sidebar-list">
		                            <li>
		                                <a href="<?php echo base_url('tareas/detalle/' . $tarea['id']); ?>">
			                                <span class="label label-<?php echo $tarea['estado_ui_class']; ?> pull-right"><?php echo $tarea['estado']; ?></span>
	<!-- 	                                    <div class="small pull-right m-t-xs">9 hours ago</div> -->
		                                    <h4><?php echo $tarea['titulo']; ?></h4>
		                                    <?php echo $tarea['descripcion']; ?>
		
	<!-- 	                                    <div class="small">Completion with: 22%</div> -->
	<!--
		                                    <div class="progress progress-mini">
		                                        <div style="width: 22%;" class="progress-bar progress-bar-warning"></div>
		                                    </div>
	-->
		                                    <div class="small text-muted m-t-xs"><?php echo formatear_fecha($tarea['hasta'], 'd-m-Y', null, $this->usuario->timezone); ?></div>
		                                </a>
		                            </li>
		                        </ul>
								<?php } ?>
	                        <?php } ?>
	                    </div>
	
	                    <div id="tab-3" class="tab-pane">
	                        <div class="sidebar-title">
	                            <h3><i class="fa fa-gears"></i> Configuración</h3>
	                            <small><i class="fa fa-tim"></i> Infórmanos como estas como para atender estas tareas.</small>
	                        </div>
	                    
	                    	<div class="setings-item">
							<span>Soporte Técnico</span>
	                            <div class="switch">
	                                <div class="onoffswitch">
	                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example4">
	                                    <label class="onoffswitch-label" for="example4">
	                                        <span class="onoffswitch-inner"></span>
	                                        <span class="onoffswitch-switch"></span>
	                                    </label>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="setings-item">
		                        
	                        <div class="sidebar-content">
	                            <h4>¿Y ahora?</h4>
	                            <div class="small">
	                                Por favor informanos en que estado estas para poder hacer sustentable la plataforma ;-)
	                            </div>
	                        </div>
	
	                    </div>
	                </div>
            	</div>
				<div class="slimScrollBar" style="background-color: rgb(0, 0, 0); width: 7px; position: absolute; top: 2px; opacity: 0.4; display: none; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; z-index: 99; right: 1px; height: 675.0260412A333D6px; background-position: initial initial; background-repeat: initial initial;"></div>
				<div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; background-color: rgb(51, 51, 51); opacity: 0.4; z-index: 90; right: 1px; background-position: initial initial; background-repeat: initial initial;"></div>
            </div>
		</div>
    </div>

<!-- Modal Eliminar la imagen-->
<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
	    <div class="modal-content animated">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title">Eliminar la imagen</h4>
	        </div>
	        <div class="modal-body">
		        <p>&iquest;Est&aacute; seguro de querer eliminar la imagen <strong> <input type="button" name="seccion" id="seccion" value="" class="btn_eliminar_popup"/></strong>?</p>
		        <div class="modal-footer">
		        	<form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('cms-v2/contenidos/eliminarmedia/'); ?>">
		            	<input type="hidden" name="id" id="id" value="" />
		            	<input type="hidden" name="id_contenido" value="<?php echo $item['id_contenido']; ?>">
		                <input type="submit" class="btn btn-primary" value="Eliminar">
		            </form>
		        </div>
	        </div>
	    </div>
    </div>
</div>
<!-- Fin Modal Eliminar la imagen-->

<!-- Modal Ingresar box de Home -->
<div class="modal inmodal" id="myModaldos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
   		<div class="modal-content animated">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title">Modificar box de Home</h4>
	        </div>
	
	        <div class="modal-body">
		        <p>Modifique item para:<strong> <input type="button" name="seccion" id="seccion" value="" class="btn_eliminar_popup"/></strong></p>
		        <div class="modal-footer">
		            <form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('cms-v2/contenidos/modificar_box/'); ?>">
	                   <div class="col-sm-2">
	                    	<label class="control-label pull-left">Orden</label>
	                    	<input type="text" name="orden" id="orden" value="" class="form-control">
	                   </div>
	                    <div class="col-sm-4 control-label">
	                    	<label class="control-label">Icono</label><?php echo form_dropdown('subtitulo', $iconos, null, array('id'=>'subtitulo', 'class'=>'form-control m-b')); ?>
	                    </div>
	                    <div class="col-sm-5 control-label">
	                    	<label class="control-label">Color de fondo</label>
	                    	<input type="text" name="contenido2" id="contenido2" value="" class="form-control">
	                    </div>
	                   <div class="col-sm-12">
	                    	<label class="control-label pull-left">T&iacute;tulo</label>
	                    	<input type="text" name="titulo" id="seccion" value="" class="form-control"><br>
	                    </div>
	                    <div class="col-sm-12 text-left">
	                    	<label class="control-label pull-left">Texto</label>
	                    	<input type="text" name="contenido1" id="contenido1" value="" class="form-control" style="height:70px;"><br>
	                    	<!-- <div class="ibox-content no-padding"><textarea class="form-control summernote" name="contenido1" rows="11"></textarea></div>-->
	                   </div>
	                    <div class="col-sm-12">
			            	<input type="hidden" name="id_tipo" value="4" />
			            	<input type="hidden" name="id" id="id" value="" />
			            	<input type="hidden" name="id_contenido" id="id_contenido" value="" />
			                <input type="submit" class="btn btn-primary" value="Ingresar">
	                    </div>
		            </form>
		        </div>
			</div>
  		</div>
	</div>
</div>
<!-- Fin Modal box de Home -->

<!-- Modal eliminar box de Home-->
<div class="modal inmodal" id="myModaltres" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
	    <div class="modal-content animated">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title">Eliminar box de Home</h4>
	        </div>
	        <div class="modal-body">
		        <p>&iquest;Est&aacute; seguro de querer eliminar el ítem de <strong> <input type="button" name="seccion" id="seccion" value="" class="btn_eliminar_popup"/></strong>?</p>
		        <div class="modal-footer">
		            <form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('cms-v2/contenidos/eliminar_contenido_adicional/'); ?>">
		            	<input type="hidden" name="id" id="id" value="" />
		            	<input type="hidden" name="id_contenido" id="id_contenido" value="<?php echo $item['id_contenido'];?>" />
		                <input type="submit" class="btn btn-danger" value="Eliminar">
		            </form>
		        </div>
	        </div>
	    </div>
    </div>
</div>
<!-- Fin Modal eliminar box de Home -->
						 	
<!-- Modal Ingresar Recomendacion -->
<div class="modal inmodal amimated fadeInDown fast" id="myModalcuatro" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
   		<div class="modal-content animated">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title">Ingresar ítem recomendaci&oacute;n</h4>
	        </div>
	
	        <div class="modal-body">
		        <p>Ingrese item para:<strong> <input type="button" name="seccion" id="seccion" value="" class="btn_eliminar_popup"/></strong></p>
		        <div class="modal-footer">
		            <form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('cms-v2/contenidos/ingresar_informacion/'); ?>">
	                   <div class="col-sm-2">
	                    	<label class="control-label pull-left">Orden</label>
	                    	<input type="text" name="orden" class="form-control">
	                   </div>
	                    <div class="col-sm-10">
	                    	<label class="control-label pull-left">T&iacute;tulo</label>
	                    	<input type="text" name="titulo" class="form-control">
	                    </div>
	                    <div class="col-sm-12">
	                    	<label class="control-label pull-left">Recomendaci&oacute;n</label>
		                    <div class="ibox-content no-padding"><textarea class="form-control summernote" name="contenido1" rows="11"></textarea></div>
	                    </div>
	                    <div class="col-sm-12">
	                    	<label class="control-label pull-left">Firma</label>
	                    	<input type="text" name="contenido2" class="form-control">
	                    	<br>
	                    </div>
		            	<input type="hidden" name="id_tipo" value="3" />
		            	<input type="hidden" name="id" id="id" value="" />
			            <input type="hidden" name="id_contenido" id="id_contenido" value="" />
		                <input type="submit" class="btn btn-primary" value="Ingresar">
		            </form>
		        </div>
			</div>
  		</div>
	</div>
</div>
<!-- Fin Modal Recomendacion -->

<!-- Modal Modificar Recomendacion -->
<div class="modal inmodal amimated fadeInDown fast" id="myModalEditarInformacion" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
   		<div class="modal-content animated">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title">Modificar ítem recomendaci&oacute;n</h4>
	        </div>
	
	        <div class="modal-body">
		        <p>Modifique item para:<strong> <input type="button" name="seccion" id="seccion" value="" class="btn_eliminar_popup"/></strong></p>
		        <div class="modal-footer">
		            <form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('cms-v2/contenidos/modificar_informacion/'); ?>">
	                   <div class="col-sm-2">
	                    	<label class="control-label pull-left">Orden</label>
	                    	<input type="text" name="orden" id="orden" value="" class="form-control">
	                   </div>
	                    <div class="col-sm-10">
	                    	<label class="control-label pull-left">T&iacute;tulo</label>
	                    	<input type="text" name="titulo" id="seccion" value="" class="form-control"><br>
	                    </div>
	                    <div class="col-sm-12">
	                    	<label class="control-label pull-left">Recomendaci&oacute;n</label>
	                    	<input type="text" name="contenido1" id="contenido1" value="" class="form-control" style="height:70px;"><br>
<!-- 		                    <div class="ibox-content no-padding"><textarea class="form-control summernote" name="contenido1" rows="11"></textarea></div> -->
	                    </div>
	                    <div class="col-sm-12">
	                    	<label class="control-label pull-left">Firma</label>
	                    	<input type="text" name="contenido2" id="contenido2" value="" class="form-control">
	                    	<br>
	                     </div>
		            	<input type="hidden" name="id_tipo" value="3" />
		            	<input type="hidden" name="id" id="id" value="" />
	                    <input type="hidden" name="id_contenido" id="id_contenido" value="" >
		                <input type="submit" class="btn btn-primary" value="Ingresar">
		            </form>
		        </div>
			</div>
  		</div>
	</div>
</div>
<!-- Fin Modificar Recomendacion -->

<!-- Modal eliminar Recomendacion -->
<div class="modal inmodal amimated fadeInDown fast" id="myModalcinco" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
	    <div class="modal-content animated">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title">Eliminar ítem Recomendaci&oacute;n</h4>
	        </div>
	        <div class="modal-body">
		        <p>&iquest;Est&aacute; seguro de querer eliminar el ítem de <strong> <input type="button" name="seccion" id="seccion" value="" class="btn_eliminar_popup"/></strong>?</p>
		        <div class="modal-footer">
		            <form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('cms-v2/contenidos/eliminar_contenido_adicional/'); ?>">
		            	<input type="hidden" name="id" id="id" value="" />
		            	<input type="hidden" name="id_contenido" id="id_contenido" value="<?php echo $item['id_contenido'];?>" />
		                <input type="submit" class="btn btn-danger" value="Eliminar">
		            </form>
		        </div>
	        </div>
	    </div>
    </div>
</div>
<!-- Fin Modal eliminar Recomendacion -->

<!-- Modal eliminar Imagen Slide -->
<div class="modal inmodal" id="myModalseis" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
	    <div class="modal-content animated">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title">Eliminar Imagen de Slide</h4>
	        </div>
	        <div class="modal-body">
		        <p>&iquest;Est&aacute; seguro de querer eliminar la imagen <strong> <input type="button" name="seccion" id="seccion" value="" class="btn_eliminar_popup"/></strong>?</p>
		        <div class="modal-footer">
		            <form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('cms-v2/contenidos/eliminarmedia/'); ?>">
		            	<input type="hidden" name="id" id="id" value="" />
		            	<input type="hidden" name="id_contenido" id="id_contenido" value="<?php echo $item['id_contenido'];?>" />
		                <input type="submit" class="btn btn-danger" value="Eliminar">
		            </form>
		        </div>
	        </div>
	    </div>
    </div>
</div>
<!-- Fin Modal eliminar Imagen Slide -->
						 	
<!-- Modal Ingresar Imagen Slide -->
<div class="modal inmodal" id="myModalsiete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
   		<div class="modal-content animated">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title">Ingresar imagen</h4>
	        </div>
	
	        <div class="modal-body">
		        <p>Ingrese imagen para:<strong> <input type="text" name="seccion" id="seccion" value="" class="btn_eliminar_popup"/></strong></p>
		        <div class="modal-footer">
                	<?php echo form_open_multipart(base_url('cms-v2/contenidos/ingresarimagen/'), array('class'=>'form_eliminar')); ?>
	                    <div class="col-sm-12">
	                        <div class="fileinput fileinput-new input-group " data-provides="fileinput">
	                            <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
	                            <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Seleccionar imagen</span><span class="fileinput-exists">Cambiar</span><input type="file" name="imagen_slide"></span>
	                            <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>	
	                    	</div>
	                    </div>
	                    <div class="col-sm-12">
	                    	<label class="control-label pull-left">Estado</label>
			                <?php echo form_dropdown('estado', $estados, null, array('class'=>'form-control m-b')); ?>
	                    </div>
	                    <div class="col-sm-12">
	                    	<label class="control-label pull-left">Texto Caption</label>
		                    <div class="ibox-content no-padding"><textarea class="form-control summernote" name="contenido1" rows="11"></textarea></div>
	                    </div>
		            	<input type="hidden" name="id" id="id" value="" />
		                <input type="submit" class="btn btn-primary" value="Ingresar">
		             <?php echo form_close(); ?>
		        </div>
			</div>
  		</div>
	</div>
</div>
<!-- Fin Modal Ingresar Imagen Slide -->

<!-- DROPZONE -->
<script src="<?php echo base_url('assets/js/plugins/dropzone/dropzone.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/plugins/jasny/jasny-bootstrap.min.js'); ?>"></script>

<!-- SUMMERNOTE -->
<script src="<?php echo base_url('assets/js/plugins/summernote/summernote.min.js'); ?>"></script>

<!-- Color picker -->
<script src="<?php echo base_url('assets/js/plugins/colorpicker/bootstrap-colorpicker.min.js'); ?>"></script>


<script>
    $('.summernote').summernote({
      height: 150,   
      placeholder: 'ingresar contenido...'});
              
        Dropzone.options.dropzoneForm = {
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 2, // MB
            dictDefaultMessage: "<strong>Subir archivos desde aqu&iacute;. </strong></br>"
        };

  $('#myModal').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var seccion = $(e.relatedTarget).data().seccion;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#seccion').val(seccion);
  });

  $('#myModaldos').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var id_contenido = $(e.relatedTarget).data().id_contenido;
     var orden = $(e.relatedTarget).data().orden;
     var subtitulo = $(e.relatedTarget).data().subtitulo;
     var contenido1 = $(e.relatedTarget).data().contenido1;
     var contenido2 = $(e.relatedTarget).data().contenido2;
     var seccion = $(e.relatedTarget).data().seccion;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#id_contenido').val(id_contenido);
      $(e.currentTarget).find('#orden').val(orden);
      $(e.currentTarget).find('#subtitulo').val(subtitulo);
      $(e.currentTarget).find('#contenido1').val(contenido1);
      $(e.currentTarget).find('#contenido2').val(contenido2);
      $(e.currentTarget).find('#seccion').val(seccion);
  });
  $('#myModaltres').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var seccion = $(e.relatedTarget).data().seccion;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#seccion').val(seccion);
  });
  $('#myModalcuatro').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var seccion = $(e.relatedTarget).data().seccion;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#seccion').val(seccion);
  });
  $('#myModalcinco').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var seccion = $(e.relatedTarget).data().seccion;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#seccion').val(seccion);
  });

  $('#myModalseis').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var seccion = $(e.relatedTarget).data().seccion;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#seccion').val(seccion);
  });
  
  $('#myModalsiete').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var seccion = $(e.relatedTarget).data().seccion;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#seccion').val(seccion);
  });
  $('#myModalEditarInformacion').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var id_contenido = $(e.relatedTarget).data().id_contenido;
     var orden = $(e.relatedTarget).data().orden;
     var contenido1 = $(e.relatedTarget).data().contenido1;
     var contenido2 = $(e.relatedTarget).data().contenido2;
     var seccion = $(e.relatedTarget).data().seccion;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#seccion').val(seccion);
      $(e.currentTarget).find('#id_contenido').val(id_contenido);
      $(e.currentTarget).find('#orden').val(orden);
      $(e.currentTarget).find('#contenido2').val(contenido2);
      $(e.currentTarget).find('#contenido1').val(contenido1);
  });

  $('.demo1').colorpicker();  
</script>

</body>
</html>