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
				
				<?php if (isset($facturas)) { ?>
					<?php foreach ($facturas as $factura) { ?>
					<div class="row">
		            	<div class="col-lg-12">
			                <div class="ibox float-e-margins">
			                    <div class="ibox-title">
			                        <h5>
				                        <a name="<?php echo $factura['id']; ?>"></a>
			                        	<?php echo $factura['comprobante']; ?>
										<span class="badge <?php echo $factura['operacion_ui_class']; ?>"><?php echo $factura['operacion']; ?></span>
			                        </h5>
									<div class="ibox-tools">
										<a href="<?php echo base_url('administracion/facturas/detalle/' . $factura['id']); ?>" class="btn btn-outline btn-xs">
			                                <i class="fa fa-eye"> Ver factura</i>
			                            </a>
			                            <a class="collapse-link">
			                                <i class="fa fa-chevron-up"></i>
			                            </a>
			                            <a class="close-link">
			                                <i class="fa fa-times"></i>
			                            </a>
			                        </div>
			                    </div>
			                    
			                    <div class="ibox-content">
		                            <div class="table-responsive">
		                                <table class="table table-striped footable">
		                                    <tbody>
			                                    <tr>
				                                    <td><?php echo formatear_fecha($factura['fecha'], 'd-m-Y', null, $this->usuario->timezone); ?></td>
			                                        <td><?php echo $factura['forma_pago']; ?></td>
			                                        <td class="text-right">
				                                        <?php echo $factura['simbolo']; ?><?php echo $factura['total_neto']; ?>
			                                        </td>
			                                        <td class="text-center"><span class="label <?php echo $factura['estado_ui_class']; ?>"><?php echo $factura['estado']; ?></span></td>
			                                    </tr>
			                                    
			                                    <?php if (isset($factura['padre'])) { ?>
			                                    <tr>
				                                    <td>
					                                    <a href="#<?php echo $factura['padre']['id']; ?>"><?php echo $factura['padre']['comprobante']; ?></a>
				                                    	<?php echo formatear_fecha($factura['padre']['fecha'], 'd-m-Y', '<br><small>%s</small>', $this->usuario->timezone); ?>
				                                    	</td>
			                                        <td><?php echo $factura['padre']['forma_pago']; ?><br><span class="badge <?php echo $factura['padre']['operacion_ui_class']; ?>"><?php echo $factura['padre']['operacion']; ?></span></td>
			                                        <td class="text-right">
				                                        <?php echo $factura['padre']['simbolo']; ?><?php echo $factura['padre']['total_neto']; ?>
				                                        <br>
				                                        <small><?php echo $factura['padre']['simbolo']; ?><?php echo $factura['padre']['saldo']; ?></small>
			                                        </td>
			                                        <td class="text-center"><span class="label <?php echo $factura['estado_ui_class']; ?>"><?php echo $factura['estado']; ?></span></td>
			                                    </tr>
			                                    <?php } ?>
			                                    
			                                    <?php if (isset($factura['movimientos'])) { ?>
				                                	<?php foreach ($factura['movimientos'] as $movimiento) { ?>
				                                    <tr>
					                                    <td><?php echo formatear_fecha($movimiento['fecha'], 'd-m-Y', null, $this->usuario->timezone); ?></td>
				                                        <td><?php echo $movimiento['forma_pago']; ?></td>
				                                        <td class="text-right"><?php echo $movimiento['simbolo']; ?><?php echo $movimiento['valor']; ?></td>
				                                        <td class="text-center">
					                                        <?php if ($movimiento['id_estado'] == 1 && ($movimiento['id_forma_pago'] != 12 && $movimiento['id_forma_pago'] != 13)) { ?>
					                                        	<a href="#" onclick="conciliarPago(<?php echo $movimiento['id']; ?>)" class="check-link"><i class="fa fa-square-o"></i> </a>
					                                        <?php } ?>
					                                        <span class="label <?php echo $movimiento['estado_ui_class']; ?>"><?php echo $movimiento['estado']; ?></span>
					                                    </td>
				                                    </tr>
													<?php } ?>
												<?php } ?>
												
													<tr>
														<td>&nbsp;</td>
														<td>&nbsp;</td>
					                                    <td class="text-right"><?php echo $factura['simbolo']; ?><?php echo $factura['saldo']; ?></td>
					                                    <td>&nbsp;</td>
				                                    </tr>
		                                    </tbody>
		                                </table>
		                            </div>
		
		                        </div>
			                </div>
			            </div>
		            </div>
		            <?php } ?>
		        <?php } ?>
	            
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