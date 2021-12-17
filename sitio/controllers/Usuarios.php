<?php

require_once 'sitio/src/AppBase.php';


class Usuarios extends AppBase
{
	
	function __construct(Main $main)
	{
		parent::__construct($main);
		$this->metas = new Meta();
		$this->metas->setTitle("Página de usuario.");
	}
	public function verAction()
	{
		$partial = "sitio/partial/usuario.phtml";
		$data = array();

		$req = $this->main->getRequest();

		if($req->getQueryParam("id") != null)
		{

			$this->metas->setTitle("Perfil de usuario.");

			$id = $req->getQueryParam('id');
            
			
			$u = $this->obtenerUsuario($id);
	    
			if($u == null)return $this->nofoundAction("El usuario no es válido.");
			
			$data['usuario'] = $u;

		}
		else
		{
			$data['usuario'] = isset($this->usuario) ? $this->usuario : null;
		}
		
		return $this->view("sitio/layouts/default.phtml", $data, $this->metas, $partial);
	}
	public function erroraccesoAction()
	{
	    $data = array(
	        "msg"		=> "Error. Usuario no permitido.",
	    );
	    
	    return $this->view("sitio/layouts/default.phtml", $data, $this->metas, "sitio/views/erroracceso.phtml", 403);
	}
	public function errorinternoAction()
	{
	    return $this->errorAction("Error interno. Servicio no disponible.", 500);
	}
	public function logoutAction()
	{
		
		$req = $this->main->getRequest();
		$ir = $req->getQueryParam("red", $_SERVER['SERVER_NAME']);
		session_destroy();
		header("Location: http://".$ir);
		exit();	
	}
	
	public function loginAction()
	{
		$req = $this->main->getRequest();

		$log = array('estado' => 'neutro', 'msg' => 'Ingrese sus datos de acceso.');
		
		if($req->isPost())
		{
			
		}
		return $this->view("sitio/layouts/default.phtml", $log, $this->metas, "sitio/views/usuarios/login.phtml");
	}
	

}