<?php
namespace Application\Page;

use MyFrameWork\Request\ProcessRequest;
use MyFrameWork\Memory\MemoryPage;
use MyFrameWork\Enum\ResponseType;
use MyFrameWork\Enum\RequestType;
use MyFrameWork\Enum\Flag;

class Filemanager extends ProcessRequest {
    
    const DEFAULT_ROOT = 'image/';
    
    public function __construct() {
        dump("construiu");
        //não está entrando no construtor quando clicka-se no link do componente de upload. PORQUE ?????
        //$this->allowMethod('index', array('admin'));
        //$this->allowMethod('delete', array('admin'));
        
    }
    
    protected  function setParameters() {
        $this->addParameter('path', 'string', array(Flag::DEFAULT_VALUE => self::DEFAULT_ROOT));
        $this->addParameter('header', 'boolean', array(Flag::DEFAULT_VALUE => true));
        
    }
    
    public function getPath() {
        $path = str_replace('.', '', $this->getParameter('path'));
        if (startsWith('/', $path)) {
            $path = substr($path, 1);
        }
        if (!endsWith($path, '/')) {
            $path .= '/';
        }
        $this->pagedata['path'] = $path;
        return $path;
    }
    
    public function _index() {
        MemoryPage::addCss('static/plugin/bootstrap-fileinput-master/css/fileinput.min.css');
        MemoryPage::addJs("static/plugin/bootstrap-fileinput-master/js/fileinput.min.js");
        MemoryPage::addJs('js/modal-fileupload.js');
        
        $this->setParameters();
        $this->cleanParameters();
        
        $this->pagedata['showheader'] = filter_var($this->getParameter('header'), FILTER_VALIDATE_BOOLEAN);
        $path = $this->getPath();
        $fullpath = PATH_APP . '/static/' . $path;
        $this->pagedata['files'] = array();
        if (is_dir($path)) {
            $diretorio = dir($path);
        }
        else if (is_dir($fullpath)) {
            $diretorio = dir($fullpath);
        }
        else {
            $this->pagedata['path'] = '';
            return true;
        }
        $list = array();
        while($arquivo = $diretorio->read()) {
            if ($arquivo == '.' || $arquivo == '..') {
                continue;
            }
            $aux = strtolower($arquivo);
            if (endsWith($aux, '.jpg') || endsWith($aux, '.png') || endsWith($aux, '.jpeg') || endsWith($aux, '.gif')) {
                $this->pagedata['files'][] = $arquivo;
            }
            else if (is_dir($fullpath . $arquivo)) {
                $list[] = array('url' => $path . $arquivo, 'name' => $arquivo);
            }
            else {
                //TODO
            }
        }
        $this->pagedata['hasfile'] = !empty($this->pagedata['files']);
        $diretorio->close();
        if ($this->pagedata['showheader']) {
            if (self::DEFAULT_ROOT != $path) {
                array_unshift($list, array('url' => substr($path, 0, strrpos($path, '/', -2)), 'name' => '..'));
            }
            $this->pagedata['folders'] = $list;
        }
        return true;
    }
    
    public function _save() {
        $this->setParameters();
        $this->cleanParameters();
        
        $this->responseType = ResponseType::JSON;
        $path = $this->getPath();
        $fullpath = PATH_APP . '/static/' . $path;
        $files = array();
        foreach ($_FILES as $file) {
            //http://php.net/manual/pt_BR/features.file-upload.php
            //TODO Valida tamanho do arquivo $file['size']
            //TODO validar tipo do arquivo $file['type']
            //TODO validar erro $file['error']
            //TODO validar se o path pode ser escrito
            $filename = $file['name'];
            if (file_exists($fullpath . $filename)) {
                //TODO rename file corretamente
                //$filename = date('Y-m-d-H-i-s') . '_' . $file['name'];
            }
            move_uploaded_file($file['tmp_name'], $fullpath . $filename);
            $files[] = $filename;
        }
        $this->pagedata['files'] = $files;
        return true;
    }
    
    public function _delete() {
        $path = PATH_APP . '/static/' . $this->getPath();
        if (is_dir($path)) {
            foreach ($_POST['file'] as $filename) {
                $file = $path . $filename;
                if (file_exists($file) && (endsWith($file, '.jpg') || endsWith($file, '.png') || endsWith($file, '.jpeg') || endsWith($file, '.gif'))) {
                    unlink($file);
                }
            }
        }
        redirect('filemanager?path=' . $this->getPath());
    }
    
    public function _deleteone() {
        $this->setParameters();
        $this->addParameter("file", "string", array(Flag::REQUIRED));
        $this->cleanParameters();
        
        $path = PATH_APP . '/static/' . $this->getPath();
        if (is_dir($path)) {
            $file = $path . $this->getParameter("file");
            if (file_exists($file) && (endsWith($file, '.jpg') || endsWith($file, '.png') || endsWith($file, '.jpeg') || endsWith($file, '.gif'))) {
                unlink($file);
            }
        }
        
        redirect('filemanager?path=' . $this->getPath());
    }
}