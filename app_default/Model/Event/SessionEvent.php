<?php

namespace Application\Model\Event;

/**
 * Description of SessionEvent
 *
 * @author Diego Rosa dos Santos <diegosantos@alphaeditora.com.br>
 */
class SessionEvent extends Event {
    
    const ACESSO_NEGADO = 'acesso_negado';
    const ACESSO_PERMITIDO = 'acesso_permitido';
    
    protected $usuario;
    protected $sessionID;
    
    public function __construct($usuario) {
        $this->usuario = $usuario;
    }
    
    public function getUsuario() {
        return $this->usuario;
    }
    
}
