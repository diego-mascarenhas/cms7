<style>
.note-editor.note-frame { border:0;}
.note-editable .row {margin: 0px;}
.note-editable .row div {border: 1px dotted;}
.tooltip-inner {max-width: 250px;width: 250px;}
.control-label, .input-group { margin-top:10px;}
.hr-line-dashed {margin: 10px 0 20px;}
.box-items { display: flex; }
.contact-box  { display: flex; flex-direction:column; justify-content:space-between; width:100%;}
.modal-title { text-align: center;margin: 20px 0 30px;border-bottom: 1px solid #e5e6e7;padding-bottom: 5px;}
	@media(max-width:768px){
.control-label { margin-top:18px;}	
.input-group { margin-top:0;}	
}

</style>

         <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-8 col-sm-8 col-xs-8">
                <h2>Proyectos</h2>
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url('cms-v2/paginas');?>">Home</a></li>
                    <li><a href="<?php echo base_url('cms-v2/servicios');?>">Proyectos</a></li>
                    <li class="active"><strong><?php echo (empty($detalle['id'])) ? 'Crear nuevo' : 'Modificar'; ?></strong></li>
                </ol>
            </div>

            <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
			<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
			<input type="hidden" name="filtro1" value="<?php echo (!empty($detalle['filtro1'])) ? $detalle['filtro1'] : null; ?>">
			<input type="hidden" name="id_tipo" value="19">
			<input type="hidden" name="medidas1" value="280x280">
			<input type="hidden" name="id_categoria" value="27">
			
            <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
                <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
                <button class="btn btn-primary" type="submit">Guardar cambios</button>
            </div>
        </div>
        
        <div class="wrapper wrapper-content animated fadeInRight">
			<div class="row">
                <?php if (validation_errors()) : ?>
				<div class="col-md-12 m-t-sm">
					<div class="alert alert-danger" role="alert"><?php echo validation_errors(); ?></div>
				</div>
				<?php endif; ?>
				<?php if (isset($error)) : ?>
				<div class="col-md-12 m-t-sm">
					<div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
				</div>
				<?php endif; ?>
			</div>
        </div>
        
		<?php if ($this->session->flashdata('mensaje')) { ?>
		<div class="col-md-12">
			<?php if ($this->session->flashdata('mensaje') == 'error') { ?>
			<div class="alert alert-danger alert-dismissable" role="alert">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <?php echo $error; ?></div>
			<?php } else { ?>
			<div class="alert alert-success alert-dismissable" role="alert">
				<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
				<?php echo $this->session->flashdata('mensaje');?></div>
			<?php } ?>
		</div>
		<?php } ?>

       	<!-- Comienzo Tabs -->
        <div class="wrapper wrapper-content" style="padding-top:0 !important;">
            <div class="row">
                <div class="col-lg-12">
                    <div class="tabs-container">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#tab-0"> Datos Generales</a></li>
                        	<?php foreach($idiomas as $idioma) { ?>
                            <li class=""><a data-toggle="tab" href="#tab-<?php echo $idioma['orden'];?>"> <?php echo $idioma['idioma'];?></a></li>
                        	<?php } ?>
                        </ul>

                        <div class="tab-content">
	                        <!-- Item Generales -->
	                        <div id="tab-0" class="tab-pane active">
	                            <div class="panel-body">
								 <div class="row">
					                <div class="col-lg-12 p-xxs">
										<div class="form-group pull-left full-width">
						                    <label class="col-xs-12 col-sm-1 col-md-1 control-label">Título</label>
				                            <div class="col-xs-12 col-sm-5 col-md-4">
		                                        <div class="input-group">
		                                        	<input type="text" name="titulo" class="form-control" value="<?php echo (isset($detalle['titulo'])) ? $detalle['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título general, no se mostrará en el sitio." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
											</div>
						                    <label class="col-xs-12 col-sm-1 col-md-1 control-label">Estado</label>
				                            <div class="col-xs-12 col-sm-5 col-md-3">
		                                        <div class="input-group">
						                            <select name="estado" class="required form-control m-b">
							                            <option value="3"<?php if (isset($detalle['estado']) && $detalle['estado'] == '3') echo ' selected'; ?>>Activo</option>
							                            <option value="1"<?php if (isset($detalle['estado']) && $detalle['estado'] == '1') echo ' selected'; ?>>Inactivo</option>
						                            </select>
						                            <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Determina si el contenido se mostrará en el sitio." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
					                         </div>
										</div>
										<div class="form-group pull-left full-width">
						                    <label class="col-xs-12 col-sm-1 col-md-1 control-label">Destacado</label>
                                         	<div class="col-sm-4">
		                                        <div class="input-group">
						                            <select name="destacado_<?php echo $idioma['extension'];?>" class="required form-control m-b">
							                            <option value="3"<?php if (isset($detalle['destacado']) && $detalle['destacado'] == '3') echo ' selected'; ?>>Sí</option>
							                            <option value="0"<?php if (isset($detalle['destacado']) && $detalle['destacado'] == '0') echo ' selected'; ?>No</option>
						                            </select>
						                            <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Determina si el contenido se mostrará en el carousel con proyectos del sitio." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
					                         </div>
						                    <label class="col-xs-12 col-sm-1 col-md-1 control-label">Orden</label>
				                            <div class="col-xs-12 col-sm-5 col-md-3">
		                                        <div class="input-group">
			                                        <input type="text" name="orden" class="form-control" value="<?php echo (isset($detalle['orden'])) ? $detalle['orden']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en el que se mostrará. Se puede dejar vacío y luego acomodar el orden accediendo a Ordenar desde el listado de información." title=""> <i class="fa fa-question"></i></button>
			                                    </div>
		                                    </div>
			                            </div>

									<?php
										$CI =& get_instance();
										$CI->load->model("contacto_model");
										$parametros2['id_perfil'] = 5;
										$contactos = $CI->contacto_model->getContactos($parametros2);
									?>

										<div class="form-group pull-left full-width">
		                            		<label class="col-sm-1 control-label">Usuario</label>
                                         	<div class="col-sm-4">
                                         		<div class="input-group">
	                                         		<select name="filtro1" class="form-control m-b">
	                                                <option value="0"> -- Seleccione Contacto --</option>
													<?php if (isset($contactos)) {  
														foreach($contactos as $contacto)
														{ 
															if($contacto['id'] == $detalle['filtro1']) { $selected = 'selected'; } else { $selected = null; }
															echo '<option value="'.$contacto['id'].'"'.$selected.'>'.$contacto['contacto'].'</option></div>';
														  }
														}  ?></select><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Usuario previamente ingresado. Para ingresar un nuevo usuario debe ir a Panel de Control > Usuarios > Ingresar contacto y seguir los pasos indicados en el instructivo enviado. Recuerde que el el Nombre debe ser la calle, el Apellido el número, Area Privada debe ser Invitado y el Usuario la calle y número del proyecto sin espacios. En email puede ingresar el institucional." title=""> <i class="fa fa-question"></i></button></span></div>
                                         	</div>
			                            </div>

								   </div>
	                            </div>
	                        </div>
	                       </div>
	
	                        <!-- Items Idiomas -->
                        	<?php foreach($idiomas as $idioma) { ?>
	                        <div id="tab-<?php echo $idioma['orden'];?>" class="tab-pane">

                        	<?php 
								if(!empty($detalle['id']))
								{
									$CI->load->model("Servicios_model");
									$parametros['id'] = $detalle['id'];
									$parametros['idioma'] = $idioma['extension'];
									$item = $this->Servicios_model->getServicioDetalleIdioma($parametros);
									$parametros['id_tipo'] = 32;
									$imagen = $this->Servicios_model->getMedia($parametros);
									$par_archivo['id'] = $detalle['id'];
									$par_archivo['idioma'] = $idioma['extension'];
									$archivo = $this->Servicios_model->getArchivo($par_archivo);
									$galeria_proyectos = $this->Servicios_model->comboProyectos();
								}
							?>
	                            <div class="panel-body">
								 <div class="row">

					                <div class="col-lg-12 p-xxs">
										<div class="form-group pull-left full-width">
						                    <label class="text-right col-sm-1 control-label">Título</label>
											<div class="col-sm-5">
		                                        <div class="input-group">
		                                        	<input type="text" name="titulo_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del proyecto que se mostrará en el sitio, debe tener una '/' entre calle y número, sin espacios." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
											</div>

						                    <label class="text-right col-sm-2 control-label">Nombre (url)</label>
						                    <div class="col-sm-4">
		                                        <div class="input-group">
		                                        	<input type="text" name="url_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['uri'])) ? $item['uri']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Url del del proyecto, si se deja vacía toma el título sanitizado como url." title=""> <i class="fa fa-question"></i></button></span>
		                                        </div>
		                                     </div>
										</div>
										<div class="form-group pull-left full-width">
		                            		<label class="col-sm-1 text-right control-label">Miniatura</label>
							                <?php if(isset($imagen['archivo'])) { ?>
							                <div class="col-sm-1">
								                <img src="<?php echo base_url('/multimedia/thumbs/'.$imagen['archivo']);?>" alt="<?php echo $item['titulo'];?>" width="70">
							                </div>
							                <?php } ?>
							                <div class="<?php echo (isset($imagen['archivo'])) ? "col-sm-4" : "col-sm-5"; ?>">
		                                        <div class="input-group">
			                                       <input type="file" name="imagen1_<?php echo $idioma['extension'];?>" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Imagen obligatoria para que se vea el proyecto en el sitio en el carousel de proyectos. Debe ser gif, jpg o png y tener 280x280 píxeles o proporcionales mayores." title=""> <i class="fa fa-question"></i></button>
			                                    </div>
							                </div>

		                                    <label class="col-sm-2 control-label text-right">Galería Intro</label>
                                         	<div class="col-sm-4">
                                         		<div class="input-group">
												<select name="<?php echo 'id_proyecto_'.$idioma['extension'];?>" class="form-control m-b">
	                                                <option value="0"> -- Seleccione Galería --</option>
													<?php if (isset($galeria_proyectos)) {  
														foreach($galeria_proyectos as $proyecto)
														{ 
															if($proyecto['id'] == $item['id_proyecto']) { $selected = 'selected'; } else { $selected = null; }
															echo '<option value="'.$proyecto['id'].'"'.$selected.'>'.$proyecto['descripcion'].'</option></div>';
															$galerias = $this->Servicios_model->comboProyectos($proyecto['id']);
															if($galerias)
															{
																foreach($galerias as $galeria)
																{ 
																	if($galeria['id'] == $item['id_proyecto']) { $selected = 'selected'; } else { $selected = null; }
																	echo '<option value="'.$galeria['id'].'"'.$selected.'>&nbsp; - '.$galeria['descripcion'].'</option></div>';
																}
															}
														  }
														}  ?></select><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Galería de Intro al proyecto, se administra desde Multimedia." title=""> <i class="fa fa-question"></i></button></span></div>
                                         	</div>
										</div>

										<div class="form-group pull-left full-width">
		                            		<label class="col-sm-1 text-right control-label">Archivo</label>
							                <?php if(isset($archivo['archivo'])) { ?>
							                <div class="col-sm-1">
								                <img src="<?php echo base_url('/multimedia/502/10578/'.$archivo['archivo']);?>" alt="<?php echo $item['titulo'];?>" width="70">
							                </div>
							                <?php } ?>
							                <div class="<?php echo (isset($imagen['archivo'])) ? "col-sm-4" : "col-sm-5"; ?>">
		                                        <div class="input-group">
			                                       <input type="file" name="archivo_<?php echo $idioma['extension'];?>" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Archivo para descarga del cliente que se loguee." title=""> <i class="fa fa-question"></i></button></span>
			                                    </div>
							                </div>
										</div>
		                                    <label class="col-sm-2 control-label text-right">Galería Imágenes</label>
                                         	<div class="col-sm-4">
                                         		<div class="input-group">
												<select name="<?php echo 'descuento_'.$idioma['extension'];?>" class="form-control m-b">
	                                                <option value="0"> -- Seleccione Galería --</option>
													<?php if (isset($galeria_proyectos)) {  
														foreach($galeria_proyectos as $proyecto)
														{ 
															if($proyecto['id'] == $item['descuento']) { $selected = 'selected'; } else { $selected = null; }
															echo '<option value="'.$proyecto['id'].'"'.$selected.'>'.$proyecto['descripcion'].'</option></div>';
															$galerias = $this->Servicios_model->comboProyectos($proyecto['id']);
															if($galerias)
															{
																foreach($galerias as $galeria)
																{ 
																	if($galeria['id'] == $item['descuento']) { $selected = 'selected'; } else { $selected = null; }
																	echo '<option value="'.$galeria['id'].'"'.$selected.'>&nbsp; - '.$galeria['descripcion'].'</option></div>';
																}
															}
														  }
														}  ?></select><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Galería de imágenes del proyecto, se administra desde Multimedia. Esta galería se muestra luego del listado de plantas." title=""> <i class="fa fa-question"></i></button></span></div>
                                         	</div>
					                </div>

					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Proyecto</h2>
										<div class="form-group pull-left full-width">
						                    <label class="text-right col-sm-1 control-label">Título</label>
						                    <div class="col-sm-5">
		                                        <div class="input-group">
		                                        	<input type="text" name="texto_adicional_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['texto_adicional'])) ? $item['texto_adicional']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de Proyecto." title=""> <i class="fa fa-question"></i></button></span></div>
		                                    </div>
                                         	<label class="col-sm-1 control-label text-right">Galería</label>
                                         	<div class="col-sm-5">
                                         		<div class="input-group">
												<select name="<?php echo 'subtitulo_'.$idioma['extension'];?>" class="form-control m-b">
	                                                <option value="0"> -- Seleccione Galería --</option>
													<?php if (isset($galeria_proyectos)) {  
														foreach($galeria_proyectos as $proyecto)
														{ 
															if($proyecto['id'] == $item['subtitulo']) { $selected = 'selected'; } else { $selected = null; }
															echo '<option value="'.$proyecto['id'].'"'.$selected.'>'.$proyecto['descripcion'].'</option></div>';
															$galerias = $this->Servicios_model->comboProyectos($proyecto['id']);
															if($galerias)
															{
																foreach($galerias as $galeria)
																{ 
																	if($galeria['id'] == $item['subtitulo']) { $selected = 'selected'; } else { $selected = null; }
																	echo '<option value="'.$galeria['id'].'"'.$selected.'>&nbsp; - '.$galeria['descripcion'].'</option></div>';
																}
															}
														  }
														}  ?></select><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Galería de Proyecto, se administra desde Multimedia." title=""> <i class="fa fa-question"></i></button></span></div>
                                         	</div>
				                 		</div>
					                </div>
					                <hr class="hr-line-dashed pull-left full-width">
					                <div class="col-lg-12 p-xxs">
										<div class="form-group pull-left full-width">
											<div class="col-lg-12 p-xxs">
												<div class="ibox-title bg-muted"><h5>Texto</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="texto del box proyecto, teniendo en cuenta que para el título (por ej: EDIFICIO RESIDENCIAL,…) debe seleccionarlo y aplicar Header 5 y para el resto del texto aplicar etiqueta p (párrafo)." title=""> <i class="fa fa-question"></i></button></div>
												<textarea class="form-control summernote2" name="contenido1_<?php echo $idioma['extension'];?>" rows="10"><?php echo(isset($item['contenido1'])) ? $item['contenido1']: null?></textarea>
											</div>
										</div>
					                </div>

					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Layout</h2>
										<div class="form-group pull-left full-width">
                                         	<label class="col-sm-1 control-label text-right">Título</label>
                                         	<div class="col-sm-5">
                                         		<div class="input-group">
		                                        	<input type="text" name="contenido2_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['contenido2'])) ? $item['contenido2']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de Layout." title=""> <i class="fa fa-question"></i></button></span></div>
                                         	</div>
                                         	<label class="col-sm-1 control-label text-right">Galería</label>
                                         	<div class="col-sm-5">
                                         		<div class="input-group">
												<select name="<?php echo 'contenido3_'.$idioma['extension'];?>" class="form-control m-b">
	                                                <option value="0"> -- Seleccione Galería --</option>
													<?php if (isset($galeria_proyectos)) {  
														foreach($galeria_proyectos as $proyecto)
														{ 
															if($proyecto['id'] == $item['contenido3']) { $selected = 'selected'; } else { $selected = null; }
															echo '<option value="'.$proyecto['id'].'"'.$selected.'>'.$proyecto['descripcion'].'</option></div>';
															$galerias = $this->Servicios_model->comboProyectos($proyecto['id']);
															if($galerias)
															{
																foreach($galerias as $galeria)
																{ 
																	if($galeria['id'] == $item['contenido3']) { $selected = 'selected'; } else { $selected = null; }
																	echo '<option value="'.$galeria['id'].'"'.$selected.'>&nbsp; - '.$galeria['descripcion'].'</option></div>';
																}
															}
														  }
														}  ?></select><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Galería de Layout, se administra desde Multimedia." title=""> <i class="fa fa-question"></i></button></span></div>
                                         	</div>
				                 		</div>
					                </div>
					                <hr class="hr-line-dashed pull-left full-width">

					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Equipamiento</h2>
										<div class="form-group pull-left full-width">
                                         	<label class="col-sm-1 control-label text-right">Título</label>
                                         	<div class="col-sm-5">
                                         		<div class="input-group">
		                                        	<input type="text" name="contenido4_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['contenido4'])) ? $item['contenido4']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de Equipamiento." title=""> <i class="fa fa-question"></i></button></span></div>
                                         	</div>
                                         	<label class="col-sm-1 control-label text-right">Galería</label>
                                         	<div class="col-sm-5">
                                         		<div class="input-group">
												<select name="<?php echo 'puntaje_'.$idioma['extension'];?>" class="form-control m-b">
	                                                <option value="0"> -- Seleccione Galería --</option>
													<?php if (isset($galeria_proyectos)) {  
														foreach($galeria_proyectos as $proyecto)
														{ 
															if($proyecto['id'] == $item['puntaje']) { $selected = 'selected'; } else { $selected = null; }
															echo '<option value="'.$proyecto['id'].'"'.$selected.'>'.$proyecto['descripcion'].'</option></div>';
															$galerias = $this->Servicios_model->comboProyectos($proyecto['id']);
															if($galerias)
															{
																foreach($galerias as $galeria)
																{ 
																	if($galeria['id'] == $item['puntaje']) { $selected = 'selected'; } else { $selected = null; }
																	echo '<option value="'.$galeria['id'].'"'.$selected.'>&nbsp; - '.$galeria['descripcion'].'</option></div>';
																}
															}
														  }
														}  ?></select><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Galería de Equipamiento, se administra desde Multimedia." title=""> <i class="fa fa-question"></i></button></span></div>
                                         	</div>
				                 		</div>
					                </div>
					                <div class="col-lg-12 p-xxs">
										<div class="form-group pull-left full-width">
											<div class="col-lg-6 p-xxs">
												<div class="ibox-title bg-muted"><h5>Texto</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Texto con listados separados en dos boxes de carga. Para el título (por ej: CARACTERÍSTICAS PRINCIPALES DE LOS DEPARTAMENTOS…) debe seleccionarlo y aplicar Header 5 y para el resto del texto aplicar lista." title=""> <i class="fa fa-question"></i></button></div>
												<textarea class="form-control summernote2" name="contenido5_<?php echo $idioma['extension'];?>" rows="10"><?php echo(isset($item['contenido5'])) ? $item['contenido5']: null?></textarea>
											</div>
											<div class="col-lg-6 p-xxs">
												<div class="ibox-title bg-muted"><h5>Texto</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="texto con listados separados en dos boxes de carga. Para el título (por ej: CARACTERÍSTICAS PRINCIPALES DE LOS DEPARTAMENTOS…) debe seleccionarlo y aplicar Header 5 y para el resto del texto aplicar lista." title=""> <i class="fa fa-question"></i></button></div>
												<textarea class="form-control summernote2" name="contenido6_<?php echo $idioma['extension'];?>" rows="10"><?php echo(isset($item['contenido6'])) ? $item['contenido6']: null?></textarea>
											</div>
										</div>
					                </div>

					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Plantas <a title="Ordenar" href="<?php echo base_url('cms-v2/servicios/ordenar_items/'.$detalle['id'].'/157/'.$idioma['extension']);?>" class="sepV_a btn btn-primary btn-sm red pull-right m-l-sm"><i class="fa fa-sort-circle"></i> Ordenar</a> <a title="Ingresar" id="item" href="#" data-toggle="modal" data-id="157" data-id_contenido="<?php echo $detalle['id'];?>" data-idioma="<?php echo $idioma['extension'];?>" data-target="#myModalIngresarInformacion" class="sepV_a btn btn-primary btn-sm red pull-right"><i class="fa fa-plus-circle"></i> Ingresar</a></h2>
									</div>
				                    <div class="col-sm-12 p-xxs">
										<div class="form-group pull-left full-width">
                                         	<label class="col-sm-1 control-label text-right">Galería </label>
                                         	<div class="col-sm-5">
                                         		<div class="input-group">
												<select name="<?php echo 'contenido8_'.$idioma['extension'];?>" class="form-control m-b">
	                                                <option value="0"> -- Seleccione Galería --</option>
													<?php if (isset($galeria_proyectos)) {  
														foreach($galeria_proyectos as $proyecto)
														{ 
															if($proyecto['id'] == $item['contenido8']) { $selected = 'selected'; } else { $selected = null; }
															echo '<option value="'.$proyecto['id'].'"'.$selected.'>'.$proyecto['descripcion'].'</option></div>';
															$galerias = $this->Servicios_model->comboProyectos($proyecto['id']);
															if($galerias)
															{
																foreach($galerias as $galeria)
																{ 
																	if($galeria['id'] == $item['contenido8']) { $selected = 'selected'; } else { $selected = null; }
																	echo '<option value="'.$galeria['id'].'"'.$selected.'>&nbsp; - '.$galeria['descripcion'].'</option></div>';
																}
															}
														  }
														}  ?></select><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Galería de Plantas del Proyecto, de la cual se despliegan las galerías asociadas a cada planta en particular, se administra desde Multimedia. Si no se completa este campo no se verán las demás galerías de las plantas." title=""> <i class="fa fa-question"></i></button></span></div>
                                         	</div>
				                 		</div>
				                    </div>
				                    
				                    <div class="col-sm-12 p-xxs">
					                    <div style="display: flex;flex-direction: row;height: auto;">
						                <?php 
											if(!empty($detalle['id']))
											{
												$parametros1['id'] = $detalle['id'];
												$parametros1['id_tipo'] = 157; 
												$parametros1['idioma'] = $idioma['extension'];
												$miembros= $CI->Servicios_model->getServicioAdicionalIdioma($parametros1);

								               if(!empty($miembros)) {
												foreach($miembros as $miembro) { ?>	
								                <div class="col-md-6 col-lg-3 box-items">
													<div class="contact-box<?php echo ($miembro['estado'] == 1) ? ' bg-inactiva' : '';?>">
									                    <div class="col-sm-12">
									                        <h3><strong><?php echo ellipsize($miembro['titulo'],25, 1);?></strong></h3>
									                        <address>
									                            <div><?php echo character_limiter($miembro['contenido1'], 58, '...');?></div>
									                        </address>
									                    </div>
									                    <div class="col-sm-12 pull-right text-right	">
					                                            <a title="Modificar" href="<?php echo base_url('cms-v2/servicios/modificar_informacion/'.$detalle['id'].'/'.$miembro['id']);?>" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i> Modificar</a>
					                                            <a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $miembro['titulo'];?>?" data-estado="<?php echo $miembro['estado'];?>" data-id_contenido="<?php echo $detalle['id'];?>" data-id="<?php echo $miembro['id'];?>" data-target="#myModalEliminarInformacion" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i> Eliminar</a>
									                    </div>
									                </div>
									             </div>
									             <?php } } else { echo 'No se encontraron resultados';} } ?>
										</div>
					                </div>

					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Video/Iframe</h2>
										<div class="form-group pull-left full-width">
											<label class="text-right col-sm-1 control-label">Título</label>
						                    <div class="col-sm-5">
		                                        <div class="input-group"><input type="text" name="contenido10_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['contenido10'])) ? $item['contenido10']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título del Video/Iframe." title=""> <i class="fa fa-question"></i></button></span></div>
		                                    </div>
											<label class="text-right col-sm-1 control-label">Iframe <button type="button" class="btn btn-primary btn-circle" data-toggle="tooltip" data-placement="top" data-original-title="Código completo del Iframe del video." title=""> <i class="fa fa-question"></i></button></label>
						                    <div class="col-sm-5"><textarea name="video_<?php echo $idioma['extension'];?>" class="form-control"><?php echo (isset($item['video'])) ? $item['video']: null; ?></textarea></div>
		                                    
										</div>
					                </div>

					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Fotos avances de obra</h2>
										<div class="form-group pull-left full-width">
						                    <label class="text-right col-sm-1 control-label">Título</label>
						                    <div class="col-sm-5">
		                                        <div class="input-group">
		                                        	<input type="text" name="contenido11_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['contenido11'])) ? $item['contenido11']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de Fotos avances de obra" title=""> <i class="fa fa-question"></i></button></span></div>
		                                    </div>
                                         	<label class="col-sm-1 control-label text-right">Galería</label>
                                         	<div class="col-sm-5">
                                         		<div class="input-group">
												<select name="<?php echo 'contenido12_'.$idioma['extension'];?>" class="form-control m-b">
	                                                <option value="0"> -- Seleccione Galería --</option>
													<?php if (isset($galeria_proyectos)) { 
														foreach($galeria_proyectos as $proyecto)
														{ 
															if($proyecto['id'] == $item['contenido12']) { $selected = 'selected'; } else { $selected = null; }
															echo '<option value="'.$proyecto['id'].'"'.$selected.'>'.$proyecto['descripcion'].'</option></div>';
															$galerias = $this->Servicios_model->comboProyectos($proyecto['id']);
															if($galerias)
															{
																foreach($galerias as $galeria)
																{ 
																	if($galeria['id'] == $item['contenido12']) { $selected = 'selected'; } else { $selected = null; }
																	echo '<option value="'.$galeria['id'].'"'.$selected.'>&nbsp; - '.$galeria['descripcion'].'</option></div>';
																}
															}
														  }
														}  ?></select><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Galería de Fotos avances de obra, se administra desde Multimedia." title=""> <i class="fa fa-question"></i></button></span></div>
                                         	</div>
				                 		</div>
					                </div>
					                
					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Contacto/Ubicación</h2>
										<div class="form-group pull-left full-width">
						                    <label class="text-right col-sm-1 control-label">Título</label>
						                    <div class="col-sm-5">
		                                        <div class="input-group">
		                                        	<input type="text" name="contenido7_<?php echo $idioma['extension'];?>" class="form-control" value="<?php echo (isset($item['contenido7'])) ? $item['contenido7']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de Contacto/Ubicación" title=""> <i class="fa fa-question"></i></button></span></div>
		                                    </div>
                                         	<label class="col-sm-1 control-label text-right">Galería</label>
                                         	<div class="col-sm-5">
                                         		<div class="input-group">
												<select name="<?php echo 'orden_'.$idioma['extension'];?>" class="form-control m-b">
	                                                <option value="0"> -- Seleccione Galería --</option>
													<?php if (isset($galeria_proyectos)) { 
														foreach($galeria_proyectos as $proyecto)
														{ 
															if($proyecto['id'] == $item['orden']) { $selected = 'selected'; } else { $selected = null; }
															echo '<option value="'.$proyecto['id'].'"'.$selected.'>'.$proyecto['descripcion'].'</option></div>';
															$galerias = $this->Servicios_model->comboProyectos($proyecto['id']);
															if($galerias)
															{
																foreach($galerias as $galeria)
																{ 
																	if($galeria['id'] == $item['orden']) { $selected = 'selected'; } else { $selected = null; }
																	echo '<option value="'.$galeria['id'].'"'.$selected.'>&nbsp; - '.$galeria['descripcion'].'</option></div>';
																}
															}
														  }
														}  ?></select><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Galería de Contacto, se administra desde Multimedia." title=""> <i class="fa fa-question"></i></button></span></div>
                                         	</div>
				                 		</div>
										<div class="form-group pull-left full-width">
											<label class="text-right col-sm-1 control-label">Google Maps <button type="button" class="btn btn-primary btn-circle btn-sm" data-toggle="tooltip" data-placement="top" data-original-title="HTML de Google Maps. Debe copiar el mismo desde la ubicación del lugar en Maps de Google > Compartir > Insertar un mapa > Copiar HTML, sacando del mismo el alto (height) y el ancho (width)." title=""> <i class="fa fa-question"></i></button></label>

						                    <div class="col-sm-5"><textarea name="contenido9_<?php echo $idioma['extension'];?>" class="form-control"><?php echo (isset($item['contenido9'])) ? $item['contenido9']: null; ?></textarea></div>
										</div>
					                </div>

					                <div class="col-lg-12 p-xxs">
					                    <div class="pull-left full-width">
											<h2 class="b-r-sm bg-muted p-xs pull-left full-width">Empresas que acompaña<a title="Ordenar" href="<?php echo base_url('cms-v2/servicios/ordenar_items/'.$detalle['id'].'/154/'.$idioma['extension']);?>" class="sepV_a btn btn-primary btn-sm red pull-right m-l-sm"><i class="fa fa-sort-circle"></i> Ordenar</a> 
											<a title="Ingresar" id="item" href="#" data-toggle="modal" data-id="154" data-id_contenido="<?php echo $detalle['id'];?>" data-idioma="<?php echo $idioma['extension'];?>" data-target="#myModalIngresarLogos" class="sepV_a btn btn-primary btn-sm red pull-right"><i class="fa fa-plus-circle"></i> Ingresar</a></h2>
					                    </div>
						                <?php 
											if(!empty($detalle['id']))
											{
												$parametros1['id'] = $detalle['id'];
												$parametros1['id_tipo'] = 154; 
												$parametros1['idioma'] = $idioma['extension'];
												$miembros= $CI->Servicios_model->getServicioAdicionalIdioma($parametros1);

								               if(!empty($miembros)) {
												foreach($miembros as $miembro) { ?>	
								                <div class="col-md-6 col-lg-3 box-items">
													<div class="contact-box<?php echo ($miembro['estado'] == 1) ? ' bg-inactiva' : '';?>">
									                    <div class="col-sm-12">
									                        <div class="text-center">
									                            <?php if($miembro['imagen']) { ?>
									                            	<img src="<?php echo base_url('multimedia/thumbs/'.$miembro['imagen']);?>" title="<?php echo $miembro['titulo']; ?>" alt="<?php echo $miembro['titulo'];?>" class="m-b-xs" style="width:auto; max-width:100%;">
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
				                                            <a title="Modificar" href="<?php echo base_url('cms-v2/servicios/modificar_informacion/'.$detalle['id'].'/'.$miembro['id']);?>" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i> Modificar</a>
				                                            <a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion="<?php echo $miembro['titulo'];?>?" data-estado="<?php echo $miembro['estado'];?>" data-id_contenido="<?php echo $detalle['id'];?>" data-id="<?php echo $miembro['id'];?>" data-target="#myModalEliminarInformacion" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i> Eliminar</a>
									                    </div>
									                </div>
									             </div>
									             <?php } } else { echo 'No se encontraron resultados';} } ?>
					                </div>

					                <div class="col-lg-12 p-xxs">
										<h2 class="b-r-sm bg-muted p-xs pull-left full-width">SEO</h2>
										<div class="form-group pull-left full-width">
						                    <div class="col-md-6">
						                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>Título</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Título de la página." title=""> <i class="fa fa-question"></i></button></div>
							                    <div class="ibox-content no-padding"><textarea class="form-control" name="seo_titulo_<?php echo $idioma['extension'];?>" rows="5"><?php echo(isset($item['seo_titulo'])) ? $item['seo_titulo']: null?></textarea></div>
						                    </div>
						                    <div class="col-md-6">
						                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>Descripci&oacute;n</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Descripción de la página." title=""> <i class="fa fa-question"></i></button></div>
							                    <div class="ibox-content no-padding"><textarea class="form-control" name="seo_descripcion_<?php echo $idioma['extension'];?>" rows="5"><?php echo(isset($item['seo_descripcion'])) ? $item['seo_descripcion']: null?></textarea></div>
						                    </div>
					                 	</div>
									</div>
			                </div>
						</div>
                       	<?php } ?>
					  <!-- Fin Items Idiomas -->
                     <?php echo form_close();?>
                     
                    </div>
                 </div>
             </div>                 
         </div>
     </div>     

<!-- Modal Ingresar Plantas -->
<div class="modal inmodal" id="myModalIngresarInformacion" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
   		<div class="modal-content animated">
	        <div class="modal-body p-xs pull-left full-width">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title">Ingresar contenido de Plantas</h4>
		        <form name="ingresar" class="form_ingresar" method="post" action="<?php echo base_url('cms-v2/servicios/ingresar_informacion/'); ?>">
		            <input type="hidden" name="idioma" id="idioma" value="">
		            <input type="hidden" name="id_contenido" id="id_contenido" value="">
                    <input type="hidden" name="id_tipo" id="id" value="">
                    <div class="col-sm-12">
	                    <label class="control-label col-sm-3">Título</label>
		                <div class="input-group col-sm-9"><input type="text" name="titulo" id="titulo" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Nombre de la planta, por ej. 'Planta Baja'." title=""> <i class="fa fa-question"></i></button></span></div>
	                    <label class="control-label col-sm-3">Galería</label>
						<div class="input-group col-sm-9"><?php echo form_dropdown('id_proyecto', $media_proyectos, null, 'class="form-control m-b-sm"'); ?><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Galería de asocia a la planta, se administra desde Multimedia." title=""> <i class="fa fa-question"></i></button></span></div>
                    </div>
                    <div class="col-sm-12">
                    	<label class="control-label col-sm-3">Estado</label>
                    	<div class="input-group col-sm-9">
	                        <select name="estado" id="estado" class="form-control m-b">
	                            <option value="1">Inactivo</option>
	                            <option value="3">Activo</option>
	                        </select><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Si es activo se muestra en la web, si es inactivo no se muestra en la web." title=""> <i class="fa fa-question"></i></button></span>
                    	</div>
                    </div>
                    <div class="col-sm-12 m-b-md">
	                    <label class="control-label col-sm-3">Orden</label>
	                    <div class="input-group col-sm-9"><input type="text" name="orden" id="orden" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en que se mostrará el ítem." title=""> <i class="fa fa-question"></i></button></span></div>
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

<!-- Modal Ingresar Logos -->
<div class="modal inmodal" id="myModalIngresarLogos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
   		<div class="modal-content animated">
	        <div class="modal-body p-xs pull-left full-width">
	            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	            <h4 class="modal-title text-center">Ingresar Logo de Empresa</h4>
		        <form name="ingresar" class="form_ingresar" method="post" enctype="multipart/form-data" action="<?php echo base_url('cms-v2/servicios/ingresar_informacion/'); ?>">
		            <input type="hidden" name="idioma" id="idioma" value="">
		            <input type="hidden" name="id_contenido" id="id_contenido" value="">
                    <input type="hidden" name="id_tipo" id="id" value="">
	            	<input type="hidden" name="medidas" value="200x152">
	            	<input type="hidden" name="id_imagen_tipo" value="13">
                    <div class="col-sm-12">
	                    <label class="control-label col-sm-3">Nombre</label>
		                <div class="input-group col-sm-9"><input type="text" name="titulo" id="titulo" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Nombre de la empresa, obligatorio." title=""> <i class="fa fa-question"></i></button></span></div>

                    </div>
                    <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Imagen</label>
                    	<div class="input-group col-sm-9">
	                        <div class="fileinput fileinput-new form-control" data-provides="fileinput"><input type="file" name="imagen"><span class="input-group-btn" style="position: absolute;right: 28px;top: 0;"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Imagen en jpg, gif o png de 200x150 píxeles. Recuerde que el peso debe ser el mínimo posible para no afectar la descarga del site." title=""> <i class="fa fa-question"></i></button></span></div>
                    	</div>
					</div>
                    <div class="col-sm-12 m-b-sm">
	                    <label class="control-label col-sm-3">Orden</label>
	                    <div class="input-group col-sm-9"><input type="text" name="orden" id="orden" value="" class="form-control"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Orden en que se mostrará el ítem." title=""> <i class="fa fa-question"></i></button></span></div>
	                </div>
                    <div class="col-sm-12 m-b-md">
	                    <label class="control-label col-sm-3">Estado</label>
                    	<div class="input-group col-sm-9">
	                        <select name="estado" class="form-control m-b">
	                            <option value="1">Inactivo</option>
	                            <option value="3">Activo</option>
	                        </select><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-sm" data-toggle="tooltip" data-placement="top" data-original-title="Si es activo se muestra en la web, si es inactivo no se muestra en la web." title=""> <i class="fa fa-question"></i></button></span>
                    	</div>
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
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                <h4 class="modal-title">Eliminar contenido</h4>
                <p class="text-center">&iquest;Está seguro de querer eliminar el contenido <em> <input type="text" name="seccion" id="seccion" value="" style="border:1px solid #f8fafb; background:#f8fafb; width:auto !important;"/></em></p>
                <div class="modal-footer">
	                <form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('cms-v2/servicios/eliminar_informacion/'); ?>">
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

<!-- SUMMERNOTE -->
<script src="<?php echo base_url('assets/js/plugins/summernote/summernote.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/summernote-grid.js'); ?>"></script>

<script>
$('.summernote').summernote({
        placeholder: 'Ingrese contenido...',
        tabsize: 2,
        height: 250,
        toolbar: [
          ['insert', ['file'], ['image']],
          ['style', ['style']],
          ['font', ['bold', 'italic', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link']],
          ['view', ['codeview']],
          ['insert', ['grid']]
        ],
        styleTags: ['p', 'code', 'span', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6']
  }).on("summernote.enter", function(we, e) {
      $(this).summernote('pasteHTML', '<br>&VeryThinSpace;');
      e.preventDefault();
});

$('.summernote2').summernote({
        placeholder: 'Ingrese contenido...',
        tabsize: 2,
        height: 120,
        toolbar: [
          ['insert', ['file'], ['image']],
          ['style', ['style']],
          ['font', ['bold', 'italic', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link']],
          ['view', ['codeview']],
          ['insert', ['grid']]
        ],
        styleTags: ['p', 'code', 'span', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6']
  }).on("summernote.enter", function(we, e) {
      $(this).summernote('pasteHTML', '<br>&VeryThinSpace;');
      e.preventDefault();
});

  $('#myModalIngresarInformacion, #myModalIngresarLogos').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var idioma = $(e.relatedTarget).data().idioma;
     var id_contenido = $(e.relatedTarget).data().id_contenido;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#idioma').val(idioma);
      $(e.currentTarget).find('#id_contenido').val(id_contenido);
      $(e.currentTarget).find('#estado').val(estado);
  });

  $('#myModalEliminarInformacion').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var seccion = $(e.relatedTarget).data().seccion;
     var estado = $(e.relatedTarget).data().estado;
     var id_contenido = $(e.relatedTarget).data().id_contenido;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#seccion').val(seccion);
      $(e.currentTarget).find('#estado').val(estado);
      $(e.currentTarget).find('#id_contenido').val(id_contenido);
  });

$('[data-toggle="tooltip"]').tooltip(); 
</script>