<!doctype html>
<html>
<html>
  <head>
     <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<?php require_once("clases/scripts.php") ?>
</head>
<body>
	<div class="container">
		<nav id="cabecera">
		</nav>
		<header>
			
		</header>
	</div>
	<div class="container">
			<div class="contenido">
			<section class="introducion">
				<?php funcion_instrucciones(&$usuario); ?>
			</section>
			
				<div id="tabs">
					<form method="post" action="validar.php">
						<!-- LISTA DE USUARIOS A EVALUAR -->
						<ul>
							<?php crearLista(&$usuario); ?>
						</ul>

						<!-- CUERPO DE CADA MENU DE LOS USUARIOS A EVALUAR. -->
						
						<?php crearCuerpoLista(&$usuario,&$conexion); ?>

						<input id="enviar" type="submit" value="Enviar" name="Enviar" class="btn btn-enviar">
					</form>								
				</div>
			</div>
		<footer>
			
		</footer>
	</div>
</body>
</html>