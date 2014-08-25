<?php

namespace jaspion\DAO;

use jaspion\DAO\Conexao;
use jaspion\Models\Model;

/**
 *
 *
 * @author gilmario
 */
abstract class DAO {

    private $db;
    protected $table;
    protected $model;

    function __construct($conexao, Model $object, $table) {
        $this->db = Conexao::getDb($conexao);
        $this->model = $object;
        $this->table = $table;
    }

    public function salvar(Model $object) {
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
        return $this->executa("INSERT INTO {$this->table} ({$campos})VALUES({$valor})");
    }

    protected function executa($sql) {
        try {
            $resultado = $this->db->query($sql);
            if ($resultado) {
                return $resultado;
            } else {
                throw new \Exception("Erro de Sql " . $this->db->errorInfo(), 0, null);
            }
        } catch (\Exception $ex) {
            $this->db->rollBack();
            throw $ex;
        }
    }

    public function atualizar(Model $object, $where = null) {
        $dados = $object->setBanco();
        $where = ($where != null) ? "WHERE {$where}" : "";
        foreach ($dados as $ind => $val) {
            if (!is_int($val) || !is_double($val) || !is_float($val)) {
                $val = "'" . $val . "'";
            }
            $campos[] = "{$ind} = {$val}";
        }
        $campos = implode(', ', $campos);
        return $this->executa("UPDATE {$this->table} SET {$campos} {$where}");
    }

    public function deletar($where) {
        return $this->executa("DELETE FROM {$this->table} WHERE {$where}");
    }

    public function listar($where = null) {
        $where = ($where != null) ? "WHERE {$where}" : "";
        $q = $this->db->query("SELECT * FROM {$this->table} {$where}");
        return $this->arryToList($q);
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
        if (count($o) > 0) {
            return $o[0];
        } else {
            return null;
        }
    }

    public function consultar($campo, $nome = "") {
        $query = "SELECT * FROM {$this->table} WHERE {$campo} like '{$nome}%'";
        $row = $this->executa($query);
        $objects = $this->arryToList($row);
        return $objects;
    }

}
