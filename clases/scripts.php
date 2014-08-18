<?php
require_once('fungeneral.fn.php');
//codigos de enlace a los escripts y a las hojas de estilos

?>
	<title>SEI Universidad Tecnologica Lasalle</title>
	<script src="js/jquery-1.9.1.js"></script>
	<script src="js/jquery-ui.js"></script>
    
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css"> 
    <link rel="stylesheet" type="text/css" href="css/stylePartes.css">
	<link href="image/ulsa.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
	
    <script>
    	// Crea los menus de los evaluantes en forma de tabs
	    $(function() {
			$( "#tabs" ).tabs({
				// collapsible: true
				heightStyle: "content"
			});
		});

		// Escript para el efecto acordion.
		$(function() {
			$( ".accordion" ).accordion({
				heightStyle: "content"
				// collapsible: true
			});
		});

		//Constructor de Dialogos.
		$(function() {
			$( "#dialog-message" ).dialog({
				modal: true,
				buttons: {
					Ok: function() {
						location.href='index.php';
						$( this ).dialog( "close" );						
					}

				}
			});
		});

	</script>