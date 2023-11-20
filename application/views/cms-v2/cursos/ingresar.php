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
</style>

	<link href="<?php echo base_url('assets/css/plugins/colorpicker/bootstrap-colorpicker.min.css'); ?>" rel="stylesheet" type="text/css">


        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-xs-8 col-sm-8 col-lg-8">
                <h2>Sitio web Cursos</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="/cms">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('cms-v2/cursos/');?>">Cursos</a>
                    </li>
                    <li class="active">
                        <strong><?php echo (empty($contenido['id'])) ? 'Crear nuevo' : 'Modificar'; ?></strong>
                    </li>
                </ol>
            </div>

            <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
			<input type="hidden" name="id" value="<?php echo (!empty($contenido['id'])) ? $contenido['id'] : null; ?>">
			<input type="hidden" name="seccion" value="3">
            <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
                <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
                <button class="btn btn-primary" type="submit">Guardar cambios</button>
            </div>
        </div>
                       
        <div class="wrapper wrapper-content animated fadeInRight p_b_25">
            <!-- Titulo Mensajes -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox-title ibox-title-custom"><h5>Subir curso</h5>
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

			<div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title"><h5>Información del curso</h5>
                        <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                    </div>
                    
                    <div class="ibox-content" style="float:left;">
	                 	<div class="form-group">
		                    <label class="text-right col-sm-1 control-label">T&iacute;tulo</label>
		                    <div class="col-sm-5 col-md-4"><input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['id'])) ? $item['titulo']: null; ?>"></div>
		                    <label class="text-right col-sm-1 control-label">Nombre (url)</label>
		                    <div class="col-sm-5 col-md-4"><input type="text" name="url" class="form-control" value="<?php echo (isset($item['id'])) ? $item['url']: null; ?>"></div>
	                 	</div>
			            <br><br><br>
	                 	<div class="form-group">
		                    <label class="text-right col-sm-1 control-label">Estado</label>
		                    <div class="col-sm-1">
			                    <?php echo (isset($item['id'])) ? form_dropdown('estado', $estados, $contenido['id_estado'], array('class'=>'form-control m-b')) : form_dropdown('estado', $estados, null, array('class'=>'form-control m-b')); ?></div>
		                    <label class="text-right col-md-2 col-lg-2 control-label">Destacado</label>
		                    <div class="col-md-1">
			                    <?php echo (isset($item['id'])) ? form_dropdown('destacado', $destacados, $contenido['destacado'], array('class'=>'form-control m-b')) : form_dropdown('destacado', $destacados, null, array('class'=>'form-control m-b')); ?></div>
		                    <label class="text-right col-sm-2 col-md-2 col-lg-1 control-label">Estrellas</label>
		                    <div class="col-sm-1 col-md-1">
			                    <?php echo (isset($item['id'])) ? form_dropdown('puntaje', $estrellas, $contenido['puntaje'], array('class'=>'form-control m-b')) : form_dropdown('puntaje', $estrellas, null, array('class'=>'form-control m-b')); ?></div>
		                    <!-- Multimedia -->
                            <label class="col-sm-1 control-label">Canal Videos</label>
                            <div class="col-sm-3">
                                <?php echo form_dropdown('media_proyecto', $media_proyectos, (isset($contenido['media_proyecto'])) ? $contenido['media_proyecto'] : null, 'class="form-control m-b"'); ?>
                            </div>
	                 	</div>
                            
			                    
			            <br><br><br>

	                 	<div class="form-group" >
		                    <label class="text-right col-sm-1 control-label">Precio en AR$</label>
		                    <div class="col-sm-2"><input type="text" name="precio" class="form-control" value="<?php echo (isset($item['id'])) ? $item['precio']: null; ?>"></div>
		                    <label class="text-right col-sm-1 control-label">Precio en U$S</label>
		                    <div class="col-sm-2"><input type="text" name="precioUsd" class="form-control" value="<?php echo (isset($item['id'])) ? $item['precioUsd']: null; ?>"></div>
<!--
		                    <label class="text-right col-sm-1 control-label">Descuento</label>
		                    <div class="col-sm-2"><input type="text" name="descuento" class="form-control" value="<?php echo (isset($item['id'])) ? $item['descuento']: null; ?>"></div>
-->
			            </div>
			            
			            <br><br><br>
	                 	<div class="form-group">
		                  <!-- Imagenes Generales -->
		                    <div class="col-sm-5 col-sm-offset-1">
		                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>Imagen</h5></div>
								<div class="ibox-content b_bottom">
		                            <?php if(!empty($contenido['imagen'])) { ?>
	                            	<p>Imagen Actual</p>
	                            	<img src="<?php echo base_url('/multimedia/511/7358/'.$contenido['imagen']);?>" title="<?php echo $item['titulo'];?>" alt="<?php echo $item['titulo'];?>" style="height:100px;float: left;padding-bottom: 24px;padding-right: 25px;"/>
	                            <?php } ?>
									<br><br>
		                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
		                                <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
		                                <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Seleccionar imagen</span><input type="file" name="image"></span>
			                    	</div>
								</div>
		                    </div>
		                    
			               <!-- Mapa -->
		                    <div class="col-sm-5">
		                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>Mapa</h5></div>
								<div class="ibox-content b_bottom">
		                            <?php if(!empty($contenido['miniatura'])) { ?>
	                            	<p>Imagen Actual</p>
	                            	<img src="<?php echo base_url('/multimedia/511/7358/'.$contenido['miniatura']);?>" title="<?php echo $item['titulo'];?>" alt="<?php echo $item['titulo'];?>" style="height:100px;float: left;padding-bottom: 24px;padding-right: 25px;"/>
	                            <?php } ?>
									<br><br>
		                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
		                                <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
		                                <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Seleccionar imagen</span><input type="file" name="miniatura"></span>
			                    	</div>
								</div>
		                    </div>
	                 	</div>

			            <br><br><br>
	                 	<div class="form-group" style="float:left; margin-top:25px;">
		                  <!-- PDF -->
		                    <div class="col-sm-12 col-md-4">
		                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>PDF Recetas <small>(m&aacute;ximo 2Mb)</small></h5></div>
								<div class="ibox-content b_bottom">
		                            <?php if(!empty($item['archivo1'])) { ?>
	                            	<p>Imagen Actual</p>
	                            	<img src="<?php echo base_url('/multimedia/511/7358/'.$item['archivo1']);?>" title="<?php echo $item['titulo'];?>" alt="<?php echo $item['titulo'];?>" style="height:100px;float: left;padding-bottom: 24px;padding-right: 25px;"/>
	                            <?php } ?>
									<br><br>
		                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
		                                <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
		                                <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Seleccionar archivo</span><input type="file" name="archivo1"></span>
			                    	</div>
								</div>
		                    </div>
		                    
			               <!-- Yapa -->
		                    <div class="col-sm-12 col-md-4">
		                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>Yapa <small>(m&aacute;ximo 2Mb)</small></h5></div>
								<div class="ibox-content b_bottom">
		                            <?php if(!empty($item['archivo2'])) { ?>
	                            	<p>Archivo Actual</p>
	                            	<img src="<?php echo base_url('/multimedia/511/7358/'.$item['archivo2']);?>" title="<?php echo $item['titulo'];?>" alt="<?php echo $item['titulo'];?>" style="height:100px;float: left;padding-bottom: 24px;padding-right: 25px;"/>
	                            <?php } ?>
									<br><br>
		                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
		                                <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
		                                <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Seleccionar yapa</span><input type="file" name="archivo2"></span>
			                    	</div>
								</div>
		                    </div>

			               <!-- Ingredientes -->
		                    <div class="col-sm-12 col-md-4">
		                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>Ingredientes <small>(m&aacute;ximo 16Mb)</small></h5></div>
								<div class="ibox-content b_bottom">
		                            <?php if(!empty($item['archivo3'])) { ?>
	                            	<p>PDF Actual</p>
	                            	<img src="<?php echo base_url('/multimedia/511/7358/'.$item['archivo3']);?>" title="<?php echo $item['titulo'];?>" alt="<?php echo $item['titulo'];?>" style="height:100px;float: left;padding-bottom: 24px;padding-right: 25px;"/>
	                            <?php } ?>
									<br><br>
		                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
		                                <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
		                                <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Seleccionar pdf</span><input type="file" name="archivo3"></span>
			                    	</div>
								</div>
		                    </div>
	                 	</div>

                    </div>
                </div>
            </div>
            

         <!-- Slide -->
			<div class="col-lg-12" style="margin-top:25px;">
                <div class="ibox float-e-margins">
                    <div class="ibox-title"><h5>Breadcrumb</h5>
                        <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                    </div>

                    <div class="ibox-content pull-left full-width">
                    	<div class="col-lg-2">
                            <p>Seleccione color de fondo</p>
                            	<input type="text" class="form-control demo1 colorpicker-element" value="<?php echo (isset($contenido['color'])) ? $contenido['color']: null; ?>" name="color">
                            <br>
                        </div>
                    
                    
                    	<div class="col-lg-8 col-sm-offset-1">
		                 <?php if(!empty($contenido['imagen_adicional'])) { ?>
                        	<p>Imagen Actual</p>
                        	<img src="<?php echo base_url('/multimedia/511/7358/'.$contenido['imagen_adicional']);?>" title="<?php echo $item['titulo'];?>" alt="<?php echo $item['titulo'];?>" style="max-width:90%; max-height:170px;"/>
                        <?php } ?>
							<br><br>
                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
                                <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Seleccionar imagen</span><input type="file" name="imagen_adicional"></span>
	                    	</div>
	                   	</div>
                	</div>
				</div>
			</div>
          <!-- Fin Slide -->

          <!-- Contenido -->
			<div class="col-lg-12" style="margin-top:25px;">
                <div class="ibox float-e-margins">
                    <div class="ibox-title"><h5>Contenido</h5>
                        <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                    </div>

                    <div class="ibox-content pull-left full-width">
						<div class="col-lg-6">
		                    <div class="ibox-title" style="background:#f7f7f7;"><h5>Contenido</h5></div>
		                    	<div class="ibox-content no-padding"><textarea class="form-control summernote" name="contenido1" rows="11"><?php echo (isset($item['id'])) ? $item['contenido1']: null; ?></textarea></div>
	                   	</div>
						<div class="col-lg-6">
		                    <div class="ibox-title" style="background:#f7f7f7;"><h5>Contenido ampliado</h5></div>
		                    	<div class="ibox-content no-padding"><textarea class="form-control summernote" name="contenido2" rows="11"><?php echo (isset($item['id'])) ? $item['contenido2']: null; ?></textarea></div>
	                   	</div>
                    </div>
                    <div class="ibox-content pull-left full-width">
						<div class="col-lg-6">
		                    <div class="ibox-title" style="background:#f7f7f7;"><h5>Contenido adicional (sólo se muestra una vez adquirido el curso)</h5></div>
		                    	<div class="ibox-content no-padding"><textarea class="form-control summernote" name="contenido3" rows="11"><?php echo (isset($item['id'])) ? $item['contenido3']: null; ?></textarea></div>
	                   	</div>
                	</div>
				</div>
			</div>
          <!-- Fin Contenido -->

			<div class="col-lg-12" style="margin-top:25px;margin-bottom:25px;">
                <div class="ibox float-e-margins collapsed">
                    <div class="ibox-title"><h5>SEO</h5>
                        <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                    </div>

                    <div class="ibox-content pull-left full-width">
						<div class="col-lg-12">
		                 	<div class="form-group">
			                    <div class="col-md-4 sm-12 m_b_25">
			                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>Título</h5></div>
				                    <div class="ibox-content no-padding"><textarea class="form-control" name="seo_titulo" rows="5"><?php echo (isset($item['seo_titulo'])) ? $item['seo_titulo']: null?></textarea></div>
			                    </div>
			                    <div class="col-md-4 sm-12 m_b_25">
			                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>Descripci&oacute;n</h5></div>
				                    <div class="ibox-content no-padding"><textarea class="form-control" name="seo_descripcion" rows="5"><?php echo (isset($item['seo_descripcion'])) ? $item['seo_descripcion']: null?></textarea></div>
			                    </div>
			                    <div class="col-md-4 sm-12 m_b_25">
			                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>Keywords</h5></div>
				                    <div class="ibox-content no-padding"><textarea class="form-control" name="seo_keywords" rows="5"><?php echo (isset($item['seo_keywords'])) ? $item['seo_keywords']: null?></textarea></div>
			                    </div>
		                 	</div>
						</div>
                    </div>

                </div>
			</div>
		<?=form_close()?>
		<!-- Fin Contenido -->

          <!-- Informacion -->
          <div class="col-lg-12 m_t_25">
            <div class="ibox float-e-margins">
                <div class="ibox-title"><h5>Información</h5>
                    <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                 </div>
                <div class="ibox-content">
	                <?php if ( (isset($_GET['error'])) && ($_GET['error'] == 'informacion')) : ?>
					<div class="col-md-12">
						<div class="alert alert-danger" role="alert"><?php echo 'Debe completar el campo informacion'; ?></div>
					</div>
					<?php endif; ?>
                 	<div class="form-group">
		                <?php 
		               if(!empty($informaciones)) {
						foreach($informaciones as $informacion) { ?>	
						<form name="editar" class="form_editar m_b_10" method="post" action="<?php echo base_url('cms-v2/cursos/modificar_informacion/'); ?>" style="display: inline-block; width:100%; padding:5px 0;">
		                    <div class="col-sm-2 control-label">
		                    	<label class="control-label">Icono</label><?php echo (isset($informacion['id'])) ? form_dropdown('subtitulo', $iconos, $informacion['subtitulo'], array('class'=>'form-control m-b')) : form_dropdown('subtitulo', $iconos, null, array('class'=>'form-control m-b')); ?>
		                    </div>
		                    <div class="col-sm-1 control-label">
		                    	<label class="control-label">Orden</label><input type="text" name="orden" class="form-control" value="<?php echo (isset($informacion['orden'])) ? $informacion['orden']: null?>">
		                    </div>
		                    <div class="col-sm-7">
		                    	<label class="control-label">Informacion</label><input type="text" name="titulo" class="form-control" value="<?php echo (isset($informacion['titulo'])) ? $informacion['titulo']: null?>">
		                    </div>
		                    <div class="col-sm-2 m_t_20">
								<input type="hidden" name="id" id="id" value="<?php echo $informacion['id'];?>" />
								<input type="hidden" name="id_contenido" id="id_contenido" value="<?php echo $contenido['id'];?>" />
								<button type="submit" class="btn btn-info btn-circle"><i class="fa fa-pencil"></i></button>
								<a title="Eliminar" id="titulo" href="#" data-toggle="modal" data-seccion='<?php echo strip_tags($informacion['titulo']);?>' data-id="<?php echo $informacion['id'];?>" data-target="#myModalcinco" class="btn btn-danger btn-circle"> <i class="fa fa-times"></i></a></div>
						</form>
						<div class="hr-line-dashed m_t_b_5"></div>
						<?php }  } else { echo 'No se encontraron resultados';} ?>
                 	</div>
                 	<div class="form-group">
						<?php if(!empty($item['id_contenido'])) { ?> <a title="Ingresar" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $item['titulo'];?>" data-id="<?php echo $contenido['id'];?>" data-target="#myModalcuatro" class="sepV_a btn btn-primary btn-sm red pull-left"><i class="fa fa-plus-circle"></i> Ingresar nueva	</a><?php } ?><br><br>
					</div>
		   		</div>
		   	</div>
       </div>

          <!-- Tener en cuenta -->
          <div class="col-lg-12 m_t_25">
            <div class="ibox float-e-margins">
                <div class="ibox-title"><h5>Tener en cuenta</h5>
                    <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                 </div>
                <div class="ibox-content">
	                <?php if ( (isset($_GET['error'])) && ($_GET['error'] == 'cuenta')) : ?>
					<div class="col-md-12">
						<div class="alert alert-danger" role="alert"><?php echo 'Debe completar el campo item'; ?></div>
					</div>
					<?php endif; ?>
                 	<div class="form-group">
		                <?php 
		               if(!empty($cuentas)) {
						foreach($cuentas as $cuenta) { ?>	
						<form name="editar" class="form_editar m_b_10" method="post" action="<?php echo base_url('cms-v2/cursos/modificar_cuenta/'); ?>" style="display: inline-block; width:100%; padding:5px 0;">
		                    <div class="col-sm-1 control-label">
		                    	<label class="control-label">Orden</label><input type="text" name="orden" class="form-control" value="<?php echo (isset($cuenta['orden'])) ? $cuenta['orden']: null?>">
		                    </div>
		                    <div class="col-sm-8">
		                    	<label class="control-label">Item</label><input type="text" name="titulo" class="form-control" value="<?php echo (isset($cuenta['titulo'])) ? $cuenta['titulo']: null?>">
		                    </div>
		                    <div class="col-sm-3 m_t_20">
								<input type="hidden" name="id" id="id" value="<?php echo $cuenta['id'];?>" />
								<input type="hidden" name="id_contenido" id="id_contenido" value="<?php echo $contenido['id'];?>" />
								<button type="submit" class="btn btn-info btn-circle"><i class="fa fa-pencil"></i></button>
								<a title="Eliminar" id="titulo" href="#" data-toggle="modal" data-seccion='<?php echo strip_tags($cuenta['titulo']);?>' data-id="<?php echo $cuenta['id'];?>" data-target="#myModaltres" class="btn btn-danger btn-circle"> <i class="fa fa-times"></i></a></div>
						</form>
						<div class="hr-line-dashed m_t_b_5"></div>
						<?php }  } else { echo 'No se encontraron resultados';} ?>
                 	</div>
                 	<div class="form-group">
						<?php if(!empty($item['id_contenido'])) { ?> <a title="Ingresar" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $item['titulo'];?>" data-id="<?php echo $contenido['id'];?>" data-target="#myModaldos" class="sepV_a btn btn-primary btn-sm red pull-left"><i class="fa fa-plus-circle"></i> Ingresar nueva	</a><?php } ?><br><br>
					</div>
		   		</div>
		   	</div>
       </div>
       
          <!-- Imagenes -->
			<div class="col-lg-12" style="margin-top:25px;margin-bottom:25px;">
                <div class="ibox float-e-margins">
                    <div class="ibox-title"><h5>Imágenes de la galería</h5>
                        <div class="ibox-tools">  <a class="collapse-link pull-right"><i class="fa fa-chevron-up"></i></a>
				        <?php if(!empty($fotos)) { ?>
                        <a class="btn btn-primary pull-right btn-sm" type="submit" href="<?php echo base_url('cms-v2/cursos/ordenar/'.$item['id']);?>" style="margin-top:-8px; margin-right:10px;">Ordenar</a>
				        <?php } ?>	
                        </div>
                    </div>

                    <div class="ibox-content" style="float: left; width:100%;">
						<div class="col-lg-12">
							<h2>Imágenes subidas</h2>
			                <?php 
				               if(!empty($fotos)) {
								foreach($fotos as $lista) { ?>	
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
						  
						<?php if(!empty($item['id'])) { ?>
						<div class="col-lg-12">
						 	<br><br><div class="hr-line-dashed"></div>
							<h2>Subir Imágenes</h2>
							
							<div class="table-responsive" style="background: none; border:0;">
								<?php echo form_open('cms-v2/cursos/upload', array('class' =>'dropzone'));?>
								<input type="hidden" name="id_contenido" value="<?php echo $contenido['id']; ?>">
								<?php echo form_close();?>
							</div>
						</div>
						<?php } ?>
                	</div>
				</div>
          </div>
          <!-- Fin Imagenes -->



		<br><br></div></div>
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
				<div class="slimScrollBar" style="background-color: rgb(0, 0, 0); width: 7px; position: absolute; top: 2px; opacity: 0.4; display: none; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; z-index: 99; right: 1px; height: 675.0260416666666px; background-position: initial initial; background-repeat: initial initial;"></div>
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
		        	<form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('cms-v2/cursos/eliminarmedia/'); ?>">
		            	<input type="hidden" name="id" id="id" value="" />
		            	<input type="hidden" name="id_contenido" value="<?php echo $item['id']; ?>">
		                <input type="submit" class="btn btn-primary" value="Eliminar">
		            </form>
		        </div>
	        </div>
	    </div>
    </div>
</div>
<!-- Fin Modal Eliminar la imagen-->

<!-- Modal Ingresar tener en cuenta -->
<div class="modal inmodal" id="myModaldos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
   		<div class="modal-content animated">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title">Ingresar ítem tener en cuenta</h4>
	        </div>
	
	        <div class="modal-body">
		        <p>Ingrese item para:<strong> <input type="button" name="seccion" id="seccion" value="" class="btn_eliminar_popup"/></strong></p>
		        <div class="modal-footer">
		            <form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('cms-v2/cursos/ingresar_cuenta/'); ?>">
	                   <div class="col-sm-2">
	                    	<label class="control-label pull-left">Orden</label>
	                    	<input type="text" name="orden" class="form-control">
	                   </div>
	                   <div class="col-sm-10">
	                    	<label class="control-label pull-left">Item</label>
	                    	<input type="text" name="titulo" class="form-control">
	                   </div><br><br>
		            	<input type="hidden" name="id_tipo" value="2" />
		            	<input type="hidden" name="id" id="id" value="" />
		                <input type="submit" class="btn btn-primary" value="Ingresar">
		            </form>
		        </div>
			</div>
  		</div>
	</div>
</div>
<!-- Fin Modal -->

<!-- Modal eliminar tener en cuenta-->
<div class="modal inmodal" id="myModaltres" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
	    <div class="modal-content animated">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title">Eliminar ítem tener en cuenta</h4>
	        </div>
	        <div class="modal-body">
		        <p>&iquest;Est&aacute; seguro de querer eliminar el ítem de <strong> <input type="button" name="seccion" id="seccion" value="" class="btn_eliminar_popup"/></strong>?</p>
		        <div class="modal-footer">
		            <form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('cms-v2/cursos/eliminar_contenido_adicional/'); ?>">
		            	<input type="hidden" name="id" id="id" value="" />
		            	<input type="hidden" name="id_contenido" id="id_contenido" value="<?php echo $contenido['id'];?>" />
		                <input type="submit" class="btn btn-danger" value="Eliminar">
		            </form>
		        </div>
	        </div>
	    </div>
    </div>
</div>
<!-- Fin Modal eliminar tener en cuenta -->
						 	
<!-- Modal Ingresar Informacion -->
<div class="modal inmodal amimated fadeInDown fast" id="myModalcuatro" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
   		<div class="modal-content animated">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title">Ingresar ítem Informacion</h4>
	        </div>
	
	        <div class="modal-body">
		        <p>Ingrese item para:<strong> <input type="button" name="seccion" id="seccion" value="" class="btn_eliminar_popup"/></strong></p>
		        <div class="modal-footer">
		            <form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('cms-v2/cursos/ingresar_informacion/'); ?>">
	                   <div class="col-sm-3">
	                    <label class="control-label pull-left">Icono</label><?php echo (isset($informacion['id'])) ? form_dropdown('subtitulo', $iconos, $informacion['subtitulo'], array('class'=>'form-control m-b')) : form_dropdown('subtitulo', $iconos, null, array('class'=>'form-control m-b')); ?></div>
	                   <div class="col-sm-2">
	                    	<label class="control-label pull-left">Orden</label>
	                    	<input type="text" name="orden" class="form-control">
	                   </div>
	                    <div class="col-sm-7">
	                    	<label class="control-label pull-left">Informacion</label>
	                    	<input type="text" name="titulo" class="form-control">
	                    </div>
	                    <br><br><br><br>
		            	<input type="hidden" name="id_tipo" value="1" />
		            	<input type="hidden" name="id" id="id" value="" />
		                <input type="submit" class="btn btn-primary" value="Ingresar">
		            </form>
		        </div>
			</div>
  		</div>
	</div>
</div>
<!-- Fin Modal Informacion -->

<!-- Modal eliminar Informacion-->
<div class="modal inmodal amimated fadeInDown fast" id="myModalcinco" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
	    <div class="modal-content animated">
	        <div class="modal-header">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title">Eliminar ítem Informacion</h4>
	        </div>
	        <div class="modal-body">
		        <p>&iquest;Est&aacute; seguro de querer eliminar el ítem de <strong> <input type="button" name="seccion" id="seccion" value="" class="btn_eliminar_popup"/></strong>?</p>
		        <div class="modal-footer">
		            <form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('cms-v2/cursos/eliminar_contenido_adicional/'); ?>">
		            	<input type="hidden" name="id" id="id" value="" />
		            	<input type="hidden" name="id_contenido" id="id_contenido" value="<?php echo $contenido['id'];?>" />
		                <input type="submit" class="btn btn-danger" value="Eliminar">
		            </form>
		        </div>
	        </div>
	    </div>
    </div>
</div>
<!-- Fin Modaleliminar Informacion -->

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
            maxFilesize: 16, // MB
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
     var seccion = $(e.relatedTarget).data().seccion;
      $(e.currentTarget).find('#id').val(id);
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
  
  $('.demo1').colorpicker();
	
</script>

</body>
</html>