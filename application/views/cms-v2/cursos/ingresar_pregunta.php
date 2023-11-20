<style>
.btn-file>input { position: absolute;top: 0;right: 0;margin: 0;opacity: 0;filter: alpha(opacity=0);font-size: 23px;height: 100%;width: 100%;direction: ltr;cursor: pointer;}
.skin-1 .ibox-content:last-child {border-style: solid solid solid solid;}
.ibox-title,.ibox-content {border-width: 1px;}
</style>

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-xs-8 col-sm-8 col-lg-8">
                <h2>Sitio web Cursos</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="/cms">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('cms-v2/cursos/');?>">Cursos</a>
                    </li>
                    <li class="active">
                        <strong><?php echo (!isset($pregunta['id'])) ? 'Crear nueva pregunta de certificaci&oacute;n' : 'Modificar pregunta de certificaci&oacute;n'; ?></strong>
                    </li>
                </ol>
            </div>

            <?php echo form_open(null, array('class'=>'form-horizontal')); ?>
			<input type="hidden" name="id" value="<?php echo (isset($pregunta['id'])) ? $pregunta['id']: null; ?>">
			<input type="hidden" name="id_contenido" value="<?php echo ($this->input->get('curso')) ? $this->input->get('curso') : $item['id']; ?>">
            <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
                <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
                <button class="btn btn-primary" type="submit">Guardar cambios</button>
            </div>
        </div>
                       
        <div class="wrapper wrapper-content animated fadeInRight p_b_25">
            <!-- Titulo Mensajes -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox-title ibox-title-custom"><h5>Subir pregunta de certificaci&oacute;n para <span style="color:red;"><?php echo $item['titulo']; ?></span></h5>
					</div>
                </div>
                <?php if (validation_errors()) : ?>
				<div class="col-md-12">
					<div class="alert alert-danger" role="alert"><?php echo validation_errors(); ?></div>
				</div>
				<?php endif; ?>
				<?php if (isset($error)) : ?>
				<div class="col-md-12">
					<div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
				</div>
				<?php endif; ?>
            </div>
        </div>
        
       	<!-- Comienzo Tabs -->
        <div class="wrapper wrapper-content animated fadeInRight" style="padding-top:0 !important;">
            <div class="row">

			<div class="col-lg-12 m_b_25">
                <div class="ibox float-e-margins">
                    <div class="ibox-title"><h5>Información de la pregunta</h5>
                        <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                    </div>
                    
                    <div class="ibox-content" style="float:left; width:100%;">
	                 	<div class="form-group">
		                    <label class="text-right col-sm-1 control-label">T&iacute;tulo</label>
		                    <div class="col-sm-5"><input type="text" name="titulo" class="form-control" value="<?php echo (isset($pregunta['id'])) ? $pregunta['titulo']: null; ?>"></div>
		                    <label class="text-right col-sm-1 control-label">Estado</label>
		                    <div class="col-sm-1">
			                    <?php echo (isset($pregunta['id'])) ? form_dropdown('estado', $estados, $pregunta['estado'], array('class'=>'form-control m-b')) : form_dropdown('estado', $estados, null, array('class'=>'form-control m-b')); ?></div>
		                    <label class="text-right col-lg-1 control-label">Orden</label>
		                    <div class="col-sm-1"><input type="text" name="orden" class="form-control" value="<?php echo (isset($pregunta['orden'])) ? $pregunta['orden']: null; ?>"></div>
		                    <label class="text-right col-lg-1 control-label">Correcta</label>
		                    <div class="col-sm-1"><input type="text" name="subtitulo" class="form-control" value="<?php echo (isset($pregunta['subtitulo'])) ? $pregunta['subtitulo']: null; ?>"></div>
			            </div>
			            <br><br><br>
                    </div>
                </div>
            </div>

          <!-- Informacion -->
          <div class="col-lg-12 m_t_25">
            <div class="ibox float-e-margins">
                <div class="ibox-title"><h5>Respuestas</h5>
                    <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                 </div>
                    <div class="ibox-content" style="float:left; width:100%;">
                 	<div class="form-group">
	                    <label class="text-right col-lg-1 control-label">1</label>
	                    <div class="col-sm-5"><input type="text" name="contenido1" class="form-control" value="<?php echo (isset($pregunta['id'])) ? $pregunta['contenido1']: null; ?>"></div>
	                    <label class="text-right col-lg-1 control-label">2</label>
	                    <div class="col-sm-5"><input type="text" name="contenido2" class="form-control" value="<?php echo (isset($pregunta['id'])) ? $pregunta['contenido2']: null; ?>"></div>
                 	</div><br><br>
                 	<div class="form-group">
	                    <label class="text-right col-lg-1 control-label">3</label>
	                    <div class="col-sm-5"><input type="text" name="contenido3" class="form-control" value="<?php echo (isset($pregunta['id'])) ? $pregunta['contenido3']: null; ?>"></div>
	                    <label class="text-right col-lg-1 control-label">4</label>
	                    <div class="col-sm-5"><input type="text" name="contenido4" class="form-control" value="<?php echo (isset($pregunta['id'])) ? $pregunta['contenido4']: null; ?>"></div>
                 	</div><br><br>
                 	<div class="form-group">
	                    <label class="text-right col-lg-1 control-label">5</label>
	                    <div class="col-sm-5"><input type="text" name="contenido5" class="form-control" value="<?php echo (isset($pregunta['id'])) ? $pregunta['contenido5']: null; ?>"></div>
	                    <label class="text-right col-lg-1 control-label">6</label>
	                    <div class="col-sm-5"><input type="text" name="contenido6" class="form-control" value="<?php echo (isset($pregunta['id'])) ? $pregunta['contenido6']: null; ?>"></div>
                 	</div><br><br>
                 	<div class="form-group">
	                    <label class="text-right col-lg-1 control-label">7</label>
	                    <div class="col-sm-5"><input type="text" name="contenido7" class="form-control" value="<?php echo (isset($pregunta['id'])) ? $pregunta['contenido7']: null; ?>"></div>
                 	</div>
			<?=form_close()?>
		<!-- Fin Contenido -->
                    
		   		</div>
		   	</div>
       </div>
       

		<br><br></div></div>
        <!-- Fin Tener en cuenta -->
<!-- Fin Contenido -->