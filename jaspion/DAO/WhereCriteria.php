<?php

namespace jaspion\DAO;

/**
 * Description of WhereCriteria
 *
 * @author allan
 */
class WhereCriteria {

    const IGUAL = "=";
    const DIFERENTE = "<>";
    const MAIOR = ">";
    const MENOR = "<";
    const MAIORIGUAL = ">=";
    const MENORIGUAL = "<=";
    const IS_NULL = "IS NULL";
    const IS_NOT_NULL = "IS NOT NULL";
    const LIKE = "LIKE";

    private $sql;
    private $parametro;

    private function __construct($campo, $operador, $valor = null) {
        if (is_null($valor)) {
            $this->sql = $campo . " " . $operador;
        } else {
            $parametroCampo = ":" . strtolower($campo);
            $this->sql = $campo . " " . $operador . " " . $parametroCampo;
            $this->parametro = array($parametroCampo => $valor);
        }
    }

    function getSql() {
        return $this->sql;
    }<?php

namespace jaspion\DAO;

/**
 * Description of WhereCriteria
 *
 * @author allan
 */
class WhereCriteria {

    const IGUAL = "=";
    const DIFERENTE = "<>";
    const MAIOR = ">";
    const MENOR = "<";
    const MAIORIGUAL = ">=";
    const MENORIGUAL = "<=";
    const IS_NULL = "IS NULL";
    const IS_NOT_NULL = "IS NOT NULL";
    const LIKE = "LIKE";
    const STARTING_WITH = "STARTING WITH";
    const ASC = "ASC";
    const DESC = "DESC";

    protected $sql;
    private $parametro;
    private $tipo;

    protected function __construct($campo, $operador, $valor = null, $tipo = \PDO::PARAM_STR) {
        $campo = trim($campo);
        if (is_null($valor)) {
            $this->sql = $campo . " " . $operador;
        } else {
            $parametroCampo = ":" . strtolower($campo);
            $this->sql = $campo . " " . $operador . " " . $parametroCampo;
            $this->parametro = array($parametroCampo => $valor);
            $this->tipo = array($parametroCampo => $tipo);
        }
    }

    function getSql() {
        return $this->sql;
    }

    function getParametro() {
        return $this->parametro;
    }

    function getTipo() {
        return $this->tipo;
    }

    public static function addIgual($campo, $valor, $tipo = \PDO::PARAM_STR) {
        return new WhereCriteria($campo, self::IGUAL, $valor, $tipo);
    }

    private static function addOrder($campo, $asc) {
        return new WhereCriteria($campo, $asc);
    }

    public static function addOrderAsc($campo) {
        return self::addOrder($campo, self::ASC);
    }

    public static function addOrderDesc($campo) {
        return self::addOrder($campo, self::DESC);
    }

    public static function addMaiorQue($campo, $valor, $tipo = \PDO::PARAM_STR) {
        return new WhereCriteria($campo, self::MAIOR, $valor, $tipo);
    }

    public static function addMenorQue($campo, $valor, $tipo = \PDO::PARAM_STR) {
        return new WhereCriteria($campo, self::MENOR, $valor, $tipo);
    }

    public static function addMaiorIgual($campo, $valor, $tipo = \PDO::PARAM_STR) {
        return new WhereCriteria($campo, self::MAIORIGUAL, $valor, $tipo);
    }

    public static function addMenorIgual($campo, $valor, $tipo = \PDO::PARAM_STR) {
        return new WhereCriteria($campo, self::MENORIGUAL, $valor, $tipo);
    }

    public static function addIsNull($campo) {
        return new WhereCriteria($campo, self::IS_NULL, null);
    }

    public static function addIsNotNull($campo) {
        return new WhereCriteria($campo, self::IS_NOT_NULL, null);
    }

    public static function addDiferente($campo, $valor) {
        return new WhereCriteria($campo, self::DIFERENTE, $valor, $tipo = \PDO::PARAM_STR);
    }

    public static function addLike($campo, $valor) {
        return new WhereCriteriaLike($campo, self::LIKE, $valor);
    }

    public static function addStartingWith($campo, $valor) {
        return new WhereCriteria($campo, self::STARTING_WITH, $valor);
    }

}


    function getParametro() {
        return $this->parametro;
    }

    public static function addIgual($campo, $valor) {
        return new WhereCriteria($campo, self::IGUAL, $valor);
    }

    public static function addMaiorQue($campo, $valor) {
        return new WhereCriteria($campo, self::MAIOR, $valor);
    }

    public static function addMenorQue($campo, $valor) {
        return new WhereCriteria($campo, self::MENOR, $valor);
    }

    public static function addMaiorIgual($campo, $valor) {
        return new WhereCriteria($campo, self::MAIORIGUAL, $valor);
    }

    public static function addMenorIgual($campo, $valor) {
        return new WhereCriteria($campo, self::MENORIGUAL, $valor);
    }

    public static function addIsNull($campo, $valor) {
        return new WhereCriteria($campo, self::IS_NULL, null);
    }

    public static function addIsNotNull($campo, $valor) {
        return new WhereCriteria($campo, self::IS_NOT_NULL, null);
    }

    public static function addDiferente($campo, $valor) {
        return new WhereCriteria($campo, self::DIFERENTE, $valor);
    }

    public static function addLike($campo, $valor) {
        return new WhereCriteria($campo, self::LIKE, $valor);
    }

}
