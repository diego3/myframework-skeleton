<?php

namespace Application\Page;
use MyFrameWork\Request\ProcessRequest;
use MyFrameWork\Memory\MemoryPage;

/**
 * Representa uma página de acesso negado
 *
 * @author Diego Rosa dos Santos <diegosantos@alphaeditora.com.br>
 */
class DeniedacessPage extends ProcessRequest {
    
    public function __construct() {
        $this->allowMethod("_index", "*");
        MemoryPage::addCss("css/page/accessdenied.css");
        $this->filename = 'acessdenied';
    }
    
    public function _index() {
        return true;
    }
}
