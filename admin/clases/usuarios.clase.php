<?php
//HEREDADORES DE CLASES DE FORMA MULTIPLE
Abstract class ExtensionBridge
{
    // array containing all the extended classes
    private $_exts = array();
    public $_this;
        
    function __construct(){$_this = $this;}
    
    public function addExt($object)
    {
        $this->_exts[]=$object;
    }
    
    public function __get($varname)
    {
        foreach($this->_exts as $ext)
        {
            if(property_exists($ext,$varname))
            return $ext->$varname;
        }
    }
    
    public function __call($method,$args)
    {
        foreach($this->_exts as $ext)
        {// return call_user_method_array($method,$ext,$args); depreciada desde php4.0
            if(method_exists($ext,$method))             
            	return call_user_func_array(array($ext,$method),$args);
            	
        }
        throw new Exception("This Method {$method} doesn't exists");
    }
    
    
}

// HEREDADOR
class Extender extends ExtensionBridge
{
    function __construct()
    {
    }

    public function heredar($class)
    {
    	 parent::addExt(new $class());
    }
}

 /**
* CLASES REFERENTES A CADA UNO DE LOS TIPOS DE USUARIOS.
*/
class Usuarios
{
	private $numero_carnet;
	private $codigo_usuario;
	private $usuarios_a_evaluar = array();
	private $evaluado_evaluador = array();
	private $tipo_usuario;
	private $rango_pretuntas = array();//
	
	public function getCodigos($cod1,$cod2,$cod3)
	{
		$this->numero_carnet = $cod1 . "-" . $cod2 . "-" . $cod3;
		$this->codigo_usuario = $cod2;
	}

	public function getTipoUsuario($tipo)
	{
		$this->tipo_usuario = $tipo;		
	}

	public function getUsuariosEvaluar($evaluado)
	{
		array_push($this->usuarios_a_evaluar,$evaluado);
	}

	public function getEvaluador_Evaluado($evaluador,$evaluado)
	{
		if (!is_array($this->evaluado_evaluador[$evaluador])) {
			$this->evaluado_evaluador[$evaluador] = array();
		}
		array_push($this->evaluado_evaluador[$evaluador], $evaluado);

	}

	public function getRangoPreguntas($usuario_a_evaluar,$Npregunta)
	{
		$this->rango_pretuntas[$usuario_a_evaluar] = $Npregunta;
	}

	public function showCodigoUsuario()
	{
		return $this->codigo_usuario;
	}

	public function showNumeroCarnet()
	{
		return $this->numero_carnet;
	}

	public function showTipoUsuario()
	{
		return $this->tipo_usuario;
	}

	public function showUsuariosEvaluar()
	{
		//Retorna una array con los usuarios a evaluar.
		return $this->usuarios_a_evaluar;
	}
	public function showEvaluador_Evaluado()
	{
		//Retorna una array con los usuarios a evaluar.
		return $this->evaluado_evaluador;
	}
	public function showRangoPreguntas($usuario_a_evaluar)
	{
		return $this->rango_pretuntas[$usuario_a_evaluar];
	}

}

/**
* CLASE PARA LOS USUARIOS ALUMNOS 
*/
class Alumnos
{

}

/**
* CLASE PARA LOS USUARIOS DOCENTES
*/
class Docentes
{
	private $id_alumno;
	private $numero_to_evaluar;
	private $id_docente_clase = array();
	private $codigo_clases    = array();
	private $codigo_docentes  = array();
	private $nombre_clases    = array();
	private $nombre_docentes  = array();

	
	//SOLICITUD DE DATOS
	public function getIdAlumno($id_alumno)
	{
		$this->id_alumno = $id_alumno;
	}
	public function getNumeroToEvaluarDocentes($numero)
	{
		$this->numero_to_evaluar = $numero;
	}
	public function getIdClases($id_clase)
	{
		array_push($this->id_docente_clase, $id_clase);
	}

	public function getCodigoDocente($codigo_docente,$id_clase)
	{
		$this->codigo_docentes[$id_clase] = $codigo_docente;
	}

	public function getCodigoClase($codigo_clase,$id_clase)
	{
		$this->codigo_clases[$id_clase] = $codigo_clase;
	}

	public function getNombreClase($nombre_clase,$id_clase)
	{
		$this->nombre_clases[$id_clase] = $nombre_clase;
	}

	public function getNombreDocente($nombre_docente,$codigo_docente)
	{
		$this->nombre_docentes[$codigo_docente] = $nombre_docente;
	}

	//MUESTRA DE DATOS
	public function showIdAlumno()
	{
		return $this->id_alumno;
	}
	public function showNombreDocente()
	{
		return $this->nombre_docentes;
	}
	public function showNumeroToEvaluarDocentes()
	{
		return $this->numero_to_evaluar;
	}

	public function showIdClases()
	{
		return $this->id_docente_clase;
	}

	public function showCodigoDocente()
	{
		return $this->codigo_docentes;
	}

	public function showCodigoClase()
	{
		return $this->codigo_clases;
	}

	public function showNombreDocente_to_Codigo($nombre_docente)
	{ 
		return array_search($nombre_docente,$this->nombre_docentes);
	}

	public function showNombreClase_to_Codigo($nombre_clase)
	{
		return array_search($nombre_clase,$this->nombre_clases);
	}

	public function showCodigoClase_to_Nombre($id_clase)
	{
		return $this->nombre_clases[$id_clase];
	}

	public function showCodigoDocente_to_Nombre($codigo_docente)
	{
		return $this->nombre_docentes[$codigo_docente];
	}

}



/**
* CLASE PARA LOS ADMINISTRADORES
*/
class Administradores
{
	private $numero_to_evaluar;
	private $codigo_nombre = array();

	public function getNumeroToEvaluarAdministradores($numero)
	{
		$this->numero_to_evaluar = $numero;
	}
	public function getCodigo_nombre($nombre,$codigo)
	{
		$this->codigo_nombre[$codigo] = $nombre;
	}

	public function showAdministradores()
	{
		return $this->codigo_nombre;
	}

	public function showNombre_to_Codigo($nombre)
	{ 
		return array_search($nombre,$this->codigo_nombre);
	}

	public function showCodigo_to_Nombre($codigo)
	{
		return $this->codigo_nombre[$codigo];
	}
	
	public function showNumeroToEvaluarAdministradores()
	{
		return $this->numero_to_evaluar;
	}
}

/**
* CLASE PARA LAS PREGUNTAS DE LOS USUARIOS
*/
class preguntas
{
	private $preguntas = array();
	
	public function getpreguntas($usuario,$id,$pregunta)
	{
		if(!is_array($this->preguntas[$usuario]))
		{
			$this->preguntas[$usuario] = array();	
		}
		$temp = &$this->preguntas[$usuario];
		$temp[$id] = $pregunta;

	}

	public function showpreguntas($usuario)
	{
		$retorno = &$this->preguntas[$usuario];
		return $retorno;
	}

	public function showPregunta($usuario,$id_pregunta)
	{
		$retorno = &$this->p[$usuario];
		return $retorno[$id_pregunta];
	}

	public function showId($usuario,$pregunta)
	{
		$retorno = &$this->p[$usuario];
		return array_search($pregunta,$retorno);
	}

}



?>