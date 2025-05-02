<?php include('header.php'); ?>
<tr>
	<td>
		<h2 style="font-size:30px; color:#FF1A1D; border-bottom:1px solid lightgrey;"><?php echo $_POST['titulo']; ?></h2>
		<br><br>
	</td>
</tr>
<tr>
	<td>		
		<?php echo $_POST['titulo']; ?>
		<br>
		<br>
		<?php
				foreach (json_decode($_POST['data'], true) as $key => $value)
				{
					if ($key != 'username' && $key != 'hash')
					{
						echo $key . ': ' . $value;
						echo '<br>';
					}
				}
		?>
	</td>
</tr>
<?php include('footer.php'); ?>