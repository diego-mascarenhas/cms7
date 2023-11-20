<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-lg-10">
	                <h2>Tareas</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <strong>Tareas</strong>
	                    </li>
	                </ol>
	            </div>
	        </div>

            <div class="wrapper wrapper-content">
                <div class="middle-box text-center animated fadeInRightBig">
                    <h3 class="font-bold">Sin tareas</h3>
                    <div class="error-desc">
                        No se han encontrado tareas hasta el momento,<br>
                        para crear una presione el siguiente botón.
                        <br/><a href="<?php echo base_url('tareas/ingresar'); ?>" class="btn btn-primary m-t">Crear nueva tarea</a>
                    </div>
                </div>
            </div>