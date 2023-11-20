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
                        <strong>Clientes</strong>
                    </li>
                </ol>
            </div>
        </div>
            
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">
	                <div class="ibox float-e-margins">
                    	<div class="ibox-title"><h5>Listado de Clientes</h5></div>
	                    <div class="ibox-content">
	                        <div class="table-responsive tabla-pedidos">
			                    <table class="table table-striped table-bordered dataTables-listado">
			                    <thead>
			                    <tr>
			                        <th>Nro. del Pedido</th>
			                        <th>Nombre</th>
			                        <th>Celular</th>
			                        <th>Email</th>
			                        <th>Domicilio</th>
			                        <th>Observaciones</th>
			                        <th>Estado del Pedido</th>
			                        <th>Acciones</th>
			                    </tr>
			                    </thead>
			                    <tbody>
			                   <?php foreach($listado as $lista) { ?>	
			                   	 <tr class="gradeX">
			                        <td><?php echo $lista['id'];?></td>
			                        <td><?php echo $lista['nombre'];?></td>
			                        <td><?php echo $lista['celular'];?></td>
			                        <td><?php echo ($lista['email']) ? $lista['email'] : 'Sin datos';?></td>
			                        <td><?php echo $lista['domicilio'];?></td>
			                        <td><?php echo $lista['observaciones'];?></td>
			                        <td><?php echo $lista['tipo_estado'];?></td>
			                        <td>
										<div class="dropdown">
										  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Acciones <span class="caret"></span>
										  </button>
										  <ul class="dropdown-menu dropdown-clientes" aria-labelledby="dropdownMenu1">
										    <li><a href="https://wa.me/<?php echo $lista['celular'];?>?text=Hola&nbsp;te&nbsp;consulto&nbsp;por" title="Chatear por consulta" target="_blank"><i class="fa fa-whatsapp"></i> Chatear por consulta</a></li>
										    <li> <a href="mailto:<?php echo $lista['email'];?>?subject=Hola&nbsp;te&nbsp;consulto&nbsp;por" title="Contactar por mail" target="_blank"><i class="fa fa-envelope"></i> Contactar por mail</a></li>
										  </ul>
										</div>
				                    </td>
			                    </tr>
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
	  ],        
        pageLength: 10
    });
});
</script>