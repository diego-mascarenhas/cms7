        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-xs-8 col-sm-8 col-lg-8">
                <h2>eLearning Pedidos Empresa</h2>
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li><a href="<?php echo base_url('cms-v2/elearning/pedidos/'); ?>">Pedidos</a></li>
                    <li><strong>Ingresar</strong></li>
                </ol>
            </div>
        </div>
            
        <div class="wrapper wrapper-content animated fadeInRight">
            <?php echo form_open(null, array('class'=>'form-horizontal')); ?>
            <input type="hidden" name="id_contacto" value="<?php echo $contacto['id'];?>">
            <input type="hidden" name="masivo" value="1">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
	                    <h5>Ingresar Pedido de <?php echo ($contacto['tipo_contacto'] == 0) ? 'Usuario <a href="/cms-v2/elearning/usuarios/modificar/individuos/'.$contacto['id'].'">'.$contacto['nombre'].' '.$contacto['apellido'].'</a>' : 'Empresa <a href="/cms-v2/elearning/usuarios/modificar/empresas/'.$contacto['id'].'">'.$contacto['nombre'].'</a>'; ?>
	                    </h5>
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
		                 	<div class="form-group full-width" style="float-left; margin-bottom:30px;">
			                    <label class="col-sm-1 control-label">Referencia</label>
			                    <div class="col-sm-3"><input type="text" name="observaciones" class="form-control" value="<?php echo (isset($item['observaciones'])) ? $item['observaciones']: null; ?>"></div>
			                    <label class="col-sm-1 control-label">Estado</label>
			                    <div class="col-sm-3"><?php echo (isset($item['estado'])) ? form_dropdown('estado', $estados, $item['estado'], array('class'=>'form-control m-b')) : form_dropdown('estado', $estados, null, array('class'=>'form-control m-b')); ?></div>
		                 	</div>							
					 	<div class="hr-line-dashed"></div><br>
						 	<h2>Cursos</h2>
		                 	<div class="form-group" style="float-left; width:100%;">
							<?php if(!empty($cursos)) { foreach($cursos as $lista) { ?>	
								<div class="col-lg-10 col-lg-offset-1">
				                    <h4><input type="checkbox" name="items[]" value="<?php echo $lista['id_elearning'];?>" <?php if(isset($relacionados)) { foreach($relacionados as $rela) { if($lista['id'] == $rela['id_seccion']) {echo ' checked';} } }?>>
									<?php echo $lista['titulo'];?> </h4>
								</div>
				           <?php } } else { echo 'No se encontraron resultados'; } ?>	
				           </div>  
		                 	<div class="form-group">
				                <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
				                    <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
				                    <button class="btn btn-primary" type="submit">Ingresar pedido</button>
				                </div>
		                 	</div>
	                    </div>
                    </div>
                </div>
            </div>
         </div>
	 <?=form_close()?>
    </div>        