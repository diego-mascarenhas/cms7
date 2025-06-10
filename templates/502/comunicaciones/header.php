<html>
<head>
	<title>revision alpha | Comunicaciones</title>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<style>
	* { padding:0; margin:0; line-height:1.5; }
	body { font-family:helvetica, arial, verdana, sans-serif; }
	h1, h2, h3, h4, h5, h6, strong { font-weight:600; }
	p, span, a, td { font-size:14px; font-weight:300; color:#777777; }
	a { text-decoration:none; }
	a:hover { text-decoration:underline; }
	ul, ol { padding-left: 2em; }
	li { padding-bottom: 1em; }
	</style>
</head>
<body bgcolor="#F5EFEF" marginheight="0" marginwidth="0">
	<table width="100%" bgcolor="#F5EFEF" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td height="20"></td>
		</tr>
		<tr>
			<td align="center">
				<table width="700" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td align="center">
							<table width="660" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td height="25" colspan="2"></td>
								</tr>
								<tr>
									<td><h1>
										<!-- <img src="https://cms.revisionalpha.com/templates/502/comunicaciones/images/revision-alpha.png" alt="revision alpha" width="252" height="35" style="display:block; position:relative;"></h1> -->
										<img src="https://cms.revisionalpha.com/templates/502/comunicaciones/images/revision-alpha.svg" alt="revision alpha" height="50" style="display:block; position:relative;">
									</h1>
									</td>
									<td align="right">
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
										<span><strong><?php echo $dia . ' ' . date('d') . ' de ' . $mes . ' de ' . date('Y'); ?></strong></span><br>
										<span><em>Administración</em></span>
									</td>
								</tr>
								<tr>
									<td height="25" colspan="2"></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td height="2px" bgcolor="#FF1A1D"></td>
					</tr>
					<tr>
						<td align="center">
							<table width="660" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td height="50"></td>
								</tr>