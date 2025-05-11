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
<!--
	                        <div class="setings-item">
								<span>Soporte Nivel 1</span>
		                        <div class="switch">
		                            <div class="onoffswitch">
		                                <input type="checkbox" name="collapsemenu" checked="" class="onoffswitch-checkbox" id="example">
		                                <label class="onoffswitch-label" for="example">
		                                    <span class="onoffswitch-inner"></span>
		                                    <span class="onoffswitch-switch"></span>
		                                </label>
		                            </div>
		                        </div>
		                    </div>
		                    <div class="setings-item">
								<span>Soporte Avanzado</span>
	                            <div class="switch">
	                                <div class="onoffswitch">
	                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example2">
	                                    <label class="onoffswitch-label" for="example2">
	                                        <span class="onoffswitch-inner"></span>
	                                        <span class="onoffswitch-switch"></span>
	                                    </label>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="setings-item">
							<span>Soporte Crítico</span>
	                            <div class="switch">
	                                <div class="onoffswitch">
	                                    <input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example3">
	                                    <label class="onoffswitch-label" for="example3">
	                                        <span class="onoffswitch-inner"></span>
	                                        <span class="onoffswitch-switch"></span>
	                                    </label>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="setings-item">
-->
	                    
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

</body>
</html>