<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-lg-10">
	                <h2>Agenda</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('agenda'); ?>">Agenda</a>
	                    </li>
	                    <li class="active">
	                        <strong><?php echo (!isset($detalle['id'])) ? 'Ingresar fecha' : 'Modificar fecha'; ?></strong>
	                    </li>
	                </ol>
	            </div>
	        </div>
	       
	       <div class="wrapper wrapper-content animated fadeInRight">
		        <div class="row">
		            <div class="col-lg-12">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title">
		                        <h5><?php echo (empty($detalle['id'])) ? 'Crear nueva fecha' : 'Modificar fecha'; ?></h5>
		                    </div>
		                    <div class="ibox-content">
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
								
	                            <?php echo form_open(null, array('class'=>'form-horizontal')); ?>
	                            	<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
		                            <div class="form-group">
			                            <?php if (!empty($detalle['id']) && ($reunion['estado'] == (2 || 3))) { ?> 
			                            <label class="col-sm-1 control-label">Día</label>
	                                    <div class="col-sm-2">
			                                <input type="text" name="dia" class="form-control" value="<?php echo (isset($detalle['dia'])) ? $detalle['dia'] : null; ?>" readonly="true">
			                                <small>Por ej. 10/04/2020</small>
			                            </div>
			                            <label class="col-sm-1 control-label">Hora</label>
	                                    <div class="col-sm-2">
			                                <input type="text" name="hora" class="form-control" value="<?php echo (isset($detalle['hora'])) ? $detalle['hora'] : null; ?>" readonly="true">
			                                <small>Por ej. 10:30</small>
			                            </div>
			                            <label class="col-sm-1 control-label">Oficina</label>
	                                    <div class="col-sm-2">
			                                <input type="text" name="pais" class="form-control" value="<?php echo (isset($detalle['pais'])) ? $detalle['pais'] : null; ?>" readonly="true">
			                                <small>Por ej. Argentina</small>
			                            </div>
			                            <label class="col-sm-1 control-label">Estado</label>
					                    <div class="col-sm-6 col-lg-2">
				                            <input type="text" name="estado_a" class="form-control m-b" value="Bloqueada" readonly="true">
			                                <small>No puede modificarse el estado porque la fecha está asociada a una reunión. Modifique la fecha de la reunión para poder habilitar esta fecha.</small>
				                        </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>

		                            <div class="form-group">
		                                <div class="col-sm-4 col-sm-offset-2">
		                                    <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
			                            <?php } else { ?> 
			                            <label class="col-sm-1 control-label">Día</label>
	                                    <div class="col-sm-12 col-md-2">
			                                <input type="text" name="dia" class="form-control" value="<?php echo (isset($detalle['dia'])) ? $detalle['dia'] : null; ?>">
			                                <small>Por ej. 10/04/2020</small>
			                            </div>
			                            <label class="col-sm-1 control-label">Hora</label>
	                                    <div class="col-sm-12 col-md-2">
			                                <input type="text" name="hora" class="form-control" value="<?php echo (isset($detalle['hora'])) ? $detalle['hora'] : null; ?>">
			                                <small>Por ej. 10:30</small>
			                            </div>
			                            <label class="col-sm-1 control-label">Oficina</label>
	                                    <div class="col-sm-12 col-md-2">
						                    <?php echo (isset($detalle['id'])) ? form_dropdown('pais', $paises, $detalle['pais'], array('class'=>'form-control m-b')) : form_dropdown('pais', $paises, null, array('class'=>'form-control m-b')); ?></div>
			                            <label class="col-sm-1 control-label">Estado</label>
	                                    <div class="col-sm-12 col-md-2">
						                    <?php echo (isset($detalle['id'])) ? form_dropdown('estado', $estados, $detalle['estado'], array('class'=>'form-control m-b')) : form_dropdown('estado', $estados, null, array('class'=>'form-control m-b')); ?></div>
		                            </div>
		                            <div class="hr-line-dashed"></div>

		                            <div class="form-group">
		                                <div class="col-sm-4 col-sm-offset-2">
		                                    <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
		                                    <button class="btn btn-primary" type="submit">Guardar cambios</button>
			                            <?php } ?> 
		                                </div>
		                            </div>
		                        </form>
		                    </div>
		                </div>
		            </div>
		        </div>
	        </div>	        	       