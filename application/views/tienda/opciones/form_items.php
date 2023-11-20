<link href="<?php echo base_url('assets/css/tienda.css'); ?>" rel="stylesheet" type="text/css">
		<div class="row wrapper border-bottom white-bg page-heading">
        	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
                <h2>Tienda</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('tienda/dashboard'); ?>">Home</a>
                    </li>
                    <li>
	                    <a href="<?php echo base_url('tienda/productos'); ?>">Productos</a>
                    </li>
                    <li>
                         <a href="<?php echo base_url('tienda/opciones'); ?>"><strong>Grupos de Opciones </strong> </a>
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
	                        <h5><?php echo (isset($item['id'])) ? 'Modificar' : 'Crear nuevo'; ?> Item para <a href="<?php echo base_url('tienda/opciones/modificar/'.$grupo['id']);?>" title="<?php echo $grupo['opcion_grupo']; ?>"><?php echo $grupo['opcion_grupo']; ?></a></h5>
	                    </div>

	                    <div class="ibox-content">
                    		<?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
                        	<input type="hidden" name="id_tienda" value="<?php echo $tienda['id'];?>">
                        	<input type="hidden" name="id_opcion_grupo" value="<?php echo $grupo['id']; ?>">
                        	<input type="hidden" name="id" value="<?php if (isset($item['id'])) { echo $item['id']; } ?>">
                            <div class="form-group">
	                            <label class="col-sm-2 control-label">Nombre</label>
                                <div class="col-sm-4">
	                                <input type="text" class="form-control" name="opcion" value="<?php if (isset($item['opcion'])) { echo $item['opcion']; } else { if ($this->input->post('opcion')) { echo $this->input->post('opcion'); } }?>">
	                            </div>
	                            <label class="col-sm-2 control-label">Precio <?php echo $tienda['simbolo'];?> </label>
                                <div class="col-sm-4">
	                                <input type="text" class="form-control" name="precio" value="<?php if (isset($item['precio'])) { echo $item['precio']; } else { if ($this->input->post('precio')) { echo $this->input->post('precio'); } }?>">
	                            </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            
                            
						 	<div class="form-group m-b-md pull-left full-width m-t-sm">
	                            <label class="col-sm-2 control-label">Estado</label>
	                            <div class="col-sm-4">
		                            <div class="radio radio-inline">
	                                	<input type="radio" name="estado" value="2" <?php if (isset($item['estado']) && $item['estado'] == '2') echo 'checked="checked"'; ?>> <label> Activo </label>
		                            </div>
		                            <div class="radio radio-inline">
                                    	<input type="radio" name="estado" value="1" <?php if (isset($item['estado']) && $item['estado'] == '1') echo 'checked="checked"'; ?>><label> Inactivo </label>
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