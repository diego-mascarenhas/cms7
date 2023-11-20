<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Mailer</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('emailer/templates'); ?>">Plantillas</a>
	                    </li>
	                    <li class="active">
	                        <strong>Detalle</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
	                    <a class="btn btn-white btn-sm" target="_blank" href="<?php echo base_url('emailer/templates/ver/' . $detalle['id']); ?>"><i class="fa fa-eye"></i> Ver plantilla</a>
                        <a href="<?php echo base_url('emailer/templates/modificar/') . $detalle['id']; ?>" class="btn btn-primary btn-sm">Modificar plantilla</a>
                    </div>
                </div>
	        </div>
		        
		    <div class="wrapper wrapper-content animated fadeInRight">
				<div class="alert alert-info">
						<?php echo '<pre>' . print_r($detalle, true) . '</pre>'; ?>
				</div>
	        </div>