<?php
function createDefaultAppGroups() {
    echo '<li>CreateDefaultAppGroups</li>';
    
    $dao = Factory::DAO('grupo');
    /* @var $dao GrupoDAO */
    
    $grupos = array(
        'empresa' => 'Gerencia os dados de uma empresa',
        'cliente' => 'Cliente que realiza as compras'
    );
    foreach ($grupos as $nome => $descricao) {
        $grupo = $dao->getByNome($nome);
        if (empty($grupo)) {
            assert($dao->novo($nome, $descricao));
        }
    }
} createDefaultAppGroups();

function createDefaultEmpresa() {
    echo '<li>CreateDefaultEmpresa</li>';
    
    $dao = Factory::DAO('empresa');
    /* @var $dao EmpresaDAO */

    $empresa = $dao->getByUrl(DOMAIN);
    if (empty($empresa)) {
        assert($dao->novo(
            'Alphapress',
            DOMAIN,
            'image/empresa/logo-alphapress.png',
            'info@alphaeditora.com.br',
            '1234567',
            'IDINT',
            'default',
            'Endereco',
            'Bandeirantes',
            'PR'
       ));
    }
} createDefaultEmpresa();