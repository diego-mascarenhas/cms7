<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Tareas</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <strong>Tareas</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
                    <div class="title-action">
                        <a href="<?php echo base_url('tareas/ingresar/'); ?>" class="btn btn-primary btn-sm">Ingresar tarea</a>
                    </div>
                </div>
	        </div>
			
			<div class="wrapper wrapper-content animated fadeInRight">
				
				<?php if ($this->usuario->perfil == 'reseller') { ?>
			    <div class="row">
		            <div class="col-lg-12">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title">
		                        <h5>Filtros</h5>
		                    </div>
		                    <div class="ibox-content">
		                        <?php echo form_open(null, array('class'=>'form-horizontal', 'method'=>'get')); ?>
	                            	<?php if ($this->usuario->perfil == 'reseller') { ?>
	                            	<div class="form-group">
			                            <label class="col-sm-2 control-label"><?php echo $this->lang->line('cms_users-agentes'); ?></label>
		                                <div class="col-sm-4">
			                                <?php echo form_dropdown('id_contacto', $agentes, (isset($parametros['id_contacto'])) ? $parametros['id_contacto'] : null, 'class="form-control m-b"'); ?>
			                            </div>
			                            <label class="col-sm-2 control-label"><?php echo $this->lang->line('cms_proyectos'); ?></label>
		                                <div class="col-sm-4">
			                                <?php echo form_dropdown('id_proyecto', $proyectos, (isset($parametros['id_proyecto'])) ? $parametros['id_proyecto'] : null, 'class="form-control m-b"'); ?>
			                            </div>
		                            </div>
		                            <?php } ?>
		                            
		                            <div class="form-group">
		                                <div class="col-sm-10 col-sm-offset-2">
		                                    <button class="btn btn-primary btn-sm pull-right" type="submit">Buscar</button>
		                                </div>
		                            </div>
		                        </form>
		                    </div>
		                </div>
		            </div>
		        </div>
	            <?php } ?>
                
                <div class="row">
	                <div class="col-lg-4">
	                    <div class="ibox">
	                        <div class="ibox-content">
	                            <h3>Pendientes</h3>
<!--
	                            <div class="input-group">
	                                <input type="text" placeholder="Add new task. " class="input input-sm form-control">
	                                <span class="input-group-btn">
	                                        <button type="button" class="btn btn-sm btn-white"> <i class="fa fa-plus"></i> Add task</button>
	                                </span>
	                            </div>
-->
	
	                            <ul class="sortable-list connectList agile-list" id="todo">
		                            <?php if (isset($tareas['todo'])) { ?>
			                            <?php foreach ($tareas['todo'] as $tarea) { ?>
			                            <li class="<?php echo $tarea['estado_ui_class']; ?>-element" id="<?php echo $tarea['id']; ?>">
		                                    <?php echo $tarea['titulo']; ?>
		                                    <div class="agile-detail">
		                                        <a href="<?php echo base_url('tareas/detalle/' . $tarea['id']); ?>" class="pull-right btn btn-xs btn-primary">Detalle</a>
		                                        <i class="fa fa-user"></i> <?php echo $tarea['contacto']; ?>
		                                        <?php if (isset($tarea['hasta'])) { ?>
		                                        	<br><i class="fa fa-clock-o"></i> <?php echo formatear_fecha($tarea['hasta'], 'd-m-Y', null, $this->usuario->timezone); ?>
		                                        <?php } ?>
		                                    </div>
		                                </li>
			                            <?php } ?>
		                            <?php } ?>
	                            </ul>
	                        </div>
	                    </div>
	                </div>
	                <div class="col-lg-4">
	                    <div class="ibox">
	                        <div class="ibox-content">
	                            <h3>En curso</h3>
	                            <ul class="sortable-list connectList agile-list" id="inprogress">
		                            <?php if (isset($tareas['inprogress'])) { ?>
		                            	<?php foreach ($tareas['inprogress'] as $tarea) { ?>
			                            <li class="<?php echo $tarea['estado_ui_class']; ?>-element" id="<?php echo $tarea['id']; ?>">
		                                    <?php echo $tarea['titulo']; ?>
		                                    <div class="agile-detail">
		                                        <a href="<?php echo base_url('tareas/detalle/' . $tarea['id']); ?>" class="pull-right btn btn-xs btn-primary">Detalle</a>
		                                        <i class="fa fa-user"></i> <?php echo $tarea['contacto']; ?>
		                                        <?php if (isset($tarea['hasta'])) { ?>
		                                        	<br><i class="fa fa-clock-o"></i> <?php echo formatear_fecha($tarea['hasta'], 'd-m-Y', null, $this->usuario->timezone); ?>
		                                        <?php } ?>
		                                    </div>
		                                </li>
			                            <?php } ?>
		                            <?php } ?>    
	                            </ul>
	                        </div>
	                    </div>
	                </div>
	                <div class="col-lg-4">
	                    <div class="ibox">
	                        <div class="ibox-content">
	                            <h3>Finalizadas</h3>
	                            <ul class="sortable-list connectList agile-list" id="completed">
		                            <?php if (isset($tareas['completed'])) { ?>
			                            <?php foreach ($tareas['completed'] as $tarea) { ?>
			                            <li class="<?php echo $tarea['estado_ui_class']; ?>-element" id="<?php echo $tarea['id']; ?>">
		                                    <?php echo $tarea['titulo']; ?>
		                                    <div class="agile-detail">
		                                        <a href="<?php echo base_url('tareas/detalle/' . $tarea['id']); ?>" class="pull-right btn btn-xs btn-primary">Detalle</a>
		                                        <i class="fa fa-user"></i> <?php echo $tarea['contacto']; ?>
		                                        <?php if (isset($tarea['hasta'])) { ?>
		                                        	<br><i class="fa fa-clock-o"></i> <?php echo formatear_fecha($tarea['hasta'], 'd-m-Y', null, $this->usuario->timezone); ?>
		                                        <?php } ?>
		                                    </div>
		                                </li>
			                            <?php } ?>
		                            <?php } ?>
	                            </ul>
	                        </div>
	                    </div>
	                </div>
	            </div>
	            
<!--
	            <div class="row">
	                <div class="col-lg-12">
	                    <h4>Serialised Output</h4>
	                    <p>Serializes the sortable's item id's into an array of string.</p>
	                    <div class="output p-m m white-bg"></div>
	                </div>
	            </div>
-->
	        </div>
	        
	        <!-- Mainly scripts -->
		    <script src="<?php echo base_url('assets/js/jquery-ui-1.10.4.min.js'); ?>"></script>
		
		    <script>
		        $(document).ready(function(){
		
		            $("#todo, #inprogress, #completed").sortable({
		                connectWith: ".connectList",
		                update: function( event, ui ) {
		
		                    var todo = $( "#todo" ).sortable( "toArray" );
		                    var inprogress = $( "#inprogress" ).sortable( "toArray" );
		                    var completed = $( "#completed" ).sortable( "toArray" );
		                    
		                    //$('.output').html("ToDo: " + window.JSON.stringify(todo) + "<br/>" + "In Progress: " + window.JSON.stringify(inprogress) + "<br/>" + "Completed: " + window.JSON.stringify(completed));
		                    
							$.ajax({
								type: 'POST',
								url: '<?php echo base_url('tareas/process-sortable/todo'); ?>',
								data: {items: JSON.stringify(todo)},
								success: function(data) {
									console.log(data);
								}
							});
							
							$.ajax({
								type: 'POST',
								url: '<?php echo base_url('tareas/process-sortable/inprogress'); ?>',
								data: {items: JSON.stringify(inprogress)},
								success: function(data) {
									console.log(data);
								}
							});
							
							$.ajax({
								type: 'POST',
								url: '<?php echo base_url('tareas/process-sortable/completed'); ?>',
								data: {items: JSON.stringify(completed)},
								success: function(data) {
									console.log(data);
								}
							});
		                }
		            }).disableSelection();
		
		        });
		    </script>
