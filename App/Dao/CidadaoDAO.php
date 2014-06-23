<?php

namespace App\Dao;

use SEFIN\DAO\Dao;

/**
 * Description of CidadaoDAO
 *
 * @author gilmario
 */
class CidadaoDAO extends Dao {

    protected $table = 'cidadao';

    /**
     * Cria o contribuinte a partir de um post
     * @param type $resultSet
     * @return \App\Models\CadastroCredito
     */
    public function constroiEntidade($resultSet) {
        $entidade = new \App\Models\Cidadao();
        $entidade->setBairro(strtoupper($resultSet['BAIRRO']));
        $entidade->setCep(\SEFIN\UTIL\Converter::trataMascara($resultSet['CEP']));
        $entidade->setCidade(strtoupper($resultSet['CIDADE']));
        $entidade->setComplemento(strtoupper($resultSet['COMPLEMENTO']));
        $entidade->setCpf(\SEFIN\UTIL\Converter::trataMascara($resultSet['CPF']));
        if (isset($resultSet['DATA_CRIACAO'])) {
            $entidade->setDataCriacao(\SEFIN\UTIL\Converter::stringParaData($resultSet['DATA_CRIACAO']));
        }
        if (isset($resultSet['COD_CONTRIBUINTE'])) {
            $entidade->setCodigoContribuinteSam($resultSet['COD_CONTRIBUINTE']);
        }
        $entidade->setEmail(strtolower($resultSet['EMAIL']));
        $entidade->setLogradouro(strtoupper($resultSet['LOGRADOURO']));
        $entidade->setNome(strtoupper($resultSet['NOME']));
        $entidade->setNumero(strtoupper($resultSet['NUMERO']));
        $entidade->setSituacao($resultSet["SITUACAO"]);
        $entidade->setTelefonePrincipal(\SEFIN\UTIL\Converter::trataMascara($resultSet['TELEFONE_PRINCIPAL']));
        $entidade->setTelefoneSecundario(\SEFIN\UTIL\Converter::trataMascara($resultSet['TELEFONE_SECUNDARIO']));
        $entidade->setUf(strtoupper($resultSet['UF']));
        return $entidade;
    }

    public function adiciona(\App\Models\Cidadao $entidade) {
        $sql = "INSERT INTO " . $this->table . " (cpf,nome,logradouro,cep,numero,complemento,bairro,cidade,uf,email,telefone_principal,telefone_secundario,situacao,cod_contribuinte,data_criacao)"
                . " VALUES('{$entidade->getCpf()}','{$entidade->getNome()}','{$entidade->getLogradouro()}','{$entidade->getCep()}','{$entidade->getNumero()}','{$entidade->getComplemento()}',"
                . "'{$entidade->getBairro()}','{$entidade->getCidade()}','{$entidade->getUf()}','{$entidade->getEmail()}','{$entidade->getTelefonePrincipal()}','{$entidade->getTelefoneSecundario()}',"
                . "'{$entidade->getSituacao()}',null,current_date)";
        $this->insertUpdate($sql);
    }

    public function carregarInativo($id) {
        try {
            $this->db->beginTransaction();
            $query = "SELECT * FROM CIDADAO WHERE CPF = '{$id}' AND SITUACAO = 'I'";
            $row = $this->db->query($query);
            $return = $row->fetch();
            $this->db->commit();
        } catch (PDOException $ex) {
            $this->db->rollBack();
            return $ex;
        }
        return $return;
    }

    public function ativaCadastro($cpf) {
        $query = "UPDATE CIDADAO SET SITUACAO = 'A' WHERE CPF = '{$cpf}'";
        $this->insertUpdate($query);
    }

}
