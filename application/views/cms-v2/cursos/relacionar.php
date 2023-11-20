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
	            <h2>Sitio web Cursos</h2>
	            <ol class="breadcrumb">
	                <li>
	                    <a href="/cms">Home</a>
	                </li>
	                <li>
	                    <a href="<?php echo base_url('cms-v2/cursos/');?>">Cursos</a>
	                </li>
	                <li class="active">
	                    <strong>Relacionar cursos</strong>
	                </li>
	            </ol>
	        </div>
	
	        <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
	        <input type="hidden" name="id" value="<?php echo $this->uri->segment(4); ?>">
	        <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
	            <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
	            <button class="btn btn-primary" type="submit">Relacionar</button>
	        </div>
	    </div>
                       
        <div class="wrapper wrapper-content animated fadeInRight p_b_25">
            <!-- Titulo Mensajes -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox-title ibox-title-custom"><h5>Relacionar curso</h5></div>
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

       	<!-- Comienzo Contenido -->
        <div class="wrapper wrapper-content animated fadeInRight" style="padding-top:0 !important;">
            <div class="row" style="margin:0px !important">

				<div class="col-lg-12 float-e-margins" style="background:#fff;">
                    <div class="ibox-title" style="border:none !important;">
                		<h5>Relacionar cursos seleccionados del listado a: <em><?php echo isset($item['titulo']) ? $item['titulo'] :  null; ?></em> <button type="button" class="btn btn-info btn-circle" data-toggle="tooltip" data-placement="right" title="Los cursos seleccionados del listado se mostrarán en el sidebar del curso"><i class="fa fa-question"></i></button></h5></div>
						<div class="hr-line-dashed m_t_b_5"></div>
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
        
<!--
        <div class="wrapper wrapper-content animated fadeInRight" style="padding-top:0 !important;">
            <div class="row" style="margin:0px !important">
				<div class="col-lg-12 float-e-margins" style="background:#fff;">
-->
					<div style="padding:20px;float: left;width: 100%;">
					<?php if(isset($item['titulo'])) { 
		               if(!empty($listado)) { foreach($listado as $lista) { ?>	
						<div class="col-lg-4">
	                        <h4><input type="checkbox" name="relaciones[]" value="<?php echo $lista['id'];?>" <?php foreach($relacionados as $rela) { if($lista['id'] == $rela['id_contenido_relacionado']) {echo ' checked';} } ?>>
							<?php echo $lista['titulo']; ?> </h4>
						</div>
	               <?php } } else { echo 'No se encontraron resultados';} ?>	
				   <?php echo form_close();?>
				   <?php } ?>
				   </div>
              </div>  
            </div>    
        </div>

