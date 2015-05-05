<?php

namespace Application\Model\Dao;

use MyFrameWork\Factory;
use MyFrameWork\DataBase\DAO;

/**
 * Geralmente os sistemas utilizam algum esquema de permissão ou autorização.
 * Essa classe visa representar a tabela de usuarios em um banco de dados relacional.
 * 
 */
class UsuarioDAO extends DAO {
    
    protected function setParams() {
        $this->tablename = 'usuario';
    }
    
    /**
     * Cria um novo grupo
     * @param string $nome Nome
     * @param string $email E-mail do usuário
     * @param string $password senha
     * @return int
     */
    public function novo($nome, $email, $password) {
        $user = $this->getByEmail($email);
        if (empty($user)) {
            if (strlen($password) != 32) {
                $password = hashit($password);
            }
            return $this->insert(array('nome' => $nome, 'email' => $email, 'password' => $password));
        }
        Factory::log()->warn('O E-mail "' . $email . '" já se encontra cadastrado');
        return 0;
    }
    
    /**
     * 
     * @param string $newPassword
     * @param int $userId
     * @return int 1 to success and 0 to failure
     */
    public function updatePassword($newPassword, $userId) {
        return $this->update(array("password"  => hashit($newPassword)), $userId);
    }
    
    /**
     * Retorna os dados do usuário pelo seu e-mail
     * @param string $email E-mail do usuário
     */
    public function getByEmail($email) {
        return $this->getByKey('email', $email);
    }
    
    /**
     * 
     * @return UsuarioGrupoDAO
     */
    public function getUsuarioGrupoDAO() {
        return Factory::DAO('usuarioGrupo', $this->getDatabase());
    }
}
