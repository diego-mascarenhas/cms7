<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Relacion de Media a Proyectos -->
<link href="<?php echo base_url('assets/css/custom.css'); ?>" rel="stylesheet" type="text/css">

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-10 col-lg-10">
	                <h2>Multimedia</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <a href="<?php echo base_url('multimedia/'); ?>">Multimedia</a>
	                    </li>
	                    <li class="active">
	                        <strong>Asociar</strong>
	                    </li>
	                </ol>
	            </div>
            </div>
            
			<div class="row wrapper wrapper-content animated fadeInRight">
				<div class="row">
		            <div class="col-sm-12 col-lg-11 ml_25">
		                <div class="ibox float-e-margins">
		                    <div class="ibox-title"><h5>Asociar a proyectos</h5></div>
		                    <div class="ibox-content">
		                        <div class="row">
		                            <div class="col-sm-3 b-r">
			                            <h3 class="m-t-none m-b">
				                            <a href="<?php echo base_url('multimedia/detalle/' . $item['id']); ?>"><?php echo $item['nombre']; ?></a>
			                            </h3>
		                            	<p><i class="fa fa-user"></i> Usuario: <?php echo $item['contacto']; ?></p>
		                                <p><i class="fa fa-clock-o"></i> Fecha: <?php echo formatear_fecha($item['fecha_alta'], 'd-m-Y H:i', ' hs', $this->usuario->timezone); ?></p>
										
										<p class="text-center">
											<br><br><a href="">
											<?php if ($item['thumb']) { ?>
<!-- 											<img alt="image" class="img-responsive" src="<?php echo base_url('multimedia/' . $item['grupo'] . '/' . $media['id_empresa'] . '/' . $media['archivo'] . '_thumb'); ?>"> -->
												<?php } else { ?>
													<?php switch ($item['tipo'])
				                                        {
				                                        	case 'imagen':
				                                        		$ico = 'fa-file-picture-o';
				                                        		break;
				                                        	case 'video':
				                                        		$ico = 'fa-film';
				                                        		break;
				                                        	case 'audio':
				                                        		$ico = 'fa-music';
				                                        		break;
				                                        	default:
				                                        		$ico = 'fa-file';
				                                        		break;
				                                        }
														?>
	                                            	<i class="fa <?php echo $ico; ?> big-icon"></i>
	                                            <?php } ?>
											</a>
										</p>
		                            </div>

		                            <div class="col-sm-9"><h4>Proyectos <small>Asociar archivo a los siguientes proyectos</small></h4>
						                <?php echo form_open(null, array('class'=>'form-horizontal')); ?>
											<input type="hidden" name="id" value="<?php echo $item['id']; ?>">
											<?php if (isset($proyectos)) { ?>
												<?php
													function menuProyectosVista($menu, $nivel=null)
													{
														$CI =& get_instance();

														$sql = "SELECT media_rel_proyectos.id_proyecto, media_rel_proyectos.orden";
														$sql .= " FROM media_rel_proyectos";
														$sql .= " WHERE media_rel_proyectos.id_media = " . $CI->uri->segment(3);
														$query = $CI->db->query($sql);
														$relacionados = $query->result_array();

														?>
														<?php foreach($menu as $obj) { ?>
															<fieldset>
											                <?php if (!isset($nivel)) { ?>
								                    		<div class="nav-label nav-first-level">	
																<input type="checkbox" name="proyectos[]" value="<?php echo $obj['id']; ?>"<?php foreach($relacionados as $rela) { if($obj['id'] == $rela['id_proyecto']) {echo ' checked';} } ?>>	
																<label><?php echo $obj['proyecto']; ?></label>
															</div>
											                <?php } else { ?>
															<div>	
												            	<input type="checkbox" name="proyectos[]" value="<?php echo $obj['id']; ?>"<?php foreach($relacionados as $rela) { if($obj['id'] == $rela['id_proyecto']) {echo ' checked';} } ?>>	
																<label><?php echo (isset($obj['proyecto'])) ? $obj['proyecto'] : $obj['id']; ?></label></div>
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
										                		<ul class="nav <?php echo $level_ui_class; ?>">
										                			<?php menuProyectosVista($obj['hijos'], $obj['nivel']); ?>
										                		</ul>
											                <?php } ?>
															</fieldset>
														<?php } 
													}
								                
								                	menuProyectosVista($proyectos);
								                ?>
											<?php } ?>
											<br>
											<br>
											<button class="btn btn-primary pull-right m-t-n-sm" type="submit"><strong><i class="fa fa-link"></i> Asociar</strong></button>
										<?php echo form_close();?>
		                            </div>
		                        </div>
							</div>
		                </div>
		            </div>
                </div>
            </div>
            
