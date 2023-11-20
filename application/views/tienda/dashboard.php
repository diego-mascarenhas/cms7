<link href="<?php echo base_url('assets/css/tienda.css'); ?>" rel="stylesheet" type="text/css">

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-xs-8 col-sm-8 col-lg-8">
		<h2>Dashboard</h2>
		<ol class="breadcrumb">
			<li>
				<a href="<?php echo base_url('tienda/dashboard'); ?>">Home</a>
			</li>
			<li>
				<a href="<?php echo base_url('tienda/tienda/mi-tienda'); ?>">Tienda</a>
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
		<div class="col-lg-3">
			<div class="widget widget-tienda">
				<a href="<?php echo base_url('micuenta/perfil'); ?>" title="Ir a Mi Perfil">
					<div class="row">
						<div class="col-xs-4">
							<i class="fa fa-user fa-5x"></i>
						</div>
						<div class="col-xs-8 text-right">
							<span>Mi Cuenta</span>
							<h2 class="font-bold">Perfíl</h2>
						</div>
					</div>
				</a>
			</div>
		</div>

		<div class="col-lg-3">
			<div class="widget widget-tienda">
				<a href="<?php echo base_url('tienda/tienda/mi-tienda'); ?>" title="Ir a Mi Tienda">
					<div class="row">
						<div class="col-xs-4">
							<i class="fa fa-cogs fa-5x"></i>
						</div>
						<div class="col-xs-8 text-right">
							<span>Mi Tienda</span>
							<h2 class="font-bold">Datos</h2>
						</div>
					</div>
				</a>
			</div>
		</div>

		<div class="col-lg-3">
			<div class="widget widget-tienda">
				<a href="<?php echo base_url('tickets'); ?>" title="Ir a Tickets">
					<div class="row">
						<div class="col-xs-4">
							<i class="fa fa-ticket fa-5x"></i>
						</div>
						<div class="col-xs-8 text-right">
							<span>Tickets</span>
							<h2 class="font-bold">0</h2>
						</div>
					</div>
				</a>
			</div>
		</div>
		
		<div class="col-lg-3">
			<div class="widget widget-tienda">
				<a href="<?php echo base_url('micuenta/balance'); ?>" title="Ir a Balance">
					<div class="row">
						<div class="col-xs-4">
							<i class="fa fa-money fa-5x"></i>
						</div>
						<div class="col-xs-8 text-right">
							<span>Balance</span>
							<h2 class="font-bold">$0</h2>
						</div>
					</div>
				</a>
			</div>
		</div>
	</div>
	  
	<!-- Configuración -->
	<div class="row" style="margin-top:10px;">
		<div class="col-lg-10">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>Datos generales</h5>
				</div>
				<div class="ibox-content">
					<div class="row">
						<div class="col-lg-3">
							<div class="ibox-content text-center" style="border:0; padding:10px 0 0;">
								<div class="p-sm m-b-sm">
									<?php if ($item['logo']) { ?>
										<img style="height:100px;" src="<?php echo base_url('/multimedia/thumbs/' . $item['logo']); ?>" title="<?php echo $item['titulo'];?>" alt="<?php echo $item['titulo']; ?>">
									<?php } else { ?>
										<p>No hay imagen <br>de logo cargada.</p>
									<?php } ?>
								</div>
								
								<div class="p-sm m-b-sm">
									<img style="height:100px;" src="<?php echo base_url('tienda/qr/index/' . $item['id']); ?>">
								</div>

								<div class="p-md m-b-sm" style="background:#fff;">
									<a href="<?php echo base_url('tienda/qr/menu/' . $item['id']); ?>" class="btn-sm btn-primary" target="_blank"><i class="fa fa-print"></i> QR Menú </a><br><br>
									<a href="<?php echo base_url('tienda/qr/pedidos/' . $item['id']); ?>" class="btn-sm btn-primary" target="_blank"><i class="fa fa-print"></i> QR Tienda</a>
								</div>		
							</div>
						</div>

						<div class="col-lg-4">
							<div class="ibox-content" style="background:#f3f3f4;">
								<h3>Datos de la Tienda</h3>
								<ul class="list-group clear-list m-t">
									<?php if (!empty($item['telefono'])) { ?>
									<li class="list-group-item">
										<i class="fa fa-phone"></i> <?php echo $item['telefono']; ?>
									</li>
									<?php } ?>
									<li class="list-group-item">
										<i class="fa fa-globe"></i>
											<a href="<?php echo $item['url'] . $item['titulo']; ?>" title="Ir a mi tienda" target="_blank"><?php echo $item['url'] . $item['titulo']; ?></a>
									</li>
									<li class="list-group-item">
										<i class="fa fa-map-marker"></i>
										<?php 
											echo $item['domicilio'] . ' ' . $item['numero'];
											echo ($item['localidad']) ? ', ' . $item['localidad'] : '';
											echo ($item['provincia']) ? ', ' . $item['provincia'] : '';
											echo ($item['moneda_pais']) ? ' (' . $item['moneda_pais'] . ')' : '';
										?>
									</li>
									<li class="list-group-item">
										<i class="fa fa-whatsapp"></i> <?php echo $item['celular']; ?>
									</li>
								</ul>
							</div>
						</div>

						<div class="col-lg-5">
							<div class="ibox-content" style="background:#f3f3f4;">
								<h3>Redes Sociales</h3>
								<ul class="list-group clear-list m-t">
									<?php if (!empty($item['facebook'])) { ?>
										<li class="list-group-item">
											<i class="fa fa-facebook-square"></i> 
											<a href="<?php echo $item['facebook']; ?>" target="_blank"><?php echo $item['facebook']; ?></a>
										</li>
									<? } ?>
									
									<?php if (!empty($item['instagram'])) { ?>
										<li class="list-group-item">
											<i class="fa fa-instagram"></i>
										<a href="<?php echo $item['instagram']; ?>" target="_blank"><?php echo $item['instagram']; ?></a>
										</li>
									<? } ?>
									
									<?php if (!empty($item['twitter'])) { ?>
										<li class="list-group-item">
											<i class="fa fa-twitter"></i>
										<a href="<?php echo $item['twitter']; ?>" target="_blank"><?php echo $item['twitter']; ?></a>
										</li>
									<? } ?>
									
									<?php if (!empty($item['youtube'])) { ?>
										<li class="list-group-item">
											<i class="fa fa-youtube"></i>
										<a href="<?php echo $item['youtube']; ?>" target="_blank"><?php echo $item['youtube']; ?></a>
										</li>
									<? } ?>
									
									<?php if (!empty($item['linkedin'])) { ?>
									<li class="list-group-item">
										<i class="fa fa-linkedin"></i>
									<a href="<?php echo $item['linkedin']; ?>" target="_blank"><?php echo $item['linkedin']; ?></a>
										</li>
									<? } ?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="col-lg-2">
			<div class="wrapper wrapper-content project-manager">
				<h4>Pedimos Fácil</h4>
				<p class="small">
					No dude en contactarnos para cualquier información adicional que necesite para su tienda.
				</p>
				<br>
				
				<h5>Ventas</h5>
				<ul class="list-unstyled project-files">
					<li><a href="mailto:ventas@pedimosfacil.com?subject=Consulta&nbsp;desde&nbsp;CMS"><i class="fa fa-envelope"></i> ventas@pedimosfacil.com</a></li>
					<li><a href="https://wa.me/5493413661548?text=Hola&nbsp;quisiera&nbsp;consultar&nbsp;por" target="_blank" title="Contactar por Whatsapp"><i class="fa fa-whatsapp"></i> +54 9 3413 66-1548</a></li>
				</ul>
				<br>
				
				<h5>Soporte técnico</h5>
				<ul class="list-unstyled project-files">
					<li><a href="mailto:soporte@pedimosfacil.com?subject=Consulta&nbsp;desde&nbsp;CMS"><i class="fa fa-envelope"></i> soporte@pedimosfacil.com</a></li>
					<li><a href="https://wa.me/5491138738376?text=Hola&nbsp;quisiera&nbsp;consultar&nbsp;por" target="_blank" title="Contactar por Whatsapp"><i class="fa fa-whatsapp"></i> +54 9 11 3873-8376</a></li>
				</ul>
				<!-- <div class="text-center m-t-md">
					<a href="#" class="btn btn-xs btn-primary">Add files</a>
					<a href="#" class="btn btn-xs btn-primary">Report contact</a>
				</div> -->
			</div>
		</div>
	</div>
	<!-- Fin Configuración -->
