<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="content-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-8 mx-auto text-center pt-4 pb-5">
	            <div class="wrapper wrapper-content">
	                <div class="middle-box text-center">
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
		                    <h6 class="font-bold">Eliminar proyecto</h6>
		                    <div class="error-desc">
								<h7><p>¿Está seguro que quiere eliminar el proyecto <strong><?php echo $detalle['proyecto']; ?></strong>?</p></h7>
								<div class="col-lg-12 text-center">
									<button class="btn btn-secondary" type="submit" href="javascript:window.history.go(-1);" style="margin-bottom: 15px;">Cancelar</button>
									<!-- <a class="btn btn-secondary" type="submit" href="javascript:window.history.go(-1);">Cancelar</a> -->
									<button class="btn btn-primary" type="submit" style="margin-bottom: 15px;">Elminar</button>
								</div>

		                    </div>
	                    </form>
	                </div>
	            </div>
			</div>
		</div>
	</div>
</div>
