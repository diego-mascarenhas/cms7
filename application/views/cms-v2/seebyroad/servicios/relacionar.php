<style>
.note-editor.note-frame { border:0;}
.note-editable .row {margin: 0px;}
.note-editable .row div {border: 1px dotted;}
.tooltip-inner {max-width: 250px;width: 250px;}
.listado-paquetes { padding:0; margin:0 15px; float:left;}
.listado-paquetes li { width:33%; display:inline-block;}

@media(max-width:1400px) {
.listado-paquetes li { width:49%;}
}	
@media(max-width:992px) {
.listado-paquetes li { width:98%;}
}	
</style>

         <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-8 col-sm-8 col-xs-8">
                <h2>Paquetes</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('cms-v2/paginas');?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('cms-v2/servicios');?>">Paquetes</a>
                    </li>
                    <li class="active">
                        <strong>Relacionar</strong>
                    </li>
                </ol>
            </div>

            <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
			<input type="hidden" name="id" value="<?php echo (!empty($detalle['id'])) ? $detalle['id'] : null; ?>">
            <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
                <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
                <button class="btn btn-primary" type="submit">Relacionar</button>
            </div>
        </div>
        
        <div class="wrapper wrapper-content animated fadeInRight">
			<div class="row">
                <?php if (validation_errors()) : ?>
				<div class="col-md-12 m-t-sm">
					<div class="alert alert-danger" role="alert"><?php echo validation_errors(); ?></div>
				</div>
				<?php endif; ?>
				<?php if (isset($error)) : ?>
				<div class="col-md-12 m-t-sm">
					<div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
				</div>
				<?php endif; ?>
			</div>
        </div>
        
       	<!-- Comienzo Tabs -->
        <div class="wrapper wrapper-content" style="padding-top:0 !important;">
            <div class="row">
                <div class="col-lg-12">
                    <div class="tabs-container">
                        <ul class="nav nav-tabs">
                        	<?php foreach($idiomas as $idioma) { ?>
                            <li class="<?php echo ($idioma['orden'] == 1)?'active':'';?>"><a data-toggle="tab" href="#tab-<?php echo $idioma['orden'];?>"> <?php echo $idioma['idioma'];?></a></li>
                        	<?php } ?>
                        </ul>

                        <div class="tab-content">
                        	<?php foreach($idiomas as $idioma) { ?>
	                        <div id="tab-<?php echo $idioma['orden'];?>" class="tab-pane <?php echo ($idioma['orden'] == 1)?'active':'';?>">

                        	<?php 
								if(!empty($detalle['id']))
								{
									$CI =& get_instance();
									$CI->load->model("Servicios_model");
									$parametros['id'] = $detalle['id'];
									$parametros['idioma'] = $idioma['extension'];
									$item = $this->Servicios_model->getServicioDetalleIdioma($detalle['id'], $idioma['extension']);
									$relacionados = $this->Servicios_model->getRelacionados($parametros);
								}
							?>
	                            <div class="panel-body">
								 <div class="row">
					                <div class="col-lg-12 p-xxs">
					                    <?php if(isset($item['titulo'])) {?>
					                    <h2 class="bg-muted p-sm m-b-lg">Paquete: <?php echo $item['titulo']; ?><button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Paquete en cuyo detalle se mostrarán los paquetes relacionados." title=""> <i class="fa fa-question"></i></button></h2>
					                    <h3>Paquetes Relacionados</h3>
					                    <hr class="m-t-sm">
					                </div>
				                    <ul class="listado-paquetes p-0 m-0">
				                    	<?php if(!empty($listado)) { foreach($listado as $lista) { ?>	
				                         <li>
				                         	<p><input type="checkbox" name="relaciones_<?php echo $idioma['extension'];?>[]" value="<?php echo $lista['id'];?>" <?php if(!empty($relacionados)) { foreach($relacionados as $rela) { if($lista['id'] == $rela['id_servicio']) {echo ' checked';} } } ?>>
										<?php echo $lista['titulo']; ?> </p></li>
										<?php } } else { echo 'No se encontraron resultados';} ?>
					               </ul>
				                    <?php } else {?>
					                    <h3 class="p-sm m-b-lg">No hay paquete cargado para este idioma</h3>
				                    </div>
				                    <?php } ?>
				                </div>
							</div>
	                     </div>
                       	<?php } ?>
					  <!-- Fin Items Idiomas -->
                     <?php echo form_close();?>
                     
                    </div>
                 </div>
             </div>                 
         </div>
     </div>     

<!-- SUMMERNOTE -->
<script src="<?php echo base_url('assets/js/plugins/summernote/summernote.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/summernote-grid.js'); ?>"></script>

<script>
$('.summernote').summernote({
        placeholder: 'Ingrese contenido...',
        tabsize: 2,
        height: 250,
        toolbar: [
          ['insert', ['file'], ['image']],
          ['style', ['style']],
          ['font', ['bold', 'italic', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link']],
          ['view', ['codeview']],
          ['insert', ['grid']]
        ],
        styleTags: ['p', 'code', 'span', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6']
  }).on("summernote.enter", function(we, e) {
      $(this).summernote('pasteHTML', '<br>&VeryThinSpace;');
      e.preventDefault();
});

$('[data-toggle="tooltip"]').tooltip(); 
</script>