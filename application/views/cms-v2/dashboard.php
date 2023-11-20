<style>
.dataTablesAutores td, .dataTablesPedidos td, .dataTablesUsuarios td, .dataTablesLibros td  { font-size:12px;}
.dataTablesAutores th, .dataTablesPedidos th, .dataTablesUsuarios th, .dataTablesLibros th  { background:#ebebeb !important;font-size:12px;}
@media (min-width:1200px) and (max-width:1430px) { 
.icono-dashboard { font-size: 2em !important; }
.widget .text-right { padding-left: 5px !important; padding-right: 2px !important; }
.widget .text-center { padding-left: 2px !important; padding-right: 3px !important; }
.widget.style1 h2 { font-size:25px;}
}
@media (min-width:1200px) and (max-width:1520px) { 
.icono-dashboard { font-size: 2em !important; }
}
@media (min-width:1300px) and (max-width:1610px) { 
.widget.style1  { padding:10px; }
.widget .text-right { padding-left: 10px !important; padding-right: 10px !important; }
.widget .text-center { padding-left: 10px !important; padding-right: 10px !important; }
}
</style>

            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-xs-8 col-sm-8 col-lg-8">
                    <h2>Sitio web Dashboard</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="/cms">Home</a>
                        </li>
                        <li class="active">
                            <strong>Dashboard</strong>
                        </li>
                    </ol>
                </div>
            </div>

       	<!-- Comienzo Tabs -->
        <div class="wrapper wrapper-content">
	      <!-- Pedidos -->
        	  <div class="row">
				<div class="col-md-4 col-lg-2">
				    <a href="/cms-v2/pedidos?moneda=ar&estado=2" class="block">
				    <div class="widget style1 navy-bg">
			            <div class="row">
			                <div class="col-xs-2 text-center">
			                    <i class="fa fa-money fa-3x icono-dashboard"></i>
			                </div>
			                <div class="col-xs-10 text-right">
			                    <span>Compras en pesos</span>
			                    <h2 class="font-bold">$ <?php echo $totalpedidospesos['total']; ?> </h2>
			                </div>
			            </div>
				    </div></a>
				</div>
				<div class="col-md-4 col-lg-2">
				    <a href="/cms-v2/pedidos?moneda=ex&estado=2" class="block">
				    <div class="widget style1 navy-bg">
			            <div class="row">
			                <div class="col-xs-2 text-center">
			                    <i class="fa fa-money fa-3x icono-dashboard"></i>
			                </div>
			                <div class="col-xs-10 text-right">
			                    <span>Compras en dólares</span>
			                    <h2 class="font-bold">$ <?php echo $totalpedidosdolares['total']; ?> </h2>
			                </div>
			            </div>
				    </div></a>
				</div>
				<div class="col-md-4 col-lg-2">
				    <a href="/cms-v2/usuarios" class="block">
				    <div class="widget style1 lazur-bg">
				        <div class="row">
			                <div class="col-xs-2 text-center">
				                <i class="fa fa-users fa-3x icono-dashboard"></i>
				            </div>
				            <div class="col-xs-10 text-right">
				                <span>Usuarios registrados</span>
				                <h2 class="font-bold"><?php echo $totalusuarios['total']; ?></h2>
				            </div>
				        </div>
				    </div></a>
				</div>

				<div class="col-md-4 col-lg-2">
				    <a href="/cms-v2/pedidos?estado=5" class="block">
				    <div class="widget style1 yellow-bg">
				        <div class="row">
			                <div class="col-xs-2 text-center">
				                <i class="fa fa-exclamation-circle fa-3x icono-dashboard"></i>
				            </div>
				            <div class="col-xs-10 text-right">
				                <span>Pedidos pendientes</span>
				                <h2 class="font-bold"><?php echo $totalpedidospendientes['total']; ?></h2>
				            </div>
				        </div>
				    </div></a>
				</div>

				<div class="col-md-4 col-lg-2">
				    <a href="/cms-v2/pedidos?estado=2" class="block">
				    <div class="widget style1 red-bg">
				        <div class="row">
			                <div class="col-xs-2 text-center">
				                <i class="fa fa-shopping-cart fa-3x icono-dashboard"></i>
				            </div>
				            <div class="col-xs-10 text-right">
				                <span>Pedidos finalizados </span>
				                <h2 class="font-bold"><?php echo $totalpedidosfinalizados['total']; ?></h2>
				            </div>
				        </div>
				    </div></a>
				</div>

				<div class="col-md-4 col-lg-2">
				    <a href="/cms-v2/pedidos?estado=7" class="block">
				    <div class="widget style1 blue-bg">
				        <div class="row">
			                <div class="col-xs-2 text-center">
				                <i class="fa fa-gift fa-3x icono-dashboard"></i>
				            </div>
				            <div class="col-xs-10 text-right">
				                <span>Pedidos regalados </span>
				                <h2 class="font-bold"><?php echo $totalpedidosregalados['total']; ?></h2>
				            </div>
				        </div>
				    </div></a>
				</div>
        	  </div>
        	  
        	 <!-- Configuracion -->
        	  <div class="row" style="margin-top:34px;">
	            <div class="col-lg-12">
	                <div class="ibox float-e-margins">
	                    <div class="ibox-title">
	                        <h5>Datos generales del sitio</h5>
	                        <div class="ibox-tools">
	                            <a class="collapse-link">
	                                <i class="fa fa-chevron-up"></i>
	                            </a>
	                        </div>
	                    </div>
	                    <div class="ibox-content">
	                        <div class="row">
	                            <div class="col-lg-3">
		                            <div class="ibox-content text-center" style="background:#23c6c8; color:#fff; padding:40px 0;">
		                                <h1><?php echo $item['titulo'];?></h1><br>
		                                <div class="m-b-sm">
		                                     <img style="height:61px;" src="<?php echo base_url('/multimedia/511/7358/'.$item['logo']);?>" title="<?php echo $item['titulo'];?>" alt="<?php echo $item['titulo'];?>">
		                                </div>
		                                <p class="font-bold"><br><?php echo $item['subtitulo'];?></p>
		
		                            </div>
	                            </div>

	                            <div class="col-lg-6">
		                            <div class="ibox-content" style="background:#f3f3f4;">
		                                <h3>Datos de Contacto</h3>
										<ul class="list-group clear-list m-t">
				                            <li class="list-group-item fist-item"><span class="label red-bg"><i class="fa fa-envelope"></i></span> &nbsp;<?php echo $item['email'];?></li>
				                            <li class="list-group-item"><span class="label red-bg"><i class="fa fa-phone"></i></span> &nbsp;<?php echo $item['telefonos'];?></li>
				                            <?php echo($item['facebook']) ? '<li class="list-group-item"><span class="label red-bg"><i class="fa fa-facebook"></i></span> &nbsp;'.$item['facebook'].'</li>' : null;?>
				                            <?php echo($item['instagram']) ? '<li class="list-group-item"><span class="label red-bg"><i class="fa fa-instagram"></i></span> &nbsp;'.$item['instagram'].'</li>' : null;?>
				                            <?php echo($item['twitter']) ? '<li class="list-group-item"><span class="label red-bg"><i class="fa fa-twitter"></i></span> &nbsp;'.$item['twitter'].'</li>' : null;?>
				                            <?php echo($item['youtube']) ? '<li class="list-group-item"><span class="label red-bg"><i class="fa fa-youtube"></i></span> &nbsp;'.$item['youtube'].'</li>' : null;?>
				                            <?php echo($item['linkedin']) ? '<li class="list-group-item"><span class="label red-bg"><i class="fa fa-linkedin"></i></span> &nbsp;'.$item['linkedin'].'</li>' : null;?>
		                                </ul>
		                            </div>
	                            </div>

	                            <div class="col-lg-3">
		                            <div class="ibox-content" style="background:#f3f3f4;">
		                                <h3>Datos de SEO</h3>
										<ul class="list-group clear-list m-t">
				                            <li class="list-group-item"><h4>Descripción:</h4><p> <?php echo $item['descripcion'];?></p></li>
				                            <li class="list-group-item"><h4>Keywords:</h4><p> <?php echo $item['keywords'];?></p></li>
		                                </ul>
		                            </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
        	  </div>
        	  
	          <!-- Fin Configuracion -->
        	  <div class="row" style="margin-top:18px;">
	            <div class="col-lg-6">
	                <div class="ibox float-e-margins">
	                    <div class="ibox-title">
	                        <h5>&Uacute;ltimos Pedidos</h5>
	                        <div class="ibox-tools">
	                            <a class="collapse-link">
	                                <i class="fa fa-chevron-up"></i>
	                            </a>
	                        </div>
	                    </div>
	                    <div class="ibox-content">
	                        <div class="row">
	                            <div class="col-lg-12">
			                        <div class="table-responsive">
					                    <table class="table dataTablesPedidos table-hover margin bottom">
						                    <thead>
						                    <tr>
						                        <th>Pedido</th>
						                        <th>Fecha</th>
						                        <th>Usuario</th>
						                        <th>Forma de pago</th>
						                        <th>Monto</th>
						                        <th>Estado</th>
						                    </tr>
						                    </thead>
						                    <tbody>
						                   <?php foreach($pedidos as $pedido) { ?>	
						                   	 <tr class="gradeX">
						                        <td><?php echo $pedido['id'];?></td>
						                        <td><?php echo $pedido['fecha_alta'];?></td>
						                        <td><?php echo 'Nombre y Apellido'; //$pedido['nombre'].' '.$pedido['apellido'];?></td>
						                        <td><?php echo ($pedido['id_forma_pago'] == 1) ? 'Mercado Pago' : 'Paypal';?></td>
						                        <td><?php echo $pedido['total'];?></td>
						                        <td><?php echo $pedido['estado'];?></td>
						                    </tr>
						                   <?php } ?>	
						                    </tbody>
					                    </table>
			                        </div>
			                        <a href="https://cms.revisionalpha.com/cms-v2/pedidos" class="btn btn-info pull-right btn-sm" type="button"><i class="fa fa-list"></i> Ver pedidos</a>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <!-- Fin Pedidos -->

	            <!-- Usuarios -->
	            <div class="col-lg-6">
	                <div class="ibox float-e-margins">
	                    <div class="ibox-title">
	                        <h5>&Uacute;ltimas noticias</h5>
	                        <div class="ibox-tools">
	                            <a class="collapse-link">
	                                <i class="fa fa-chevron-up"></i>
	                            </a>
	                        </div>
	                    </div>
	                    <div class="ibox-content">
	                        <div class="row">
	                            <div class="col-lg-12">
			                        <div class="table-responsive">
					                    <table class="table dataTablesNoticias table-hover margin bottom">
						                    <thead>
							                    <tr>
							                        <th>Imagen</th>
							                        <th>T&iacute;tulo</th>
							                        <th>Categor&iacute;a</th>
							                        <th>Estado</th>
							                    </tr>
						                    </thead>
						                    <tbody>
							                    <?php foreach($noticias as $noticia) { ?>	
							                   	 <tr class="gradeX">
													<td>
														<?php if(!empty($noticia['imagen'])) { ?>	
								                        <img src="<?php echo base_url('/multimedia/511/7358/'.$noticia['imagen']);?>" title="<?php echo $noticia['seccion'];?>" alt="<?php echo $noticia['seccion'];?>" 	width="70">
														<?php } else { ?>	
														<img src="https://cocinaonlinesolenardelli.com/assets/images/logo-sole-nardelli.png" alt="<?php echo $noticia['seccion']; ?>" width="70" />
														<?php } ?></td>
							                        <td><?php echo $noticia['titulo'];?></td>
							                        <td><?php echo $noticia['seccion'];?></td>
							                        <td><?php echo $noticia['estado'];?></td>
							                    </tr>
							                   <?php } ?>	
						                    </tbody>
					                    </table>
			                        </div>
			                        <a href="https://cms.revisionalpha.com/cms-v2/noticias" class="btn btn-info pull-right btn-sm" type="button"><i class="fa fa-list"></i> Ver noticias</a>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	      <!-- Fin Usuarios -->

<!-- Page-Level Scripts -->
<script>
$(document).ready(function(){

	$('.dataTablesPedidos').DataTable({
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
	        {extend: 'excel', title: 'Listado de Pedidos'},
	        {extend: 'pdf', title: 'Listado de Pedidos'}
            ]
        });

	$('.dataTablesNoticias').DataTable({
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
	        {extend: 'excel', title: 'Listado de Autores'},
	        {extend: 'pdf', title: 'Listado de Autores'}
            ]
        });
});
</script>

