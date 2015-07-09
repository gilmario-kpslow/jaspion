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
    private $tipo;
    private $order;

    public function __construct() {
        $this->and = array();
        $this->or = array();
        $this->parametro = array();
        $this->tipo = array();
        $this->order = array();
    }

    public function addAnd(WhereCriteria $criteria) {
        $this->adicionaParametro($this->and, $criteria);
        return $this;
    }

    public function addOrderBy(WhereCriteria $criteria) {
        $this->adicionaParametro($this->order, $criteria);
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
                $this->tipo[$key] = $criteria->getTipo()[$key];
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

            if (!empty($this->order)) {
                $sql .=" ORDER BY ";
                $sql .= $this->getOrder();
            }

            return $sql;
        }
        return "";
    }

    public function getParametrosWhere() {
        return $this->parametro;
    }

    public function getTiposWhere() {
        return $this->tipo;
    }

    private function getAnd() {
        return implode(" AND ", $this->and);
    }

    private function getOrder() {
        return implode(" , ", $this->order);
    }

    private function getOr() {
        return implode(" OR ", $this->or);
    }

}
