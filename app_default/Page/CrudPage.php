<?php

namespace Application\Page;

use MyFrameWork\Crud;
use MyFrameWork\Session;
use MyFrameWork\Request\ProcessRequest;

/**
 * Description of CrudPage
 *
 * @author Diego
 */
abstract class CrudPage extends ProcessRequest {
    /**
     *
     * @var \MyFrameWork\Crud
     */
    protected  $crud;
    
    /**
     *
     * @var \MyFrameWork\DataBase\DAO
     */
    protected $dao;
    
    /**
     *
     * @var \MyFrameWork\Session 
     */
    protected $session;
    
    protected $daoname;
    
    protected $urlbase;
    
    public function abstracoesEspecificasDeCadaPage() {
        //dao name : usar no crud, e sera a url base depois do host na rota
        //schema da tabela ao listar, 
        //actions da tabela
        //configs de css e js devido ao template da aplicação [ geralmente vai no construtor]
        //   -- sobrescrever o costrutor e chamar o parent no final
        
    }
    
    public abstract function setParameters();
    /**
     * Deve retornar um array com chave => valor onde a chave é o nome da coluna
     * e o valor é o nome da coluna da tabela do banco de dados
     * @return array 
     */
    public abstract function getTableSchema();

    public function __construct() {
        $this->crud = new Crud($this->daoname);
        if(null !== $this->urlbase) {
            $this->crud->setUrlbase($this->urlbase);
        }
        
        $this->dao = $this->crud->getDao();
        $this->session = Session::getInstance();
    }
    
    public function _edit() {
        $this->setParameters();
        $this->cleanParameters();
        
        $this->pagedata["form"] = $this->crud->edit($this->id, $this->parametersMeta[$this->getMethod()], $this->parametersValue);
        $this->setTemplateFile("crud_editar");
        return true;
    }
    
    public function _save() {
        $this->setParameters();
        $this->cleanParameters();
        
        if ($this->isValidParameters()) {
            $id = $this->crud->save($this->id, $this->parametersValue);
            if ($id > 0) {
                redirect("{$this->crud->getUrlbase()}/edit/{$id}");
                return 0;//??
            }
        }
        return $this->_edit();
    }
    
    /**
     * por padrão o crud usa o metodo listAll do DAO para listar TODOS os dados de uma tabela.
     * Portanto para realizar uma consulta específica você pode sobrescrever
     * esse método para setar o crud::setDados()
     * @see \MyFrameWork\Crud::setDados
     */
    public function preList() {}
    
    public function _list() {
        $this->preList();
        
        $this->pagedata = $this->crud->browse(ucfirst($this->urlbase) . "s", $this->getTableSchema());
        $this->setTemplateFile("crud_listar");
        return true;
    }
    
    public function _delete() {
        if($this->crud->delete($this->id)) {
            $this->_list();
        }else {
            //@todo ERROR ?
            debug();
        }
    }
}
