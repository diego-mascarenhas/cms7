<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-lg-10">
	                <h2>Facturas</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>"><?php echo $this->lang->line('variable_name'); ?>Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('micuenta'); ?>"><?php echo $this->lang->line('variable_name'); ?>Mi cuenta</a>
	                    </li>
	                    <li>
	                        <strong>Facturas</strong>
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
	                                        <th><?php echo $this->lang->line('variable_name'); ?>Comprobante</th>
	                                        <th><?php echo $this->lang->line('variable_name'); ?>Razón social</th>
	                                        <th><?php echo $this->lang->line('variable_name'); ?>Forma de Pago</th>
	                                        <th class="text-right"><?php echo $this->lang->line('variable_name'); ?>Valor</th>
	                                        <th class="text-center"><?php echo $this->lang->line('variable_name'); ?>Vencimiento</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                	<?php foreach ($facturas as $factura) { ?>
		                                    <tr>
			                                    <td>
				                                    <a href="<?php echo base_url('micuenta/facturas/detalle/'); ?><?php echo $factura['id']; ?>"><?php echo $factura['comprobante']; ?></a>
			                                    	<?php echo formatear_fecha($factura['fecha'], 'd-m-Y', '<br><small>%s</small>', $this->usuario->timezone); ?>
			                                    	</td>
		                                        <td><?php echo $factura['razon_social']; ?></td>
		                                        <td><?php echo $factura['forma_pago']; ?></td>
		                                        <td class="text-right">
			                                        <?php echo $factura['simbolo']; ?><?php echo $factura['total_neto']; ?>
			                                        <br>
			                                        <small><?php echo $factura['simbolo']; ?><?php echo $factura['saldo']; ?></small>
		                                        </td>
		                                        <td class="text-center">
			                                        <?php echo formatear_fecha($factura['vencimiento'], 'd-m-Y', null, $this->usuario->timezone); ?>
			                                        <?php echo formatear_fecha($factura['recibido'], 'd-m-Y', '<br><small><em>(Recibida: %s)</em></small>', $this->usuario->timezone, null); ?>
			                                    </td>
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