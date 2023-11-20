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
	                        <strong><?php echo (!isset($detalle['id'])) ? 'Ingresar' : 'Modificar'; ?></strong>
	                    </li>
	                </ol>
	            </div>
	        </div>
	       
	       <div class="wrapper wrapper-content animated fadeInRight">
		        <div class="row">
		            <div class="col-lg-12">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title">
		                        <h5><?php echo (empty($detalle['id'])) ? 'Crear nueva renunión' : 'Modificar renunión'; ?></h5>
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
			                            <label class="col-sm-1 control-label">Nombre</label>
		                                <div class="col-sm-3">
			                                <input type="text" name="nombre" class="form-control" value="<?php echo (isset($detalle['nombre'])) ? $detalle['nombre'] : null; ?>">
			                            </div>
			                            <label class="col-sm-1 control-label">Empresa</label>
		                                <div class="col-sm-3">
			                                <input type="text" name="empresa" class="form-control" value="<?php echo (isset($detalle['empresa'])) ? $detalle['empresa'] : null; ?>">
			                            </div>
			                            <label class="col-sm-1 control-label">Email</label>
		                                <div class="col-sm-3">
			                                <input type="text" name="email" class="form-control" value="<?php echo (isset($detalle['email'])) ? $detalle['email'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>

	                            	<div class="form-group">
			                            <label class="col-sm-1 control-label">Teléfono</label>
		                                <div class="col-sm-3">
			                                <input type="text" name="telefono" class="form-control" value="<?php echo (isset($detalle['telefono'])) ? $detalle['telefono'] : null; ?>">
			                            </div>
			                            <label class="col-sm-1 control-label">País</label>
		                                <div class="col-sm-3">
			                                <input type="text" name="pais" class="form-control" value="<?php echo (isset($detalle['pais'])) ? $detalle['pais'] : null; ?>">
			                            </div>
					                    <label class="col-sm-1 control-label">Estado</label>
		                                <div class="col-sm-3">
						                    <?php echo (isset($detalle['id'])) ? form_dropdown('estado', $estados, $detalle['id_estado'], array('class'=>'form-control m-b')) : form_dropdown('estado', $estados, null, array('class'=>'form-control m-b')); ?></div>
		                            </div>
		                            <div class="hr-line-dashed"></div>

	                            	<div class="form-group">
			                            <?php if (!empty($detalle['id'])) { ?> 
				                            <input type="hidden" name="fecha_actual" value="<?php echo $detalle['id_agenda_fecha']; ?>">
						                    <div class="well well-sm" style="margin:0 34px 20px;"><h4>País de interés y fecha actual: <?php echo $detalle['oficina'].' - '.$detalle['dia'].' - '.$detalle['hora']; ?>hs. </h4></div>
			                            <?php } ?> 

			                            <label class="col-sm-2 control-label">País de interés</label>
	                                    <div class="col-sm-3">
						                    <?php echo (isset($detalle['id'])) ? form_dropdown('oficina', $paises, $detalle['oficina'], array('id'=>'oficina', 'class'=>'form-control m-b')) : form_dropdown('oficina', $paises, null, array('id'=>'oficina', 'class'=>'form-control m-b')); ?></div>
					                    <label class="col-sm-2 control-label">Fechas disponibles</label>
					                    <div class="col-sm-3">
											<select name="id_agenda_fecha" id="id_agenda_fecha" class="form-control form-control m-b">
										    	<option value="">Selecciona fecha</option>
										    </select>
					                    </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            		                            
		                            <div class="form-group">
		                                <div class="col-sm-4 col-sm-offset-2">
		                                    <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
		                                    <button class="btn btn-primary" type="submit">Guardar cambios</button>
		                                </div>
		                            </div>
		                        </form>
		                    </div>
		                </div>
		            </div>
		        </div>
	        </div>

<script>
$(document).ready(function(){
	$("#oficina").change(function() {
		$("#oficina option:selected").each(function() {
			oficina = $('#oficina').val();
			$.post("https://cms.revisionalpha.com/agenda/llena_fechas", {
				oficina : oficina
			}, function(data) {
				$("#id_agenda_fecha").html(data);
			});
		});
	})

});
</script>
