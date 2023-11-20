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
</style>

        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-xs-8 col-sm-8 col-lg-8">
                <h2>Sitio web Secciones</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="/cms-v2">Home</a>
                    </li>
                    <li>
                        <a href="/cms-v2/secciones">Secciones</a>
                    </li>
                    <li class="active">
                        <strong><?php echo (empty($item['id'])) ? 'Crear nueva' : 'Modificar'; ?></strong>
                    </li>
                </ol>
            </div>

            <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
            <input type="hidden" name="id" value="<?php echo (isset($item['id'])) ? $item['id'] : null; ?>">
            <input type="hidden" name="seccion_tipo" value="<?php echo (isset($item['id'])) ? $item['id_secciones_tipo'] : null; ?>">
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
	                    <h5><?php echo (!isset($item['id'])) ? 'Crear nueva secci&oacute;n' : 'Modificar secci&oacute;n'; ?></h5>
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
		                   	<div class="form-group">
	                            <label class="col-sm-1 control-label">Secci&oacute;n</label>
	                            <div class="col-sm-3"><input type="text" name="seccion" class="form-control m-b" value="<?php echo $item['seccion']; ?>"></div>
	                            <label class="col-sm-1 control-label">Estado</label>
	                            <div class="col-sm-3"><?php echo (isset($item['id_estado'])) ? form_dropdown('estado', $estados, $item['id_estado'], array('class'=>'form-control m-b')) : form_dropdown('estado', $estados, null, array('class'=>'form-control m-b')); ?></div>
		                   	</div>
	                    </div>

	                    <div class="col-sm-6">
	                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>Imagen</h5></div>
			                    <div class="ibox-content">
									<?php if(!empty($item['imagen'])) { ?>
	                            	<p>Imagen Actual</p>
	                            	<img src="<?php echo base_url('/multimedia/511/7358/'.$item['imagen']);?>" alt="<?php echo $item['seccion']; ?>" style="max-width:90%; height:auto;"/>
									<?php } ?>
									<br><br>
		                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
		                                <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
		                                <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Seleccionar imagen</span><input type="file" name="imagen"></span>
		                            </div>
		                        </div>
		                    </div>
		          </div>

		          <!-- SEO -->
		          <div class="row" style="background:#fff; float:left;margin:20px 0;width:100%; padding:0 20px 20px 20px;">
						<h2>SEO</h2>
	                 	<div class="form-group">
		                    <div class="col-md-4 col-sm-12 m_b_25">
		                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>T&iacute;tulo</h5> &nbsp;<button type="button" class="btn btn-info btn-circle" data-toggle="tooltip" data-placement="right" title="Entre 10 y 70 caracteres inclu&iacute;dos signos y espacios." style="margin-top:-5px;"><i class="fa fa-question"></i></button></div>
			                    <div class="ibox-content no-padding">
				                    <textarea class="form-control" name="seo_titulo" rows="5"><?php echo (isset($item['seo_titulo'])) ? $item['seo_titulo']: null?></textarea>
			                    </div>
		                    </div>

		                    <div class="col-md-4 col-sm-12 m_b_25">
		                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>Descripci&oacute;n</h5> &nbsp;<button type="button" class="btn btn-info btn-circle" data-toggle="tooltip" data-placement="right" title="Entre 30 y 300 caracteres inclu&iacute;dos signos y espacios." style="margin-top:-5px;"><i class="fa fa-question"></i></button></div>
			                    <div class="ibox-content no-padding">
				                    <textarea class="form-control" name="seo_descripcion" rows="5"><?php echo (isset($item['seo_descripcion'])) ? $item['seo_descripcion']: null?></textarea>
			                    </div>
		                    </div>
		                    <div class="col-md-4 col-sm-12 m_b_25">
		                    	<div class="ibox-title" style="background:#f7f7f7;"><h5>Keywords</h5> &nbsp;<button type="button" class="btn btn-info btn-circle" data-toggle="tooltip" data-placement="right" title="Entre 3 y 10 frases o palabras separadas por coma, hasta 600 caracteres inclu&iacute;dos signos y espacios." style="margin-top:-5px;"><i class="fa fa-question"></i></button></div>
			                    <div class="ibox-content no-padding">
				                    <textarea class="form-control" name="seo_keywords" rows="5"><?php echo (isset($item['seo_keywords'])) ? $item['seo_keywords']: null?></textarea>
			                    </div>
		                    </div>
	                 	</div>
		          </div>
                  <!-- FIN SEO -->
                </div>
            </div>
        </div>
     </div>
<?=form_close()?>
        