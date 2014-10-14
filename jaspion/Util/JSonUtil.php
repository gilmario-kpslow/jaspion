<?php

namespace jaspion\Util;

use ReflectionClass;

/**
 * Description of JSonUtil
 *
 * @author gilmario
 */
class JSonUtil {

    public static function criaJson($valor, $nome = "valor") {
        if (is_object($valor)) {
            return JSonUtil::criaJSONObject($valor);
        } elseif (is_array($valor)) {
            return JSonUtil::criaJSONArray($valor);
        } else {
            return "{\"{$nome}\":\"{$valor}\"}";
        }
    }

    private static function criaJSONObject($object) {
        $json = "{";
        $reflection = new ReflectionClass(get_class($object));
        foreach ($reflection->getProperties() as $atributos) {
            $get = "get" . ucfirst($atributos->name);
            $json .= JSonUtil::criaJSONvariavel($atributos->name, $object->$get()) . ",";
        }
        return substr($json, 0, -1) . "}";
    }

    private static function criaJSONArray($array) {
        $json = "[";
        foreach ($array as $value) {
            if (is_array($value)) {
                $json .= JSonUtil::criaJSONArray($value) . ",";
            } elseif (is_object($value)) {
                $json .= JSonUtil::criaJSONObject($value) . ",";
            } else {
                $json .= "\"$value\"" . ",";
            }
        }
        return substr($json, 0, -1) . "]";
    }

    private static function criaJSONvariavel($nome, $valor) {
        if (is_array($valor)) {
            return "\"{$nome}\" : " . JSonUtil::criaJSONArray($valor);
        } else if (is_object($valor)) {
            return "\"{$nome}\" : " . JSonUtil::criaJSONObject($valor);
        } else {
            return "\"{$nome}\" : \"{$valor}\"";
        }
    }

}
