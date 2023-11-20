<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

		<div id="content-wrapper">
			<div class="container-fluid">
	            <div class="row">
					<div class="col-lg-12">
						<div class="main-title">
							<?php if ($this->usuario->perfil == 'admin') { ?>
					        <div class="btn-group float-right right-action">
					            <a href="<?php echo base_url('administracion/contactos/ingresar/'); ?>" class="right-action-link text-gray">Crear nuevo usuario</a>
					        </div>
					        <?php } ?>
							<h6>Gestión de usuarios</h6>
						</div>
					</div>
				</div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
								<tr>
                                    <th>Contacto</th>
                                    <?php if ($this->usuario->perfil == 'reseller'): ?><th>Empresa</th><?php endif; ?>
                                    <th class="text-center">Usuario</th>
                                    <th class="text-center">Perfil</th>
                                    <th class="text-center d-none d-lg-block">Ultima visita</th>
                                    <th class="text-center">Acciones</th>
                                    <th class="text-center">Estado</th>
								</tr>
                            </thead>
                            <tbody>
                            	<?php foreach ($contactos as $contacto) { ?>
                                <tr>
                                    <td><?php echo $contacto['contacto']; ?></td>
                      				<td class="text-center"><?php echo $contacto['username']; ?></td>
                      				<td class="text-center"><?php echo $contacto['perfil']; ?></td>
                                    <td class="text-center d-none d-lg-block"><?php echo formatear_fecha($contacto['ultima_visita'], 'd-m-Y H:i', ' Hs', $this->usuario->timezone); ?></td>
                                    <td class="text-center">
                                        <a href="<?php echo base_url('tickets/ingresar?id_empresa=' . $contacto['id_empresa'] . '&id_contacto=' . $contacto['id']); ?>"><i class="fa fa-ticket"></i></a>
                                        <?php if (isset($contacto['username'])) { ?>
											&nbsp;&nbsp;<a href="<?php echo base_url('micuenta/perfil/password/' . $contacto['id']); ?>"><span class="fa fa-unlock-alt"></span></a>
                                        <?php } ?>
                                        &nbsp;&nbsp;<a href="<?php echo base_url('administracion/contactos/modificar/' . $contacto['id']); ?>"><span class="fa fa-edit"></span></a>
	                                </td>
	                                <td class="text-center"><span class="label <?php echo $contacto['estado_ui_class']; ?>"><?php echo $contacto['estado']; ?></span></td>
                                </tr>
								<? } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="<?php echo ($this->usuario->perfil == 'reseller') ? 7 : 6; ?>">
	                                    <?php if (isset($paginado)) echo $paginado; ?>
	                                
<!--
										$config['full_tag_open'] = '<ul class="pagination justify-content-center pagination-sm mb-0">';
										$config['full_tag_close'] = '</ul>';
										$config['first_link'] = false;
										$config['last_link'] = false;
										$config['first_tag_open'] = '<li class="page-item">';
										$config['first_tag_close'] = '</li>';
										$config['prev_link'] = 'Anterior';
										$config['prev_tag_open'] = '<li class="page-item prev">';
										$config['prev_tag_close'] = '</li>';
										$config['next_link'] = 'Siguiente';
										$config['next_tag_open'] = '<li class="page-item">';
										$config['next_tag_close'] = '</li>';
										$config['last_tag_open'] = '<li class="page-item">';
										$config['last_tag_close'] = '</li>';
										$config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
										$config['cur_tag_close'] = '</a></li>';
										$config['num_tag_open'] = '<li class="page-item"><a href="#" class="page-link">';
										$config['num_tag_close'] = '</a></li>';
										
		                                <ul class="pagination justify-content-center pagination-sm mb-0">
					                        <li class="page-item disabled">
					                           <a tabindex="-1" href="#" class="page-link">Previous</a>
					                        </li>
					                        <li class="page-item active"><a href="#" class="page-link">1</a></li>
					                        <li class="page-item"><a href="#" class="page-link">2</a></li>
					                        <li class="page-item"><a href="#" class="page-link">3</a></li>
					                        <li class="page-item">
					                           <a href="#" class="page-link">Next</a>
					                        </li>
					                    </ul>
-->
	                                
	                                </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>