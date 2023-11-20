<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-10 col-lg-10">
	                <h2>Multimedia</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('multimedia/'); ?>">Multimedia</a>
	                    </li>
	                    <li class="active">
	                        <strong>Reporte</strong>
	                    </li>
	                </ol>
	            </div>
	        </div>
			
			<div class="wrapper wrapper-content animated fadeInRight">
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox float-e-margins">
	                        <div class="ibox-content">
	                            <div class="table-responsive">
	                                <table class="table table-striped">
	                                    <thead>
	                                    <tr>
	                                        <th>ID</th>
	                                        <th>Archivo</th>
	                                        <th class="text-center">Peso</th>
	                                        <th class="text-center">fecha</th>
	                                        <th class="text-center">Estado</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                	<?php foreach ($reporte as $item) { ?>
		                                    <tr>
			                                    <td>
				                                    <?php if (isset($item['id'])) { ?>
				                                    	<?php echo $item['id']; ?>
				                                    <?php } ?>
			                                    </td>
		                                        <td>
			                                        <?php if (isset($item['id'])) { ?>
			                                        	<a href="<?php echo base_url('multimedia/detalle/' . $item['id']); ?>"><?php echo $item['nombre']; ?></a>
			                                        <?php } else { ?>
			                                        	<?php echo $item['name']; ?>
			                                        <?php } ?>
			                                        <br>
			                                        <small><?php echo $item['server_path']; ?></small>
			                                    </td>
		                                        <td class="text-center">
			                                        <?php echo byte_format($item['size']*1024); ?>
			                                        <?php if ($item['estado'] == 2) { ?>
			                                        <br>
													<small>(Base de datos: <?php echo byte_format($item['size_db']*1024); ?>)</small>
													<?php } ?>
			                                    </td>
		                                        <td class="text-center">
			                                        <?php echo formatear_fecha($item['fecha_alta'], 'd-m-Y', ' Hs', $this->usuario->timezone); ?>
			                                        <br>
			                                        <small><?php echo formatear_fecha($item['fecha_alta'], 'H:i', ' Hs', $this->usuario->timezone); ?></small>
			                                    </td>
				                                <td class="text-center">
					                                <?php if ($item['estado'] == 2) { ?>
			                                        	<span class="label label-info">Se ajustó el peso</span>
			                                        <?php } elseif ($item['estado'] == 3) { ?>
			                                        	<a href="<?php echo base_url('multimedia/eliminar-archivo/?archivo=' . $item['server_path']); ?>"><span class="label label-danger">No se encuentra el registro</span></a>
			                                        <?php } elseif ($item['estado'] == 4) { ?>
			                                        <a href="<?php echo base_url('multimedia/eliminar-archivo/?archivo=' . $item['server_path']); ?>"><span class="label label-warning">Archivo para eliminar</span></a>
			                                        <?php } else { ?>
				                                        <span class="label label-primary">ok</span>
				                                    <?php } ?>
					                            </td>
		                                    </tr>
											<? } ?>
	                                    </tbody>
	                                </table>
	                            </div>
	
	                        </div>
	                    </div>
	                </div>
	
	            </div>
	        </div>