<?php include('header.php'); ?>
<tr>
	<td>
		<h2 style="font-size:30px; color:#FF6666; border-bottom:1px solid lightgrey;">Suspensi&oacute;n de Servicios</h2>
		<br><br>
	</td>
</tr>
<tr>
	<td>		
		<strong><?php echo $_POST['contacto']; ?>,</strong><br>
		<br>
		Lamentablemente, al no tener respuesta, tuvimos que proceder con la suspensi&oacute;n de los servicios prestados.
		<br>
		<br>
		Para ver el estado de sus servicios y el balance de su cuenta puede hacerlo desde el <a href="<?php echo 'https://cms.revisionalpha.com/user/login/?username=' . $_POST['username'] . '&password=' . $_POST['hash'] . '&redirect=https://cms.revisionalpha.com/micuenta/'; ?>" style="color:#FF6666;">&aacute;rea de clientes</a> de nuestro sitio.
	</td>
</tr>
<?php include('footer.php'); ?>