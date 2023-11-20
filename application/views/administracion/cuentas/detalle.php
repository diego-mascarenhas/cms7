<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Administración</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('administracion/cuentas'); ?>">Cuentas</a>
	                    </li>
	                    <li class="active">
	                        <strong>Detalle</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
                        <a href="<?php echo base_url('administracion/cuentas/modificar/') . $detalle['id']; ?>" class="btn btn-primary btn-sm">Modificar cuenta</a>
                    </div>
                </div>
	        </div>