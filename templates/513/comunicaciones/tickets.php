<html>
<head>
	<title>Pedimos Facil</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<style>
	* { padding:0; margin:0; line-height:1.5; }
	body { font-family:helvetica, arial, verdana, sans-serif; }
	h1, h2, h3, h4, h5, h6, strong { font-weight:600; }
	p, span, a, td { font-size:14px; font-weight:300; color:#777777; }
	a { text-decoration:none; }
	a:hover { text-decoration:underline; }
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
									<td><h1><img src="https://cms.revisionalpha.com/templates/513/comunicaciones/images/logo-pedimos-facil.png" alt="revision alpha" width="230" height="50" style="display:block; position:relative;"></h1></td>
									<td align="right">
										<?php
										( date('w') == 1 ) ? $dia = 'Lunes' : null;
										( date('w') == 2 ) ? $dia = 'Martes' : null;
										( date('w') == 3 ) ? $dia = 'Mi&eacute;rcoles' : null;
										( date('w') == 4 ) ? $dia = 'Jueves' : null;
										( date('w') == 5 ) ? $dia = 'Viernes' : null;
										( date('w') == 6 ) ? $dia = 'S&aacute;bado' : null;
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
										<span><em><?php echo $_POST['area']; ?></em></span>
									</td>
								</tr>
								<tr>
									<td height="25" colspan="2"></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td height="2px" bgcolor="blueviolet"></td>
					</tr>
					<tr>
						<td align="center">
							<table width="660" bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td height="50"></td>
								</tr>
								<tr>
									<td>
										<h2 style="font-size:30px; color:#13b613; border-bottom:1px solid lightgrey;">Ticket: <?php echo $_POST['asunto']; ?></h2>
										<br><br>
									</td>
								</tr>
								<tr>
									<td>		
										<?php echo $_POST['mensaje']; ?>
										<br>
										<br>
									</td>
								</tr>
								<?php if ($_POST['agente']) { ?>
								<tr>
									<td>
										<a href="https://cms.revisionalpha.com/user/login?username=<?php echo $_POST['username']; ?>&password=<?php echo $_POST['hash']; ?>&redirect=https://cms.revisionalpha.com/tickets/detalle/<?php echo $_POST['id_ticket']; ?>"
											style="	font-family: helvetica, arial, verdana, sans-serif !important;
													width: auto;
													width: 75px;
													background: #13b613;
													margin-right: 10px;
													padding: 10px 20px;
													font-family: semibold;
													font-size: 16px;
													text-transform: uppercase;
													color: white;
													border-radius: 3px;
													text-decoration: none;">Responder Ticket</a>
									</td>
								</tr>
								<?php } else { ?>
								<tr>
									<td>
										<a href="https://cms.revisionalpha.com/user/login?username=<?php echo $_POST['username']; ?>&password=<?php echo $_POST['hash']; ?>&redirect=https://cms.revisionalpha.com/tickets/detalle/<?php echo $_POST['id_ticket']; ?>"
											style="	font-family: helvetica, arial, verdana, sans-serif !important;
													width: auto;
													width: 75px;
													background: #13b613;
													margin-right: 10px;
													padding: 10px 20px;
													font-family: semibold;
													font-size: 16px;
													text-transform: uppercase;
													color: white;
													border-radius: 3px;
													text-decoration: none;">Responder Ticket</a>
									</td>
								</tr>
								<?php } ?>
								<tr>
									<td>		
										<br><br>
										<span>--<br>
										<em>Publicado por: <?php echo $_POST['autor']; ?></em><br></span>
									</td>
								</tr>
								<tr>
									<td height="50"></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td height="10" bgcolor="blueviolet"></td>
					</tr>
					<tr>
						<td align="center">
							<table width="100%" bgcolor="darkgray" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td align="center">
										<table width="660" bgcolor="darkgray" border="0" cellpadding="0" cellspacing="0">
											<tr>
												<td height="25" colspan="2"></td>
											</tr>
											<tr>
												<td>
													<a href="https://www.revisionalpha.com/" style="font-size:17px; color:#FFFFFF; text-decoration:none;"><img src="https://cms.revisionalpha.com/templates/513/comunicaciones/images/logo-pedimos-facil.png" alt="revision alpha" style="display:block; position:relative; padding:2px" width= "165">
													www.pedimosfacil.com</a>
												</td>
												<td align="right">
													<span style="color:#FFFFFF"><strong>Cont&aacute;ctenos:</strong> <span style="color:#FFFFFF !important">+54 11.5219.0345</span><br>
													<strong>Email:</strong> <a href="mailto:administracion@pedimosfacil.com?subject=Consulta" style="color:inherit;">administracion@pedimosfacil.com</a></span>
												</td>
											</tr>
											<tr>
												<td height="25" colspan="2"></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height="20"></td>
		</tr>
	</table>
</body>
</html>