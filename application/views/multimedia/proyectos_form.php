<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Multimedia</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('multimedia/'); ?>">Multimedia</a>
	                    </li>
	                    <li class="active">
	                        <strong>Proyectos</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
		            <div class="title-action">
			            <?php if ( ($this->usuario->perfil == 'admin') && (!empty($detalle['id'])) ) { ?>
		            		<a href="<?php echo base_url('multimedia/compartir-proyecto/') . $detalle['id']; ?>" class="btn btn-primary btn-sm">Compartir proyecto</a>
		            		<a href="<?php echo base_url('multimedia/eliminar-proyecto/') . $detalle['id']; ?>" class="btn btn-primary btn-sm">Eliminar proyecto</a>
						<?php } ?>
					</div>
	            </div>
	        </div>
	        
			<div class="wrapper wrapper-content animated fadeInRight">
		        <div class="row">
		            <div class="col-lg-12">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title">
			                    <h5><?php echo (!isset($detalle['id'])) ? 'Ingresar proyecto' : 'Modificar proyecto'; ?></h5>
		                    </div>
		                    <div class="ibox-content">
		                        <?php if (validation_errors()) : ?>
									<div class="col-md-12">
										<div class="alert alert-danger" role="alert">
											<?php echo validation_errors(); ?>
										</div>
									</div>
								<?php endif; ?>
								<?php if (isset($error)) : ?>
									<div class="col-md-12">
										<div class="alert alert-danger" role="alert">
											<?php echo $error; ?>
										</div>
									</div>
								<?php endif; ?>
								
		
		                        <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
				                <input type="hidden" name="id" value="<?php echo (isset($detalle['id'])) ? $detalle['id'] : null; ?>">
	
								<div class="form-group">
		                            <label class="col-sm-2 control-label">Proyecto</label>
	                                <div class="col-sm-4">
		                                <input type="text" name="proyecto" class="form-control" value="<?php echo (isset($detalle['proyecto'])) ? $detalle['proyecto']: null; ?>">
		                            </div>
		                           
		                            <label class="col-sm-2 control-label">Padre</label>
	                                <div class="col-sm-4">
										<div class="input-group m-b">
	                                        <div class="input-group-btn open">
												<select name="padre" class="form-control m-b">
	                                                <option value="0"> -- Seleccione Padre --</option>
													<?php if (isset($proyectos)) { ?>
													<?php
														function menuProyectosVista($menu, $nivel=null)
														{
															$CI =& get_instance();
															if($CI->uri->segment(3))
															{
																$sql = "SELECT media_proyectos.id, media_proyectos.padre";
																$sql .= " FROM media_proyectos";
																$sql .= " WHERE media_proyectos.id = " . $CI->uri->segment(3);
																$query = $CI->db->query($sql);
																$item = $query->row_array();
															}
															?>
															<?php foreach($menu as $obj): ?>
												                <?php if (!isset($nivel)) { ?>
										                    		 	<div class="nav-label nav-first-level">
																	 	<option value="<?php echo $obj['id']; ?>"<?php if(isset($item['padre'])){ echo ($obj['id'] == $item['padre']) ? ' selected' : '';}?>><?php echo $obj['proyecto']; ?></option></div>
												                    	<?php } else { ?>
																			<option value="<?php echo $obj['id']; ?>"<?php if(isset($item['padre'])){ echo ($obj['id'] == $item['padre']) ? ' selected' : '';}?>>
																			<?php if ($obj['nivel'] == 2) { echo ' &nbsp; ';} elseif ($obj['nivel'] == 3) { echo ' &nbsp; &nbsp; ';} ?><?php echo (isset($obj['proyecto'])) ? $obj['proyecto'] : $obj['id']; ?></option>
																			<?php } ?>
												                    	<?php if (isset($obj['hijos'])): ?><?php endif; ?>
					
												                	<?php if (isset($obj['hijos'])): ?>
												                		<?php
													                		switch ($obj['nivel'])
													                		{
													                			case 2:
													                				$level_ui_class = 'nav-third-level';
													                				break;
													                			case 3:
													                				$level_ui_class = 'nav-fourth-level';
													                				break;
													                			default:
													                				$level_ui_class = 'nav-second-level';
													                				break;
													                		}
												                		?>
												                		<ul class="nav <?php echo $level_ui_class; ?>">
												                			<?php menuProyectosVista($obj['hijos'], $obj['nivel']); ?>
												                		</ul>
												                	<?php endif; ?>
															<?php endforeach; ?>
															<?php 
														}
									                
									                	menuProyectosVista($proyectos);
									                ?>
					                            <?php } ?>
												</select>    
											</div> 
										</div>
		                            </div>
	                            </div>
	
								<div class="form-group">
		                            <label class="col-sm-2 control-label">Miniatura</label>
		                            <div class="col-sm-10">
		                            	<input type="file" name="file" class="form-control">
		                            	<small><em>Por el momento solo acepta archivos JPG</em></small>
			                        </div>
		                        </div>
		                        
		                        <div class="form-group">
		                            <label class="col-sm-2 control-label">Estados</label>
		                            <div class="col-sm-10">
		                            	<div class="radio radio-inline">
		                                	<input type="radio" name="estado" value="1" <?php if (isset($detalle['estado']) && $detalle['estado'] == 1) echo 'checked="checked"'; ?>>
		                                	<label> Inactivo </label>
			                            </div>
			                            <div class="radio radio-inline">
	                                    	<input type="radio" name="estado" value="2" <?php if (isset($detalle['estado']) && $detalle['estado'] == 2) echo 'checked="checked"'; ?>>
	                                    	<label> Activo </label>
			                            </div>
			                            <div class="radio radio-inline">
	                                    	<input type="radio" name="estado" value="3" <?php if (isset($detalle['estado']) && $detalle['estado'] == 3) echo 'checked="checked"'; ?>>
	                                    	<label> Público </label>
			                            </div>
			                        </div>
		                        </div>
								<div class="hr-line-dashed"></div>
		                    	
								<div class="form-group">
	                                <div class="col-sm-4 col-sm-offset-2">
						                <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
						                <button class="btn btn-primary" type="submit">Guardar cambios</button>
										</form>
	                                </div>
	                            </div>
		                            
		                    </div>
		                </div>
		            </div>
		        </div>
		
				<?php if (!empty($detalle['proyecto'])) { ?>
		            <div class="row">
		                <div class="col-lg-12">
			                <div class="ibox float-e-margins">
			                    <div class="ibox-title">
				                    <h5>Usuarios que pueden ver <?php echo $detalle['proyecto']; ?></h5> 
			                    </div>
			                </div>
		                </div>
		            </div>
		            
			        <div class="row">
					<?php if (!empty($relacionar)) { ?>
						<?php foreach ($relacionar as $contacto) { ?>
			            <div class="col-lg-4">
			                <div class="contact-box">
			                    <div class="col-sm-4">
			                        <div class="text-center">
										<?php if ($contacto['avatar'] != NULL) { ?>
										<img alt="image" class="img-circle m-t-xs img-responsive" src="img/a2.jpg">								
										<?php } else { ?>
			                            <i class="fa fa-user fa-4x"></i>
										<?php } ?>
			                        </div>
			                    </div>
			                    <div class="col-sm-8">
			                        <h3 class="mb_2"><a href="<?php echo base_url('administracion/contactos/detalle/' . $contacto['id']); ?>"><?php echo $contacto['contacto']; ?></a></h3>
			                        <p><strong><?php echo $contacto['username']; ?></strong></p>
			                    </div>
			                    <div class="clearfix"></div>
			                </div>
			            </div>
			            <?php } ?>
					<?php } else { ?>
			            <div class="col-lg-12">
			                <div class="contact-box">No se ha compartido el contenido aún.</div>
			            </div>
					<?php } ?>
		
					</div>
				<?php } ?>
			</div>

