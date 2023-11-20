<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
            	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
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
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
                        <a href="<?php echo base_url('administracion/proyectos/ingresar/'); ?>" class="btn btn-primary btn-sm">Ingresar proyecto</a>
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
	                                        <th>#</th>
	                                        <th>Título</th>
	                                        <th>Empresa</th>
	                                        <th class="text-center">Responsable</th>
	                                        <th class="text-center">Fecha</th>
	                                        <th class="text-center">Estado</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                	<?php foreach ($proyectos as $proyecto) { ?>
		                                    <tr>
			                                    <td><?php echo $proyecto['numero_proyecto']; ?></td>
		                                        <td><a href="<?php echo base_url('administracion/proyectos/detalle/' . $proyecto['id']); ?>"><?php echo $proyecto['titulo']; ?></a></td>
		                                        <td><a href="<?php echo base_url('administracion/empresas/detalle/' . $proyecto['id_empresa']); ?>"><?php echo $proyecto['empresa']; ?></a></td>
		                                        <td class="text-center"><?php echo $proyecto['responsable']; ?></td>
		                                        <td class="text-center"><?php echo formatear_fecha($proyecto['fecha_alta'], 'd-m-Y', null, $this->usuario->timezone); ?></td>
		                                        <td class="text-center"><span class="label <?php echo $proyecto['estado_ui_class']; ?>"><?php echo $proyecto['estado']; ?></span></td>
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