<?php

namespace jaspion\Util;

/*
 * Esta classe e responsavel por manipula as anotações de outras classes
 */
use ReflectionClass;
use ReflectionProperty;
/**
 * Description of Util
 *
 * @author allan
 */
class AnotacaoUtil {
    /*
     * Este metodo retorna um array com todas a anotações e seu valores, dos atributos
     * de um Objeto 
     */

    public static function gerarArraydeAnotacaoAtributo($object) {
        try {
            $reflection = new ReflectionClass(get_class($object));
            foreach ($reflection->getProperties() as $atributos) {
                $prop = new ReflectionProperty($reflection->getName(), $atributos->getName());
                $comment = $prop->getDocComment();
                $arrComment = array();
                $expressao = '/@[\w]+[ ]?=[ ]?[\w]+/';
                preg_match_all($expressao, $comment, $arrComment);
                foreach ($arrComment[0] as $value) {
                    $parametro = explode("=", str_replace("@", "", $value));
                    $anotacoes[$atributos->getName()][trim($parametro[0])] = trim($parametro[1]);
                }
            }
            return $anotacoes;
        } catch (Exception $exc) {
            return null;
        }
    }

}
