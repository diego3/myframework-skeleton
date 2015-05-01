<?php
namespace Application\Page;

use MyFrameWork\Request\ProcessRequest;
use MyFrameWork\Factory;
use MyFrameWork\Memory\MemoryPage;

class Painel extends ProcessRequest {
    
    public function __construct() {
        \MyFrameWork\Memory\MemoryPage::addCss("css/page/painel-index.css");
        $this->pageTitle = 'Painel administrativo';
        if ($_GET['_action'] == 'install') {
            define('TEST_OR_INSTALL', true);
        }
    }
    
    public function isInstalled() {
        return file_exists('default/install/installed.php');
    }
    
    /**
     * testar a conexao com o banco antes de realizar a instalação
     */
    public function _testaconexao() {
        \MyFrameWork\Memory\MemoryPage::addCss("css/page/painel-index.css");
        //MemoryPage::add('debug', true);
        $porta = $_POST["driver"] == "pgsql" ? '5432' : '3306';
        $params = array(
            "driver" => $_POST["driver"],
            "dbname" => $_POST["banco"],
            "port" => $porta,
            "host" => $_POST["host"],
            "user" => $_POST["usuario"],
            "password" => $_POST["senha"]
        );
        $conectado = false;
        
        $db = Factory::database($params);
        /*@var $db \MyFrameWork\DataBase\PgDataBase */
        if(is_object($db)) {
            $conectado = true;
            $status = $db->getAttribute(\PDO::ATTR_CONNECTION_STATUS);
        }else {
            //dump(\MyFrameWork\LoggerApp::getErrors());
            //dump(\MyFrameWork\LoggerApp::getLastError());
            $status = \MyFrameWork\LoggerApp::getLastError();
            $conectado = false;
        }
       
        $this->pagedata["conectado"] = $conectado;
        $this->pagedata["status_conexao"] = mb_convert_encoding($status, "utf-8");
        $this->filename = "painel_index";
        return true;
    }
    
    /**
     * tentar criar o banco de dado e mostrar caso o banco já exista
     */
    public function _createdatabase() {
        
    }
    
    public function _index() {
        \MyFrameWork\Memory\MemoryPage::addCss("css/page/painel-index.css");
        return true;
    } 
    
    public function _install() {
        if ($this->isInstalled()) {
            //TODO show error
            return false;
        }
        echo "<h2>CREATE default TABLES </h2>";
        $this->createDatabaseItems('default/install/');
        echo "<h2>EXECUTE SCRIPTS</h2>";
        $this->executeScripts('default/install/');
        
        echo "<h2>CREATE app TABLES </h2>";
        $this->createDatabaseItems('app/install/');
        echo "<h2>EXECUTE SCRIPTS</h2>";
        $this->executeScripts('app/install/');
    }
    
    protected function createDatabaseItems($folder) {
        $db = Factory::database();
        foreach (glob($folder . '*.sql') as $sqlfile) {
            echo "<li>{$sqlfile}</li>";
            $items = explode(';', file_get_contents($sqlfile));
            foreach ($items as $object) {
                //echo $object . '<br>';
                if (!$db->execute($object)) {
                    //echo "<pre>" . $object . "</pre><hr>";
                }
            }
        }
    }
    
    protected function executeScripts($folder) {
        foreach (glob($folder . '*.php') as $phpfile) {
            echo "<li>{$phpfile}</li>";
            require_once($phpfile);
        }
    }
}

