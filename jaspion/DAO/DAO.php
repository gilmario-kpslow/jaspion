<?php

namespace jaspion\DAO;

use jaspion\DAO\Conexao;
use jaspion\Models\Model;

/**
 * Description of DAO
 *
 * @author gilmario
 */
abstract class DAO {

    protected $db;
    protected $table;
    protected $model;

    function __construct($conexao, Model $object) {
        $this->db = Conexao::getDb($conexao);
        $this->model = $object;
    }

    public function salvar(Model $object) {
        try {
            $dados = $object->setBanco();
            $campos = implode(',', array_keys($dados));
            $valores = array_values($dados);
            foreach ($valores as $value) {
                if (is_int($value) || is_double($value) || is_float($value)) {
                    $valor[] = $value;
                } else {
                    $value = str_replace("'", "", $value);
                    $valor[] = "'" . $value . "'";
                }
            }
            $valor = implode(',', $valor);

            $this->db->query("INSERT INTO {$this->table} ({$campos})VALUES({$valor})");
        } catch (PDOException $ex) {
            $this->db->rollBack();
            return $ex;
        }
    }

    public function atualizar(Model $object, $where = null) {
        try {
            $dados = $object->setBanco();
            $where = ($where != null) ? "WHERE {$where}" : "";
            foreach ($dados as $ind => $val) {
                if (!is_int($val) || !is_double($val) || !is_float($val)) {
                    $val = "'" . $val . "'";
                }

                $campos[] = "{$ind} = {$val}";
            }
            $campos = implode(', ', $campos);
            $this->db->query("UPDATE {$this->table} SET {$campos} {$where}");
        } catch (PDOException $ex) {
            $this->db->rollBack();
            return $ex;
        }
    }

    public function deletar($where) {
        try {
            $this->db->query("DELETE FROM {$this->table} WHERE {$where}");
        } catch (PDOException $ex) {
            $this->db->rollBack();
            return $ex;
        }
    }

    public function listar($where = null) {
        try {
            $where = ($where != null) ? "WHERE {$where}" : "";
            $q = $this->db->query("SELECT * FROM {$this->table} {$where}");
            if ($q) {
                return $this->arryToList($q);
            } else {
                return null;
            }
        } catch (PDOException $ex) {
            $this->db->rollBack();
            return $ex;
        }
    }

    protected function arryToList($q) {
        $objects = array();
        foreach ($q->fetchAll() as $rs) {
            $this->model = new $this->model;
            $this->model->popularBanco($rs);
            $objects[] = $this->model;
        }
        return $objects;
    }

    public function carregar($campo, $id) {
        $o = $this->listar("{$campo} = '{$id}'");
        if ($o) {
            return $o[0];
        } else {
            return null;
        }
    }

    public function consultar($campo, $nome = "") {
        try {
            $query = "SELECT * FROM {$this->table} WHERE {$campo} like '{$nome}%'";
            $row = $this->db->query($query);
            $objects = array();
            foreach ($row->fetchAll() as $rs) {
                $this->model->popularBanco($rs);
                $objects[] = $this->model;
            }
            return $objects;
        } catch (PDOException $ex) {
            $this->db->rollBack();
            return $ex;
        }
    }

}
