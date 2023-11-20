<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Agenda</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('agenda'); ?>">Fecha de Agenda</a>
	                    </li>
	                    <li class="active">
	                        <strong>Eliminar fecha</strong>
	                    </li>
	                </ol>
	            </div>
	        </div>

            <div class="wrapper wrapper-content">
                <div class="middle-box text-center animated fadeIn
                ">
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
	                    <h3 class="font-bold">Eliminar fecha</h3>
	                    <div class="error-desc">
	                        <p>¿Está seguro que quiere eliminar la fecha <strong><?php echo $detalle['dia']; ?> a las <?php echo $detalle['hora']; ?>hs.</strong>?</p>
	                        <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
		                    <button class="btn btn-primary" type="submit">Eliminar</button>
	                    </div>
                    </form>
                </div>
            </div>