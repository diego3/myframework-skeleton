<?php

namespace Application\Page;

use MyFrameWork\Request\ProcessRequest;
use MyFrameWork\Factory;
use MyFrameWork\Crud\Crud;
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
    /**
     *
     * @var \MyFrameWork\Crud 
     */
    protected $crud;
    
    public function __construct() {
        $this->crud = new Crud("usuario");
    }
    
    public function _index() {
        $this->addParameter("email", "string", array(Flag::REQUIRED));
        $this->addParameter("password", "string", array(Flag::REQUIRED));
        $this->addParameter("image", "fileimage");
        
        $this->cleanParameters();//or $this->isValidParameters()
        
        
        //dump($this->parametersValue); exit;
        $this->pagedata["form"] = $this->crud->edit($this->id, $this->parametersMeta[$this->getMethod()], $this->parametersValue);
        
        return true;
    }
    
    
}
