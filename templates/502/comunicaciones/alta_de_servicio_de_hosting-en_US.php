<?php include('header.php'); ?>
<tr>
	<td>
		<h2 style="font-size:30px; color:#FF1A1D; border-bottom:1px solid lightgrey;">Hosting plan</h2>
		<br><br>
	</td>
</tr>
<tr>
	<td>		
		<strong><?php echo $_POST['contacto']; ?>,</strong><br>
		<br>
		Your hosting plan (<strong><?php echo $_POST['descripcion']; ?></strong>) has been successfuly created. These are your access credentials:<br>
		<br>
		<br>
		<strong>URL:</strong> <a href="http://<?php echo $_POST['dominio']; ?>" style="color:#FF1A1D;">http://<?php echo $_POST['dominio']; ?></a><br>
		<strong>Control panel:</strong> <a href="http://<?php echo $_POST['dominio'] . '/cpanel'; ?>" style="color:#FF1A1D;">http://<?php echo $_POST['dominio'] . '/cpanel'; ?></a><br>
		<strong>User:</strong> <?php echo $_POST['user']; ?><br>
		<strong>Password:</strong> <?php echo $_POST['pass']; ?><br>
		<br>
		Please configure these nameserver profiles for your account:<br><br>
		<strong>primary DNS:</strong> ns1.revisionalpha.com<br>
		<strong>secondary DNS:</strong> ns2.revisionalpha.com<br><br>

		<a href="https://www.revisionalpha.com/instructivos/tramites/delegacion-de-dominios-nicar/" style="color:#FF1A1D;">Domain delegation in Argentina (Nic.ar)</a><br><br>

		If your domain has not yet been delegated to our servers you can use the following temporary access data:<br>
		<br>
		<strong>URL: (temporary):</strong> <a href="http://<?php echo $_POST['ip']; ?>/~<?php echo $_POST['user']; ?>" style="color:#FF1A1D;">http://<?php echo $_POST['ip']; ?>/~<?php echo $_POST['user']; ?></a><br>
		<strong>Control panel (temporary):</strong> <a href="http://<?php echo $_POST['ip']; ?>/cpanel" style="color:#FF1A1D;">http://<?php echo $_POST['ip']; ?>/cpanel</a><br>
		<strong>Usuario:</strong> <?php echo $_POST['user']; ?><br>
		<strong>Contrase&ntilde;a:</strong> <?php echo $_POST['pass']; ?><br>
		<br>
		Here we leave a couple of instructions so you can start working with your account:<br>
		<br>
		<a href="https://www.revisionalpha.com/instructivos/cpanel/acceder-a-cpanel/" style="color:#FF1A1D;">Access the control panel (cPanel)</a><br>
		<a href="https://www.revisionalpha.com/instructivos/cpanel/crear-cuenta-de-correo/" style="color:#FF1A1D;">Create email accounts (cPanel)</a><br>
		<a href="https://www.revisionalpha.com/instructivos/cpanel/administrador-de-archivos/" style="color:#FF1A1D;">File manager (cPanel)</a><br>
		<br>
		
		To check your account and services status, please <a href="<?php echo 'https://cms.revisionalpha.com/user/login/?username=' . $_POST['username'] . '&password=' . $_POST['hash'] . '&redirect=https://cms.revisionalpha.com/micuenta/'; ?>" style="color:#FF1A1D;">login to the customer area</a> of our site.
		<br>
		<br>
		<span><a href="<?php echo 'https://cms.revisionalpha.com/user/login/?username=' . $_POST['username'] . '&password=' . $_POST['hash'] . '&redirect=https://cms.revisionalpha.com/micuenta/perfil/'; ?>" style="color:#FF1A1D;">Access to modify your user profile.</a></span>
		<br>
		<br>
		<table width="100%" bgcolor="#39A0ED" border="0" cellpadding="0" cellspacing="10">
			<tr>
				<td>
					<span style="color:#FFFFFF;"><strong>IMPORTANT</strong><br><br>If you have not yet completed the profile data, remember it is necessary to do so before the end of the current month to be billed adequately. If your account data is still incomplete at the end of the current month, your services may be temporarily suspended until your profile is updated.</span>
				</td>
			</tr>
		</table>
	</td>
</tr>
<?php include('footer.php'); ?>