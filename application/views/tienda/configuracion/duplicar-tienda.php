<link href="<?php echo base_url('assets/css/tienda.css'); ?>" rel="stylesheet" type="text/css">
	<div class="row wrapper border-bottom white-bg page-heading">
    	<div class="col-lg-12">
            <h2>Tienda</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url('administracion/empresas'); ?>">Empresas</a>
                </li>
                <li>
                    <strong>Duplicar Tienda</strong>
                </li>
            </ol>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
			<?php if ($this->session->flashdata('tipo')) { ?>
			<div class="col-md-12">
				<div class="alert <?php echo($this->session->flashdata('tipo') == '0') ? 'alert-danger': 'alert-success';?> alert-dismissable" role="alert">
	            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
	            <?php echo $this->session->flashdata('mensaje'); ?></div>
			</div>
			<?php } ?>

            <?php if ($this->input->get('ok') == 1) { ?>
			<div class="col-md-12">
				<div class="alert alert-success alert-dismissable">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <p>El contenido fue modificado con &eacute;xito.</p>
				</div>
			</div>
            <?php } ?>
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
	                        <h5>Copiar categorías y productos de tienda</h5>
	                    </div>

	                    <div class="ibox-content pull-left full-width">
			            <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
						 	<div class="form-group m-b-md pull-left full-width m-t-sm">
							 	<label class="col-md-2 control-label">Tienda Original</label>
							 	<div class="col-sm-4"><?php echo form_dropdown('origen', $listado, set_value('origen'), array('class'=>'form-control m-b')); ?></div>
							 	<label class="col-md-2 control-label">Copiar a Tienda</label>
							 	<div class="col-sm-4"><?php echo form_dropdown('destino', $listado, set_value('destino'), array('class'=>'form-control m-b')); ?></div>
                            </div>
							<div class="form-group">
								<div class="col-sm-12 text-center">
									<a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
									<button class="btn btn-primary" type="submit">Guardar cambios</button>
								</div>
							</div>
						<?php echo form_close();?>
					</div>              							
                  </div>
                </div>
            </div>
        </div>