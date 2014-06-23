<?php

namespace App\Dao;

/**
 * Description of MunicipioDAO
 *
 * @author gilmario
 */
class MunicipioDAO extends \SEFIN\DAO\Dao {

    public function constroiEntidade($resultSet) {
        // Não nescessário
    }

    public function UF() {
        try {
            $this->db->beginTransaction();
            $query = "SELECT DISTINCT UF FROM MUNICIPIOS ORDER BY UF";
            $row = $this->db->query($query);
            $return = $row->fetchAll();
            $this->db->commit();
        } catch (PDOException $ex) {
            $this->db->rollBack();
            return $ex;
        }
        return $return;
    }

    public function municipios($uf) {
        try {
            $this->db->beginTransaction();
            $query = "SELECT NOME FROM MUNICIPIOS WHERE UF ='{$uf}' ORDER BY NOME";
            $row = $this->db->query($query);
            $return = $row->fetchAll();
            $this->db->commit();
        } catch (PDOException $ex) {
            $this->db->rollBack();
            return $ex;
        }
        return $return;
    }

}
