<link href="<?php echo base_url('assets/css/tienda.css'); ?>" rel="stylesheet" type="text/css">
			<div class="row wrapper border-bottom white-bg page-heading">
            	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Tienda</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url('tienda/dashboard'); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('tienda/clientes/registrados'); ?>">Clientes registrados</a>
	                    </li>
	                    <li>
	                        <strong>Ingresar</strong>
	                    </li>
	                </ol>
	            </div>
	        </div>
            


	      <div class="wrapper wrapper-content animated fadeInRight">
            <!-- Titulo Mensajes -->
            <div class="row">
                <?php if (validation_errors()) : ?>
				<div class="col-md-12">
					<div class="alert alert-danger" role="alert">
						<?php 
							echo (isset($erroremail)) ? '<p>'.$erroremail.'</p>' : '';
							echo validation_errors(); 
						?>
					</div>
				</div>
				<?php endif; ?>
				<?php if (isset($error)) : ?>
				<div class="col-md-12">
					<div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
				</div>
				<?php endif; ?>
            </div>

            <div class="row">
	            <div class="col-lg-12">
	                <div class="ibox float-e-margins">
	                    <div class="ibox-title">
	                        <h5><?php echo (isset($item['id'])) ? 'Modificar' : 'Crear nuevo'; ?> Cliente</h5>
	                    </div>

	                    <div class="ibox-content">
                            <?php echo form_open(null, array('class'=>'form-horizontal', 'autocomplete'=>'off')); ?>
                            	<input type="hidden" name="id" value="<?php echo (!empty($item['id'])) ? $item['id'] : null; ?>">
                            	<?php if (empty($item['id'])) { ?>
                            		<input type="hidden" name="username" value="<?php echo $this->usuario->id_empresa.rand(200,999).date('YmdHms'); ?>">
                            	<?php } ?>
                            	
	                            <div class="form-group">
		                            <label class="col-sm-2 control-label">Número</label>
	                                <div class="col-sm-4">
		                                <input type="text" class="form-control" name="numero_cliente" value="<?php echo (isset($adicionales['numero_cliente'])) ? $adicionales['numero_cliente'] : null; ?>">
		                            </div>
	                            </div>
	                            <div class="hr-line-dashed"></div>

	                            <div class="form-group">
		                            <label class="col-sm-2 control-label">Nombre</label>
	                                <div class="col-sm-4">
		                                <input type="text" class="form-control" name="nombre" value="<?php echo (isset($item['nombre'])) ? $item['nombre'] : null; ?>">
		                            </div>
		                            <label class="col-sm-2 control-label">Apellido</label>
	                                <div class="col-sm-4">
		                                <input type="text" class="form-control" name="apellido" value="<?php echo (isset($item['apellido'])) ? $item['apellido'] : null; ?>">
		                            </div>
	                            </div>
	                            <div class="hr-line-dashed"></div>
	                            
	                            <div class="form-group">
		                            <label class="col-sm-2 control-label">Usuario/Email</label>
	                                <div class="col-sm-4">
		                                <input type="text" class="form-control" name="email" value="<?php echo (isset($item['email'])) ? $item['email'] : null; ?>">
		                            </div>
		                            <label class="col-sm-2 control-label">Contrase&ntilde;a</label>
	                                <div class="col-sm-2">
		                                <input type="password" class="form-control" name="password">
		                            </div>

	                            </div>
	                            <div class="hr-line-dashed"></div>
	                            
	                            <div class="form-group">
		                            <label class="col-sm-2 control-label">Sexo</label>
		                            <div class="col-sm-4">
			                            <div class="radio radio-inline">
		                                	<input type="radio" name="sexo" value="F" <?php if (isset($item['sexo']) && $item['sexo'] == 'F') echo 'checked="checked"'; ?>> <label> Femenino </label>
			                            </div>
			                            <div class="radio radio-inline">
                                        	<input type="radio" name="sexo" value="M" <?php if (isset($item['sexo']) && $item['sexo'] == 'M') echo 'checked="checked"'; ?>><label> Masculino </label>
			                            </div>
		                            </div>
		                            <label class="col-sm-2 control-label">Estado</label>
		                            <div class="col-sm-4">
		                            	<div class="radio radio-inline">
		                                	<input type="radio" name="estado" value="1" <?php if (isset($item['estado']) && $item['estado'] == 1) echo 'checked="checked"'; ?>>
		                                	<label> Inactivo </label>
			                            </div>
			                            <div class="radio radio-inline">
                                        	<input type="radio" name="estado" value="2" <?php if (isset($item['estado']) && ($item['estado'] == 2 || $item['estado'] == 3)) echo 'checked="checked"'; ?>>
                                        	<label> Activo </label>
			                            </div>
			                        </div>
	                            </div>
	                            <div class="hr-line-dashed"></div>
	                            
	                            <div class="form-group">
		                            <label class="col-sm-2 control-label">Teléfono</label>
	                                <div class="col-sm-4">
		                                <input type="text" class="form-control" name="telefono" value="<?php echo (isset($item['telefono'])) ? $item['telefono'] : null; ?>">
		                            </div>
		                            <label class="col-sm-2 control-label">Celular</label>
	                                <div class="col-sm-4">
		                                <input type="text" class="form-control" name="celular" value="<?php echo (isset($item['celular'])) ? $item['celular'] : null; ?>">
		                            </div>
	                            </div>
	                            <div class="hr-line-dashed"></div>
	                            
	                            <h2>Domicilios</h2>
	                            <div class="form-group">
		                            <label class="col-sm-2 control-label">Dirección</label>
	                                <div class="col-sm-4">
		                                <input type="text" class="form-control" name="domicilio" value="<?php echo (isset($adicionales['domicilio'])) ? $adicionales['domicilio'] : null; ?>">
		                            </div>

		                            <label class="col-sm-2 control-label">Dirección de entrega</label>
	                                <div class="col-sm-4">
		                                <input type="text" class="form-control" name="domicilio_entrega" value="<?php echo (isset($adicionales['domicilio_entrega'])) ? $adicionales['domicilio_entrega'] : null; ?>">
		                            </div>
	                            </div>
	                            <div class="hr-line-dashed"></div>
	                            
								<h2>Datos de facturación</h2>
	                            <div class="form-group">
		                            <label class="col-sm-2 control-label">Razón Social</label>
	                                <div class="col-sm-4">
		                                <input type="text" class="form-control" name="razon_social" value="<?php echo (isset($adicionales['razon_social'])) ? $adicionales['razon_social'] : null; ?>">
		                            </div>
	                            </div>
	                            <div class="hr-line-dashed"></div>
	                            
	                            <div class="form-group">
		                            <label class="col-sm-2 control-label">Condición I.V.A.</label>
	                                <div class="col-sm-4">
		                                <select name="id_condicion_iva" id="facturacion-field" class="form-control input-md">
		                                    <option value="3" <?php if (isset($adicionales['id_condicion_iva']) && $adicionales['id_condicion_iva'] == '3') echo 'selected'; ?>>Consumidor final</option>
		                                    <option value="2" <?php if (isset($adicionales['id_condicion_iva']) && $adicionales['id_condicion_iva'] == '2') echo 'selected'; ?>>Monotributista</option>
		                                    <option value="1" <?php if (isset($adicionales['id_condicion_iva']) && $adicionales['id_condicion_iva'] == '1') echo 'selected'; ?>>Responsable inscripto</option>
		                                    <option value="4" <?php if (isset($adicionales['id_condicion_iva']) && $adicionales['id_condicion_iva'] == '4') echo 'selected'; ?>>IVA exento</option>
		                                </select>
		                            </div>
	                                <div class="col-sm-4">
		                                <fieldset id="cuit-field">
			                                <input type="hidden" name="documento_tipo" value="<?php if (isset($adicionales['id_condicion_iva']) && $adicionales['id_condicion_iva'] == 3) echo 'DNI'; else echo 'CUIT'; ?>">
			                                <input type="text" name="documento" placeholder="<?php if (isset($adicionales['id_condicion_iva']) && $adicionales['id_condicion_iva'] == 3) echo 'DNI (*)'; else echo 'CUIT (*)'; ?>" value="<?php if (!empty($adicionales['documento'])) echo $adicionales['documento']; ?>" class="form-control input-md required">
			                            </fieldset>
		                            </div>
	                            </div>
	                            <div class="hr-line-dashed"></div>

								<h2>Datos de Pago</h2>
	                            <div class="form-group">
		                            <label class="col-sm-2 control-label">Condiciones de pago</label>
	                                <div class="col-sm-4">
		                                <input type="text" name="condiciones" placeholder="" value="<?php echo (isset($adicionales['condiciones'])) ? $adicionales['condiciones'] : null; ?>" class="form-control input-md required">
			                            </fieldset>
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