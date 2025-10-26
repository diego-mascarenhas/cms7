         <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-8 col-sm-8 col-xs-8">
                <h2>eLearning Categorías</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('cms-v2/elearning');?>">Elearning</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('cms-v2/elearning/categorias');?>">Categorías</a>
                    </li>
                    <li>
                        <strong>Ordenar</strong>
                    </li>
                </ol>
            </div>
            <div class="col-xs-4 col-sm-4 col-lg-4"></div>
        </div>
            
        <div class="wrapper wrapper-content animated">
            <div class="row">
                <div class="col-lg-12">
                	<div class="ibox float-e-margins">
	                    <div class="ibox-title">
	                        <h5>Ordenar categorías</h5>
	                    </div>
                	</div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-1"></div>
                <div class="col-lg-10">
                    <ul class="sortable-list connectList agile-list" id="media">
		                <?php if($listado){ foreach($listado as $lista) { ?>	
                       	 <li class="lista" id="<?php echo $lista['id']; ?>"> - <?php echo $lista['categoria'];?></li>
		                <?php } } ?>	
                    </ul>
                </div>	
                <div class="col-lg-1"></div>
            </div>
        </div>

<!-- Mainly scripts -->
<script src="<?php echo base_url('assets/js/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>

<!-- Page-Level Scripts -->
<script>
    $(document).ready(function(){
	
        $("#media").sortable({
        connectWith: ".connectList",
        update: function( event, ui ) {

            var media = $( "#media" ).sortable( "toArray" );
                            
			$.ajax({
				type: 'POST',
				url: '<?php echo base_url('cms-v2/elearning/categorias/ordenarCategorias/media'); ?>',
				data: {items: JSON.stringify(media)},
				success: function(data) {
					console.log(data);
				}
			});
			
        }
    }).disableSelection();

    });
</script>
