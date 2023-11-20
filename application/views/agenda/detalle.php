<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Agenda</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('agenda'); ?>">Agenda</a>
	                    </li>
	                    <li class="active">
	                        <strong>Detalle</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
		            <div class="title-action">
			            <a href="<?php echo base_url('agenda/modificar/' . $detalle['id']); ?>" class="btn btn-primary btn-sm">Modificar renunión</a>
                    </div>
                </div>
                <div class="col-xs-12">
	                <?php if (isset($notas)) { ?>
				        <ul class="notes">
	                        <?php foreach ($notas as $nota) { ?>
	                        <li>
	                            <div>
	                                <small><?php echo $nota['contacto']; ?>  <?php echo formatear_fecha($nota['fecha_alta'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></small>
	                                <h4><?php echo $nota['titulo']; ?></h4>
	                                <p><?php echo ellipsize($nota['descripcion'], 100); ?></p>
	                                <a href="<?php echo base_url('notas/modificar/' . $nota['id']); ?>"><i class="fa fa-edit"></i></a>
	                            </div>
	                        </li>
	                        <?php } ?>
	                    </ul>
	                <?php } ?>
                </div>
	        </div>
	        
	        <div class="wrapper wrapper-content animated fadeInRight">		            
	            
	            <div class="ibox-content m-b-sm border-bottom">
					<div class="row">
	                    <div class="col-sm-12">
	                        <div class="form-group">
	                            <label class="control-label">Nombre</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['nombre']; ?></div>
	                            <label class="control-label">Empresa</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['empresa']; ?></div>
	                            <label class="control-label">Email</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['email']; ?></div>
	                            <label class="control-label">Teléfono</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['telefono']; ?></div>
	                            <label class="control-label">País</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['pais']; ?></div>
	                            <label class="control-label">País de interés</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['oficina']; ?></div>
	                            <label class="control-label">Fecha</label>
	                            <div class="bg-muted p-xs b-r-sm"><?php echo $detalle['dia'].' - '.$detalle['hora']; ?></div>
	                            <label class="control-label">Estado</label>
	                            <div class="bg-muted p-xs b-r-sm"><?php echo $detalle['estado']; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-12">
	                        <div class="form-group">
	                        </div>
	                    </div>
	                </div>
	            </div>	            	            	            
	        </div>	       