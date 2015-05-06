<?php

namespace Application\Page;

use MyFrameWork\Request\ProcessRequest;
use MyFrameWork\Factory;
use MyFrameWork\Crud;
use MyFrameWork\Enum\Flag;

/**
 * Description of Crudexemplo
 * 
 * Page exemplo de como se utilizar os diversos datatypes disponíveis, acredito
 * que com exemplos as coisas ficam mais claras :)
 * Para visualizar acesse http://localhost/crudexemplo
 * 
 * 
 * @todo adcionar mini descrição no /painel com link para esse crud
 * @author Diego
 */
class Crudexemplo extends ProcessRequest {
    
    
    public function _index() {
        $this->addParameter("email", "string", array(Flag::REQUIRED));
        $this->addParameter("password", "string", array(Flag::REQUIRED));
        $this->addParameter("image", "fileimage");
        
        $this->cleanParameters();//or $this->isValidParameters()
        
        
        $crud = new Crud("usuario", "usuario");/*@var $crud Crud*/
        //dump($this->parametersValue); exit;
        $this->pagedata["form"] = $crud->edit("", $this->parametersMeta[$this->getMethod()], $this->parametersValue);
        
        
        return true;
    }
    
    
}
