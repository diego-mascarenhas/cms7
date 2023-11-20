<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Mi cuenta</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url('micuenta'); ?>"><?php echo $this->lang->line('cms_users-mi-cuenta'); ?></a>
	                    </li>
	                    <li class="active">
	                        <strong>Idioma</strong>
	                    </li>
	                </ol>
	            </div>
	        </div>
	        
	        <div class="wrapper wrapper-content animated fadeInRight">
		        <div class="row">
		            <div class="col-lg-12">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title">
		                        <h5>Modificar idioma y zona horaria</h5>
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
								
	                            <?php echo form_open(null, array('class'=>'form-horizontal')); ?>
	                            	<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">

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
		                                <div class="col-sm-4 col-sm-offset-2">
		                                    <?php if ($this->session->has_userdata('referrer')) { ?> <a class="btn btn-white" type="submit" href="<?php echo $this->session->userdata('referrer'); ?>">Cancelar</a><?php } ?>
		                                    <button class="btn btn-primary" type="submit">Guardar cambios</button>
		                                </div>
		                            </div>
		                        </form>
		                    </div>
		                </div>
		            </div>
		        </div>
	        </div>