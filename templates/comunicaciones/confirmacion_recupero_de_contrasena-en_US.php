<?php include('header.php'); ?>
<tr>
	<td>
		<h2 style="font-size:30px; color:#FF1A1D; border-bottom:1px solid lightgrey;">Password recovery confirmation</h2>
		<br><br>
	</td>
</tr>
<tr>
	<td>		
		<strong><?php echo $_POST['contacto'] ?>,</strong><br>
		<br>
		We have received a password recovery request from your account. Click on the following link to confirm this request and follow the suggested steps:
		<br><br>
		<a href="https://cms.revisionalpha.com/user/password-reset-confirm/<?php echo $_POST['token']; ?>" style="color:#FF1A1D;">Confirm password recovery request</a>
		<br>
		<br>
		If you have not made this request, please disregard this message.
	</td>
</tr>
<?php include('footer.php'); ?>