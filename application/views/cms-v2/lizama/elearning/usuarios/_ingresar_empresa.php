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
.tooltip-inner {max-width: 250px;width: 250px;}
</style>

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-xs-8 col-sm-8 col-lg-8">
            	<h2>Elearning Empresas</h2>
                <ol class="breadcrumb">
                    <li><a href="/micuenta">Home</a></li>
                    <li><a href="/cms-v2/elearning/usuarios/empresas">Empresas</a></li>
                    <li class="active"><strong><?php echo (empty($item['id'])) ? 'Crear nueva' : 'Modificar'; ?></strong></li>
                </ol>
            </div>
            <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
            <input type="hidden" name="id" value="<?php echo (isset($item['id'])) ? $item['id'] : null; ?>">
            <input type="hidden" name="medidas" value="120x120">
            <input type="hidden" name="area_privada" value="5">
            <input type="hidden" name="tipo_contacto" value="1">
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
	                    <h5>Información del usuario <?php echo (isset($item['id'])) ? '<a>'.$item['nombre'].'</a>' : null; ?></h5>
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
		                    <label class="col-sm-1 control-label">Empresa*</label>
		                    <div class="col-sm-3"><input type="text" name="nombre" class="form-control" value="<?php echo (isset($item['nombre'])) ? $item['nombre']: null; ?>"></div>
		                    <label class="col-sm-1 control-label">Avatar</label>
								<?php if(!empty($item['avatar'])) { ?>
								<img src="<?php echo base_url('/multimedia/thumbs/'.$item['avatar']);?>" alt="<?=$item['titulo']?>" style="height:100px;"/>
								<?php } ?>
		                    <div class="col-sm-3">
			                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
		                            <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
		                            <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Seleccionar imagen</span><span class="fileinput-exists">Cambiar</span><input type="file" name="avatar"></span>
		                            <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remover</a>	
		                    	</div>
							</div>
	                 	</div>							

	                 	<div class="form-group">
		                    <label class="col-sm-1 control-label">Dirección</label>
		                    <div class="col-sm-3"><input type="text" name="domicilio" class="form-control" value="<?php echo (isset($item['domicilio'])) ? $item['domicilio']: null; ?>"></div>
		                    <label class="col-sm-1 control-label">CP</label>
		                    <div class="col-sm-3"><input type="text" name="codigo_postal" class="form-control" value="<?php echo (isset($item['codigo_postal'])) ? $item['codigo_postal']: null; ?>"></div>
		                    <label class="col-sm-1 control-label">Localidad</label>
		                    <div class="col-sm-3"><input type="text" name="localidad" class="form-control" value="<?php echo (isset($item['localidad'])) ? $item['localidad']: null; ?>"></div>
	                 	</div>							

	                 	<div class="form-group">
		                    <label class="col-sm-1 control-label">Provincia</label>
		                    <div class="col-sm-3"><input type="text" name="provincia" class="form-control" value="<?php echo (isset($item['provincia'])) ? $item['provincia']: null; ?>"></div>
		                    <label class="col-sm-1 control-label">País</label>
		                    <div class="col-sm-3"><input type="text" name="domicilio_entrega" class="form-control" value="<?php echo (isset($item['domicilio_entrega'])) ? $item['domicilio_entrega']: null; ?>"></div>
	                 	</div>							

	                 	<div class="form-group">
		                    <label class="col-sm-1 control-label">Documento</label>
		                    <div class="col-sm-3"><input type="text" name="documento" class="form-control" value="<?php echo (isset($item['documento'])) ? $item['documento']: null; ?>"></div>
		                    <label class="col-sm-1 control-label">Teléfono</label>
		                    <div class="col-sm-3"><input type="text" name="telefono" class="form-control" value="<?php echo (isset($item['telefono'])) ? $item['telefono']: null; ?>"></div>
		                    <label class="col-sm-1 control-label">Celular</label>
		                    <div class="col-sm-3"><input type="text" name="celular" class="form-control" value="<?php echo (isset($item['celular'])) ? $item['celular']: null; ?>"></div>
	                 	</div>							

					 	<div class="hr-line-dashed"></div>
					 	<br>
						<h2>Datos de Acceso</h2>
	                 	<div class="form-group">
		                    <label class="col-sm-1 control-label">Usuario/Email*</label>
		                    <div class="col-sm-3"><input type="text" name="email" class="form-control" value="<?php echo (!empty($item['email'])) ? $item['email'] : null; ?>" <?php echo (!empty($item['id'])) ? 'readonly=true' : null; ?>></div>
		                    <label class="col-sm-1 control-label">Contraseña*</label>
		                    <div class="col-sm-3"><input type="password" name="password" class="form-control" value=""></div>
		                    <label class="col-sm-1 control-label">Estado</label>
		                    <div class="col-sm-3">
			                    <?php echo (isset($item['estado'])) ? form_dropdown('estado', $estados, $item['estado'], array('class'=>'form-control m-b')) : form_dropdown('estado', $estados, null, array('class'=>'form-control m-b')); ?></div>
	                 	</div>
					 	<div class="hr-line-dashed"></div>
					 	<br>
	                 	<div class="form-group">
							<input type="hidden" name="id" value="<?php echo (!empty($item['id'])) ? $item['id'] : ''; ?>">
			                <div class="col-12" style="margin-top:34px; margin-left:20px;">
			                    <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
			                    <button class="btn btn-primary" type="submit">Guardar cambios</button>
			                </div>
	                 	</div>
	                 </div>
                </div>
            </div>
         </div>
	 <?=form_close()?>

    <?php if(isset($item['id'])) {  ?>
        <div class="col-lg-12 m-t-lg m-b-lg">
            <div class="ibox float-e-margins">
               <div class="ibox-title pull-left full-width">
                    <h2 class="bg-muted p-sm">Pedidos</h2>
                </div>
                <div class="ibox-content" style="border-top:0;">
                    <div class="table-responsive">
	                    <table class="table table-striped table-bordered table-hover">
		                    <thead>
		                    <tr>
		                        <th>Pedido</th>
		                        <th>Fecha</th>
		                        <th>Usuario</th>
		                        <th>Estado</th>
		                        <th>Acciones</th>
		                    </tr>
		                    </thead>
		                    <tbody>
			                   <?php if(isset($pedidos)) { foreach($pedidos as $pedido) { ?>	
			                   	 <tr class="gradeX">
			                        <td><?php echo $pedido['id'];?></td>
			                        <td><?php echo $pedido['fecha_alta'];?></td>
			                        <td><?php echo $pedido['contacto'];?></td>
			                        <td><?php echo $pedido['tipo_estado'];?></td>
			                        <td>
				                        <a href="<?php echo base_url('cms-v2/elearning/pedidos/detalle/').$pedido['id']; ?>" title="Ver" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> Ver</a> 
				                        <a href="<?php echo base_url('cms-v2/elearning/pedidos/modificar/').$pedido['id']; ?>" title="Modificar" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i> Modificar</a> </td>
			                     </tr>
			                   <?php } } ?>	
		                    </tbody>
	                    </table>
                    </div>
		   		</div>
		   	</div>
       </div>
      <?php } ?>	
    </div>
    </div>

<script>
$('[data-toggle="tooltip"]').tooltip(); 
</script>