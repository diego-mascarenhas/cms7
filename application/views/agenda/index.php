<style>
#DataTables_Table_0_filter, #DataTables_Table_1_filter { float:right;margin-bottom:18px;}
#DataTables_Table_0_info {float:left; width:100%;}
#DataTables_Table_0_paginate { text-align:center;}
</style>
			<div class="row wrapper border-bottom white-bg page-heading">
            	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Agenda</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <strong>Agenda</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
<!--
                    <div class="title-action">
                        <a href="<?php echo base_url('agenda/ingresar/fechas'); ?>" class="btn btn-primary btn-sm">Ingresar reunión</a>
                    </div>
-->
                </div>
	        </div>
            
	        <div class="wrapper wrapper-content">
	            <div class="row">
	                <div class="col-lg-12">
		                <div class="ibox float-e-margins">
	                    	<div class="ibox-title"><h5>Listado de Fechas</h5>
	                    	<a href="<?php echo base_url('agenda/fechas/'); ?>" class="btn btn-primary btn-sm pull-right">Ingresar fecha</a></div>
		                    <div class="ibox-content">
		                        <div class="table-responsive">
				                    <table class="table table-striped table-bordered table-hover dataTables-example" >
					                    <thead>
					                    <tr>
					                        <th>Día y hora</th>
					                        <th>País</th>
					                        <th>Estado</th>
					                        <th>Acciones</th>
					                    </tr>
					                    </thead>
					                    <tbody>
						                    
						                <?php if (isset($fechas)) { ?>
											<?php foreach($fechas as $fecha) { ?>	
						                   		<tr class="gradeX">
													<td><?php echo $fecha['dia'].' - '.$fecha['hora']; ?></td>
													<td><?php echo $fecha['pais']; ?></td>
													<td><?php echo ($fecha['id_estado'] == 3) ? 'Disponible' : 'Bloqueada'; ?></td>
													<td>
							                        	<a href="<?php echo base_url('agenda/modificar_fecha/' . $fecha['id']); ?>" title="Modificar" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i> Modificar</a> 
							                        	<a href="<?php echo base_url('agenda/duplicar_fecha/' . $fecha['id']); ?>" title="Duplicar" class="btn btn-primary btn-sm"><i class="fa fa-copy"></i> Duplicar</a> 
														<?php if ($fecha['id_estado'] == 3) { ?><a href="<?php echo base_url('agenda/eliminar_fecha/' . $fecha['id']); ?>" title="Eliminar" class="btn btn-primary btn-sm"><i class="fa fa-minus-circle"></i> Eliminar</a><?php } ?>
													</td>
						                    	</tr>
											<?php } ?>	
										<?php } ?>
					                    </tbody>
				                    </table>
		                        </div>
							</div>
	
	                	</div>
	            	</div>
	            </div>
	        </div>


	        <div class="wrapper wrapper-content">
	            <div class="row">
	                <div class="col-lg-12">
		                <div class="ibox float-e-margins">
	                    	<div class="ibox-title"><h5>Listado de Reuniones</h5>
	                    	<a href="<?php echo base_url('agenda/ingresar/'); ?>" class="btn btn-primary btn-sm pull-right">Ingresar reunión</a></div>
		                    <div class="ibox-content">
		                        <div class="table-responsive">
				                    <table class="table table-striped table-bordered table-hover dataTables-reuniones">
					                    <thead>
					                    <tr>
					                        <th>Nombre</th>
					                        <th>Empresa</th>
					                        <th>Email</th>
					                        <th>Teléfono</th>
					                        <th>Casa matriz</th>
					                        <th>País de Interés</th>
					                        <th>Reunión</th>
					                        <th>Estado</th>
					                        <th>Acciones</th>
					                    </tr>
					                    </thead>
					                    <tbody>
						                    
						                <?php if (isset($listado)) { ?>
											<?php foreach($listado as $lista) { ?>	
						                   		<tr class="gradeX">
													<td><?php echo $lista['nombre']; ?></td>
													<td><?php echo $lista['empresa']; ?></td>
													<td><?php echo $lista['email']; ?></td>
													<td><?php echo $lista['telefono']; ?></td>
													<td><?php echo $lista['pais']; ?></td>
													<td><?php echo $lista['oficina']; ?></td>
													<td><?php echo $lista['dia'].' - '.$lista['hora']; ?></td>
													<td><?php echo $lista['estado']; ?></td>
													<td>
							                        	<a href="<?php echo base_url('agenda/modificar/' . $lista['id']); ?>" title="Modificar" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i> Modificar</a> 
														<a href="<?php echo base_url('agenda/detalle/' . $lista['id']); ?>" title="Detalle" class="btn btn-primary btn-sm"><i class="fa fa-bookmark"></i> Detalle</a> 
													</td>
						                    	</tr>
											<?php } ?>	
										<?php } ?>
					                    </tbody>
				                    </table>
		                        </div>
							</div>
	
	                	</div>
	            	</div>
	            </div>
	        </div>
	
	
    			<!-- Tablas -->
			<script src="<?php echo base_url('assets/js/plugins/dataTables/datatables.min.js'); ?>"></script>
			<script>
			$(document).ready(function(){
			    $('.dataTables-reuniones').DataTable({
				    "language": {
			            "lengthMenu": "Mostrar _MENU_ resultados por p&aacute;gina",
			            "zeroRecords": "No se encontraron resultados",
			            "infoEmpty": "No se encontraron resultados",
			            "infoFiltered": "(filtered from _MAX_ total records)",
			            "search": "Buscar:",
			            "emptyTable": "No se encontraron resultados",
			            "info": "Mostrando _START_ to _END_ de _TOTAL_ resultados",
			            "infoEmpty": "Mostrando 0 to 0 of 0 resultados",
			            "infoFiltered":   "(filtrados de _MAX_ total de resultados)",
					    "loadingRecords": "Cargando...",
					    "processing": "Procesando...",
					    "paginate": {
					        "first":      "Primera",
					        "last":       "&Uacute;ltima",
					        "next":       "Siguiente",
					        "previous":   "Anterior"
					    },
					    "aria": {
					        "sortAscending":  ": ordenar ascendente",
					        "sortDescending": ": ordenar descendente"
					    }
			        },
			        pageLength: 10,
			        responsive: true,
	                dom: '<"html5buttons"B>lTfgitp',
	                buttons: [
                    	{extend: 'csv', title: 'Agenda CSV'},
	                    {extend: 'pdf', title: 'Agenda PDF'},
	                    {extend: 'excel', title: 'Agenda EXCEL'}
                ]
            });

			    $('.dataTables-example').DataTable({
				    "language": {
			            "lengthMenu": "Mostrar _MENU_ resultados por p&aacute;gina",
			            "zeroRecords": "No se encontraron resultados",
			            "infoEmpty": "No se encontraron resultados",
			            "infoFiltered": "(filtered from _MAX_ total records)",
			            "search": "Buscar:",
			            "emptyTable": "No se encontraron resultados",
			            "info": "Mostrando _START_ to _END_ de _TOTAL_ resultados",
			            "infoEmpty": "Mostrando 0 to 0 of 0 resultados",
			            "infoFiltered":   "(filtrados de _MAX_ total de resultados)",
					    "loadingRecords": "Cargando...",
					    "processing": "Procesando...",
					    "paginate": {
					        "first":      "Primera",
					        "last":       "&Uacute;ltima",
					        "next":       "Siguiente",
					        "previous":   "Anterior"
					    },
					    "aria": {
					        "sortAscending":  ": ordenar ascendente",
					        "sortDescending": ": ordenar descendente"
					    }
			        },
			        pageLength: 10,
			        responsive: true
			    });
			});
			</script>