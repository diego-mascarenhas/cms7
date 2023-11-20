<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

		<div id="content-wrapper">
			<div class="container-fluid">
				<?php if ($this->usuario->perfil == 'user' ) { ?>
					<div class="row wrapper border-bottom white-bg page-heading">
			            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
			                <h6>Multimedia</h6>
			                <ol class="breadcrumb">
			                    <li>
			                        <a href="<?php echo base_url(); ?>">Home</a>
			                    </li>
			                    <li>
			                        <a href="<?php echo base_url('multimedia/proyectos'); ?>">Archvios</a>
			                    </li>
			                    <li class="active">
		                            <strong>Categoria</strong>
			                    </li>
			                </ol>
			            </div>
		            </div>
		
			        <div class="wrapper wrapper-content animated fadeInRight">
			            <div class="row">
			                <div class="col-lg-12">
				                <div class="ibox float-e-margins">
				                    <div class="ibox-title">
					                    <h5>Detalle</h5>
				                    </div>
			
				                    <div class="ibox-content pb_50">
					                    <p>Usted no tiene privilegios para ver este contenido</p>
				                    </div>
				                </div>
			                </div>
			            </div>
			        </div>
		
				<?php } else { ?>
					<link href="<?php echo base_url('assets/css/plugins/jasny/jasny-bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
					<link href="<?php echo base_url('assets/css/custom.css'); ?>" rel="stylesheet" type="text/css">
		
					<div class="row">
					    <div class="col-lg-12">
					        <div class="main-title">
					            <h6><?php echo (!empty($detalle['id'])) ? 'Modificación de la categoría' : 'Creación de nueva categoría'; ?></h6>
					        </div>
					        <hr>
					    </div>
					</div>
					<div class="row">
						<div class="col-lg-12">
				            <?php if (validation_errors()) : ?>
				                <div class="col-sm-6">
				                    <div class="alert alert-danger" role="alert">
				                        <?php echo validation_errors(); ?>
				                    </div>
				                </div>
				            <?php endif; ?>
				            <?php if (isset($error)) : ?>
				                <div class="col-sm-6">
				                    <div class="alert alert-danger" role="alert">
				                        <?php echo $error; ?>
				                    </div>
				                </div>
				            <?php endif; ?>
		
				            <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
					            <input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
								
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<label for="e3">Nombre</label>
											<input type="text" name="proyecto" class="form-control border-form-control" value="<?php echo (isset($detalle['proyecto'])) ? $detalle['proyecto']: null; ?>">
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label for="e4">Estado</label>
												<select id="e4" class="custom-select" name="estado">
													<option value="1" <?php if (isset($detalle['estado']) && $detalle['estado'] == 1) echo 'selected'; ?> >Inactivo</option>
													<option value="2" <?php if (isset($detalle['estado']) && $detalle['estado'] == 2) echo 'selected'; ?>>Activo</option>
													<option value="3" <?php if (isset($detalle['estado']) && $detalle['estado'] == 3) echo 'selected'; ?>>Público</option>
												</select>
										</div>
									</div>
								</div>
								
								<?php if (!empty($detalle['proyecto'])) { ?>
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<label for="e3">Tags</label>
											<input type="text" name="tags" class="form-control border-form-control" value="<?php echo (isset($detalle['tags'])) ? $detalle['tags']: null; ?>">
										</div>
									</div>
									<div class="col-lg-3">
										<div class="form-group">
											<label for="e3">Destacado</label>
											<input type="text" name="destacado" class="form-control border-form-control" value="<?php echo (isset($detalle['destacado'])) ? $detalle['destacado']: null; ?>">
										</div>
									</div>
									<div class="col-lg-3">
										<div class="form-group">
											<label for="e3">Miniatura</label>
											<input type="file" name="file" class="form-control">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<div class="form-group">
											<label for="e3">Comentario</label>
											<input type="text" name="comentario" class="form-control border-form-control" value="<?php echo (isset($detalle['comentario'])) ? $detalle['comentario']: null; ?>">
										</div>
									</div>
								</div>
								<?php } ?>
								
<!--
								<div class="row">
							        <div class="col-lg-12">
							            <div class="main-title">
							                <h6>Tags</h6>
							            </div>
							        </div>
							    </div>
							
							    <div class="row category-checkbox">
							        <div class="col-lg-2 col-xs-6 col-4">
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="customCheck1"> <label class="custom-control-label" for="customCheck1">Abaft</label>
							            </div>
							
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="customCheck2"> <label class="custom-control-label" for="customCheck2">Brick</label>
							            </div>
							
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="customCheck3"> <label class="custom-control-label" for="customCheck3">Purpose</label>
							            </div>
							
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="customCheck4"> <label class="custom-control-label" for="customCheck4">Shallow</label>
							            </div>
							
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="customCheck5"> <label class="custom-control-label" for="customCheck5">Spray</label>
							            </div>
							        </div>
							
							        <div class="col-lg-2 col-xs-6 col-4">
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="zcustomCheck1"> <label class="custom-control-label" for="zcustomCheck1">Cemetery</label>
							            </div>
							
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="zcustomCheck2"> <label class="custom-control-label" for="zcustomCheck2">Trouble</label>
							            </div>
							
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="zcustomCheck3"> <label class="custom-control-label" for="zcustomCheck3">Pin</label>
							            </div>
							
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="zcustomCheck4"> <label class="custom-control-label" for="zcustomCheck4">Fall</label>
							            </div>
							
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="zcustomCheck5"> <label class="custom-control-label" for="zcustomCheck5">Leg</label>
							            </div>
							        </div>
							
							        <div class="col-lg-2 col-xs-6 col-4">
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="czcustomCheck1"> <label class="custom-control-label" for="czcustomCheck1">Scissors</label>
							            </div>
							
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="czcustomCheck2"> <label class="custom-control-label" for="czcustomCheck2">Stitch</label>
							            </div>
							
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="czcustomCheck3"> <label class="custom-control-label" for="czcustomCheck3">Agonizing</label>
							            </div>
							
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="czcustomCheck4"> <label class="custom-control-label" for="czcustomCheck4">Rescue</label>
							            </div>
							
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="czcustomCheck5"> <label class="custom-control-label" for="czcustomCheck5">Quiet</label>
							            </div>
							        </div>
							
							        <div class="col-lg-2 col-xs-6 col-4">
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="customCheck1"> <label class="custom-control-label" for="customCheck1">Abaft</label>
							            </div>
							
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="customCheck2"> <label class="custom-control-label" for="customCheck2">Brick</label>
							            </div>
							
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="customCheck3"> <label class="custom-control-label" for="customCheck3">Purpose</label>
							            </div>
							
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="customCheck4"> <label class="custom-control-label" for="customCheck4">Shallow</label>
							            </div>
							
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="customCheck5"> <label class="custom-control-label" for="customCheck5">Spray</label>
							            </div>
							        </div><
							
							        <div class="col-lg-2 col-xs-6 col-4">
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="zcustomCheck1"> <label class="custom-control-label" for="zcustomCheck1">Cemetery</label>
							            </div>
							
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="zcustomCheck2"> <label class="custom-control-label" for="zcustomCheck2">Trouble</label>
							            </div>
							
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="zcustomCheck3"> <label class="custom-control-label" for="zcustomCheck3">Pin</label>
							            </div>
							
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="zcustomCheck4"> <label class="custom-control-label" for="zcustomCheck4">Fall</label>
							            </div>
							
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="zcustomCheck5"> <label class="custom-control-label" for="zcustomCheck5">Leg</label>
							            </div>
							        </div>
							
							        <div class="col-lg-2 col-xs-6 col-4">
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="czcustomCheck1"> <label class="custom-control-label" for="czcustomCheck1">Vessel</label>
							            </div>
							
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="czcustomCheck2"> <label class="custom-control-label" for="czcustomCheck2">Stitch</label>
							            </div>
							
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="czcustomCheck3"> <label class="custom-control-label" for="czcustomCheck3">Agonizing</label>
							            </div>
							
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="czcustomCheck4"> <label class="custom-control-label" for="czcustomCheck4">Rescue</label>
							            </div>
							
							            <div class="custom-control custom-checkbox">
							                <input type="checkbox" class="custom-control-input" id="czcustomCheck5"> <label class="custom-control-label" for="czcustomCheck5">Quiet</label>
							            </div>
							        </div>
							    </div>
							    <hr>
-->
		                        
								<div class="row">
									<div class="col-lg-12 text-center">
									    <button class="btn btn-secondary" type="submit" style="margin-bottom: 25px;">
									        <a href="#" onclick="history.go(-1)" style="color:white">Cancelar</a>
									    </button>
									    <button class="btn btn-primary" type="submit" style="margin-bottom: 25px;">Guardar cambios</button>
									</div>
								</div>
							</form>
						</div>
					</div>
		
					<div class="row">
						<div class="col-lg-12">
						    <div class="ibox float-e-margins">
						        <div class="ibox-content">
						            <div class="table-responsive">
						                <table class="table table-striped">
						                    <?php if (!empty($detalle['proyecto'])) { ?>
						                    <thead>
						                        <tr>
						                            <th class="text-left">Contacto</th>
						                            <th class="text-left">Usuario</th>
													<th class="text-center">Ultima visita</th>
						                        </tr>
						                    </thead>
						                    <tbody>
						                        <?php if (isset($relacionar)) { ?>
						                            <?php foreach ($relacionar as $contacto) { ?>
						                        <tr>
						                            <td class="text-left">
														<?php echo $contacto['contacto']; ?>
						                            </td>
													<td class="text-left">
														<?php echo $contacto['username']; ?>
													</td>
													<td class="text-center"><?php echo formatear_fecha($contacto['ultima_visita'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></td>
						                            <?php } ?>
						                        <?php } else { ?>
						                            <td colspan="3">
						                                <span>Aún no se ha compartido la categoría.
						                                <a href="<?php echo base_url('multimedia/compartir-proyecto/' . $detalle['id']); ?>">¿Quiere compartirla?</a></span>
						                            </td>
						                        <?php } ?>
						                        </tr>
						                    </tbody>
						                    <tfoot>
						                        <tr>
						                            <td colspan="3">
														<a href="<?php echo base_url('multimedia/compartir-proyecto/' . $detalle['id']); ?>"><span class="fa fa-share-alt"> Compartir categoría</a>
						                                &nbsp;&nbsp;
						                                <?php if (($this->usuario->perfil == 'admin') && (!empty($detalle['id']))) { ?>
						                                	<a href="<?php echo base_url('multimedia/eliminar-proyecto/' . $detalle['id']); ?>"><span class="fa fa-trash"> Eliminar categoría</a>
						                                <?php } ?>
						                            </td>
						                        </tr>
						                    </tfoot>
						                    <?php } ?>
						                </table>
						            </div>
						        </div>
						    </div>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>
