<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

			<div class="row wrapper border-bottom white-bg page-heading">
	            <div class="col-lg-10">
	                <h2>Voip</h2>
	                <ol class="breadcrumb">
	                    <li>
	                        <a href="<?php echo base_url(); ?>">Home</a>
	                    </li>
	                    <li>
	                        <strong>Voip</strong>
	                    </li>
	                </ol>
	            </div>
	        </div>
	        
	        <div class="wrapper wrapper-content">
                <div class="middle-box text-center animated fadeInRightBig">
                    <h3 class="font-bold">Llamando</h3>
                    <div class="error-desc">
	                    <?php if (isset($detalle['error'])) { ?>
	                    	<?php echo $detalle['error']; ?>
	                    <?php } else { ?>
                        	Estableciendo comunicación...
						<?php } ?>
                    </div>
                </div>
            </div>