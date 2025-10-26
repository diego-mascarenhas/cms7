<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
			
00018888C202410071EMPRESA000004695672940000092                                                                                                                                                                                                                                                                                                                
037000000000000174        00170026800020000000268596REVISION ALPHA 2024100900000004139942202410210000000413994300000000000000000000000   000000000000000                      0000000000000000000000000000000000000000                                                                                                                                        
037000000000000220        00140026700001509701674242REVISION ALPHA 2024100900000008409803202410210000000840980400000000000000000000000   000000000000000                      0000000000000000000000000000000000000000 

			<div class="wrapper wrapper-content animated fadeInRight">
	            <div class="row">
	                <div class="col-lg-12">
	                    <div class="ibox float-e-margins">
	                        <div class="ibox-content">
	                            <div class="table-responsive">
	                                <table class="table table-striped">
	                                    <tbody>
		                                	<?php foreach ($debitos as $debito) { ?>
		                                    <tr>
		                                        <td><?php echo $debito['codigo']; ?></td>
		                                        <td><?php echo $debito['empresa']; ?></td>
<!-- 		                                        <td><a href="<?php echo base_url('administracion/empresas/detalle/' . $debito['id_empresa']); ?>"><?php echo $debito['empresa']; ?></a></td> -->
		                                        <td class="text-center"><?php echo $debito['cantidad']; ?></td>
		                                        <td class="text-center"><?php echo formatear_fecha($debito['fecha'], 'd-m-Y', null, $this->usuario->timezone); ?></td>
		                                        <td class="text-right">$<?php echo $debito['saldo']; ?></td>
		                                    </tr>
											<? } ?>
											<tr>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td>&nbsp;</td>
												<td class="text-right"><strong>$<?php echo $total; ?></strong></td>
											</tr>
	                                    </tbody>
	                                </table>
	                            </div>
	
	                        </div>
	                    </div>
	                </div>
	
	            </div>
	        </div>