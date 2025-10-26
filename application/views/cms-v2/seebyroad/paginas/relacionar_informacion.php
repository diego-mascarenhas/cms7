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
                        <strong>Relacionar Destinos</strong>
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
		                 <h5>Relacionar destinos para <a href="<?php echo base_url('cms-v2/paginas/modificar_informacion/'.$detalle['id_con_contenido'].'/'.$detalle['id']);?>"><?php echo $detalle['titulo'];?></a> del destino <a href="<?php echo base_url('cms-v2/paginas/modificar/'.$detalle['id_con_contenido']);?>"><?php echo $padre['seccion'];?></a> en idioma <a><?php echo($detalle['idioma'] == 'en') ? 'inglés' : 'español';?></a></h5></div>
	                    
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
			                    <h2 class="bg-muted p-sm m-b-lg">Item Destino: <?php echo $detalle['titulo']; ?><button type="button" class="btn btn-primary btn-circle m-l-md" data-toggle="tooltip" data-placement="top" data-original-title="Destino en cuyo detalle se mostrarán los destinos relacionados." title=""> <i class="fa fa-question"></i></button></h2>
			                    <h3>Destinos Relacionados</h3>
			                    <hr class="m-t-sm">
			
								<?php
									$CI =& get_instance();
									$CI->load->model("Paginas_model");
									$parametros['estado'] = 3;
									$listado1 = $CI->Paginas_model->getContenidoAdicionalIdioma(571, 305, $detalle['idioma'], $parametros);
									$listado2 = $CI->Paginas_model->getContenidoAdicionalIdioma(573, 321, $detalle['idioma'], $parametros);
									$listado3 = $CI->Paginas_model->getContenidoAdicionalIdioma(575, 323, $detalle['idioma'], $parametros);
									$listado4 = $CI->Paginas_model->getContenidoAdicionalIdioma(577, 325, $detalle['idioma'], $parametros);
									$listado5 = $CI->Paginas_model->getContenidoAdicionalIdioma(579, 327, $detalle['idioma'], $parametros);
									$listado6 = $CI->Paginas_model->getContenidoAdicionalIdioma(581, 319, $detalle['idioma'], $parametros);
									$listado7 = $CI->Paginas_model->getContenidoAdicionalIdioma(581, 329, $detalle['idioma'], $parametros);
									$listado = array_merge($listado1, $listado2, $listado3, $listado4, $listado5, $listado6, $listado7);
								?>

			                <ul class="listado-paquetes p-0 m-0">
			                	<?php if(!empty($listado)) { foreach($listado as $lista) { ?>	
			                     <li>
			                     	<p><input type="checkbox" name="relaciones[]" value="<?php echo $lista['id'];?>" <?php if(!empty($relacionados)) { foreach($relacionados as $rela) { if($lista['id'] == $rela['id']) {echo ' checked';} } } ?>>
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

<script>
$('[data-toggle="tooltip"]').tooltip(); 
</script>