<?php include('header.php'); ?>
<tr>
	<td>
		<h2 style="font-size:30px; color:blueviolet; border-bottom:1px solid lightgrey;">Nueva factura</h2>
		<br><br>
	</td>
</tr>
<tr>
	<td>		
		<strong><?php echo $_POST['contacto']; ?>,</strong><br>
		<br>
		Este aviso es para informarle que la factura <?php echo $_POST['comprobante']; ?> por un valor de <?php echo $_POST['simbolo'] . number_format($_POST['total_neto'], 2, ',', '.'); ?>.- ha sido emitida.
		
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
			Para realizar el pago correspondiente a trav&eacute;s de mercado pago <a href="<?php echo 'https://cms.revisionalpha.com/user/login/?username=' . $_POST['username'] . '&password=' . $_POST['hash'] . '&redirect=https://cms.revisionalpha.com/micuenta/facturas/detalle/' . $_POST['id']; ?>" style="color:blueviolet;">presionando aqu&iacute;.</a>
			<br>
			<br>
			Recuerde que si elige como forma de pago cualquier opci&oacute;n que no sea tarjeta de cr&eacute;dito deber&aacute; contemplar los plazos de acreditaci&oacute;n correspondientes al m&eacute;todo elegido en relaci&oacute;n al vencimiento de la factura para evitar cualquier inconveniente.<br>
			<br>
			
		<?php endif; ?>
		
		Para ver el estado de sus servicios y el balance de su cuenta puede hacerlo desde el <a href="<?php echo 'https://cms.revisionalpha.com/user/login/?username=' . $_POST['username'] . '&password=' . $_POST['hash'] . '&redirect=https://cms.revisionalpha.com/micuenta/'; ?>" style="color:#FF1A1D;">&aacute;rea de clientes</a> de nuestro sitio.
		<!-- Para consultar por el estado de sus servicios y balance de su cuenta puede hacerlo a través de nuestro canal exclusivo de <a href="https://api.whatsapp.com/send/?phone=12202137800&text=<?php echo urlencode('Mi usuario es ' . $_POST['username'] . ' y tengo una consulta sobre mi factura ' . $_POST['comprobante']); ?>" style="color:#FF1A1D;">WhatsApp.</a> -->
		<br>
		<br>
		<div style="color:#39A0ED;"><strong>¡Nos estamos renovando para ti!</strong>
		<br>
		Para darte un soporte más ágil y personalizado, necesitamos que te registres en nuestro nuevo sistema de administración.
		<br>
		<br>
		📍 Puedes hacerlo desde nuestro sitio web tocando el botón de WhatsApp, o directamente en este enlace:
		<br>
		👉 <a href="https://api.whatsapp.com/send/?phone=12202137800&text=Quiero+registrarme" style="color:inherit;">WhatsApp +1 220 213 7800</a>
		<br>
		<br>
		🎯 Muy pronto lanzaremos nuevos servicios que te ayudarán a gestionar tus activos de forma más eficiente.
		<br>
		<br>
		No te quedes afuera. ¡Regístrate y sé el primero en aprovechar estas mejoras!
		</div>	
		
		
		<?php if (!empty($_POST['notificacion'])) : ?>
		<br><br>
		<table width="100%" bgcolor="#39A0ED" border="0" cellpadding="0" cellspacing="10">
			<tr>
				<td>
					<span style="color:#FFFFFF;">
						<strong>IMPORTANTE</strong>
						<?php echo $_POST['notificacion']; ?>
					</span>
				</td>
			</tr>
		</table>
		<?php endif; ?>
		
	</td>
</tr>
<?php include('footer.php'); ?>