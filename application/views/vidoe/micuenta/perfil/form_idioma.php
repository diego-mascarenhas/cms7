<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

		    <div id="content-wrapper">
		        <div class="container-fluid">
					<div class="row">
						<div class="col-lg-12">
							<div class="main-title">
								<h6>Modificación de idioma</h6>
							</div>
							<hr>
						</div>
					</div>
		            <div class="row">
		                <div class="col-lg-12">
		                    <?php if (validation_errors()) : ?>
	                            <div class="col-md-12">
	                                <div class="alert alert-danger" role="alert">
	                                    <?php echo validation_errors(); ?>
	                                </div>
	                            </div><?php endif; ?><?php if (isset($error)) : ?>

	                            <div class="col-md-12">
	                                <div class="alert alert-danger" role="alert">
	                                    <?php echo $error; ?>
	                                </div>
	                            </div>
	                        <?php endif; ?>

	                        <?php echo form_open(null, array('class'=>'form-horizontal')); ?>
								<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
			                    <div class="osahan-form">
			                        <div class="row">
			                            <div class="col-lg-6">
			                                <div class="form-group">
			                                    <label for="e1">Idioma</label> <?php echo form_dropdown('idioma', $idiomas, $detalle['idioma'], 'class="custom-select"'); ?>
			                                </div>
			                            </div>

			                            <div class="col-lg-6">
			                                <div class="form-group">
			                                    <label for="e1">Zona horaria</label> <?php echo timezone_menu($detalle['timezone'], 'custom-select', 'timezone'); ?>
			                                </div>
			                            </div>
			                        </div>
			                    </div>
								<div class="col-lg-12 text-center">
									<button class="btn btn-secondary" type="submit" style="margin-bottom: 25px;">
									<?php if ($this->session->has_userdata('referrer')) { ?>
			                        	<a href="<?php echo $this->session->userdata('referrer'); ?>" style="color:white">Cancelar</a>
			                        <?php } ?>
									</button>
									<button class="btn btn-primary" type="submit" style="margin-bottom: 25px;">Guardar cambios</button>
								</div>
							</form>
			            </div>
		            </div>
		        </div>
