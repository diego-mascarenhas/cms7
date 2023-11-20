<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div id="content-wrapper">
	            <div class="container-fluid upload-details">
	                <div class="row">
						<div class="col-lg-12">
	                       <div class="main-title">
	                          <h6>Gestión de archivo</h6>
	                       </div>
	                    </div>
						<div class="col-lg-2 text-center">
							<?php if (isset($detalle['thumb']))
	                                { 
	                                    $thumb = 'multimedia/thumbs/' . $detalle['thumb'];
	                                }
	                                else
									{
										switch ($detalle['tipo'])
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
	                        <a href="<?php echo base_url('/multimedia/upload-thumb/' . $detalle['id'] ); ?>" title="Subir miniatura">
		                        <img class="img-fluid" src="<?php echo base_url($thumb); ?>">
	                        </a>
						</div>
	
	                    <div class="col-lg-6" style="padding-top:8px;">
	                        <div class="osahan-title">
	                            <?php echo (isset($detalle['nombre'])) ? $detalle['nombre'] : null; ?>
	                        </div>
	                        <div class="osahan-size">
	                            <i class="fas fa-calendar-alt"></i> <?php echo formatear_fecha($detalle['fecha_alta'], 'd-m-Y H:i', ' hs', $this->usuario->timezone); ?>
							</div>
	
	                        <div class="osahan-progress">
	                            <!-- <div class="progress">
	                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
	                            </div> -->
	                            <div class="osahan-close">
<!-- 		                            <div class="osahan-close"><a href="<?php echo base_url('/multimedia/upload-thumb/' . $detalle['id'] ); ?>" title="Subir miniatura"><i class="fas fa-edit"></i> </a></div> -->
	                            </div>
	                        </div>
	                        <div class="osahan-desc"></div>
	                    </div>
	                </div>
	                <hr>
	
	                <div class="row">
	                    <div class="col-lg-12">
							<?php if (validation_errors()) : ?>
								<div class="col-md-12">
									<div class="alert alert-danger" role="alert">
										<?php echo validation_errors(); ?>
									</div>
								</div>
							<?php endif; ?>
							<?php if (isset($error)) : ?>
								<div class="col-md-12">
									<div class="alert alert-danger" role="alert">
										<?php echo $error; ?>
									</div>
								</div>
							<?php endif; ?>
	
	                        <?php echo form_open(null, array('class'=>'form-horizontal')); ?>
	                        	<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
	                        	<input type="hidden" name="fecha_alta" value="<?php echo $detalle['fecha_alta']; ?>">
		                        <div class="osahan-form">
		                            <div class="row">
		                                <div class="col-lg-12">
		                                    <div class="form-group">
		                                        <label for="e1"><?php echo $this->lang->line('cms_media-nombre'); ?></label>
		                                        <input type="text" id="e1" class="form-control" name="nombre" value="<?php echo (isset($detalle['nombre'])) ? $detalle['nombre'] : null; ?>">
		                                    </div>
		                                </div>
		                                <div class="col-lg-12">
		                                    <div class="form-group">
		                                        <label for="e2"><?php echo $this->lang->line('cms_media-descripcion'); ?></label>
		                                        <?php echo form_textarea('descripcion', (isset($detalle['descripcion'])) ? $detalle['descripcion'] : null, 'class="form-control"'); ?>
		                                    </div>
		                                </div>
		                                <div class="col-lg-12">
											<div class="form-group">
												<label for="e3">Tags</label>
												<input type="text" name="tags" class="form-control border-form-control" value="<?php echo (isset($detalle['tags'])) ? $detalle['tags']: null; ?>">
											</div>
										</div>
		                            </div>
		                            <div class="row">
	<!--
		                                <div class="col-lg-3">
		                                    <div class="form-group">
		                                        <label for="e3">Tipo</label>
		                                        <select id="e3" class="custom-select" name="stream">
		                                            <option value="1" <?php if ($detalle['stream'] == 1) echo 'selected'; ?>>Storage</option>
													<option value="2" <?php if ($detalle['stream'] == 2) echo 'selected'; ?>>VOD</option>
													<option value="3" <?php if ($detalle['stream'] == 3) echo 'selected'; ?>>Adaptative</option>
		                                        </select>
		                                    </div>
		                                </div>
	-->
		                                <?php if ($this->usuario->perfil == 'admin') { ?>
		                                <div class="col-lg-3">
		                                    <div class="form-group">
		                                        <label for="e4">Estado</label>
		                                        <select id="e4" class="custom-select" name="estado">
		                                            <option value="1" <?php if ($detalle['estado'] == 1) echo 'selected'; ?>>Inactivo</option>
													<option value="2" <?php if ($detalle['estado'] == 2) echo 'selected'; ?>>Activo</option>
													<option value="3" <?php if ($detalle['estado'] == 3) echo 'selected'; ?>>Público</option>
		                                        </select>
		                                    </div>
		                                </div>
		                                <?php } ?>
		                            </div>
		                        </div>
		                        <div class="osahan-area text-center mt-3">
			                        <button class="btn btn-secondary" type="submit" href="javascript:window.history.go(-1);">Cancelar</button>
		                            <button class="btn btn-primary" type="submit">Guardar cambios</button>
		                        </div>
		                    </form>
	
	<!--
	                        <div class="terms text-center">
	                            <p class="mb-0">Por favor lea atentamente los <a href="https://rocoto.tv/nosotros/">Términos y Condiciones</a> and <a href="https://rocoto.tv/wp-content/uploads/2014/10/Lolo1.jpg">Lolo's Guidelines</a>.</p>
	
	                            <p class="hidden-xs mb-0">Guardando este archivo se dan por aceptado los mismos.</p>
	                        </div>
	-->
	                    </div>
	                </div>
	            </div>
