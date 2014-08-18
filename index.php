<?php
 function funcion_index() { 
    //Capturar Mensajes de ERROR.
    
    if (isset($_REQUEST['msg'])) {
        $msg = $_REQUEST['msg'];
        msg($msg);
    }

    ?>

     <div id="cuerpo">

		<div id="login" align="center">
		<form action="evaluacion.php" method="post">
            <legend>ACCESO</legend>
            <label>Codigo de Carnet</label>

            <div class="input-append input-prepend">
                <input name="tbanio" type="password" size="4" maxlength="2" class="input-prepend input-corto" required/>
                <span class="add-on">-</span>
                <input name="tbusuario" type="password" size="4" maxlength="3" class="input-prepend input-corto" required/>
                <span class="add-on">-</span>
                <input name="tbcodigo" type="password" size="4" maxlength="4" class="input-prepend input-corto"  required/>
                <input name="btingreso" type="submit" value="Ingresar" class="btn btn-primary"/>
            </div>         
    	</form>
   		</div>
    </div>
		
<?php }
	require_once("plantillas/Pindex.php"); ?>