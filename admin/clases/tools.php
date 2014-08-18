<!-- <script src="js/jquery-1.9.1.js"></script> -->

<?php 
require_once("clases/conexion.clase.php");
function formOpen()
{
		echo "\"<p class='validateTips'>Todos los Campos son requeridos.</p>";
		echo "<form>";
			echo "<fieldset>";
				echo "<label for='name'>Name</label>";
				echo "<select name='tablas' id='select-table' class='ui-widget-content ui-corner-all'>";
					echo "<option value=usuarios >Usuarios</option>";
					echo "<option value=Alumnos>Alumnos</option>";
					echo "<option value=Docentes>Docentes</option>";
					echo "<option value=docente_clase>Docentes - Clases</option>";
					echo "<option value=Administradores>Administradores</option>";
					echo "<option value=Preguntas>Preguntas</option>";
					echo "<option value=estructuras>Estructuras</option>";
				echo "</select>";
			echo "</fieldset>";
		echo "</form>\"";

}

function formAddStruct()
{
	echo "\"<p class='validateTips'>Todos los Campos son requeridos.</p>";
		echo "<form>";
			echo "<fieldset>";
				echo "<select name='tablas' id='user' class='ui-widget-content ui-corner-all'>";
					echo "<option value=Alumnos>Alumnos</option>";
					echo "<option value=Docentes>Docentes</option>";
					echo "<option value=Administradores>Administradores</option>";
				echo "</select>";
			echo "</fieldset>";
		echo "</form>\"";
}
function formAddUser()
{
		echo "\"<p class='validateTips'>Todos los Campos son requeridos.</p>";
		echo "<form>";
			echo "<fieldset>";
				echo "<label for='name'>Codigo</label>";
				echo "<input type='text' id='codigo' placeholder='Ingresa el codigo' required><br>";
				echo "<label for='name'>Tipo de Usuario</label>";
				echo "<select id='usuario' id='select-evaluador' class='text ui-widget-content ui-corner-all' required>";					
					echo "<option value=Alumnos>Alumnos</option>";
					echo "<option value=Docentes>Docentes</option>";					
					echo "<option value=Administradores>Administradores</option>";					
				echo "</select><br>";
				echo "<label >Evaluado</label>";
				echo "<select id='evaluado' id='select-evaluador' class='text ui-widget-content ui-corner-all' required>";					
					echo "<option value=Alumnos>Alumnos</option>";
					echo "<option value=Docentes>Docentes</option>";					
					echo "<option value=Administradores>Administradores</option>";					
					echo "<option value=autoDocentes>autoDocentes</option>";					
					echo "<option value=autoAdministradores>autoAdministradores</option>";					
				echo "</select><br>";				
				echo "<label for='name'>N Preguntas</label>";
				echo "<input id='npreguntas' type='text' class='text ui-widget-content ui-corner-all' required>";
			echo "</fieldset>";
		echo "</form>\"";

}
?>

<script>

	// Crea una nueva fila en la ventana de agregar alumnos

	function addAlumnoElemento() {
		var idold =  $('.ultimo').attr('id');
		var idnew = Number(idold) + 1;
		var nclases = document.getElementById('nclases');
		$('#'+idold).removeClass('ultimo');					
		$('#' + (idold)).before(<?php echo "\"";formAddAlumnoClase(); echo"\""; ?>);
		$('.ultimo .delete').attr('id',idnew );	
		$('.ultimo .asignatura').attr('id',"asignatura-"+idnew );	
		$('.ultimo .docente').attr('id',"docente-"+idnew );	
		$('.ultimo').attr('id',idnew);
		nclases.value = Number(nclases.value) + 1;
		<?php completar($_SESSION['arreglo_Asignatura'],"\"materia\""); ?>;
	}

	function dellAlumnoElemento(id) {
		$('#'+id + ' .asignatura').val("");
		$('#'+id + ' .docente').val("");
		$('#'+id).slideUp();	
	}
	function bucardocente (id) {
		var id = id.split("-");
		id = id[1];
		var clase = $('#'+id+" .asignatura").val();
		var asignaturas = new Array();
		var codigos = new Array();
		<?php
		$asignaturas = &$_SESSION['arreglo_Asignatura'];
		$codigos = &$_SESSION['arreglo_Asignatura_codigo'];
		for($i = 0;$i < count($asignaturas); $i++){ //usamos count para saber cuantos elementos hay ?>
			
       			asignaturas.push('<?php echo $asignaturas[$i]; ?>');
			
     	<?php } 
     	 for($i = 0;$i < count($codigos); $i++){ //usamos count para saber cuantos elementos hay ?>
			
       			codigos.push('<?php echo $codigos[$i]; ?>');
			
     	<?php } ?>
     	// Buscarndo la Clase
     	var codigo = "-1";
     	for (var i = 0; i < asignaturas.length; i++) {
     		if(asignaturas[i] == clase)
     		{
     			codigo = i;
     			break;
     		}
     	};
     	$('#'+id+" .docente").html("<option value='none'>No hay docentes</option>");
     	if (!(codigo == "-1")) 
     	{     		
     		var cDocentes = new Array();
     		var Ids = new Array();
	     	<?php 
	     	// buscar el codigo del docente de esa asignatura
	     	$conexion = new conexion();
	     	$tabla = "docente_clase";
	     	$camposAselect = "id,codigo_docente,codigo_clase";
	     	$where = 1;
	     	$Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
	     	for ($i=0; $i < mysql_num_rows($Myconsulta); $i++) { 
	     		$datos = mysql_fetch_array($Myconsulta);
	     		$temp = $datos['codigo_clase'];
	     		$id = $datos['id'];
	     		$Cdocente = $datos['codigo_docente'];?>
	     		if (codigos[codigo] == <?php echo "\"$temp\""; ?>)
	     		{
	     			cDocentes.push('<?php echo $Cdocente; ?>');
	     			Ids.push('<?php echo $id ?>');
	     		};
	     		
	     	<?php }
			$conexion = new conexion();
	     	$tabla = "Docentes";
	     	$camposAselect = "codigo,nombre";
	     	$where = 1;
	     	$Myconsulta = $conexion->getSelect($tabla,$camposAselect,$where);
	     	for ($i=0; $i < mysql_num_rows($Myconsulta); $i++) { 
	     		$datos = mysql_fetch_array($Myconsulta);
	     		$temp = $datos['codigo'];
	     		$nombre = "'".$datos['nombre']."'";?>
	     		for (var i = 0; i < cDocentes.length; i++) {
	     			if (cDocentes[i] == <?php echo "\"$temp\""; ?>) {
	     				$('#'+id+" .docente").append("<option value='"+ Ids[i]+ "''>" + <?php echo "$nombre"; ?> + '  </option>');
	     			}; 
	     		};
	     			     		
	     	<?php }

	     	?>
	     };
	}
</script>
<?php
function formAddAlumno()
{
		echo "\"<p class='validateTips'>Todos los Campos son requeridos.</p>";
		echo "<form>";
			echo "<fieldset>";
				echo "<table class='table table-bordered' id='add-alumno'>";
					echo "<tr>";
						echo "<th>Nombre Asignatura</th>";						
						echo "<th>Nombre Docente</th>";
					echo "</tr>";
				formAddAlumnoClase();
				echo "</table>";				
				echo "<h1> Codigo de Estudiantes</h1>";
				echo "<div class=codigos>";
					funAddCodigo();					
				echo "</div>";
				echo "<input type=hidden name=nclases id=nclases value=1>" ;
				echo "<input type=hidden name=nalumnos id=nalumnos value=1>" ;
			echo "</fieldset>";
		echo "</form>\"";

}

function formAddAlumnoClase()
{
	$id = 1;
	echo "<tr class='ultimo' id=$id>";
		echo "<td>";
	echo "<input type='text' id=asignatura-$id class='text ui-widget-content ui-corner-all materia asignatura' )>";
		echo "</td>";
		echo "<td>";
	echo "<select id=docente-$id class='text ui-widget-content ui-corner-all docente' onfocus=bucardocente($(this).attr('id'))>";					
		echo "<option value='none'>No hay docentes</option>";					
	echo "</select>";
		echo "</td>";
		echo "<td>";
	echo "<div class=add-button>";
	echo "<a class='btn tools' title='Agregar elemento' id=add-element onclick=addAlumnoElemento()><li class=icon-plus></li></a>";
	echo "<a class='btn tools delete' id=$id title='Eliminar elemento' onclick=dellAlumnoElemento($(this).attr('id'))><li class=icon-remove></li></a>";
	echo "</div>";
		echo "</td>";
	echo "</tr>";
}?>
<script>
	function addCodigoElemento (id) {
		var idold =  $('.codigoUltimo').attr('id');
		var idnew = Number(idold) + 1;
		var nalumnos = document.getElementById('nalumnos');
		$('.codigos #'+idold).removeClass('codigoUltimo');
		$('.codigos #' + (idold)).before(<?php echo "\"";funAddCodigo(); echo"\""; ?>);
		$('.codigoUltimo input').attr('id',"codigo-"+idnew );
		$('.codigoUltimo .delete').attr('id',idnew );	
		$('.codigoUltimo').attr('id',idnew);
		nalumnos.value = Number(nalumnos.value) + 1;
	}
	
	function dellCodigoElemento (id) {
		$('.codigos #'+id).val("none");
		alert($('.codigos #'+id).val());
		$('.codigos #'+id).slideUp();
	}

// ADD Docentes
     function addDocenteElemento() {
		var idold =  $('.Docente-ultimo').attr('id');
		var idnew = Number(idold) + 1;
		var ndocentes = document.getElementById('ndocentes');
		$('#'+idold).removeClass('Docente-ultimo');					
		$('#' + (idold)).before(<?php echo "\"";formAddADocente(); echo"\""; ?>);
		$('.Docente-ultimo .delete').attr('id',idnew );	
		$('.Docente-ultimo #Nombre-Docente-'+1).attr('id',"Nombre-Docente-"+idnew );		
		$('.Docente-ultimo #Codigo-Docente-'+1).attr('id',"Codigo-Docente-"+idnew );		
		$('.Docente-ultimo').attr('id',idnew);
		ndocentes.value = Number(ndocentes.value) + 1;
	}

	function dellDocenteElemento(id) {
		$('#Nombre-Docente-'+id).val("none");
		$('#Codigo-Docente-'+id).val("none");
		$('#'+id).slideUp();	
	}

	// ADD docente Asignatura..
     function addDocenteAsignaturaElemento() {
		var idold =  $('.Docente-ultimo').attr('id');
		var idnew = Number(idold) + 1;
		var ndocentes = document.getElementById('ndocentes');
		$('#'+idold).removeClass('Docente-ultimo');					
		$('#' + (idold)).before(<?php echo "\"";funAddDocenteClase(); echo"\""; ?>);
		$('.Docente-ultimo .delete').attr('id',idnew );	
		$('.Docente-ultimo #Nombre-Docente-'+1).attr('id',"Nombre-Docente-"+idnew );		
		$('.Docente-ultimo #Nombre-Asignatura-'+1).attr('id',"Nombre-Asignatura-"+idnew );		
		$('.Docente-ultimo').attr('id',idnew);
		ndocentes.value = Number(ndocentes.value) + 1;
		<?php completar($_SESSION['arreglo_Asignatura'],"\"materia\""); ?>;
		<?php completar($_SESSION['arreglo_Docentes'],"\"docente\""); ?>;
	}

	function dellDocenteAsignaturaElemento(id) {
		$('#Nombre-Docente-'+id).val("none");
		$('#Codigo-Docente-'+id).val("none");
		$('#'+id).slideUp();	
	}

	// ADD Administrador
     function addAdministradorElemento() {
		var idold =  $('.Administrador-ultimo').attr('id');
		var idnew = Number(idold) + 1;
		var nadministradores = document.getElementById('nadministradores');
		$('#'+idold).removeClass('Administrador-ultimo');					
		$('#' + (idold)).before(<?php echo "\"";funAddAdministrador(); echo"\""; ?>);
		$('.Administrador-ultimo .delete').attr('id',idnew );	
		$('.Administrador-ultimo #Nombre-Administrador-'+1).attr('id',"Nombre-Administrador-"+idnew );		
		$('.Administrador-ultimo #Codigo-Administrador-'+1).attr('id',"Codigo-Administrador-"+idnew );	
		$('.Administrador-ultimo #docencia-'+1).attr('id',"docencia-"+idnew );	
		$('.Administrador-ultimo').attr('id',idnew);
		nadministradores.value = Number(nadministradores.value) + 1;
	}

	function dellAdministradorElemento(id) {
		$('#Nombre-Administrador-'+id).val("none");
		$('#Codigo-Administrador-'+id).val("none");
		$('#'+id).slideUp();	
	}
	// ADD Preguntas
     function addPreguntaElemento() {
		var idold =  $('.Pregunta-ultimo').attr('id');
		var idnew = Number(idold) + 1;
		var npreguntas = document.getElementById('npreguntas');
		$('#'+idold).removeClass('Pregunta-ultimo');					
		$('#' + (idold)).before(<?php echo "\"";funAddPreguntas(); echo"\""; ?>);
		$('.Pregunta-ultimo .delete').attr('id',idnew );	
		$('.Pregunta-ultimo #Pregunta-'+1).attr('id',"Pregunta-"+idnew );		
		$('.Pregunta-ultimo').attr('id',idnew);
		npreguntas.value = Number(npreguntas.value) + 1;
	}

	function dellPreguntaElemento(id) {
		$('#Pregunta-'+id).val("none");
		$('#'+id).slideUp();
	}
</script>

<?php
function funAddCodigo()
{
	$id = 1;	
	echo "<div id=$id class='codigoUltimo'>";
		echo "<input type='text' id=codigo-$id class=input-mediano placeholder='ej:10-ice-0158' size=15 maxlength=12>";
		echo "<a class='btn tools booton' title='Agregar elemento' id=add-element onclick=addCodigoElemento()><li class=icon-plus></li></a>";
		//echo "<a class='btn tools delete booton' id=$id title='Eliminar elemento' onclick=dellCodigoElemento($(this).attr('id'))><li class=icon-remove></li></a>";
		
	echo "</div>";
}


function formAddDocente()
{
	echo "\"<p class='validateTips'>Todos los Campos son requeridos.</p>";
		echo "<form>";
			echo "<fieldset>";
				echo "<table class='table table-bordered' id='add-alumno'>";
					echo "<tr>";
						echo "<th>Nombre Docente</th>";		
						echo "<th>Codigo Docente</th>";
					echo "</tr>";	
					formAddADocente();
				echo "</table>";
				echo "<input type=hidden name=ndocentes id=ndocentes value=1>" ;
			echo "</fieldset>";
		echo "</form>\"";
}

function formAddADocente()
{
	$id = 1;
	echo "<tr class='Docente-ultimo' id=$id>";
		echo "<td>";
	echo "<input type='text' id=Nombre-Docente-$id class='text ui-widget-content ui-corner-all' )>";
		echo "</td>";
		echo "<td>";
	echo "<input type='text' id=Codigo-Docente-$id class='text ui-widget-content ui-corner-all' )>";	
		echo "</td>";
		echo "<td>";
	echo "<div class=add-button>";
	echo "<a class='btn tools' title='Agregar elemento' id=add-element onclick=addDocenteElemento()><li class=icon-plus></li></a>";
	echo "<a class='btn tools delete' id=$id title='Eliminar elemento' onclick=dellDocenteElemento($(this).attr('id'))><li class=icon-remove></li></a>";
	echo "</div>";
		echo "</td>";
	echo "</tr>";
}
function formDel()
{
		echo "\"<form>";
			echo "<fieldset>";
				echo "<label for='name'>Esta seguro que quiere Eliminar ese Elemento?</label>";				
			echo "</fieldset>";
		echo "</form>\"";

}


// Agregar asignatura con docente correspondiente
function formAddDocenteClase()
{
	echo "\"<p class='validateTips'>Todos los Campos son requeridos.</p>";
		echo "<form>";
			echo "<fieldset>";
				echo "Año: <input type='text' name=ano id=ano class=input-mediano>  ";
				echo "Grupo: <input type='number' name=grupo id=grupo class=input-mediano maxlength=1>  ";
				echo "Periodo: <input type='text' name=periodo id=periodo  >  ";
				echo "<table class='table table-bordered' id='add-alumno'>";
					echo "<tr>";
						echo "<th>Nombre Asignatura</th>";		
						echo "<th>Nombre Docente</th>";
					echo "</tr>";	
					funAddDocenteClase();
				echo "</table>";
				echo "<input type=hidden name=ndocentes id=ndocentes value=1>" ;
			echo "</fieldset>";
		echo "</form>\"";
}

function funAddDocenteClase()
{
	$id = 1;
	echo "<tr class='Docente-ultimo' id=$id>";
		echo "<td>";
	echo "<input type='text' id=Nombre-Asignatura-$id class='text ui-widget-content ui-corner-all materia' )>";
		echo "</td>";
		echo "<td>";
	echo "<input type='text' id=Nombre-Docente-$id class='text ui-widget-content ui-corner-all docente' )>";	
		echo "</td>";
		echo "<td>";
	echo "<div class=add-button>";
	echo "<a class='btn tools' title='Agregar elemento' id=add-element onclick=addDocenteAsignaturaElemento()><li class=icon-plus></li></a>";
	echo "<a class='btn tools delete' id=$id title='Eliminar elemento' onclick=dellDocenteAsignaturaElemento($(this).attr('id'))><li class=icon-remove></li></a>";
	echo "</div>";
		echo "</td>";
	echo "</tr>";
}


function formAddAdministrador()
{
	echo "\"<p class='validateTips'>Todos los Campos son requeridos.</p>";
		echo "<form>";
			echo "<fieldset>";
				echo "<table class='table table-bordered' id='add-alumno'>";
					echo "<tr>";
						echo "<th>Nombre Administrador</th>";		
						echo "<th>Codigo Administrador</th>";
						echo "<th>Es Docente</th>";
					echo "</tr>";	
					funAddAdministrador();
				echo "</table>";
				echo "<input type=hidden name=nadministradores id=nadministradores value=1>" ;
			echo "</fieldset>";
		echo "</form>\"";
}

function funAddAdministrador()
{
	$id = 1;
	echo "<tr class='Administrador-ultimo' id=$id>";
		echo "<td>";
	echo "<input type='text' id=Nombre-Administrador-$id class='text ui-widget-content ui-corner-all' )>";
		echo "</td>";
		echo "<td>";
	echo "<input type='text' id=Codigo-Administrador-$id class='text ui-widget-content ui-corner-all' )>";	
		echo "</td>";
		echo "<td>";
	echo "<select name=docencia id=docencia-$id>";	
		echo "<option value=1>Si</option>";	
		echo "<option value=0>No</option>";	
	echo "</select>";	
		echo "</td>";
		echo "<td>";
	echo "<div class=add-button>";
	echo "<a class='btn tools' title='Agregar elemento' id=add-element onclick=addAdministradorElemento()><li class=icon-plus></li></a>";
	echo "<a class='btn tools delete' id=$id title='Eliminar elemento' onclick=dellAdministradorElemento($(this).attr('id'))><li class=icon-remove></li></a>";
	echo "</div>";
		echo "</td>";
	echo "</tr>";
}
function formAddPreguntas()
{
	echo "\"<p class='validateTips'>Todos los Campos son requeridos.</p>";
		echo "<form>";
			echo "<fieldset>";
				echo "<label for='name'>Tipo de Usuario</label>";
				echo "<select id='select-evaluador' class='text ui-widget-content ui-corner-all' required>";					
					echo "<option value=Alumnos>Alumnos</option>";
					echo "<option value=Docentes>Docentes</option>";					
					echo "<option value=Administradores>Administradores</option>";					
				echo "</select><br>";
				echo "<label >Evaluado</label>";
				echo "<select id='select-evaluado' class='text ui-widget-content ui-corner-all' required>";					
					echo "<option value=Alumnos>Alumnos</option>";
					echo "<option value=Docentes>Docentes</option>";					
					echo "<option value=Administradores>Administradores</option>";					
					echo "<option value=autoDocentes>autoDocentes</option>";					
					echo "<option value=autoAdministradores>autoAdministradores</option>";					
				echo "</select><br>";	
				echo "<table class='table table-bordered' id='add-alumno'>";
					echo "<tr>";
						echo "<th>Preguntas</th>";								
					echo "</tr>";	
					funAddPreguntas();
				echo "</table>";
				echo "<input type=hidden name=npreguntas id=npreguntas value=1>" ;
			echo "</fieldset>";
		echo "</form>\"";
}

function funAddPreguntas()
{
	$id = 1;
	echo "<tr class='Pregunta-ultimo' id=$id>";
		echo "<td>";
	echo "<textarea id=Pregunta-$id class='text ui-widget-content ui-corner-all' cols='200' rows='5' placeholder='Ingresa tu Pregunta'></textarea>";
		echo "</td>";
		echo "<td>";
	echo "<div class=add-button>";
	echo "<a class='btn tools' title='Agregar elemento' id=add-element onclick=addPreguntaElemento()><li class=icon-plus></li></a>";
	echo "<a class='btn tools delete' id=$id title='Eliminar elemento' onclick=dellPreguntaElemento($(this).attr('id'))><li class=icon-remove></li></a>";
	echo "</div>";
		echo "</td>";
	echo "</tr>";
}

function formImport()
{
	echo "\"<form action='admin.php' method='post' enctype='multipart/form-data'>";                        
		echo"<div>";
            echo "<label for='archivo'>Subir las Tablas en extencion .csv</label>";
            echo "<input type='file' name='archivo'/>";
            echo "<input type='submit' name='subir' value='Subir' />";
        echo "</div>";                        
	echo "</form>\"";
}
?>