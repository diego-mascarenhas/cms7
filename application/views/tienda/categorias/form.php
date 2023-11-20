<link href="<?php echo base_url('assets/css/tienda.css'); ?>" rel="stylesheet" type="text/css">
		<div class="row wrapper border-bottom white-bg page-heading">
        	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
                <h2>Tienda</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('tienda/dashboard'); ?>">Home</a>
                    </li>
                    <li>
                         <a href="<?php echo base_url('tienda/productos'); ?>">Productos </a>
                    </li>
                    <li>
                         <a href="<?php echo base_url('tienda/categorias'); ?>"><strong>Categorías </strong> </a>
                    </li>
                </ol>
            </div>
        </div>

        <div class="wrapper wrapper-content animated fadeInRight">
            <!-- Titulo Mensajes -->
            <div class="row">
                <?php if (validation_errors()) : ?>
				<div class="col-md-12">
					<div class="alert alert-danger" role="alert"><?php echo validation_errors(); ?></div>
				</div>
				<?php endif; ?>
				<?php if (isset($error)) : ?>
				<div class="col-md-12">
					<div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
				</div>
				<?php endif; ?>
            </div>

            <div class="row">
	            <div class="col-lg-12">
	                <div class="ibox float-e-margins">
	                    <div class="ibox-title">
	                        <h5><?php echo (isset($item['id'])) ? 'Modificar' : 'Crear nueva'; ?> Categoría</h5>
	                    </div>

	                    <div class="ibox-content">
                    		<?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
                        	<input type="hidden" name="id_tienda" value="<?php echo $tienda['id'];?>">
                        	<?php echo ($this->input->get('productos') == 1) ? '<input type="hidden" name="productos" value="1">' : '';?>
                        	<input type="hidden" name="id" value="<?php if (isset($item['id'])) { echo $item['id']; } ?>">
                            <div class="form-group">
	                            <label class="col-sm-2 control-label">Categoría</label>
                                <div class="col-sm-4">
	                                <input type="text" class="form-control" name="categoria" value="<?php if (isset($item['categoria'])) { echo $item['categoria']; } else { if ($this->input->post('categoria')) { echo $this->input->post('categoria'); } }?>">
	                            </div>
	                            <label class="col-sm-2 control-label">Observaciones</label>
                                <div class="col-sm-4">
	                                <input type="text" class="form-control" name="observaciones" value="<?php if (isset($item['observaciones'])) { echo $item['observaciones']; } else { if ($this->input->post('observaciones')) { echo $this->input->post('observaciones'); } }?>">
	                            </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            
                            
						 	<div class="form-group m-b-md pull-left full-width m-t-sm">
			                    <div class="col-sm-2"></div>
			                    <div class="col-sm-4">
			                    	<div class="ibox-title bg-muted"><h5>Imagen Categoría</h5></div>
									<div class="ibox-content caja-imagen-tienda">
										<?php if(!empty($item['imagen'])) { ?>
		                            	<p>Imagen Actual</p>
		                            	<img src="<?php echo base_url('/multimedia/thumbs/'.$item['imagen']);?>" title="<?php echo $item['categoria'];?>" alt="<?php echo $item['categoria'];?>" style="height:100px;float: left;padding-bottom: 24px;padding-right: 25px;"/>
		                            <?php } ?>
										<br><br>
                                      <input type="file" name="imagen" class="form-control">

									</div>
			                    </div>

	                            <label class="col-sm-1 control-label">Estado</label>
	                            <div class="col-sm-2">
		                            <div class="radio radio-inline">
	                                	<input type="radio" name="estado" value="3" <?php if (isset($item['estado']) && $item['estado'] == '3') echo 'checked="checked"'; ?>> <label> Activo </label>
		                            </div>
		                            <div class="radio radio-inline">
                                    	<input type="radio" name="estado" value="1" <?php if (isset($item['estado']) && $item['estado'] == '1') echo 'checked="checked"'; ?>><label> Inactivo </label>
		                            </div>
	                            </div>
	                            <label class="col-sm-2 control-label">Mostrar en el Menú</label>
	                            <div class="col-sm-1">
		                            <div class="checkbox checkbox-inline">
	                                	<input type="checkbox" name="delivery" value="1" <?php if (isset($item['delivery']) && $item['delivery'] == '1') echo 'checked="checked"'; ?>> <label>  </label>
		                            </div>
	                            </div>

                            </div>
							<div class="hr-line-dashed pull-left full-width"></div>
                    
                            		                            
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