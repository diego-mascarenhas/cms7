<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	
			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Administración</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('administracion/facturas'); ?>">Facturas</a>
	                    </li>
	                    <li class="active">
	                        <strong><?php echo (!isset($detalle['id'])) ? 'Ingresar Item' : 'Modificar Item'; ?></strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
	                    <?php if ($this->usuario->perfil == 'reseller') { ?>
                        	<a href="<?php echo base_url('administracion/facturas/eliminar-item/') . $detalle['id']; ?>" class="btn btn-primary btn-sm">Eliminar item</a>
						<?php } ?>
                    </div>
                </div>
	        </div>
	
	        <div class="wrapper wrapper-content animated fadeInRight">
		        <div class="row">
		            <div class="col-lg-12">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title">
		                        <h5><?php echo (empty($detalle['id'])) ? 'Ingresar item' : 'Modificar item'; ?></h5>
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
	                            	<input type="hidden" name="id_factura" value="<?php echo $detalle['id_factura']; ?>">
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Categoría</label>
			                            <div class="col-sm-4">
			                                <?php echo form_dropdown('id_categoria', $categorias_generales, $detalle['id_categoria'], 'class="form-control m-b"'); ?>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Descripción</label>
		                                <div class="col-sm-10">
			                                <?php echo form_textarea('descripcion', (isset($detalle['descripcion'])) ? $detalle['descripcion'] : null, 'class="form-control col-sm-12"'); ?>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Descuento en $</label>
		                                <div class="col-sm-10">
			                                <input type="text" name="descuento" class="form-control" value="<?php echo (isset($detalle['descuento'])) ? $detalle['descuento'] : null; ?>">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Valor sin I.V.A.</label>
		                                <div class="col-sm-10">
			                                <input type="text" name="valor" class="form-control" value="<?php echo (isset($detalle['valor'])) ? $detalle['valor'] : null; ?>">
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