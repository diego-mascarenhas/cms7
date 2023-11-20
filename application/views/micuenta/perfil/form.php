<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Mi cuenta</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url('micuenta'); ?>"><?php echo $this->lang->line('cms_users-mi-cuenta'); ?></a>
	                    </li>
	                    <li class="active">
	                        <strong>Perfil</strong>
	                    </li>
	                </ol>
	            </div>
	        </div>
	        
	        <div class="wrapper wrapper-content animated fadeInRight">
		        <div class="row">
		            <div class="col-lg-12">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title">
		                        <h5>Datos de mi cuenta</h5>
		                    </div>
		                    <div class="ibox-content">
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
			                        <input type="hidden" name="id_contacto" value="<?php if (!empty($valores['id_contacto'])) echo $valores['id_contacto']; ?>">
			                        <input type="hidden" name="id_empresa" value="<?php if (!empty($valores['id_empresa'])) echo $valores['id_empresa']; ?>">
			                        <input type="hidden" name="id_cuenta" value="<?php if (!empty($valores['id_cuenta'])) echo $valores['id_cuenta']; ?>">

		                            <h2>Datos de usuario</h2>
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Usuario</label>
		                                <div class="col-sm-2">
			                                <input type="text" placeholder="Nombre de Usuario (*)" value="<?php echo $this->usuario->username; ?>" disabled="true" readonly="true" class="form-control">
			                            </div>
			                            <label class="col-sm-2 control-label">Email</label>
		                                <div class="col-sm-2">
			                                <input type="email" placeholder="Email (*)" value="<?php echo $this->usuario->email; ?>" disabled="true" readonly="true" class="form-control">
			                            </div>
			                            <div class="col-sm-4">
				                            <p>El nombre de usuario y la direcci&oacute;n de correo<br>
				                            <strong>NO PUEDEN CAMBIARSE</strong><br>
				                            para evitar la p&eacute;rdida de informaci&oacute;n.</p>
				                        </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <h2>Datos de contacto</h2>
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Nombre</label>
		                                <div class="col-sm-4">
			                                <input type="text" name="nombre" id="nombre" placeholder="Nombre (*)" value="<?php if (!empty($valores['nombre']) && !empty($valores['apellido'])) echo $valores['nombre']; ?>" class="form-control">
			                            </div>
			                            <label class="col-sm-2 control-label">Apellido</label>
		                                <div class="col-sm-4">
			                                <input type="text" name="apellido" id="apellido" placeholder="Apellido (*)" value="<?php if (!empty($valores['apellido'])) echo $valores['apellido']; ?>" class="form-control">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Empresa</label>
		                                <div class="col-sm-4">
			                                <input type="text" name="empresa" id="empresa" value="<?php if (!empty($valores['empresa'])) echo $valores['empresa']; ?>" placeholder="Empresa" class="form-control" <?php if ($this->usuario->perfil != 'admin') { echo 'disabled="true" readonly="true"'; } ?>>
			                            </div>
			                            <label class="col-sm-2 control-label">Teléfono</label>
		                                <div class="col-sm-4">
			                                <input type="text" name="telefono" placeholder="Tel&eacute;fono" value="<?php if (!empty($valores['telefono'])) echo $valores['telefono']; ?>" class="form-control">
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <?php if ($this->usuario->perfil == 'admin') { ?>
									<h2>Datos de facturación</h2>
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Condición I.V.A.</label>
		                                <div class="col-sm-4">
			                                <input type="hidden" name="id_empresa_fiscal" value="<?php if (!empty($valores['id_empresa_fiscal'])) echo $valores['id_empresa_fiscal']; ?>">
			                                
			                                <select name="id_condicion_iva" id="facturacion-field" class="form-control input-md">
			                                    <option value="3" <?php if ($valores['id_condicion_iva'] == 3) echo 'selected="true"'; ?>>
			                                        Consumidor final
			                                    </option>
			
			                                    <option value="2" <?php if ($valores['id_condicion_iva'] == 2) echo 'selected="true"'; ?>>
			                                        Monotributista
			                                    </option>
			
			                                    <option value="1" <?php if ($valores['id_condicion_iva'] == 1) echo 'selected="true"'; ?>>
			                                        Responsable inscripto
			                                    </option>
			
			                                    <option value="4" <?php if ($valores['id_condicion_iva'] == 4) echo 'selected="true"'; ?>>
			                                        IVA exento
			                                    </option>
			                                </select>
			                            </div>
		                                <div class="col-sm-2">
			                                <fieldset id="cuit-field">
				                                <input type="text" name="cuit" placeholder="<?php if (empty($valores['id_condicion_iva']) || $valores['id_condicion_iva'] == 3) echo 'DNI (*)'; else echo 'CUIT (*)'; ?>" value="<?php if (!empty($valores['cuit'])) echo $valores['cuit']; ?>" class="form-control input-md required">
				                            </fieldset>
			                            </div>
			                            <div class="col-sm-4">
			                                <fieldset id="razon-field" <?php echo ($valores['id_condicion_iva'] != 1 && $valores['id_condicion_iva'] != 4) ? 'style="display:none"' : null; ?>>
				                                <input type="text" name="razon_social" placeholder="Raz&oacute;n social (*)" value="<?php if (!empty($valores['razon_social'])) echo $valores['razon_social']; ?>" class="form-control input-md required">
				                            </fieldset>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            
		                            <h2>Forma de pago</h2>
		                            <div class="form-group">
			                            <label class="col-sm-2 control-label">Forma</label>
		                                <div class="col-sm-4">
			                                <fieldset>
				                                <select name="id_forma_pago" id="metodo-field" class="form-control input-md">
				                                    <option value="13" <?php if (!empty($valores['id_forma_pago']) && $valores['id_forma_pago'] == 13) echo 'selected="true"'; ?>>
				                                        MercadoPago
				                                    </option>
				
				                                    <option value="5" <?php if (!empty( $valores['id_forma_pago']) && $valores['id_forma_pago'] == 5) echo 'selected="true"'; ?>>
				                                        D&eacute;bito bancario
				                                    </option><?php if (!empty( $valores['id_forma_pago']) && $valores['id_forma_pago'] == 16)
				                                            { ?>
				
				                                    <option value="16" selected="true">
				                                        Transferencia bancaria
				                                    </option><?php } ?>
				                                </select>
				                            </fieldset>
			                            </div>
		                                <div class="col-sm-4">
			                                <div id="datos-debito" <?php if (empty( $valores['id_forma_pago']) || $valores['id_forma_pago'] != 5) echo 'style="display:none;"'; ?>>
				                                <fieldset>
				                                    <input type="text" name="titular" id="titular" placeholder="Titular (*)" value="<?php if (!empty($valores['titular'])) echo $valores['titular']; ?>" class="form-control input-md required" <?php if (empty($valores['id_forma_pago']) || $valores['id_forma_pago'] != 5) echo 'disabled="true"'; ?>>
				                                </fieldset><br>
				
				                                <fieldset>
				                                    <input type="text" name="cuenta_documento" id="cuenta_documento" placeholder="DNI/CUIT (*)" value="<?php if (!empty($valores['cuenta_documento'])) echo $valores['cuenta_documento']; ?>" class="form-control input-md required" <?php if (empty($valores['id_forma_pago']) || $valores['id_forma_pago'] != 5) echo 'disabled="true"'; ?>>
				                                </fieldset><br>
				
				                                <fieldset>
				                                    <input type="text" name="cbu" placeholder="CBU (*)" maxlength="23" value="<?php if (!empty($valores['cbu'])) echo $valores['cbu']; ?>" class="form-control input-md required" <?php if (empty($valores['id_forma_pago']) || $valores['id_forma_pago'] != 5) echo 'disabled="true"'; ?>>
				                                </fieldset><br>
				
				                                <p class="cambiarse">El sistema de d&eacute;bito bancario es s&oacute;lo para cuentas bancarias de Argentina. No se hacen d&eacute;bitos de tarjetas de cr&eacute;dito con este medio de pago.</p>
				                            </div>
			                            </div>
		                            </div>
		                            <div class="hr-line-dashed"></div>
		                            <?php } ?>
		                            
		                            
		                            <div class="form-group">
		                                <div class="col-sm-4 col-sm-offset-2">
			                                <?php if ($this->session->has_userdata('referrer')) { ?> <a class="btn btn-white" type="submit" href="<?php echo $this->session->userdata('referrer'); ?>">Cancelar</a><?php } ?>
		                                    <button class="btn btn-primary" type="submit">Guardar cambios</button>
		                                </div>
		                            </div>
		                        </form>
		                    </div>
		                </div>
		            </div>
		        </div>
	        </div>
		
		    
		    <script type="text/javascript">
				jQuery(document).ready(function(){
					var facturacion = jQuery('#facturacion-field');
			
					facturacion.change(function(){
						if(facturacion.children('option:selected').val() != 3){
							jQuery('#cuit-field input').attr('placeholder', 'CUIT (*)');
						} else{
							jQuery('#cuit-field input').attr('placeholder', 'DNI (*)');
						}
			
						if(facturacion.children('option:selected').val() != 1 && facturacion.children('option:selected').val() != 4){
							jQuery('#razon-field').hide();
							jQuery('#razon-field input').attr('disabled', true);
						} else{
							jQuery('#razon-field').show();
							jQuery('#razon-field input').removeAttr('disabled');
						}
					});
			
					var metodo_pago = jQuery('#metodo-field');
			
					metodo_pago.change(function(){
						if(metodo_pago.children('option:selected').val() == 13){
							jQuery('#datos-debito').hide();
							jQuery('#datos-debito input').attr('disabled', true);
						} else{
							jQuery('#datos-debito').show();
							jQuery('#datos-debito input').removeAttr('disabled');
						}
					});
			
					var titular = jQuery('#titular');
			
					titular.focus(function(){
						if(!titular.val()){
							if(!jQuery('#empresa').val()){
								var nombre_value = jQuery('#nombre').val();
								var apellido_value = jQuery('#apellido').val();
								var nombre_apellido_value = $.trim(nombre_value + ' ' + apellido_value);
								titular.val(nombre_apellido_value);
							} else{
								var empresa_value = jQuery('#empresa').val();
								titular.val(empresa_value);
							}
						}
					});
			
					var cuenta_documento = jQuery('#cuenta_documento');
			
					cuenta_documento.focus(function(){
						if(!cuenta_documento.val() && jQuery('#cuit-field').is(':visible')){
							var cuit = jQuery('#cuit-field input').val();
							cuenta_documento.val(cuit);
						}
					});
				});
			</script>