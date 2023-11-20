<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

			<div class="wrapper wrapper-content animated fadeInRight">
				
				<?php if ($this->usuario->perfil == 'admin') { ?>
				<div class="row">
		            <div class="col-lg-3">
			            <div class="widget style1 yellow-bg">
			                <a href="<?php echo base_url('micuenta/perfil'); ?>" style="color: #fff">
			                    <div class="row">
			                        <div class="col-xs-4">
			                            <i class="fa fa-gears fa-5x"></i>
			                        </div>
			                        <div class="col-xs-8 text-right">
			                            <span><?php echo $this->lang->line('cms_users-perfil'); ?></span>
			                            <h2 class="font-bold"><?php echo $this->usuario->perfil; ?></h2>
			                        </div>
			                    </div>
		                    </a>
		                </div>
		            </div>
		            <div class="col-lg-3">
			            <div class="widget style1 yellow-bg">
			                <a href="<?php echo base_url('micuenta/balance'); ?>" style="color: #fff">
			                    <div class="row">
			                        <div class="col-xs-4">
			                            <i class="fa fa-money fa-5x"></i>
			                        </div>
			                        <div class="col-xs-8 text-right">
			                            <span><?php echo $this->lang->line('cms_balance'); ?></span>
			                            <h2 class="font-bold">$<?php echo ($balance['saldo']) ? $balance['saldo'] : 0; ?></h2>
			                        </div>
			                    </div>
		                    </a>
		                </div>
		            </div>
		            <div class="col-lg-3">
			            <div class="widget style1 yellow-bg">
			                <a href="<?php echo base_url('administracion/contactos'); ?>" style="color: #fff">
			                    <div class="row">
			                        <div class="col-xs-4">
			                            <i class="fa fa-users fa-5x"></i>
			                        </div>
			                        <div class="col-xs-8 text-right">
			                            <span><?php echo $this->lang->line('cms_users-usuarios'); ?></span>
			                            <h2 class="font-bold"><?php echo $contactos; ?></h2>
			                        </div>
			                    </div>
		                    </a>
		                </div>
		            </div>
		            <div class="col-lg-3">
			            <div class="widget style1 yellow-bg">
			                <a href="<?php echo base_url('multimedia/proyectos'); ?>" style="color: #fff">
			                    <div class="row">
			                        <div class="col-xs-4">
			                            <i class="fa fa-rss fa-5x"></i>
			                        </div>
			                        <div class="col-xs-8 text-right">
			                            <span><?php echo $this->lang->line('cms_media-canales'); ?></span>
			                            <h2 class="font-bold"><?php echo ($media['proyectos']) ? $media['proyectos'] : 0; ?></h2>
			                        </div>
			                    </div>
		                    </a>
		                </div>
		            </div>
		            <div class="col-lg-3">
			            <div class="widget style1 yellow-bg">
			                <a href="<?php echo base_url('multimedia'); ?>" style="color: #fff">
			                    <div class="row">
			                        <div class="col-xs-4">
			                            <i class="fa fa-file-o fa-5x"></i>
			                        </div>
			                        <div class="col-xs-8 text-right">
			                            <span><?php echo $this->lang->line('cms_media-archivos'); ?></span>
			                            <h2 class="font-bold"><?php echo ($media['archivos']) ? $media['archivos'] : 0; ?></h2>
			                        </div>
			                    </div>
		                    </a>
		                </div>
		            </div>
		            <div class="col-lg-3">
			            <div class="widget style1 yellow-bg">
			                <a href="<?php echo base_url('multimedia'); ?>" style="color: #fff">
			                    <div class="row">
			                        <div class="col-xs-4">
			                            <i class="fa fa-cloud fa-5x"></i>
			                        </div>
			                        <div class="col-xs-8 text-right">
			                            <span><?php echo $this->lang->line('cms_users-storage'); ?></span>
			                            <h2 class="font-bold"><?php echo byte_format($media['espacio']*1024); ?></h2>
			                        </div>
			                    </div>
		                    </a>
		                </div>
		            </div>
		        </div>
		        <?php } ?>
		        
				<?php if (isset($facturas)) { ?>
	            <div class="row">
		            <?php foreach ($facturas as $factura) { ?>
	                <div class="col-md-4">
	                    <div class="payment-card">
	                        <div class="row">
	                                <i class="fa fa-file payment-icon-big text-danger mi_cuenta_icono"></i>
		                           <div class="mi_cuenta_detalle">
			                            <?php echo formatear_fecha($factura['fecha'], 'd-m-Y', '<small>%s</small>', $this->usuario->timezone); ?>
		                        	<br>
		                        	<small><?php echo $factura['razon_social']; ?></small>
		                        	<br>
		                        	<strong><?php echo $factura['simbolo']; ?><?php echo $factura['total_neto']; ?></strong></div>
	                        </div>
	                        <h2><?php echo $factura['comprobante']; ?></h2>
	                        <div class="row">
	                            <div class="col-sm-6">
	                                <small>
	                                    <?php echo $this->lang->line('cms_users-vencimiento'); ?>: <strong><?php echo formatear_fecha($factura['vencimiento'], 'd-m-Y', null, $this->usuario->timezone); ?></strong>
	                                </small>
	                            </div>
	                            <div class="col-sm-6 text-right">
		                            <a href="<?php echo base_url('micuenta/facturas/detalle/' . $factura['id']); ?>" class="btn btn-xs btn-primary"><?php echo $this->lang->line('cms_users-ver_factura'); ?></a>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	                <?php } ?>
	            </div>
	            <?php } ?>
	            
	            
	            <?php if (isset($servicios)) { ?>
	            <div class="row">
		            <?php foreach ($servicios as $servicio) { ?>
	                <div class="col-sm-6 col-md-4 col-lg-3">
	                    <div class="ibox">
	                        <div class="ibox-content product-box">
	
	                            <div class="product-imitation">
	                                <a href="<?php echo base_url('micuenta/servicios/detalle/' . $servicio['id']); ?>">
		                                
		                                <?php switch ($servicio['id_tipo'])
		                                {
		                                	case 1:
		                                		$ico = '<i class="fa fa-globe fa-5x color:#ccc;"></i>';
		                                		break;
		                                	case 2:
		                                		$ico = '<i class="fa fa-cloud fa-5x"></i>';
		                                		break;
		                                	case 3:
		                                		$ico = '<i class="fa fa-database fa-5x"></i>';
		                                		break;
		                                	case 4:
		                                		$ico = '<i class="fa fa-paper-plane-o fa-5x"></i>';
		                                		break;
		                                	case 5:
		                                		$ico = '<i class="fa fa-plane fa-5x"></i>';
		                                		break;
		                                	case 6:
		                                		$ico = '<i class="fa fa-tasks fa-5x"></i>';
		                                		break;
		                                	case 7:
		                                		$ico = '<i class="fa fa-phone fa-5x"></i>';
		                                		break;
		                                	case 9:
		                                		$ico = '<i class="fa fa-google-wallet fa-5x"></i>';
		                                		break;
		                                	case 10:
		                                		$ico = '<i class="fa fa-lock fa-5x"></i>';
		                                		break;
		                                	case 11:
		                                		$ico = '<i class="fa fa-film fa-5x"></i>';
		                                		break;
		                                	case 12:
		                                		$ico = '<i class="fa fa-exchange fa-5x"></i>';
		                                		break;
		                                	case 13:
		                                		$ico = '<i class="fa fa-signal fa-5x"></i>';
		                                		break;
		                                	default:
		                                		$ico = '<i class="fa fa-cogs fa-5x color:#ccc !important;"></i>';
		                                		break;
		                                }
		                                
		                                echo $ico;
		                                
		                                ?>
		                            </a>
	                            </div>
	                            <div class="product-desc">
		                            <?php if ($this->usuario->perfil == 'admin') { ?>
	                                <span class="product-price">
	                                    <?php echo $servicio['simbolo'] . ' ' . $servicio['total']; ?>
	                                </span>
	                                <?php } ?>
	                                <small class="text-muted"><?php echo $servicio['categoria_padre']; ?></small>
	                                <a href="<?php echo base_url('micuenta/servicios/detalle/' . $servicio['id']); ?>" class="product-name"><?php echo $servicio['categoria']; ?></a>
									<div class="small m-t-xs">
	                                    <?php echo character_limiter($servicio['descripcion'], 70); ?>
	                                </div>
	                                <div class="m-t text-righ">
		                                <span class="label <?php echo $servicio['estado_ui_class']; ?>"><?php echo $servicio['estado']; ?></span>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
	                <?php } ?>
	            </div>
	            <?php } ?>
	            
	        </div>