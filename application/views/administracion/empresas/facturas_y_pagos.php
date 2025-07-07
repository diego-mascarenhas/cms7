<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Administración</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        Facturas y pagos
	                    </li>
	                    <li class="active">
	                        <a href="<?php echo base_url('administracion/empresas/detalle/' . $empresa['id']); ?>"><strong><?php echo $empresa['empresa']; ?></strong></a>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
                        <a href="<?php echo base_url('administracion/movimientos/ingresar?id_empresa=' . $empresa['id']); ?>" class="btn btn-primary btn-sm">Ingresar movimiento</a>
                    </div>
                </div>
	        </div>
			
			<div class="wrapper wrapper-content animated fadeInRight">
	            <div class="row">
	                <div class="col-lg-6">
	                    <div class="ibox float-e-margins">
		                    <div class="ibox-title">
		                        <h5>Facturas</h5>
		                    </div>
	                        <div class="ibox-content">
	                            <div class="table-responsive">
	                                <table class="table table-striped footable">
	                                    <thead>
	                                    <tr>
		                                    <th>Fecha</th>
	                                        <th>Comprobante</th>
	                                        <th>Forma de Pago</th>
	                                        <th class="text-right">Valor</th>
	                                        <th class="text-center">Estado</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                    <?php if (isset($facturas)) { ?>
			                                	<?php foreach ($facturas as $factura) { ?>
			                                    <tr>
				                                    <td><?php echo formatear_fecha($factura['fecha'], 'd-m-Y', null, $this->usuario->timezone); ?></td>
				                                    <td>
					                                    <a href="<?php echo base_url('administracion/facturas/detalle/'); ?><?php echo $factura['id']; ?>"><?php echo $factura['comprobante']; ?></a>
				                                    </td>
			                                        <td><span class="badge <?php echo $factura['operacion_ui_class']; ?>"><?php echo $factura['operacion']; ?></span> <?php echo $factura['forma_pago']; ?></td>
			                                        <td class="text-right">
				                                        <?php echo $factura['simbolo']; ?><?php echo $factura['total_neto']; ?>
				                                    </td>
			                                        <td class="text-center">
			                                        	<?php if ($factura['saldo'] > 0) { ?>
				                                        	<span class="label label-danger">Pendiente</span>
				                                        <?php } else { ?>
				                                        	<span class="label label-primary">Pagada</span>
				                                        <?php } ?>
			                                        </td>
			                                    </tr>
												<?php } ?>
											<? } else { ?>
												<tr>
													 <td colspan="5">Aún no se han registrado facturas.</td>
												</tr>
											<? } ?>
	                                    </tbody>
	                                </table>
	                            </div>
	
	                        </div>
	                    </div>
	                </div>
	                
	                <div class="col-lg-6">
	                    <div class="ibox float-e-margins">
		                    <div class="ibox-title">
		                        <h5>Pagos</h5>
		                    </div>
	                        <div class="ibox-content">
	                            <div class="table-responsive">
	                                <table class="table table-striped footable">
	                                    <thead>
	                                    <tr>
		                                    <th>Fecha</th>
	                                        <th>Comprobante</th>
	                                        <th>Forma de Pago</th>
	                                        <th class="text-right">Valor</th>
	                                        <th class="text-center">Estado</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                    <?php if (isset($movimientos)) { ?>
			                                	<?php foreach ($movimientos as $movimiento) { ?>
			                                    <tr>
				                                    <td><?php echo formatear_fecha($movimiento['fecha'], 'd-m-Y', null, $this->usuario->timezone); ?></td>
				                                    <td><a href="<?php echo base_url('administracion/facturas/detalle/'); ?><?php echo $movimiento['id_factura']; ?>"><?php echo $movimiento['comprobante_factura']; ?></a></td>
			                                        <td><span class="badge <?php echo $movimiento['operacion_ui_class']; ?>"><?php echo $movimiento['operacion']; ?></span> <?php echo $movimiento['forma_pago']; ?></td>
			                                                                                <td class="text-right"><?php echo $movimiento['simbolo']; ?><?php echo $movimiento['valor']; ?></td>
                                        <td class="text-center">
				                                        <?php if ($movimiento['id_estado'] == 1 && ($movimiento['id_forma_pago'] != 12 && $movimiento['id_forma_pago'] != 13)) { ?>
				                                        	<a href="#" onclick="conciliarPago(<?php echo $movimiento['id']; ?>)" class="check-link"><i class="fa fa-square-o"></i> </a>
				                                        <?php } ?>
				                                        <span class="label <?php echo $movimiento['estado_ui_class']; ?>"><?php echo $movimiento['estado']; ?></span>
				                                    </td>
			                                    </tr>
			                                    <? } ?>
											<? } else { ?>
												<tr>
													 <td colspan="5">Aún no se han registrado pagos.</td>
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
	        
	        <script>
	    function conciliarPago(id) { 
			// Cambiar el ícono a loading
			var link = $('a[onclick="conciliarPago(' + id + ')"]');
			var originalIcon = link.find('i');
			originalIcon.removeClass('fa-square-o').addClass('fa-spinner fa-spin');
			link.css('pointer-events', 'none'); // Disable click durante la operación
			
			$.ajax( {
			    type: 'POST',
			    url: '<?php echo base_url('administracion/movimientos/conciliar-pago/'); ?>',
			    data: "id="+id,
			    dataType: 'json',
			    success: function(data) {
			        if (data.success) {
			        	// Cambiar el ícono a check
			        	originalIcon.removeClass('fa-spinner fa-spin').addClass('fa-check-circle text-success');
			        	
			        	// Actualizar el estado visual
			        	var estadoSpan = link.next('span');
			        	estadoSpan.removeClass('label-warning').addClass('label-primary').text('Aprobado');
			        	
			        	// Mostrar mensaje de éxito
			        	toastr.success(data.message, 'Éxito');
			        	
			        	// Opcional: Recargar la página después de 2 segundos
			        	setTimeout(function() {
			        		window.location.reload();
			        	}, 2000);
			        	
			        } else {
			        	// Restaurar el ícono original
			        	originalIcon.removeClass('fa-spinner fa-spin').addClass('fa-square-o');
			        	link.css('pointer-events', 'auto');
			        	
			        	// Mostrar mensaje de error
			        	toastr.error(data.message, 'Error');
			        }
			    },
			    error: function(xhr, status, error) {
			        // Restaurar el ícono original
			        originalIcon.removeClass('fa-spinner fa-spin').addClass('fa-square-o');
			        link.css('pointer-events', 'auto');
			        
			        // Mostrar mensaje de error
			        toastr.error('Error al procesar la solicitud', 'Error');
			    }
			});
		}
    </script>