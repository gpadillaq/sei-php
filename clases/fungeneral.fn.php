<?php
function msg($msg)
{
	# code...
	echo "<div id='dialog-message' title='Error Detectado'>";
		echo "<p>";
			echo "<span class='ui-icon ui-icon-circle-check' style='float:left; margin:0 7px 50px 0;'></span>";
			echo "Se ha Detectado Un ERROR en la Conexion";
		echo "</p>";
		echo "<p>";
			echo "<b>$msg</b>";
		echo "</p>";
	echo "</div>";
}
function msgOk($titleBar,$title,$msg)
{
	# code...
	echo "<div id='dialog-message' title='".$titleBar."'>";
		echo "<p>";
			echo "<span class='ui-icon ui-icon-circle-check' style='float:left; margin:0 7px 50px 0;'></span>";
			echo $title;
		echo "</p>";
		echo "<p>";
			echo "<b>$msg</b>";
		echo "</p>";
	echo "</div>";
}
function ExisteCodigo($num,$tabla)
{
		if ($num <= 0) {
			///MOSTAR ALERTA
			?><script> location.href="index.php?msg=Error: No Existen El Codigo Ingresado en la tabla <?php echo $tabla?>"; </script><?php
		}else return $num;
}

function usuarioEvaluado($codigo)
{
	$msg = "Error: El usuario con Codigo ".$codigo." ya realizo la Evaluacion";
	echo "<script> location.href='index.php?msg=".$msg."';</script>";

}

?>