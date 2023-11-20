<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
            	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Log</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <strong>Log</strong>
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
	                                        <th class="text-center">TIPO</th>
	                                        <th class="text-center">Log</th>
	                                        <th class="text-center">Fecha</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                	<?php foreach ($lista as $item) { ?>
		                                    <tr>
		                                        <td class="text-center"><?php echo $item['tipo']; ?></td>
		                                        <td><?php echo $item['log']; ?></td>
		                                        <td class="text-center"><?php echo formatear_fecha($item['fecha'], 'd-m-Y', null, $this->usuario->timezone); ?></td>
		                                    </tr>
											<? } ?>
	                                    </tbody>
	                                    <tfoot>
		                                    <tr>
			                                    <td colspan="3"><?php if (isset($paginado)) echo $paginado; ?></td>
		                                    </tr>
	                                    </tfoot>
	                                </table>
	                            </div>
	
	                        </div>
	                    </div>
	                </div>
	
	            </div>
	        </div>