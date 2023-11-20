<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-lg-10">
	                <h2>Multimedia</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('multimedia'); ?>">Multimedia</a>
	                    </li>
	                    <li>
	                        <strong>Ingresar</strong>
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
					
	                <?php echo form_open(null, array('class'=>'form-horizontal')); ?>
	                    <h3 class="font-bold">Ingresar archivo</h3>
	                    <div class="error-desc">
	                        <p>El archivo <strong><?php echo $nombre; ?></strong> no existe.</p>
	                        
	                        <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Volver</a>
	                    </div>
                    </form>
                </div>
            </div>