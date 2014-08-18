<?php
require_once('fungeneral.fn.php');
require_once("clases/conexion.clase.php");
require_once('tools.php');

// Contiene los nombres de las asignatruas
$_SESSION['arreglo_Asignatura'] = array();
$_SESSION['arreglo_Asignatura_codigo'] = array(); //Contiene los codigos de las asignaturas
$_SESSION['arreglo_Docentes'] = array();	//Contiene los NOmbres de los docentes.

// Buscar Todas las asignaturas
$conexion = new conexion();
$tabla = "asignaturas";
$camposAselect = "codigo,nombre";
$where = 1;
$Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
if(mysql_num_rows($Myconsulta) == 0)
array_push($_SESSION['arreglo_Asignatura'], "No hay datos");
else
{
  while($datos = mysql_fetch_array($Myconsulta))
  {
	array_push($_SESSION['arreglo_Asignatura'], $datos["nombre"]);
	array_push($_SESSION['arreglo_Asignatura_codigo'], $datos["codigo"]);
  }
}
// Buscar a todos los Docentes
$tabla = "Docentes";
$camposAselect = "nombre";
$where = 1;
$Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
if(mysql_num_rows($Myconsulta) == 0)
array_push($_SESSION['arreglo_Docentes'], "No hay datos");
else
{
  while($datos = mysql_fetch_array($Myconsulta))
  {
	array_push($_SESSION['arreglo_Docentes'], $datos["nombre"]);
  }
}

?>
	<title>SEI Universidad Tecnologica Lasalle</title>
	<script src="js/jquery-1.9.1.js" charset="utf-8"></script>
	<script src="js/jquery-ui.js" charset="utf-8"></script>
	<script src="ckeditor/ckeditor.js" charset="utf-8"></script>

	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="css/stylePartes.css">
    <link href="../image/ulsa.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">

    <script>
    	<?php  function completar ($argument,$tipo) { ?>
    		$(function() {
			//Se crea dos array que amacenaran los datos de busqueda inteligente.
			var docente = new Array();
			var asignatura = new Array();
			var clase = "."+<?php echo $tipo; ?>;
		 <?php //Esto es un poco de php para obtener lo que necesitamos

     		for($p = 0;$p < count($argument ); $p++){ //usamos count para saber cuantos elementos hay ?>
     			if (clase == ".docente")
     			{
     				docente.push('<?php echo $argument[$p]; ?>');
     			}else
     			{
     				asignatura.push('<?php echo $argument[$p]; ?>');
     			};

     	<?php } ?>
     	if (clase == ".docente")
     	{
     		$(clase).autocomplete({ //Usamos el ID de la caja de texto donde lo queremos
       		source: docente //Le decimos que nuestra fuente es el arregloa
       		});
     	}else
     	{
     		$(clase).autocomplete({ //Usamos el ID de la caja de texto donde lo queremos
       		source: asignatura});
     	};


     	});
    	<?php } ?>


    	// Seleccionar Tabla
    	function selecTable(nameTabla){ // Perminte seleccionar la Tabla
		$(function(){
			document.cookie = "TablaSelect = "	+ nameTabla[0].id;
		});}

   function selectable(id,nameTabla){ // Perminte seleccionar las celda
		$(function(){
			var Elemento = "#" + nameTabla[0].id + '-' + id;
			var Element = nameTabla[0].id + '-' + id;
				/* Act on the event */
				$('.select').removeClass('select');
				$('.unselect').css('background-color', '');
				$(Elemento).addClass('select');
				$(Elemento).css('background-color', '#FECA4');
				document.cookie = "ElementSelect = " + Element;
				document.cookie = "TablaSelect = "	+ nameTabla[0].id;
		});}

    	// Crea los menus de los evaluantes en forma de tabs
	    $(function() {
			$( "#tabs" ).tabs({
				// collapsible: true
				heightStyle: "content"
			});
			document.cookie = "ElementSelect=null";
			document.cookie = "TablaSelect=null";
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
						location.href='admin.php';
						$( this ).dialog( "close" );
					}

				}
			});
		});

		//Funcion que abre las tablas en forma de tabs
		function Tab(label,id,tabContentHtml,table) {

		var tabTitle = $( "#tab_title" ),
			tabContent = $( "#tab_content" ),
			tabTemplate = "<li onclick=selecTable("+ table + ")><a href='#{href}'>#{label}</a> <span class='ui-icon ui-icon-close' id="+ table + " role='presentation'>Remove Tab</span></li>",
			tabCounter = 2;

		var tabs = $( ".contenido-admin #tabs" ).tabs();

		var li = $( tabTemplate.replace( /#\{href\}/g, "#" + id ).replace( /#\{label\}/g, label ) );

        tabs.find( ".ui-tabs-nav" ).append( li );
        tabs.append( "<div id='" + id + "'>" + tabContentHtml + "</div>" );
        tabs.tabs( "refresh" );
        tabCounter++;

        // close icon: removing the tab on click
		tabs.delegate( "span.ui-icon-close", "click", function() {
			document.cookie = $(this).attr('id') + '=close';
			var panelId = $( this ).closest( "li" ).remove().attr( "aria-controls" );
			$( "#" + panelId ).remove();
			tabs.tabs( "refresh" );
			// console.log($(this).attr('id'));
			location.href = 'admin.php';
		});

		tabs.bind( "keyup", function( event ) {
			if ( event.altKey && event.keyCode === $.ui.keyCode.BACKSPACE ) {
				var panelId = tabs.find( ".ui-tabs-active" ).remove().attr( "aria-controls" );
				$( "#" + panelId ).remove();
				tabs.tabs( "refresh" );
			}
		});
	}

	// Funcion de los botones de herramienta del admin
	$(function(){
		$(".tools").click(function(event) {
				// Crea objetos con lo que se me permitira verificar a cual clase pertenese el elemento
				var simple = new RegExp("(^| )" + "tools-specific" + "( |$)");
				var resultados = new RegExp("(^| )" + "tools-results" + "( |$)");
				var generales = new RegExp("(^| )" + "tools-general" + "( |$)");
				var elemento = document.getElementById($(this).attr('id'));
				var id = $(this).attr('id');

				if (simple.test(elemento.className))
				{
					toolsSimple(id);
				} else{
					if (resultados.test(elemento.className))
					{
						toolsResultados(id);
					} else{
						if (generales.test(elemento.className))
						{
							toolsGenerales(id);
						};
					};
				};

				function toolsSimple(id) {
					var val;
					var val2;
					$.each(document.cookie.split(';'), function(index, elem) {
						var cookie = $.trim(elem);
						var dato = "";
						if (cookie.indexOf('ElementSelect') == 0) {
						val = cookie.slice(name.length + 1);};
						if (cookie.indexOf('TablaSelect') == 0) {
						val2 = cookie.slice(name.length + 1);};
					});
					val = val.split("=");
					val = val[1];
					val2 = val2.split("=");
					val2 = val2[1];
					var val = val.split("-");
					val[0] = val2;
					switch(id)
					{
						case 'edit-element':
							switch(val[0])
							{
								case 'estructuras':
									divi = document.getElementById('hidden');
									divi.id = "dialog-form-estructura";
									divi.title = "Editar Estructura";
									dialogos(val[0]);
									//Capturar el id del elemento seleccionado
									var id;
									$.each(document.cookie.split(';'), function(index, elem) {
									var cookie = $.trim(elem);
									if (cookie.indexOf('ElementSelect') == 0) {
									id = cookie.slice(name.length + 1);};});
									//Seleparar el nombre del campo del valor
									id = id.split("=");
									//asignar solo el valor del campo a la variable id
									id = id[1];
									//Capturar el codigo htm de la estructura seleccionada
									var codigo = document.getElementById(id);
									codigo = codigo.getElementsByTagName('td');
									codigo = codigo[1];
									codigo = codigo.innerHTML;
									//Seleparar el nombre del campo del valor
									id = id.split("-");
									//asignar solo el valor del campo a la variable id
									id = id[1];
									codigo = "<div><form method=post><input type=hidden value=" + id +" name='idStructura'/><textarea class=ckeditor  name=editor id=editor1 rows=10 cols=80>" + codigo + "</textarea></form></div>";

									$(function  () {
										$("#dialog-form-estructura").html(codigo);
										$( "#dialog-form-estructura" ).dialog( "open" );
									});
									CKEDITOR.replace('editor');


									//Capturar el id del elemento seleccionado
									// var id;
									// $.each(document.cookie.split(';'), function(index, elem) {
									// var cookie = $.trim(elem);
									// if (cookie.indexOf('ElementSelect') == 0) {
									// id = cookie.slice(name.length + 1);};});
									// //Seleparar el nombre del campo del valor
									// id = id.split("=");
									// //asignar solo el valor del campo a la variable id
									// id = id[1];
									// //Guardar el id del elemento en las cookis
									// document.cookie = "idStructura="+id;

									// //Capturar el codigo htm de la estructura seleccionada
									// var codigo = document.getElementById(id);
									// codigo = codigo.getElementsByTagName('td');
									// codigo = codigo[1];
									// codigo = codigo.innerHTML;
									// alert(codigo);
									// document.cookie = "codigoStructura="+codigo;
									// open("editor.php?codigo=" + codigo);
								break;
							}
						break;
						case 'add-element':
							switch(val[0])
							{
								case 'estructuras':
									divi = document.getElementById('hidden');
									divi.id = "dialog-form-add-struct";
									divi.title = "Agregar un Nuevo Usuario";
									dialogos(val[0]);
									var codigo = <?php formAddStruct();  ?>;
									$(function  () {
										$("#dialog-form-add-struct").html(codigo);
										$( "#dialog-form-add-struct" ).dialog( "open" );
									});
								break;
								case 'usuarios':
									divi = document.getElementById('hidden');
									divi.id = "dialog-form-add-user";
									divi.title = "Agregar un Nuevo Usuario";
									dialogos(val[0]);
									var codigo = <?php formAddUser();  ?>;
									$(function  () {
										$("#dialog-form-add-user").html(codigo);
										$( "#dialog-form-add-user" ).dialog( "open" );
									});
								break;
								case 'Alumnos':
									divi = document.getElementById('hidden');
									divi.id = "dialog-form-add-alumno";
									divi.title = "Agregar un Nuevo Alumno";
									dialogos(val[0]);
									var codigo = <?php formAddAlumno();  ?>;
									$(function  () {
										$("#dialog-form-add-alumno").html(codigo);
										<?php completar($_SESSION['arreglo_Asignatura'],"\"materia\""); ?>;
										$( "#dialog-form-add-alumno" ).dialog( "open" );
									});

								break;
								case 'Docentes':
									divi = document.getElementById('hidden');
									divi.id = "dialog-form-add-docente";
									divi.title = "Agregar un Nuevo Docente";
									dialogos(val[0]);
									var codigo = <?php formAddDocente();  ?>;
									$(function  () {
										$("#dialog-form-add-docente").html(codigo);
										$( "#dialog-form-add-docente" ).dialog( "open" );
									});
								break;
								case 'docente_clase':
									divi = document.getElementById('hidden');
									divi.id = "dialog-form-add-docente_clase";
									divi.title = "Agregar una Asignatura a un Docente";
									dialogos(val[0]);
									var codigo = <?php formAddDocenteClase();  ?>;
									$(function  () {
										$("#dialog-form-add-docente_clase").html(codigo);
										<?php completar($_SESSION['arreglo_Asignatura'],"\"materia\""); ?>;
										<?php completar($_SESSION['arreglo_Docentes'],"\"docente\""); ?>;
										$( "#dialog-form-add-docente_clase" ).dialog( "open" );
									});
								break;
								case 'Administradores':
									divi = document.getElementById('hidden');
									divi.id = "dialog-form-add-administrador";
									divi.title = "Agregar un Nuevo Administrador";
									dialogos(val[0]);
									var codigo = <?php formAddAdministrador();  ?>;
									$(function  () {
										$("#dialog-form-add-administrador").html(codigo);
										$( "#dialog-form-add-administrador" ).dialog( "open" );
									});
								break;
								case 'Preguntas':
									divi = document.getElementById('hidden');
									divi.id = "dialog-form-add-preguntas";
									divi.title = "Agregar Nuevas Preguntas";
									dialogos(val[0]);
									var codigo = <?php formAddPreguntas();  ?>;
									$(function  () {
										$("#dialog-form-add-preguntas").html(codigo);
										$( "#dialog-form-add-preguntas" ).dialog( "open" );
									});
								break;
							}
						break;
						case 'remove-element':
							switch(val[0])
							{
								case 'estructuras':
									divi = document.getElementById('hidden');
									divi.id = "dialog-form-del";
									divi.title = "Eliminar la Estructura";
									dialogos(val[0]);
									var codigo = <?php formDel();  ?>;
									$(function  () {
										$("#dialog-form-del").html(codigo);
										$( "#dialog-form-del" ).dialog( "open" );
									});
									document.cookie = "id=" + val[1];
								break;
								case 'usuarios':
									divi = document.getElementById('hidden');
									divi.id = "dialog-form-del";
									divi.title = "Eliminar Usuario";
									dialogos(val[0]);
									var codigo = <?php formDel();  ?>;
									$(function  () {
										$("#dialog-form-del").html(codigo);
										$( "#dialog-form-del" ).dialog( "open" );
									});
									document.cookie = "id=" + val[1];
								break;
								case 'Alumnos':
									divi = document.getElementById('hidden');
									divi.id = "dialog-form-del";
									divi.title = "Eliminar Alumno";
									dialogos(val[0]);
									var codigo = <?php formDel();  ?>;
									$(function  () {
										$("#dialog-form-del").html(codigo);
										$( "#dialog-form-del" ).dialog( "open" );
									});
									document.cookie = "id=" + val[1];
								break;
								case 'Docentes':
									divi = document.getElementById('hidden');
									divi.id = "dialog-form-del";
									divi.title = "Eliminar Docentes";
									dialogos(val[0]);
									var codigo = <?php formDel();  ?>;
									$(function  () {
										$("#dialog-form-del").html(codigo);
										$( "#dialog-form-del" ).dialog( "open" );
									});
									document.cookie = "id=" + val[1];
								break;
								case 'docente_clase':
									divi = document.getElementById('hidden');
									divi.id = "dialog-form-del";
									divi.title = "Eliminar Docentes Clases";
									dialogos(val[0]);
									var codigo = <?php formDel();  ?>;
									$(function  () {
										$("#dialog-form-del").html(codigo);
										$( "#dialog-form-del" ).dialog( "open" );
									});
									document.cookie = "id=" + val[1];
								break;
								case 'Administradores':
									divi = document.getElementById('hidden');
									divi.id = "dialog-form-del";
									divi.title = "Eliminar Administrador";
									dialogos(val[0]);
									var codigo = <?php formDel();  ?>;
									$(function  () {
										$("#dialog-form-del").html(codigo);
										$( "#dialog-form-del" ).dialog( "open" );
									});
									document.cookie = "id=" + val[1];
								break;
								case 'Preguntas':
									divi = document.getElementById('hidden');
									divi.id = "dialog-form-del";
									divi.title = "Eliminar Pregunta";
									dialogos(val[0]);
									var codigo = <?php formDel();  ?>;
									$(function  () {
										$("#dialog-form-del").html(codigo);
										$( "#dialog-form-del" ).dialog( "open" );
									});
									document.cookie = "id=" + val[1];
								break;
							}
						break;

						case 'import-table':
							switch(val[0])
							{
								case 'Alumnos':
									divi = document.getElementById('hidden');
									divi.id = "dialog-form-import-alumno";
									divi.title = "Import Tablas de Alumnos";
									dialogos(val[0]);
									var codigo = <?php formImport();  ?>;
									$(function  () {
										$("#dialog-form-import-alumno").html(codigo);
										$( "#dialog-form-import-alumno").dialog( "open" );
										document.cookie = "funcion=import";
										document.cookie = "tabla=" + val[0];
									});
								break;
								case 'Docentes':
									divi = document.getElementById('hidden');
									divi.id = "dialog-form-import-docentes";
									divi.title = "Import Tablas de Docente";
									dialogos(val[0]);
									var codigo = <?php formImport();  ?>;
									$(function  () {
										$("#dialog-form-import-docentes").html(codigo);
										$( "#dialog-form-import-docentes").dialog( "open" );
										document.cookie = "funcion=import";
										document.cookie = "tabla=" + val[0];
									});
								break;

							}
						break;

					}
				}
				function toolsResultados(id) {
					switch(id)
					{
						case 'open-results':
							document.cookie = 'resultados=open';
							location.href = 'admin.php';
						break;
						case 'export-results':
							var cookieResultado = <?php
							 if (isset($_COOKIE['resultados']))
							 {
							 	echo "\"".$_COOKIE['resultados']."\"";
							 }else echo "null";
							 ?>;

							if (cookieResultado == "open") {
								$(document).ready(function() {
									$("#datos_a_enviar1").val( $("<div>").append( $("#ResulAlumnos").eq(0).clone()).html());
									$("#datos_a_enviar2").val( $("<div>").append( $("#ResulOtros").eq(0).clone()).html());
									$("#datos_a_enviar3").val( $("<div>").append( $("#ComentariosAlumnos").eq(0).clone()).html());
									$("#datos_a_enviar4").val( $("<div>").append( $("#ComentariosOtros").eq(0).clone()).html());
									$("#FormularioExportacion").submit();
								});

							}else{
								alert("Para Exportar los Resultados Primero Debe dar Click en ver Resultados");
							};
						break;
					}

				}

				function toolsGenerales(id) {
					switch(id)
					{
						case 'open-table':
						divi = document.getElementById('hidden');
						divi.id = "dialog-form";
						divi.title = "Abrir Tablas";
						dialogos(id);
						var codigo = <?php formOpen();  ?>;
						$(function  () {
						$("#dialog-form").html(codigo);
						$( "#dialog-form" ).dialog( "open" );
						});

						break;
						case 'clean-table':

						break;
						case 'cerrar-sesion':
							document.cookie = "user=close";
							location.href = 'admin.php';
						break;
					}
				}
		});


	// Creacion de Dialogos.
		function dialogos (argument) {
			$(function () {
				function updateTips( t ) {
					tips
						.text( t )
						.addClass( "ui-state-highlight" );
					setTimeout(function() {
						tips.removeClass( "ui-state-highlight", 1500 );
					}, 500 );
				}

				function checkLength( o, n, min, max ) {
					if ( o.val().length > max || o.val().length < min ) {
						o.addClass( "ui-state-error" );
						updateTips( "Length of " + n + " must be between " +
							min + " and " + max + "." );
						return false;
					} else {
						return true;
					}
				}

				function checkRegexp( o, regexp, n ) {
					if ( !( regexp.test( o.val() ) ) ) {
						o.addClass( "ui-state-error" );
						updateTips( n );
						return false;
					} else {
						return true;
					}
				}

			switch(argument)
			{
				case 'open-table':
					$( "#dialog-form" ).dialog({
						autoOpen: false,
						height: 300,
						width: 350,
						modal: true,
						buttons: {
							Abrir: function() {
								var table = $('#dialog-form select').val();
								document.cookie = table + '=open';
								location.href='admin.php';
		   					  $( this ).dialog( "close" );

							},
							Cancel: function() {
								location.href='admin.php';
								$( this ).dialog( "close" );
							}
						}
					});
				break;

				case 'estructuras':
					$('#dialog-form-add-struct').dialog({
						autoOpen: false,
						height:450,
						width: 450,
						modal:true,
						buttons: {
							Guardar: function() {
								var Usuario = $('#user').val();
								document.cookie = "UsuarioEstructura = " + Usuario;
								document.cookie = "funcion = add";
								document.cookie = 'tabla=' + argument;
								location.href='admin.php';
		   					  $( this ).dialog( "close" );
							},
							Cancel: function() {
								location.href='admin.php';
								$( this ).dialog( "close" );
							}
						}
					});

					$( "#dialog-form-del" ).dialog({
						autoOpen: false,
						height: 300,
						width: 300,
						modal: true,
						buttons: {
							Eliminar: function() {
								document.cookie= "funcion=del";
								document.cookie = "tabla=" + argument;
								location.href='admin.php';
								$( this ).dialog( "close" );
							},
							Cancel: function() {
								location.href='admin.php';
								$( this ).dialog( "close" );
							}
						}
					});

					$('#dialog-form-estructura').dialog({
						autoOpen: false,
						height:450,
						width: 1000,
						modal:true,
						buttons: {
							Cancel: function() {
								location.href='admin.php';
								$( this ).dialog( "close" );
							}
						}
					});
				break;

				case 'usuarios':
				var tips = $( ".validateTips" );
				$( "#dialog-form-add-user" ).dialog({
						autoOpen: false,
						height: 400,
						width: 400,
						modal: true,
						buttons: {
							Agregar: function() {
								var codigo = $( "#codigo" ),
									usuario = $( "#usuario" ),
									evaluado = $( "#evaluado" ),
									npreguntas = $( "#npreguntas" ),
									allFields = $( [] ).add( codigo ).add( usuario ).add( evaluado ).add( npreguntas );

								var bValid = true;
								allFields.removeClass( "ui-state-error" );
								bValid = bValid && checkLength( codigo, "codigo", 1, 5 );
								bValid = bValid && checkLength( usuario, "usuario", 6, 20 );
								bValid = bValid && checkLength( evaluado, "evaluado", 5, 20 );
								bValid = bValid && checkLength( npreguntas, "npreguntas", 0, 5 );
								// VAlidacion de datos
								bValid = bValid && checkRegexp( codigo,/^[a-z]([0-9a-z_])+$/i, "Username may consist of a-z, 0-9, underscores, begin with a letter." );
								bValid = bValid && checkRegexp( usuario, /^[a-z]([0-9a-z_])+$/i, "Username may consist of a-z, 0-9, underscores, begin with a letter." );
								bValid = bValid && checkRegexp( evaluado, /^[a-z]([0-9a-z_])+$/i, "Username may consist of a-z, 0-9, underscores, begin with a letter." );


								if (bValid) {
									document.cookie = 'funcion=add';
									document.cookie = 'tabla=' + argument;
									document.cookie = 'codigo=' + codigo.val();
									document.cookie = 'usuario=' + usuario.val();
									document.cookie = 'evaluado=' + evaluado.val();
									document.cookie = 'npreguntas=' + npreguntas.val();
									location.href='admin.php';
		   					  		$( this ).dialog( "close" );
								};


							},
							Cancel: function() {
								location.href='admin.php';
								$( this ).dialog( "close" );
							}
						}
					});


					$( "#dialog-form-del" ).dialog({
						autoOpen: false,
						height: 300,
						width: 300,
						modal: true,
						buttons: {
							Eliminar: function() {
								document.cookie= "funcion=del";
								document.cookie = "tabla=" + argument;
								location.href='admin.php';
								$( this ).dialog( "close" );
							},
							Cancel: function() {
								location.href='admin.php';
								$( this ).dialog( "close" );
							}
						}
					});
				break;
				case 'Alumnos':
					var tips = $( ".validateTips" );

				$( "#dialog-form-add-alumno" ).dialog({
						autoOpen: false,
						height: 600,
						width: 650,
						modal: true,
						buttons: {
							Agregar: function() {
								var codigos = new Array();
								var Asignaturas = new Array();
								var Docentes = new Array();
								var NClases = $('#nclases').val();
								var Nalumnos = $('#nalumnos').val();
								var bValid = true;

								for (var i = 1; i <= NClases; i++) {
									idDocentes = "#docente-" + i;
									docenteI = $(idDocentes).val();
									// CApturar los datos
									if (!(docenteI == "none"))
									{
										Docentes.push(docenteI);
									};

								};

								if(Docentes.length <= 0)
								{
									bValid = false;
								}

								for (var i = 1; i <= Nalumnos; i++) {
									idAlumnos = "#codigo-" + i;
									alumnoI = $(idAlumnos).val();
									if (!(alumnoI == ""))
									{
										if(!(alumnoI == "none") && !(alumnoI == undefined))
										{
											codigos.push(alumnoI.toUpperCase());
										}
									}else bValid = false;
								};

								if (bValid) {
									document.cookie = 'funcion=add';
									document.cookie = 'tabla=' + argument;
									document.cookie = 'codigos=' + codigos;
									document.cookie = 'docentes=' + Docentes;
									location.href='admin.php';
		   					  		$( this ).dialog( "close" );
								};

							},
							Cancel: function() {
								location.href='admin.php';
								$( this ).dialog( "close" );
							}
						}
					});

					$( "#dialog-form-del" ).dialog({
						autoOpen: false,
						height: 300,
						width: 300,
						modal: true,
						buttons: {
							Eliminar: function() {
								document.cookie= "funcion=del";
								document.cookie = "tabla=" + argument;
								location.href='admin.php';
								$( this ).dialog( "close" );
							},
							Cancel: function() {
								location.href='admin.php';
								$( this ).dialog( "close" );
							}
						}
					});

					$("#dialog-form-import-alumno").dialog({
						autoOpen: false,
						height: 300,
						width: 300,
						modal: true,
						buttons:{
							Cancelar: function(){
								location.href='admin.php';
								$(this).dialog("close");
							}
						}
						});
				break;
				case 'Docentes':
					$( "#dialog-form-add-docente" ).dialog({
						autoOpen: false,
						height: 600,
						width: 650,
						modal: true,
						buttons: {
							Agregar: function() {
								var Codigos = new Array();
								var Docentes = new Array();
								var NDocentes = $('#ndocentes').val();
								var bValid = true;
								for (var i = 1; i <= NDocentes; i++) {
									idDocentes = "#Nombre-Docente-" + i;
									idCodigos = "#Codigo-Docente-" + i;
									docenteI = $(idDocentes).val();
									codigoI = $(idCodigos).val();
									// CApturar los datos
									if ((!(docenteI == "none") && !(codigoI == "none")) && ((!(docenteI == "") && !(codigoI == ""))))
									{
										Docentes.push(docenteI);Codigos.push(codigoI.toUpperCase());
									};

								};
								if(Docentes.length <= 0)
								{
									bValid = false;
								}
								if (bValid) {
									document.cookie = 'funcion=add';
									document.cookie = 'tabla=' + argument;
									document.cookie = 'codigos=' + Codigos;
									document.cookie = 'docentes=' + Docentes;
									location.href='admin.php';
		   					  		$( this ).dialog( "close" );
								};

							},
							Cancel: function() {
								location.href='admin.php';
								$( this ).dialog( "close" );
							}
						}
					});
					$( "#dialog-form-del" ).dialog({
						autoOpen: false,
						height: 300,
						width: 300,
						modal: true,
						buttons: {
							Eliminar: function() {
								document.cookie= "funcion=del";
								document.cookie = "tabla=" + argument;
								location.href='admin.php';
								$( this ).dialog( "close" );
							},
							Cancel: function() {
								location.href='admin.php';
								$( this ).dialog( "close" );
							}
						}
					});

					$("#dialog-form-import-docentes").dialog({
						autoOpen: false,
						height: 300,
						width: 300,
						modal: true,
						buttons:{
							Cancelar: function(){
								location.href='admin.php';
								$(this).dialog("close");
							}
						}
						});
				break;
				case 'docente_clase':
					$( "#dialog-form-add-docente_clase" ).dialog({
						autoOpen: false,
						height: 600,
						width: 650,
						modal: true,
						buttons: {
							Agregar: function() {
								var Asignaturas = new Array();
								var Docentes = new Array();
								var NDocentes = $('#ndocentes').val();
								var Ano = $('#ano').val();
								var Grupo = $('#grupo').val();
								var Periodo = $('#periodo').val();
								//alert(parseInt(Ano)  + "   " + parseInt(Grupo));
								var bValid = true;
								for (var i = 1; i <= NDocentes; i++) {
									idDocentes = "#Nombre-Docente-" + i;
									idAsignaturas = "#Nombre-Asignatura-" + i;
									docenteI = $(idDocentes).val();
									asignaturaI = $(idAsignaturas).val();
									// CApturar los datos
									if ((!(docenteI == "none") && !(asignaturaI == "none")) && ((!(docenteI == "") && !(asignaturaI == ""))))
									{
										Docentes.push(docenteI);Asignaturas.push(asignaturaI);
									};

								};
								if(Docentes.length <= 0 || Periodo.length <= 0 || Ano.length <= 0 || Grupo.length <= 0)
								{
									bValid = false;

								}
								if (parseInt(Ano) == NaN || parseInt(Ano) < 0) {
									bValid = false;
								};
								if (parseInt(Grupo) == NaN || parseInt(Grupo) < 0) {
										bValid = false;
								};
								if (bValid) {
									document.cookie = 'funcion=add';
									document.cookie = 'tabla=' + argument;
									document.cookie = 'asignaturas=' + Asignaturas;
									document.cookie = 'docentes=' + Docentes;
									document.cookie = 'ano=' + Ano;
									document.cookie = 'grupo=' + Grupo;
									document.cookie = 'periodo=' + Periodo;
									location.href='admin.php';
		   					  		$( this ).dialog( "close" );
								};

							},
							Cancel: function() {
								location.href='admin.php';
								$( this ).dialog( "close" );
							}
						}
					});

					$( "#dialog-form-del" ).dialog({
						autoOpen: false,
						height: 300,
						width: 300,
						modal: true,
						buttons: {
							Eliminar: function() {
								document.cookie= "funcion=del";
								document.cookie = "tabla=" + argument;
								location.href='admin.php';
								$( this ).dialog( "close" );
							},
							Cancel: function() {
								location.href='admin.php';
								$( this ).dialog( "close" );
							}
						}
					});


				break;
				case 'Administradores':
					$( "#dialog-form-add-administrador" ).dialog({
						autoOpen: false,
						height: 600,
						width: 650,
						modal: true,
						buttons: {
							Agregar: function() {
								var Codigos = new Array();
								var Administradores = new Array();
								var Docencia = new Array();
								var NAdministradores = $('#nadministradores').val();
								var bValid = true;
								for (var i = 1; i <= NAdministradores; i++) {
									idAdministradores = "#Nombre-Administrador-" + i;
									idCodigos = "#Codigo-Administrador-" + i;
									idDocencia = "#docencia-" + i;
									administradorI = $(idAdministradores).val();
									codigoI = $(idCodigos).val();
									docenciaI = $(idDocencia).val();
									// CApturar los datos
									if ((!(administradorI == "none") && !(codigoI == "none")) && ((!(administradorI == "") && !(codigoI == ""))))
									{
										Administradores.push(administradorI);Codigos.push(codigoI.toUpperCase());Docencia.push(docenciaI.toUpperCase());
									};

								};
								if(Administradores.length <= 0)
								{
									bValid = false;
								}
								if (bValid) {
									document.cookie = 'funcion=add';
									document.cookie = 'tabla=' + argument;
									document.cookie = 'codigos=' + Codigos;
									document.cookie = 'administradores=' + Administradores;
									document.cookie = 'docencia=' + Docencia;
									location.href='admin.php';
		   					  		$( this ).dialog( "close" );
								};

							},
							Cancel: function() {
								location.href='admin.php';
								$( this ).dialog( "close" );
							}
						}
					});
					$( "#dialog-form-del" ).dialog({
						autoOpen: false,
						height: 300,
						width: 300,
						modal: true,
						buttons: {
							Eliminar: function() {
								document.cookie= "funcion=del";
								document.cookie = "tabla=" + argument;
								location.href='admin.php';
								$( this ).dialog( "close" );
							},
							Cancel: function() {
								location.href='admin.php';
								$( this ).dialog( "close" );
							}
						}
					});
				break;
				case 'Preguntas':
					$( "#dialog-form-add-preguntas" ).dialog({
						autoOpen: false,
						height: 600,
						width: 650,
						modal: true,
						buttons: {
							Agregar: function() {

								var Preguntas = new Array();
								var evaluador = $('#select-evaluador').val();
								var evaluado = $('#select-evaluado').val();
								var Npreguntas = $('#npreguntas').val();
								var bValid = true;
								for (var i = 1; i <= Npreguntas; i++) {
									idpregunta = "#Pregunta-" + i;
									preguntaI = $(idpregunta).val();
									// CApturar los datos
									if (!(preguntaI == "none"))
									{
										Preguntas.push(preguntaI);
									};

								};//alert(Preguntas.length)
								if(Preguntas.length <= 0)
								{
									bValid = false;
								}
								if (bValid) {
									document.cookie = 'funcion=add';
									document.cookie = 'tabla=' + argument;
									document.cookie = 'evaluador=' + evaluador;
									document.cookie = 'evaluado=' + evaluado;
									document.cookie = 'preguntas=' + Preguntas;
									location.href='admin.php';
		   					  		$( this ).dialog( "close" );
								};

							},
							Cancel: function() {
								location.href='admin.php';
								$( this ).dialog( "close" );
							}
						}
					});
					$( "#dialog-form-del" ).dialog({
						autoOpen: false,
						height: 300,
						width: 300,
						modal: true,
						buttons: {
							Eliminar: function() {
								document.cookie= "funcion=del";
								document.cookie = "tabla=" + argument;
								location.href='admin.php';
								$( this ).dialog( "close" );
							},
							Cancel: function() {
								location.href='admin.php';
								$( this ).dialog( "close" );
							}
						}
					});
				break;

			}});
		}


		// muestra un mensaje cuando pasas el mouse sobre los botones de herramientas.
		$( ".tools" ).tooltip({
			show: null,
			position: {
				my: "left top",
				at: "left bottom"
			},
			open: function( event, ui ) {
				ui.tooltip.animate({ top: ui.tooltip.position().top + 10 }, "fast" );
			}
		});
	});



	//Abre tabs con los resultados de la encuesta....
<?function Abrir($mostrar,$table,$docentesClase,$name)
{
	$_COOKIE[$table] = "open";
	 // echo "console.log(\"".print_r($DatosDocentes)."\");";
	?>
        var label = <?php echo "'".$name."'" ?>,
      	table = <?php echo "'".$table."'" ?>,
        id = "tabs-" + <?php echo "'".$name."'" ?>,
        tabContentHtml = <?php echo "\"";
         $clases = "\'table table-bordered table-condensed\'";
        if ($table == "resultados") {
        	echo "<table id=$name class=$clases>";
        }else echo "<table id=$table class=$clases>";
        if (count($mostrar) > 0)
        switch ($table) {
        	case 'Alumnos':
        	echo "<tr>";
        		echo "<th>";echo "Codigo";echo "</th>";
        		echo "<th>";echo "Evaluado si=1/no=0";echo "</th>";
        		echo "<th>";echo "Asignaturas";echo "</th>";
        	echo "</tr>";

        	foreach ($mostrar as $key => $value) {
        		$varId = $value['id'];
        		echo "<tr id=$table-$varId class='unselect' onclick=selectable($varId,$table)>";
        			foreach ($value as $key2 => $value2) {

        				if ($key2 != 'id') {
	        				echo "<td>";
	        			    	echo $value2;
	        				echo "</td>";
        				};
        			}
        			echo "</tr>";
           	}

        		break;
        	case 'Docentes':
	        	echo "<tr>";
	        		echo "<th>";echo "Codigo";echo "</th>";
	        		echo "<th>";echo "Nombre";echo "</th>";
	        		echo "<th>";echo "Evaluado si=1/no=0";echo "</th>";
	        	echo "</tr>";
        		foreach ($mostrar as $key => $value) {
        			$varId = $key;
        			echo "<tr id=$table-$varId class='unselect' onclick=selectable($varId,$table)>";
        			foreach ($value as $key2 => $value2) {
        				echo "<td>";
        					echo $value2;
        				echo "</td>";
        			}
        			echo "</tr>";

        		}
        		break;
        	case 'Administradores':
        		echo "<tr>";
	        		echo "<th>";echo "Codigo";echo "</th>";
	        		echo "<th>";echo "Nombre";echo "</th>";
	        		echo "<th>";echo "Docente si=1/no=0";echo "</th>";
	        		echo "<th>";echo "Evaluado si=1/no=0";echo "</th>";
	        	echo "</tr>";
	        	foreach ($mostrar as $key => $value) {
        			$varId = $key;
        			echo "<tr id=$table-$varId class='unselect' onclick=selectable($varId,$table)>";
        			foreach ($value as $key2 => $value2) {
        				echo "<td>";
        					echo $value2;
        				echo "</td>";
        			}
        			echo "</tr>";

        		}

        		break;
        	case 'docente_clase':
        		echo "<tr>";
	        		echo "<th>Docente</th>";
	        		echo "<th>Clase</th>";
					echo "<th>Año</th>";
					echo "<th>Grupo</th>";
					echo "<th>Periodo</th>";
	        	echo "</tr>";
	        	foreach ($mostrar as $key => $value) {
        			$varId = $key;
        			echo "<tr id=$table-$varId class='unselect' onclick=selectable($varId,$table)>";
        			foreach ($value as $key2 => $value2) {
        				echo "<td>";
        					echo $value2;
        				echo "</td>";
        			}
        			echo "</tr>";

        		}
        		break;
        	case 'Preguntas':
        		foreach ($mostrar as $key => $value) {

        			echo "<tr>";
		        		echo "<th>";echo $value['evaluador'];echo "</th>";
		        		echo "<th>";echo $value['evaluado'];echo "</th>";
	        		echo "</tr>";
	        		$evaluador = $value['evaluador'];
	        		$evaluado = $value['evaluado'];

        			foreach ($value as $key2 => $value2) {


        				if ($key2 == 'evaluador' || $key2 == 'evaluado') {continue;};
        				$varId = $evaluador."-".$evaluado."-".$key2;
        				echo "<tr id=$table-$varId class='unselect' onclick=selectable(\'$varId\',$table)>";
        				echo "<td colspan=2>";
        					echo $value2;
        				echo "</td>";
        				echo "</tr>";

        			}

        		}
        		break;
        	case 'comentarios':
        		echo "<tr>";
	        		echo "<th>";echo "Evaluador";echo "</th>";
	        		echo "<th>";echo "Evaluado";echo "</th>";
	        		echo "<th>";echo "Positivos";echo "</th>";
	        		echo "<th>";echo "Negativos";echo "</th>";
	        		echo "<th>";echo "Recomendaciones";echo "</th>";
        		echo "</tr>";
        		foreach ($mostrar as $key => $value) {

	        		$evaluador = $value['evaluador'];
	        		$evaluado = $value['evaluado'];
	        		$varId = $evaluador."-".$evaluado."-".$key2;
        			echo "<tr id=$table-$varId class='unselect'>";

        			foreach ($value as $key2 => $value2) {

        				echo "<td collapse=2>";
        					echo $value2;
        				echo "</td>";


        			}
        			echo "</tr>";

        		}
        		break;
        	case 'usuarios':
        		echo "<tr>";
	        		echo "<th>";echo "Codigo";echo "</th>";
	        		echo "<th>";echo "Evaluador";echo "</th>";
	        		echo "<th>";echo "Evaluado";echo "</th>";
	        		echo "<th>";echo "N Aspectos";echo "</th>";
	        	echo "</tr>";
        		foreach ($mostrar as $key => $value) {
        			$varId = $key;
        			echo "<tr id=$table-$varId class='unselect' onclick=selectable($varId,$table)>";
        			foreach ($value as $key2 => $value2) {
        				echo "<td>";
        					echo $value2;
        				echo "</td>";
        			}
        			echo "</tr>";

        		}
        		break;
        	case 'resultados':
        	echo "<tr>";
        	switch ($name) {
        		case 'ResulAlumnos':
        			echo "<th>Evaluador</th>";
					echo "<th>Evaluado</th>";
					echo "<th>Asignatura</th>";
					echo "<th>Año</th>";
					echo "<th>Grupo</th>";
					echo "<th>Cuatrimestre</th>";
					echo "<th>Pregunta</th>";
					echo "<th>Resultados</th>";
					echo "<th>N Evaluadores</th>";
					echo "<th>Promedio</th>";
        			break;
        		default:
        			if ($name == "ComentariosAlumnos") {
        				echo "<th>Evaluador</th>";
						echo "<th>Evaluado</th>";
						echo "<th>Asignatura</th>";
						echo "<th>Año</th>";
						echo "<th>Grupo</th>";
						echo "<th>Cuatrimestre</th>";
						echo "<th>Positivos</th>";
						echo "<th>Negativos</th>";
						echo "<th>Recomendaciones</th>";
        			}elseif($docentesClase == -1) {
        				echo "<th>Evaluador</th>";
						echo "<th>Evaluado</th>";
						echo "<th>Positivos</th>";
						echo "<th>Negativos</th>";
						echo "<th>Recomendaciones</th>";
        			}else{
        				echo "<th>Evaluador</th>";
						echo "<th>Evaluado</th>";
						echo "<th>Pregunta</th>";
						echo "<th>Resultados</th>";
						echo "<th>N Evaluadores</th>";
						echo "<th>Promedio</th>";

        			}
        			break;
        	}
        	echo "</tr>";

        	foreach ($mostrar as $key2 => $value2) {
		        $varId = $value2['id'];
		        echo "<tr id=$name>";
		            foreach ($value2 as $key => $value) {
		                if (($name == "ResulAlumnos" || $name == "ComentariosAlumnos") && $key === 'evaluado' && strlen($value)<8) {
		                    foreach ($docentesClase[$value] as $llave => $valor) {
		                        # code...
		                        if (strlen($llave)>1 && $llave != "id")
		                        {

		                            echo "<td>";
		                                echo "$valor";
		                            echo "</td>";
		                        }
		                    }
		                    continue;
		                }
		                if ($key == "id") {
		                	continue;
		                }
		                echo "<td>";
		                    echo $value;
		                echo "</td>";
		            }
		        echo "</tr>";
		    }

        		break;
        	case 'estructuras':
        		echo "<tr>";
	        		echo "<th>";echo "Usuarios";echo "</th>";
	        		echo "<th>";echo "Estructura";echo "</th>";
        		echo "</tr>";
        		foreach ($mostrar as $key => $value) {

	        		$evaluado = $value['usuario'];
	        		$varId = $key;
        			echo "<tr id=$table-$varId class='unselect' onclick=selectable($varId,$table)>";

        			foreach ($value as $key2 => $value2) {

        				echo "<td collapse=2>";
        					print preg_replace("[\n|\r|\n\r]", "", $value2);
        				echo "</td>";

        			}
        			echo "</tr>";

        		}
        		break;
        	default:

        	break;
        }

        echo "</table>";
        echo "\"";?>;
        Tab(label,id,tabContentHtml,table);


<?php }


//Importa los datos de un archivo csv
function import($tabla)
{
	if(isset($_REQUEST['subir']))
	{
		if($_FILES['archivo']['error'] != 0)
		{
			echo "alert('Error al Subir el Archivo!');";
			return;
		}

		$name = $_FILES['archivo']['tmp_name'];
		$type = $_FILES['archivo']['type'];
		$leng = $_FILES['archivo']['size'];
		// echo $type;
		// if($type == "application/vnd.ms-excel" && is_uploaded_file($name))
		if((($type == "text/csv") || $type == "application/vnd.ms-excel") && is_uploaded_file($name))
		{
			$fd = fopen($name,"r");

			//Determinar el caracter delimitador
			$caracter = "";
			$temp = fgetcsv($fd,0,";");
			if(count($temp)>1)
			{
				$caracter = ";";
			}else
			{
				$temp = fgetcsv($fd,0,",");
				if(count($temp)>1)
				{
					$caracter = ",";
				}
			}
			fclose($fd);

			//Recorrido del fichero
			$fd = fopen($name,"r");
			if($caracter == "," || $caracter == ";")
			{
				while($datos = fgetcsv($fd,2000,$caracter))
				{
					if(isset($datos[0]) && $datos != "")
					{
						$datos_mysql = array();
						foreach ($datos as $key => $value)
						{
							if($value != " " && $value != "")
							{
								$datos_mysql[] = $value;
							}
							else
								break;
            }

            if (count($datos_mysql) <= 0) {
            	echo ($datos_mysql[0]);
            	return;
            }
						//Asignar a la base de datos
						$Codigo_Estudiante = str_replace("\n","",$datos_mysql[0]);
						$Turno_Estudiante = $datos_mysql[1];
						$Grado_Estudiante = $datos_mysql[2];
						$Grupo_Estudiante = $datos_mysql[3];
						$Asignaturas = $datos_mysql[4];

						//Verificar si existe el codigo
						$conexion = new conexion();
						$tabla = "Alumnos";
						$camposAselect = "id";
						$where = "codigo='".$Codigo_Estudiante."'";

						$Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
						if (mysql_num_rows($Myconsulta)>0) {
							$datos = mysql_fetch_array($Myconsulta);
							$id_Estudiante = $datos['id'];
							//aGREGAR ASIGNATURAS
								AgregarAsignaturas($id_Estudiante,$Asignaturas,$Turno_Estudiante,$Grado_Estudiante,$Grupo_Estudiante);

						}else{
							//Ingresar al Estudiante a la tabla Alumnos
							$camposAinsertar = "codigo";
							$valores = "'".$Codigo_Estudiante."'";
							$conexion->getInsert($tabla,$camposAinsertar,$valores);
							$Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
							if (mysql_num_rows($Myconsulta)>0) {
							$datos = mysql_fetch_array($Myconsulta);
							$id_Estudiante = $datos['id'];
							//aGREGAR ASIGNATURAS
								AgregarAsignaturas($id_Estudiante,$Asignaturas,$Turno_Estudiante,$Grado_Estudiante,$Grupo_Estudiante);

							}

						}

					}
				}
			}

		}
		echo "alert('Importacion Finalizada!.');";

	}else {
            // Mostrar mensajes de errores
            echo "alert('El archivo debe tener una extensión .csv.');";
            return false;
     }
}

function AgregarAsignaturas ($id_Estudiante,$Asignaturas,$Turno,$Grado,$Grupo) {
	//Conexion
	$conexion = new conexion();
	//Buscar el codigo de la clase
	$tabla = "asignaturas";
	$camposAselect = "codigo";
	$where = "nombre = '".$Asignaturas."'";
	$Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
	if (mysql_num_rows($Myconsulta)>0) {
		$dato = mysql_fetch_array($Myconsulta);
		$Codigo_Asignatura = $dato['codigo'];
	}
	else{
		//Crear un documento con los datos no posibles de procesar.

		return;
	}

	//Buscar una relacion clase-docente basados en los datos del estudiante.
	$tabla = "docente_clase";
	$camposAselect = "id";
	$where = "codigo_clase='".$Codigo_Asignatura."' AND ano='".$Grado."' AND grupo=".$Grupo." AND periodo='".$Turno."'";
	$Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
	if (mysql_num_rows($Myconsulta)>0) {
		$dato = mysql_fetch_array($Myconsulta);
		$id_Codigo_Clase = $dato['id'];
	}
	else{
		//Crear un documento con los datos no posibles de procesar.
		return;
	}

	$tabla = "alumno_clase";
	$camposAinsertar = "id_alumno,id_docente_clase";
	$valores = $id_Estudiante.",".$id_Codigo_Clase;
	$Myconsulta = $conexion->getInsert($tabla,$camposAinsertar,$valores);
}

?>

	$(function () {

		if (location.pathname=="/SEI/admin/index.php" || location.pathname=="/SEI/admin") {
			return;
		}else{
			<?php
			$conexion = new conexion;
			$DatosDocentes = array();
			$DatosAdministradores = array();
			$DatosAsignaturas = array();
			$DatosPreguntas = array();

			// Verifica si no hay alguna funcion a realizar add or delete
			if (isset($_COOKIE['funcion'])) {
				$funcion = $_COOKIE['funcion'];
				$tabla = $_COOKIE['tabla'];
				$conexion = new conexion();
				switch ($funcion) {
					case 'import':
						switch($tabla)
						{
							case 'Alumnos':
								 import($tabla);
							break;
							case 'Docentes':
								import($tabla);
							break;
						}
					break;
					case 'add':
						switch ($tabla) {
							case 'estructuras':
								$usuario = $_COOKIE['UsuarioEstructura'];
								$table = $tabla;
								$camposAselect = "id";
								$where = "usuario = '$usuario'";
								$Myconsulta = $conexion->getSelect($table,$camposAselect,$where);
								$Nelementos = mysql_num_rows($Myconsulta);

								if ($Nelementos <= 0) {
									//Indicar que ya existe
									$camposAinsertar = "usuario,codigoEstructura";
									$valores = "'$usuario',''";
									$conexion->getInsert($table,$camposAinsertar,$valores);
								}
								break;
							case 'usuarios':
								$codigo = $_COOKIE['codigo'];
								$usuario = $_COOKIE['usuario'];
								$evaluado = $_COOKIE['evaluado'];
								$npreguntas = $_COOKIE['npreguntas'];
								$table = $tabla;
								$camposAselect = "id";
								$where = "codigo = '$codigo' AND usuario = '$usuario' AND evaluante = '$evaluado'";
								$Myconsulta = $conexion->getSelect($table,$camposAselect,$where);
								$Nelementos = mysql_num_rows($Myconsulta);
								if ($Nelementos > 0) {
									$camposAmodificar = "cantidad_pregunta = $npreguntas";
									$conexion->getUpdate($table,$camposAmodificar,$where);
								}else{
									$camposAinsertar = "codigo,usuario,evaluante,cantidad_pregunta";
									$valores = "'$codigo','$usuario','$evaluado',$npreguntas";
									$conexion->getInsert($table,$camposAinsertar,$valores);
								}
								break;
							case 'Alumnos':
								$codigos = explode(",", $_COOKIE['codigos']);
								$docentes = explode(",",$_COOKIE['docentes']);
								foreach ($codigos as $key => $value) {
									if (!($value == "")) {
										$table = $tabla;
										$camposAselect = "id";
										$where = "codigo = '$value'";
										$Myconsulta = $conexion->getSelect($table,$camposAselect,$where);
										$Nelementos = mysql_num_rows($Myconsulta);
										if ($Nelementos > 0) {
											$datos = mysql_fetch_array($Myconsulta);
											//  Verificar ahora en la tabla alumno_clase
											foreach ($docentes as $key2 => $value2) {
												$table2 = "alumno_clase";
												$camposAselect2 = "id";
												$where2 = "id_alumno = '$datos[id]' AND id_docente_clase = $value2";
												$Myconsulta2 = $conexion->getSelect($table2,$camposAselect2,$where2);
												$Nelementos = mysql_num_rows($Myconsulta2);
												// Si el amumno no tiene esa clase asignada solo se le asigna en la base de dato alumno_clase
												if ($Nelementos == 0) {
													$camposAinsertar = "id_alumno,id_docente_clase";
													$valores = "$datos[id],$value2";
													$conexion->getInsert($table2,$camposAinsertar,$valores);
												}
											}

										}else{

											// Si el alumno no ha sido ingresado a la base de datos se ingresa a la tabla alumnos

												$camposAinsertar = "codigo";
												$valores = "'$value'";
												$conexion->getInsert($table,$camposAinsertar,$valores);
											foreach ($docentes as $key2 => $value2) {

												// CApturar el id del alumno que acaba de ser ingresado
												$where = "codigo = '$value'";
												$Myconsulta = $conexion->getSelect($table,$camposAselect,$where);
												$datos = mysql_fetch_array($Myconsulta);
												$table2 = "alumno_clase";
												$camposAinsertar = "id_alumno,id_docente_clase";
												$valores = "$datos[id],$value2";
												$conexion->getInsert($table2,$camposAinsertar,$valores);
												}
										}
										}
								}
								break;
							case 'Docentes':
								$codigos = explode(",", $_COOKIE['codigos']);
								$docentes = explode(",",$_COOKIE['docentes']);
								$table = $tabla;
								$camposAselect = "id";

								foreach ($codigos as $key => $value) {
									$where = "codigo = '$value'";
									$Myconsulta = $conexion->getSelect($table,$camposAselect,$where);
									$Nelementos = mysql_num_rows($Myconsulta);
									if ($Nelementos <= 0) {
										$nombre = $docentes[$key];
										$codigo = $value;
										$camposAinsertar = "codigo,nombre";
										$valores = "'$codigo','$nombre'";
										$conexion->getInsert($table,$camposAinsertar,$valores);
									}
								}

								break;
							case 'docente_clase':
								$asignaturas = explode(",", $_COOKIE['asignaturas']);
								$docentes = explode(",",$_COOKIE['docentes']);
								$Ano = $_COOKIE['ano'];
								$Grupo = $_COOKIE['grupo'];
								$Periodo = $_COOKIE['periodo'];
								$Nelementos = 0;

								for ($i=0; $i < count($docentes) ; $i++) {
									// Buscar el codigo del docente.
									$camposAselect = "codigo";
								 	$where = "nombre = '".$docentes[$i]."'";
								 	$table = "Docentes";

									$Myconsulta = $conexion->getSelect($table,$camposAselect,$where);
									$Nelementos = mysql_num_rows($Myconsulta);
									// si el docente existe en la base de datos
									if ($Nelementos > 0) {
										// Capturar el codigo del docente.
										$codigo = mysql_fetch_array($Myconsulta);
										$codigoIdocente = $codigo['codigo'];

										// Buscar el codigo de la asignatura.
										$table = "asignaturas";
										$where = "nombre = '".$asignaturas[$i]."'";
										$Myconsulta = $conexion->getSelect($table,$camposAselect,$where);
										$Nelementos = mysql_num_rows($Myconsulta);
										// Si la asignatura Existe..
										if ($Nelementos > 0) {
											// Capturar el codigo de la asignatura.
											$codigo = mysql_fetch_array($Myconsulta);
											$codigoIasignatura = $codigo['codigo'];

											// Verificar si ya existen esos datos en la base de datos.
											$Nelementos = 0;
											$table = $tabla;
											$camposAselect = "id";
											$where = "codigo_docente='$codigoIdocente' AND codigo_clase='$codigoIasignatura' AND ano='$Ano' AND grupo=$Grupo AND periodo='$Periodo'";
											$Myconsulta = $conexion->getSelect($table,$camposAselect,$where);
											$Nelementos = mysql_num_rows($Myconsulta);
											// Si no existen esos datos en la base de datos ingresarlos.
											if ($Nelementos <= 0) {
												$camposAinsertar = "codigo_docente,codigo_clase,ano,grupo,periodo";
												$valores = "'$codigoIdocente','$codigoIasignatura','$Ano',$Grupo,'$Periodo'";
												$conexion->getInsert($table,$camposAinsertar,$valores);
											}
										}
									}
								 }

							break;
							case 'Administradores':
								$codigos = explode(",", $_COOKIE['codigos']);
								$administradores = explode(",",$_COOKIE['administradores']);
								$docencia = explode(",",$_COOKIE['docencia']);
								$table = $tabla;
								$camposAselect = "id";

								foreach ($codigos as $key => $value) {
									$where = "codigo = '$value'";
									$Myconsulta = $conexion->getSelect($table,$camposAselect,$where);
									$Nelementos = mysql_num_rows($Myconsulta);
									if ($Nelementos <= 0) {
										$nombre = $administradores[$key];
										$docente = $docencia[$key];
										$codigo = $value;
										$camposAinsertar = "codigo,nombre,docente";
										$valores = "'$codigo','$nombre',$docente";
										$conexion->getInsert($table,$camposAinsertar,$valores);

										// Si el Administrador tambien es docente se ingresa en la tabla de docentes.
										if($docente > 0)
										{
											$table2 = "Docentes";
											$Myconsulta = $conexion->getSelect($table2,$camposAselect,$where);
											$Nelementos = mysql_num_rows($Myconsulta);
											if ($Nelementos <= 0) {
												$camposAinsertar = "codigo,nombre";
												$valores = "'$codigo','$nombre'";
												$conexion->getInsert($table2,$camposAinsertar,$valores);

											}
										}

									}
								}

								break;
							case 'Preguntas':
								$Preguntas = explode(",", $_COOKIE['preguntas']);
								$evaluado = $_COOKIE['evaluado'];
								$evaluador = $_COOKIE['evaluador'];
								$table = $tabla;
								$camposAselect = "id";

								//Verificar si el evaluado y el evaluador esta registrado en la base de datos.
								$where = "evaluador = '$evaluador' AND evaluado = '$evaluado'";
								$Myconsulta = $conexion->getSelect($table,$camposAselect,$where);
								$Nelementos = mysql_num_rows($Myconsulta);
								if ($Nelementos <= 0) {
									// se el evaluador y el evaluado no existe se agrega a la base datos
									$camposAinsertar = "evaluador,evaluado";
									$valores = "'$evaluador','$evaluado'";
									$conexion->getInsert($table,$camposAinsertar,$valores);
									//Buscar el id correspondiente al evaluado y evaludor en la base de datos.
									$where = "evaluador = '$evaluador' AND evaluado = '$evaluado'";
									$camposAselect = "id";
									$Myconsulta = $conexion->getSelect($table,$camposAselect,$where);
									$Nelementos = mysql_num_rows($Myconsulta);
								}

								if ($Nelementos > 0) {
									//Capturar el id de los evaluados.
									$id = mysql_fetch_array($Myconsulta);
									$idEvaluados = $id['id'];
									//Ingresar las preguntas...
									foreach ($Preguntas as $key => $value) {
										$table = "listaPreguntas";
										$Preguntas = $key;
										$camposAinsertar = "pregunta";
										$valores = "'$value'";
										$conexion->getInsert($table,$camposAinsertar,$valores);

										//Buscar el id correspondiente al evaluado y evaludor en la base de datos.
										$where = "pregunta = '$value'";
										$camposAselect = "id";
										$Myconsulta = $conexion->getSelect($table,$camposAselect,$where);
										$Nelementos = mysql_num_rows($Myconsulta);
										if ($Nelementos > 0) {
											//Capturar el id de las preguntas.
											$id = mysql_fetch_array($Myconsulta);
											$idPreguntas = $id['id'];
											$table = "usuario_pregunta";

											//ingregas los datos de la tabla usuarios preguntas
											$camposAinsertar = "listaPreguntas_id,Preguntas_id";
											$valores = "$idPreguntas,$idEvaluados";
											$conexion->getInsert($table,$camposAinsertar,$valores);

											}
										}

									}

								break;
							default:
								break;
						}
						break;
					case 'del':
						switch ($tabla) {
							case 'estructuras':
								$id = $_COOKIE['id'];
								$table = $tabla;
								$camposAselect = "id";
								$where = "id = $id";
								$Myconsulta = $conexion->getSelect($table,$camposAselect,$where);
								$Nelementos = mysql_num_rows($Myconsulta);
								if ($Nelementos > 0) {
									$conexion->getDel($table,$where);
								}
								break;
							case 'usuarios':
								$id = $_COOKIE['id'];
								$table = $tabla;
								$camposAselect = "id";
								$where = "id = $id";
								$Myconsulta = $conexion->getSelect($table,$camposAselect,$where);
								$Nelementos = mysql_num_rows($Myconsulta);
								if ($Nelementos > 0) {
									$conexion->getDel($table,$where);
								}
								break;
							case 'Alumnos':
								$id = $_COOKIE['id'];
								$table = $tabla;
								$camposAselect = "id";
								$where = "id = $id";
								$Myconsulta = $conexion->getSelect($table,$camposAselect,$where);
								$Nelementos = mysql_num_rows($Myconsulta);
								if ($Nelementos > 0) {
									$conexion->getDel($table,$where);
								}
								$table = "alumno_clase";
								$camposAselect = "id";
								$where = "id_alumno = $id";
								$Myconsulta = $conexion->getSelect($table,$camposAselect,$where);
								$Nelementos = mysql_num_rows($Myconsulta);
								if ($Nelementos > 0) {
									$conexion->getDel($table,$where);
								}
								break;
							case 'Docentes':
								$id = $_COOKIE['id'];
								// buscar a docente en su tabla
								$table = $tabla;
								$camposAselect = "codigo";
								$where = "id = $id";
								$Myconsulta = $conexion->getSelect($table,$camposAselect,$where);
								$Nelementos = mysql_num_rows($Myconsulta);
								$codigo = mysql_fetch_array($Myconsulta);
								$codigo = $codigo['codigo'];
								if ($Nelementos > 0) {
									// buscar a docente en la tabla administrador
									$table2 = "Administradores";
									$camposAselect2 = "id";
									$where2 = "codigo = '$codigo'";
									$Myconsulta = $conexion->getSelect($table2,$camposAselect2,$where2);
									$Nelementos = mysql_num_rows($Myconsulta);

									if ($Nelementos > 0) {$conexion->getUpdate($table2,"docente=0",$where2);}
									$conexion->getDel($table,$where);

									$table = "docente_clase";
									$camposAselect = "id";
									$where = "codigo_docente = '$codigo'";
									$Myconsulta = $conexion->getSelect($table,$camposAselect,$where);
									$Nelementos = mysql_num_rows($Myconsulta);
									$id = mysql_fetch_array($Myconsulta);
									$id = $id['id'];
									if ($Nelementos > 0) {
										$conexion->getDel($table,$where);
										$table = "alumno_clase";
										$where = "id_docente_clase = $id";
										$Myconsulta = $conexion->getSelect($table,$camposAselect,$where);
										$Nelementos = mysql_num_rows($Myconsulta);

										if ($Nelementos > 0) {
											$conexion->getDel($table,$where);
										}
									}
								}
								break;
								case 'docente_clase':
									$id = $_COOKIE['id'];
									// Verificar si existe el id en la tabla docente_clase
									$table = $tabla;
									$camposAselect = "id";
									$where = "id = $id";
									$Myconsulta = $conexion->getSelect($table,$camposAselect,$where);
									$Nelementos = mysql_num_rows($Myconsulta);
									if ($Nelementos > 0) {
										// si existe eliminarlo.
										$conexion->getDel($table,$where);
										// buscar el id el la tabla alumno_clase
										$table = "alumno_clase";
										$where = "id_docente_clase = $id";
										$Myconsulta = $conexion->getSelect($table,$camposAselect,$where);
										$Nelementos = mysql_num_rows($Myconsulta);
										if ($Nelementos > 0)
										{
											// Si existe eliminarlo
											$conexion->getDel($table,$where);
										}
									}
								break;
							case 'Administradores':
								$id = $_COOKIE['id'];
								// buscar a docente en su tabla
								$table = $tabla;
								$camposAselect = "codigo";
								$where = "id = $id";
								$Myconsulta = $conexion->getSelect($table,$camposAselect,$where);
								$Nelementos = mysql_num_rows($Myconsulta);
								$codigo = mysql_fetch_array($Myconsulta);
								$codigo = $codigo['codigo'];
								if ($Nelementos > 0) {
									// Elimianar el administrador
									$conexion->getDel($table,$where);

									// buscar al administrador en la tabla docentes
									$table2 = "Docentes";
									$camposAselect2 = "id";
									$where2 = "codigo = '$codigo'";
									$Myconsulta = $conexion->getSelect($table2,$camposAselect2,$where2);
									$Nelementos = mysql_num_rows($Myconsulta);

									if ($Nelementos > 0)
									{
										// Si existe el administrador en la tabla docente eliminarlo
										$conexion->getDel($table2,$where2);

										// verificar si el Administrador tiene asignaturas designadas
										$table = "docente_clase";
										$camposAselect = "id";
										$where = "codigo_docente = '$codigo'";
										$Myconsulta = $conexion->getSelect($table,$camposAselect,$where);
										$Nelementos = mysql_num_rows($Myconsulta);
										$id = mysql_fetch_array($Myconsulta);
										$id = $id['id'];
										if ($Nelementos > 0) {
											// si tiene asignaturas asignadas eliminar las designaciones
											$conexion->getDel($table,$where);

											// Verifiar si hay que reciben clase con ese administrador
											$table = "alumno_clase";
											$where = "id_docente_clase = $id";
											$Myconsulta = $conexion->getSelect($table,$camposAselect,$where);
											$Nelementos = mysql_num_rows($Myconsulta);

											if ($Nelementos > 0) {
												// si hay alumnos elimininar esa relacion.s
												$conexion->getDel($table,$where);

											}
										}
									}

								}
								break;
							case 'Preguntas':
								$ElementSelect = explode("-",$_COOKIE['ElementSelect']);
								$id = $ElementSelect[3];
								$table = "listaPreguntas";
								$camposAselect = "id";
								$where = "id = $id";
								$Myconsulta = $conexion->getSelect($table,$camposAselect,$where);
								$Nelementos = mysql_num_rows($Myconsulta);
								$codigo = mysql_fetch_array($Myconsulta);
								$codigo = $codigo['codigo'];
								if ($Nelementos > 0) {
									// Elimianar el administrador
									$conexion->getDel($table,$where);
									// buscar al administrador en la tabla docentes
									$table = "usuario_pregunta";
									$camposAselect = "id";
									$where = "listaPreguntas_id = $id";
									$Myconsulta = $conexion->getSelect($table,$camposAselect,$where);
									$Nelementos = mysql_num_rows($Myconsulta);
									if ($Nelementos > 0)
									{
										// Si existe el administrador en la tabla docente eliminarlo
										$conexion->getDel($table,$where);
									}
								}
								break;
							default:
								# code...
								break;
						}
						break;
				}
				echo "document.cookie = 'funcion=null';";
			}
			// Bucar los nombre de las clases con sus codigos.,

		    $tabla = "asignaturas";
		    $camposAselect = "codigo,nombre";
		    $where = 1;
		    $Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
		    $Nelementos = ExisteCodigo(mysql_num_rows($Myconsulta));
		    for ($i=0; $i <$Nelementos ; $i++) {
		        $datos = mysql_fetch_array($Myconsulta);
		        $DatosAsignaturas[$datos['codigo']] = $datos['nombre'];
		    }
		// Bucar los nombre de los docentes y sus codigos
		    $tabla = "Docentes";
		    $camposAselect = "codigo,nombre";
		    $where = 1;
		    $Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
		    $Nelementos = mysql_num_rows($Myconsulta);
		    for ($i=0; $i <$Nelementos ; $i++) {
		        $datos = mysql_fetch_array($Myconsulta);
		        $DatosDocentes[$datos['codigo']] = $datos['nombre'];
		    }

		// Bucar los nombre de los administradores y sus codigos

		    $tabla = "Administradores";
		    $camposAselect = "codigo,nombre";
		    $where = 1;
		    $Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
		    $Nelementos = mysql_num_rows($Myconsulta);
		    for ($i=0; $i <$Nelementos ; $i++) {
		        $datos = mysql_fetch_array($Myconsulta);
		        $DatosAdministradores[$datos['codigo']] = $datos['nombre'];
		    }

		    $_SESSION['Asignaturas']  = $DatosAsignaturas;
		    $_SESSION['Docentes']  = $DatosDocentes;
		    $_SESSION['Administradores']  = $DatosAdministradores;

		    // $_SESSION['Preguntas']  = $DatosPreguntas;
				// Abrir Tablas
				if(isset($_SESSION['USER'])){
					foreach ($_COOKIE as $key => $value) {
				        if ($value == "open") {
				            buscar_tabla($key);
				        }
				        /*if ($new == $key) {
				           $bandera = 0;
				        }*/
			   		 }
				}
			    ?>
			  };
			});


	</script>