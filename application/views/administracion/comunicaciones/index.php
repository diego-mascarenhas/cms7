<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Administración</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li class="active">
	                        <strong>Comunicaciones</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
                        <a href="<?php echo base_url('administracion/comunicaciones/stats/'); ?>" class="btn btn-white btn-sm">Ver estadísticas</a>
                    </div>
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
	                                        <th>Destinatario</th>
	                                        <th>Asunto</th>
	                                        <th>Enviado</th>
	                                        <th>Recibido</th>
	                                        <th class="text-center">Estado</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                	<?php foreach ($lista as $item) { ?>
		                                    <tr>
		                                        <td>
			                                        <a href="<?php echo base_url('administracion/contactos/detalle/' . $item['id_contacto']); ?>"><?php echo $item['contacto']; ?></a><br>
													<small><?php echo $item['email']; ?></small>
			                                    </td>
		                                        <td><a href="<?php echo base_url('administracion/comunicaciones/detalle/' . $item['id']); ?>" target="_blank"><?php echo $item['asunto']; ?></a></td>
		                                        <td>
			                                        <?php echo formatear_fecha($item['enviado'], 'd-m-Y', null, $this->usuario->timezone); ?><br>
													<small><?php echo formatear_fecha($item['enviado'], 'H:i', ' Hs', $this->usuario->timezone); ?></small>
			                                    </td>
		                                        <td>
			                                        <?php echo formatear_fecha($item['recibido'], 'd-m-Y', null, $this->usuario->timezone); ?><br>
													<small><?php echo formatear_fecha($item['recibido'], 'H:i', ' Hs', $this->usuario->timezone); ?></small>
			                                    </td>
		                                        <td class="text-center"><span class="label <?php echo $item['estado_ui_class']; ?>"><?php echo $item['estado']; ?></span></td>
		                                    </tr>
											<? } ?>
	                                    </tbody>
	                                    <tfoot>
		                                    <tr>
			                                    <td colspan="5"><?php if (isset($paginado)) echo $paginado; ?></td>
		                                    </tr>
	                                    </tfoot>
	                                </table>
	                            </div>
	
	                        </div>
	                    </div>
	                </div>
	
	            </div>
	        </div>