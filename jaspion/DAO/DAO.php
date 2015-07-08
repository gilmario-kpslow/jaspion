<?php

namespace jaspion\DAO;

use jaspion\DAO\Conexao;
use jaspion\Models\Model;
use jaspion\DAO\WhereCriteria;
use jaspion\DAO\WhereCriteriaBuider;

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
        $parametros = array_values($dados);
        foreach ($parametros as $value) {
            $valores[] = '?';
        }
        $valor = implode(',', $valores);
        $insert = $this->db->prepare("INSERT INTO {$this->table} ({$campos})VALUES({$valor})");
        $result = $insert->execute($parametros);

        if (!$result) {
            throw new \Exception("Erro de Sql " . $this->geraErro($this->db->errorInfo()), 0, null);
        }
    }

    protected function executa($sql) {
        try {
            $resultado = $this->db->query($sql);
            if ($resultado) {
                return $resultado;
            } else {
                throw new \Exception("Erro de Sql " . $this->geraErro($this->db->errorInfo()), 0, null);
            }
        } catch (\Exception $ex) {
            $this->db->rollBack();
            throw $ex;
        }
    }

    private function geraErro($array) {
        $erro = "";
        foreach ($array as $ex) {
            $erro .= $ex;
        }
        return $erro;
    }

    public function atualizar(Model $object, Array $where) {
        $dados = $object->setBanco();
        $parametro = array();

        foreach ($dados as $ind => $val) {
            if (!is_int($val) || !is_double($val) || !is_float($val)) {
                $val = "'" . $val . "'";
            }
            $campos[] = "{$ind} = ?";
            $parametro[] = $val;
        }
        $campos = implode(', ', $campos);

        foreach ($where as $cp => $vl) {
            $condicao[] = "{$cp} = ?";
            $parametro[] = $vl;
        }
        $condicao = implode(" AND ", $condicao);
        $upd = $this->db->prepare("UPDATE {$this->table} SET {$campos} WHERE {$condicao}");
        $result = $upd->execute($parametro);
        if (!$result) {
            throw new \Exception("Erro de Sql " . $this->geraErro($this->db->errorInfo()), 0, null);
        }
    }

    public function deletar($where) {
        $where = (!is_null($where)) ? $criteriaBuilder->getStringWhere() : "";
        $parametos = (!is_null($where)) ? $criteriaBuilder->getParametrosWhere() : null;
        $q = $this->db->prepare("DELETE FROM {$this->table} WHERE {$where}");
        return $q->execute($parametos);
    }

    /**
     *
     * @deprecated since version 1
     */
    public function listar($where = null) {
        $where = ($where != null) ? "WHERE {$where}" : "";
        $q = $this->executa("SELECT * FROM {$this->table} {$where}");
        return $this->arryToList($q);
    }

    public function listarCriteria(WhereCriteriaBuider $criteriaBuilder = null) {
        $where = (!is_null($criteriaBuilder)) ? $criteriaBuilder->getStringWhere() : "";
        $parametos = (!is_null($criteriaBuilder)) ? $criteriaBuilder->getParametrosWhere() : null;
        $sql = "SELECT * FROM {$this->table} {$where}";
        if ($where != "") {
            $q = $this->db->prepare($sql);
            foreach ($parametos as $k => $v) {
                $q->bindParam($k, $v);
            }
            $q->execute();
        } else {
            $q = $this->db->query($sql);
        }
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

    /**
     *
     * @deprecated since version 1
     */
    public function carregar($campo, $id) {
        $o = $this->listar("{$campo} = '{$id}'");
        if (count($o) > 0) {
            return $o[0];
        } else {
            return null;
        }
    }

    public function carregarCriteria($campo, $id) {
        $criteriaBuilder = new WhereCriteriaBuider();
        $criteriaBuilder->addAnd(WhereCriteria::addIgual($campo, $id));
        $o = $this->listar($criteriaBuilder);

        if (count($o) > 0) {
            return $o[0];
        } else {
            return null;
        }
    }

    public function beginTransaction() {
        $this->db->beginTransaction();
    }

    public function commit() {
        $this->db->commit();
    }

    public function rollBack() {
        $this->db->rollBack();
    }

}
