<?php

require_once 'sitio/src/Main.php';
require_once 'sitio/src/Objetos.php';
require_once 'sitio/src/Request.php';
require_once 'sitio/src/Renders.php';
require_once 'sitio/src/DB.php';

class AppBase   // base de las controladoras
{
	protected $cliente_ip;
	protected $csrf;
	protected Usuario $usuario;
	protected Main $main;
	protected DB $_db;
	protected $metas;

	public function __construct(Main $main)
	{
		$this->main = $main;
		$this->cliente_ip = $_SERVER['REMOTE_ADDR'];
		$this->metas = new Meta();
		$this->metas->setTitle("Coso Base");
		
		if(empty($_SESSION['csrf']))
		{
		    $_SESSION["csrf"] = substr(str_shuffle(md5(time().$this->cliente_ip)),0, 30);
		    $_SESSION["cliente_ip"] = $this->cliente_ip;
		}
		$this->csrf = $_SESSION["csrf"];
				
		/*
		 * Comprueba la valides del usuario
		 */
		if(isset($_SESSION['uid']))
		{
		    $u = $this->obtenerUsuario($_SESSION['uid']);
		    if($u != null && $u->activo)
		    {
		        $this->usuario = $u;
		    }
		    else  // usuario inexistente o inactivo
		    {
		        unset($_SESSION['uid']);
		    }
		    
		}
		
		$this->comprobarAcceso();		
	}
	private function comprobarAcceso()
	{
	    $n = strtolower($this->main->getRouter()->getName());
	    $c = strtolower($this->main->getRouter()->getController());
	    $a = strtolower($this->main->getRouter()->getAction());
	 
        if($c == 'usuarios' && ($a == "errorinterno" || $a == "erroracceso" || $a == "nofound" || $a == "error" || $a == "logout"))return;
        if($c == 'publico')return;
        if(!isset($this->usuario) && $c == "usuarios" && ($a == "login" || $a == "clavecambio" || $a == "clave"))return;

		// secciones privadas del sitio solo para usuarios
        if(
			!isset($this->usuario)
			&& false // sus comprobaciones
			)
        {
            header("Location: http://".$_SERVER['SERVER_NAME']."/usuarios/login");
            exit();
        }
	    
	}
	
	public function obtenerUsuario($id)
	{
	    $result = $this->db()->select(new Select('usuarios', "*", null, "usuarios.id = :id and usuarios.activo = 1"), array("id" => $id));
	    if($result->count == 1)
	    {
	        return new Usuario((array)$result->data[0]);
	    }
	    return null;
	}
	public function validarUsuario($correo, $clave)
	{
	    $result = $this->db()->select(new Select('usuarios', "*", null, "usuarios.correo = :correo AND usuarios.clave = :clave AND usuarios.activo = 1"), array("correo" => $correo, "clave" => md5($clave)));
	    if($result->count == 1)
	    {
	        return new Usuario((array)$result->data[0]);
	    }
	    return null;
	}
	public function loginUsuario($correo, $clave)
	{
	    $u = $this->validarUsuario($correo, $clave);
	    if($u != null && $u->activo == true)
	    {
	        $this->usuario = $u;
	        $_SESSION['uid'] = $this->usuario->id;
	        return true;
	    }
	    return false;
	}
	
	public function comprobarCSRF(array $post)
	{
		return (isset($post['csrf']) && $post['csrf'] == $_SESSION["csrf"]);
	}

	public function nofoundAction($msg = null)
	{
	    return $this->view(
	                   "sitio/layouts/default.phtml", 
	                   array("msg" => ($msg == null ? "La página no está disponible." : $msg)), 
	                   $this->metas, 
	                   "sitio/views/nofound.phtml", 
	                   404
	               );
	}
	public function errorAction($msg = null, $codigo = 503)
	{
	    return $this->view(
	                   "sitio/layouts/default.phtml", 
	                   array("msg" => ($msg == null ? "A ocurrido un error." : $msg)), 
	                   $this->metas, 
	                   "sitio/views/error.phtml", 
	                   $codigo
	               );
	}
	public function view($layout_file, $data, $metas, $vista_file, $codigo = 200)
	{
	    $data['csrf'] = $this->csrf;
	    $data['usuario'] = isset($this->usuario) ? $this->usuario : null;

		if($this->main->getRequest()->isApi())
		{
			return new Response(new RenderJSON($data), $codigo);
		}
	    
	    $l = new Layout($layout_file, $metas);
	    $l->setData($data);		
	    return new Response(new RenderPHP($data, $vista_file, $l), $codigo);
	}
	public function db()
	{
	    if(!isset($this->_db))
		{
			$this->_db = new DB($this->main->getDBConfig('dsn'), $this->main->getDBConfig('usuario'), $this->main->getDBConfig('clave'));
			if($this->_db->isErrors())
			{
				header("Location: http://".$_SERVER['SERVER_NAME']."/errorinterno.html");
			    exit();
			}
		}
	    return $this->_db;
	}
}


