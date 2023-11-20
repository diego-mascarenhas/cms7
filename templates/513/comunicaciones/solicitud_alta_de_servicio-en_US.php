<?php include('header.php'); ?>
<tr>
	<td>
		<h2 style="font-size:30px; color:blueviolet; border-bottom:1px solid lightgrey;">Application for Provisioning of Service</h2>
		<br><br>
	</td>
</tr>
<tr>
	<td>		
		<strong><?php echo $_POST['contacto']; ?>,</strong><br>
		<br>
		We have received your service registration request <strong><?php echo $_POST['descripcion']; ?></strong> por a price of <strong>$<?php echo $_POST['valor']; ?></strong> (+ I.V.A. per month). If this information is correct, please click on the link below to confirm your request and proceed with the registration.
		<br>
		<br>
		<a href="<?php echo 'https://cms.revisionalpha.com/user/login/?username=' . $_POST['username'] . '&password=' . $_POST['hash'] . '&redirect=https://cms.revisionalpha.com/micuenta/servicios/activar/' . $_POST['id']; ?>" style="color:blueviolet;">Confirm service request</a>
		<br>
		<br>
		If you have not made any request for any of our services, please disregard this notice.
	</td>
</tr>
<?php include('footer.php'); ?>