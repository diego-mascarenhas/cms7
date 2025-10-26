<?php include('header.php'); ?>
<tr>
	<td>
		<h2 style="font-size:30px; color:#FF1A1D; border-bottom:1px solid lightgrey;">Datos de Perfil Incompletos</h2>
		<br><br>
	</td>
</tr>
<tr>
	<td>		
		<strong><?php echo $_POST['contacto']; ?>,</strong><br>
		<br>
		Le informamos que al d&iacute;a de la fecha los datos de perfil de su cuenta figuran incompletos.<br>
		De no completarse los datos dentro de las 72 hs se proceder&aacute; a la suspensi&oacute;n de los servicios prestados.<br>
		<br>
		Para ingresar a su perfil de usuario y completar o modificar sus datos puede hacerlo desde el <a href="<?php echo 'https://cms.revisionalpha.com/user/login/?username=' . $_POST['username'] . '&password=' . $_POST['hash'] . '&redirect=https://cms.revisionalpha.com/micuenta/perfil/'; ?>" style="color:#FF1A1D;">&aacute;rea de clientes</a> de nuestro sitio.<br>
		<br>
		Una vez completados los datos podr&aacute; reactivarlos inmediatamente desde la <a href="<?php echo 'https://cms.revisionalpha.com/user/login/?username=' . $_POST['username'] . '&password=' . $_POST['hash'] . '&redirect=https://cms.revisionalpha.com/micuenta/servicios/'; ?>" style="color:#FF1A1D;">secci&oacute;n de servicios.</a>
	</td>
</tr>
<?php include('footer.php'); ?>