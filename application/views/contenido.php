<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Eventos</h2>

                    <ol class="breadcrumb">
                        <li><a href="/contenido/">Home</a></li>

                        <li><a>Eventos</a></li>

                        <li class="active"><strong>Tercer Nivel Item 1</strong></li>
                    </ol>
                </div>

                <div class="col-lg-2"></div>
            </div>

            <div class="wrapper wrapper-content animated fadeInRight">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox">
                            <div class="ibox-title">
                                <h5>Edición</h5>

                                <div class="ibox-tools">
                                    <a class="collapse-link"></a> <a class="dropdown-toggle" data-toggle="dropdown" href="#"></a>

                                    <ul class="dropdown-menu dropdown-user">
                                        <li><a href="#">Config option 1</a></li>

                                        <li><a href="#">Config option 2</a></li>
                                    </ul><a class="close-link"></a>
                                </div>
                            </div>

                            <div class="ibox-content">
                                <h2>Tercer Nivel Item 1</h2>

                                <p>Ejemplo de validación de datos.</p>

                                <form id="form" action="#" class="wizard-big">
                                    <h1>Categoría</h1>

                                    <fieldset>
                                        <h2>Información de la categoría</h2>

                                        <div class="row">
                                            <div class="col-lg-8">
                                                <div class="form-group">
                                                    <label>Categoría *</label> <input id="userName" name="userName" type="text" class="form-control required">
                                                </div>
                                            </div>

                                            <div class="col-lg-4">
                                                <div class="text-center">
                                                    <div style="margin-top: 20px"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <h1>Datos</h1>

                                    <fieldset>
                                        <h2>Formulario de datos</h2>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>Título *</label> <input id="name" name="name" type="text" class="form-control required">
                                                </div>

                                                <div class="form-group">
                                                    <label>Texto *</label> <input id="surname" name="surname" type="text" class="form-control required">
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <h1>Alertas</h1>

                                    <fieldset>
                                        <div class="text-center" style="margin-top: 120px">
                                            <h2>Listo!</h2>
                                        </div>
                                    </fieldset>

                                    <h1>Publicar</h1>

                                    <fieldset>
                                        <h2>Publicación del evento</h2><input id="acceptTerms" name="acceptTerms" type="checkbox" class="required"> <label for="acceptTerms">¿Desea publicar el evento?</label>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
    
		    <!-- Steps -->
		    <script src="<?php echo base_url('assets/js/plugins/staps/jquery.steps.min.js'); ?>" type="text/javascript"></script>
		    
		    <!-- Jquery Validate -->
		    <script src="<?php echo base_url('assets/js/plugins/validate/jquery.validate.min.js'); ?>" type="text/javascript"></script>
		    
		    <script type="text/javascript">
		    $(document).ready(function(){
		            $("#wizard").steps();
		            $("#form").steps({
		                bodyTag: "fieldset",
		                onStepChanging: function (event, currentIndex, newIndex)
		                {
		                    // Always allow going backward even if the current step contains invalid fields!
		                    if (currentIndex > newIndex)
		                    {
		                        return true;
		                    }
		
		                    // Forbid suppressing "Warning" step if the user is to young
		                    if (newIndex === 3 && Number($("#age").val()) < 18)
		                    {
		                        return false;
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
		                    // Suppress (skip) "Warning" step if the user is old enough.
		                    if (currentIndex === 2 && Number($("#age").val()) >= 18)
		                    {
		                        $(this).steps("next");
		                    }
		
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