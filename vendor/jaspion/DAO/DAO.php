<?php

namespace jaspion\DAO;

use jaspion\DAO\Conexao;

/**
 * Description of DAO
 *
 * @author gilmario
 */
abstract class DAO {

    protected $db;
    protected $table;

    function __construct($conexao) {
        $this->db = Conexao::getDb($conexao);
    }

    public function salvar(Array $dados) {
        try {
            $this->db->beginTransaction();
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
            $this->db->commit();
        } catch (PDOException $ex) {
            $this->db->rollBack();
            return $ex;
        }
    }

    public function atualizar(Array $dados, $where = null) {
        try {
            $this->db->beginTransaction();
            $where = ($where != null) ? "WHERE {$where}" : "";
            foreach ($dados as $ind => $val) {
                if (!is_int($val) || !is_double($val) || !is_float($val)) {
                    $val = "'" . $val . "'";
                }

                $campos[] = "{$ind} = {$val}";
            }
            $campos = implode(', ', $campos);
            $this->db->query("UPDATE {$this->getTabela()} SET {$campos} {$where}");
            $this->db->commit();
        } catch (PDOException $ex) {
            $this->db->rollBack();
            return $ex;
        }
    }

    public function deletar($where) {
        try {
            $this->db->beginTransaction();
            $this->db->query("DELETE FROM {$this->getTabela()} WHERE {$where}");
            $this->db->commit();
        } catch (PDOException $ex) {
            $this->db->rollBack();
            return $ex;
        }
    }

    public function listar($where = null) {
        try {
            $this->db->beginTransaction();
            $where = ($where != null) ? "WHERE {$where}" : "";
            $q = $this->db->query("SELECT * FROM {$this->getTabela()} {$where}");
            $this->db->commit();
            return $q->fetchAll();
        } catch (PDOException $ex) {
            $this->db->rollBack();
            return $ex;
        }
    }

    public function carregar($campo, $id) {
        return $this->listar("{$campo} = '{$id}'");
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

}
