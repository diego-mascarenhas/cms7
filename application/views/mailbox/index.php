<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Correo</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li class="active">
	                        <strong>Correo</strong>
	                    </li>
	                </ol>
	            </div>
		    </div>
			
			<div class="wrapper wrapper-content animated fadeInRight">
		        <div class="row">
		            <div class="col-lg-2">
		                <?php include('sidebar.php'); ?>
		            </div>
		            <div class="col-lg-10 animated fadeInRight">
		            <div class="mail-box-header">
		
		                <form method="get" action="<?php echo base_url('mailbox'); ?>" class="pull-right mail-search">
		                    <div class="input-group">
		                        <input type="text" class="form-control input-sm" name="search" placeholder="Buscar..." value="<?php echo $this->input->get('search'); ?>">
		                        <div class="input-group-btn">
		                            <button type="submit" class="btn btn-sm btn-primary">
		                                Buscar
		                            </button>
		                        </div>
		                    </div>
		                </form>
		                <h2>
		                    Inbox (<?php echo $total_rows; ?>)
		                </h2>
		                <div class="mail-tools tooltip-demo m-t-md">
		                    <div class="btn-group pull-right">
<!--
		                        <button class="btn btn-white btn-sm"><i class="fa fa-arrow-left"></i></button>
		                        <button class="btn btn-white btn-sm"><i class="fa fa-arrow-right"></i></button>
-->
								<?php if (isset($paginado)) echo $paginado; ?>
		                    </div>
		                    <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="left" title="Refrescar inbox" onclick="refrescar();"><i class="fa fa-refresh"></i> Refrescar</button>
<!--
		                    <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Marcar como ledídos" onclick="marcarComoLeidos();"><i class="fa fa-eye"></i> </button>
		                    <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Marcar como importantes" onclick="marcarComoImportantes();"><i class="fa fa-exclamation"></i> </button>
-->
		                    <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Eliminar" onclick="eliminarVarios();"><i class="fa fa-trash-o"></i> </button>
		
		                </div>
		            </div>
		                <div class="mail-box">
		
		                <table class="table table-hover table-mail">
			                <tbody>
				                <tr>
					                <td class="check-mail"><input type="checkbox" id="checkall"></td>
					                <td>Asunto</td>
					                <td class="text-center">Fecha</td>
					            <tr>
					            <form id="lista">
					            <?php if (!empty($emails)) { ?>
							        <?php foreach ($emails as $email) { ?>
					                <tr class="<?php echo $email['estado_ui_class'] ?>">
					                    <td class="check-mail">
	<!-- 				                        <a href="#" onclick="seleccionar(<?php echo $email['id']; ?>)" class="check-link"><i class="fa fa-square-o"></i> </a> -->
											<input type="checkbox" name="checks[]" value="<?php echo $email['id']; ?>">
					                    </td>
					                    <td class="mail-subject">
						                    <a href="<?php echo base_url('mailbox/detalle/'); ?><?php echo $email['id']; ?>"><?php echo $email['subject']; ?></a>
						                    <span class="label label-<?php echo $email['prioridad_ui_class']; ?> pull-right"><?php echo $email['prioridad']; ?></span>
						                </td>
	<!-- 				                    <td class=""><i class="fa fa-paperclip"></i></td> -->
					                    <td class="text-right mail-date" nowrap="true"><?php echo formatear_fecha($email['enviado'], 'd-m-Y H:i', ' hs', $this->usuario->timezone); ?></td>
					                </tr>
									<?php } ?>
								<?php } ?>
					            </form>
			                </tbody>
		                </table>
		
		
		                </div>
		            </div>
		        </div>
        	</div>
        	
        	
        	<script>
	        	function refrescar() { 
					location.reload();
				}
				
				$('#checkall').change(function(){
				    if($(this).prop('checked')){
				        $('tbody tr td input[type="checkbox"]').each(function(){
				            $(this).prop('checked', true);
				        });
				    }else{
				        $('tbody tr td input[type="checkbox"]').each(function(){
				            $(this).prop('checked', false);
				        });
				    }
				});
					
			    function eliminarVarios() { 
					$.ajax( {
					    type: 'POST',
					    url: 'mailbox/eliminar-varios/',
					    data: $("#lista").serialize(),
					    success: function(data) {
					        //alert(data);
					        location.reload();
					    }
					});
				}
		    </script>
        	