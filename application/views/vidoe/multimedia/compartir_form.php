<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div id="content-wrapper">
            	<div class="container-fluid ">            
					<div class="row">
						<div class="col-lg-12">
							<div class="main-title">
								<h6>Compartir la categoría "<?php echo $detalle['proyecto']; ?>"</h6>
							</div>
							<hr>
						</div>
					</div>
					<?php echo form_open(null, array('class'=>'form-horizontal')); ?>
					<div class="row">
						<div class="col-lg-12">
							<input type="hidden" name="id" value="<?php echo $detalle['id']; ?>">
	                        	<?php if (isset($contactos)) { ?>
								<?php foreach($contactos as $obj) { ?>
				            	<fieldset>
									<input type="checkbox" name="relacionados[]" value="<?php echo $obj['id']; ?>" <?php if (isset($relacionar)) { foreach($relacionar as $rela) { if ($obj['id'] == $rela['id']) { echo ' checked'; } } } ?>>
				                    <label><?php echo $obj['contacto']; ?> <small> <?php if ($this->usuario->perfil == 'reseller') { ?><?php echo $obj['empresa']; ?><?php } ?></small></label>
				            	</fieldset>
								<?php } ?>
							<?php } ?>
							
						</div>
					</div>
					<hr>
					
					<div class="col-lg-12 text-center">
					    <h7>
					        <p>¿Está seguro que desea compartir la categoria <strong><?php echo $detalle['proyecto']; ?></strong> con los usuarios seleccionados?</p>
					    </h7>
					</div>
					<div class="row">
					    <div class="col-lg-12 text-center">
					        <button class="btn btn-secondary" type="submit" href="javascript:window.history.go(-1);" style="margin-bottom: 15px;">Cancelar</button>
					        <button class="btn btn-primary" type="submit" style="margin-bottom: 15px;">Asociar</button>
					    </div>
					</div>
					<?php echo form_close();?>
            	</div>
        	</div>