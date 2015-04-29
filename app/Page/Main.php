<?php

namespace Application\Page;

use MyFrameWork\Request\ProcessRequest;

/**
 * Description of MainPage
 *
 * @author Diego Rosa dos Santos <diegosantos@alphaeditora.com.br>
 */
class Main extends ProcessRequest {
   
    
    public function _index() {
        $this->pagedata["bem-vindo"] = "BEM VINDO!";
        return true;
    }
    
    
    public function _phpinfo() {
        phpinfo();
    }

    

}
