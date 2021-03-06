<?php

namespace Application\Model\Dao;

use MyFrameWork\Factory;
use MyFrameWork\DataBase\DAO;
use MyFrameWork\Common\Cryptographer; 

/**
 * Geralmente os sistemas utilizam algum esquema de permissão ou autorização.
 * Essa classe visa representar a tabela de usuarios em um banco de dados relacional.
 * 
 */
class UsuarioDAO extends DAO {
    
    protected function setParams() {
        $this->tablename = 'usuario';
        $this->hasactive = true;
    }
    
    /**
     * Retorna uma senha criptografada com a camada imediata encodada com base64
     * @param string $password
     * @return string 
     */
    protected function encrypt($password) {
        return Cryptographer::encriptyPassword($password);
    }
    
    /**
     * Cria um novo usuário
     * 
     * @param string $email    E-mail do usuário
     * @param string $password Deve ser uma senha sem criptografia ou hash
     * @param string $nome     Nome
     * @return int
     */
    public function novo($email, $password, $nome) {
        $user = $this->getByEmail($email);
        if (empty($user)) {
            if (strlen($password) != 60) {
                $password = $this->encrypt($password);
            }
            return $this->insert(array('nome' => $nome, 'email' => $email, 'password' => $password));
        }
        Factory::log()->warn('O E-mail "' . $email . '" já se encontra cadastrado');
        return 0;
    }
    
    /**
     * 
     * @param string $newPassword Deve ser uma senha sem criptografia ou hash
     * @param int $userId         O id do usuário
     * @return int                1 to success and 0 to failure
     */
    public function updatePassword($newPassword, $userId) {
        return $this->update(array("password"  => $this->encrypt($newPassword)), $userId);
    }
    
    /**
     * Retorna os dados do usuário pelo seu e-mail
     * 
     * @param string $email E-mail do usuário
     */
    public function getByEmail($email) {
        return $this->getByKey('email', $email);
    }
    
    /**
     * 
     * @return \Application\Model\Dao\UsuariogrupoDAO
     */
    public function getUsuariogrupoDAO() {
        return Factory::DAO('usuarioGrupo', $this->getDatabase());
    }
}
