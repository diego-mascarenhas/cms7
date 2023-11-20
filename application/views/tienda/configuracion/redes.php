<style>
.tabs-container .panel-body { border-bottom:0;}
.tooltip-inner {max-width: 250px;width: 250px;}
pre  { border:1px solid #5402b2; background:#ebdff9; font-size:10px;}
pre code { white-space: pre-line; }
</style>          
              
<link href="<?php echo base_url('assets/css/tienda.css'); ?>" rel="stylesheet" type="text/css">
		<div class="row wrapper border-bottom white-bg page-heading">
        	<div class="col-lg-12">
                <h2>Tienda</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('tienda/dashboard'); ?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('tienda/tienda/mi-tienda'); ?>">Configuración</a>
                    </li>
                    <li>
                        <strong>Redes</strong>
                    </li>
                </ol>
            </div>
            <?php echo form_open(null, array('class'=>'form-horizontal')); ?>
			<input type="hidden" name="id" value="<?php echo $item['id']; ?>">
        </div>

       	<!-- Comienzo Tabs -->
        <div class="wrapper wrapper-content animated fadeInRight">
            <!-- Titulo Mensajes -->
            <div class="row">
                <?php if ($this->input->get('error') == 1) { ?>
				<div class="col-md-12">
					<div class="alert alert-danger alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <p>No se pudieron modificar los datos.</p>
					</div>
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
	                        <h5>Redes Sociales</h5>
	                    </div>

	                    <div class="ibox-content pull-left">
						 	<div class="form-group m-b-md pull-left full-width m-t-sm">
							 	<label class="col-md-2 control-label">Facebook</label>
							 	<div class="col-sm-4">
                                    <div class="input-group">
                                    	<input type="text" name="facebook" class="form-control" value="<?php echo (isset($item['facebook'])) ? $item['facebook']: null; ?>"> <span class="input-group-btn"> <button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="URL completa, por ejemplo: https://www.facebook.com/sunegocio/" title=""> <i class="fa fa-question"></i></span>
                                    </div>
								</div>
							 	<label class="col-md-2 control-label">Twitter</label>
							 	<div class="col-sm-4">
                                    <div class="input-group">
                                    	<input type="text" name="twitter" class="form-control" value="<?php echo (isset($item['twitter'])) ? $item['twitter']: null; ?>"> <span class="input-group-btn"> <button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="URL completa, por ejemplo: https://twitter.com/sunegocio/" title=""> <i class="fa fa-question"></i></span>
                                    </div>
                               </div>
                            </div>

						 	<div class="form-group m-b-md pull-left full-width m-t-sm">
							 	<label class="col-md-2 control-label">Instagram</label>
							 	<div class="col-sm-4">
                                    <div class="input-group">
										<input type="text" name="instagram" class="form-control" value="<?php echo (isset($item['instagram'])) ? $item['instagram']: null; ?>"> <span class="input-group-btn"> <button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="URL completa, por ejemplo: https://www.instagram.com/sunegocio/" title=""> <i class="fa fa-question"></i></span>
                                    </div>
                                </div>
							 	<label class="col-md-2 control-label">Linkedin</label>
							 	<div class="col-sm-4">
                                    <div class="input-group">
										<input type="text" name="linkedin" class="form-control" value="<?php echo (isset($item['linkedin'])) ? $item['linkedin']: null; ?>"> <span class="input-group-btn"> <button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="URL completa, por ejemplo: https://www.linkedin.com/sunegocio/" title=""> <i class="fa fa-question"></i></span>
                                    </div>
                                </div>
                            </div>

						 	<div class="form-group m-b-md pull-left full-width m-t-sm">
							 	<label class="col-md-2 control-label">Youtube</label>
							 	<div class="col-sm-4">
                                    <div class="input-group">
										<input type="text" name="youtube" class="form-control" value="<?php echo (isset($item['youtube'])) ? $item['youtube']: null; ?>"> <span class="input-group-btn"> <button type="button" class="btn btn-primary m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="URL completa, por ejemplo: https://www.youtube.com/sunegocio/" title=""> <i class="fa fa-question"></i></span>
                                    </div>
                                </div>
                            </div>

							<div class="col-lg-12 p-xxs">
							<div class="form-group">
								<div class="col-sm-4">
									<a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
									<button class="btn btn-primary" type="submit">Guardar cambios</button>
								</div>
							</div>
						</div>              							
						<?php echo form_close();?>
						</div>
                      </div>
                    </div>
                </div>
            </div>
<!-- Fin Contenido -->