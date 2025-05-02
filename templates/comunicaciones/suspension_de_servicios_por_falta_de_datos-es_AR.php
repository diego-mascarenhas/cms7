<?php include('header.php'); ?>
<tr>
	<td>
		<h2 style="font-size:30px; color:#FF1A1D; border-bottom:1px solid lightgrey;">Suspensi&oacute;n de Servicios</h2>
		<br><br>
	</td>
</tr>
<tr>
	<td>		
		<strong><?php echo $_POST['contacto']; ?>,</strong><br>
		<br>
		Le informamos que los servicios asociados a su cuenta han sido <strong>suspendidos</strong> por falta de datos en su perfil.
		<br>
		<br>
		Para completar sus datos y reactivar los servicios deberá hacerlo desde el <a href="<?php echo 'https://cms.revisionalpha.com/user/login/?username=' . $_POST['username'] . '&password=' . $_POST['hash'] . '&redirect=https://cms.revisionalpha.com/micuenta/perfil/'; ?>" style="color:#FF1A1D;">&aacute;rea de clientes</a> de nuestro sitio web.
	</td>
</tr>
<?php include('footer.php'); ?>