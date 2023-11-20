<?php include('header.php'); ?>
<tr>
	<td>
		<h2 style="font-size:30px; color:blueviolet; border-bottom:1px solid lightgrey;">Notificación de uso de disco</h2>
		<br><br>
	</td>
</tr>
<tr>
	<td>		
		<strong><?php echo $_POST['contacto']; ?>,</strong><br>
		<br>
		Le notificamos que el dominio <?php echo $_POST['dominio']; ?> esta a punto de alcanzar su límite de espacio. Esto quiere decir que sus mails y el contenido de su sitio web están por superar la capacidad de alojamiento contratado.
		<br>
		<br>
		Para que no tenga interrupción del servicio, el sistema realiazará una actualización de plan.
		<br>
		Para optimizar su espacio debe gestionar los archivos.
		Dejamos acá instructivos de como borrar mails viejos desde el web mail.
		<br>
		<br>
…. Instructivo…..
		<br>
		<br>
Dar de baja archivos innecesarios
		<br>
		<br>
….. instructivo…..
 		<br>
		<br>
En el caso que no se quiera mantener con el nuevo plan, se debe informar por medio de un ticket que no están conforme con este cambio antes que finalice el mes en curso.
		<br>
		<br>
		<a href="<?php echo 'https://cms.revisionalpha.com/user/login/?username=' . $_POST['username'] . '&password=' . $_POST['hash'] . '&redirect=https://cms.revisionalpha.com/tickets/ingresar/' . $_POST['id']; ?>" style="color:blueviolet;">Confirmar solicitud de alta de plan</a>
	</td>
</tr>
<?php include('footer.php'); ?>