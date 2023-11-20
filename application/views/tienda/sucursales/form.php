<link href="<?php echo base_url('assets/css/tienda.css'); ?>" rel="stylesheet" type="text/css">
		<div class="row wrapper border-bottom white-bg page-heading">
        	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
                <h2>Tienda</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('tienda/dashboard'); ?>">Home</a>
                    </li>
                    <li>
                         <a href="<?php echo base_url('tienda/tienda/mi-tienda'); ?>">Mi Tienda </a>
                    </li>
                    <li>
                         <a href="<?php echo base_url('tienda/sucursales'); ?>"><strong>Sucursales </strong></a>
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
	                        <h5><?php echo (isset($item['id'])) ? 'Modificar' : 'Crear nueva'; ?> Sucursal</h5>
	                    </div>

	                    <div class="ibox-content">
                    		<?php echo form_open(null, array('class'=>'form-horizontal')); ?>
                        	<input type="hidden" name="id_tienda" value="<?php echo $tienda['id'];?>">
                        	<input type="hidden" name="id" value="<?php if (isset($item['id'])) { echo $item['id']; } ?>">
						 	
                            <div class="form-group">
	                            <label class="col-sm-2 control-label">Nombre de la Sucursal</label>
                                <div class="col-sm-4">
	                                <input type="text" class="form-control" name="titulo" value="<?php if (isset($item['titulo'])) { echo $item['titulo']; } else { if ($this->input->post('titulo')) { echo $this->input->post('titulo'); } }?>">
	                            </div>
	                            <label class="col-sm-2 control-label">Observaciones</label>
                                <div class="col-sm-4">
	                                <input type="text" class="form-control" name="contenido1" value="<?php if (isset($item['contenido1'])) { echo $item['contenido1']; } else { if ($this->input->post('contenido1')) { echo $this->input->post('contenido1'); } }?>">
	                            </div>
                            </div>
							<div class="hr-line-dashed pull-left full-width"></div>
                            
						 	<div class="form-group m-b-md pull-left full-width m-t-sm">
							 	<label class="col-md-2 control-label">Orden</label>
							 	<div class="col-sm-4">
	                                <input type="text" class="form-control" name="orden" value="<?php if (isset($item['orden'])) { echo $item['orden']; } else { if ($this->input->post('orden')) { echo $this->input->post('orden'); } }?>"></div>
	                            <label class="col-sm-2 control-label">Estado</label>
	                            <div class="col-sm-4">
		                            <div class="radio radio-inline radio-primary">
	                                	<input type="radio" name="estado" value="2" <?php if (isset($item['estado']) && $item['estado'] == '2') echo 'checked="checked"'; ?>> <label> Activa </label>
		                            </div>
		                            <div class="radio radio-inline radio-primary">
                                    	<input type="radio" name="estado" value="1" <?php if (isset($item['estado']) && $item['estado'] == '1') echo 'checked="checked"'; ?>><label> Inactiva </label>
		                            </div>
	                            </div>
                            </div>
							<div class="hr-line-dashed pull-left full-width"></div>

						 	<div class="form-group m-b-md pull-left full-width m-t-sm">
							 	<label class="col-md-2 control-label">Teléfono fijo o para recibir llamadas</label>
							 	<div class="col-sm-4">
									<input type="text" name="telefono" class="form-control" value="<?php echo (isset($item['telefono'])) ? $item['telefono']: null; ?>"></div>
								<label class="col-md-2 control-label">Celular (de la sucursal)</label>
								<div class="col-md-4">
									<input type="text" name="celular" class="form-control" value="<?php echo (isset($item['celular'])) ? $item['celular']: null; ?>"></div>
                            </div>
							<div class="hr-line-dashed pull-left full-width"></div>

						 	<div class="form-group m-b-md pull-left full-width m-t-sm">
							 	<label class="col-md-2 control-label">Email (de la sucursal)</label>
							 	<div class="col-sm-4">
									<input type="text" name="email" class="form-control" value="<?php echo (isset($item['email'])) ? $item['email']: null; ?>"></div>
                            </div>
							<div class="hr-line-dashed pull-left full-width"></div>

						 	<div class="form-group m-b-md pull-left full-width m-t-md">
								<label class="col-md-2 control-label">Calle</label>
								<div class="col-md-4">
									<input type="text" name="domicilio" class="form-control" value="<?php echo (isset($item['domicilio'])) ? $item['domicilio']: null; ?>"></div>
								<label class="col-md-2 control-label">Número</label>
								<div class="col-md-4">
									<input type="text" name="numero" class="form-control" value="<?php echo (isset($item['numero'])) ? $item['numero']: null; ?>"></div>
						 	</div>
						 	
						 	<div class="form-group m-b-md pull-left full-width m-t-md">
								<label class="col-md-2 control-label">Localidad</label>
								<div class="col-md-2">
									<input type="text" name="localidad" class="form-control" value="<?php echo (isset($item['localidad'])) ? $item['localidad']: null; ?>"></div>
								<label class="col-md-2 control-label">Provincia</label>
								<div class="col-md-2">
									<input type="text" name="provincia" class="form-control" value="<?php echo (isset($item['provincia'])) ? $item['provincia']: null; ?>"></div>
								<label class="col-md-2 control-label">País</label>
								<div class="col-md-2">
									<?php echo (isset($item['pais'])) ? form_dropdown('pais', $paises, $item['pais'], array('class'=>'form-control m-b')) : form_dropdown('pais', $paises, null, array('class'=>'form-control m-b')); ?></div>
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
