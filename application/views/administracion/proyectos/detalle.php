<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Administración</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('administracion/proyectos'); ?>">Proyectos</a>
	                    </li>
	                    <li class="active">
	                        <strong>Detalle</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
	                    <?php if ($this->usuario->perfil == 'reseller') { ?>
	                    	<a href="<?php echo base_url('notas/ingresar?id_tipo=113&id_referencia=' . $detalle['id']); ?>" class="btn btn-white btn-sm"><i class="fa fa-thumb-tack"></i></a>
                        <?php } ?>
                        <a href="<?php echo base_url('administracion/proyectos/modificar/' . $detalle['id']); ?>" class="btn btn-primary btn-sm">Modificar proyecto</a>
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
	                            <label class="control-label">Empresa</label>
	                            <div class="bg-muted p-xs b-r-sm"> <a href="<?php echo base_url('administracion/empresas/detalle/' . $detalle['id_empresa']); ?>"><?php echo $detalle['empresa']; ?></a></div>
	                        </div>
	                    </div>
	                </div>
	                <div class="row">
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Título</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['titulo']; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-6">
	                        <div class="form-group">
	                            <label class="control-label">Categoría</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['categoria']; ?></div>
	                        </div>
	                    </div>
	                </div>
	                <div class="row">
	                    <div class="col-sm-3">
	                        <div class="form-group">
	                            <label class="control-label">Desde</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo formatear_fecha($detalle['desde'], 'd-m-Y', null, $this->usuario->timezone); ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-3">
	                        <div class="form-group">
	                            <label class="control-label">Hasta</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo formatear_fecha($detalle['hasta'], 'd-m-Y', null, $this->usuario->timezone); ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-3">
	                        <div class="form-group">
	                            <label class="control-label">Responsable</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['responsable']; ?></div>
	                        </div>
	                    </div>
	                    <div class="col-sm-3">
	                        <div class="form-group">
	                            <label class="control-label">Estado</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['estado']; ?></div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            
	            <div class="ibox-content m-b-sm border-bottom">
	                <div class="row">
	                    <div class="col-sm-12">
	                        <div class="form-group">
	                            <label class="control-label">Descripción</label>
	                            <div class="bg-muted p-xs b-r-sm"> <?php echo $detalle['descripcion']; ?></a></div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            
	            <?php if (isset($detalle['valor'])) { ?>
	            <div class="ibox-content m-b-sm border-bottom">
	                <div class="row">
		                <table class="table invoice-total">
                            <tbody>
                                <tr>
                                    <td><strong>Total:</strong></td>
                                    <td><strong><?php echo $detalle['valor']; ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
	                </div>
	            </div>
	            <?php } ?>
	            
				<?php if (isset($tareas)) { ?>
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox float-e-margins">
	                        <div class="ibox-content">
	                            <div class="table-responsive">
	                                <table class="table table-striped footable">
	                                    <thead>
	                                    <tr>
	                                        <th>Tarea</th>
	                                        <th>Contacto</th>
	                                        <th class="text-center">Desde</th>
	                                        <th class="text-center">Hasta</th>
	                                        <th class="text-center">Horas asignadas / utilizadas</th>
	                                        <th class="text-center">Porcentaje</th>
	                                        <th class="text-center">Estado</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
		                                	<?php foreach ($tareas as $tarea) { ?>
		                                    <tr>
		                                        <td><a href="<?php echo base_url('tareas/detalle/' . $tarea['id']); ?>"><?php echo $tarea['titulo']; ?></a></td>
		                                        <td><a href="<?php echo base_url('administracion/contactos/detalle/'); ?><?php echo $tarea['id_contacto']; ?>"><?php echo $tarea['contacto']; ?></a></td>
		                                        <td class="text-center"><?php echo formatear_fecha($tarea['desde'], 'd-m-Y', null, $this->usuario->timezone); ?></td>
		                                        <td class="text-center"><?php echo formatear_fecha($tarea['hasta'], 'd-m-Y', null, $this->usuario->timezone); ?></td>
		                                        <td class="text-center"><?php if (isset($tarea['horas_designadas'])) echo $tarea['horas_designadas'] . ' hs'; ?> <?php if (isset($tarea['horas_utilizadas'])) echo ' / ' . $tarea['horas_utilizadas']  . ' hs'; ?></td>
		                                        <td class="text-center"><?php if (isset($tarea['porcentaje_realizado'])) echo $tarea['porcentaje_realizado'] . '%'; ?></td>
		                                        <td class="text-center"><span class="label label-<?php echo $tarea['estado_ui_class']; ?>"><?php echo $tarea['estado']; ?></span></td>
		                                    </tr>
											<? } ?>
	                                    </tbody>
	                                    <tfoot>
		                                    <tr>
			                                    <td colspan="7"><?php if (isset($paginado)) echo $paginado; ?></td>
		                                    </tr>
	                                    </tfoot>
	                                </table>
	                            </div>
	
	                        </div>
	                    </div>
	                </div>
	            </div>
	            <?php } ?>
	            
	        </div>