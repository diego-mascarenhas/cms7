<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Multimedia</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('multimedia/'); ?>">Multimedia</a>
	                    </li>
	                    <li>
	                        Compartir proyecto
	                    </li>
	                    <li class="active">
	                        <strong><?php echo $detalle['proyecto']; ?></strong>
	                    </li>
	                </ol>
	            </div>
	        </div>
	        
	        <div class="wrapper wrapper-content animated fadeInRight">
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox float-e-margins">
	                        <div class="ibox-content">
	                            <div class="table-responsive">
		                            <?php echo form_open(null, array('class'=>'form-horizontal')); ?>
		                            <input type="hidden" name="id" value="<?php echo $detalle['id']; ?>">
		                                <table class="table table-striped">
		                                    <thead>
		                                    <tr>
		                                        <th class="text-center">Compartir</th>
		                                        <th>Contacto</th>
		                                        <th>Username</th>
		                                        <th class="text-center">Ultima visita</th>
		                                        <th class="text-center">Estado</th>
		                                    </tr>
		                                    </thead>
		                                    <tbody>
			                                	<?php foreach ($contactos as $obj) { ?>
			                                    <tr>
				                                    <td class="text-center"><input type="checkbox" name="relacionados[]" value="<?php echo $obj['id']; ?>" <?php if (isset($relacionar)) { foreach($relacionar as $rela) { if ($obj['id'] == $rela['id']) { echo ' checked'; } } } ?>></td>
			                                        <td><a href="<?php echo base_url('administracion/contactos/detalle/' . $obj['id']); ?>"><?php echo $obj['contacto']; ?></a></td>
			                                        <td><?php echo $obj['username']; ?></td>
			                                        <td class="text-center"><?php echo formatear_fecha($obj['ultima_visita'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></td>
					                                <td class="text-center"><span class="label <?php echo $obj['estado_ui_class']; ?>"><?php echo $obj['estado']; ?></span></td>
			                                    </tr>
												<? } ?>
		                                    </tbody>
		                                </table>
		                                <button class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</button>
							            <input type="submit" class="btn btn-primary" value="Compartir">
									</form>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>