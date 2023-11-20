<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Sitio web</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li class="active">
	                        <strong>Sitio web</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
	                    <?php if (isset($categorias)) { ?>
	                    <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle">Ingresar <span class="caret"></span></button>
                            <ul class="dropdown-menu" style="left: auto !important; right:1px;">
	                            <?php foreach ($categorias as $item) { ?>
                                <li><a href="<?php echo base_url('cms/ingresar?categoria=' . $item['id']); ?>"><?php echo $item['categoria']; ?></a></li>
                                <?php } ?>
                            </ul>
                        </div>
	                    <?php } ?>
                    </div>
                </div>
	        </div>				
			
			<div class="wrapper wrapper-content animated fadeInRight">
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox float-e-margins">
	                        <div class="ibox-content">
	                            <div class="table-responsive">
		                            <?php echo form_open(null, array('class'=>'form-horizontal', 'method'=>'get')); ?>
			                            <div class="form-group">
			                            	<label class="col-sm-2 control-label">Categoría</label>
											<div class="col-sm-4">
												<?php echo form_dropdown('categoria', $combo_categorias, (isset($parametros['categoria'])) ? $parametros['categoria'] : null, 'class="form-control m-b"'); ?>
											</div>
			                                <div class="col-sm-6">
			                                    <button class="btn btn-sm btn-primary" type="submit">Aplicar filtros</button>
			                                </div>
			                            </div>
		                            </form>
	                                <table class="table table-striped table-bordered table-hover dataTables">
	                                    <thead>
	                                    <tr>
	                                        <th>Título</th>
	                                        <th>Autor</th>
	                                        <th class="text-center">Fecha de alta</th>
	                                        <th class="text-center">Estado</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                	<?php foreach ($contenidos as $contenido) { ?>
		                                    <tr>
			                                    <td>
				                                    <a href="<?php echo base_url('cms/modificar/'); ?><?php echo $contenido['id']; ?>"><?php echo $contenido['titulo']; ?></a>
			                                    	<br>
				                                    <small><?php echo $contenido['categoria']; ?></small>
				                                </td>
			                                    <td><?php echo $contenido['contacto']; ?></td>
			                                    <td class="text-center"><?php echo formatear_fecha($contenido['fecha_alta'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></td>
			                                    <td class="text-center"><span class="label <?php echo $contenido['estado_ui_class']; ?>"><?php echo $contenido['estado']; ?></span></td>
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
		                pageLength: 10,
		                responsive: true,
		                dom: '<"html5buttons"B>lTfgitp',
		                buttons: [
		                    {extend: 'csv', title: 'CMS'},
		                    {extend: 'pdf', title: 'CMS'}
		                ],
		                fnDrawCallback: function (oSettings){
						    if(oSettings.fnRecordsTotal() < 10){     
						        $('.dataTables_length').hide();
						        $('.dataTables_paginate').hide();
						    } else {
						        $('.dataTables_length').show();
						        $('.dataTables_paginate').show(); 
						    }
						},
						language: {
				            "lengthMenu": "Mostrar _MENU_ registros por página",
				            "zeroRecords": "No se encontraron registros",
				            "info": "Mostrando página _PAGE_ de _PAGES_",
				            "infoEmpty": "No hay registros",
				            "infoFiltered": "(filtrado de _MAX_ registros totales)"
				        }
		            
		            });
		        });
		    </script>