<?php include('header.php'); ?>
<tr>
	<td>
		<h2 style="font-size:30px; color:blueviolet; border-bottom:1px solid lightgrey;">New Invoice</h2>
		<br><br>
	</td>
</tr>
<tr>
	<td>		
		<strong><?php echo $_POST['contacto']; ?>,</strong><br>
		<br>
		This notice is to inform you that invoice <?php echo $_POST['comprobante']; ?> for the amount of <?php echo $_POST['simbolo'] . number_format($_POST['total_neto'], 2, ',', '.'); ?>.- has been issued.
		
		<?php if (!empty($_POST['vencimiento']))
		{
		?>
			<br>
			El vencimiento de la misma es el <?php
														$fecha = new DateTime();
														$fecha->setTimestamp($_POST['vencimiento']);
														echo $fecha->format('d-m-Y');
												?>.
		<?php } ?>
		
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
		
		<?php elseif (($_POST['id_forma_pago'] == 2 || $_POST['id_forma_pago'] == 3) && ($_POST['id_factura_tipo'] == 30 || $_POST['id_factura_tipo'] == 31)) : ?>
			Datos de la cuenta a transferir:<br><br>
			<strong>Banco:</strong> Galicia<br>
			<strong>Titular:</strong> revision alpha S.A.S.<br>
			<strong>C.U.I.T.:</strong> 30-71671007-2<br>
			<strong>Cuenta Corriente:</strong> 12416-2 019-8<br>
			<strong>CBU:</strong> 00700191 20000012416286<br>
			<br>
			
		<?php elseif (($_POST['id_forma_pago'] == 5) && ($_POST['id_factura_tipo'] == 15 || $_POST['id_factura_tipo'] == 16 || $_POST['id_factura_tipo'] == 30 || $_POST['id_factura_tipo'] == 31)) : ?>
Automatic debits will be made before the <strong>10th day of the current month.</strong><br>
			<br>
		
		<?php elseif (($_POST['id_forma_pago'] == 13) && ($_POST['id_factura_tipo'] == 15 || $_POST['id_factura_tipo'] == 16 || $_POST['id_factura_tipo'] == 30 || $_POST['id_factura_tipo'] == 31)) : ?>
			To submit payment via Mercado Pago, <a href="<?php echo 'https://cms.revisionalpha.com/user/login/?username=' . $_POST['username'] . '&password=' . $_POST['hash'] . '&redirect=https://cms.revisionalpha.com/micuenta/facturas/detalle/' . $_POST['id']; ?>" style="color:blueviolet;">please press here.</a>
			<br>
			<br>
			Remember that if you choose any payment option alternative to a credit card, you must consider mailing/clearing times in relation to the billing due date, to avoid any inconvenience.<br>
			<br>
			
		<?php endif; ?>
		
			 <a href="<?php echo 'https://cms.revisionalpha.com/user/login/?username=' . $_POST['username'] . '&password=' . $_POST['hash'] . '&redirect=https://cms.revisionalpha.com/micuenta/'; ?>" style="color:blueviolet;">Log into your account</a> to review your services status and the balance of your account
			<br>
		
		<?php if (!empty($_POST['notificacion'])) : ?>
		<br><br>
		<table width="100%" bgcolor="blueviolet" border="0" cellpadding="0" cellspacing="10">
			<tr>
				<td>
					<span style="color:#FFFFFF;"><strong>IMPORTANT</strong>
					<?php echo $_POST['notificacion']; ?>
					<br><br>
					If you have any questions, you can contact us through our website:<br>
					<a href="https://www.revisionalpha.com/contactenos" style="color:inherit">https://www.revisionalpha.com/contact-us</a><br><br>
					Your trust and support is greatly appreciated.</span>
				</td>
			</tr>
		</table>
		<?php endif; ?>
		
	</td>
</tr>
<?php include('footer.php'); ?>