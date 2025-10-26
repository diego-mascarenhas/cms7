<?php include('header.php'); ?>
<tr>
	<td>
		<h2 style="font-size:30px; color:#FF1A1D; border-bottom:1px solid lightgrey;"><?php echo $_POST['titulo']; ?></h2>
		<br><br>
	</td>
</tr>
<tr>
	<td>		
		<?php echo $_POST['descripcion']; ?>
		<br>
		<br>
		<?php if (isset($_POST['valor'])) { ?>
			<?php if (isset($_POST['descuento'])) { ?>
				Subtotal: $<?php echo number_format($_POST['valor'], 0, ',', '.'); ?>.-<br>
				Descuento: <?php echo number_format($_POST['descuento'], 0); ?>%<br>
				<strong>Total: $<?php echo number_format($_POST['valor']-$_POST['valor']*$_POST['descuento']/100, 0, ',', '.'); ?>.-</strong>
			<?php } else { ?>
				<strong>Total: $<?php echo number_format($_POST['valor'], 0, ',', '.'); ?></strong>.-
			<?php } ?>
		<br>
		<br>
		<small>
			<em>*Los precios expresados no incluyen I.V.A.</em><br>
			<em>El presente presupuesto es válido por un período de 20 días hábiles.</em>
		</small>
		<?php } ?>
	</td>
</tr>
<?php include('footer.php'); ?>