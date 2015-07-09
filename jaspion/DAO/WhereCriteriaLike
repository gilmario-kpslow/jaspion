<?php

namespace jaspion\DAO;

/**
 * Description of WhereCriteria
 *
 * @author allan
 */
class WhereCriteriaLike extends WhereCriteria {

    protected function __construct($campo, $operador, $valor = null, $tipo = \PDO::PARAM_STR) {
        parent::__construct($campo, $operador, $valor, $tipo);
        $this->sql.= "||'%'";
    }

}
