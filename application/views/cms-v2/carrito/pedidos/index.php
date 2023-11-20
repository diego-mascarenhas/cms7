<!-- Tablas -->
<link href="<?php echo base_url('assets/css/plugins/dataTables/datatables.min.css'); ?>" rel="stylesheet" type="text/css">

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-12">
                <h2>Carro de Compras</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="/cms-v2/carrito/dashboard">Home</a>
                    </li>
                    <li>
                        Pedidos
                    </li>
                    <li class="active">
                        <strong>Listado</strong>
                    </li>
                </ol>
            </div>
        </div>
            
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">
	                <div class="ibox float-e-margins">
                    	<div class="ibox-title"><h5>Listado de Pedidos</h5></div>
	                    <div class="ibox-content">
	                        <div class="table-responsive">
			                    <table class="table table-striped table-bordered table-hover dataTables-example" >
				                    <thead>
					                    <tr>
					                        <th>N&deg; de Pedido</th>
					                        <th>Fecha</th>
					                        <th>Usuario</th>
					                        <th>Monto</th>
					                        <th>Estado</th>
					                        <th>Acciones</th>
					                    </tr>
				                    </thead>
				                    <tbody>
				                    <?php foreach($listado as $lista) { ?>	
				                   	 <tr class="gradeX">
				                        <td><?php echo $lista['id'];?></td>
				                        <td><?php echo $lista['fecha_alta'];?></td>
				                        <td><?php echo $lista['contacto'];?></td>
				                        <td><?php echo $lista['total'];?></td>
				                        <td><?php echo $lista['tipo_estado'];?></td>
				                        <td>
					                        <a href="<?php echo base_url('cms-v2/carrito/pedidos/modificar/').$lista['id']; ?>" title="Modificar" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i> Editar</a> </td>
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
        responsive: true
    });
});
</script>

