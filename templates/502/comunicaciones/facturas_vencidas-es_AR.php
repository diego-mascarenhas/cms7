<?php include('header.php'); ?>
<tr>
	<td>
		<h2 style="font-size:30px; color:#FF6666; border-bottom:1px solid lightgrey;">Factura vencida</h2>
		<br><br>
	</td>
</tr>
<tr>
	<td>		
		<strong><?php echo $_POST['contacto']; ?>,</strong><br>
		<br>
		Este aviso es para informarle que la factura <?php echo $_POST['comprobante']; ?> ha vencido el d&iacute;a 
												<?php
														$fecha = new DateTime();
														$fecha->setTimestamp($_POST['vencimiento']);
														echo $fecha->format('d-m-Y');
												?>.
		<br>
		<br>
		El saldo deudor es de <?php echo $_POST['simbolo'] . number_format($_POST['saldo'], 2, ',', '.'); ?>.- Para ver el detalle de la misma <a href="<?php echo 'https://cms.revisionalpha.com/user/login/?username=' . $_POST['username'] . '&password=' . $_POST['hash'] . '&redirect=https://cms.revisionalpha.com/micuenta/facturas/detalle/' . $_POST['id']; ?>" style="color:#FF6666;">presionando aqu&iacute;.</a>
		<br>
		<br>
		<?php if (($_POST['id_forma_pago'] == 2 || $_POST['id_forma_pago'] == 3) && ($_POST['id_factura_tipo'] == 15 || $_POST['id_factura_tipo'] == 16)) : ?>
			Datos de la cuenta a transferir:<br><br>
			<strong>Banco:</strong> Galicia<br>
			<strong>Titular:</strong> Diego Adri&aacute;n Mascarenhas Goyt&iacute;a<br>
			<strong>C.U.I.T.:</strong> 20-25024200-0<br>
			<strong>Cuenta Corriente:</strong> 7386-0 019-3<br>
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
			El d&eacute;bito autom&aacute;tico ser&aacute; realizado antes del d&iacute;a 10 del corriente mes.<br>
			Por favor verifique que los fondos de su cuenta sean suficientes para el pago de la misma.<br>
			<br>
		
		<?php elseif (($_POST['id_forma_pago'] == 13) && ($_POST['id_factura_tipo'] == 15 || $_POST['id_factura_tipo'] == 16 || $_POST['id_factura_tipo'] == 30 || $_POST['id_factura_tipo'] == 31)) : ?>
			Para realizar el pago correspondiente a trav&eacute;s de mercado pago <a href="<?php echo 'https://cms.revisionalpha.com/user/login/?username=' . $_POST['username'] . '&password=' . $_POST['hash'] . '&redirect=https://cms.revisionalpha.com/micuenta/facturas/detalle/' . $_POST['id']; ?>" style="color:#FF6666;">presionando aqu&iacute;.</a>
			<br>
			<br>
			Recuerde que si elige como forma de pago cualquier opci&oacute;n que no sea tarjeta de cr&eacute;dito deber&aacute; contemplar los plazos de acreditaci&oacute;n correspondientes al m&eacute;todo elegido en relaci&oacute;n al vencimiento de la factura para evitar cualquier inconveniente.<br>
			<br>
			
		<?php endif; ?>
		
			Para ver el estado de sus servicios y el balance de su cuenta puede hacerlo desde el <a href="<?php echo 'https://cms.revisionalpha.com/user/login/?username=' . $_POST['username'] . '&password=' . $_POST['hash'] . '&redirect=https://cms.revisionalpha.com/micuenta/'; ?>" style="color:#FF6666;">&aacute;rea de clientes</a> de nuestro sitio.
	</td>
</tr>
<?php include('footer.php'); ?>