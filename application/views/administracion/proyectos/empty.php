<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
	                <h2>Administración</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li class="active">
	                        <strong>Proyectos</strong>
	                    </li>
	                </ol>
	            </div>
            </div>

            <div class="wrapper wrapper-content">
                <div class="middle-box text-center animated fadeInRightBig">
                    <h3 class="font-bold">Sin proyectos</h3>
                    <div class="error-desc">
                        No se han creado proyectos hasta el momento,<br>
                        para crear uno presione el siguiente botón.
                        <br/><a href="<?php echo base_url('administracion/proyectos/ingresar'); ?>" class="btn btn-primary m-t">Crear nuevo proyecto</a>
                    </div>
                </div>
            </div>