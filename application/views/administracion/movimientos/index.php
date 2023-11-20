<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Administración</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li class="active">
	                        <strong>Movimientos</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
                        <a href="<?php echo base_url('administracion/movimientos/transferir/'); ?>" class="btn btn-primary btn-sm">Transferir</a>
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
		                                    <th>Fecha</th>
	                                        <th>Comprobante</th>
	                                        <th>Empresa</th>
	                                        <th>Forma de Pago</th>
	                                        <th class="text-right">Valor</th>
	                                        <th class="text-center">Estado</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                	<?php foreach ($movimientos as $movimiento) { ?>
		                                    <tr>
			                                    <td><?php echo formatear_fecha($movimiento['fecha'], 'd-m-Y', null, $this->usuario->timezone); ?></td>
			                                    <td><a href="<?php echo base_url('administracion/facturas/detalle/'); ?><?php echo $movimiento['id_factura']; ?>"><?php echo $movimiento['comprobante']; ?></a></td>
		                                        <td><a href="<?php echo base_url('administracion/empresas/detalle/'); ?><?php echo $movimiento['id_empresa']; ?>"><?php echo $movimiento['empresa']; ?></a></td>
		                                        <td>
			                                        <span class="badge <?php echo $movimiento['operacion_ui_class']; ?>"><?php echo $movimiento['operacion']; ?></span> <?php echo $movimiento['forma_pago']; ?> (<?php echo $movimiento['cuenta']; ?>)
		                                        </td>
		                                        <td class="text-right"><?php echo $movimiento['simbolo']; ?><?php echo $movimiento['valor']; ?></td>
		                                        <td class="text-center">
			                                        <?php if ($movimiento['id_estado'] == 1 && ($movimiento['id_forma_pago'] != 12 && $movimiento['id_forma_pago'] != 13)) { ?>
			                                        	<a href="#" onclick="conciliarPago(<?php echo $movimiento['id']; ?>)" class="check-link"><i class="fa fa-square-o"></i> </a>
			                                        <?php } ?>
			                                        <span class="label <?php echo $movimiento['estado_ui_class']; ?>"><?php echo $movimiento['estado']; ?></span>
			                                    </td>
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
	        
	        
	        <script>
	    function conciliarPago(id) { 
			$.ajax( {
			    type: 'POST',
			    url: 'movimientos/conciliar-pago/',
			    data: "id="+id,
			    success: function(data) {
			        //alert(data);
			    }
			});
		}
    </script>