<link href="<?php echo base_url('assets/css/tienda.css'); ?>" rel="stylesheet" type="text/css">
		<div class="row wrapper border-bottom white-bg page-heading">
        	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
                <h2>Tienda</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('tienda/dashboard'); ?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('tienda/tienda/mi-tienda'); ?>">Tienda</a>
                    </li>
                    <li>
                        <strong>Pedidos</strong>
                    </li>
                </ol>
            </div>
            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                <div class="title-action">
<!--                     <a href="<?php echo base_url('tienda/pedidos/ingresar'); ?>" class="btn btn-primary btn-sm">Ingresar Pedidos</a> -->
                </div>
            </div>
        </div>
            
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">
	                <div class="ibox float-e-margins">
                    	<div class="ibox-title"><h5>Listado de Pedidos <?php echo (isset($estados)) ? $estados : '';?></h5></div>
	                    <div class="ibox-content">
	                        <div class="table-responsive tabla-pedidos">
				                <div class="col-sm-4 m-b-md botones-filtros">
			                    	<div class="btn-group">
				                        <a class="btn btn-sm btn-white<?php echo ($periodicidad == 'todos') ? ' active':'';?>" href="<?php echo base_url('tienda/pedidos/listado');?>">Todos </a>
				                        <a class="btn btn-sm btn-white<?php echo ($periodicidad == 'diario') ? ' active':'';?>" href="<?php echo base_url('tienda/pedidos/listado/diario/');?>">Día </a>
				                        <a class="btn btn-sm btn-white<?php echo ($periodicidad == 'semanal') ? ' active':'';?>" href="<?php echo base_url('tienda/pedidos/listado/semanal/');?>">Semana </a>
				                        <a class="btn btn-sm btn-white<?php echo ($periodicidad == 'mensual') ? ' active':'';?>" href="<?php echo base_url('tienda/pedidos/listado/mensual/');?>">Mes </a>
			                        </div>
				                </div>
			                    <table class="table table-striped table-bordered dataTablespedidos">
			                    <thead>
			                    <tr>
			                        <th>Nro.</th>
			                        <th>Fecha pedido</th>
			                        <th>Monto</th>
			                        <th>Cliente</th>
			                        <th width="64">Entregado</th>
			                        <th width="64">Pagado</th>
			                        <th width="120" style="min-width:140px !important;">Estado</th>
			                        <th width="90">Acciones</th>
			                    </tr>
			                    </thead>
			                    <tbody>
			                   <?php foreach($listado as $lista) { ?>	
			                   	 <tr class="gradeX">
			                        <td><?php echo $lista['id'];?></td>
			                        <td><?php echo $lista['fecha_alta'];?></td>
			                        <td class="text-left"><?php echo $lista['total'];?></td>
			                        <td><?php echo ($lista['contacto']) ? $lista['contacto'] : $lista['nombre'];?></td>
			                        <td class="text-center bg-<?php echo ($lista['estado'] == 7) ? 'verde' : 'danger';?>"><?php echo ($lista['estado'] == 7) ? 'S&iacute;' : 'No';?></td>
			                        <td class="text-center bg-<?php echo ($lista['estado'] == 5 || $lista['estado'] == 9 || $lista['estado'] == 11) ? 'verde' : 'danger';?>"><?php echo ($lista['estado'] == 5 || $lista['estado'] == 9 || $lista['estado'] == 11) ? 'S&iacute;' : 'No';?></td>
			                        <td style="width:180px !important;"><?php echo $lista['tipo_estado'];?></td>
			                        <td class="text-center">
										<div class="dropdown">
										  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Acciones <span class="caret"></span>
										  </button>
										  <ul class="dropdown-menu dropdown-pedidos" aria-labelledby="dropdownMenu1">
										    <li><a href="<?php echo base_url('tienda/pedidos/detalle/').$lista['id']; ?>" title="Detalle"><i class="fa fa-bookmark"></i> Detalle</a></li>
										    <li><a href="<?php echo base_url('tienda/pedidos/modificar/').$lista['id']; ?>" title="Modificar"><i class="fa fa-pencil"></i> Cambiar estado</a></li>
										    <li><a href="https://wa.me/<?php echo $lista['celular'];?>?text=Hola&nbsp;te&nbsp;consulto&nbsp;por" title="Enviar consulta" target="_blank"><i class="fa fa-whatsapp"></i> Chatear por consulta</a></li>
										    <li><a href="<?php echo base_url('tienda/pedidos/modificar/').$lista['id']; ?>" title="Modificar"><i class="fa fa-envelope"></i> Email pedido en camino</a> </a></li>
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

 $('.dataTablespedidos').DataTable({
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