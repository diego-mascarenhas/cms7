<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
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
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
                        <a href="<?php echo base_url('tareas/ingresar/'); ?>" class="btn btn-primary btn-sm">Ingresar tarea</a>
                    </div>
                </div>
	        </div>
			
			<div class="wrapper wrapper-content animated fadeInRight">
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox float-e-margins">
	                        <div class="ibox-content">
	                            <div class="table-responsive">
	                                <table class="table table-striped footable">
	                                    <thead>
	                                    <tr>
	                                        <th>Tarea</th>
	                                        <th>Contacto</th>
	                                        <th class="text-center">Desde</th>
	                                        <th class="text-center">Hasta</th>
	                                        <th class="text-center">Finalizada</th>
	                                        <th class="text-center">Estado</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                	<?php foreach ($tareas as $tarea) { ?>
		                                    <tr>
		                                        <td><a href="<?php echo base_url('tareas/detalle/' . $tarea['id']); ?>"><?php echo $tarea['titulo']; ?></a></td>
		                                        <td><a href="<?php echo base_url('administracion/contactos/detalle/'); ?><?php echo $tarea['id_contacto']; ?>"><?php echo $tarea['contacto']; ?></a></td>
		                                        <td class="text-center"><?php echo formatear_fecha($tarea['desde'], 'd-m-Y', null, $this->usuario->timezone); ?></td>
		                                        <td class="text-center"><?php echo formatear_fecha($tarea['hasta'], 'd-m-Y', null, $this->usuario->timezone); ?></td>
		                                        <td class="text-center"><?php echo formatear_fecha($tarea['final'], 'd-m-Y', null, $this->usuario->timezone); ?></td>
		                                        <td class="text-center"><span class="label label-<?php echo $tarea['estado_ui_class']; ?>"><?php echo $tarea['estado']; ?></span></td>
		                                    </tr>
											<? } ?>
	                                    </tbody>
	                                    <tfoot>
		                                    <tr>
			                                    <td colspan="6"><?php if (isset($paginado)) echo $paginado; ?></td>
		                                    </tr>
	                                    </tfoot>
	                                </table>
	                            </div>
	
	                        </div>
	                    </div>
	                </div>
	
	            </div>
	        </div>