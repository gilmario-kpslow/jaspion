<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Converter
 *
 * @author gilmario
 */

namespace jaspion\Util;

class Converter {

    public static function stringParaDouble($valor) {
        if ($valor == '') {
            $valor = 0;
        }
        $valor = str_replace(".", "", $valor);
        $valor = str_replace(",", ".", $valor);
        return number_format($valor, 2, '.', '');
    }

    public static function stringParaInteger($valor) {
        $valor = str_replace(".", "", $valor);
        $valor = str_replace(",", ".", $valor);
        return intval($valor);
    }

    public static function stringParaData($data) {
        //dd/mm/yyyy
        if (strlen($data) >= 10) {
            $dataarray = explode("/", $data);
            return $dataarray[2] . "-" . $dataarray[1] . "-" . $dataarray[0];
        } else {
            return null;
        }
    }

    public static function stringParaDataTime($data) {
        //dd/mm/yyyy hh:mm:ss
        if (strlen($data) >= 16) {
            $dataarray = explode(" ", $data);
            return Converter::stringParaData($dataarray[0]) . " " . $dataarray[1];
        } else {
            return null;
        }
    }

}
