<style>
.tooltip-inner {max-width: 250px;width: 250px;}
</style>

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-xs-8 col-sm-8 col-lg-8">
                <h2>eLearning Pedidos Empresa</h2>
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li><a href="<?php echo base_url('cms-v2/elearning/pedidos/'); ?>">Pedidos</a></li>
                    <li><strong>Subir archivo con Usuarios</strong></li>
                </ol>
            </div>
        </div>
            
        <div class="wrapper wrapper-content animated fadeInRight">
            <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
            <input type="hidden" name="id_pedido" value="<?php echo $detalle['id']; ?>">
            <input type="hidden" name="id_contacto_padre" value="<?php echo $detalle['id_contacto']; ?>">
            <input type="hidden" name="razon_social" value="<?php echo $contacto['razon_social']; ?>">
            <div class="row">
                <div class="col-lg-12">
	                <div class="ibox float-e-margins">
	                    <div class="ibox-title">
		                    <h5>Subir archivo al Pedido <a href="<?php echo base_url('cms-v2/elearning/pedidos/detalle/'.$detalle['id']); ?>" title="Ir al pedido">Nro. <?php echo $detalle['id'].' - '.$detalle['observaciones']; ?></a> de la Empresa: <a href="<?php echo base_url('cms-v2/elearning/usuarios/empresas/'.$contacto['id']); ?>" title="Ir al pedido"><?php echo $contacto['nombre']; ?></a></h5>
	                    </div>
	                    <div class="ibox-content" style="min-height:140px; height:auto; float:left; padding-bottom:25px;">
	                        <?php if (validation_errors()) : ?>
							<div class="col-md-12">
								<div class="alert alert-danger" role="alert">
									<?php echo validation_errors(); ?>
								</div>
							</div>
							<?php endif; ?>
							<?php if (isset($error)) : ?>
							<div class="col-md-12">
								<div class="alert alert-danger" role="alert">
									<?php echo $error; ?>
								</div>
							</div>
							<?php endif; ?>

		                    <?php if ($this->session->flashdata('mensaje') == 'ok') : ?>
							<div class="col-md-12 pull-left full-width">
								<div class="alert alert-success alert-dismissable" role="alert">
									<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
									Su archivo se subió correctamente.
							    </div>
								<?php endif; ?>
								<?php if ($this->session->flashdata('mensaje') == 'error') : ?>
							<div class="col-md-12 pull-left full-width">
								<div class="alert alert-danger alert-dismissable" role="alert">
		                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
		                            Se produjo un error al subir el archivo.
		                        </div>
							</div>
							<?php endif; ?>

		                    <div class="col-sm-12">
	                            <h2>Cargar archivo csv</h2>
	                            <div class="form-group pull-left full-width">
		                            <div class="input-group col-sm-4 m-l-md m-t-md">
		                            	<input type="file" name="archivo" class="form-control"> <span class="input-group-btn"><button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Archivo csv con nombre, apellido, email y contraseña, en ese orden. La primer fila debe contener los nombres de los campos." title=""> <i class="fa fa-question"></i></button></span></div>
	                            </div>
	
	                            <div class="form-group">
	                                <div class="col-sm-4 col-sm-offset-2">
					                	<a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
	                                    <button class="btn btn-primary" type="submit" name="subir" value="1">Subir</button>
	                                </div>
	                            </div>
			                </div>
						</div>
	                </div>
	            </div>
            </div>
        </div>
    <!-- Fin Contenido -->
	<?php echo form_close(); ?>
<script>
$('[data-toggle="tooltip"]').tooltip(); 
</script>
			