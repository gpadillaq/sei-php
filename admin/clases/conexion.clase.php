<?php
//header("Content-Type: text/html;charset=utf-8");
/**
* CLASE CONEXION
*/
class conexion
{
	  private $usuario  = "ulsaedu_sei";
	  private $password = "pwulsa13";
	  private $host     = "localhost";
	  private $db       = "ulsaedu_sei";


	//private $usuario  = "sei";
	//private $password = "123";
	//private $host     = "localhost";
	//private $db       = "SEI";
	//private $Myconexion;

	public function __construct()
	{
    	$this->Myconexion = mysql_pconnect($this->host,$this->usuario,$this->password) or die("Error De Conexion!");
		mysql_select_db($this->db);
		mysql_query("SET NAMES 'utf8'");
	}

	public function getSelect($tabla,$camposAselect,$where)
	{
		$atributos = "SELECT ".$camposAselect." FROM ".$tabla." WHERE $where";
		// echo "console.log(\"".$atributos."\");";
		$Myconsulta = mysql_query($atributos,$this->Myconexion) or die("Error en la Consulta de Seleccion!");
		return $Myconsulta;
	}

	public function getUpdate($tabla,$camposAmodificar,$where)
	{
		$atributos = "UPDATE $tabla SET $camposAmodificar WHERE $where";
		//echo "console.log(\"".$atributos."\");";
		mysql_query($atributos,$this->Myconexion) or die("Error en la Consulta de Actualizacion!");
	}

	public function getInsert($tabla,$camposAinsertar,$valores)
	{
		$atributos = "INSERT INTO $tabla ($camposAinsertar) VALUES ($valores)";
		//echo "console.log(\"".$atributos."\");";
		mysql_query($atributos,$this->Myconexion) or die("Error en la Consulta de Insercion!"); 
	}

	public function getDel($tabla,$where)
	{
		$atributos = "DELETE FROM $tabla WHERE $where";
		 // echo "console.log(\"".$atributos."\");";
		mysql_query($atributos,$this->Myconexion) or die("Error en la Consulta de Eliminacion!"); 
	}


	public function showConexion()
	{
		return $this->Myconexion;
	}
	public function showUsuario()
	{
		return $this->usuario;
	}

	public function showPassword()
	{
		return $this->password;
	}

	public function showHost()
	{
		return $this->host;
	}

	public function showDB()
	{
		return $this->db;
	}

}


?>