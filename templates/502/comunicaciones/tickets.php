REVISION ALPHA
<?php
( date('w') == 1 ) ? $dia = 'Lunes' : null;
( date('w') == 2 ) ? $dia = 'Martes' : null;
( date('w') == 3 ) ? $dia = 'Miércoles' : null;
( date('w') == 4 ) ? $dia = 'Jueves' : null;
( date('w') == 5 ) ? $dia = 'Viernes' : null;
( date('w') == 6 ) ? $dia = 'Sábado' : null;
( date('w') == 0 ) ? $dia = 'Domingo' : null;

( date('n') == 1 ) ? $mes = 'Enero' : null;
( date('n') == 2 ) ? $mes = 'Febrero' : null;
( date('n') == 3 ) ? $mes = 'Marzo' : null;
( date('n') == 4 ) ? $mes = 'Abril' : null;
( date('n') == 5 ) ? $mes = 'Mayo' : null;
( date('n') == 6 ) ? $mes = 'Junio' : null;
( date('n') == 7 ) ? $mes = 'Julio' : null;
( date('n') == 8 ) ? $mes = 'Agosto' : null;
( date('n') == 9 ) ? $mes = 'Septiembre' : null;
( date('n') == 10 ) ? $mes = 'Octubre' : null;
( date('n') == 11 ) ? $mes = 'Noviembre' : null;
( date('n') == 12 ) ? $mes = 'Diciembre' : null;
?>
<?php echo $dia . ' ' . date('d') . ' de ' . $mes . ' de ' . date('Y'); ?>
<?php echo $_POST['area']; ?>

=====================================

TICKET: <?php echo $_POST['asunto']; ?>

-------------------------------------

<?php echo strip_tags($_POST['mensaje']); ?>

<?php if ($_POST['agente']) { ?>
Para responder a este ticket, visite:
https://cms.revisionalpha.com/user/login?username=<?php echo $_POST['username']; ?>&password=<?php echo $_POST['hash']; ?>&redirect=https://cms.revisionalpha.com/tickets/detalle/<?php echo $_POST['id_ticket']; ?>
<?php } else { ?>
Para responder a este ticket, visite:
https://cms.revisionalpha.com/user/login?username=<?php echo $_POST['username']; ?>&password=<?php echo $_POST['hash']; ?>&redirect=https://cms.revisionalpha.com/tickets/detalle/<?php echo $_POST['id_ticket']; ?>
<?php } ?>

Publicado por: <?php echo $_POST['autor']; ?>

=====================================

REVISION ALPHA
www.revisionalpha.com

Contáctenos: +54 11.5219.0345
Email: administracion@revisionalpha.com 