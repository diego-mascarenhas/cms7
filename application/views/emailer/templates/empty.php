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
	                </ol>
	            </div>
            </div>

            <div class="wrapper wrapper-content">
                <div class="middle-box text-center animated fadeInRightBig">
                    <h3 class="font-bold">Sin plantillas</h3>
                    <div class="error-desc">
                        No se han creado plantillas hasta el momento,<br>
                        para crear uno presione el siguiente botón.
                        <br/><a href="<?php echo base_url('emailer/templates/ingresar'); ?>" class="btn btn-primary m-t">Crear nueva plantilla</a>
                    </div>
                </div>
            </div>