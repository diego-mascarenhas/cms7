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
                        <button onclick="aprobarTodosPendientes()" class="btn btn-success btn-sm" id="btnAprobarTodos" style="display: none;">
                            <i class="fa fa-check-circle"></i> Aprobar Todos los Pendientes
                        </button>
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
			// Cambiar el ícono a loading
			var link = $('a[onclick="conciliarPago(' + id + ')"]');
			var originalIcon = link.find('i');
			originalIcon.removeClass('fa-square-o').addClass('fa-spinner fa-spin');
			link.css('pointer-events', 'none'); // Disable click durante la operación
			
			$.ajax( {
			    type: 'POST',
			    url: 'movimientos/conciliar-pago/',
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
			        	
			        	// Verificar si quedan pagos pendientes
			        	verificarPagosPendientes();
			        	
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
		
		function aprobarTodosPendientes() {
			if (!confirm('¿Está seguro de que desea aprobar todos los pagos pendientes? Esta acción no se puede deshacer.')) {
				return;
			}
			
			var pagosPendientes = [];
			$('a.check-link').each(function() {
				var onclick = $(this).attr('onclick');
				var id = onclick.match(/\d+/)[0];
				pagosPendientes.push(id);
			});
			
			if (pagosPendientes.length === 0) {
				toastr.info('No hay pagos pendientes para aprobar', 'Información');
				return;
			}
			
			// Disable el botón durante el proceso
			$('#btnAprobarTodos').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Procesando...');
			
			var procesados = 0;
			var exitosos = 0;
			
			pagosPendientes.forEach(function(id) {
				$.ajax({
					type: 'POST',
					url: 'movimientos/conciliar-pago/',
					data: "id=" + id,
					dataType: 'json',
					success: function(data) {
						procesados++;
						if (data.success) {
							exitosos++;
						}
						
						// Si se procesaron todos
						if (procesados === pagosPendientes.length) {
							$('#btnAprobarTodos').prop('disabled', false).html('<i class="fa fa-check-circle"></i> Aprobar Todos los Pendientes');
							
							if (exitosos > 0) {
								toastr.success('Se aprobaron ' + exitosos + ' de ' + pagosPendientes.length + ' pagos', 'Proceso Completado');
								setTimeout(function() {
									window.location.reload();
								}, 1500);
							} else {
								toastr.error('No se pudo aprobar ningún pago', 'Error');
							}
						}
					},
					error: function() {
						procesados++;
						
						// Si se procesaron todos
						if (procesados === pagosPendientes.length) {
							$('#btnAprobarTodos').prop('disabled', false).html('<i class="fa fa-check-circle"></i> Aprobar Todos los Pendientes');
							toastr.success('Se aprobaron ' + exitosos + ' de ' + pagosPendientes.length + ' pagos', 'Proceso Completado');
							
							if (exitosos > 0) {
								setTimeout(function() {
									window.location.reload();
								}, 1500);
							}
						}
					}
				});
			});
		}
		
		function verificarPagosPendientes() {
			var pagosPendientes = $('a.check-link').length;
			if (pagosPendientes > 1) {
				$('#btnAprobarTodos').show();
			} else {
				$('#btnAprobarTodos').hide();
			}
		}
		
		// Verificar al cargar la página si hay pagos pendientes
		$(document).ready(function() {
			verificarPagosPendientes();
		});
    </script>