<?php include('header.php'); ?>
<tr>
	<td>
		<h2 style="font-size:30px; color:#FF6666; border-bottom:1px solid lightgrey;">Confirmaci&oacute;n de recupero de contrase&ntilde;a</h2>
		<br><br>
	</td>
</tr>
<tr>
	<td>		
		<strong><?php echo $_POST['contacto'] ?>,</strong><br>
		<br>
		Hemos recibido una solicitud de recupero de contrase&ntilde;a de su cuenta. Haga click en el siguiente link para confirmar esta petici&oacute;n y siga los pasos sugeridos:
		<br><br>
		<a href="https://cms.revisionalpha.com/user/password-reset-confirm/<?php echo $_POST['token']; ?>" style="color:#FF6666;">Confirmar solicitud de recupero de contrase&ntilde;a</a>
		<br>
		<br>
		Si usted no se ha realizado esta solicitud, por favor desestime este mail.
	</td>
</tr>
<?php include('footer.php'); ?>