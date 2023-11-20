<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<style>.wizard > .steps > ul > li {width: 20%;}</style>
	
			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Mailer</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('emailer/newsletters'); ?>">Mensajes</a>
	                    </li>
	                    <li class="active">
	                        <strong>Crear</strong>
	                    </li>
	                </ol>
	            </div>
	        </div>
		   
			<!-- wizard -->
			<div class="wrapper wrapper-content animated fadeInRight">
				<div class="row">
					<div class="col-lg-12">
						<div class="ibox-content">
							<h2><?php echo (empty($detalle['id'])) ? 'Crear nuevo mensaje' : 'Modificar mensaje'; ?></h2><br>
							<form id="form" action="#" class="wizard-big">
								<!-- primer pestaña -->
								<h1>Tipo de envío</h1> 
								<fieldset>
									<h2>Configurar tipo de envío</h2><br>
									<div class="row">
										<div class="col-lg-6">
											<div class="form-group">
												<label class="col-sm-6 control-label">Tipo de envío<br/><small class="text-navy">Estas son las opciones</small></label>
												<div class="col-sm-6 control-label">
													<div class="radio">
														<input type="radio" id="check1" name="customRadio1" required onchange="javascript:showContent()">
														<label class="custom-control-label" for="check1"><strong>Email</strong></label>
													</div>
													<div class="radio">
														<input type="radio" id="check2" name="customRadio1" class="custom-control-input"  onchange="javascript:showContent()">
														<label class="custom-control-label" for="check2"><strong>WhatsApp</strong></label>
													</div>
													<div class="radio">
														<input type="radio" id="check3" name="customRadio1" class="custom-control-input"  onchange="javascript:showContent()">
														<label class="custom-control-label" for="check3"><strong>Ambas opciones</strong></label>
													</div>
												</div>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="form-group" id="contentAmbas" style="display: none;">
												<div class="col-sm-6 control-label">
													<div class="radio">
														<input type="radio" id="check3A" name="customRadio2" class="custom-control-input" checked>
														<label class="custom-control-label" for="check3A"><strong>Prioridad Email</strong></label>
													</div>
													<div class="radio">
														<input type="radio" id="check3B" name="customRadio2" class="custom-control-input">
														<label class="custom-control-label" for="check3B"><strong>Prioridad WhatsApp</strong></label>
													</div>
													<div class="radio">
														<input type="radio" id="check3C" name="customRadio2" class="custom-control-input">
														<label class="custom-control-label" for="check3C"><strong>Ambas opciones</strong></label>
													</div>
												</div>
											</div>
										</div>
									</div>
								</fieldset>
								<!-- segunda pestaña -->
								<h1>Datos de envío</h1>
								<fieldset>
									<h2>Datos de envío</h2>
									<div class="row">
										<div id="mailOption" style="display: none;">
											<div class="col-lg-6">
												<div class="form-group">
													<label>Nombre Remitente *</label>
													<input id="nombreRemitente" name="nombreRemitente" type="text" class="form-control required" >
												</div>
												<div class="form-group">
													<label>Asunto *</label>
													<input id="asunto" name="asunto" type="text" class="form-control required"> 
												</div>
											</div>
											<div class="col-lg-6">
												<div class="form-group">
													<label>Email Remitente *</label>
													<input id="emailRemitente" name="emailRemitente" type="text" class="form-control required email" >
												</div>
												<div class="form-group">
													<label>Email Respuesta  *</label>
													<input id="emailRespuesta" name="emailRespuesta" type="text" class="form-control required email" >
												</div>
											</div>
										</div>
									</div>
									<div class="row">
											<div id="waOption" style="display: none;">
												<div class="col-lg-6">
													<div class="form-group">
														<label>Número de WhatsApp para enviar *</label>
														<input id="wa" name="wa" type="text" class="form-control">
													</div>
												</div>
												<div class="col-lg-6">
													<div class="form-group">
														<button class="btn btn-primary btn-sm" style="margin-top:25px">Cambiar número</button>
													</div>
												</div>
											</div>
									</div>
								</fieldset>
								<!-- tercer pestaña -->
								<h1>Crear mensaje</h1>
								<fieldset>
									<div id="mailP3" style="display:none" >
										<div class="row">
											<div class="form-group">
												<label class="col-sm-2 control-label">Seleccione una Plantilla</label>
												<div class="col-sm-4">
													<?php echo form_dropdown('id_template', $templates, (isset($detalle['id_template'])) ? $detalle['id_template'] : null, 'class="form-control m-b"'); ?>
												</div>
												<div class="col-sm-4">
													<a href="<?php echo base_url('emailer/templates/ingresar/'); ?>" class="btn btn-primary btn-sm">Ingresar plantilla</a>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="form-group">
												<br>
												<label class="col-sm-1 control-label">Ingresar URL</label>
												<input class="col-sm-1" type="checkbox" class="checkbox">
												<div class="col-sm-10" style="text-align:center">
													<input id="url" name="url" type="text" class="form-control">
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="form-group">
											<br>
											<label class="col-sm-2 control-label">Mensaje</label>
											<div class="col-sm-10">
												<textarea name="mensaje" cols="40" rows="7" class="form-control ckeditor col-sm-12"></textarea>
											</div>
										</div>
									</div>
								</fieldset>
								<!-- cuarta pestaña -->
								<h1>Destinatarios</h1>
								<fieldset>
									<h2>Destinatarios</h2>
									<div class="row">
											<div class="form-group">
												<label class="col-sm-2 control-label">Seleccione una Lista</label>
												<div class="col-sm-4">
													<?php echo form_dropdown('id_lista', $listas, (isset($detalle['id_lista'])) ? $detalle['id_lista'] : null, 'class="form-control m-b"'); ?>
												</div>
												<div class="col-sm-4">
													<a href="<?php echo base_url('emailer/listas/ingresar/'); ?>" class="btn btn-primary btn-sm">Ingresar lista</a>
												</div>
											</div>
										</div>
								</fieldset>
								<!-- quinta pestaña -->
								<h1>Envío</h1>
								<fieldset>
									<h2>Envío de prueba</h2>
									<div class="row">
										<div class="form-group">
											<label class="col-sm-4 control-label">Escriba una dirección de correo válida para enviar una prueba de este mensaje</label>
											<div class="col-sm-4">
											<input id="pruebamail" name="name" type="text" class="form-control">
											</div>
											<div class="col-sm-4">
											<button type="button" class="btn btn-danger btn-sm btn-w-m">Enviar</button>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="form-group">
											<div class="col-sm-4">
												<label class="control-label">Envío rápido sin trackeo de recepción</label>
											</div>
											<div class="col-sm-4">
											<div class="radio radio-inline">
												<input type="radio" id="trackeoSi" name="radioTrackeo" class="custom-control-input" required>
														<label class="custom-control-label" for="trackeoSi"><strong>Si</strong></label>
													</div>
													<div class="radio radio-inline">
														<input type="radio" id="trackeoNo" name="radioTrackeo" class="custom-control-input">
														<label class="custom-control-label" for="trackeoNo"><strong>No</strong></label>
													</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="form-group">
											<div class="col-sm-4">
												<label class="control-label" for="tipoEnvio">Tipo de envío</label>
											</div>
											<div class="col-sm-4">
												<select id="statusED" class="form-control">
													<option>Elegir opción</option>
													<option value="estatico">Estático</option>
													<option value="dinamico">Dinámico</option>
												</select>
											</div>
										</div>
									</div>
									<div class="row output" >
										<div id="estatico" class="estadosED" style="display:none">
											<div class="form-group">
												<br>
												<label class="col-sm-4 control-label">Programar envío</label>
												<div class="col-sm-4 input-daterange">
													<input type="text" class="form-control" name="desde" value="<?php echo formatear_fecha($detalle['desde'], 'd-m-Y', null, $this->usuario->timezone); ?>">
												</div>
												<div class="col-sm-4">
													<button type="button" class="btn btn-danger btn-sm btn-w-m">Enviar</button>
												</div>
											</div>
										</div>
										<div id="dinamico" class="estadosED" style="display:none">
											<div class="form-group">
												<br>
												<label class="col-sm-4 control-label">Programar envío</label>
												<div class="col-sm-4 input-daterange">
													<span>Desde</span>
													<input type="text" class="form-control" name="desde" value="<?php echo formatear_fecha($detalle['desde'], 'd-m-Y', null, $this->usuario->timezone); ?>">
													<button type="button" class="btn btn-danger btn-sm btn-w-m" style="margin-top:10px">Enviar</button>
												</div>
												<div class="col-sm-4">
													<span>hasta</span>
													<input type="text" class="form-control" name="hasta" value="<?php echo formatear_fecha($detalle['hasta'], 'd-m-Y', null, $this->usuario->timezone); ?>">
												</div>
											</div>
										</div>
									</div>
								</fieldset>
							</form>
						</div>
					</div>
				</div>
			</div>
    
			<!-- Jquery Validate -->
			<script src="<?php echo base_url('assets/js/plugins/validate/jquery.validate.min.js'); ?>"></script>
			
			<!-- Steps -->
			<script src="<?php echo base_url('assets/js/plugins/steps/jquery.steps.min.js'); ?>"></script>

			<!-- iCheck -->
			<script src="<?php echo base_url('assets/js/plugins/iCheck/icheck.min.js'); ?>"></script>

			
			

			<!-- mostrar opciones -->
            <script type="text/javascript">
				function showContent() {
					element = document.getElementById("contentAmbas");
					element1 = document.getElementById("mailOption");
					element2 = document.getElementById("waOption");
					check1 = document.getElementById("check1");
					check2 = document.getElementById("check2");
					check3 = document.getElementById("check3");
					pes3email = document.getElementById("mailP3");
					if (check1.checked) {
						element.style.display='none';
						element1.style.display='block';
						element2.style.display='none';
						pes3email.style.display='block';
					};
					if (check2.checked) {
						element.style.display='none';
						element1.style.display='none';
						element2.style.display='block';
						pes3email.style.display='none';
					};
					if (check3.checked) {
						element.style.display='block';
						element1.style.display='block';
						element2.style.display='block';
						pes3email.style.display='block';
					};
				}
			</script>

			<script>

				$(document).ready(function(){

					$(function() {
						$('#statusED').change(function(){
							$('.estadosED').hide();
							$('#' + $(this).val()).show();
						});
					});

					$("#wizard").steps();
					$("#form").steps({
						bodyTag: "fieldset",
						labels: 
						{
							finish: "Terminar",
							next: "Siguiente",
							previous: "Anterior",
							cancel: "Cancelar"
						},

						onStepChanging: function (event, currentIndex, newIndex)
						{
							// Always allow going backward even if the current step contains invalid fields!
							if (currentIndex > newIndex)
							{
								return true;
							}

							var form = $(this);

							// Clean up if user went backward before
							if (currentIndex < newIndex)
							{
								// To remove error styles
								$(".body:eq(" + newIndex + ") label.error", form).remove();
								$(".body:eq(" + newIndex + ") .error", form).removeClass("error");
							}

							// Disable validation on fields that are disabled or hidden.
							form.validate().settings.ignore = ":disabled,:hidden";

							// Start validation; Prevent going forward if false
							return form.valid();
						},
						onStepChanged: function (event, currentIndex, priorIndex)
						{

							// Suppress (skip) "Warning" step if the user is old enough and wants to the previous step.
							if (currentIndex === 2 && priorIndex === 3)
							{
								$(this).steps("previous");
							}
						},
						onFinishing: function (event, currentIndex)
						{
							var form = $(this);

							// Disable validation on fields that are disabled.
							// At this point it's recommended to do an overall check (mean ignoring only disabled fields)
							form.validate().settings.ignore = ":disabled";

							// Start validation; Prevent form submission if false
							return form.valid();
						},
						onFinished: function (event, currentIndex)
						{
							var form = $(this);

							// Submit form input
							form.submit();
						}
					}).validate({
								errorPlacement: function (error, element)
								{
									element.before(error);
								},
								rules: {
									confirm: {
										equalTo: "#password"
									}
								}
					});

				});

    </script>

			<script>
				jQuery.extend(jQuery.validator.messages, {
					required: "Este campo es requerido",
					email: "Ingrese un email válido",
				});
			</script>

