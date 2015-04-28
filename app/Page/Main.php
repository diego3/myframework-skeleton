<?php

namespace Application\Page;

/**
 * Description of MainPage
 *
 * @author Diego Rosa dos Santos <diegosantos@alphaeditora.com.br>
 */
class Main extends ProcessRequest {
   
    
    public function _index() {
        
        return true;
    }
    
    
    public function _phpinfo() {
        phpinfo();
    }

    

}
