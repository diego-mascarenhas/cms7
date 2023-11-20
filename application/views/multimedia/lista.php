<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-xs-8 col-sm-8 col-md-6 col-lg-6">
	                <h2>Multimedia</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li class="active">
	                        <strong>Multimedia</strong>
	                    </li>
	                </ol>
	            </div>
	            <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6">
		            <div class="title-action">
			            <?php if ($this->usuario->perfil == 'reseller') { ?>
		            		<a href="<?php echo base_url('multimedia/reporte/'); ?>" class="btn btn-primary btn-sm">Reporte</a>
						<?php } ?>
					</div>
	            </div>
	        </div>
			
			<div class="wrapper wrapper-content animated fadeInRight">
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox float-e-margins">
	                        <div class="ibox-content">
	                            <div class="table-responsive">
	                                <table class="table table-striped">
	                                    <thead>
	                                    <tr>
	                                        <th class="text-center">Tipo</th>
	                                        <th>Nombre</th>
	                                        <th>Empresa</th>
	                                        <th class="text-center">Peso</th>
	                                        <th class="text-center">Estado</th>
	                                    </tr>
	                                    </thead>
	                                    <tbody>
	                                    </tbody>
	                                    <?php if (isset($medias)) { ?>
		                                	<?php foreach ($medias as $media) { ?>
		                                    <tr>
			                                    <td class="text-center">
													<?php switch ($media['tipo'])
				                                        {
				                                        	case 'imagen':
				                                        		$ico = 'fa-file-picture-o';
				                                        		break;
				                                        	case 'video':
				                                        		$ico = 'fa-film';
				                                        		break;
				                                        	case 'audio':
				                                        		$ico = 'fa-music';
				                                        		break;
				                                        	default:
				                                        		$ico = 'fa-file';
				                                        		break;
				                                        }
														?>
													<div class="icon">
	                                            		<i class="fa <?php echo $ico; ?>"></i>
	                                            		<br>
				                                        <small><?php echo $media['stream']; ?></small>
	                                            	</div>
		                                        </td>
		                                        <td>
			                                        <a href="<?php echo base_url('multimedia/detalle/' . $media['id']); ?>"><?php echo $media['nombre']; ?></a>
			                                        <br>
			                                        <small><?php echo $media['archivo']; ?></small>
			                                    </td>
		                                        <td>
			                                        <a href="<?php echo base_url('administracion/empresas/detalle/' . $media['id_empresa']); ?>"><?php echo $media['empresa']; ?></a>
			                                        <br>
			                                        <small><a href="<?php echo base_url('administracion/contactos/detalle/' . $media['id_contacto']); ?>"><?php echo $media['contacto']; ?></a></small>
			                                    </td>
												<td class="text-center">
													<?php echo byte_format($media['peso']*1024); ?>
													<br>
													<small><em><?php echo formatear_fecha($media['fecha_alta'], 'd-m-Y H:i', ' hs', $this->usuario->timezone); ?></em></small>
												</td>
												<td class="text-center">
													<span class="label <?php echo $media['estado_ui_class']; ?>"><?php echo $media['estado']; ?></span>
													<br>
													<?php if (file_exists(FCPATH . 'multimedia/procesar/' . preg_replace('/.[^.]*$/', '', $media['archivo']))) { ?>
														<small><em>(Procesando)</em></small>
													<?php } elseif ($media['tipo'] == 'video' && $media['id_stream'] != 1) { ?>
														<small><a href="<?php echo base_url('multimedia/procesar/' . $media['id']); ?>">Reprocesar</a></small>
													<?php } ?>
												</td>
		                                	</tr>
											<? } ?>
	                                    </tbody>
	                                    <tfoot>
		                                    <tr>
			                                    <td colspan="5"><?php if (isset($paginado)) echo $paginado; ?></td>
		                                    </tr>
	                                    </tfoot>
	                                    <?php } ?>
	                                </table>
	                            </div>
	
	                        </div>
	                    </div>
	                </div>
	
	            </div>
	        </div>