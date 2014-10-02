<?php

namespace jaspion\Util;

use ReflectionClass;

/**
 * Description of JSonUtil
 *
 * @author gilmario
 */
class JSonUtil {

    public static function criaJson($nome, $valor) {
        $json = "{";
        if (is_object($valor)) {
            $json .= "\"{$nome}\" : " . JSonUtil::criaJSONObject($valor);
        } elseif (is_array($valor)) {
            $json .= "\"{$nome}\" : " . JSonUtil::criaJSONArray($valor);
        } else {
            $json .= JSonUtil::criaJSONvariavel($nome, $valor);
        }
        $json .= "}";
        return $json;
    }

    public static function criaJSONObject($object) {
        $json = "{";
        $reflection = new ReflectionClass(get_class($object));
        foreach ($reflection->getProperties() as $atributos) {
            $get = "get" . ucfirst($atributos->name);
            $json .= JSonUtil::criaJSONvariavel($atributos->name, $object->$get()) . ",";
        }
        $json = substr($json, 0, -1);
        $json .= "}";
        return $json;
    }

    public static function criaJSONArray($array) {
        $json = "[";
        foreach ($array as $key => $value) {
            $json .= JSonUtil::criaJson($key, $value) . ",";
        }
        $json = substr($json, 0, -1);
        $json .= "]";
        return $json;
    }

    private static function criaJSONvariavel($nome, $valor) {
        return "\"{$nome}\" : \"{$valor}\"";
    }

}
