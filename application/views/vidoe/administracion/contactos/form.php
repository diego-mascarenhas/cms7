<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

		<div id="content-wrapper">
			<div class="container-fluid">
	            <div class="row">
					<div class="col-lg-12">
						<div class="main-title">
							<h6><?php echo (!empty($detalle['id'])) ? 'Modificación de usuario' : 'Creación de nuevo usuario'; ?></h6>
						</div>
						<hr>
					</div>
				</div>
                <div class="ibox float-e-margins">
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
				   </div>

                    <?php echo form_open(null, array('class'=>'form-horizontal', 'autocomplete'=>'off')); ?>
                    	<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
                    	<input type="hidden" name="area_privada" value="<?php echo (!empty($detalle['area_privada'])) ? $detalle['area_privada'] : 4; ?>">
                    	<input type="hidden" name="redirect" value="administracion/contactos">

                        <?php if (!isset($empresas['error'])) { ?>
                        <div class="form-group">
                            <label class="col-lg-12">Empresa</label>
                            <div class="col-lg-12">
                                <?php echo form_dropdown('id_empresa', $empresas, (isset($detalle['id_empresa'])) ? $detalle['id_empresa'] : null, 'class="form-control m-b"'); ?>
                            </div>
                        </div>
                        <?php } ?>

						<div class="row">
						    <div class="col-sm-6">
						        <div class="form-group">
						            <label class="control-label">Nombre<span class="required">*</span></label>
						            <input type="text" class="form-control border-form-control" name="nombre" value="<?php echo (isset($detalle['nombre'])) ? $detalle['nombre'] : null; ?>">
						        </div>
						    </div>
						    <div class="col-sm-6">
						        <div class="form-group">
						            <label class="control-label">Apellido<span class="required">*</span></label>
						            <input type="text" class="form-control" name="apellido" value="<?php echo (isset($detalle['apellido'])) ? $detalle['apellido'] : null; ?>">
						        </div>
						    </div>
						</div>

						<div class="row">
						    <div class="col-sm-6">
						        <div class="form-group">
						            <label class="control-label">Email<span class="required">*</span></label>
						            <input type="text" class="form-control" name="email" value="<?php echo (isset($detalle['email'])) ? $detalle['email'] : null; ?>">
						        </div>
						    </div>
						</div>
						
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label">Usuario<span class="required">*</span></label>
									<input type="text" class="form-control" name="username" value="<?php echo (isset($detalle['username'])) ? $detalle['username'] : null; ?>"  <?php if (isset($detalle['username'])) echo 'disabled="true" readonly="true"'; ?>>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label">Contraseña<span class="required">*</span></label>
									<input type="password" class="form-control" name="password">
								</div>
							</div>
						</div>
						
						<div class="row">
						    <div class="col-sm-6">
						        <div class="form-group">
						            <label>Perfil</label>
					                <?php if (isset($accion) && $accion == 'ingresar') { ?>
	                                	<?php echo form_dropdown('area_privada', $perfiles, $detalle['area_privada'], 'class="custom-select"'); ?>
	                                <?php } else { ?>
		                                <?php if ($this->usuario->id != $detalle['id'] && ($detalle['id_perfil'] == 0 || $detalle['id_perfil'] > 2)) { ?>
		                                	<?php echo form_dropdown('area_privada', $perfiles, $detalle['area_privada'], 'class="custom-select"'); ?>
		                                <?php } else { ?>
		                                	<input type="text" class="form-control" value="<?php echo $this->usuario->perfil; ?>" disabled="true">
		                                <?php } ?>
									<?php } ?>
						        </div>
						    </div>
						    <div class="col-sm-6">
						        <div class="form-group">
						            <label>Estado</label>
						            <select class="custom-select" name="estado">
						                <option name="estado" value="1" <?php if (isset($detalle['estado']) && $detalle['estado'] == 1) echo 'selected'; ?> >Inactivo</option>
						                <option value="2" <?php if (isset($detalle['estado']) && ($detalle['estado'] == 2 || $detalle['estado'] == 3)) echo 'selected'; ?> >Activo</option>
						            </select>
						        </div>
						    </div>

                        <div class="col-lg-12 text-center">
							<button class="btn btn-secondary" type="submit" href="javascript:window.history.go(-1);" style="margin-bottom: 35px;">Cancelar</button>
						    <button class="btn btn-primary" type="submit" style="margin-bottom: 35px;">Guardar cambios</button>
                        </div>
					</div>
				</form>
			</div>
		</div>