<style>
.tabs-container .panel-body { border-bottom:0;}
.tooltip-inner {max-width: 250px;width: 250px;}
pre  { border:1px solid #5402b2; background:#ebdff9; font-size:10px;}
pre code { white-space: pre-line; }
.form_tienda { float:left !important;}
@media(max-width:576px)
{
	.ibox-content { padding:10px;}
	.form_tienda { float:none !important;}
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
                        <strong>Pagos</strong>
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
	                        <h5>Selección opciones para Métodos de Pago</h5>
	                    </div>

	                    <div class="ibox-content form_tienda">
						<?php if(!empty($medios)) { foreach($medios as $lista) { ?>	
							<div class="form-group m-b-md pull-left full-width m-t-xs">
                               <div class="col-sm-4">
				                    <input id="checkbox<?php echo $lista['id'];?>" type="checkbox" name="relaciones[]" value="<?php echo $lista['id'];?>" <?php if(isset($item['id'])) { foreach($mediosrelacionados as $rela) { if($lista['id'] == $rela['id']) {echo ' checked';} } }?>>
                                    <label for="checkbox<?php echo $lista['id'];?>"><?php echo $lista['forma_pago']; ?></label>
			                    </div>
                               <div class="col-sm-5">
                                  <label class="col-md-6 control-label">Recargo/Descuento</label>
                               <div class="col-md-4">
				                   <select name="tipo<?php echo $lista['id'];?>" class="form-control">
				                    	<option value="0">-- Tipo --</option>
				                    	<option value="20"<?php if(isset($item['id'])) { foreach($mediosrelacionados as $rela) { if( ($lista['id'] == $rela['id']) && ($rela['tipo'] == 20)) { echo ' selected'; }} }?>>Descuento</option>
				                    	<option value="21"<?php if(isset($item['id'])) { foreach($mediosrelacionados as $rela) { if( ($lista['id'] == $rela['id']) && ($rela['tipo'] == 21)) { echo ' selected'; }} }?>>Recargo</option>
				                   </select>
                               </div>
                               </div>
                               <div class="col-sm-3">
                                <label>Porcentaje (%)</label>
			                    <input id="porcentaje<?php echo $lista['id'];?>" type="text" name="porcentaje<?php echo $lista['id'];?>" value="<?php if(isset($item['id'])) { foreach($mediosrelacionados as $rela) { if($lista['id'] == $rela['id']) { if($rela['descuento'] > 0) { $rela['porcentaje'] = $rela['descuento']; } elseif($rela['recargo'] > 0) { $rela['porcentaje'] = $rela['recargo']; } else {$rela['porcentaje'] = ''; } echo $rela['porcentaje'];} } }?>">
			                    </div>
							</div>
			           <?php } } else { echo 'No se encontraron resultados';} ?>	

			           <div class="form-group m-b-md pull-left full-width m-t-lg">
					        <label class="col-sm-2 control-label">Email para PayPal</label>
							<div class="col-sm-4">
								<input type="text" name="email_paypal" class="form-control" value="<?php echo (isset($item['email_paypal'])) ? $item['email_paypal']: null; ?>"></div>
			           </div>
			           <div class="form-group m-b-md pull-left full-width m-t-lg">
					        <label class="col-sm-2 control-label">Email para Mercado Pago</label>
							<div class="col-sm-4">
								<input type="text" name="email_MP" class="form-control" value="<?php echo (isset($item['email_MP'])) ? $item['email_MP']: null; ?>"></div>
			           </div>
			           <div class="form-group m-b-md pull-left full-width">
					        <label class="col-sm-2 control-label">ID Cliente Mercado Pago</label>
							<div class="col-sm-4">
								<input type="text" name="clienteMP" class="form-control" value="<?php echo (isset($item['clienteMP'])) ? $item['clienteMP']: null; ?>"></div>
							<label class="col-sm-2 control-label">Client Secret Mercado Pago</label>
							<div class="col-sm-4">
								<input type="text" name="claveMP" class="form-control" value="<?php echo (isset($item['claveMP'])) ? $item['claveMP']: null; ?>"></div>
					   </div>									        
			           <div class="form-group m-b-sm pull-left full-width m-t-md">
					       <div class="alert alert-info fade in">
							  <h4 class="pull-left">CREDENCIALES MERCADO PAGO</h4><br>
								<br>
								<strong>Para ver las credenciales tenés que seguir los siguientes pasos:</strong>
								<ol>
									
									<li>Ingresar en tu cuenta de Mercado Pago</li>
									<li>Ir a <a href="https://www.mercadopago.com/mla/account/credentials" target="_blank">https://www.mercadopago.com/mla/account/credentials</a></li>
									<li>Seleccionar Checkout básico y listo! Ya podés ver tu Client ID y tu Client Secret</li>
								</ol>
							</div>
			           </div>

					<div class="form-group">
						<div class="col-sm-4">
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
<!-- Fin Contenido -->
