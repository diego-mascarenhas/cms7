            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-xs-8 col-sm-8 col-lg-8">
                    <h2>Sitio web Configuración</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="/micuenta">Home</a>
                        </li>
                        <li class="active">
                            <strong>Configuración</strong>
                        </li>
                    </ol>
                </div>
            </div>

       	<!-- Comienzo Tabs -->
        <div class="wrapper wrapper-content">
            <div class="row">
			  <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="tabs-container m-b-md">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#tab-0"> Español</a></li>
                        	<?php if($adicionales) { foreach($adicionales as $adicional) { ?><li><a data-toggle="tab" href="#tab-<?php echo $adicional['id'];?>"> Portugués</a></li><?php } } ?>
                        </ul>
                        <div class="tab-content">
	                        <div id="tab-0" class="tab-pane active">
			                    <div class="ibox-content no-borders">
					           		<?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
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
									<h2>Datos del sitio</h2>
				                 	<div class="form-group">
					                    <label class="col-sm-1 control-label">Título</label>
					                    <div class="col-sm-5">
					                        <div class="input-group">
					                    		<input type="text" name="titulo" class="form-control" value="<?php echo (isset($item['titulo'])) ? $item['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Nombre." title=""> <i class="fa fa-question"></i></button></div>
			                            </div>
					                    <label class="col-sm-1 control-label">Tagline</label>
					                    <div class="col-sm-5">
					                        <div class="input-group">
						                        <input type="text" name="subtitulo" class="form-control" value="<?php echo (isset($item['subtitulo'])) ? $item['subtitulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Tagline." title=""> <i class="fa fa-question"></i></button></div>
					                    </div>
				                 	</div>
			
				                 	<div class="form-group">
					                    <div class="col-md-4">
					                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>Logo</h5></div>
											<div class="ibox-content">
					                            <?php if(!empty($item['logo'])) { ?>
				                            	<p>Imagen Actual</p>
				                            	<img src="<?php echo base_url('/multimedia/'.$this->usuario->grupo.'/'.$this->usuario->id_empresa.'/'.$item['logo']);?>" title="<?php echo $item['titulo'];?>" alt="<?php echo $item['titulo'];?>" style="height:70px;"/>
				                            <?php } ?>
												<br><br>
					                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
					                                <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
					                                <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Seleccionar imagen</span><span class="fileinput-exists">Cambiar</span><input type="file" name="logo"></span>
					                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>	
						                    	</div>
											</div>
					                    </div>
			
					                    <div class="col-md-4">
					                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>Logo Pie</h5></div>
											<div class="ibox-content">
					                            <?php if(!empty($item['logo_pie'])) { ?>
				                            	<p>Imagen Actual</p>
				                            	<img src="<?php echo base_url('/multimedia/'.$this->usuario->grupo.'/'.$this->usuario->id_empresa.'/'.$item['logo_pie']);?>" title="<?php echo $item['titulo'];?>" alt="<?php echo $item['titulo'];?>" style="height:70px;"/>
				                            <?php } ?>
												<br><br>
					                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
					                                <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
					                                <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Seleccionar imagen</span><span class="fileinput-exists">Cambiar</span><input type="file" name="logo_pie"></span>
					                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>	
						                    	</div>
											</div>
					                    </div>
					                    <div class="col-md-4">
					                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>Favicon</h5></div>
											<div class="ibox-content">
					                            <?php if(!empty($item['favicon'])) { ?>
				                            	<p>Imagen Actual</p>
				                            	<img src="<?php echo base_url('/multimedia/'.$this->usuario->grupo.'/'.$this->usuario->id_empresa.'/'.$item['favicon']);?>" title="<?php echo $item['titulo'];?>"" alt="<?php echo $item['titulo'];?>" style="height:70px;"/>
				                            <?php } ?>
												<br><br>
					                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
					                                <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
					                                <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Seleccionar imagen</span><span class="fileinput-exists">Cambiar</span><input type="file" name="favicon"></span>
					                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>	
						                    	</div>
											</div>
					                    </div>
				                 	</div>
								 	<div class="hr-line-dashed"></div>
			
									<h2>Datos de contacto</h2>
				                 	<div class="form-group">
					                    <label class="col-md-2 control-label">Dirección</label>
					                    <div class="col-md-2"><input type="text" name="direccion" class="form-control" value="<?php echo (isset($item['direccion'])) ? $item['direccion']: null; ?>"></div>
					                    <label class="col-md-2 control-label">Teléfono</label>
					                    <div class="col-md-2"><input type="text" name="telefonos" class="form-control" value="<?php echo (isset($item['telefonos'])) ? $item['telefonos']: null; ?>"></div>
					                    <label class="col-md-2 control-label">WhatsApp</label>
					                    <div class="col-md-2"><input type="text" name="telefono2" class="form-control" value="<?php echo (isset($item['telefono2'])) ? $item['telefono2']: null; ?>"></div>
				                 	</div>
				                 	<div class="form-group">
					                    <label class="col-sm-2 control-label">Web</label>
					                    <div class="col-sm-4"><input type="text" name="web" class="form-control" value="<?php echo (isset($item['web'])) ? $item['web']: null; ?>"></div>
					                    <label class="col-sm-2 control-label">Email</label>
					                    <div class="col-sm-4"><input type="text" name="email" class="form-control" value="<?php echo (isset($item['email'])) ? $item['email']: null; ?>"></div>
				                 	</div><br>
								 	<div class="hr-line-dashed"></div>
				                 	
									<h2>Redes Sociales</h2>
				                 	<div class="form-group">
					                    <label class="col-sm-1 control-label">Facebook</label>
					                    <div class="col-sm-5"><input type="text" name="facebook" class="form-control" value="<?php echo (isset($item['facebook'])) ? $item['facebook']: null; ?>"></div>
					                    <label class="col-sm-1 control-label">Twitter</label>
					                    <div class="col-sm-5"><input type="text" name="twitter" class="form-control" value="<?php echo (isset($item['twitter'])) ? $item['twitter']: null; ?>"></div>
				                 	</div>
				                 	<div class="form-group">
					                    <label class="col-sm-1 control-label">Instagram</label>
					                    <div class="col-sm-5"><input type="text" name="instagram" class="form-control" value="<?php echo (isset($item['instagram'])) ? $item['instagram']: null; ?>"></div>
					                    <label class="col-sm-1 control-label">YouTube</label>
					                    <div class="col-sm-5"><input type="text" name="youtube" class="form-control" value="<?php echo (isset($item['youtube'])) ? $item['youtube']: null; ?>"></div>
				                 	</div>
				                 	<div class="form-group">
					                    <label class="col-sm-1 control-label">Vimeo</label>
					                    <div class="col-sm-5"><input type="text" name="vimeo" class="form-control" value="<?php echo (isset($item['vimeo'])) ? $item['vimeo']: null; ?>"></div>
					                    <label class="col-sm-1 control-label">Linkedin</label>
					                    <div class="col-sm-5"><input type="text" name="linkedin" class="form-control" value="<?php echo (isset($item['linkedin'])) ? $item['linkedin']: null; ?>"></div>
				                 	</div>
				                 	<div class="form-group">
					                    <label class="col-sm-1 control-label">Spotify</label>
					                    <div class="col-sm-5"><input type="text" name="spotify" class="form-control" value="<?php echo (isset($item['spotify'])) ? $item['spotify']: null; ?>"></div>
				                 	</div><br>
								 	<div class="hr-line-dashed"></div>
								 	
									<h2>SEO</h2>
				                 	<div class="form-group">
					                    <div class="col-md-4 sm-12 m_b_25">
									        <div class="ibox-title" style="background:#f7f7f7;"><h5>Descripci&oacute;n</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Descripción de la página." title=""> <i class="fa fa-question"></i></button></div>
						                    <div class="ibox-content no-padding">
							                    <textarea class="form-control summernote" name="descripcion" rows="11"><?php echo (isset($item['descripcion'])) ? $item['descripcion']: null?></textarea>
						                    </div>
					                    </div>
					                    <div class="col-md-4 sm-12 m_b_25">
									        <div class="ibox-title" style="background:#f7f7f7;"><h5>Keywords</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Si bien están prácticamente en desuso, son palabras o frases que se asocian al contenido de la página." title=""> <i class="fa fa-question"></i></button></div>
						                    <div class="ibox-content no-padding">
							                    <textarea class="form-control summernote" name="keywords" rows="11"><?php echo (isset($item['keywords'])) ? $item['keywords']: null?></textarea>
						                    </div>
					                    </div>
					                    <div class="col-md-4 sm-12 m_b_25">
					                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>Analytics</h5></div>
						                    <div class="ibox-content no-padding">
							                    <textarea class="form-control summernote" name="analytics" rows="11"><?php echo (isset($item['analytics'])) ? $item['analytics']: null?></textarea>
						                    </div>
					                    </div>
				                 	</div>
								 	<div class="hr-line-dashed"></div>
			
				                 	<div class="form-group">
										<input type="hidden" name="id_tipo" value="1">
										<input type="hidden" name="template" value="<?php echo (isset($item['template'])) ? $item['template']: null?>">
										<input type="hidden" name="id" value="<?php echo (isset($item['id'])) ? $item['id']: null?>">
						                <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
						                    <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
						                    <button class="btn btn-primary" type="submit">Guardar cambios</button>
						                </div>
				                 	</div>
				                 	<?php echo form_close();?>
			                    </div>
			                </div>

                        	<?php if($adicionales) { foreach($adicionales as $adicional) { ?>
	                        <div id="tab-<?php echo $adicional['id'];?>" class="tab-pane">
			                    <div class="ibox-content no-borders">
					           		<?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
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

									<h2>Datos del sitio</h2>
				                 	<div class="form-group">
					                    <label class="col-sm-1 control-label">Título</label>
					                    <div class="col-sm-5">
					                        <div class="input-group">
					                    		<input type="text" name="titulo" class="form-control" value="<?php echo (isset($adicional['titulo'])) ? $adicional['titulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Nombre." title=""> <i class="fa fa-question"></i></button></div>
			                            </div>
					                    <label class="col-sm-1 control-label">Tagline</label>
					                    <div class="col-sm-5">
					                        <div class="input-group">
						                        <input type="text" name="subtitulo" class="form-control" value="<?php echo (isset($adicional['subtitulo'])) ? $adicional['subtitulo']: null; ?>"><span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Tagline." title=""> <i class="fa fa-question"></i></button></div>
					                    </div>
				                 	</div>
			
				                 	<div class="form-group">
					                    <div class="col-md-4">
					                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>Logo</h5></div>
											<div class="ibox-content">
					                            <?php if(!empty($adicional['logo'])) { ?>
				                            	<p>Imagen Actual</p>
				                            	<img src="<?php echo base_url('/multimedia/'.$this->usuario->grupo.'/'.$this->usuario->id_empresa.'/'.$adicional['logo']);?>" title="<?php echo $adicional['titulo'];?>" alt="<?php echo $adicional['titulo'];?>" style="height:70px;"/>
				                            <?php } ?>
												<br><br>
					                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
					                                <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
					                                <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Seleccionar imagen</span><span class="fileinput-exists">Cambiar</span><input type="file" name="logo"></span>
					                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>	
						                    	</div>
											</div>
					                    </div>
			
					                    <div class="col-md-4">
					                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>Logo Pie</h5></div>
											<div class="ibox-content">
					                            <?php if(!empty($adicional['logo_pie'])) { ?>
				                            	<p>Imagen Actual</p>
				                            	<img src="<?php echo base_url('/multimedia/'.$this->usuario->grupo.'/'.$this->usuario->id_empresa.'/'.$adicional['logo_pie']);?>" title="<?php echo $adicional['titulo'];?>" alt="<?php echo $adicional['titulo'];?>" style="height:70px;"/>
				                            <?php } ?>
												<br><br>
					                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
					                                <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
					                                <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Seleccionar imagen</span><span class="fileinput-exists">Cambiar</span><input type="file" name="logo_pie"></span>
					                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>	
						                    	</div>
											</div>
					                    </div>
					                    <div class="col-md-4">
					                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>Favicon</h5></div>
											<div class="ibox-content">
					                            <?php if(!empty($adicional['favicon'])) { ?>
				                            	<p>Imagen Actual</p>
				                            	<img src="<?php echo base_url('/multimedia/'.$this->usuario->grupo.'/'.$this->usuario->id_empresa.'/'.$adicional['favicon']);?>" title="<?php echo $adicional['titulo'];?>"" alt="<?php echo $adicional['titulo'];?>" style="height:70px;"/>
				                            <?php } ?>
												<br><br>
					                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
					                                <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
					                                <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Seleccionar imagen</span><span class="fileinput-exists">Cambiar</span><input type="file" name="favicon"></span>
					                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>	
						                    	</div>
											</div>
					                    </div>
					                    <div class="col-sm-1"></div>
				                 	</div>
								 	<div class="hr-line-dashed"></div>
			
									<h2>Datos de contacto</h2>
				                 	<div class="form-group">
					                    <label class="col-md-2 control-label">Dirección</label>
					                    <div class="col-md-2"><input type="text" name="direccion" class="form-control" value="<?php echo (isset($adicional['direccion'])) ? $adicional['direccion']: null; ?>"></div>
					                    <label class="col-md-2 control-label">Teléfono</label>
					                    <div class="col-md-2"><input type="text" name="telefonos" class="form-control" value="<?php echo (isset($adicional['telefonos'])) ? $adicional['telefonos']: null; ?>"></div>
					                    <label class="col-md-2 control-label">WhatsApp</label>
					                    <div class="col-md-2"><input type="text" name="telefono2" class="form-control" value="<?php echo (isset($adicional['telefono2'])) ? $adicional['telefono2']: null; ?>"></div>
				                 	</div>
				                 	<div class="form-group">
					                    <label class="col-sm-2 control-label">Web</label>
					                    <div class="col-sm-4"><input type="text" name="web" class="form-control" value="<?php echo (isset($adicional['web'])) ? $adicional['web']: null; ?>"></div>
					                    <label class="col-sm-2 control-label">Email</label>
					                    <div class="col-sm-4"><input type="text" name="email" class="form-control" value="<?php echo (isset($adicional['email'])) ? $adicional['email']: null; ?>"></div>
				                 	</div><br>
								 	<div class="hr-line-dashed"></div>
				                 	
									<h2>Redes Sociales</h2>
				                 	<div class="form-group">
					                    <label class="col-sm-1 control-label">Facebook</label>
					                    <div class="col-sm-5"><input type="text" name="facebook" class="form-control" value="<?php echo (isset($adicional['facebook'])) ? $adicional['facebook']: null; ?>"></div>
					                    <label class="col-sm-1 control-label">Twitter</label>
					                    <div class="col-sm-5"><input type="text" name="twitter" class="form-control" value="<?php echo (isset($adicional['twitter'])) ? $adicional['twitter']: null; ?>"></div>
				                 	</div>
				                 	<div class="form-group">
					                    <label class="col-sm-1 control-label">Instagram</label>
					                    <div class="col-sm-5"><input type="text" name="instagram" class="form-control" value="<?php echo (isset($adicional['instagram'])) ? $adicional['instagram']: null; ?>"></div>
					                    <label class="col-sm-1 control-label">YouTube</label>
					                    <div class="col-sm-5"><input type="text" name="youtube" class="form-control" value="<?php echo (isset($adicional['youtube'])) ? $adicional['youtube']: null; ?>"></div>
				                 	</div>
				                 	<div class="form-group">
					                    <label class="col-sm-1 control-label">Vimeo</label>
					                    <div class="col-sm-5"><input type="text" name="vimeo" class="form-control" value="<?php echo (isset($adicional['vimeo'])) ? $adicional['vimeo']: null; ?>"></div>
					                    <label class="col-sm-1 control-label">Linkedin</label>
					                    <div class="col-sm-5"><input type="text" name="linkedin" class="form-control" value="<?php echo (isset($adicional['linkedin'])) ? $adicional['linkedin']: null; ?>"></div>
				                 	</div>
				                 	<div class="form-group">
					                    <label class="col-sm-1 control-label">Spotify</label>
					                    <div class="col-sm-5"><input type="text" name="spotify" class="form-control" value="<?php echo (isset($adicional['spotify'])) ? $adicional['spotify']: null; ?>"></div>
				                 	</div><br>
								 	<div class="hr-line-dashed"></div>
								 	
									<h2>SEO</h2>
				                 	<div class="form-group">
					                    <div class="col-md-4 sm-12 m_b_25">
									        <div class="ibox-title" style="background:#f7f7f7;"><h5>Descripci&oacute;n</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Descripción de la página." title=""> <i class="fa fa-question"></i></button></div>
						                    <div class="ibox-content no-padding">
							                    <textarea class="form-control summernote" name="descripcion" rows="11"><?php echo (isset($adicional['descripcion'])) ? $adicional['descripcion']: null?></textarea>
						                    </div>
					                    </div>
					                    <div class="col-md-4 sm-12 m_b_25">
									        <div class="ibox-title" style="background:#f7f7f7;"><h5>Keywords</h5> <button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Si bien están prácticamente en desuso, son palabras o frases que se asocian al contenido de la página." title=""> <i class="fa fa-question"></i></button></div>
						                    <div class="ibox-content no-padding">
							                    <textarea class="form-control summernote" name="keywords" rows="11"><?php echo (isset($adicional['keywords'])) ? $adicional['keywords']: null?></textarea>
						                    </div>
					                    </div>
					                    <div class="col-md-4 sm-12 m_b_25">
					                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>Analytics</h5></div>
						                    <div class="ibox-content no-padding">
							                    <textarea class="form-control summernote" name="analytics" rows="11"><?php echo (isset($adicional['analytics'])) ? $adicional['analytics']: null?></textarea>
						                    </div>
					                    </div>
				                 	</div>
								 	<div class="hr-line-dashed"></div>
			
				                 	<div class="form-group">
										<input type="hidden" name="id_tipo" value="1">
										<input type="hidden" name="template" value="<?php echo (isset($item['template'])) ? $item['template']: null?>">
										<input type="hidden" name="id" value="<?php echo (isset($adicional['id'])) ? $adicional['id']: null?>">
						                <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
						                    <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
						                    <button class="btn btn-primary" type="submit">Guardar cambios</button>
						                </div>
				                 	</div>
			                    </div>
			                </div>
			                <?php echo form_close();?>
			                <?php } } ?>
			            </div>
			         </div>
			     </div>
            </div>

        </div>
    </div>
    <!-- Fin Contenido -->
     
<script>
$('[data-toggle="tooltip"]').tooltip(); 
</script>     