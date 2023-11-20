<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

	<div class="wrapper wrapper-content">
		
		<div class="row">
            <div class="col-lg-3">
	            <?php if ($smtps['mails_en_cola'] > 8000) { ?>
                <div class="widget style1 red-bg">
	                <a href="<?php echo base_url('emailer/smtps'); ?>" style="color: #fff">
	                    <div class="row">
	                        <div class="col-xs-4">
	                            <i class="fa fa-bars fa-5x"></i>
	                        </div>
	                        <div class="col-xs-8 text-right">
	                            <span>Mails en cola</span>
	                            <h2 class="font-bold"><?php echo $smtps['mails_en_cola']; ?></h2>
	                        </div>
	                    </div>
                    </a>
                </div>
                <?php } elseif ($smtps['mails_en_cola'] > 5000) { ?>
                <div class="widget style1 yellow-bg">
	                <a href="<?php echo base_url('emailer/smtps'); ?>" style="color: #fff">
	                    <div class="row">
	                        <div class="col-xs-4">
	                            <i class="fa fa-bars fa-5x"></i>
	                        </div>
	                        <div class="col-xs-8 text-right">
	                            <span>Mails en cola</span>
	                            <h2 class="font-bold"><?php echo $smtps['mails_en_cola']; ?></h2>
	                        </div>
	                    </div>
                    </a>
                </div>
                <?php } else { ?>
                <div class="widget style1 lazur-bg">
	                <a href="<?php echo base_url('emailer/smtps'); ?>" style="color: #fff">
	                    <div class="row">
	                        <div class="col-xs-4">
	                            <i class="fa fa-bars fa-5x"></i>
	                        </div>
	                        <div class="col-xs-8 text-right">
	                            <span>Mails en cola</span>
	                            <h2 class="font-bold"><?php echo ($smtps['mails_en_cola']) ? $smtps['mails_en_cola'] : 0; ?></h2>
	                        </div>
	                    </div>
                    </a>
                </div>
                <?php } ?>
            </div>
            <div class="col-lg-3">
	            <?php if ($inbox['noleidos']) { ?>
	            
	            <?php
		            
					switch ($inbox['prioridad'])
		            {
		            	case 1:
		            		$prioridad_ui_class = 'red';
		            		break;
		            	case 2:
		            		$prioridad_ui_class = 'yellow';
		            		break;
		            	case 3:
		            		$prioridad_ui_class = 'blue';
		            		break;
		            	case 4:
		            		$prioridad_ui_class = 'lazur';
		            		break;
		            	default:
		            		$prioridad_ui_class = 'navy';
		            		break;
		            }
		            
	            ?>
                <div class="widget style1 <?php echo $prioridad_ui_class; ?>-bg">
	                <a href="<?php echo base_url('mailbox?prioridad=' . $inbox['prioridad']); ?>" style="color: #fff">
	                    <div class="row">
	                        <div class="col-xs-4">
	                            <i class="fa fa-envelope-o fa-5x"></i>
	                        </div>
	                        <div class="col-xs-8 text-right">
	                            <span>Mensajes Nuevos</span>
	                            <h2 class="font-bold"><?php echo ($inbox['noleidos']) ? $inbox['noleidos'] : 0; ?></h2>
	                        </div>
	                    </div>
	                </a>
                </div>
                <?php } else { ?>
                <div class="widget style1 navy-bg">
	                <a href="<?php echo base_url('mailbox?prioridad=' . $inbox['prioridad']); ?>" style="color: #fff">
	                    <div class="row">
	                        <div class="col-xs-4">
	                            <i class="fa fa-envelope-o fa-5x"></i>
	                        </div>
	                        <div class="col-xs-8 text-right">
	                            <span>Mensajes</span>
	                            <h2 class="font-bold"><?php echo (isset($inbox['leidos'])) ? $inbox['leidos'] : 0; ?></h2>
	                        </div>
	                    </div>
	                </a>
                </div>
                <?php } ?>
            </div>
            <div class="col-lg-3">
	            <?php if ($tareas['vencidas']) { ?>
                <div class="widget style1 red-bg">
	                <a href="<?php echo base_url('tareas'); ?>" style="color: #fff">
	                    <div class="row">
	                        <div class="col-xs-4">
	                            <i class="fa fa-tasks fa-5x"></i>
	                        </div>
	                        <div class="col-xs-8 text-right">
	                            <span>Tareas Vencidas</span>
	                            <h2 class="font-bold"><?php echo $tareas['vencidas']; ?></h2>
	                        </div>
	                    </div>
                    </a>
                </div>
                <?php } else { ?>
                <div class="widget style1 yellow-bg">
	                <a href="<?php echo base_url('tareas'); ?>" style="color: #fff">
	                    <div class="row">
	                        <div class="col-xs-4">
	                            <i class="fa fa-tasks fa-5x"></i>
	                        </div>
	                        <div class="col-xs-8 text-right">
	                            <span>Tareas Pendientes</span>
	                            <h2 class="font-bold"><?php echo ($tareas['pendientes']) ? $tareas['pendientes'] : 0; ?></h2>
	                        </div>
	                    </div>
                    </a>
                </div>
                <?php } ?>
            </div>
            <div class="col-lg-3">
	            <?php if ($tickets['nuevos']) { ?>
                <div class="widget style1 red-bg">
	                <a href="<?php echo base_url('tickets'); ?>" style="color: #fff">
	                    <div class="row">
	                        <div class="col-xs-4">
	                            <i class="fa fa-ticket fa-5x"></i>
	                        </div>
	                        <div class="col-xs-8 text-right">
	                            <span>Tickets Nuevos</span>
	                            <span><small>Promedio: <?php echo $tickets['stats']['inicio']; ?></small></span>
	                            <h2 class="font-bold"><?php echo $tickets['nuevos']; ?></h2>
	                        </div>
	                    </div>
                    </a>
                </div>
                <?php } elseif ($tickets['abiertos']) { ?>
                <div class="widget style1 yellow-bg">
	                <a href="<?php echo base_url('tickets'); ?>" style="color: #fff">
	                    <div class="row">
	                        <div class="col-xs-4">
	                            <i class="fa fa-ticket fa-5x"></i>
	                        </div>
	                        <div class="col-xs-8 text-right">
	                            <span>Tickets Abiertos</span>
	                            <span><small>Promedio: <?php echo $tickets['stats']['inicio']; ?></small></span>
	                            <h2 class="font-bold"><?php echo $tickets['abiertos']; ?></h2>
	                        </div>
	                    </div>
                    </a>
                </div>
                <?php } else { ?>
                <div class="widget style1 navy-bg">
	                <a href="<?php echo base_url('tickets'); ?>" style="color: #fff">
	                    <div class="row">
	                        <div class="col-xs-4">
	                            <i class="fa fa-ticket fa-5x"></i>
	                        </div>
	                        <div class="col-xs-8 text-right">
	                            <span>Tickets <small>(<?php echo $tickets['stats']['inicio']; ?>)</small></span>
	                            <h2 class="font-bold">0</h2>
	                        </div>
	                    </div>
                    </a>
                </div>
                <?php } ?>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-6">
                <div class="widget white-bg p-xl">
                	<i class="fa fa-exchange"></i> ATHINA
                	<img src="data: image/gif;base64, <?php echo $bandwidth['athina']; ?>" width="100%">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="widget lazur-bg p-xl">
                    <h2>Gigared</h2>
	                <ul class="list-unstyled m-t-md">
	                    <li>
	                        <span class="fa fa-home m-r-xs"></span>
	                        <label>Cliente:</label> 151 / 
	                        <label>Rack:</label> A17B
	                    </li>
	                    <li>
	                        <span class="fa fa-envelope m-r-xs"></span>
	                        <label>Email (Soporte):</label>
	                        noc@gigared.com.ar
	                    </li>
	                    <li>
	                        <span class="fa fa-envelope m-r-xs"></span>
	                        <label>Email (Entrada):</label>
	                        idc@gigared.com.ar
	                    </li>
	                    <li>
	                        <span class="fa fa-phone m-r-xs"></span>
	                        <label>NOC:</label>
	                        6040-6020 / 15 5144-2973
	                    </li>
	                    <li>
	                        <span class="fa fa-phone m-r-xs"></span>
	                        <label>Alejandra Carballés:</label>
	                        15 3053-9758
	                    </li>
	                </ul>
                </div>
<!--
                <div class="widget lazur-bg p-lg text-center">
                    <div class="m-b-md">
                        <i class="fa fa-bell fa-4x"></i>
                        <h1 class="m-xs"><?php echo $dashboard['blacklist']; ?></h1>
                        <h3 class="font-bold no-margins">
                            IPs
                        </h3>
                        <small>En lista negra</small>
                    </div>
                </div>
-->
            </div>
            <div class="col-lg-2">
                <div class="widget lazur-bg p-lg text-center">
	                <a href="<?php echo base_url('hosting/tomo-la-guardia/'); ?>" style="color: #fff">
                    <div class="m-b-md">
                        <i class="fa fa-medkit fa-4x"></i>
                        <h1 class="m-xs">Guardia</h1>
                        <h3 class="font-bold no-margins">
                            <?php echo $hosting['contacto_de_guardia']['contacto']; ?>
                        </h3>
                    </div>
	                </a>
                </div>
            </div>            
        </div>
        
        <div class="row">
            <div class="col-lg-2">
                <div class="widget style1 navy-bg">
                    <div class="row vertical-align">
                        <div class="col-xs-3">
                            <i class="fa fa-user fa-3x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <h2 class="font-bold"><?php echo $usuarios['online']; ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="widget style1 navy-bg">
                    <div class="row vertical-align">
                        <div class="col-xs-3">
                            <i class="fa fa-thumbs-up fa-3x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <h2 class="font-bold"><?php echo $servicios['activos']; ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2">
	            <?php if ($servicios['suspender']) { ?>
	            	<a href="<?php echo base_url('administracion/servicios?estado=2'); ?>">
		                <div class="widget style1 yellow-bg">
		                    <div class="row vertical-align">
		                        <div class="col-xs-3">
		                            <i class="fa fa-thumbs-down fa-3x"></i>
		                        </div>
		                        <div class="col-xs-9 text-right">
		                            <h2 class="font-bold"><?php echo $servicios['suspender']; ?></h2>
		                        </div>
		                    </div>
		                </div>
	            	</a>
	            <?php } elseif ($servicios['activar']) { ?>
	            	<a href="<?php echo base_url('administracion/servicios?estado=3'); ?>">
		            	<div class="widget style1 yellow-bg">
		                    <div class="row vertical-align">
		                        <div class="col-xs-3">
		                            <i class="fa fa-thumbs-up fa-3x"></i>
		                        </div>
		                        <div class="col-xs-9 text-right">
		                            <h2 class="font-bold"><?php echo $servicios['activar']; ?></h2>
		                        </div>
		                    </div>
		                </div>
	            	</a>
	            <?php } else { ?>
	            	<div class="widget style1 lazur-bg">
	                    <div class="row vertical-align">
	                        <div class="col-xs-3">
	                            <i class="fa fa-thumbs-down fa-3x"></i>
	                        </div>
	                        <div class="col-xs-9 text-right">
	                            <h2 class="font-bold"><?php echo $servicios['suspendidos']; ?></h2>
	                        </div>
	                    </div>
	                </div>
	            <?php } ?>
            </div>
            <div class="col-lg-4">
                <div class="widget style1 <?php echo ($newsletters['restantes']) ? 'navy-bg' : 'lazur-bg'; ?>">
                    <div class="row vertical-align">
                        <div class="col-xs-3">
                            <i class="fa fa-paper-plane-o fa-3x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <h2 class="font-bold"><?php echo ($newsletters['restantes']) ? $newsletters['restantes'] : 0; ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2">
	            <?php if ($comunicaciones['error']) { ?>
	            <a href="<?php echo base_url('administracion/comunicaciones?estado=4'); ?>">
	                <div class="widget style1 red-bg">
	                    <div class="row vertical-align">
	                        <div class="col-xs-3">
	                            <i class="fa fa-bullhorn fa-3x"></i>
	                        </div>
	                        <div class="col-xs-9 text-right">
	                            <h2 class="font-bold"><?php echo ($comunicaciones['error']) ? $comunicaciones['error'] : 0; ?></h2>
	                        </div>
	                    </div>
	                </div>
	            </a>
                <?php } elseif ($comunicaciones['enviar']) { ?>
                <a href="<?php echo base_url('administracion/comunicaciones?estado=1'); ?>">
	                <div class="widget style1 yellow-bg">
	                    <div class="row vertical-align">
	                        <div class="col-xs-3">
	                            <i class="fa fa-bullhorn fa-3x"></i>
	                        </div>
	                        <div class="col-xs-9 text-right">
	                            <h2 class="font-bold"><?php echo ($comunicaciones['enviar']) ? $comunicaciones['enviar'] : 0; ?></h2>
	                        </div>
	                    </div>
	                </div>
                </a>
                <?php } elseif ($facturas['imprimir']) { ?>
                <a href="<?php echo base_url('administracion/facturas?estado=1'); ?>">
	                <div class="widget style1 yellow-bg">
	                    <div class="row vertical-align">
	                        <div class="col-xs-3">
	                            <i class="fa fa-file fa-3x"></i>
	                        </div>
	                        <div class="col-xs-9 text-right">
	                            <h2 class="font-bold"><?php echo ($facturas['imprimir']) ? $facturas['imprimir'] : 0; ?></h2>
	                        </div>
	                    </div>
	                </div>
                </a>
                <?php } elseif ($facturas['error']) { ?>
                <a href="<?php echo base_url('administracion/facturas?estado=8'); ?>">
	                <div class="widget style1 red-bg">
	                    <div class="row vertical-align">
	                        <div class="col-xs-3">
	                            <i class="fa fa-file fa-3x"></i>
	                        </div>
	                        <div class="col-xs-9 text-right">
	                            <h2 class="font-bold"><?php echo $facturas['error']; ?></h2>
	                        </div>
	                    </div>
	                </div>
                </a>
                <?php } else { ?>
                <div class="widget style1 lazur-bg">
                    <div class="row vertical-align">
                        <div class="col-xs-3">
                            <i class="fa fa-file fa-3x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <h2 class="font-bold">0</h2>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>

		<div class="row">
            <div class="col-lg-6">
	            <div class="ibox float-e-margins">
	                <div class="ibox-content">
	                    <h2>Mis Tareas</h2>
	                    <small>Lista de tareas diarias</small>
	                    <?php if (!empty($tareas['lista'])) { ?>
	                    <ul class="todo-list m-t small-list tooltip-demo">
		                    <?php foreach ($tareas['lista'] as $tarea) { ?>
			                    <?php if ($tarea['id_estado'] == 2) { ?>
			                    <li>
		                            <a href="#" onclick="cambiarTareaEstado(<?php echo $tarea['id']; ?>)" class="check-link"><i class="fa fa-check-square"></i> </a>
		                            <span class="m-l-xs todo-completed"><?php echo $tarea['titulo']; ?></span>
		                        </li>
		                        <?php } else { ?>
		                        <li>
		                            <a href="#" onclick="cambiarTareaEstado(<?php echo $tarea['id']; ?>)" class="check-link"><i class="fa fa-square-o"></i> </a>
		                            <span class="m-l-xs"><a href="<?php echo base_url('tareas/detalle/' . $tarea['id']); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $tarea['descripcion']; ?>"><?php echo $tarea['titulo']; ?></a></span>
		                            <?php if (!empty($tarea['hasta'])) { ?>
		                            	<small class="label <?php echo $tarea['estado_ui_class']; ?>"><i class="fa fa-clock-o"></i> <?php echo formatear_fecha($tarea['hasta'], 'd-m-Y', null, $this->usuario->timezone); ?></small>
		                            <?php } ?>
		                        </li>
		                        <?php } ?>
	                        <?php } ?>
	                    </ul>
	                    <?php } else { ?>
	                    <ul class="todo-list m-t small-list">
	                        <li>
	                            <span class="text-info m-l-xs"><i class="fa fa-check-circle"></i> Sin tareas pendientes!</span>
	                        </li>
	                    </ul>
	                    <?php } ?>
	                </div>
	            </div>
            </div>
            
            <div class="col-lg-6">
                <div class="ibox float-e-margins">
					<div class="ibox-content">
						<h2>Hosting</h2>
						<small>Planes llegando a su capacidad <em>(Actualización cada una hora)</em></small>
						<?php if (!empty($planes)) { ?>
						<ul class="todo-list m-t small-list tooltip-demo">
							<?php foreach ($planes as $plan) { ?>
							<li>
								<span class="m-l-xs">
									<a href="<?php echo base_url('hosting/detalle/' . $plan['id']); ?>" data-toggle="tooltip" data-placement="top" title="Actualizado: <?php echo formatear_fecha($plan['fecha'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?>"><?php echo $plan['domain']; ?></a> <i class="fa fa-cloud"></i> <?php echo $plan['diskused_porcentaje']; ?>% <i class="fa fa-exchange"></i>  <?php echo $plan['bandwidthused_porcentaje']; ?>%
								</span>
								<div class="progress progress-mini">
									<div style="width: <?php echo $plan['porcentaje']; ?>%;" class="progress-bar <?php echo $plan['progress_ui_class']; ?>"></div>
								</div>
							</li>
							<? } ?>
						</ul>
						<?php } else { ?>
						<ul class="todo-list m-t small-list">
							<li>
								<span class="text-info m-l-xs"><i class="fa fa-check-circle"></i> No hay planes por superar su capacidad</span>
							</li>
						</ul>
						<?php } ?>
					</div>
				</div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <h2>Newsletters</h2>
                        <small>Campañas programadas, enviándose y detenidas</small>
                        <ul class="todo-list m-t small-list">
							<?php foreach ($newsletters['news'] as $newsletter) { ?>
                            <li>
                                <span class="m-l-xs">
                                	<a href="http://mailer.revisionalpha.com/emailer/mensajes/reporte/<?php echo $newsletter['id']; ?>/&hash=<?php echo $newsletter['id_grupo']; ?>" target="_blank" alt="<?php echo $newsletter['grupo'] . ' - ' . $newsletter['remitente'] . ' <' . $newsletter['email'] . '>'; ?>" title="<?php echo $newsletter['grupo'] . ' - ' . $newsletter['remitente'] . ' <' . $newsletter['email'] . '>'; ?>"><?php echo $newsletter['asunto']; ?></a>
                                </span>
								<small class="label <?php echo $newsletter['estado_ui_class']; ?>"><i class="fa fa-clock-o"></i> <?php echo formatear_fecha($newsletter['inicio'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></small>
                            </li>
                            <? } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
	

    <script>
	    function cambiarTareaEstado(id) { 
			$.ajax( {
			    type: 'POST',
			    url: 'tareas/cambiar-estado/',
			    data: "id="+id,
			    success: function(data) {
			        //alert(data);
			    }
			});
		}
		
		function yoMeEncargo(id) { 
			$.ajax( {
			    type: 'POST',
			    url: 'hosting/yo-me-encargo-ajax/',
			    data: "id="+id,
			    success: function(data) {
			        //alert(data);
			        $(".nagios #"+id).hide('slow');
			    }
			});
		}
		
		setTimeout(function() {
	    		location.reload();
			},60000);
	</script>