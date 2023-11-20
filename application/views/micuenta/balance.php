<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-lg-10">
	                <h2><?php echo $this->lang->line('cms_users-mi-cuenta'); ?></h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url('micuenta'); ?>"><?php echo $this->lang->line('cms_users-mi-cuenta'); ?></a>
	                    </li>
	                    <li class="active">
	                        <strong><?php echo $this->lang->line('cms_users-balance'); ?></strong>
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
	                                <table class="table table-striped footable">
	                                    <thead>
	                                    <tr>
		                                    <th><?php echo $this->lang->line('cms_users-fecha'); ?></th>
	                                        <th><?php echo $this->lang->line('cms_users-movimiento'); ?></th>
	                                        <th><?php echo $this->lang->line('cms_users-comprobante'); ?></th>
	                                        <th class="text-right"><?php echo $this->lang->line('cms_users-importe'); ?></th>
	                                        <th class="text-right"><?php echo $this->lang->line('cms_users-subtotal'); ?></th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                    <?php if (isset($balance)) { ?>
			                                	<?php foreach (array_reverse($balance) as $movimiento) { ?>
			                                    <tr>
				                                    <td><?php echo formatear_fecha($movimiento['fecha'], 'd-m-Y', null, $this->usuario->timezone); ?></td>
				                                    <td><span class="<?php echo $movimiento['operacion_ui_class']; ?>"><?php echo $movimiento['descripcion']; ?></span></td>
			                                        <td><a href="<?php echo base_url('micuenta/facturas/detalle/'); ?><?php echo $movimiento['id_factura']; ?>"> <?php echo $movimiento['comprobante']; ?></a></td>
			                                        <td class="text-right"><?php echo $movimiento['simbolo']; ?><?php echo $movimiento['valor']; ?></td>
			                                        <td class="text-right"><span class="<?php echo $movimiento['subtotal_ui_class']; ?>"><?php echo $movimiento['simbolo']; ?><?php echo $movimiento['subtotal']; ?></span></td>
			                                    </tr>
												<?php } ?>
											<?php } ?>
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