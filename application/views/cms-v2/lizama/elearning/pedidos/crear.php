        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-xs-8 col-sm-8 col-lg-8">
                <h2>eLearning Pedidos</h2>
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li><a href="<?php echo base_url('cms-v2/elearning/pedidos/'); ?>">Pedidos</a></li>
                    <li><strong>Crear Nuevo Pedido</strong></li>
                </ol>
            </div>
        </div>
            
        <div class="wrapper wrapper-content animated fadeInRight">
            <?php echo form_open(null, array('class'=>'form-horizontal', 'id'=>'form-pedido')); ?>
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
	                    <h5>Crear Nuevo Pedido con Lista de Contactos</h5>
                    </div>
                    <div class="ibox-content" style="min-height:140px; height:auto; float:left; padding-bottom:25px;">
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
	                    <div class="col-sm-12">
						 	<h2>Datos del pedido</h2>
		                 	<div class="form-group full-width" style="float:left; margin-bottom:30px;">
			                    <label class="col-sm-2 control-label">Seleccionar Contacto/Empresa *</label>
			                    <div class="col-sm-4">
			                    	<select name="id_contacto" id="id_contacto" class="form-control m-b" required>
			                    		<option value="">-- Seleccionar --</option>
			                    		<optgroup label="Empresas">
				                    		<?php if(!empty($empresas)) { foreach($empresas as $empresa) { ?>
				                    			<option value="<?php echo $empresa['id'];?>" <?php echo (isset($item['id_contacto']) && $item['id_contacto'] == $empresa['id']) ? 'selected' : '';?>>
				                    				<?php echo $empresa['razon_social'];?>
				                    			</option>
				                    		<?php } } ?>
			                    		</optgroup>
			                    		<optgroup label="Individuos">
				                    		<?php if(!empty($individuos)) { foreach($individuos as $individuo) { ?>
				                    			<option value="<?php echo $individuo['id'];?>" <?php echo (isset($item['id_contacto']) && $item['id_contacto'] == $individuo['id']) ? 'selected' : '';?>>
				                    				<?php echo $individuo['contacto'];?> (<?php echo $individuo['email'];?>)
				                    			</option>
				                    		<?php } } ?>
			                    		</optgroup>
			                    	</select>
			                    </div>
			                    <label class="col-sm-1 control-label">Referencia</label>
			                    <div class="col-sm-2"><input type="text" name="observaciones" class="form-control" value="<?php echo (isset($item['observaciones'])) ? $item['observaciones']: null; ?>"></div>
			                    <label class="col-sm-1 control-label">Estado</label>
			                    <div class="col-sm-2"><?php echo (isset($item['estado_pedido'])) ? form_dropdown('estado_pedido', $estados_pedido, $item['estado_pedido'], array('class'=>'form-control m-b')) : form_dropdown('estado_pedido', $estados_pedido, 7, array('class'=>'form-control m-b')); ?></div>
		                 	</div>
		                 	
		                 	<div id="contactos-empresa-container" class="form-group full-width" style="float:left; margin-bottom:30px; display:none;">
			                    <div class="col-sm-12">
			                    	<div class="hr-line-dashed"></div>
			                    	<h3>Usuarios de la Empresa</h3>
			                    	<p class="text-muted">Seleccione los usuarios que tendrán acceso al curso:</p>
			                    	<div id="lista-contactos-empresa" class="m-t-md">
			                    		<!-- Se cargará dinámicamente via AJAX -->
			                    	</div>
			                    </div>
		                 	</div>
		                 	
					 	<div class="hr-line-dashed"></div><br>
						 	<h2>Cursos</h2>
		                 	<div class="form-group" style="float-left; width:100%;">
							<?php if(!empty($cursos)) { foreach($cursos as $lista) { ?>	
								<div class="col-lg-10 col-lg-offset-1">
				                    <h4><input type="checkbox" name="items[]" value="<?php echo $lista['id_elearning'];?>" <?php if(isset($item['items']) && in_array($lista['id_elearning'], $item['items'])) { echo 'checked'; } ?>>
									<?php echo $lista['titulo'];?> </h4>
								</div>
				           <?php } } else { echo 'No se encontraron resultados'; } ?>	
				           </div>  
		                 	<div class="form-group">
				                <div class="col-xs-12 col-sm-12 col-lg-12" style="margin-top:34px; text-align:right;">
				                    <a class="btn btn-white" type="submit" href="<?php echo base_url('cms-v2/elearning/pedidos/');?>">Cancelar</a>
				                    <button class="btn btn-primary" type="submit">Crear pedido</button>
				                </div>
		                 	</div>
	                    </div>
                    </div>
                </div>
            </div>
         </div>
	 <?=form_close()?>
    </div>

<script src="<?php echo base_url('assets/js/jquery-3.1.1.min.js'); ?>"></script>
<script>
$(document).ready(function() {
	$('#id_contacto').change(function() {
		var id_contacto = $(this).val();
		
		if(id_contacto) {
			$.ajax({
				url: '<?php echo base_url('cms-v2/elearning/pedidos/get_contactos_empresa'); ?>',
				type: 'POST',
				data: {id_contacto: id_contacto},
				dataType: 'json',
				success: function(response) {
					if(response.success && response.es_empresa) {
						var html = '';
						if(response.contactos && response.contactos.length > 0) {
							html += '<div class="row">';
							html += '<div class="col-sm-12"><label><input type="checkbox" id="seleccionar-todos"> Seleccionar Todos</label></div>';
							html += '</div>';
							html += '<div class="row m-t-sm">';
							$.each(response.contactos, function(index, contacto) {
								html += '<div class="col-sm-6 col-md-4">';
								html += '<div class="checkbox">';
								html += '<label>';
								html += '<input type="checkbox" name="contactos_seleccionados[]" value="' + contacto.id + '" class="contacto-checkbox">';
								html += ' ' + contacto.contacto + ' (' + contacto.email + ')';
								html += '</label>';
								html += '</div>';
								html += '</div>';
							});
							html += '</div>';
						} else {
							html = '<div class="alert alert-warning">Esta empresa no tiene usuarios asociados. Puede agregarlos después de crear el pedido.</div>';
						}
						$('#lista-contactos-empresa').html(html);
						$('#contactos-empresa-container').show();
						
						$('#seleccionar-todos').on('change', function() {
							$('.contacto-checkbox').prop('checked', $(this).prop('checked'));
						});
					} else {
						$('#contactos-empresa-container').hide();
						$('#lista-contactos-empresa').html('');
					}
				},
				error: function() {
					alert('Error al cargar los contactos de la empresa.');
				}
			});
		} else {
			$('#contactos-empresa-container').hide();
			$('#lista-contactos-empresa').html('');
		}
	});
});
</script>
