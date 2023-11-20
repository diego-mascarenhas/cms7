<?php include('header.php'); ?>
<tr>
	<td>
		<h2 style="font-size:30px; color:#FF6666; border-bottom:1px solid lightgrey;">Modificaci&oacute;n de datos de perfil</h2>
		<br><br>
	</td>
</tr>
<tr>
	<td>		
		<strong><?php echo $_POST['contacto']; ?>,</strong><br>
		<br>
		<?php if ($_POST['nombre_update'] == true) { ?>
			First name: <strong><?php echo $_POST['nombre']; ?></strong><br>
		<?php } else { ?>
			First name: <?php echo $_POST['nombre']; ?><br>
		<?php } ?>
		
		<?php if ($_POST['apellido_update'] == true) { ?>
			Last name: <strong><?php echo $_POST['apellido']; ?></strong><br>
		<?php } else { ?>
			Last name: <?php echo $_POST['apellido']; ?><br>
		<?php } ?>
		
		<?php if ($_POST['telefono_update'] == true) { ?>
			Telephone: <strong><?php echo $_POST['telefono']; ?></strong><br>
		<?php } else { ?>
			Telephone: <?php echo $_POST['telefono']; ?><br>
		<?php } ?>
		<br>
		
		<?php if ($_POST['empresa_update'] == true) { ?>
			Company/Brand: <strong><?php echo $_POST['empresa']; ?></strong><br>
		<?php } else { ?>
			Company/Brand: <?php echo $_POST['empresa']; ?><br>
		<?php } ?>
		
		<?php
			switch ($_POST['id_forma_pago'])
			{
				case 5:
					$forma_pago = 'D&eacute;bito bancario';
					break;
				default:
					$forma_pago = 'Mercado Pago';
					break;
			}
		?>
		<?php if ($_POST['forma_pago_update'] == true) { ?>
			Form of payment: <strong><?php echo $forma_pago; ?></strong><br>
		<?php } else { ?>
			Form of payment: <?php echo $forma_pago; ?><br>
		<?php } ?>
		
		<?php if (!empty($_POST['razon_social'])) { ?>
			<?php if ($_POST['razon_social_update'] == true) { ?>
				Business name: <strong><?php echo $_POST['razon_social']; ?></strong><br>
			<?php } else { ?>
				Business name: <?php echo $_POST['razon_social']; ?><br>
			<?php } ?>
		<?php } ?>
		
		<?php if (!empty( $_POST['cuit'])) 
			{
				$documento = (strlen($_POST['cuit']) > 8) ? 'CUIT: ' : 'DNI: ';
		?>
					
			<?php if ($_POST['cuit_update'] == true) { ?>
				<?php echo $documento; ?> <strong><?php echo $_POST['cuit']; ?></strong><br>
			<?php } else { ?>
				<?php echo $documento; ?> <?php echo $_POST['cuit']; ?><br>
			<?php } ?>
			<br>
		<?php } ?>
		
		<?php if (!empty($_POST['cbu'])) { ?>
		
			<?php if ($_POST['titular_update'] == true) { ?>
				Titular: <strong><?php echo $_POST['titular']; ?></strong><br>
			<?php } else { ?>
				Titular: <?php echo $_POST['titular']; ?><br>
			<?php } ?>

			<?php if ($_POST['cbu_update'] == true) { ?>
				CBU: <strong><?php echo $_POST['cbu']; ?></strong><br>
			<?php } else { ?>
				CBU: <?php echo $_POST['cbu']; ?><br>
			<?php } ?>

			<?php if ($_POST['cuenta_documento_update'] == true) { ?>
				I.D. number: <strong><?php echo $_POST['cuenta_documento']; ?></strong><br>
			<?php } else { ?>
				I.D. number: <?php echo $_POST['cuenta_documento']; ?><br>
			<?php } ?>
			<br>
		<?php } ?>
		
		<br>
		If you have not made any changes, please inform us of this notification  through <a href="https://www.revisionalpha.com/contactenos/" style="color:#FF6666;">our contact form</a>.
	</td>
</tr>
<?php include('footer.php'); ?>