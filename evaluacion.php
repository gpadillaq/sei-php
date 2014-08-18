<?php

function funcion_instrucciones($usuario)
{
	//Ralizar la conexion a la base de datos
	$conexion = new conexion(); 
	$tabla = 'estructuras'; 
	$where = 'usuario = '."'".$usuario->showTipoUsuario()."'"; //variable condicional para la consulta
	$camposAselect = "codigoEstructura";

	 //Verificar el Tipo de Usuario.
	$Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
	$Nelementos = mysql_num_rows($Myconsulta);
	$CodigoHTML = mysql_fetch_array($Myconsulta);

	//MOstrar el codigo
	echo html_entity_decode($CodigoHTML['codigoEstructura']);
}
function encabezadoTabla()
{?>
	<tr>
		<th>No</th>
		<th>Aspecto a Evaluar</th>
		<th>1</th>
		<th>2</th>
		<th>3</th>
		<th>4</th>
		<th>5</th>
	</tr>
	
<?php }

function aspectos($evaluador,$evaluado)
{
	
}
function crearLista($usuario)
{
	foreach ($usuario->showUsuariosEvaluar() as $key => $user_to_evaluar) {
		# code...
		$ancla = "#tabs-".$user_to_evaluar;
		echo "<li class='tabs'><a href=$ancla> $user_to_evaluar </a></li>";
	}
}
function showMostrarPreguntas($usuario,$user_to_evaluar,$codigo)
{
	// Segmento de Preguntas
	if (($codigo == $usuario->showNumeroCarnet()) && (($user_to_evaluar == "Docentes") || ($user_to_evaluar == "Administradores"))) {
		# code...
		return 0;
	}
	echo "<table class='table table-bordered'>";	
		encabezadoTabla();
		$indice = 1;
		foreach ($usuario->showEvaluador_Evaluado() as $evaluador => $value) {
			# code...
			foreach ($value as $key => $evaluado) {
				# code...

				if ($evaluado == $user_to_evaluar) {
					# code...
					foreach ($usuario->showpreguntas($evaluado) as $key2 => $preguntas) {
					# code...
					$Npreguntas = $usuario->showRangoPreguntas($evaluado);//Revisar esta parte ya no se utiliza
					echo "<tr>";
						echo "<td>$indice</td>";
						echo "<td>";
							echo "$preguntas";
						echo "</td>";
						
						// Opciones de las Preguntas...

						// Verificar si es auto evaluacion.
						if (strstr($user_to_evaluar, "auto")) {
							# code...
							$evaluador = $user_to_evaluar;
						}
						for ($i=0; $i <= 4; $i++) { 
							# code...
							$nombre = $evaluador.",".$user_to_evaluar.",".$codigo.",".$key2;
							echo "<td><input type=radio name=$nombre required value=".(($i)*2.5)."></td>";
						}
					echo "</tr>";
					$indice +=1;
				}
				echo "</table>";

				//Segmento de aspectos positivos y negativos y campos a mejorar.
				// echo "<div class=anote>";

					// Introduccion
					echo "<p>";
						echo "En los siguientes espacios anote los aspectos positivos y negativos. Además tiene un espacio para realizar
						recomendaciones. Por favor sea breve y preciso con sus aportes.";
					echo "</p>";

					//CAmpos de textos.

					// positivos
					echo "<h4 class=CampTextAnote>"; 
						echo "Aspectos POSITIVOS";
					echo "</h4>";
					echo "<textarea name='".$evaluador.",".$user_to_evaluar.",".$codigo.",positivos' cols='50' rows='5' maxlength='100' placeholder='Escribe Tu comentario aqui....'></textarea>";

					// negativos
					echo "<h4 class=CampTextAnote>"; 
						echo "Aspectos NEGATIVOS";
					echo "</h4>";
					echo "<textarea name='".$evaluador.",".$user_to_evaluar.",".$codigo.",negativos' cols='50' rows='5' maxlength='100' placeholder='Escribe Tu comentario aqui....'></textarea>";

					// recomendaciones.
					echo "<h4 class=CampTextAnote>"; 
						echo "RECOMENDACIONES";
					echo "</h4>";
					echo "<textarea name='".$evaluador.",".$user_to_evaluar.",".$codigo.",recomendaciones' cols='50' rows='5' maxlength='100' placeholder='Escribe Tu comentario aqui....'></textarea>";
 				// echo "</div>";


				}

			}
		}
}
function showAdministradores($usuario,$user_to_evaluar)
{
	# code...
	echo "<div class='accordion'>";

	switch ($usuario->showTipoUsuario()) {
		case ' ':
			# code...
		break;
		default:
		foreach ($usuario->showAdministradores() as $codigo => $nombre) {
			# code...
			echo "<h2 class='tabs'>".$codigo;
			echo " - ".$nombre."</h2>";
		
			echo "<div>";
			// echo "<div class='introduccion'>";
			// 	showintroAdmin();
			// echo "</div>";
					
				showMostrarPreguntas($usuario,$user_to_evaluar,$codigo);
			
			echo "</div>";

		}
			# code...
			break;
	}
	echo "</div>";
}

function showDocentes($usuario,$user_to_evaluar)
{
	# code...
	echo "<div class=accordion>";
	switch ($usuario->showTipoUsuario()) {
		case 'Alumnos':
			# code...
				foreach ($usuario->showIdClases() as $key => $value) {
			 	$Cclase = $usuario->showCodigoClase();
				$Cdocente = $usuario->showCodigoDocente();

				echo "<h2 class='tabs'>".$usuario->showCodigoDocente_to_Nombre($Cdocente[$value]);
				echo " - ".$usuario->showCodigoClase_to_Nombre($Cclase[$value])."</h2>";
				
				echo "<div>";
				// echo "<div class='introduccion'>";
				// 	showintroDoc();
				// echo "</div>";
				echo "<table class=table>";
					showMostrarPreguntas($usuario,$user_to_evaluar,$value);
				echo "</table>";
				echo "</div>";
			}			
			break;
		default:
			# code...
		foreach ($usuario->showNombreDocente() as $codigo => $nombre) {
			# code...
			echo "<h4 class='tabs'>".$codigo;
			echo " - ".$nombre."</h4>";
		
			echo "<div>";
			// echo "<div class='introduccion'>";
			// 		showintroDoc();
			// echo "</div>";

			echo "<table class=table>";
				showMostrarPreguntas($usuario,$user_to_evaluar,$codigo);
			echo "</table>";
			echo "</div>";

		}
			break;
	}
	echo "</div>";

}
function showOtros($usuario,$user_to_evaluar)
{
	# code...
	echo "<table class=table>";
		showMostrarPreguntas($usuario,$user_to_evaluar,$usuario->showNumeroCarnet());
	echo "</table>";
}

function crearCuerpoLista($usuario){

	foreach ($usuario->showUsuariosEvaluar() as $key => $user_to_evaluar) {
		# code...
		echo "<div id=tabs-$user_to_evaluar>";
			// Cuerpo de cada uno de los usuarios a evalar...
			switch ($user_to_evaluar) {
				case 'Docentes':
					# code...
				showDocentes(&$usuario,$user_to_evaluar);
					break;
				case 'Administradores':
					# code...
				showAdministradores(&$usuario,$user_to_evaluar);
					break;				
				default:
					# code...
				showOtros(&$usuario,$user_to_evaluar);
					break;
			}
		echo"</div>";
	}

}

function alumnos($usuario,$Nelementos)
{
	# code...
}

function docentes($usuario,$Nelementos,$conexion,$datos)
{
	# code...
	switch ($usuario->showTipoUsuario()) {
		case 'Alumnos':
			# code...
		//Guardar el id del alumno
		$usuario->getIdAlumno($datos['id']);

		//Buscar las asignaturas del alumno
		$tabla = "alumno_clase";
		$camposAselect = 'id_docente_clase';
		$where = 'id_alumno = '.$usuario->showIdAlumno();
		$Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
		$Nelementos = ExisteCodigo(mysql_num_rows($Myconsulta),$tabla);
		$usuario->getNumeroToEvaluarDocentes($Nelementos); 	
		// Guardar los id_docente-clase en el objeto.
		for ($i=0; $i < $Nelementos; $i++) { 
			$datos = mysql_fetch_array($Myconsulta);
			foreach ($datos as $key => $value) {
				# code...
				if ($key === $camposAselect) {
					# code...
					$usuario->getIdClases($value);
					
				}								
			}
		}

 		//Buscar los codigos de las asignaturas y los docentes.
 		$tabla = "docente_clase";
 		$camposAselect = "codigo_docente,codigo_clase";
 		$where = "";
		foreach ($usuario->showIdClases() as $key => $value) {
			# code...
			$where = "id =".$value;
			$Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
 			$Nelementos = ExisteCodigo(mysql_num_rows($Myconsulta),$tabla);
 			$datos = mysql_fetch_array($Myconsulta);

 			//Guardar los Codigos 
			foreach ($datos as $key2 => $value2) {
				# code...
				if ($key2 === "codigo_docente") {
					# code...
					$usuario->getCodigoDocente($value2,$value);
				}elseif ($key2 === "codigo_clase") {
					# code...
					$usuario->getCodigoClase($value2,$value);
				}								
			}
		} 

		//Buscar el Nombre de las Clases.
		$tabla = "asignaturas";
		$camposAselect = "nombre";
		$where = "";
		foreach ($usuario->showCodigoClase() as $key => $codigo) {
			# code...
			$where = "codigo = ".$codigo;
			$Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
			$Nelementos = ExisteCodigo(mysql_num_rows($Myconsulta),$tabla);
 			$datos = mysql_fetch_array($Myconsulta);

 			//Guardar los nombres de las Clases
 			foreach ($datos as $key2 => $nombre) {
 				# code...
 				if ($key2 === "nombre") {
 					# code...
 					$usuario->getNombreClase($nombre,$codigo);
 				}
 			}			
		}

		//Buscar el Nombre de los Docentes
		$tabla = "Docentes";
		$camposAselect = "nombre";
		$where = "";
		foreach ($usuario->showCodigoDocente() as $key => $codigo) {
			# code...
			$where = "codigo = '".$codigo."'";
			$Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
			$Nelementos = ExisteCodigo(mysql_num_rows($Myconsulta),$tabla);
 			$datos = mysql_fetch_array($Myconsulta);

 			//Guardar los nombres de los Docentes
 			foreach ($datos as $key2 => $nombre) {
 				# code...
 				if ($key2 === "nombre") {
 					# code...
 					$usuario->getNombreDocente($nombre,$codigo);
 				}
 			}
		}
			break;
		
		default:
			# code...
		// Buscar a todos los Docentes.
		$tabla = "Docentes";
		$camposAselect = "codigo,nombre";
		$where = "1";
		$Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
		$Nelementos = ExisteCodigo(mysql_num_rows($Myconsulta),$tabla);
		$usuario->getNumeroToEvaluarDocentes($Nelementos); 		
 		//Almacenas los DAtos.
 		for ($i=0; $i < $Nelementos ; $i++) { 
 			# code...
 			$datos = mysql_fetch_array($Myconsulta);
 			$usuario->getNombreDocente($datos['nombre'],$datos['codigo']);
 		}
			break;
	}
		
}

function administradores($usuario,$Nelementos,$conexion)
{
	# code...
	//Capturar a todos los administradores
	$tabla = "Administradores";
	$camposAselect = "codigo,nombre";
	$where = "1";
	$Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
	$Nelementos = ExisteCodigo(mysql_num_rows($Myconsulta),$tabla);
	$usuario->getNumeroToEvaluarAdministradores($Nelementos);

	for ($i=0; $i < $Nelementos; $i++) { 
		# code...
		$datos = mysql_fetch_array($Myconsulta);
		$usuario->getCodigo_nombre($datos['nombre'],$datos['codigo']);
	}
}
//captura de los datos
if(isset($_REQUEST['btingreso']))
{
	//llamada al archivo que contiene las clases;
	require_once("clases/usuarios.clase.php");
	require_once("clases/conexion.clase.php");
	require_once("clases/fungeneral.fn.php");

	//Crear Objetos
	$usuario = new Extender();
	$usuario->heredar('Usuarios');

	//Capturar los datos
	$usuario->getCodigos($_REQUEST['tbanio'],strtoupper($_REQUEST['tbusuario']),$_REQUEST['tbcodigo']);

	//Ralizar la conexion a la base de datos
	 $conexion = new conexion(); 
	$tabla = 'usuarios'; 
	$where = 'codigo = '."'".$usuario->showCodigoUsuario()."'"; //variable condicional para la consulta

	 //Verificar el Tipo de Usuario.
	$Myconsulta = $conexion->getSelect($tabla,'usuario, evaluante,cantidad_pregunta',$where);
	$Nelementos = ExisteCodigo(mysql_num_rows($Myconsulta),$tabla);
	
	

	for ($i=0,$bandera=0; $i < $Nelementos; $i++) { 
		$datos = mysql_fetch_array($Myconsulta);
		foreach ($datos as $key => $value) {
			//Si el la primera vez que entra al ciclo guardar el tipo de 
			//usuario y almacenarlo en el obejeto
			if ($key === 'usuario' && $bandera == 0) {
				$usuario->getTipoUsuario($value);
			}else
			//Almacenar los usuario a evaluar
			if ($key === 'evaluante') {
				$usuario->getUsuariosEvaluar($value);
				$usuario->getEvaluador_Evaluado($usuario->showTipoUsuario(),$value);
			}else
			//Almacenar el numero de preguntas para el evaluante
			if ($key === 'cantidad_pregunta') {
				$usuario->getRangoPreguntas($datos['evaluante'],$value);
			}

		}
	}
	
	#Verificar el Tipo de usuario a evaluar.
	switch ($usuario->showTipoUsuario()) {
		case 'Alumnos':
			# code...
		$camposAselect = 'id,evaluado';
			break;
		case 'Docentes':
			# code...
		$camposAselect = 'nombre,evaluado';
			break;
		case 'Administradores':
			# code...
		$camposAselect = 'nombre,docente,evaluado';
			break;
		default:
		$camposAselect = 'nombre,evaluado';

	}
	// Almacenar el las cookies lo datos del 
	setcookie('TABLE',$usuario->showTipoUsuario());
	setcookie('CODIGO',$usuario->showNumeroCarnet());

	//Verificar si el usuario existe y si existe Buscar sus Datos
	$tabla = $usuario->showTipoUsuario();
	$where = 'codigo = '."'".$usuario->showNumeroCarnet()."'";
	$Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
	$Nelementos = ExisteCodigo(mysql_num_rows($Myconsulta),$tabla);
	$datos = mysql_fetch_array($Myconsulta);
	if ($datos['evaluado'] != 0) {
			usuarioEvaluado($usuario->showNumeroCarnet());
		}

	//Si existe la celda docente agregarlo siguiente 
	if ($datos['docente'] != 0) {
		# code...
		//Agregarle los evaluantes de un usuario de tipo docente.
		$tabla = 'usuarios';
		$where = "usuario = 'docentes'";
		$Myconsulta = $conexion->getSelect($tabla,'evaluante,cantidad_pregunta',$where);
		$Nelementos = ExisteCodigo(mysql_num_rows($Myconsulta),$tabla);
		
		for ($i=0,$bandera=0; $i < $Nelementos; $i++) { 
			$datos = mysql_fetch_array($Myconsulta);
			foreach ($datos as $key => $value) {
				//Almacenar los usuario a evaluar
				if ($key === 'evaluante') {
					$usuario->getUsuariosEvaluar($value);
					$usuario->getEvaluador_Evaluado('Docentes',$value);

				}else
				//Almacenar el numero de preguntas para el evaluante
				if ($key === 'cantidad_pregunta') {
					$usuario->getRangoPreguntas($datos['evaluante'],$value);
				}
			}
		}
	}

	//Asignar Datos a las Nuevas Clases.
	foreach ($usuario->showUsuariosEvaluar() as $key => $value) {
		# code...	
		switch ($value) {
			case 'Alumnos':
				# code...
			// Asignar un objeto para cada tipo de usuario a evaluar
			$usuario->heredar($value);
			alumnos(&$usuario,$Nelementos,&$conexion);
				break;
			case 'Docentes':
				# code...
				// Asignar un objeto para cada tipo de usuario a evaluar
			$usuario->heredar($value);
			docentes(&$usuario,$Nelementos,&$conexion,&$datos);
				break;
			case 'Administradores':
				# code...
			// Asignar un objeto para cada tipo de usuario a evaluar
			$usuario->heredar($value);
			administradores(&$usuario,$Nelementos,&$conexion);
				break;
			default:
				# code...
				break;
		}

	}

	$usuario->heredar('preguntas');
	//Buscar las preguntas pertinentes al usuario.
	foreach ($usuario->showEvaluador_Evaluado() as $evaluador => $value) {
		# code...
		foreach ($value as $key2 => $evaluado) {
			# code...
			$tabla = 'Preguntas';
			$camposAselect = 'id';

			//Busca el id de las preguntas.
			$where = "evaluador = '".$evaluador."' AND evaluado = '".$evaluado."'";
			$Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
			$Nelementos = ExisteCodigo(mysql_num_rows($Myconsulta),$tabla);

			$datos = mysql_fetch_array($Myconsulta);
			$tabla = "usuario_pregunta";
			$camposAselect = "listaPreguntas_id";
			$where = "Preguntas_id = ".$datos['id'];

			$Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
			$Nelementos = ExisteCodigo(mysql_num_rows($Myconsulta),$tabla);

			//Buscar las preguntas.
			$tabla = 'listaPreguntas';
			$camposAselect = "id,pregunta";
			$where = "";
			$datos = mysql_fetch_array($Myconsulta);
			$where .= "id = ".$datos['listaPreguntas_id'];
			for ($i=0; $i < $Nelementos - 1; $i++) { 
				# code...
				$datos = mysql_fetch_array($Myconsulta);
				$where .= " OR id = ".$datos['listaPreguntas_id'];			
			}
			$Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
			$Nelementos = ExisteCodigo(mysql_num_rows($Myconsulta),$tabla);

			for ($i=0; $i < $Nelementos; $i++) { 
				$datos = mysql_fetch_array($Myconsulta);
				$usuario->getpreguntas($evaluado,$datos['id'],$datos['pregunta']);
			}
		}	

	}


	require_once("plantillas/Pevaluacion.php");

}
else
{
  echo "<script>location.href = 'index.php';</script>";
}


?>