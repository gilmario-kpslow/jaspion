<?php

namespace jaspion\Util;

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

}
