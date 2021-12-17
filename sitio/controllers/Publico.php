<?php

require_once 'sitio/src/AppBase.php';

class Publico extends AppBase
{

    function __construct($main)
    {
        parent::__construct($main);
        $this->metas = new Meta();
        $this->metas->setTitle("Página info pública.");
    }
    public function verAction()
    {
        
        $data = array(
            "msg"		=> "Página info pública.",
        );
        
        return $this->view("sitio/layouts/default.phtml", $data, $this->metas, "sitio/views/general.phtml");
    }
    
    public function contactoAction()
    {
        $data = array(
            "msg"		=> "Página de contacto.",
        );
        
        return $this->view("sitio/layouts/default.phtml", $data, $this->metas, "sitio/views/general.phtml");
        
    }
    

}