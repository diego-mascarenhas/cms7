<?php include('header.php'); ?>
<tr>
	<td>
		<h2 style="font-size:30px; color:#FF1A1D; border-bottom:1px solid lightgrey;">Tareas</h2>
		<br><br>
	</td>
</tr>
<tr>
	<td>
		<?php if ($_POST['tareas']) { ?>
			<?php foreach ($_POST['tareas'] as $item) { ?>
				<a href="<?php echo 'https://cms.revisionalpha.com/user/login/?username=' . $_POST['username'] . '&password=' . $_POST['hash'] . '&redirect=https://cms.revisionalpha.com/tareas/detalle/' . $item['id']; ?>" style="color:#FF1A1D;">
					<strong><?php echo $item['titulo']; ?></strong>
				</a>
				<br>
				<?php echo $item['descripcion']; ?>
				<br>
				<br>
			<?php } ?>
		<?php } else { ?>
			No posée tareas en curso, por favor <a href="<?php echo 'https://cms.revisionalpha.com/user/login/?username=' . $_POST['username'] . '&password=' . $_POST['hash'] . '&redirect=https://cms.revisionalpha.com/tareas?id_contacto=' . $_POST['id_contacto']; ?>" style="color:#FF1A1D;">
				<strong>presione aquí</strong>
			</a> para verificar que no haya pendientes.
			<br>
			<br>
		<?php } ?>
		
		<table width="100%" bgcolor="#39A0ED" border="0" cellpadding="0" cellspacing="10">
			<tr>
				<td>
					<span style="color:#FFFFFF;"><strong>IMPORTANTE</strong><br><br>Las tareas están ordenadas por prioridad siendo la primera la más urgente.
					<br>
					Para gestionar las mismas <a href="<?php echo 'https://cms.revisionalpha.com/user/login/?username=' . $_POST['username'] . '&password=' . $_POST['hash'] . '&redirect=https://cms.revisionalpha.com/tareas?id_contacto=' . $_POST['id_contacto']; ?>" style="color:#FFFFFF;">
						<strong>presione aquí.</strong>
					</a></span>
				</td>
			</tr>
		</table>
	</td>
</tr>
<?php include('footer.php'); ?>