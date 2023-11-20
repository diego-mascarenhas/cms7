<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
		
			<div class="row wrapper border-bottom white-bg page-heading">
		        <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
		            <h2>Configuración</h2>
		            <ol class="breadcrumb">
		                <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('configuracion'); ?>">Configuración</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('configuracion/categorias'); ?>">Categorías</a>
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
		                        <h5><?php echo (empty($detalle['id'])) ? 'Crear nueva categoría' : 'Modificar categoría'; ?></h5>
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
			                            <label class="col-sm-2 control-label">Categoría</label>
		                                <div class="col-sm-4">
			                                <input type="text" class="form-control" name="categoria" value="<?php echo (isset($detalle['categoria'])) ? $detalle['categoria'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">Padre</label>
		                                <div class="col-sm-4">
			                                <select name="padre" class="form-control m-b">
		                                		<?php echo $categorias_generales; ?>
		                                	</select>
			                            </div>
		                            </div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Descripción</label>
		                                <div class="col-sm-10">
			                                <?php echo form_textarea('descripcion', (isset($detalle['descripcion'])) ? $detalle['descripcion'] : null, 'class="form-control col-sm-12"'); ?>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Moneda</label>
		                                <div class="col-sm-2">
			                                <?php echo form_dropdown('id_moneda', $monedas, (isset($detalle['id_moneda'])) ? $detalle['id_moneda'] : null, 'class="form-control m-b"'); ?>
			                            </div>
			                            <label class="col-sm-2 control-label">Valor</label>
		                                <div class="col-sm-2">
			                                <input type="text" name="valor" class="form-control" value="<?php echo (isset($detalle['valor']) && $detalle['valor'] > 0) ? $detalle['valor'] : null; ?>">
			                            </div>
			                            <label class="col-sm-2 control-label">Descuento (%)</label>
		                                <div class="col-sm-2">
			                                <input type="text" name="descuento" class="form-control" value="<?php echo (isset($detalle['descuento']) && $detalle['descuento'] > 0) ? $detalle['descuento'] : null; ?>">
			                            </div>
		                            </div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Frecuencia</label>
		                                <div class="col-sm-4">
			                                <?php echo form_dropdown('frecuencia', $frecuencias, (isset($detalle['frecuencia'])) ? $detalle['frecuencia'] : null, 'class="form-control m-b"'); ?>
			                            </div>
			                            <label class="col-sm-4 control-label">Convertir a pesos</label>
		                                <div class="col-sm-2">
			                                <label class="checkbox-inline"><input type="checkbox" name="convertir" value="1" <?php if (isset($detalle['convertir']) && $detalle['convertir'] == 1) echo 'checked="checked"'; ?>> Si </label>
	                                    </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Tipo</label>
		                                <div class="col-sm-4">
		                                	<?php echo form_dropdown('id_tipo', $tipos, (isset($detalle['id_tipo'])) ? $detalle['id_tipo'] : null, 'class="form-control m-b"'); ?>
			                            </div>
			                            <label class="col-sm-2 control-label">Orden</label>
		                                <div class="col-sm-4">
			                                <input type="text" class="form-control" name="orden" value="<?php echo (isset($detalle['orden'])) ? $detalle['orden'] : null; ?>">
			                            </div>
		                            </div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Características</label>
		                                <div class="col-sm-10">
			                                <?php echo form_textarea('caracteristicas', (isset($detalle['caracteristicas'])) ? $detalle['caracteristicas'] : null, 'class="form-control col-sm-12"'); ?>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Estados</label>
			                            <div class="col-sm-10">
			                            	<div class="radio radio-inline">
			                                	<input type="radio" name="estado" value="1" <?php if (isset($detalle['estado']) && $detalle['estado'] == 1) echo 'checked="checked"'; ?>>
			                                	<label> Inactivo </label>
				                            </div>
				                            <div class="radio radio-inline">
	                                        	<input type="radio" name="estado" value="2" <?php if (isset($detalle['estado']) && ($detalle['estado'] == 2)) echo 'checked="checked"'; ?>>
	                                        	<label> Activo </label>
				                            </div>
				                            <div class="radio radio-inline">
	                                        	<input type="radio" name="estado" value="3" <?php if (isset($detalle['estado']) && ($detalle['estado'] == 3)) echo 'checked="checked"'; ?>>
	                                        	<label> Público </label>
				                            </div>
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