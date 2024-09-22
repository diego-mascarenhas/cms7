<style>
.tabs-container .panel-body { border-bottom:0;}
.tooltip-inner {max-width: 250px;width: 250px;}
pre  { border:1px solid #5402b2; background:#ebdff9; font-size:10px;}
pre code { white-space: pre-line; }
.valor_pd { width:70px;}
@media(max-width:768px) {
.valor_pd { width:auto; margin-top:10px;}
}
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
                        <strong>Envios </strong>
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
	                        <h5>Selección opciones para envíos</h5>
	                    </div>

	                    <div class="ibox-content form_tienda">
						<?php if(!empty($envios)) { ?>	
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover">
								<thead>
		                    		<tr>
			                    		<th>Tipo</th>
			                    		<th>Observación</th>
			                    		<th>Recargo/Descuento</th>
			                    		<th>Valor</th>
			                    		<th>Monto mínimo</th>
			                    		<?php if($item['bruler_id'] > 0) { ?>
			                    		<th>ID Bruler</th>
			                    		<?php } ?>
		                    		</tr>
		                    	</thead>

                            <?php foreach($envios as $listaenvios) { ?>	
	                    	<tr>
	                    		<td>
									<div class="checkbox checkbox-primary">
					                    <input id="checkbox<?php echo $listaenvios['id'];?>" type="checkbox" name="relacionesenvios[]" value="<?php echo $listaenvios['id'];?>" <?php if(isset($item['id'])) { foreach($enviosrelacionados as $relaenvios) { if($listaenvios['id'] == $relaenvios['id']) {echo ' checked';} } }?>>
                                        <label for="checkbox<?php echo $listaenvios['id'];?>"><?php echo $listaenvios['medio_envio']; ?></label>
									</div>
								</td>
	                    		<td>
	                                <input type="text" name="observaciones<?php echo $listaenvios['id'];?>" value="<?php if(isset($item['id'])){ foreach($enviosrelacionados as $relaenvios) { if($listaenvios['id'] == $relaenvios['id']) { echo ($relaenvios['observaciones']) ? $relaenvios['observaciones'] : null ; } } }?>">
                                </td>
	                    		<td>
				                   <select name="envio<?php echo $listaenvios['id'];?>" class="form-control" style="width:100px;">
				                    	<option value="0">-- Tipo --</option>
				                    	<option value="20"<?php if(isset($item['id'])) { foreach($enviosrelacionados as $relaenvios) { if( ($listaenvios['id'] == $relaenvios['id']) && ($relaenvios['tipo'] == 20)) { echo ' selected'; }} }?>>Descuento</option>
				                    	<option value="21"<?php if(isset($item['id'])) { foreach($enviosrelacionados as $relaenvios) { if( ($listaenvios['id'] == $relaenvios['id']) && ($relaenvios['tipo'] == 21)) { echo ' selected'; }} }?>>Recargo</option>
				                   </select>
                                </td>
	                    		<td>
                                 <label><?php echo ($listaenvios['id'] == 1) ? 'Porcentaje(%) ' : 'Valor en $ ';?></label>
			                     <input class="valor_pd" id="valor<?php echo $listaenvios['id'];?>" type="text" name="valor<?php echo $listaenvios['id'];?>" value="<?php if(isset($item['id'])) { foreach($enviosrelacionados as $relaenvios) { if($listaenvios['id'] == $relaenvios['id']) { if($relaenvios['descuento'] > 0) { $relaenvios['valor'] = $relaenvios['descuento']; } elseif($relaenvios['recargo'] > 0) { $relaenvios['valor'] = $relaenvios['recargo']; } else {$relaenvios['valor'] = ''; } echo $relaenvios['valor'];} } }?>">
	                    		<td>
			                     <input class="valor_pd" id="mmc<?php echo $listaenvios['id'];?>" type="text" name="mmc<?php echo $listaenvios['id'];?>" value="<?php if(isset($item['id'])) { foreach($enviosrelacionados as $relaenvios) { if($listaenvios['id'] == $relaenvios['id']) { echo($relaenvios['mmc']) ? $relaenvios['mmc']: null;} } }?>">
			                    </td>
			                   <?php if($item['bruler_id'] > 0) { ?>
                               <td>
				                    <input id="id_bruler<?php echo $listaenvios['id'];?>" type="text" name="id_bruler<?php echo $listaenvios['id'];?>" value="<?php if(isset($item['id'])) { foreach($enviosrelacionados as $rela) { if($listaenvios['id'] == $rela['id'])  { echo $rela['id_bruler']; } } } ?>">
                               </td>
			                   <?php } ?>
							</tr>
			           <?php } ?> 
			           </table>
			           <?php } else { echo 'No se encontraron resultados';} ?>	

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
        </div>
<!-- Fin Contenido -->
