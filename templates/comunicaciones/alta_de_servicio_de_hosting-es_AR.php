<?php include('header.php'); ?>
<tr>
	<td>
		<h2 style="font-size:30px; color:#FF6666; border-bottom:1px solid lightgrey;">Alta de Servicio</h2>
		<br><br>
	</td>
</tr>
<tr>
	<td>		
		<strong><?php echo $_POST['contacto']; ?>,</strong><br>
		<br>
		Su plan (<strong><?php echo $_POST['descripcion']; ?></strong>) ha sido dado de alta exitosamente. Estos son sus datos de acceso:<br>
		<br>
		<strong>URL:</strong> <a href="http://<?php echo $_POST['dominio']; ?>" style="color:#FF6666;">http://<?php echo $_POST['dominio']; ?></a><br>
		<strong>Panel de Control:</strong> <a href="http://<?php echo $_POST['dominio'] . '/cpanel'; ?>" style="color:#FF6666;">http://<?php echo $_POST['dominio'] . '/cpanel'; ?></a><br>
		<strong>Usuario:</strong> <?php echo $_POST['user']; ?><br>
		<strong>Contrase&ntilde;a:</strong> <?php echo $_POST['pass']; ?><br>
		<br>
		Estos son nuestros DNS para la delegaci&oacute;n de su dominio:<br><br>
		<strong>DNS primario:</strong> ns1.revisionalpha.com<br>
		<strong>DNS secundario:</strong> ns2.revisionalpha.com<br><br>

		<a href="https://www.revisionalpha.com/instructivos/tramites/delegacion-de-dominios-nicar/" style="color:#FF6666;">Delegaci&oacute;n de dominios (Nic.ar)</a><br><br>

		Si su dominio a&uacute;n no ha sido delegado hacia nuestros servidores puede usar los siguientes datos de acceso temporal:<br>
		<br>
		<strong>URL (temporal):</strong> <a href="http://<?php echo $_POST['ip']; ?>/~<?php echo $_POST['user']; ?>" style="color:#FF6666;">http://<?php echo $_POST['ip']; ?>/~<?php echo $_POST['user']; ?></a><br>
		<strong>Panel de Control (temporal):</strong> <a href="http://<?php echo $_POST['ip']; ?>/cpanel" style="color:#FF6666;">http://<?php echo $_POST['ip']; ?>/cpanel</a><br>
		<strong>Usuario:</strong> <?php echo $_POST['user']; ?><br>
		<strong>Contrase&ntilde;a:</strong> <?php echo $_POST['pass']; ?><br>
		<br>
		A continuaci&oacute;n le dejamos un par de instructivos para que pueda empezar a trabajar con su cuenta:<br>
		<br>
		<a href="https://www.revisionalpha.com/instructivos/cpanel/acceder-a-cpanel/" style="color:#FF6666;">Acceder al panel de control (cPanel)</a><br>
		<a href="https://www.revisionalpha.com/instructivos/cpanel/crear-cuenta-de-correo/" style="color:#FF6666;">Crear cuentas de correo (cPanel)</a><br>
		<a href="https://www.revisionalpha.com/instructivos/cpanel/administrador-de-archivos/" style="color:#FF6666;">Administrador de archivos (cPanel)</a><br>
		<br>
		
		Para ver el estado de sus servicios y el balance de su cuenta puede hacerlo desde el <a href="<?php echo 'https://cms.revisionalpha.com/user/login/?username=' . $_POST['username'] . '&password=' . $_POST['hash'] . '&redirect=https://cms.revisionalpha.com/micuenta/'; ?>" style="color:#FF6666;">&aacute;rea de clientes</a> de nuestro sitio.
		<br>
		<br>
		<span>Para ingresar a su perfil de usuario y modificar sus datos puede hacerlo haciendo <a href="<?php echo 'https://cms.revisionalpha.com/user/login/?username=' . $_POST['username'] . '&password=' . $_POST['hash'] . '&redirect=https://cms.revisionalpha.com/micuenta/perfil/'; ?>" style="color:#FF6666;">click aqu&iacute;</a>.</span>
		<br>
		<br>
		<table width="100%" bgcolor="#5CA7D7" border="0" cellpadding="0" cellspacing="10">
			<tr>
				<td>
					<span style="color:#FFFFFF;"><strong>IMPORTANTE</strong><br><br>Si todav&iacute;a no ha completado los datos de perfil recuerde que es necesario que lo haga antes de finalizar el corriente mes para poder realizar la facturaci&oacute;n de los servicios contratados. Si finalizado el corriente mes los datos de su cuenta contin&uacute;an incompletos sus servicios ser&aacute;n suspendidos temporalmente hasta que los mencionados sean completados.</span>
				</td>
			</tr>
		</table>
	</td>
</tr>
<?php include('footer.php'); ?>