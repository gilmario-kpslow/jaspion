<?php

namespace jaspion\DAO;

use jaspion\DAO\WhereCriteria;

/**
 * Description of WhereCriteriaBuider
 *
 * @author allan
 */
class WhereCriteriaBuider {

    private $and;
    private $or;
    private $parametro;

    public function __construct() {
        $this->and = array();
        $this->or = array();
        $this->parametro = array();
    }

    public function addAnd(WhereCriteria $criteria) {
        $this->adicionaParametro($this->and, $criteria);
        return $this;
    }

    public function addOr(WhereCriteria $criteria) {
        $this->adicionaParametro($this->or, $criteria);
        return $this;
    }

    private function adicionaParametro(&$condicoes, WhereCriteria $criteria) {
        $condicoes[] = $criteria->getSql();
        if (!is_null($criteria->getParametro())) {
            foreach ($criteria->getParametro() as $key => $value) {
                $this->parametro[$key] = $value;
            }
        }
    }

    public function getStringWhere() {
        if (!empty($this->and) || !empty($this->or)) {
            $sql = " WHERE ";
            if (!empty($this->and)) {
                $sql .= $this->getAnd();
                if (!empty($this->or)) {
                    $sql .= " AND (";
                    $sql .= $this->getOr();
                    $sql .= ")";
                }
            } else {
                if (!empty($this->or)) {
                    $sql .= $this->getOr();
                }
            }
            return $sql;
        }
        return "";
    }

    public function getParametrosWhere() {
        return $this->parametro;
    }

    private function getAnd() {
        return implode(" AND ", $this->and);
    }

    private function getOr() {
        return implode(" OR ", $this->or);
    }

}
