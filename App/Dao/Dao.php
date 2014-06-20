<?php

namespace App\DAO;

use App\Init;

/**
 * Description of DAO
 *
 * @author gilmario
 */
abstract class Dao {

    protected $db;
    protected $table;

    function __construct() {
        $this->db = Init::getDb();
    }

    public function getAll() {
        try {
            $this->db->beginTransaction();
            $query = "SELECT * FROM {$this->table}";
            $row = $this->db->query($query);
            $return = $row->fetchAll();
            $this->db->commit();
        } catch (PDOException $ex) {
            $this->db->rollBack();
            return $ex;
        }
        return $return;
    }

    public function carregar($campo, $id) {
        try {
            $this->db->beginTransaction();
            $query = "SELECT * FROM {$this->table} WHERE {$campo} = '{$id}'";
            $row = $this->db->query($query);
            $return = $row->fetch();
            $this->db->commit();
        } catch (PDOException $ex) {
            $this->db->rollBack();
            return $ex;
        }
        return $return;
    }

    public function insertUpdate($sql) {
        try {
            $this->db->beginTransaction();
            $this->db->query($sql);
            $this->db->commit();
        } catch (PDOException $ex) {
            $this->db->rollBack();
            return $ex;
        }
    }

    public function consultar($campo, $nome = "") {
        try {
            $this->db->beginTransaction();
            $query = "SELECT * FROM {$this->table} WHERE {$campo} like '{$nome}%'";
            $row = $this->db->query($query);
            $return = $row->fetchAll();
            $this->db->commit();
        } catch (PDOException $ex) {
            $this->db->rollBack();
            return $ex;
        }
        return $return;
    }

    public function lista($campo, $valor = "") {
        try {
            $this->db->beginTransaction();
            $query = "SELECT * FROM {$this->table} WHERE {$campo} = {$valor}";
            $return = $this->db->query($query);
        } catch (PDOException $ex) {
            $this->db->rollBack();
            return $ex;
        }
        return $return;
    }

    public abstract function constroiEntidade($resultSet);
}
