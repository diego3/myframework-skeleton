<?php

namespace Application\Page;

use MyFrameWork\Request\ProcessRequest;

class ErrorPage extends ProcessRequest {
    public function __construct() {
        $this->filename = 'errorpage';
    }
    
    public function _index() {
        return true;
    }
}