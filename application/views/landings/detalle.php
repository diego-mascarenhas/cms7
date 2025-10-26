<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
#DataTables_Table_0_filter, #DataTables_Table_1_filter { float:right;margin-bottom:18px;}
#DataTables_Table_0_info {float:left; width:100%;}
#DataTables_Table_0_paginate { text-align:center;}
</style>
    <link href="<?php echo base_url('assets/css/plugins/codemirror/codemirror.css'); ?>" rel="stylesheet" type="text/css">
			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Landings</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('landings'); ?>">Landings</a>
	                    </li>
	                    <li class="active">
	                        <strong>Detalle</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
		            <div class="title-action">
			            <a href="<?php echo base_url('landings/modificar/' . $detalle['id']); ?>" class="btn btn-primary btn-sm">Modificar landing</a>
                    </div>
                </div>
                <div class="col-xs-12">
	                <?php if (isset($notas)) { ?>
				        <ul class="notes">
	                        <?php foreach ($notas as $nota) { ?>
	                        <li>
	                            <div>
	                                <small><?php echo $nota['contacto']; ?>  <?php echo formatear_fecha($nota['fecha_alta'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></small>
	                                <h4><?php echo $nota['titulo']; ?></h4>
	                                <p><?php echo ellipsize($nota['descripcion'], 100); ?></p>
	                                <a href="<?php echo base_url('notas/modificar/' . $nota['id']); ?>"><i class="fa fa-edit"></i></a>
	                            </div>
	                        </li>
	                        <?php } ?>
	                    </ul>
	                <?php } ?>
                </div>
	        </div>
	        
	        <div class="wrapper wrapper-content animated fadeInRight">		            
	            
	            <div class="ibox-content m-b-sm border-bottom">
					<div class="row">
	                    <div class="col-sm-12">
	                        <div class="form-group">
	                            <label class="control-label">Titulo</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['titulo']; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-12">
	                        <div class="form-group">
	                            <label class="control-label">Fecha</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo formatear_fecha($detalle['fecha_alta'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            
	            <?php if($detalle['codigo']) { ?>
	            <div class="ibox-content m-b-sm border-bottom">
	                <div class="row">
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Codigo</label>
	                            <div class="bg-muted p-xs b-r-sm"> <textarea id="code1"><?php echo htmlspecialchars($detalle['codigo']); ?></textarea></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Codigo Thank You Page</label>
	                            <div class="bg-muted p-xs b-r-sm"> <textarea id="code2"><?php echo htmlspecialchars($detalle['codigo_gracias']); ?></textarea></div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <?php } ?>
	            
	            <div class="row">
	                <div class="col-lg-12">
		                <div class="ibox float-e-margins">
	                    	<div class="ibox-title"><h5>Contactos</h5></div>
		                    <div class="ibox-content">
		                        <div class="table-responsive">
		                            <?php if (isset($contactos)) { ?>
				                    <table class="table table-striped table-bordered table-hover dataTablesLeeds" >
					                    <thead>
					                    <tr>
											<th width="170">Fecha</th>
					                        <?php 
						                       // echo '<pre>' . print_r($contactos, true) . '</pre>';
						                        $headers = json_decode($contactos[0]['data'],true);
						                        foreach(array_keys($headers) as $key)
						                        {
												    echo '<th>'.$key.'</th>';
												}
											?>
											
<!--
											<th width="170">Fecha</th>
					                        <th>Nombre y Apellido</th>
					                        <th>Email</th>
					                        <th>Tel&eacute;fono</th>
											<?php if ($detalle['id'] == 11) { ?>
					                        <th>Empresa</th>
					                        <th>Pa&iacute;s de Inter&eacute;s</th>
					                        <th>Casa Matriz</th>
					                        <?php } else { ?>
					                        <th>Consulta</th>
					                        <th>Tipo</th>
											<?php } ?>
-->
					                    </tr>
					                    </thead>
					                    <tbody>
						                    
			                                <?php foreach ($contactos as $contacto) { 
				                                  //echo '<pre>' . print_r($contacto, true) . '</pre>';		?>                                		  
			                                		  
						                   		<tr class="gradeX">
													<?php 
														$datos = json_decode($contacto['data'],true); 
														
														if(count($datos) == count($headers))
														{
															echo '<td>'.$contacto['fecha'].'</td>';
															foreach(array_keys($headers) as $tester)
															{
																if(array_key_exists($tester, $datos))
																{
																	echo '<td>';
																	if($tester == 'provincia') 
																	{ 
																		foreach($provincias as $provincia) { if($provincia['id'] == $datos['provincia']) { echo $provincia['descripcion']; } } 
																	}
																	else { echo $datos[$tester]; }
																	echo '</td>';
																}
															}
														}
														
													?>
													
<!--
													<?php if ($detalle['id'] == 11) { ?>
													<td><?php echo $datos['nombre']; ?></td>
													<td><?php echo $datos['email']; ?></td>
													<td><?php echo $datos['telefono']; ?></td>
													<td><?php echo $datos['empresa']; ?></td>
													<td><?php echo $datos['oficina']; ?></td>
													<td><?php echo $datos['pais']; ?></td>
													<?php } else { ?>
													<td><?php echo (!empty($datos['nombre'])) ? $datos['nombre'] : 's/d'; echo (!empty($datos['apellido'])) ? $datos['apellido'] : null; ?></td>
													<td><?php echo (!empty($datos['email'])) ? $datos['email'] : 's/d'; ?></td>
													<td><?php echo (!empty($datos['telefono'])) ? $datos['telefono'] : 's/d'; ?></td>
													<td><?php echo (!empty($datos['consulta'])) ? $datos['consulta'] : 's/d'; ?></td>
													<td><?php echo (!empty($datos['tipo'])) ? $datos['tipo'] : 's/d'; ?></td>
													<?php } ?>
-->
						                    	</tr>
											<?php } ?>	
					                    </tbody>
				                    </table>
									<? } else { ?>
										<p>No se encontraron conversiones</p>
	                            <?php } ?>
		                        </div>
							</div>
	                	</div>
	                </div>
	            </div>
	            
	        </div>
	        
		    <!-- CodeMirror -->
			<script src="<?php echo base_url('assets/js/plugins/codemirror/codemirror.js'); ?>"></script>
			<script src="<?php echo base_url('assets/js/plugins/codemirror/mode/javascript/javascript.js'); ?>"></script>
		
			<script src="<?php echo base_url('assets/js/plugins/dataTables/datatables.min.js'); ?>"></script>
		    <script>
		         $(document).ready(function(){
				    $('.dataTablesLeeds').DataTable({
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
				        "order": [[ 0, "desc" ]],
				        pageLength: 25,
				        responsive: true,
   		                dom: '<"html5buttons"B>lTfgitp',
		                buttons: [
	                    	{extend: 'csv', title: 'Listado de Contactos CSV'},
		                    {extend: 'excel', title: 'Listado de Contactos EXCEL'},
		                    {extend: 'pdf', title: 'Listado de Contactos PDF'}
	                ]
	            });
			});
					             var editor_one = CodeMirror.fromTextArea(document.getElementById("code1"), {
		                 lineNumbers: true,
		                 matchBrackets: true,
		                 styleActiveLine: true
		             });
		             var editor_one = CodeMirror.fromTextArea(document.getElementById("code2"), {
		                 lineNumbers: true,
		                 matchBrackets: true,
		                 styleActiveLine: true
		             });

		    </script>	       