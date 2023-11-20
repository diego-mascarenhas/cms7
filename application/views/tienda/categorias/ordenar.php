<link href="<?php echo base_url('assets/css/tienda.css'); ?>" rel="stylesheet" type="text/css">
		<div class="row wrapper border-bottom white-bg page-heading">
        	<div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
                <h2>Tienda</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('tienda/dashboard'); ?>">Home</a>
                    </li>
                    <li>
                         <a href="<?php echo base_url('tienda/categorias'); ?>">Categorías </a>
                    </li>
                    <li>
                        <strong>Ordenar</strong>
                    </li>
                </ol>
            </div>
        </div>
            
        <div class="wrapper wrapper-content animated">
            <div class="row">
                <div class="col-lg-12">
                	<div class="ibox float-e-margins">
	                    <div class="ibox-title">
	                        <h5>Ordenar categorías de la tienda</h5>
	                    </div>
                	</div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-1"></div>
                <div class="col-lg-10">
                    <ul class="sortable-list connectList agile-list" id="media">
		                <?php foreach($listado as $lista) { ?>	
                        <li class="lista" id="<?php echo $lista['id']; ?>">
							<img src="<?php echo ($lista['imagen']) ? base_url('/multimedia/thumbs/'.$lista['imagen']) : 'https://app.pedimosfacil.com/v2/assets/images/no-disponible.jpg';?>" title="" alt="" class="listados_miniatura">
                        <?php echo $lista['categoria'];?></li>
		                <?php } ?>	
                    </ul>
                </div>	
                <div class="col-lg-1"></div>
            </div>
        </div>

          <!-- Footer -->
            <div class="footer">
	            <div class="pull-right">
	                <strong>CMS+</strong> ☰
	            </div>
	            <div>
	                <strong>Copyright</strong> revision alpha &copy;2002-2019
	            </div>
	        </div>
        </div>

        <div id="right-sidebar" class="animated">
            <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 100%;">
	            <div class="sidebar-container" style="overflow: hidden; width: auto; height: 100%;">

	                <ul class="nav nav-tabs navs-3">
	                    <li class="active">
	                    	<a data-toggle="tab" href="#tab-1">Notas</a>
	                    </li>
	                    <li>
	                    	<a data-toggle="tab" href="#tab-2">Tareas</a>
	                    </li>
	                    <li class="">
	                    	<a data-toggle="tab" href="#tab-3"><i class="fa fa-gear"></i></a>
	                    </li>
	                </ul>
	
	                <div class="tab-content">
						<div id="tab-1" class="tab-pane active">
							<div class="sidebar-title">
	                            <h3> <i class="fa fa-comments-o"></i> Ultimas Notas</h3>
	                            <small><i class="fa fa-tim"></i> Tenés <?php echo count($notas = $this->session->userdata('notas')); ?> <?php echo (count($notas) == 1) ? 'nueva nota' : 'nuevas notas'; ?>.</small>
	                        </div>
	                        <div>
		                        <?php if ($notas) { ?>
			                        <?php foreach ($notas as $nota) { ?>
		                            <div class="sidebar-message">
		                                <a href="<?php echo base_url($nota['uri'] . '/detalle/' . $nota['id_referencia']); ?>">
		                                    <div class="pull-left text-center">
		                                        <img alt="image" class="img-circle message-avatar" src="<?php echo base_url('multimedia/avatars/' . $nota['avatar']); ?>">
	<!--
		                                        <div class="m-t-xs">
		                                            <i class="fa fa-star text-warning"></i>
		                                            <i class="fa fa-star text-warning"></i>
		                                        </div>
	-->
		                                    </div>
		                                    <div class="media-body">
												<?php echo $nota['titulo']; ?>
		                                        <br>
		                                        <small class="text-muted"><?php echo formatear_fecha($nota['fecha_alta'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></small>
		                                    </div>
		                                </a>
		                            </div>
	                            	<?php } ?>
	                            <?php } ?>
	                        </div>
	                    </div>
	
	                    <div id="tab-2" class="tab-pane">
	                        <div class="sidebar-title">
	                            <h3> <i class="fa fa-cube"></i> Ultimas Tareas</h3>
	                            <small><i class="fa fa-tim"></i> Tenés <?php echo count($tareas = $this->session->userdata('tareas')); ?> tareas pendientes.<!--  10 no sin terminar. --></small>
	                        </div>
	                        <?php if ($tareas) { ?>
		                        <?php foreach ($tareas as $tarea) { ?>
		                        <ul class="sidebar-list">
		                            <li>
		                                <a href="<?php echo base_url('tareas/detalle/' . $tarea['id']); ?>">
			                                <span class="label label-<?php echo $tarea['estado_ui_class']; ?> pull-right"><?php echo $tarea['estado']; ?></span>
	<!-- 	                                    <div class="small pull-right m-t-xs">9 hours ago</div> -->
		                                    <h4><?php echo $tarea['titulo']; ?></h4>
		                                    <?php echo $tarea['descripcion']; ?>
		
	<!-- 	                                    <div class="small">Completion with: 22%</div> -->
	<!--
		                                    <div class="progress progress-mini">
		                                        <div style="width: 22%;" class="progress-bar progress-bar-warning"></div>
		                                    </div>
	-->
		                                    <div class="small text-muted m-t-xs"><?php echo formatear_fecha($tarea['hasta'], 'd-m-Y', null, $this->usuario->timezone); ?></div>
		                                </a>
		                            </li>
		                        </ul>
								<?php } ?>
	                        <?php } ?>
	                    </div>
	
	                    <div id="tab-3" class="tab-pane">
	                        <div class="sidebar-title">
	                            <h3><i class="fa fa-gears"></i> Configuración</h3>
	                            <small><i class="fa fa-tim"></i> Infórmanos como estas como para atender estas tareas.</small>
	                        </div>
	                    
	                    	<div class="setings-item">
							<span>Soporte Técnico</span>
	                            <div class="switch">
	                                <div class="onoffswitch">
	                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example4">
	                                    <label class="onoffswitch-label" for="example4">
	                                        <span class="onoffswitch-inner"></span>
	                                        <span class="onoffswitch-switch"></span>
	                                    </label>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="setings-item">
		                        
	                        <div class="sidebar-content">
	                            <h4>¿Y ahora?</h4>
	                            <div class="small">
	                                Por favor informanos en que estado estas para poder hacer sustentable la plataforma ;-)
	                            </div>
	                        </div>
	
	                    </div>
	                </div>
            	</div>
				<div class="slimScrollBar" style="background-color: rgb(0, 0, 0); width: 7px; position: absolute; top: 2px; opacity: 0.4; display: none; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; z-index: 99; right: 1px; height: 675.0260416666666px; background-position: initial initial; background-repeat: initial initial;"></div>
				<div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-top-left-radius: 7px; border-top-right-radius: 7px; border-bottom-right-radius: 7px; border-bottom-left-radius: 7px; background-color: rgb(51, 51, 51); opacity: 0.4; z-index: 90; right: 1px; background-position: initial initial; background-repeat: initial initial;"></div>
            </div>
		</div>
    </div>

    <!-- Mainly scripts -->
    <script src="<?php echo base_url('assets/js/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>

    <!-- Page-Level Scripts -->
    <script>
        $(document).ready(function(){
		
            $("#media").sortable({
            connectWith: ".connectList",
            update: function( event, ui ) {

                var media = $( "#media" ).sortable( "toArray" );
                                
				$.ajax({
					type: 'POST',
					url: '<?php echo base_url('tienda/categorias/ordenarCategorias/media'); ?>',
					data: {items: JSON.stringify(media)},
					success: function(data) {
						console.log(data);
					}
				});
				
            }
        }).disableSelection();
	
	    });
	</script>
</body>
</html>