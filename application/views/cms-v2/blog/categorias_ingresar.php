<style>
.btn-file>input { position: absolute;top: 0;right: 0;margin: 0;opacity: 0;filter: alpha(opacity=0);font-size: 23px;height: 100%;width: 100%;direction: ltr;cursor: pointer;}
å.ibox-title,.ibox-content {border-width: 1px;}
.b_bottom { border-bottom: 1px solid #e7eaec }
.m_t_20 { margin-top:20px !important;}
.m_t_b_5 { margin:5px 0px !important;}
.p_b_25 { padding-bottom:25px !important;}
</style>

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-xs-8 col-sm-8 col-lg-8">
                <h2>Sitio web Categor&iacute;as de Blog</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="/cms">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('cms-v2/noticias/categorias');?>">Categorias</a>
                    </li>
                    <li class="active">
                        <strong><?php echo (empty($item['id'])) ? 'Crear nueva' : 'Modificar'; ?></strong>
                    </li>
                </ol>
            </div>

            <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
			<input type="hidden" name="id" value="<?php echo (!empty($item['id'])) ? $item['id'] : null; ?>">
            <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
                <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
                <button class="btn btn-primary" type="submit">Guardar cambios</button>
            </div>
        </div>
                       
        <div class="wrapper wrapper-content animated fadeInRight p_b_25">
            <!-- Titulo Mensajes -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox-title ibox-title-custom"><h5>Subir Categor&iacute;a</h5>
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
        
        
        <div class="wrapper wrapper-content animated fadeInRight p_b_25">
            <div class="row">
				<div class="col-lg-12">
	                <div class="ibox float-e-margins">
	                    <div class="ibox-title"><h5>Información de la categor&iacute;a</h5>
	                        <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
	                    </div>
	                    
	                    <div class="ibox-content" style="float:left;width:100%;">
		                 	<div class="form-group">
			                    <label class="text-right col-sm-1 control-label">T&iacute;tulo</label>
			                    <div class="col-sm-3 col-md-3"><input type="text" name="seccion" class="form-control" value="<?php echo (isset($item['seccion'])) ? $item['seccion']: null; ?>"></div>
			                    <label class="text-right col-sm-1 control-label">Orden</label>
			                    <div class="col-sm-1 col-md-1"><input type="text" name="orden" class="form-control" value="<?php echo (isset($item['orden'])) ? $item['orden']: null; ?>"></div>
			                    <label class="text-right col-sm-1 control-label">Estado</label>
			                    <div class="col-sm-2">
				                    <?php echo (isset($item['id'])) ? form_dropdown('estado', $estados, $item['estado'], array('class'=>'form-control m-b')) : form_dropdown('estado', $estados, null, array('class'=>'form-control m-b')); ?></div>
				            </div>
				            <br><br><br>
	                    </div>
	                </div>
				</div>
        

			<div class="col-lg-12" style="margin-top:25px;margin-bottom:25px;">
                <div class="ibox float-e-margins">
                    <div class="ibox-title"><h5>SEO</h5>
                        <div class="ibox-tools"><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></div>
                    </div>

                    <div class="ibox-content" style="float: left; width:100%;">
						<div class="col-lg-12">
		                 	<div class="form-group">
			                    <div class="col-md-4 sm-12 m_b_25">
			                    	<div class="ibox-title" style="background:#F5EFEF;"><h5>Título</h5></div>
				                    <div class="ibox-content no-padding"><textarea class="form-control" name="seo_titulo" rows="5"><?php echo (isset($item['seo_titulo'])) ? $item['seo_titulo']: null?></textarea></div>
			                    </div>
			                    <div class="col-md-4 sm-12 m_b_25">
			                    	<div class="ibox-title" style="background:#F5EFEF;"><h5>Descripci&oacute;n</h5></div>
				                    <div class="ibox-content no-padding"><textarea class="form-control" name="seo_descripcion" rows="5"><?php echo (isset($item['seo_descripcion'])) ? $item['seo_descripcion']: null?></textarea></div>
			                    </div>
			                    <div class="col-md-4 sm-12 m_b_25">
			                    	<div class="ibox-title" style="background:#F5EFEF;"><h5>Keywords</h5></div>
				                    <div class="ibox-content no-padding"><textarea class="form-control" name="seo_keywords" rows="5"><?php echo (isset($item['seo_keywords'])) ? $item['seo_keywords']: null?></textarea></div>
			                    </div>
		                 	</div>
						</div>
                    </div>
                </div>
			</div>
		<?=form_close()?>
		<!-- Fin Contenido -->
		<br><br></div></div>