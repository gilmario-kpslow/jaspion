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
