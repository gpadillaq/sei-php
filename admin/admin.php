<?php
session_start();

 require_once("clases/conexion.clase.php");
 require_once("clases/fungeneral.fn.php");
//require_once("clases/scripts.php");

// Verificar si el usuario es correcto
$conexion = new conexion;
if (isset($_REQUEST['entrar'])) {

    $usuario = $_REQUEST['user'];
    $pw = $_REQUEST['pw'];

    $tabla = "webmasters";
    $camposAselect = "id";
    $where = "user = '$usuario' AND pwd = '$pw'";
    $Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
    $Nelementos = ExisteCodigo(mysql_num_rows($Myconsulta));
    if ($Nelementos>0) {
        $_SESSION['USER'] = "SXSIF;ASIDSL";

    }else{
        session_destroy();
        echo "<script> location.href = 'index.php'; </script>";
    }

}elseif (!isset($_SESSION['USER']) || isset($_REQUEST['cerrar-sesion'])) {
    session_destroy();
    echo "<script> location.href = 'index.php'; </script>";
}

if (isset($_REQUEST['editor'])) {
        $codigo = $_REQUEST['editor'];
        $codigo = htmlentities($codigo);
        $codigo = ereg_replace("[\n|\r|\n\r]", "", $codigo);
        $id = $_REQUEST['idStructura'];

        $tabla = "estructuras";
        $camposAselect = "id";
        $where = "id = $id";
        $Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
        $Nelementos = mysql_num_rows($Myconsulta);
        if ($Nelementos>0) {
            // actualizar codigo
            $camposAmodificar= "codigoEstructura='$codigo'";
            $where = "id = $id";
            $conexion->getUpdate($tabla,$camposAmodificar,$where);
        }
}





// Variables de datos de las tablas
$resultadosDocentes = array();
$resultadosAdministradores = array();
$resultadosAlumnos = array();
$docentesClase = array();

function funcion_herramientas()
{ ?>
    <div class="row-fluid">
        <div class="btn-generales span4 input-prepend">
            <!-- botones para tratar a las tablas -->
            <a class="btn tools-general tools" title="Abrir Tabla" id="open-table" ><li class="icon-list-alt"><li class="icon-folder-open"></li></li></a>
            <a class="btn tools-general tools" title="Limpiar datos" id="clean-table" ><li class="icon-list-alt"><li class="icon-remove"></li></li></a>
            <form action="admin.php" method="post">
                 <input type="submit" class="btn tools-general tools" title="Cerrar Sesion" name="cerrar-sesion" value="Salir">
            </form>

        </div>
        <div class="span1"></div>

        <div class="btn-specific span4 input-prepend">
            <a class="btn tools-specific tools" title="Agregar elemento" id="add-element" ><li class="icon-plus"></li></a>
            <a class="btn tools-specific tools" title="Remover elemento" id="remove-element" ><li class="icon-remove"></li></a>
            <a class="btn tools-specific tools" title="Editar elemento" id="edit-element" ><li class="icon-edit"></li></a>
            <a class="btn tools-specific tools" title="Actualizar" id="refresh-tabs" ><li class="icon-refresh"></li></a>
			<a class="btn tools-specific tools" title="Inportar Datos" id="import-table"><li class="icon-book"></li></a>
        </div>
        <div class="span1"></div>
        <div class="btn-resultados span2 input-prepend">
            <a class="btn tools-results tools" title="Ver Resultados" id="open-results" ><li class="icon-eye-open"><li class="icon-list-alt"></li></li></a>
            <a class="btn tools-results tools" title="Exportar Resultados" id="export-results" ><img src="../image/export_to_excel.gif"></a>
        </div>


    </div>




    <?php

}

function buscar_tabla($name)
{
    switch ($name) {
        case 'resultados':
            resultadosall();
            break;
        case 'usuarios':
            mostrarUsuarios();
            break;
        case 'Alumnos':
            mostrarAlumnos();
            break;
        case 'Administradores':
            mostrarAdministradores();
            break;
        case 'Docentes':
            mostrarDocentes();
            break;
        case 'Preguntas':
            mostrarPreguntas();
            break;
        case 'docente_clase':
            mostrarDocentesClase();
            break;
        case 'estructuras':
            mostrarEstructuras();
            break;
    }

}

function mostrarEstructuras()
{
    // abri una nueva conexion
    $conexion = new conexion();
    $datos = array();
    $tabla = "estructuras";
    $camposAselect = "*";
    $where = 1;
    $Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);

    for ($i=0; $i < mysql_num_rows($Myconsulta) ; $i++) {
        $var = mysql_fetch_array($Myconsulta);
        foreach ($var as $key => $value) {

            if (!is_array($datos[$value]) && $key === 'id') {
                $datos[$value] = array();
                $temp = &$datos[$value];
            }
            if (strlen($key) > 1 && $key !== 'id') {

                $temp[$key] = $value;
            }

        }
    }

    Abrir($datos,'estructuras',2,'Estructuras');
}

function mostrarUsuarios()
{
    // abri una nueva conexion
    $conexion = new conexion();
    $datos = array();
    $tabla = "usuarios";
    $camposAselect = "*";
    $where = 1;
    $Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);

    for ($i=0; $i < mysql_num_rows($Myconsulta) ; $i++) {
        $var = mysql_fetch_array($Myconsulta);
        foreach ($var as $key => $value) {
            if ($key === 'id') {
                $datos[$value] = array();
                $temp = &$datos[$value];
            }
            if (strlen($key) > 1 && $key !== 'id') {

                $temp[$key] = $value;
            }

        }
    }

    Abrir($datos,'usuarios',2,'Usuarios');
}

function mostrarAlumnos()
{
    // abri una nueva conexion
    $conexion = new conexion();
    $datos = array();
    $tabla = "Alumnos";
    $camposAselect = "*";
    $where = 1;
    $maxNumClass = 0;
    $Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);

    for ($i=0; $i < mysql_num_rows($Myconsulta); $i++) {
        $datos[$i] = array();
        $temp = &$datos[$i];
        $var = mysql_fetch_array($Myconsulta);
        $temp['id']  = $var['id'];
        $temp['codigo'] = $var['codigo'];
        $temp['evaluado'] = $var['evaluado'];
        $Myconsulta2 = $conexion->getSelect('alumno_clase','id_docente_clase',"id_alumno = ".$var['id']);
        $Nelementos = mysql_num_rows($Myconsulta2);
        if ($Nelementos > $maxNumClass) {
            $maxNumClass = $Nelementos;
        }

        for ($j=0; $j < $Nelementos ; $j++) {
            $var2 = mysql_fetch_array($Myconsulta2);

            $Myconsulta3 = $conexion->getSelect('docente_clase','codigo_clase',"id = ".$var2['id_docente_clase']);
            if (mysql_num_rows($Myconsulta3) > 0) {
                $var3 = mysql_fetch_array($Myconsulta3);

                $idClase = $var2['id_docente_clase'];
                $codigoClase = $var3['codigo_clase'];
                $DatosAsignaturas = &$_SESSION['Asignaturas'];
                $temp[$idClase] = $DatosAsignaturas[$codigoClase];

            }


        }

    }
    Abrir($datos,'Alumnos',$maxNumClass,'Alumnos');
}

function mostrarAdministradores()
{
    // abri una nueva conexion
    $conexion = new conexion();
    $datos = array();
    $tabla = "Administradores";
    $camposAselect = "*";
    $where = 1;
    $Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);

    for ($i=0; $i < mysql_num_rows($Myconsulta) ; $i++) {
        $var = mysql_fetch_array($Myconsulta);
        foreach ($var as $key => $value) {

            if (!is_array($datos[$value]) && $key === 'id') {
                $datos[$value] = array();
                $temp = &$datos[$value];
            }
            if (strlen($key) > 1 && $key !== 'id') {
                $temp[$key] = $value;
            }

        }
    }

    Abrir($datos,'Administradores',2,'Administradores');
}

function mostrarDocentes()
{
    // abri una nueva conexion
    $conexion = new conexion();
    $tabla = "Docentes";
    $camposAselect = "*";
    $where = 1;
    $Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);

    for ($i=0; $i < mysql_num_rows($Myconsulta) ; $i++) {
        $var = mysql_fetch_array($Myconsulta);
        foreach ($var as $key => $value) {

            if ($key === 'id') {
                $datos[$value] = array();
                $temp = &$datos[$value];
            }
            if (strlen($key) > 1 && $key !== 'id') {
                $temp[$key] = $value;
            }

        }
    }
    Abrir($datos,'Docentes',2,'Docentes');
}

function mostrarPreguntas()
{
    // abri una nueva conexion
    $conexion = new conexion();
    $datos = array();
    $tabla = "Preguntas";
    $camposAselect = "*";
    $where = 1;
    $Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);

    for ($i=0; $i < mysql_num_rows($Myconsulta); $i++) {
        $var = mysql_fetch_array($Myconsulta);
        $id = $var['id'];
        $datos[$id] = array();
        $temp = &$datos[$id];
        $temp['evaluador'] = $var['evaluador'];
        $temp['evaluado'] = $var['evaluado'];

        $tabla = "usuario_pregunta";
        $camposAselect = "listaPreguntas_id";
        $where = "Preguntas_id = ".$var['id'];

        $Myconsulta2 = $conexion->getSelect($tabla,$camposAselect,$where);

        for ($j=0; $j < mysql_num_rows($Myconsulta2) ; $j++) {
            $var2 = mysql_fetch_array($Myconsulta2);
            $tabla = "listaPreguntas";
            $camposAselect = "pregunta";
            $where = "id = ".$var2['listaPreguntas_id'];

            $Myconsulta3 = $conexion->getSelect($tabla,$camposAselect,$where);
            $var3 = mysql_fetch_array($Myconsulta3);
            $key = $var2['listaPreguntas_id'];
            $value = $var3['pregunta'];
            $temp[$key] = $value;

        }

    }
    Abrir($datos,'Preguntas',2,'Preguntas');
}

function mostrarComentarios()
{
    // abri una nueva conexion
    $conexion = new conexion();
    $DatosAdministradores = &$_SESSION['Administradores'];
    $DatosDocentes = &$_SESSION['Docentes'];
    $DatosAsignaturas = &$_SESSION['Asignaturas'];
    // VER LOS RESULTADOS DE LA EVALUACION
    $tabla = "comentarios";
    $camposAselect = "*";
    $where = 1;
    $Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);

    // Verificar que si hay datos en la tabla resultados
    if (mysql_num_rows($Myconsulta) > 0) {

        // guardar los datos
        for ($i=0; $i < mysql_num_rows($Myconsulta); $i++) {
            $datos = mysql_fetch_array($Myconsulta);
            switch ($datos['evaluador']) {
                case 'Alumnos':
                    # code...
                if (strlen($datos['evaluado']) < 8){
                    $tabla = "docente_clase";
                    $camposAselect = "*";
                    $where = "id =".$datos['evaluado'];
                    $Myconsulta2 = $conexion->getSelect($tabla,$camposAselect,$where);

                    if (!is_array($docentesClase[$datos['evaluado']] )) {
                        $docentesClase[$datos['evaluado']] = array();
                    }
                    $datosTemp = mysql_fetch_array($Myconsulta2);
                    $datosTemp['codigo_docente'] = $DatosDocentes[$datosTemp['codigo_docente']];
                    $datosTemp['codigo_clase'] = $DatosAsignaturas[$datosTemp['codigo_clase']];
                    $docentesClase[$datos['evaluado']] = $datosTemp;

                    if (!is_array($resultadosAlumnos[$i])) {
                        $resultadosAlumnos[$i] = array();
                    }
                    $temp = &$resultadosAlumnos[$i];
                    break;
                }
                default:
                    # code...
                if (!is_array($resultadosOtros[$i])) {
                   $resultadosOtros[$i] = array();
                }
                $temp = &$resultadosOtros[$i];

            }
            foreach ($datos as $key => $value) {

                if (strlen($key) > 1) {
                    if ($key == "evaluado" && strlen($value)>2) {
                        $name = $DatosDocentes[$value];
                        if ($name == "") {
                            $name = $DatosAdministradores[$value];
                        }
                    // echo "console.log(\"".$name."\");";
                        $value = $name;
                    }
					$buscar=array(chr(13).chr(10), "\r\n", "\n", "\r");
					$reemplazar=array("", "", "", "");
					$temp[$key]=str_ireplace($buscar,$reemplazar,$value);
                }
            }
        }
    }

    Abrir($resultadosAlumnos,'resultados',$docentesClase,'ComentariosAlumnos');
    Abrir($resultadosOtros,'resultados',-1,'ComentariosOtros');
}

function mostrarDocentesClase()
{
    // abri una nueva conexion
    $conexion = new conexion();
    $DatosDocentes = &$_SESSION['Docentes'];
    $DatosAsignaturas = &$_SESSION['Asignaturas'];
    $tabla = "docente_clase";
    $camposAselect = "*";
    $where = 1;
    $Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);

    for ($i=0; $i < mysql_num_rows($Myconsulta) ; $i++) {
        $var = mysql_fetch_array($Myconsulta);
        foreach ($var as $key => $value) {

            if ($key === 'id') {
                $datos[$value] = array();
                $temp = &$datos[$value];
            }
            if (strlen($key) > 1 && $key !== 'id') {
                if ($key == "codigo_docente") {
                    $name = $DatosDocentes[$value];
                    $value = $name;
                }elseif ($key == "codigo_clase") {
                    $name = $DatosAsignaturas[$value];
                    $value = $name;
                }
                $temp[$key] = $value;
            }

        }
    }
    Abrir($datos,'docente_clase',2,'DocentesClase');
}

function ModificarRalumnos()
{
   // abri una nueva conexion
    $conexion = new conexion();
    $tablaOrigen = "`resultados_alumnos_temp`";
    $tablaDestino = "resultados_alumnos";
    $camposAselect = "*";
    $where = 1;
    $MyconsultaOrigen = $conexion->getSelect($tablaOrigen,$camposAselect,$where);
    $NelementosOrigen = mysql_num_rows($MyconsultaOrigen);

    //Verificar si hay datos
    if ($NelementosOrigen>0) {
        for ($i=0; $i <$NelementosOrigen; $i++) {
            $DatosOrigen = mysql_fetch_array($MyconsultaOrigen);
            //Verificar si la Celda no ha sido actualizada
            if ($DatosOrigen['Actualizado'] == 1) {
                continue;
            }
            $camposAselect = "id,resultados,nevaluadores";
            $where = "evaluador = '".$DatosOrigen['evaluador']."' AND evaluado = '".$DatosOrigen['evaluado']."' AND asignatura = '".$DatosOrigen['asignatura']."' AND ano = ".$DatosOrigen['ano']." AND periodo = '".$DatosOrigen['periodo']."' AND pregunta = '".$DatosOrigen['pregunta']."'";
            $MyconsultaDestino = $conexion->getSelect($tablaDestino,$camposAselect,$where);
            $Nelementos = mysql_num_rows($MyconsultaDestino);

            if ($Nelementos>0) {
                $DatosDestino = mysql_fetch_array($MyconsultaDestino);
                //ACtualizamos
                $camposAmodificar = "resultados=".($DatosOrigen['resultados'] + $DatosDestino['resultados']).",nevaluadores = ".(1 + $DatosDestino['nevaluadores']);
                $where = 'id = '.$DatosDestino['id'];
                $conexion->getUpdate($tablaDestino,$camposAmodificar,$where);
                //marcar como actualizado la celda actual de la tabla origen
                $conexion->getUpdate($tablaOrigen,"Actualizado = 1","id = ".$DatosOrigen['id']);
            }else{
                //Insertamos
                //Insertar los datos a la base de datos
                $camposAinsertar = "evaluador,evaluado,asignatura,ano,grupo,periodo,pregunta,resultados,nevaluadores";
                $valores = "'".$DatosOrigen['evaluador']."','".$DatosOrigen['evaluado']."','".$DatosOrigen['asignatura']."','".$DatosOrigen['ano']."','".$DatosOrigen['grupo']."','".$DatosOrigen['periodo']."','".$DatosOrigen['pregunta']."',".$DatosOrigen['resultados'].",1";
                $conexion->getInsert($tablaDestino,$camposAinsertar,$valores);
                //marcar como actualizado la celda actual de la tabla origen
                $conexion->getUpdate($tablaOrigen,"Actualizado = 1","id = ".$DatosOrigen['id']);
            }
        }
    }

    ModificarPromedio($tablaDestino);

}

function ModificarRotros()
{
   // abri una nueva conexion
    $conexion = new conexion();
    $tablaOrigen = "`resultados_otros_temp`";
    $tablaDestino = "resultados_otros";
    $camposAselect = "*";
    $where = 1;
    $MyconsultaOrigen = $conexion->getSelect($tablaOrigen,$camposAselect,$where);
    $NelementosOrigen = mysql_num_rows($MyconsultaOrigen);

    //Verificar si hay datos
    if ($NelementosOrigen>0) {
        for ($i=0; $i <$NelementosOrigen; $i++) {
            $DatosOrigen = mysql_fetch_array($MyconsultaOrigen);
            //Verificar si la Celda no ha sido actualizada
            if ($DatosOrigen['Actualizado'] == 1) {
                continue;
            }
            $camposAselect = "id,resultados,nevaluadores";
            $where = "evaluador = '".$DatosOrigen['evaluador']."' AND evaluado = '".$DatosOrigen['evaluado']."' AND pregunta = '".$DatosOrigen['pregunta']."'";
            $MyconsultaDestino = $conexion->getSelect($tablaDestino,$camposAselect,$where);
            $Nelementos = mysql_num_rows($MyconsultaDestino);

            if ($Nelementos>0) {
                $DatosDestino = mysql_fetch_array($MyconsultaDestino);
                //ACtualizamos
                $camposAmodificar = "resultados=".($DatosOrigen['resultados'] + $DatosDestino['resultados']).",nevaluadores = ".(1 + $DatosDestino['nevaluadores']);
                $where = 'id = '.$DatosDestino['id'];
                $conexion->getUpdate($tablaDestino,$camposAmodificar,$where);
                //marcar como actualizado la celda actual de la tabla origen
                $conexion->getUpdate($tablaOrigen,"Actualizado = 1","id = ".$DatosOrigen['id']);
            }else{
                //Insertamos
                //Insertar los datos a la base de datos
                $camposAinsertar = "evaluador,evaluado,pregunta,resultados,nevaluadores";
                $valores = "'".$DatosOrigen['evaluador']."','".$DatosOrigen['evaluado']."','".$DatosOrigen['pregunta']."',".$DatosOrigen['resultados'].",1";
                $conexion->getInsert($tablaDestino,$camposAinsertar,$valores);
                //marcar como actualizado la celda actual de la tabla origen
                $conexion->getUpdate($tablaOrigen,"Actualizado = 1","id = ".$DatosOrigen['id']);
            }
        }
    }
    ModificarPromedio($tablaDestino);

}
function ModificarPromedio($NombreTabla)
{
   // abri una nueva conexion
    $conexion = new conexion();
    // Modificar el promedio de los resultados.
    $tabla = $NombreTabla;
    $camposAselect = "id,resultados,nevaluadores";
    $where = 1;

    $Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
    for ($i=0; $i <mysql_num_rows($Myconsulta) ; $i++) {
        $datos = mysql_fetch_array($Myconsulta);
        $promedio = $datos['resultados']/$datos['nevaluadores'];

        // Asignar promedio.
        $camposAmodificar= "promedio=$promedio";
        $where = "id = $datos[id]";
        $conexion->getUpdate($tabla,$camposAmodificar,$where);
    }

}
function resultadosall()
{

   // abri una nueva conexion
    $conexion = new conexion();
    // Actualizar los datos de la evaluacion.
    ModificarRalumnos();
    ModificarRotros();

    // VER LOS RESULTADOS DE LA EVALUACION
    $tabla = "resultados_alumnos";
    $camposAselect = "*";
    $where = 1;
    $Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
    // Verificar que si hay datos en la tabla resultados
    if (mysql_num_rows($Myconsulta) > 0) {

        // guardar los datos
        for ($i=0; $i < mysql_num_rows($Myconsulta); $i++) {
            $datos = mysql_fetch_array($Myconsulta);
            foreach ($datos as $key => $value) {
                if (is_string($key)) {
                    $temp1[$key] = $value;
                }
            }
            $resultadosAlumnos[$i] = $temp1;
        }
    }


    $tabla = "resultados_otros";
    $camposAselect = "*";
    $where = 1;
    $Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
    // Verificar que si hay datos en la tabla resultados
    if (mysql_num_rows($Myconsulta) > 0) {

        // guardar los datos
        for ($i=0; $i < mysql_num_rows($Myconsulta); $i++) {
            $datos = mysql_fetch_array($Myconsulta);
            foreach ($datos as $key => $value) {
                if (is_string($key)) {
                    $temp2[$key] = $value;
                }
            }
            $resultadosOtros[$i] = $temp2;
        }
    }

    Abrir($resultadosAlumnos,'resultados',2,'ResulAlumnos');
    Abrir($resultadosOtros,'resultados',2,'ResulOtros');
    mostrarComentarios();

}

require_once("../plantillas/Padministrador.php");
?>