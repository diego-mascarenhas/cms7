<?php include('header.php'); ?>
<tr>
	<td>
		<h2 style="font-size:30px; color:#FF6666; border-bottom:1px solid lightgrey;">Solicitud de Alta de Servicio</h2>
		<br><br>
	</td>
</tr>
<tr>
	<td>		
		<strong><?php echo $_POST['contacto']; ?>,</strong><br>
		<br>
		Hemos recibido su petici&oacute;n de alta de servicio <strong><?php echo $_POST['descripcion']; ?></strong> por un precio de <strong>$<?php echo $_POST['valor']; ?></strong> (+ I.V.A. por mes). Si estos datos son correctos por favor haga click en el link a continuaci&oacute;n para confirmar su solicitud y dar de alta el plan.
		<br>
		<br>
		<a href="<?php echo 'https://cms.revisionalpha.com/user/login/?username=' . $_POST['username'] . '&password=' . $_POST['hash'] . '&redirect=https://cms.revisionalpha.com/micuenta/servicios/activar/' . $_POST['id']; ?>" style="color:#FF6666;">Confirmar solicitud de alta de plan</a>
		<br>
		<br>
		Si usted no ha realizado ninguna solicitud por ninguno de nuestros servicios, desestime este mail.
	</td>
</tr>
<?php include('footer.php'); ?>