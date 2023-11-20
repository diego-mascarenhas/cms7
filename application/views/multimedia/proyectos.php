<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<!-- Carga de Imagenes -->
			<link href="<?php echo base_url('assets/css/custom.css'); ?>" rel="stylesheet" type="text/css">

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Multimedia</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('multimedia'); ?>">Multimedia</a>
	                    </li>							
	                    <li class="active">
	                        <strong>Proyectos </strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
		            <?php if ($this->usuario->perfil == 'admin') { ?>
                    <div class="title-action">
                        <a href="<?php echo base_url('multimedia/gestionar-proyecto'); ?>" class="btn btn-primary btn-sm">Ingresar proyecto</a>
                    </div>
                    <?php } ?>
                </div>
	        </div>

	        <div class="wrapper wrapper-content animated fadeInRight">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
	                        <h5>Proyectos</h5>
	                    </div>
                        <div class="ibox-content">
                            	<?php if (isset($proyectos)) { ?>
									<?php
										function menuProyectosVista($menu, $nivel=null)
										{
											?>
											<?php foreach($menu as $obj) { ?>
											<fieldset>
								                <?php if (!isset($nivel)) { ?>
					                    		<div class="nav-first-level">
													 <a href="<?php echo base_url('multimedia/gestionar-proyecto/' . $obj['id']); ?>"> <span class="label label-info"><i class="fa fa-folder"></i></span> <?php echo $obj['proyecto']; ?></a>
												</div>
								                <?php } else { ?>
												<div>
													<a href="<?php echo base_url('multimedia/gestionar-proyecto/' . $obj['id']); ?>"> <span class="label label-info"><i class="fa fa-folder"></i></span> <?php echo (isset($obj['proyecto'])) ? $obj['proyecto'] : $obj['id']; ?></a>
												</div>
								                <?php } ?>
	
								                <?php if (isset($obj['hijos'])) { ?>
							                		<?php
								                		switch ($obj['nivel'])
								                		{
								                			case 2:
								                				$level_ui_class = 'nav-third-level';
								                				break;
								                			case 3:
								                				$level_ui_class = 'nav-fourth-level';
								                				break;
								                			default:
								                				$level_ui_class = 'nav-second-level';
								                				break;
								                		}
							                		?>
						                		<ul class="<?php echo $level_ui_class; ?>">
						                			<?php menuProyectosVista($obj['hijos'], $obj['nivel']); ?>
						                		</ul>
							                	<?php } ?>
											</fieldset>
											<?php } 
										}
					                
					                	menuProyectosVista($proyectos);
					                ?>
	                            <?php } ?>
                        </div>
                    </div>
                </div>
	        </div>












<!--
Si estás metido en el juego
de la quiniela cotidiana
why si cambias gato por liebre
evidenciando tu hazaña.
-->












