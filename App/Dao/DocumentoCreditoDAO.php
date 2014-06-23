<?php

namespace App\Dao;

use SEFIN\DAO\Dao;
use App\Models\DocumentoCredito;

/**
 * Description of DocumentoCreditoDAO
 *
 * @author gilmario
 */
class DocumentoCreditoDAO extends Dao {

    protected $table = "DOCUMENTOS_CREDITO";

    public function constroiEntidade($resultSet) {
        $entidade = new DocumentoCredito();
        $entidade->setCnpj($resultSet['CNPJ']);
        $entidade->setCodmoddoc($resultSet['COD_MODDOC']);
        $entidade->setCpf($resultSet['CPF']);
        $entidade->setCreditado($resultSet['CREDITADO']);
        $entidade->setNumero($resultSet['NUMERO']);
        $entidade->setSerie($resultSet['SERIE']);
        $entidade->setValorcredito($resultSet['VALOR_CREDITO']);
        $entidade->setValoriss($resultSet['VALOR_ISS']);
        $entidade->setPago($resultSet['PAGO']);
        $entidade->setDataEmissao($resultSet['DATA_EMISSAO']);
        return $entidade;
    }

    public function constroiEntidades($lista) {
        $result = array();
        foreach ($lista as $row) {
            array_push($result, $this->constroiEntidade($row));
        }
        return $result;
    }

    public function consutarNotasPaginacao($cpf, $max, $pular, $datai = null, $dataf = null) {
        try {
            $this->db->beginTransaction();
            $query = "SELECT FIRST {$max} SKIP {$pular} * FROM {$this->table} WHERE CPF='{$cpf}' ";
            if ($datai != null && $dataf != null) {
                $query = $query . " AND DATA_EMISSAO BETWEEN '{$datai}' AND '{$dataf}'";
            }
            $row = $this->db->query($query);
            $return = $row->fetchAll();
            $this->db->commit();
        } catch (PDOException $ex) {
            $this->db->rollBack();
            return $ex;
        }
        return $return;
    }

    public function contaNotas($cpf, $datai = null, $dataf = null) {
        try {
            $this->db->beginTransaction();
            $query = "SELECT COUNT(CNPJ) AS TOTAL FROM {$this->table} WHERE CPF='{$cpf}'";
            if ($datai != null && $dataf != null) {
                $query = $query . " AND DATA_EMISSAO BETWEEN '{$datai}' AND '{$dataf}'";
            }
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
