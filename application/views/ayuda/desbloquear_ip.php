<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
                    <h2>Mesa de ayuda</h2>
                    <ol class="breadcrumb">
                        <li>
	                        <a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('cms_users-home'); ?></a>
	                    </li>
	                    <li>Ayuda</li>
                        <li class="active"><strong>IP Bloqueada</strong></li>
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
					
	                <?php echo form_open(null, array('class'=>'form-horizontal')); ?>
	                	<input type="hidden" name="ip" value="<?php echo (!empty($detalle['ip'])) ? $detalle['ip'] : null; ?>">
	                    <h3 class="font-bold">IP Bloqueada</h3>
	                    <div class="error-desc">
	                        <p>Hemos detectado que su IP (<strong><?php echo $detalle['ip']; ?></strong>) se encuentra bloqueada y por ese motivo no puede usar los servicios.</p>
	                        <p>Una vez solicitado el desbloqueo, el sistema puede llegar a tardar hasta dos horas en reactivarlo.</p>
	                        <p><small>Por favor revise sus dispositivos para descartar que el bloqueo se haya producido por un virus o troyano.</small></p>
	                        
		                    <button class="btn btn-primary" type="submit">Solicitar desbloqueo</button>
	                    </div>
                    </form>
                </div>
            </div>