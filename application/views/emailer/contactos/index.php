<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<!-- navbar main -->
			<div class="row wrapper border-bottom white-bg page-heading">
            	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Mailer</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
						</li>
						<li>
						<a href="<?php echo base_url('emailer'); ?>">Mailer Dashboard</a>
	                    </li>
	                    <li class="active">
	                        <strong>Contactos</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <!-- aca estaba el boton -->
                </div>
	        </div>
			
			<!-- cuerpo -->
			<div class="wrapper wrapper-content animated fadeInRight">
				
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox float-e-margins">
		                    <div class="ibox-title">
		                        <h5>Listado de Contactos</h5>
		                    </div>
	                        <div class="ibox-content">
	                            <div class="table-responsive">
		                            <?php echo form_open(null, array('class'=>'form-horizontal', 'method'=>'get')); ?>

									<table width="100%">
										<tr>
											<td>
												<div class="form-group">
													<label class="col-sm-4 control-label">Categoría de Servicios</label>
													<div class="col-sm-8">
														<?php echo form_dropdown('servicio_categoria', $categorias_generales, (isset($parametros['servicio_categoria'])) ? $parametros['servicio_categoria'] : null, 'class="form-control m-b"'); ?>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-4 control-label">Categoría de Contactos</label>
													<div class="col-sm-8">
														<?php echo form_dropdown('servicio_categoria', $categorias_generales, (isset($parametros['servicio_categoria'])) ? $parametros['servicio_categoria'] : null, 'class="form-control m-b"'); ?>
													</div>
												</div>
											</td>
											<td>
												<div class="form-group">
													<label class="col-sm-6 control-label">Estado Categoría</label>
														<div class="col-sm-6">
															<div class="radio radio-inline">
															<input type="radio" name="estado" value="2" <?php if (isset($detalle['estado']) && ($detalle['estado'] == 2 || $detalle['estado'] == 3)) echo 'checked="checked"'; ?>>
																<label> Activo </label>
															</div>
															<div class="radio radio-inline">
															<input type="radio" name="estado" value="1" <?php if (isset($detalle['estado']) && $detalle['estado'] == 1) echo 'checked="checked"'; ?>>
																<label> Inactivo </label>
															</div>
														</div>
												</div>
												<div class="form-group">
													<label class="col-sm-6 control-label">Estado Contacto</label>
														<div class="col-sm-6">
															<div class="radio radio-inline">
															<input type="radio" name="estado" value="2" <?php if (isset($detalle['estado']) && ($detalle['estado'] == 2 || $detalle['estado'] == 3)) echo 'checked="checked"'; ?>>
																<label> Activo </label>
															</div>
															<div class="radio radio-inline">
															<input type="radio" name="estado" value="1" <?php if (isset($detalle['estado']) && $detalle['estado'] == 1) echo 'checked="checked"'; ?>>
																<label> Inactivo </label>
															</div>
														</div>
												</div>

											</td>
											<td>
												<div>
													<button class="btn btn-primary" type="submit">Aplicar filtros</button>
												</div>
											
											</td>
										</tr>

									</table>

									
									<hr>

									<!-- tabla de contactos -->
	                                <table class="table table-striped table-bordered table-hover dataTables">
	                                    <thead>
											<tr>
												<th>Contacto</th>
												<?php if ($this->usuario->perfil == 'reseller'): ?><th>Empresa</th><?php endif; ?>
												<th>Email</th>
												<th>Teléfono</th>
												<th class="text-center">Estado</th>
											</tr>
	                                    </thead>
	                                    <tbody>
		                                    <?php if (isset($contactos)) { ?>
			                                	<?php foreach ($contactos as $contacto) { ?>
			                                    <tr>
			                                        <td>
														<a href="<?php echo base_url('emailer/contactos/detalle/'); ?><?php echo $contacto['id']; ?>"><?php echo $contacto['contacto']; ?></a>
													</td>
													<?php if ($this->usuario->perfil == 'reseller'): ?>
														<td>
															<a href="<?php echo base_url('administracion/empresas/detalle/'); ?><?php echo $contacto['id_empresa']; ?>"><?php echo $contacto['empresa']; ?></a>
														</td>
													<?php endif; ?>
			                                        <td>
														<a href="mailto:<?php echo $contacto['email']; ?>"><?php echo $contacto['email']; ?></a>
													</td>
			                                        <td>
														<?php if (isset($contacto['telefono'])) { ?>
			                    	                    <a href="<?php echo base_url('voip/llamar/'); ?><?php echo $contacto['id']; ?>"><?php echo $contacto['telefono']; ?></a>
														<?php } ?>
													</td>
					                                <td class="text-center">
														<span class="label <?php echo $contacto['estado_ui_class']; ?>"><?php echo $contacto['estado']; ?></span>
													</td>
			                                    </tr>
												<? } ?>
											<?php } else { ?>
												<tr>
			                                        <td colspan="6">No se encontraron registros</td>
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
	        
			
			
	        <!-- Datatables -->
	        <script src="<?php echo base_url('assets/js/plugins/dataTables/datatables.min.js'); ?>"></script>
	        
	        
	        <!-- Page-Level Scripts -->
		    <script>
		        $(document).ready(function(){
		            $('.dataTables').DataTable({
		                pageLength: 25,
		                responsive: true,
		                dom: '<"html5buttons"B>lTfgitp',
		                buttons: [
		                    {extend: 'copy'},
		                    {extend: 'csv'},
		                    {extend: 'excel', title: 'Contactos'},
		                    {extend: 'pdf', title: 'Contactos'}
		                ],
						language: {
				            "lengthMenu": "Mostrar _MENU_ contactos por página",
				            "zeroRecords": "No se encontraron contactos",
				            "info": "Mostrando página _PAGE_ de _PAGES_",
				            "infoEmpty": "No hay registros",
							"infoFiltered": "(filtrado de _MAX_ registros totales)",
							"search":"Buscar:",
							paginate: {
								previous: "Anterior",
								next: "Siguiente",
							},
				        }
		            });
		        });
		    </script>