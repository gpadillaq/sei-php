<!doctype html>
<html >
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />	
	 <?php  require_once("clases/scripts.php"); ?>
</head>
<body>
	<div class="container">
		<nav id="cabecera">
				<?php require_once("clases/cabecera.php");?>
		</nav>
		<header>
			
		</header>

		<div class="contenido">
			<!-- Segmento de formularios -->
			<div id="hidden" class="dialogo">

			</div>
			<div class="barra-tools">
				<?php funcion_herramientas(); ?>
			</div>
			<div class="contenido-admin">
				
				<div id="tabs">
					<ul id="menus">
						<li id="INICIO"></li>
					</ul>
					
				</div>
			</div>
		<form action="SEI.php" method="post" target="_blank" id="FormularioExportacion">
			<input type="hidden" id="datos_a_enviar1" name="datos_a_enviar1" />
			<input type="hidden" id="datos_a_enviar2" name="datos_a_enviar2" />
			<input type="hidden" id="datos_a_enviar3" name="datos_a_enviar3" />
			<input type="hidden" id="datos_a_enviar4" name="datos_a_enviar4" />
		</form>
	
		</div>		
		<footer>
			
		</footer>
	</div>
</body>
</html>
