<link href="<?php echo base_url('assets/css/tienda.css'); ?>" rel="stylesheet" type="text/css">

			<div class="row wrapper border-bottom white-bg page-heading">
            	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Tablero Operativo</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url('tienda/dashboard'); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('tienda/tienda/mi-tienda'); ?>">Tienda</a>
	                    </li>
	                    <li>
	                        <strong>Tablero Operativo</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                </div>
	        </div>
            
	        <div class="wrapper wrapper-content">
	                <div class="row">
	                    <div class="col-lg-3">
	                        <div class="ibox float-e-margins">
	                            <div class="ibox-title">
	                                <a href="<?php echo base_url('tienda/pedidos/listado/diario'); ?>" title="Ver pedidos recibidos" class="label label-success pull-right">Ver pedidos</a>
	                                <h5>Pedidos del Día</h5>
	                            </div>
	                            <div class="ibox-content">
	                                <h1 class="no-margins"><?php echo $pedidosdia['total']; ?></h1>
	                                <small>Total pedidos recibido en el día</small>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="col-lg-3">
	                        <div class="ibox float-e-margins">
	                            <div class="ibox-title">
	                                <a href="<?php echo base_url('tienda/pedidos/listado/todos/entregados'); ?>" title="Ver pedidos entregados" class="label label-info pull-right">Ver pedidos</a>
	                                <h5>Pedidos Entregados</h5>
	                            </div>
	                            <div class="ibox-content">
	                                <h1 class="no-margins"><?php echo $entregados['total']; ?></h1>
	                                <small>Pedidos Entregados del día</small>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="col-lg-3">
	                        <div class="ibox float-e-margins">
	                            <div class="ibox-title<?php echo ($pendientes['total'] > 0) ? ' bg-danger' : '';?>">
	                                <a href="<?php echo base_url('tienda/pedidos/listado/diario/pendientes'); ?>" title="Ver pedidos pendientes" class="label label-warning pull-right">De hoy</a>
	                                <h5>Pedidos Pendientes</h5>
	                            </div>
	                            <div class="ibox-content<?php echo ($pendientes['total'] > 0) ? ' bg-danger' : '';?>">
	                                <h1 class="no-margins"><?php echo $pendientes['total']; ?></h1>
	                                <small>Falta entregar </small>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="col-lg-3">
	                        <div class="ibox float-e-margins">
	                            <div class="ibox-title">
	                                <a href="<?php echo base_url('tienda/pedidos/listado/todos/cancelados'); ?>" title="Ver pedidos cancelados" class="label label-danger pull-right">Ver pedidos</a>
	                                <h5>Pedidos Cancelados</h5>
	                            </div>
	                            <div class="ibox-content">
	                                <h1 class="no-margins"><?php echo $cancelados['total']; ?></h1>
	                                <small>Cancelados</small>
	                            </div>
	                        </div>
	                     </div>
	            </div>
           </div>
           
        <div class="wrapper wrapper-content">
            
            <div class="row">
                <div class="col-lg-12">
	                <div class="ibox float-e-margins">
                    	<div class="ibox-title"><h5>Listado de Pedidos</h5></div>
	                    <div class="ibox-content">
	                        <div class="table-responsive">
				                <div class="col-sm-4 m-b-xs botones-filtros">
			                    	<div class="btn-group">
				                        <a class="btn btn-sm btn-white<?php echo ($periodicidad == 'todos') ? ' active':'';?>" href="<?php echo base_url('tienda/dashboard/operativo');?>">Todos </a>
				                        <a class="btn btn-sm btn-white<?php echo ($periodicidad == 'diario') ? ' active':'';?>" href="<?php echo base_url('tienda/dashboard/operativo/diario/');?>">Día </a>
				                        <a class="btn btn-sm btn-white<?php echo ($periodicidad == 'semanal') ? ' active':'';?>" href="<?php echo base_url('tienda/dashboard/operativo/semanal/');?>">Semana </a>
				                        <a class="btn btn-sm btn-white<?php echo ($periodicidad == 'mensual') ? ' active':'';?>" href="<?php echo base_url('tienda/dashboard/operativo/mensual/');?>">Mes </a>
			                        </div>
				                </div>
			                    <table class="table table-striped dataTables-example">
			                    <thead>
			                    <tr>
			                        <th>Nro.</th>
			                        <th>Fecha alta</th>
			                        <th>Monto</th>
			                        <th>Email</th>
			                        <th>Estado</th>
			                        <th>Acciones</th>
			                    </tr>
			                    </thead>
			                    <tbody>
			                   <?php foreach($listado as $lista) { ?>	
			                   	 <tr class="gradeX">
			                        <td><?php echo $lista['id'];?></td>
			                        <td><?php echo $lista['fecha_alta'];?></td>
			                        <td><?php echo $lista['total'];?></td>
			                        <td><?php echo ($lista['email']) ? $lista['email'] : 'Sin datos';?></td>
			                        <td><?php echo $lista['tipo_estado'];?></td>
			                        <td>
				                        <a href="<?php echo base_url('tienda/pedidos/modificar/').$lista['id']; ?>" title="Modificar" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i> Modificar</a> </td>
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
	  dom: 'lBfrtip',
	  buttons: [
	    'csv', 'excel', 'pdf'
	  ],        
        pageLength: 10,
        responsive: true
    });
});
</script>