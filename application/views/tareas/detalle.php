<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row wrapper border-bottom white-bg page-heading">
	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
		<h2>Tareas</h2>
		<ol class="breadcrumb">
			<li>
				<a href="<?php echo base_url(); ?>">Home</a>
			</li>
			<li>
				<a href="<?php echo base_url('tareas'); ?>">Tareas</a>
			</li>
			<li class="active">
				<strong>Detalle</strong>
			</li>
		</ol>
	</div>
	<div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
		<div class="title-action">
			<a href="<?php echo base_url('tareas/modificar/') . $detalle['id']; ?>" class="btn btn-primary btn-sm">Modificar tarea</a>
		</div>
	</div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">		            
	<div class="ibox-content m-b-sm border-bottom">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label class="control-label">Empresa</label>
					<div class="bg-muted p-xs b-r-sm"> <a href="<?php echo base_url('administracion/contactos/detalle/'); ?><?php echo $detalle['id_contacto']; ?>"><?php echo $detalle['contacto']; ?></a></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label">Desde</label>
					<div class="bg-muted p-xs b-r-sm"> <?php echo formatear_fecha($detalle['desde'], 'd-m-Y', null, $this->usuario->timezone); ?></div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label">Hasta</label>
					<div class="bg-muted p-xs b-r-sm"> <?php echo formatear_fecha($detalle['hasta'], 'd-m-Y', null, $this->usuario->timezone, null, array('default'=>'Sin vencimiento')); ?></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label class="control-label">Título</label>
					<div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['titulo']; ?></div>
				</div>
			</div>
		</div>
	</div>
	<!-- detalle de la descripción de la tarea -->
	<?php if (!empty($detalle['descripcion'])) { ?>
		<div class="ibox-content m-b-sm border-bottom">
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<label class="control-label">Descripción</label>
						<div class="bg-muted p-xs b-r-sm"> 
							<?php echo "<pre>";
								print_r($detalle['descripcion']);
							?>	
						</div>	                       
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
</div>