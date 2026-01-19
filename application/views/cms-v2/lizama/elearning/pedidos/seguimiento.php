		<div class="row wrapper border-bottom white-bg page-heading">
        	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
                <h2>Seguimiento de Usuarios</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('micuenta'); ?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('cms-v2/elearning/pedidos/'); ?>">Pedidos</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('cms-v2/elearning/pedidos/detalle/' . $detalle['id']); ?>">Detalle</a>
                    </li>
                    <li>
                        <strong>Seguimiento</strong>
                    </li>
                </ol>
            </div>
            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                <div class="title-action">
			        <a href="<?php echo base_url('cms-v2/elearning/pedidos/detalle/' . $detalle['id']); ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Volver al Pedido</a>
                </div>
            </div>
	     </div>
	     <div class="wrapper wrapper-content animated fadeInRight">		            
            <div class="row">
            	<div class="col-lg-12">
                    <?php if ($this->session->flashdata('resultado') == '1') : ?>
					<div class="alert alert-success alert-dismissable" role="alert">
						<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
						<?php echo $this->session->flashdata('mensaje'); ?>
				    </div>
					<?php endif; ?>
					<?php if ($this->session->flashdata('resultado') == '0') : ?>
					<div class="alert alert-danger alert-dismissable" role="alert">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
						<?php echo $this->session->flashdata('mensaje'); ?>
                    </div>
                    <?php endif; ?>

                    <div class="ibox">
                        <div class="ibox-content" style="border-bottom:2px solid #e7eaec;">
	                        <h2>Pedido Nro. <?php echo $detalle['id']; ?>
	                        <small class="label-primary pull-right p-xs b-r-sm"> <?php echo $detalle['tipo_estado']; ?></small></h2>
	                        <div class="row">
	                        	<div class="col-sm-6">
			                        <dl class="dl-horizontal dl-pedidos">
			                            <dt>Cliente:</dt>
			                            <dd><?php echo $detalle['nombre'] . ' ' . $detalle['apellido']; ?></dd>
			                            <dt>Email:</dt>
			                            <dd><?php echo $detalle['email']; ?></dd>
			                        </dl>
			                    </div>
			                    <div class="col-sm-6">
			                        <dl class="dl-horizontal dl-pedidos">
			                            <?php if($detalle['empresa']) { ?>
			                            <dt>Empresa:</dt>
			                            <dd><?php echo $detalle['empresa']; ?></dd>
			                            <?php } ?>
			                            <dt>Fecha Alta:</dt>
			                            <dd><?php echo $detalle['fecha_alta']; ?></dd>
			                        </dl>
			                    </div>
	                        </div>
                        </div>
                    </div>

                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>Actividad de Usuarios</h5>
                            <div class="ibox-tools">
                                <span class="label label-info"><?php echo ($progreso) ? count($progreso) : 0; ?> usuario(s)</span>
                            </div>
                        </div>
                        <div class="ibox-content">
                        	<?php if($progreso) { ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Usuario</th>
                                            <th>Email</th>
                                            <th>Curso</th>
                                            <th class="text-center">Ingresó a Video</th>
                                            <th class="text-center">Completó Encuesta</th>
                                            <th class="text-center">Certificado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($progreso as $usuario) { 
                                            // Determinar clase de fila según el estado
                                            $row_class = '';
                                            if ($usuario['certificado'] == 1) {
                                                $row_class = 'success'; // Verde - Certificado obtenido
                                            } elseif ($usuario['fecha_ingreso_video']) {
                                                $row_class = 'warning'; // Amarillo - Solo vio video
                                            }
                                        ?>
                                        <tr class="<?php echo $row_class; ?>">
                                            <td>
                                                <strong><?php echo $usuario['nombre'] . ' ' . $usuario['apellido']; ?></strong>
                                            </td>
                                            <td><?php echo $usuario['email']; ?></td>
                                            <td><?php echo $usuario['curso_titulo']; ?></td>
                                            <td class="text-center">
                                                <?php if($usuario['fecha_ingreso_video']) { ?>
                                                    <span class="label label-primary">
                                                        <i class="fa fa-check"></i> 
                                                        <?php echo date('d/m/Y H:i', strtotime($usuario['fecha_ingreso_video'])); ?>
                                                    </span>
                                                <?php } else { ?>
                                                    <span class="text-muted"><i class="fa fa-minus"></i> Sin actividad</span>
                                                <?php } ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if($usuario['fecha_completo_encuesta']) { ?>
                                                    <span class="label label-success">
                                                        <i class="fa fa-check-circle"></i> 
                                                        <?php echo date('d/m/Y H:i', strtotime($usuario['fecha_completo_encuesta'])); ?>
                                                    </span>
                                                <?php } else { ?>
                                                    <span class="text-muted"><i class="fa fa-clock-o"></i> Pendiente</span>
                                                <?php } ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if($usuario['certificado'] == 1) { ?>
                                                    <span class="label label-success"><i class="fa fa-certificate"></i> Obtenido</span>
                                                <?php } else { ?>
                                                    <span class="label label-default">Pendiente</span>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php } else { ?>
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> No hay usuarios registrados en este pedido.
                            </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="fa fa-info-circle"></i> Información de los estados
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="alert alert-success" style="margin-bottom:5px;">
                                                <i class="fa fa-check-circle"></i> <strong>Verde:</strong> Usuario completó la encuesta y obtuvo certificado
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="alert alert-warning" style="margin-bottom:5px;">
                                                <i class="fa fa-exclamation-triangle"></i> <strong>Amarillo:</strong> Usuario ingresó al video pero no completó la encuesta
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="alert alert-default" style="margin-bottom:5px; background-color:#f8f8f8; border:1px solid #ddd;">
                                                <i class="fa fa-circle-o"></i> <strong>Sin color:</strong> Usuario sin actividad registrada
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
