<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div id="content-wrapper">
				<div class="container-fluid">
					<div class="video-block section-padding">
						<div class="row">
							<div class="col-md-8">
								<div class="single-video-left">
									<div class="single-video">
										<?php if ($detalle['tipo'] == 'video') { ?>
											<link rel="stylesheet" href="https://cdn.flowplayer.com/releases/native/3/stable/style/flowplayer.css">
											<script src="https://cdn.flowplayer.com/releases/native/3/stable/flowplayer.min.js"></script>
											<script src="https://cdn.flowplayer.com/releases/native/3/stable/plugins/hls.min.js"></script>

											<style>
												#playerElement {
													width: 100%;
													height: 0;
													padding-bottom: 56.25%; /* Mantiene la relación de aspecto 16:9 */
													position: relative;
												}
												#playerElement .flowplayer {
													position: absolute;
													top: 0;
													left: 0;
													width: 100%;
													height: 100%;
												}
											</style>

											<div id="playerElement"></div>

											<script>
												flowplayer('#playerElement', {
													src: "<?php echo $detalle['video']; ?>",
													token: "eyJraWQiOiJZMzQ5cVlIUDFRd1IiLCJ0eXAiOiJKV1QiLCJhbGciOiJFUzI1NiJ9.eyJjIjoie1wiYWNsXCI6MjIsXCJpZFwiOlwiWTM0OXFZSFAxUXdSXCJ9IiwiaXNzIjoiRmxvd3BsYXllciJ9.URiG5fT4w3-TaPyT76AjZw9Cw8Bt4_Ug9uz2S3X5Tg9I2O0WV5hNUW-hjgY61ZxMFF8THpirCkW8NhWAE0zwXQ",
													poster: "<?php echo $detalle['thumb']; ?>",
													autoplay: false,
													muted: false,
													loop: false,
													volume: 0.75
												});
											</script>
										<?php } elseif ($detalle['tipo'] == 'audio') { ?>
											<div class="box">
												<audio controls>
													<source src="<?php echo base_url('multimedia/' . $this->usuario->grupo . '/' . $this->usuario->id_empresa . '/' . $detalle['archivo']); ?>">
												</audio>
											</div>
										<?php }
											elseif (isset($detalle['thumb']))
											{ 
												$thumb = $detalle['thumb'];
											?>
												<img src="<?php echo $thumb; ?>" width="100%" height="auto">
											<?php
											}
											else
											{
												switch ($detalle['tipo'])
													{
														case 'imagen':
															$thumb = base_url('assets/vidoe/img/thumb-imagen.png');
															break;
														case 'video':
															$thumb = base_url('assets/vidoe/img/thumb-video.png');
															break;
														case 'audio':
															$thumb = base_url('assets/vidoe/img/thumb-audio.png');
															break;
														default:
															$thumb = base_url('assets/vidoe/img/thumb-default.png');
															break;
													}
											?>
												<img src="<?php echo $thumb; ?>" width="100%" height="auto">
											<?php } ?>
									</div>
									
									<div class="single-video-title box mb-3">
										<h2><?php echo $detalle['nombre']; ?></h2>
<!-- 		                              <p class="mb-0"><i class="fas fa-eye"></i> 2,729,347 views</p> -->
										<?php if (isset($breadcrumb)) { ?>
											<?php
												foreach ($breadcrumb as $item)
												{
												?>
													&nbsp;/&nbsp;<a class="mb-0" href="<?php echo base_url('multimedia/?proyecto=' . $item['id']); ?>"><?php echo $item['proyecto']; ?></a>
				
												<?php
												}
										 } ?>
									</div>
	
									<div class="single-video-info-content box mb-3">
										<h6>Usuario:</h6>
										<p><?php echo $detalle['contacto']; ?></p>
	
										<h6>Fecha:</h6>
										<p><?php echo formatear_fecha($detalle['fecha_alta'], 'd-m-Y H:i', ' hs', $this->usuario->timezone); ?></p>
	
										
										<?php if ($this->usuario->perfil == 'admin') { ?>
<!--
										<h6>Archivo:</h6>
										<p><?php echo $detalle['archivo']; ?></p>
-->
	
										<h6>Tipo:</h6>
										<p><?php echo $detalle['mime']; ?></p>
										<?php } ?>
										
										<h6>Tamaño:</h6>
										<p><?php echo byte_format($detalle['peso']*1024); ?></p>
										
										<?php if (isset($detalle['descripcion'])) { ?>
											<h6>Descripción:</h6>
											<p><?php echo $detalle['descripcion']; ?></p>
										<?php } ?>
	
										<?php if ($detalle['id_estado'] == 3) { ?>
										<h6>Link público: </h6>
											<p>
												<a href="<?php echo base_url('multimedia/share/' . $detalle['uid']); ?>" target="_blank">
													<?php echo base_url (ellipsize ('multimedia/share/' . $detalle['uid'], 10)); ?>
												</a>
											</p>
										</p>
										<?php } else { ?>
											<h6>Link público: </h6>
											<p>El video no está compartido.</p>
										<?php } ?>
										
										<?php if (isset($detalle['tags'])) { ?>
										<h6>Tags :</h6>
										<p class="tags mb-0">
											<?php $tags = explode(',', $detalle['tags']); ?>
											<?php foreach ($tags as $obj) { ?>
												<span><a href="#"><?php echo $obj; ?></a></span>	
											<? } ?>
										</p>
										<?php } ?>
										
										<br>
										<?php if ($this->usuario->perfil == 'admin' || $this->usuario->id == $detalle['username_alta']) { ?>
											<button class="btn btn-sm btn-primary pull-left" type="button">
												<a title="Modificar" href="<?php echo base_url('/multimedia/modificar/' . $detalle['id']); ?>" style="color:white; margin: 2px;">
												<i class="fa fa-edit"></i> Modificar</a>
											</button>
											
											<?php if ($this->usuario->perfil == 'admin') { ?>
											<button class="btn btn-sm btn-primary pull-left" type="button">
												<a href="<?php echo base_url('/multimedia/asociar/' . $detalle['id']); ?>" style="color:white; margin: 2px;">
												<i class="fa fa-link"></i> Asociar</a>
											</button>
											<?php } ?>
											
											<button class="btn btn-sm btn-primary pull-left" type="button">
												<a href="<?php echo base_url('multimedia/download/' . $detalle['uid']); ?>" title="Descargar"; style="color:white;" style="margin: 2px;">
												<i class="fa fa-download"></i> Descargar</a>
											</button>
	
											<button class="btn btn-sm btn-primary float-right right-action" type="button">
												<a href="<?php echo base_url('/multimedia/eliminar/' . $detalle['id']); ?>" style="color:white; margin: 2px;">
												<i class="fa fa-trash"></i> Eliminar</a>
											</button>
										<?php } elseif ($this->usuario->perfil != 'admin' && $detalle['tipo'] != 'video') { ?>
											<button class="btn btn-sm btn-primary pull-left" type="button">
												<a href="<?php echo base_url('multimedia/download/' . $detalle['uid']); ?>" title="Descargar"; style="color:white;" style="margin: 2px;">
												<i class="fa fa-download"></i> Descargar</a>
											</button>
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="single-video-right">
									<div class="row">
										<div class="col-md-12">
											<?php if (isset($medias)) { ?>
												<?php foreach($medias as $media) { ?>
												<div class="video-card video-card-list">
													<div class="video-card-image">
														<?php $link = (isset($parametros['proyecto'])) ? 'multimedia/detalle/' . $media['id'] . '?proyecto=' . $parametros['proyecto'] : 'multimedia/detalle/' . $media['id']; ?>
														<a class="play-icon" href="<?php echo base_url($link); ?>"></a>
														<?php if (isset($media['thumb']))
																{
																	$thumb = 'multimedia/thumbs/' . $media['thumb'];
																}
																else
																{
																	switch ($media['tipo'])
																		{
																			case 'imagen':
																				$thumb = 'assets/vidoe/img/thumb-imagen.png';
																				break;
																			case 'video':
																				$thumb = 'assets/vidoe/img/thumb-video.png';
																				break;
																			case 'audio':
																				$thumb = 'assets/vidoe/img/thumb-audio.png';
																				break;
																			default:
																				$thumb = 'assets/vidoe/img/thumb-default.png';
																				break;
																		}
																}
														?>
															<img class="img-fluid" src="<?php echo base_url($thumb); ?>">
														</a>
														<div class="time">
															<?php if ($media['id_estado'] == 1) { ?>
																<div class="time bg-info">Inactivo</div>
															<?php } elseif ($media['id_estado'] == 2) { ?>
																<div class="time bg-primary">Activo</div>
															<?php } else { ?>
																<div class="time bg-success">Público</div>
															<?php } ?>
														</div>
													</div>
													<div class="video-card-body">
														<div class="video-title">
															<a href="<?php echo base_url($link); ?>"><?php echo ellipsize($media['nombre'], 18); ?></a>
														</div>
													</div>
													<div class="video-view">
														<?php echo byte_format($media['peso']*1024); ?> &nbsp; <?php echo formatear_fecha($media['fecha_alta'], 'd-m-Y H:i', ' hs', $this->usuario->timezone); ?>
													</div>
												</div>
												<?php } ?>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			
			
			
			
			
			
			
			
			
			
			
<!--
Mata el miedo que guarda el animal. 
Limpia el cuerpo, pues dentro de él estás. 
Si buscas libertad, ya no andés por fuera. 
Hombre de mil nombres naces ya, naces ya. 
-->












