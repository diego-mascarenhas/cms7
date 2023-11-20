<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div id="content-wrapper">
            	<div class="container-fluid ">            
					<div class="row">
						<div class="col-lg-12">
							<div class="main-title">
								<h6>Asociación de archivos</h6>
							</div>
							<hr>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
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
											<ul>
											<?php foreach($menu as $obj) { ?>
												<li>
								                <?php if (!isset($nivel)) { ?>
													<input type="checkbox" name="proyectos[]" value="<?php echo $obj['id']; ?>"<?php foreach($relacionados as $rela) { if($obj['id'] == $rela['id_proyecto']) {echo ' checked';} } ?>>
													<label><?php echo $obj['proyecto']; ?></label><br>
								                <?php } else { ?>
									            	<input type="checkbox" name="proyectos[]" value="<?php echo $obj['id']; ?>"<?php foreach($relacionados as $rela) { if($obj['id'] == $rela['id_proyecto']) {echo ' checked';} } ?>>
													<label><?php echo (isset($obj['proyecto'])) ? $obj['proyecto'] : $obj['id']; ?></label><br>
								                <?php } ?>
	
								                	<?php if (isset($obj['hijos'])) { ?>
								                		<?php menuProyectosVista($obj['hijos'], $obj['nivel']); ?>
													<?php } ?>
												</li>
											<?php } ?>
											</ul>
									<?php
										}
	
					                	menuProyectosVista($proyectos);
							        } ?>
								<hr>
								<div class="col-lg-12 text-center">
								    <h7>
								        <p>¿Está seguro que quiere asociar el archivo <strong>
								        <a href="<?php echo base_url('multimedia/detalle/' . $item['id']); ?>"><?php echo $item['nombre']; ?></a></strong>?</p>
								    </h7>
								</div>
								<div class="row">
								    <div class="col-lg-12 text-center">
								        <button class="btn btn-secondary" type="submit" href="javascript:window.history.go(-1);" style="margin-bottom: 15px;">Cancelar</button>
								        <button class="btn btn-primary" type="submit" style="margin-bottom: 15px;">Asociar</button>
								    </div>
								</div>
							<?php echo form_close();?>
						</div>
					</div>
            	</div>
        	</div>
