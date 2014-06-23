<?php

namespace App\Dao;

/**
 * Description of UsuarioDAO
 *
 * @author gilmario
 */
class UsuarioDAO extends \SEFIN\DAO\Dao {

    protected $table = 'senha_cidadao';

    public function constroiEntidade($resultSet) {
        $entidade = new \App\Models\Usuario();
        $entidade->setCpf(\SEFIN\UTIL\Converter::trataMascara($resultSet['CPF']));
        $entidade->setSenha($resultSet['SENHA']);
        if (isset($resultSet['DATA_CRIACAO'])) {
            $entidade->setDataCriacao($resultSet['DATA_CRIACAO']);
        }
        if (isset($resultSet['PADRAO'])) {
            $entidade->setPadrao($resultSet['PADRAO']);
        }
        return $entidade;
    }

    public function logar(\App\Models\Usuario $usuario) {
        try {
            $this->db->beginTransaction();
            $query = "SELECT C.* FROM CIDADAO C INNER JOIN SENHA_CIDADAO S ON S.CPF = C.CPF WHERE S.CPF ='{$usuario->getCpf()}' AND S.SENHA = '{$usuario->getSenha()}' AND C.SITUACAO ='A'";
            $row = $this->db->query($query);
            $return = $row->fetch();
            $this->db->commit();
        } catch (PDOException $ex) {
            $this->db->rollBack();
            return $ex;
        }
        return $return;
    }

    public function validaSenha($senha, $cpf) {
        try {
            $this->db->beginTransaction();
            $query = "SELECT C.* FROM CIDADAO C INNER JOIN SENHA_CIDADAO S ON S.CPF = C.CPF WHERE S.CPF ='{$cpf}' AND S.SENHA = '{$senha}' AND C.SITUACAO ='A'";
            $row = $this->db->query($query);
            $return = $row->fetch();
            $this->db->commit();
        } catch (PDOException $ex) {
            $this->db->rollBack();
            return $ex;
        }
        return $return;
    }

    public function alteraSenha($senha, $cpf) {
        try {
            $this->db->beginTransaction();
            $query = "UPDATE SENHA_CIDADAO S SET S.SENHA ='{$senha}', PADRAO='N' WHERE S.CPF ='{$cpf}'";
            $row = $this->db->query($query);
            $return = $row->fetch();
            $this->db->commit();
        } catch (PDOException $ex) {
            $this->db->rollBack();
            return $ex;
        }
        return $return;
    }

}
