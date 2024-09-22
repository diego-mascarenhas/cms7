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
                <h2>Sitio web</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('cms-v2/paginas');?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('cms-v2/paginas');?>" class="link">Páginas</a>
                    </li>
                    <li class="active">
                        <strong>Relacionar paquetes</strong>
                    </li>
                </ol>
            </div>

            <?php echo form_open_multipart(null, array('class'=>'form-horizontal')); ?>
			<input type="hidden" name="id" value="<?php echo $detalle['id']; ?>">
			<input type="hidden" name="idioma" value="<?php echo $detalle['idioma']; ?>">
            <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
                <a class="btn btn-white" type="submit" href="javascript:window.history.go(-1);">Cancelar</a>
                <button class="btn btn-primary" type="submit">Relacionar</button>
            </div>
        </div>
        
        <div class="wrapper wrapper-content animated fadeInRight">
	        <div class="row">
	            <div class="col-lg-12">
	                <div class="ibox float-e-margins">
	                    <div class="ibox-title">
		                 <h5>Relacionar paquetes para <a href="<?php echo base_url('cms-v2/paginas/modificar_informacion/'.$detalle['id_con_contenido'].'/'.$detalle['id']);?>"><?php echo $detalle['titulo'];?></a> del destino <a href="<?php echo base_url('cms-v2/paginas/modificar/'.$detalle['id_con_contenido']);?>"><?php echo $padre['seccion'];?></a> en idioma <a><?php echo($detalle['idioma'] == 'en') ? 'inglés' : 'español';?></a></h5></div>
	                    
	                    <div class="ibox-content">
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

			            <div class="row p-sm">
			                    <h2 class="bg-muted p-sm m-b-lg">Item Destino: <?php echo $detalle['titulo']; ?><button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Destino en cuyo detalle se mostrarán los paquetes relacionados." title=""> <i class="fa fa-question"></i></button></h2>
			                    <h3>Paquetes Relacionados</h3>
			                    <hr class="m-t-sm">
			
			                <ul class="listado-paquetes p-0 m-0">
			                	<?php if(!empty($listado)) { foreach($listado as $lista) { ?>	
			                     <li>
			                     	<p><input type="checkbox" name="relaciones[]" value="<?php echo $lista['id_servicio'];?>" <?php if(!empty($relacionados)) { foreach($relacionados as $rela) { if($lista['id_servicio'] == $rela['id_servicio']) {echo ' checked';} } } ?>>
								<?php echo $lista['titulo']; ?> </p></li>
								<?php } } else { echo 'No se encontraron resultados';} ?>
			               </ul>
			                <?php echo form_close();?>
			             </div>            
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