<style>
.table-responsive { padding-bottom:200px;}
@media screen and (max-width: 767px) { 
.table-responsive { border: 1px solid #f7f7f7; }
}
</style>
<link href="<?php echo base_url('assets/css/tienda.css'); ?>" rel="stylesheet" type="text/css">
		<div class="row wrapper border-bottom white-bg page-heading">
        	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
                <h2>Tienda</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('tienda/dashboard'); ?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('tienda/tienda/mi-tienda/'); ?>">Tienda</a>
                    </li>
                    <li>
                        <strong>Clientes registrados</strong>
                    </li>
                </ol>
            </div>
            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                <div class="title-action">
                    <a href="<?php echo base_url('tienda/clientes/ingresar'); ?>" class="btn btn-primary btn-sm">Ingresar Cliente</a>
                </div>
            </div>
        </div>
            
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">
	                <div class="ibox float-e-margins">
                    	<div class="ibox-title"><h5>Listado de Clientes registrados</h5></div>
	                    <div class="ibox-content">
	                        <div class="table-responsive tabla-pedidos">
			                    <table class="table table-striped table-bordered dataTables-listado">
			                    <thead>
			                    <tr>
			                        <th>Cliente Nro.</th>
			                        <th>Nombre y apellido</th>
			                        <th>Razón Social</th>
			                        <th>Teléfono</th>
			                        <th>Email</th>
			                        <th>Estado</th>
			                        <th>Acciones</th>
			                    </tr>
			                    </thead>
			                    <tbody>
			                   <?php 
			                   		if($listado) 
			                   		{ 
			                   			foreach($listado as $lista) 
			                   			{ 
											$CI =& get_instance();
											$CI->load->model("tienda_model");
											$adicionales = $this->tienda_model->getContactoAdicionales($lista['id']);
			                   	?>	
			                   	 <tr class="gradeX">
			                        <td><?php echo $adicionales['numero_cliente'];?></td>
			                        <td><?php echo $lista['contacto'];?></td>
			                        <td><?php echo $adicionales['razon_social'];?></td>
			                        <td><?php echo $lista['telefono'];?></td>
			                        <td><?php echo $lista['email'];?></td>
			                        <td><?php echo $lista['estado'];?></td>
			                        <td>
										<div class="dropdown">
										  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Acciones <span class="caret"></span>
										  </button>
										  <ul class="dropdown-menu dropdown-clientes" aria-labelledby="dropdownMenu1">
										    <li>
										    	<a href="<?php echo base_url('tienda/clientes/modificar/'.$lista['id']);?>" title="Chatear por consulta" target="_blank"><i class="fa fa-edit"></i> Modificar</a></li>
<!--
										    <li>
											    <a title="Eliminar" id="item" href="#" data-toggle="modal" data-seccion='<?php echo $lista['contacto'];?>' data-id="<?php echo $lista['id'];?>" data-target="#myModal" class="sepV_a btn btn-sm"><i class="fa fa-minus-circle"></i> Eliminar</a>
										    </li>
-->
										    <li>
										    	<a href="https://wa.me/<?php echo $lista['telefono'];?>?text=Hola&nbsp;te&nbsp;consulto&nbsp;por" title="Chatear por consulta" target="_blank"><i class="fa fa-whatsapp"></i> Chatear por consulta</a></li>
										    <li> 
										    <a href="mailto:<?php echo $lista['email'];?>?subject=Hola&nbsp;te&nbsp;consulto&nbsp;por" title="Contactar por mail" target="_blank"><i class="fa fa-envelope"></i> Contactar por mail</a></li>
										  </ul>
										</div>
				                    </td>
			                    </tr>
			                   <?php } } ?>	
	
			                    </tbody>
			                    </table>
	                        </div>
	                    </div>
	                </div>
                </div>
            </div>
        </div>

<!-- Modal -->
<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
    <div class="modal-content animated">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
            <h4 class="modal-title">Eliminar cliente</h4>
        </div>
        <div class="modal-body">
            <p>&iquest;Est&aacute; seguro de querer eliminar el cliente <strong><input type="button" name="seccion" id="seccion" value="" class="btn_eliminar_popup"/></strong>?</p>
            <small>Tenga en cuenta que si el cliente tiene pedidos asociados no se podrá eliminar el mismo</small>
            <div class="modal-footer">
                <form name="eliminar" method="post" action="<?php echo base_url('tienda/clientes/eliminar'); ?>">
                	<input type="hidden" name="id" id="id" value=""/>
                	<input type="hidden" name="estado" id="estado" value=""/>
                    <input type="submit" class="btn btn-primary" value="Eliminar">
                </form>
            </div>
        </div>
      </div>
    </div>
</div>
<!-- Fin Modal -->

<!-- Tablas -->
<script src="<?php echo base_url('assets/js/plugins/dataTables/datatables.min.js'); ?>"></script>
<script>
$(document).ready(function(){
    $('.dataTables-listado').DataTable({
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
	  dom: 'lBfrtip',
	  buttons: [
	    'csv', 'excel', 'pdf'
	  ]        
    });
});

$('#myModal').on('show.bs.modal', function(e) {    
 var id = $(e.relatedTarget).data().id;
 var seccion = $(e.relatedTarget).data().seccion;
  $(e.currentTarget).find('#id').val(id);
  $(e.currentTarget).find('#seccion').val(seccion);
});
</script>