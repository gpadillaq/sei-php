<?php
require_once("clases/conexion.clase.php");
require_once("clases/scripts.php");
require_once("clases/fungeneral.fn.php");
$conexion = new conexion();
if (isset($_REQUEST['Enviar'])) {
	# code...
	foreach ($_REQUEST as $key => $value) {
	# code...
	if ($value != "Enviar") {

		//Verificar si el usuario existe y si existe Buscar sus Datos
		$tabla = $_COOKIE['TABLE'];
		$where = 'codigo = \''.$_COOKIE['CODIGO']."'";
		$Myconsulta = $conexion->getSelect($tabla,"evaluado",$where);
		$Nelementos = ExisteCodigo(mysql_num_rows($Myconsulta),$tabla);
		$datos = mysql_fetch_array($Myconsulta);
		if ($datos['evaluado'] != 0) {
				usuarioEvaluado($_COOKIE['CODIGO']);
			}
		
		$name = explode(",", $key);

		if (is_numeric($name['3'])) {
			# code...
			// Declaracion de variables de control de los comentarios
			$bandera = 0;
			$positivos = "";
			$negativos = "";
			$recomendaciones = "";

			//Evaluacion Resultados Alumnos
			if ($name['0'] == "Alumnos" && $name['1'] == "Docentes") {
				//Buscar la Pregunta
				$tabla = "listaPreguntas";
				$camposAselect = "pregunta";
				$where = "id = ".$name['3'];
				$Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
				$Nelementos = mysql_num_rows($Myconsulta);
				if($Nelementos > 0)
				{
					//capturar pregunta
					$pregunta = mysql_fetch_array($Myconsulta);
					$pregunta = $pregunta["pregunta"];

					//Buscar asignatura año grupo periodo
					$tabla = "docente_clase";
					$camposAselect = "*";
					$where = "id = ".$name['2'];
					$Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
					$Nelementos = mysql_num_rows($Myconsulta);
					if($Nelementos > 0)
					{
						//Capturar datos
						$datosDocenteClase = mysql_fetch_array($Myconsulta);

						//CApturar codigo del docente
						$docente = $datosDocenteClase["codigo_docente"];
						//Capturar codigo de la asignatura
						$clase = $datosDocenteClase["codigo_clase"];
						$ano = $datosDocenteClase["ano"];
						$grupo = $datosDocenteClase["grupo"];
						$periodo = $datosDocenteClase["periodo"];

						//Buscar el nombre dela asignatura
						$tabla = "asignaturas";
						$camposAselect = "nombre";
						$where = "codigo = ".$clase;
						$Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
						$Nelementos = mysql_num_rows($Myconsulta);
						if($Nelementos > 0)
						{
							$clase = mysql_fetch_array($Myconsulta);
							//Guardar el nombre de la clase
							$clase = $clase["nombre"];

							//Buscar el nombre del docente.
							$tabla = "Docentes";
							$camposAselect = "nombre";
							$where = "codigo = '".$docente."'";
							$Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
							$Nelementos = mysql_num_rows($Myconsulta);
							if($Nelementos > 0)
							{
								$docente = mysql_fetch_array($Myconsulta);
								//Guardar el nombre del docente
								$docente = $docente["nombre"];
								$tabla = "resultados_alumnos";
							}
						}
					}					
				}
				//Insertar los datos a la base de datos
				$tabla = "resultados_alumnos_temp";
				$camposAinsertar = "evaluador,evaluado,asignatura,ano,grupo,periodo,pregunta,resultados";
				$valores = "'".$name['0']."','".$docente."','".$clase."','".$ano."','".$grupo."','".$periodo."','".$pregunta."',".$value."";
				$conexion->getInsert($tabla,$camposAinsertar,$valores);
							
			}//Evaluacion Resultados otros
			else{
				//Buscar la Pregunta
				$tabla = "listaPreguntas";
				$camposAselect = "pregunta";
				$where = "id = ".$name['3'];
				$Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
				$Nelementos = mysql_num_rows($Myconsulta);
				if($Nelementos > 0)
				{
					//capturar pregunta
					$pregunta = mysql_fetch_array($Myconsulta);
					$pregunta = $pregunta["pregunta"];

					//Buscar el nombre del administrador
					$tabla = "Administradores";
					$camposAselect = "nombre";
					$where = "codigo = '".$name['2']."'";
					$Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
					$Nelementos = mysql_num_rows($Myconsulta);
					if($Nelementos > 0)
					{
						//capturar pregunta
						$administrador = mysql_fetch_array($Myconsulta);
						$administrador = $administrador["nombre"];
					}
					//Guardar la evaluacion
					$tabla = "resultados_otros_temp";
					$camposAinsertar = "evaluador,evaluado,pregunta,resultados";
					$valores = "'".$name['0']."','".$administrador."','".$pregunta."',".$value."";
					$conexion->getInsert($tabla,$camposAinsertar,$valores);
								
				}
			}				
		}else
		{
			if ($name['3'] == "positivos") {
				# code...
				$positivos = $value;

			}elseif ($name['3'] == "negativos") {
				# code...
				$negativos = $value;
			}elseif ($name['3'] == "recomendaciones") {
				# code...
				$bandera = 1;
				$recomendaciones = $value;
			}
			if ((strlen($positivos)+strlen($negativos)+strlen($recomendaciones))>5 && $bandera > 0) {
				# code...
				$tabla = "comentarios";
				$camposAinsertar = "evaluador,evaluado,positivo,negativo,recomendaciones";
				$valores = "'".$name['0']."','".$name['2']."','".$positivos."','".$negativos."','".$recomendaciones."'";
				$conexion->getInsert($tabla,$camposAinsertar,$valores);
				$bandera = 0;
			}
			
		}

		
	}
	
	}

	// MARCAR AL USUARIO COMO EVALUADO
	$tabla = $_COOKIE['TABLE'];
	$camposAmodificar = "evaluado=1";
	$where = 'codigo = \''.$_COOKIE['CODIGO']."'";
	$conexion->getUpdate($tabla,$camposAmodificar,$where);


	msgOk("Evaluacion Satisfactoria","Los datos se han almacenado exitosamente","Gracias por su tiempo");
}else
{
	echo "<script> location.href='index.php'</script>";
}




?>