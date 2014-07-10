<?php

namespace jaspion\Util;

use ReflectionClass;

/**
 * Description of JSonUtil
 *
 * @author gilmario
 */
class JSonUtil {

    public static function criaJson($array) {
        $json = "{";
        foreach ($array as $key => $value) {
            $json .= "\"{$key}\" : \"{$value}\",";
        }
        $json = substr($json, 0, -1);
        $json .= "}";

        return utf8_encode($json);
    }

    public static function criaJSONObject($object) {
        $json = "{";
        $reflection = new ReflectionClass(get_class($object));
        foreach ($reflection->getProperties() as $atributos) {
            $get = "get" . ucfirst($atributos->name);
            $json .= "\"{$atributos->name}\" : \"{$object->$get()}\",";
        }
        $json = substr($json, 0, -1);
        $json .= "}";
        return $json;
    }

    public static function criaJSONObjectArray($array) {
        $json = "{ \"lista\": [";
        foreach ($array as $value) {
            $json .= self::criaJSONObject($value) . ",";
        }
        $json = substr($json, 0, -1);
        $json .= "]}";
        return $json;
    }

}
