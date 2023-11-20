<style>
.btn-file>input { position: absolute;top: 0;right: 0;margin: 0;opacity: 0;filter: alpha(opacity=0);font-size: 23px;height: 100%;width: 100%;direction: ltr;cursor: pointer;}
.skin-1 .ibox-content:last-child {border-style: solid solid solid solid;}
.ibox-title,.ibox-content {border-width: 1px;}
.b_bottom { border-bottom: 1px solid #e7eaec }
.note-editor.note-frame { border: none;}
.btn_eliminar_popup { border:0; background:none;}
.m_t_20 { margin-top:20px !important;}
.m_t_b_5 { margin:5px 0px !important;}
.p_b_25 { padding-bottom:25px !important;}
.ibox-content, .form-group, .hr-line-dashed { float:left; width:100%; }
</style>

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-xs-8 col-sm-8 col-lg-8">
                <h2>Sitio web Usuarios</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="/cms-v2">Home</a>
                    </li>
                    <li>
                        <a href="/cms-v2/usuarios">Usuarios</a>
                    </li>
                    <li class="active">
                        <strong><?php echo (empty($item['id'])) ? 'Crear nueva' : 'Modificar'; ?></strong>
                    </li>
                </ol>
            </div>

            <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
            <input type="hidden" name="id" value="<?php echo (isset($item['id'])) ? $item['id'] : null; ?>">
            <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
                <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
                <button class="btn btn-primary" type="submit">Guardar cambios</button>
            </div>
        </div>
            

        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
	                    <h5>Información del usuario <?php echo (!isset($item['id'])) ? 'Crear nueva secci&oacute;n' : 'Modificar secci&oacute;n'; ?></h5>
                    </div>

                    <div class="ibox-content" style="min-height:140px; height:auto; float:left; padding-bottom:25px;">
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



	                    <div class="col-sm-12">
						 	<h2>Datos personales</h2>
		                 	<div class="form-group" style="float-left; width:100%;">
			                    <label class="col-sm-1 control-label">Nombre*</label>
			                    <div class="col-sm-3"><input type="text" name="nombre" class="form-control" value="<?php echo (isset($item['nombre'])) ? $item['nombre']: null; ?>"></div>
			                    <label class="col-sm-1 control-label">Apellido</label>
			                    <div class="col-sm-3"><input type="text" name="apellido" class="form-control" value="<?php echo (isset($item['apellido'])) ? $item['apellido']: null; ?>"></div>
			                    <label class="col-sm-1 control-label">Avatar</label>
									<?php if(!empty($item['imagen'])) { ?>
									<img src="<?php echo base_url('/contenidos/media/'.$item['imagen']);?>" title="<?=$item['titulo']?>" alt="<?=$item['titulo']?>" style="height:170px;"/>
									<?php } ?>
			                    <div class="col-sm-3">
				                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
			                            <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
			                            <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Seleccionar imagen</span><span class="fileinput-exists">Cambiar</span><input type="file" name="imagen"></span>
			                            <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>	
			                    	</div>
								</div>
		                 	</div>							

	                 	<div class="form-group">
		                    <label class="col-sm-1 control-label">Dirección</label>
		                    <div class="col-sm-3"><input type="text" name="domicilio" class="form-control" value="<?php echo (isset($item['domicilio'])) ? $item['domicilio']: null; ?>"></div>
		                    <label class="col-sm-2 control-label">Código postal</label>
		                    <div class="col-sm-2"><input type="text" name="codigo_postal" class="form-control" value="<?php echo (isset($item['codigo_postal'])) ? $item['codigo_postal']: null; ?>"></div>
		                    <label class="col-sm-1 control-label">Localidad</label>
		                    <div class="col-sm-3"><input type="text" name="localidad" class="form-control" value="<?php echo (isset($item['localidad'])) ? $item['localidad']: null; ?>"></div>
	                 	</div>							

	                 	<div class="form-group">
		                    <label class="col-sm-1 control-label">Provincia</label>
		                    <div class="col-sm-3"><input type="text" name="provincia" class="form-control" value="<?php echo (isset($item['provincia'])) ? $item['provincia']: null; ?>"></div>
		                    <label class="col-sm-1 control-label">Teléfono</label>
		                    <div class="col-sm-3"><input type="text" name="telefono" class="form-control" value="<?php echo (isset($item['telefono'])) ? $item['telefono']: null; ?>"></div>
		                    <label class="col-sm-1 control-label">País</label>
		                    <div class="col-sm-3"><input type="text" name="pais" class="form-control" value="<?php echo (isset($item['pais'])) ? $item['pais']: 'Argentina'; ?>"></div>
	                 	</div>							

	                 	<div class="form-group">
		                    <label class="col-sm-1 control-label">Documento</label>
		                    <div class="col-sm-3"><input type="text" name="documento" class="form-control" value="<?php echo (isset($item['documento'])) ? $item['documento']: null; ?>"></div>
		                    <label class="col-sm-1 control-label">Email</label>
		                    <div class="col-sm-3"><input type="text" name="email" class="form-control" value="<?php echo (isset($item['email'])) ? $item['email']: null; ?>"></div>
		                    <label class="col-sm-1 control-label">Celular</label>
		                    <div class="col-sm-3"><input type="text" name="celular" class="form-control" value="<?php echo (isset($item['celular'])) ? $item['celular']: null; ?>"></div>
	                 	</div>							

					 	<div class="hr-line-dashed"></div>
					 	<br>
						<h2>Datos de usuario</h2>
	                 	<div class="form-group">
		                    <label class="col-sm-1 control-label">Usuario*</label>
		                    <div class="col-sm-3"><input type="text" name="username" class="form-control" value="<?php echo (isset($item['username'])) ? $item['username']: null; ?>" <?php if ((isset($_GET['empresa'])) && (isset($item['username']))) { echo 'readonly=true';} ?>></div>
		                    <label class="col-sm-1 control-label">Contraseña*</label>
		                    <div class="col-sm-3"><input type="password" name="password" class="form-control" value=""></div>
	                 	</div>							

	                 	<div class="form-group">
		                    <label class="col-sm-1 control-label">Perfil</label>
		                    <div class="col-sm-3">
			                    <?php echo (empty($item['id'])) ? form_dropdown('area_privada', $perfiles, null, array('class'=>'form-control m-b')) : form_dropdown('area_privada', $perfiles, $item['area_privada'], array('class'=>'form-control m-b')); ?></div>
		                    <label class="col-sm-1 control-label">Estado</label>
		                    <div class="col-sm-3">
			                    <?php echo (isset($item['estado'])) ? form_dropdown('estado', $estados, $item['estado'], array('class'=>'form-control m-b')) : form_dropdown('estado', $estados, null, array('class'=>'form-control m-b')); ?></div>
	                 	</div>

	                 	<div class="form-group">
							<input type="hidden" name="id" value="<?php echo (!empty($item['id'])) ? $item['id'] : ''; ?>">
			                <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
			                    <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
			                    <button class="btn btn-primary" type="submit">Guardar cambios</button>
			                </div>
	                 	</div>
	                    </div>
	                 </div>
                </div>
            </div>
			<?=form_close()?>
            </div>

         <!-- Favoritos -->
            <div class="row m_t_20">
                <div class="col-lg-12">
	                <div class="ibox float-e-margins">
	                    <div class="ibox-title"><h5>Favoritos</h5>
	                        <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
	                    </div>
	                    <div class="ibox-content">
							<div style="padding:20px;float: left;width: 100%;">
							<?php if(!empty($favoritos)) { foreach($favoritos as $favorito) { ?>	
								<div class="col-lg-4">
			                        <h4><a href="cursos/ingresar/<?php echo $favorito['id_contenido']; ?>" title="<?php echo $favorito['titulo']; ?>"><?php echo $favorito['titulo']; ?> </a></h4>
								</div>
			               <?php } } else { echo 'No se encontraron resultados';} ?>	
						   </div>
				   		</div>
				   	</div>
		       </div>
	        </div>
        <!-- Fin Favoritos -->


       <!-- Compras -->
            <div class="row m_t_20 p_b_25">
                <div class="col-lg-12">
	                <div class="ibox float-e-margins">
	                    <div class="ibox-title"><h5>Compras</h5>
	                        <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
	                    </div>
	                    <div class="ibox-content">
	                        <div class="table-responsive">
			                    <table class="table table-striped table-bordered table-hover" >
				                    <thead>
				                    <tr>
				                        <th>Orden de compra</th>
				                        <th>Fecha</th>
				                        <th>Usuario</th>
				                        <th>Estado</th>
				                        <th>Acciones</th>
				                    </tr>
				                    </thead>
				                    <tbody>
				                   <?php if(isset($listado)) { foreach($listado as $lista) { ?>	
				                   	 <tr class="gradeX">
				                        <td><?php echo $lista['id'];?></td>
				                        <td><?php echo $lista['fecha_alta'];?></td>
				                        <td><?php echo $lista['nombre'].' '.$lista['apellido'];?></td>
				                        <td><?php echo $lista['estado'];?></td>
				                        <td>
					                        <a href="<?php echo base_url('pedidos/ver/').$lista['id']; ?>" title="Ver" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> Ver</a> 
					                        <a href="<?php echo base_url('pedidos/ingresar/').$lista['id']; ?>" title="Modificar" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i> Editar</a> </td>
		
				                    </tr>
				                   <?php } }?>	
				                    </tbody>
			                    </table>
	                        </div>
				   		</div>
				   	</div>
		       </div>
	        </div>
        </div>
        <!-- Fin Compras -->

        <!-- Fin Contenido -->
        