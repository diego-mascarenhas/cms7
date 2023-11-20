     <link href="<?php echo base_url('assets/css/plugins/dataTables/datatables.min.css'); ?>" rel="stylesheet" type="text/css">
           <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-8 col-sm-8 col-xs-8">
                    <h2>Encuestas</h2>
                    <ol class="breadcrumb">
                        <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
                        </li>
                        <li>
	                        <a href="<?php echo base_url('/encuestas/preguntas/'.$detalle['id']); ?>">Listado de preguntas</a>
                            <a href="">Respuestas</a>
                        </li>
                        <li class="active">
                            <strong>Listado</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-xs-4 col-sm-4 col-lg-4" style="margin-top:34px; text-align:right;">
                </div>
            </div>
            
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Resultado de respuestas <a href="<?php echo base_url('encuestas/modificar_pregunta/'.$detalle['id'].'/'.$total['id']); ?>" title="Ir al evento"><?php echo $total['titulo']; ?></a> para el evento <a href="<?php echo base_url('encuestas/modificar/'.$detalle['id']); ?>" title="Ir al evento"><?php echo $detalle['titulo']; ?></a></h5></div>
                    </div>

                    <div class="ibox-content">
	                    <h3>Total de votos = <?php echo $total['total'];?></h3>
	                    <ul class="list-unstyled">
	                    	<?php if($listado){ foreach($listado as $lista) { 
	                    	if($lista['votos'] > 0) { $porcentaje = $lista['votos']/$total['total']*100; } else { $porcentaje = 0;} ?>	
		                   	<li>
		                   		<?php echo $lista['titulo'].' '.$lista['subtitulo'].' - Votos: '.$lista['votos'].' ('.$porcentaje.'%)';?>
		                        <div class="progress">
		                        	<div style="width: <?php echo $lista['votos']; ?>%" aria-valuemax="<?php echo $total['total']; ?>" aria-valuemin="0" aria-valuenow="<?php echo $lista['votos']; ?>" role="progressbar" class="progress-bar progress-bar-success"></div>
		                        </div>
		                        <?php 
		                        	if($total['anonima'] == 0) 
		                        	{ 
										$CI =& get_instance();
										$contactos = $this->evento_model->contactosRespuestas($total['id'], $lista['id']);
					                    if($contactos)
					                    { 
								?>
		                        	<a class="btn btn-primary btn-xs m-b-md" data-toggle="collapse" href="#multiCollapseExample<?php echo $lista['id'];?>" role="button" aria-expanded="false" aria-controls="multiCollapseExample<?php echo $lista['id'];?>" style="margin-top:-10px;">Ver Contactos</a>
								    <div class="collapse multi-collapse" id="multiCollapseExample<?php echo $lista['id'];?>">
				                        <ul class="list-unstyled m-b-md">
					                        <?php foreach($contactos as $contacto) { ?>	
				                        	<li>- <?php echo $contacto['nombre'].','. $contacto['apellido'];?>
</li>
				                        	<?php } ?>
				                        </ul>
								    </div>
		                        <?php } } ?>
		                    </li>
			                <?php } } ?>	
		                </ul>
		                   
                    </div>

                </div>
            </div>
        </div>