       <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-xs-8 col-sm-8 col-lg-8">
                <h2>Sitio web Páginas</h2>
                <ol class="breadcrumb">
                    <li><a href="/cms-v2">Home</a></li>
                    <li><a href="<?php echo base_url('cms-v2/paginas/');?>">Páginas</a></li>
                    <li class="active"><strong>Ordenar</strong></li>
                </ol>
            </div>
        </div>
            
        <div class="wrapper wrapper-content animated">
            <div class="row">
                <div class="col-lg-12">
                	<div class="ibox float-e-margins">
	                    <div class="ibox-title">
	                        <h5>Ordenar items de <a href="<?php echo base_url('cms-v2/paginas/modificar/'.$this->uri->segment(4));?>" title="Ir a la sección <?php echo $item['seccion'];?>"><?php echo $item['seccion'];?></a> en idioma 
	                        <?php 
		                        switch($this->uri->segment(6))
		                        {
			                        case 'es': echo 'español'; break;
			                        case 'en': echo 'inglés'; break;
			                        case 'po': echo 'portugués'; break;
		                        }
	                        ?></h5>
	                    </div>
                	</div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-1"></div>
                <div class="col-lg-10">
                    <ul class="sortable-list connectList agile-list" id="media">
		                <?php foreach($listado as $lista) { ?>	
                        <li class="lista" id="<?php echo $lista['id']; ?>">
							<?php if($lista['imagen']) { ?><img src="<?php echo ($lista['imagen']) ? base_url('/multimedia/thumbs/'.$lista['imagen']) : '';?>" title="" alt="" class="listados_miniatura" width="100"> <?php } ?>
                        <?php echo $lista['titulo']; echo ($lista['contenido1']) ? '<br><b>'.strip_tags($lista['contenido1']).'</b>': null;?></li>
		                <?php } ?>	
                    </ul>
                </div>	
                <div class="col-lg-1"></div>
            </div>
        </div>

<script src="<?php echo base_url('assets/js/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>
<script>
    $(document).ready(function(){
	
        $("#media").sortable({
        connectWith: ".connectList",
        update: function( event, ui ) {
            var media = $( "#media" ).sortable( "toArray" );
			$.ajax({
				type: 'POST',
				url: '<?php echo base_url('cms-v2/paginas/ordenarInformacion/media'); ?>',
				data: {items: JSON.stringify(media)},
				success: function(data) {
					console.log(data);
				}
			});
        }
    }).disableSelection();
    });
</script>