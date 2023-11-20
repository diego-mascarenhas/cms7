<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

	        <div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Administración</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li class="active">
	                        <strong>SMTPs</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
                        <a href="<?php echo base_url('emailer/smtps/ingresar/'); ?>" class="btn btn-primary btn-sm">Ingresar SMTP</a>
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
	                                        <th>IP</th>
	                                        <th class="text-center">Envíos</th>
	                                        <th class="text-center">Errores</th>
	                                        <th class="text-center">Mails en Cola</th>
	                                        <th class="text-center">Fecha</th>
	                                        <th class="text-center">Estado</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                	<?php foreach ($servidores as $servidor) { ?>
		                                    <tr>
			                                    <td><a href="<?php echo base_url('emailer/smtps/detalle/'); ?><?php echo $servidor['id']; ?>"><?php echo $servidor['host']; ?></a></td>
				                                <td class="text-center"><?php echo $servidor['envios']; ?></td>
				                                <td class="text-center"><?php echo $servidor['errores']; ?> 
				                                <?php if ($servidor['envios'] > 0) echo '(' . ROUND($servidor['errores']*100/$servidor['envios']) . '%)'; ?></td>
				                                <td class="text-center"><?php echo $servidor['mailq']; ?></td>
		                                        <td class="text-center">
				                                    <?php echo formatear_fecha($servidor['fecha'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?>
				                                </td>
		                                        <td class="text-center"><span class="label <?php echo $servidor['estado_ui_class']; ?>"><?php echo $servidor['estado']; ?></span></td>
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