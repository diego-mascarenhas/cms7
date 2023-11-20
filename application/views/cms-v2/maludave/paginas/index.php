<style>
.lista-secciones, .lista-secciones ul { width:80%; list-style:none; margin:10px 10%; padding:0;}
.lista-secciones li {border-bottom:0;}
.lista-secciones li .btn { margin-botton:0; margin-left: 5px;}
.lista-secciones li p { padding: 8px 10px; line-height: 30px;background: #F5F5F6; }
.lista-secciones ul { width: 96%;list-style: none;margin: 10px 2% 10px;padding: 0; }
.lista-secciones ul li {border-bottom:1px dotted #ccc;}
.lista-secciones ul li p { background: #fff; padding: 4px 10px 4px; }
.lista-secciones ul li:last-child { border:0;}
.bg-inactiva {color: #a94442;background: #f2dede !important;border-color: #ebccd1;}
</style>
       <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-8 col-sm-8 col-xs-8">
                <h2>Sitio web</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="<?php echo base_url('cms-v2/paginas/');?>">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('cms-v2/paginas/');?>">Páginas</a>
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
                        <h5>Listado de Secciones</h5>
                    </div>

                    <div class="ibox-content">
                        <div class="table-responsive">
				    <ul class="lista-secciones">
	                <?php		
						function menuTester($menu1,$nivel=null)
						{							
							$CI =& get_instance();
							
							?>
							<?php foreach($menu1 as $obj): ?>
								<?php if (!isset($obj['url'])) { ?>
								<li>
			                    	<p<?php echo ($obj['estado'] == 1) ? ' class="bg-inactiva"' : '';?>>
								<?php } else { ?>
								<li>
			                    	<p<?php echo ($obj['estado'] == 1) ? ' class="bg-inactiva"' : '';?>>
			                    		<a href="<?php if(isset($obj['url'])) { echo (isset($obj['categoria'])) ? base_url('cms-v2/carrito/categorias/modificar/'.$obj['categoria']) : base_url('cms-v2/paginas/modificar/'.$obj['id_contenido']); } else { echo '#'; } ?>">
								<?php } ?>
				                    	<?php if (!isset($nivel)) { ?>
				                    	<?php echo $obj['seccion']; ?>
				                        <a title="Cambiar publicaci&oacute;n" id="item" href="#" data-toggle="modal" data-seccion='<?php echo $obj['seccion'];?>?' data-estado='<?php echo $obj['estado'];?>' data-id="<?php echo $obj['id'];?>" data-target="#myModalPublicacion" <?php echo ($obj['estado'] == 3) ? 'class="btn btn-sm btn-primary pull-right"><i class="fa fa-download"></i> Dejar de publicar' : 'class="btn btn-sm btn-primary pull-right"><i class="fa fa-upload"></i> Publicar secci&oacute;n &nbsp;';?></a>
				                        
				                        <a href="<?php echo (isset($obj['categoria'])) ? base_url('cms-v2/carrito/categorias/modificar/'.$obj['categoria']) : base_url('cms-v2/paginas/modificar/'.$obj['id_contenido']);?>" title="Modificar p&aacute;gina" class="btn btn-primary btn-sm pull-right"><i class="fa fa-pencil"></i> Modificar</a> 
				                        
				                        <a href="<?php echo base_url('cms-v2/paginas/configuracion/').$obj['id_contenido']; ?>" title="Configurar p&aacute;gina" class="btn btn-sm btn-primary pull-right"><i class="fa fa-cog"></i> Configuraci&oacute;n</a> 
			                        </p>
			                    	<?php } else { ?>
			                    		<?php echo $obj['seccion']; ?></a>
				                        <a title="Cambiar publicaci&oacute;n" id="item" href="#" data-toggle="modal" data-estado='<?php echo $obj['estado'];?>' data-seccion='<?php echo $obj['seccion'];?>?' data-id="<?php echo $obj['id'];?>" data-target="#myModalPublicacion" <?php echo ($obj['estado'] == 3) ? 'class="btn btn-sm btn-primary pull-right"><i class="fa fa-download"></i> Dejar de publicar' : 'class="btn btn-sm btn-primary pull-right"><i class="fa fa-upload"></i> Publicar secci&oacute;n &nbsp;';?></a>				                    		
			                    		<a href="<?php echo (isset($obj['categoria'])) ? base_url('cms-v2/carrito/categorias/modificar/'.$obj['categoria']) : base_url('cms-v2/paginas/modificar/'.$obj['id_contenido']);?>" title="Modificar p&aacute;gina" class="btn btn-primary btn-sm pull-right"><i class="fa fa-pencil"></i> Modificar</a> 
			                    		</p>
			                    	<?php } ?>
				                	
				                	<?php if (isset($obj['hijos'])): ?>
				                		<ul>
				                			<?php menuTester($obj['hijos'], $obj['nivel']); ?>
				                		</ul>
				                	<?php endif; ?>
								</li>
							<?php endforeach; ?>
							<?php 
						}
	                	menuTester($menu1);
	                ?>

					</ul>
					</div>
                </div>

                    <!-- Modal -->
                    <div class="modal inmodal" id="myModalPublicacion" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
	                        <div class="modal-content animated">
	                            <div class="modal-header">
	                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
	                                <h4 class="modal-title">Cambiar publicaci&oacute;n</h4>
	                            </div>
	                            <div class="modal-body">
	                                <p>&iquest;Est&aacute; seguro de cambiar publicaci&oacute;n de<strong> <input type="text" name="seccion" id="seccion" value="" style="border:1px solid #f8fafb; background:#f8fafb; width:auto !important;"/></strong></p>
		                            <div class="modal-footer">
			                            <form name="eliminar" class="form_eliminar" method="post" action="<?php echo base_url('cms-v2/paginas/publicar/'); ?>">
			                            	<input type="hidden" name="id" id="id" value="" />
			                            	<input type="hidden" name="estado" id="estado" value="" />
			                                <input type="submit" class="btn btn-primary" value="Cambiar publicaci&oacute;n">
			                            </form>
		                            </div>
		                       </div>
	                        </div>
	                     </div>
                    </div>
                    <!-- Fin Modal -->

                </div>
            </div>
            </div>
        </div>
<script>
  $('#myModalPublicacion').on('show.bs.modal', function(e) {    
     var id = $(e.relatedTarget).data().id;
     var seccion = $(e.relatedTarget).data().seccion;
     var estado = $(e.relatedTarget).data().estado;
      $(e.currentTarget).find('#id').val(id);
      $(e.currentTarget).find('#seccion').val(seccion);
      $(e.currentTarget).find('#estado').val(estado);
  });

</script>        