<style>
#DataTables_Table_0_filter, #DataTables_Table_1_filter { float:right;margin-bottom:18px;}
#DataTables_Table_0_info {float:left; width:100%;}
#DataTables_Table_0_paginate { text-align:right; margin-top:25px;}
.btn_eliminar_popup { border:0; background:none;}
</style>
     <link href="<?php echo base_url('assets/css/plugins/dataTables/datatables.min.css'); ?>" rel="stylesheet" type="text/css">
           <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-8 col-sm-8 col-xs-8">
                    <h2>Clientes Empresas</h2>
                    <ol class="breadcrumb">
                        <li>
                        	<a href="/micuenta">Home</a>
                        </li>
                        <li>Usuarios</li>
                        <li class="active">
                            <strong>Empresas</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
                    <a href="<?php echo base_url('cms-v2/elearning/usuarios/ingresar/empresas'); ?>" class="btn btn-primary">Ingresar</a>
                </div>
            </div>
            
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">
					<?php if ($this->session->flashdata('mensaje')) { ?>
					<div class="alert <?php echo ($this->session->flashdata('resultado') == 1) ? 'alert-success' : 'alert-danger' ;?> alert-dismissable" role="alert">
						<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
						<?php echo $this->session->flashdata('mensaje'); ?>
					</div>
					<?php } ?>

                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Listado de Usuarios</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
		                    <table class="table table-striped table-bordered table-hover dataTables-example">
		                    <thead>
			                    <tr>
			                        <th>Empresa</th>
			                        <th>Nombre y Apellido</th>
			                        <th>Usuario</th>
			                        <th>Estado</th>
			                        <th>Acciones</th>
			                    </tr>
		                    </thead>
		                    <tbody>
		                   <?php 
		                   		if($listado) { foreach($listado as $lista) 
		                   		{ 
		                   			$this->session->set_flashdata('contacto', $lista['id']);
		                   	?>	
		                   	 <tr class="gradeX">
		                        <td><?php echo $lista['razon_social'] ;?></td>
		                        <td><?php echo ($lista['contacto']) ? $lista['contacto'] : '-----';?></td>
		                        <td><?php echo $lista['email'];?></td>
		                        <td><?php echo $lista['estado'];?></td>
		                        <td>
			                        <a href="<?php echo base_url('cms-v2/elearning/usuarios/modificar/empresas/').$lista['id']; ?>" title="Modificar" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i> Modificar</a> 
			                        <a href="<?php echo base_url('cms-v2/elearning/pedidos/subir/'.$lista['id']); ?>" title="Ingresar" class="btn btn-primary btn-sm"><i class="fa fa-shopping-cart"></i> Ingresar Pedido</a> 
									<a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion='<?php echo $lista['contacto'];?>' data-id="<?php echo $lista['id'];?>" data-estado='<?php echo $lista['id_estado'];?>' data-target="#myModal" class="sepV_a btn btn-primary btn-sm"><i class="fa fa-minus-circle"></i> Eliminar</a>
								</td>
		                    </tr>
		                   <?php } } else { ?>	
		                   	 <tr class="gradeX">
		                        <td colspan="5">No se encontraron usuarios</td>
		                   	 </tr>
		                   <?php } ?>	
	                    </tbody>
	                  </table>
                    </div>


                    <!-- Modal -->
                    <div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
	                        <div class="modal-content animated">
	                            <div class="modal-header">
	                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	                                <h4 class="modal-title">Eliminar usuario</h4>
	                            </div>
	                            <div class="modal-body">
	                               <p>&iquest;Est&aacute; seguro de querer eliminar el usuario <strong><input type="button" name="seccion" id="seccion" value="" class="btn_eliminar_popup"></strong>?</p>
		                            <div class="modal-footer">
			                            <form name="eliminar" method="post" action="<?php echo base_url('cms-v2/elearning/usuarios/eliminar/'); ?>">
			                            	<input type="hidden" name="tipo" value="empresas">
			                            	<input type="hidden" name="id" id="id" value="">
			                            	<input type="hidden" name="estado" id="estado" value="">
			                                <input type="submit" class="btn btn-primary" value="Eliminar">
			                            </form>
		                            </div>
	                            </div>
	                        </div>
                        </div>
                    </div>
                    <!-- Fin Modal -->
                </div>
            </div>
           </div>
        </div>

<!-- Tablas -->
<script src="<?php echo base_url('assets/js/plugins/dataTables/datatables.min.js'); ?>"></script>
<script>
$(document).ready(function(){
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
        pageLength: 25,
        responsive: true,
	            dom: '<"html5buttons"B>lTfgitp',
	        buttons: [
	        	{extend: 'csv', title: 'Listado de Usuarios CSV'},
	            {extend: 'excel', title: 'Listado de Usuarios EXCEL'},
	            {extend: 'pdf', title: 'Listado de Usuarios PDF'}
	    ]
    });
});

  $('#myModal').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var seccion = $(e.relatedTarget).data().seccion;
     var estado = $(e.relatedTarget).data().estado;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#estado').val(estado);
      $(e.currentTarget).find('#seccion').val(seccion);
  });
</script>


