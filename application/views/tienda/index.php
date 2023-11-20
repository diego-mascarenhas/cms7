			<div class="row wrapper border-bottom white-bg page-heading">
            	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Tienda</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <strong>Tienda</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                </div>
	        </div>
            
	        <div class="wrapper wrapper-content">
	            <div class="row">
	                <div class="col-lg-12">
		                <div class="ibox float-e-margins">
	                    	<div class="ibox-title"><h5>Listado de Tiendas</h5>
	                    	<a href="<?php echo base_url('tienda/fechas/'); ?>" class="btn btn-primary btn-sm pull-right">Ingresar tienda</a></div>
		                    <div class="ibox-content">
		                        <div class="table-responsive">
				                    <table class="table table-striped table-bordered table-hover dataTables-example" >
					                    <thead>
					                    <tr>
					                        <th>Nombre</th>
					                        <th>Empresa</th>
					                        <th>Email</th>
					                        <th>Rubro</th>
					                        <th>Plan</th>
					                        <th>Estado</th>
					                        <th>Acciones</th>
					                    </tr>
					                    </thead>
					                    <tbody>
						                    
						                <?php if (isset($listado)) { ?>
											<?php foreach($listado as $lista) { ?>	
						                   		<tr class="gradeX">
													<td><?php echo $lista['titulo']; ?></td>
													<td><?php echo '----'; ?></td>
													<td><?php echo '----'; ?></td>
													<td><?php echo $lista['rubro']; ?></td>
													<td><?php echo $lista['plan']; ?></td>
													<td><?php echo $lista['estado']; ?></td>
													<td>
							                        	<a href="<?php echo base_url('tienda/modificar_tienda/' . $lista['id']); ?>" title="Modificar" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i> Modificar</a> 
							                        	<a href="<?php echo base_url('tienda/duplicar_tienda/' . $lista['id']); ?>" title="Duplicar" class="btn btn-primary btn-sm"><i class="fa fa-copy"></i> Duplicar</a> 
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