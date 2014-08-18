<?php
 function funcion_index() { 
    //Capturar Mensajes de ERROR.
    if (isset($_REQUEST['msg'])) {
        # code..
        $msg = $_REQUEST['msg'];
        msg($msg);
    }

    ?>
     <div id="cuerpo">

		<div id="login" align="center">
		<form action="admin.php" method="post">
            <legend>ACCESO</legend>
            <table>
            	<tr>
            		<td>
            			<label>Usuario</label>
            			<input type="text" name="user" required>
            			<label>Contraceña</label>
            			<input type="password" name="pw" required>
            		</td>
            	</tr>
            	<tr>
            		<td>
            			<input type="submit" value="Entrar" name="entrar" class="btn">
            		</td>
            	</tr>            	
            </table>           

    	</form>
   		</div>
    </div>
		
<?php }
	require_once("../plantillas/Pindex.php"); ?>