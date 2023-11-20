<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Contactos</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('administracion/contactos'); ?>">Contactos</a>
	                    </li>
	                    <li class="active">
	                        <strong><?php echo (!isset($detalle['id'])) ? 'Ingresar' : 'Modificar'; ?></strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
	                    <?php if ($this->usuario->perfil == 'reseller' && !empty($detalle['id'])) { ?>
                        	<a href="<?php echo base_url('administracion/contactos/eliminar/') . $detalle['id']; ?>" class="btn btn-primary btn-sm">Eliminar contacto</a>
						<?php } ?>
                    </div>
                </div>
	        </div>
	        
	        <div class="wrapper wrapper-content animated fadeInRight">
		        <div class="row">
		            <div class="col-lg-12">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title">
		                        <h5><?php echo (empty($detalle['id'])) ? 'Crear nuevo contacto' : 'Modificar contacto'; ?></h5>
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
								
	                            <?php echo form_open(null, array('class'=>'form-horizontal', 'autocomplete'=>'off')); ?>
	                            	<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">

		                            <?php if (!isset($empresas['error'])) { ?>
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Empresa</label>
		                                <div class="col-sm-4">
			                                <?php echo form_dropdown('id_empresa', $empresas, (isset($detalle['id_empresa'])) ? $detalle['id_empresa'] : null, 'class="form-control m-b"'); ?>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            <?php } ?>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Nombre</label>
		                                <div class="col-sm-4">
			                                <input type="text" class="form-control" name="nombre" value="<?php echo (isset($detalle['nombre'])) ? $detalle['nombre'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">Apellido</label>
		                                <div class="col-sm-4">
			                                <input type="text" class="form-control" name="apellido" value="<?php echo (isset($detalle['apellido'])) ? $detalle['apellido'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Email</label>
		                                <div class="col-sm-4">
			                                <input type="text" class="form-control" name="email" value="<?php echo (isset($detalle['email'])) ? $detalle['email'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">Sexo</label>
			                            <div class="col-sm-4">
				                            <div class="radio radio-inline">
			                                	<input type="radio" name="sexo" value="F" <?php if (isset($detalle['sexo']) && $detalle['sexo'] == 'F') echo 'checked="checked"'; ?>> <label> Femenino </label>
				                            </div>
				                            <div class="radio radio-inline">
	                                        	<input type="radio" name="sexo" value="M" <?php if (isset($detalle['sexo']) && $detalle['sexo'] == 'M') echo 'checked="checked"'; ?>><label> Masculino </label>
				                            </div>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Teléfono</label>
		                                <div class="col-sm-4">
			                                <input type="text" class="form-control" name="telefono" value="<?php echo (isset($detalle['telefono'])) ? $detalle['telefono'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">Celular</label>
		                                <div class="col-sm-4">
			                                <input type="text" class="form-control" name="celular" value="<?php echo (isset($detalle['celular'])) ? $detalle['celular'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Area privada</label>
		                                <div class="col-sm-2">
			                                <?php if (isset($accion) && $accion == 'ingresar') { ?>
			                                	<?php echo form_dropdown('area_privada', $perfiles, $detalle['area_privada'], 'class="form-control m-b"'); ?>
			                                <?php } else { ?>
				                                <?php if ($this->usuario->id != $detalle['id'] && ($detalle['id_perfil'] == 0 || $detalle['id_perfil'] > 2)) { ?>
				                                	<?php echo form_dropdown('area_privada', $perfiles, $detalle['area_privada'], 'class="form-control m-b"'); ?>
				                                <?php } else { ?>
				                                	<input type="text" class="form-control" value="<?php echo $this->usuario->perfil; ?>" disabled="true">
				                                <?php } ?>
											<?php } ?>
			                            </div>
			                            <label class="col-sm-2 control-label">Usuario</label>
		                                <div class="col-sm-2">
			                            	<input type="text" class="form-control" name="username" value="<?php echo (isset($detalle['username'])) ? $detalle['username'] : null; ?>"  <?php if (isset($detalle['username'])) echo 'disabled="true" readonly="true"'; ?>>
			                            </div>
			                            <label class="col-sm-2 control-label">Contraseña</label>
		                                <div class="col-sm-2">
			                                <input type="password" class="form-control" name="password">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Idioma</label>
		                                <div class="col-sm-2">
			                                <?php echo form_dropdown('idioma', $idiomas, $detalle['idioma'], 'class="form-control m-b"'); ?>
			                            </div>
			                            <label class="col-sm-2 control-label">Zona horaria</label>
		                                <div class="col-sm-6">
			                                <?php echo timezone_menu($detalle['timezone'], 'form-control m-b', 'timezone'); ?>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Estados</label>
			                            <div class="col-sm-10">
			                            	<div class="radio radio-inline">
			                                	<input type="radio" name="estado" value="1" <?php if (isset($detalle['estado']) && $detalle['estado'] == 1) echo 'checked="checked"'; ?>>
			                                	<label> Inactivo </label>
				                            </div>
				                            <div class="radio radio-inline">
	                                        	<input type="radio" name="estado" value="2" <?php if (isset($detalle['estado']) && ($detalle['estado'] == 2 || $detalle['estado'] == 3)) echo 'checked="checked"'; ?>>
	                                        	<label> Activo </label>
				                            </div>
				                        </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
		                                <div class="col-sm-4 col-sm-offset-2">
		                                    <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
		                                    <button class="btn btn-primary" type="submit">Guardar cambios</button>
		                                </div>
		                            </div>
		                        </form>
		                    </div>
		                </div>
		            </div>
		        </div>
	        </div>