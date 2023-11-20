<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
                    <h2>Hosting</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('hosting'); ?>">Planes</a>
	                    </li>
	                    <li class="active">
	                        <strong>Cambiar contraseña de email</strong>
	                    </li>
	                </ol>
                </div>
            </div>

			<div class="wrapper wrapper-content">
                <div class="middle-box text-center animated fadeInRightBig">
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
					
					<?php if (isset($detalle['password'])) { ?>
						<h3 class="font-bold">Cambiar contraseña de email</h3>
	                    <div class="error-desc">
	                        <p>La nueva contraseña para el email <?php echo $detalle['email']; ?> es <strong><?php echo $detalle['password']; ?></strong></p>
	                        
	                        <?php if ($this->usuario->perfil == 'reseller') { ?>
	                        	<a class="btn btn-white" type="submit" href="<?php echo base_url('hosting/detalle/' . $detalle['id']); ?>">Volver</a>
	                        <?php } else { ?>
	                        	<a class="btn btn-white" type="submit" href="<?php echo base_url('micuenta/servicios/detalle/' . $detalle['id_servicio']); ?>">Volver</a>
	                        <?php } ?>
	                    </div>
					
					<?php } else { ?>
		                <?php echo form_open(null, array('class'=>'form-horizontal')); ?>
		                	<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
		                	<input type="hidden" name="id_servicio" value="<?php echo (!empty($detalle['id_servicio'])) ? $detalle['id_servicio'] : null; ?>">
		                	<input type="hidden" name="email" value="<?php echo (!empty($detalle['email'])) ? $detalle['email'] : null; ?>">
		                    <h3 class="font-bold">Cambiar contraseña de email</h3>
		                    <div class="error-desc">
		                        <p>¿Estás seguro que quieres cambiar la contraseña del email <strong><?php echo $detalle['email']; ?></strong>?</p>
		                        
		                        <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
			                    <button class="btn btn-primary" type="submit">Cambiar</button>
		                    </div>
	                    </form>
                    <?php } ?>
                </div>
            </div>