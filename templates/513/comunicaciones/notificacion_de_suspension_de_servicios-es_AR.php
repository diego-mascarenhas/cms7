<?php include('header.php'); ?>
<tr>
	<td>
		<h2 style="font-size:30px; color:blueviolet; border-bottom:1px solid lightgrey;">Notificaci&oacute;n de Suspensi&oacute;n de Servicios</h2>
		<br><br>
	</td>
</tr>
<tr>
	<td>		
		<strong><?php echo $_POST['contacto']; ?>,</strong><br>
		<br>
		Le informamos que al d&iacute;a de la fecha su cuenta presenta <?php echo $_POST['cantidad']; ?> <?php echo ($_POST['cantidad'] > 1) ? 'facturas impagas' : 'factura impaga'; ?> por un total de <strong><?php echo $_POST['simbolo'] . number_format($_POST['saldo'], 2, ',', '.'); ?></strong>.<br>
		De no registrarse ning&uacute;n pago en las pr&oacute;ximas 48 horas se proceder&aacute; a la suspensi&oacute;n de los servicios prestados.<br>
		<br>
		<?php if ($_POST['saldo'] != $_POST['parcial'])
		{
		?>
			El pago m&iacute;nimo es de <strong><?php echo $_POST['simbolo'] . number_format($_POST['parcial'], 2, ',', '.'); ?></strong> y deber&aacute; ser realizado por transferencia bancaria a la siguiente cuenta:
		<?php
		}
		
		else
		{
		?>
			El pago deber&aacute; ser realizado por transferencia bancaria a la siguiente cuenta:
		<?
		}
		?>
		<br>
		<br>
		<strong>Banco:</strong> Galicia<br>
		<strong>Titular:</strong> revision alpha S.A.S.<br>
		<strong>C.U.I.T.:</strong> 30-71671007-2<br>
		<strong>Cuenta Corriente:</strong> 12416-2 019-8<br>
		<strong>CBU:</strong> 00700191 20000012416286<br>
		<br>
		Una vez efectuado inf&oacute;rmenos del mismo a trav&eacute;s de nuestro <a href="http://www.revisionalpha.com/contactenos/?message=Aviso de pago realizado" style="color:blueviolet">formulario de contacto</a>.
		<br>
		<br>
		Para ver el estado de sus servicios y el balance de su cuenta puede hacerlo desde el <a href="<?php echo 'https://cms.revisionalpha.com/user/login/?username=' . $_POST['username'] . '&password=' . $_POST['hash'] . '&redirect=https://cms.revisionalpha.com/micuenta/'; ?>" style="color:blueviolet;">&aacute;rea de clientes</a> de nuestro sitio.
	</td>
</tr>
<?php include('footer.php'); ?>