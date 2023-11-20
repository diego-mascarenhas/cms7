<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div id="content-wrapper">
	            <div class="container-fluid">
	                <div class="video-block section-padding">
	                    <div class="row">
	                        <div class="col-md-12">
	                            <div class="main-title">
									<?php if ($this->usuario->perfil == 'admin') { ?>
									<div class="btn-group float-right right-action">
	                                    <a href="<?php echo base_url('multimedia/gestionar-proyecto'); ?>" class="right-action-link text-gray">Crear nueva categoría</a>
	                                </div>
									<?php } ?>
									<h6>Categorías</h6>
	                            </div>
	                        </div>
	
							<?php if (isset($proyectos)) { ?>
								<?php foreach($proyectos as $proyecto) { ?>
		                        <div class="col-xl-3 col-sm-6 mb-3">
		                            <div class="channels-card">
		                                <div class="channels-card-image">
			                                <?php $link = ($proyecto['hijos']) ? base_url('multimedia/proyectos?padre=' . $proyecto['id']) : null; ?>
	
			                                <?php $thumb = ($proyecto['thumb']) ? base_url('multimedia/thumbs/' . $proyecto['thumb']) : base_url('/assets/vidoe/img/s1.png'); ?>
	
			                                <?php if ($link) { ?>
		                                    	<a href="<?php echo $link; ?>"><img class="img-fluid" src="<?php echo $thumb; ?>"></a>
											<?php } else { ?>
												<img class="img-fluid" src="<?php echo $thumb; ?>">
			                                <?php } ?>
	
											<?php if ($this->usuario->perfil == 'admin') { ?>
				                                <div class="channels-card-image-btn">
													<button type="button" class="btn btn-outline-danger btn-sm">
														<a href="<?php echo base_url('multimedia/gestionar-proyecto/' . $proyecto['id']); ?>">Gestionar
														</a>
													</button>
												</div>
											<?php } ?>
		                                </div>
	
		                                <div class="channels-card-body">
		                                    <div class="channels-title">
			                                    <?php if ($proyecto['estado'] > 1) { ?>
			                                    	<i class="fas fa-check-circle text-success"></i>
			                                    <?php } ?>
			                                    <?php if ($link) { ?>
		                                        	<a href="<?php echo $link; ?>"><?php echo $proyecto['proyecto']; ?></a>
		                                        <?php } else { ?>
													<?php echo $proyecto['proyecto']; ?>
												<?php } ?>
		                                    </div>
			                                    <div class="channels-view">
			                                    <?php if ($proyecto['cantidad']) { ?>
			                                    	<a href="<?php echo base_url('multimedia/?proyecto=' . $proyecto['id']); ?>"><?php echo $proyecto['cantidad']; ?> videos</a>
			                                    <?php } else { ?>
			                                    	<?php echo $proyecto['cantidad']; ?> videos
			                                    <?php } ?>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                        <?php } ?>
	                        <?php } ?>
	                    </div>
	                </div>
	            </div>












<!--
Y ahora tiro yo porque me toca
En este tiempo de plumaje blanco. 
Un mudo con tu voz, y un ciego como yo
Vencedores Vencidos

¡Te has fugado! ¡Me hago humo!
¡Den la alarma!
Ensayo general para la farsa actual,
teatro anti disturbios
-->












