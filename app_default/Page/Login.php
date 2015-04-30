<?php

namespace Application\Page;

use MyFrameWork\Request\ProcessRequest;
use MyFrameWork\Factory;
use MyFrameWork\Session;

class Login extends ProcessRequest {
    
    public function __construct() {
        $this->filename = 'login';
        $this->pageTitle = 'Efetuar login';
    }
    
    public function _main() {
        return true;
    }
    
    public function _entrar() {
        $this->addParameter('usuario', 'string', array(Flag::REQUIRED));
        $this->addParameter('senha', 'string', array(Flag::REQUIRED));
        $this->addParameter('redirect', 'string', array(Flag::DEFAULT_VALUE => '/'));
        
        $this->pagedata["method"] = $this->getMethod();
        if ($this->isValidParameters()) {
            $user = $this->getParameter('usuario');
            $pass = hashit($this->getParameter('senha'));
            if (Session::singleton()->login($user, $pass)) {
                redirect($this->getParameter('redirect'));
            }
        }else {
            echo "invalid parameters";
        }
        
        $this->pagedata['erro'] = $_SESSION["logginError"];
        $this->pagedata['vemail'] = $this->getParameter('usuario');
        $this->pagedata['vredirect'] = $this->getParameter('redirect');
        return $this->_main();
    }
    
    public function _sair() {
        Session::singleton()->logout();
        redirect('/');
    }
    
    public function _recover() {
        
        
        
        return true;
    }
    
    /**
     * enviar senha para usuario
     */
    public function _sendPassword() {
        
        
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function _updatepassword() {
        
    }
}

