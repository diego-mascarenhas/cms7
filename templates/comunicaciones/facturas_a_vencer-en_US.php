<?php include('header.php'); ?>
<tr>
	<td>
		<h2 style="font-size:30px; color:#FF6666; border-bottom:1px solid lightgrey;">Due Date Notification</h2>
		<br><br>
	</td>
</tr>
<tr>
	<td>		
		<strong><?php echo $_POST['contacto']; ?>,</strong><br>
		<br>
		This notice is to inform you that invoice <?php echo $_POST['comprobante']; ?> for the amount of <?php echo $_POST['simbolo'] . number_format($_POST['total_neto'], 2, ',', '.'); ?>.-  is about to expire.
	  <br>
		<br>
		<?php if (($_POST['id_forma_pago'] == 2 || $_POST['id_forma_pago'] == 3) && ($_POST['id_factura_tipo'] == 15 || $_POST['id_factura_tipo'] == 16)) : ?>
			Bank account for transfer or deposit<br><br>
			<strong>Bank:</strong> Galicia<br>
			<strong>Account holder:</strong> Diego Adri&aacute;n Mascarenhas Goyt&iacute;a<br>
			<strong>C.U.I.T.:</strong> 20-25024200-0<br>
			<strong>Account number:</strong> 7386-0 019-3<br>
			<strong>CBU:</strong> 00700191 20000007386035<br>
			<br>
		
		<?php elseif (($_POST['id_forma_pago'] == 13) && ($_POST['id_factura_tipo'] == 15 || $_POST['id_factura_tipo'] == 16)) : ?>
			To submit payment via Mercado Pago, <a href="<?php echo 'https://cms.revisionalpha.com/user/login/?username=' . $_POST['username'] . '&password=' . $_POST['hash'] . '&redirect=https://cms.revisionalpha.com/micuenta/facturas/detalle/' . $_POST['id']; ?>" style="color:#FF6666;">please press here.</a>
			<br>
			<br>
			Remember that if you choose any payment option alternative to a credit card, you must consider mailing/clearing times in relation to the billing due date, to avoid any inconvenience.<br>
			<br>
			
		<?php endif; ?>
			 <a href="<?php echo 'https://cms.revisionalpha.com/user/login/?username=' . $_POST['username'] . '&password=' . $_POST['hash'] . '&redirect=https://cms.revisionalpha.com/micuenta/'; ?>" style="color:#FF6666;">Log into your account</a> to review your services status and the balance of your account
			<br>
			
	</td>
</tr>
<?php include('footer.php'); ?>