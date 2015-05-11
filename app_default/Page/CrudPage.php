<?php

namespace Application\Page;

use MyFrameWork\Crud\Crud;
use MyFrameWork\Session;
use MyFrameWork\Request\ProcessRequestObserver;
use MyFrameWork\Event\EventManager;

/**
 * Description of CrudPage
 *
 * @author Diego
 */
abstract class CrudPage extends ProcessRequestObserver {
    /**
     *
     * @var \MyFrameWork\Crud\Crud
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
    
    /**
     * O nome da classe DAO
     * @var string 
     */
    protected $daoname;
    
    /**
     *
     * @var string 
     */
    protected $urlbase;
    
    public function abstracoesEspecificasDeCadaPage() {
        //dao name : usar no crud, e sera a url base depois do host na rota 
        //actions da tabela
        
    }
    
    
    /**
     * 
     */
    public abstract function setParameters();
    
    /**
     * Deve retornar um array com chave => valor onde a chave é o nome da coluna
     * e o valor é o nome da coluna da tabela do banco de dados
     * @return array 
     */
    public abstract function getTableSchema();

    public function __construct(EventManager $em) {
        $this->crud = new Crud($this->daoname);
        if(null !== $this->urlbase) {
            $this->crud->setUrlbase($this->urlbase);
        }
        
        $this->dao = $this->crud->getDao();
        $this->session = Session::getInstance();
        parent::__construct($em);
    }
    
    public function preEdit(){}
    
    public function _edit() {
        $this->setParameters();
        $this->cleanParameters();
        
        $this->preEdit();
        
        $this->pagedata["form"] = $this->crud->edit($this->id, $this->parametersMeta[$this->getMethod()], $this->parametersValue);
        if(null === $this->filename) {
            $this->setTemplateFile("crud_editar");
        }
        return true;
    }
    
    public function _save() {
        $this->submit();
        
        $this->posSubmit();
        
        //redirect("{$this->crud->getUrlbase()}edit/{$this->id}");
        
        return $this->_edit();
    }
    
    protected function default_save () {
        $this->setParameters();
        $this->cleanParameters();
        
        if ($this->isValidParameters()) {
            $id = $this->crud->save($this->id, $this->parametersValue);
            if ($id > 0) {
                //evita um segundo post com os mesmos dados
                redirect("{$this->crud->getUrlbase()}/edit/{$id}");
                return 0;//??
            }
        }
        return $this->_edit();
    }
    
    protected function submit() {
        $this->setParameters();
        
        if( $this->isValidParameters()) {
            $submit_status = $this->crud->salvar($this->id, $this->parametersValue);
            //dump($submit_status);    
            $status = $submit_status == 1;
            $this->session->setData("submit_status", $status);
            if(!$status) {
                # aqui o $submit_status contém a mensagem crua de erro capturada na exceção PDOException
                # TODO mapear os códigos para mensagens mais legíveis
                $this->session->setData("submit_error", $submit_status);
            }
        }
    }
    
    /**
     * Metodo utilitário utilizado após submissões de formulários.
     * Sua função é processar a camada de visualização das mensagens de erros dos parameters
     * @view usar o arquivo de template {{>form_error}}
     * @todo Esse metodo deve ser refatorado permitindo a sua utilização de forma padronizada em qualquer lugar da aplicação, ou seja, seu funcionamento não deve se restringir apenas a algum tipo de page!
     * @return void
     */
    protected function posSubmit() {
        # TODO make this reutilizable
        $this->pagedata["submited_form"] = false;
        if(null !== $this->session->getData("submit_status")) {
            $this->pagedata["submited_form"] = true;
            $this->pagedata["success"] = false;
            
            $submit_status =  $this->session->getData("submit_status");
            
            if($submit_status) {
                $this->pagedata["success"] = true;
                # TODO flutuar no topo com animação saindo para cima
                $this->pagedata["form_success_message"] = "Salvo com Sucesso!";
            }
            else {
                # TODO mapear os códigos do pdoexeption com as mensagens de erro para um formato
                # agradável ao usuário
                $this->pagedata["form_failure_message"] = $this->session->getData("submit_error");
            }
            $this->session->removeData("submit_status");
        }
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
        
        $this->pagedata = $this->crud->browse($this->urlbase, $this->getTableSchema());
        $this->setTemplateFile("crud_listar");
        return true;
    }
    
    public function _delete() {
        $operation_status =  $this->crud->delete($this->id);
        //em sucesso esta retornando 1
        
        $status = $operation_status == 1;
        $this->session->setData("submit_status", $status);
        if(!$status) {
            $this->session->setData("submit_error", $operation_status);
        }
        $this->posSubmit();
        //@todo ainda não está exibindo as mensagem de sucesso ou erro
        return $this->_list();
    }
}
