<?php

namespace jaspion\DAO;

use jaspion\DAO\Conexao;
use jaspion\Container\ModelContainer;

/**
 *
 *
 * @author gilmario
 */
abstract class DAO {

    private $db;
    protected $model;

    /**
     *
     * @param type $conexao
     * @param ModelContainer $container
     */
    function __construct($conexao, ModelContainer $container) {
        $this->db = Conexao::getDb($conexao);
        $this->model = $container;
    }

    /**
     *
     * @param type $object
     * @return type
     */
    public function salvar($object) {
        $dados = $this->model->getBanco($object);
        $campos = implode(',', array_keys($dados));
        $valores = array_values($dados);
        foreach ($valores as $value) {
            if (is_int($value) || is_double($value) || is_float($value) || is_bool($value)) {
                $valor[] = $value;
            } else {
                $value = str_replace("'", "", $value);
                $valor[] = "'" . $value . "'";
            }
        }
        $valor = implode(',', $valor);
        return $this->executa("INSERT INTO {$this->model->getTable()} ({$campos})VALUES({$valor})");
    }

    /**
     *
     * @param type $object
     * @param type $where
     * @return type
     */
    public function atualizar($object, $where) {
        $dados = $this->model->getBanco($object);
        $condicao = "WHERE {$where}";
        foreach ($dados as $ind => $val) {
            if (!is_int($val) || !is_double($val) || !is_float($val) || is_bool($val)) {
                $val = "'" . $val . "'";
            }
            $campos[] = "{$ind} = {$val}";
        }
        $campos = implode(', ', $campos);
        return $this->executa("UPDATE {$this->model->getTable()} SET {$campos} {$condicao}");
    }

    /**
     *
     * @param type $sql
     * @return type
     * @throws \Exception
     */
    public function executa($sql) {
        try {
            $resultado = $this->db->query($sql);
            if ($resultado) {
                return $resultado;
            } else {
                throw new \Exception("Erro de Sql " . $sql . "\r\n" . $this->geraErro($this->db->errorInfo()), 0, null);
            }
        } catch (\Exception $ex) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $ex;
        }
    }

    /**
     *
     * @param type $array
     * @return type
     */
    private function geraErro($array) {
        $erro = "";
        foreach ($array as $ex) {
            $erro .= $ex;
        }
        return $erro;
    }

    /**
     *
     * @param type $where
     * @return type
     */
    public function deletar($where) {
        return $this->executa("DELETE FROM {$this->model->getTable()} WHERE {$where}");
    }

    /**
     *
     * @param type $where
     * @return type
     */
    public function listar($where = null) {
        $condicao = ($where != null) ? "WHERE {$where}" : "";
        $q = $this->executa("SELECT * FROM {$this->model->getTable()} {$condicao}");
        return $this->arryToList($q);
    }

    /**
     *
     * @param type $q
     * @return type
     */
    protected function arryToList($q) {
        $objects = array();
        foreach ($q->fetchAll(\PDO::FETCH_ASSOC) as $rs) {
            $objects[] = $this->model->popularBanco($rs);
        }
        return $objects;
    }

    /**
     *
     * @param type $id
     * @return type
     */
    public function carregar($id) {
        $row = $this->executa("SELECT * FROM {$this->model->getTable()} WHERE {$id};");
        $result = $row->fetch(\PDO::FETCH_ASSOC);
        if ($result) {
            return $this->model->popularBanco($result);
        } else {
            return null;
        }
    }

    /**
     *
     * @return type
     */
    protected function getDb() {
        return $this->db;
    }

}
