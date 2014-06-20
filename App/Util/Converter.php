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

namespace SEFIN\UTIL;

class Converter {

    public static function stringParaDouble($valor) {
        "99.999,99";
        $valor = str_replace(".", "", $valor);
        $valor = str_replace(",", ".", $valor);
        return floatval($valor);
    }

    public static function stringParaData($data) {
        if (strlen($data) === 10) {
            $dia = substr($data, 0, 2);
            $mes = substr($data, 3, 2);
            $ano = substr($data, 6, 4);
            $dataC = $ano . "-" . $mes . "-" . $dia;
            return $dataC;
        } else {
            return null;
        }
    }

    public static function colocaMascaraCPF($cpf) {
        return substr($cpf, 0, 3) . "." . substr($cpf, 3, 3) . "." . substr($cpf, 6, 3) . "-" . substr($cpf, 9, 2);
    }

    public static function doubleParaString($valor) {
        return number_format($valor, 2, ',', '.');
    }

    public static function dataParaString($data) {
        $arrayData = explode('-', $data);
        krsort($arrayData);
        $dataC = implode('/', $arrayData);
        return $dataC;
    }

    public static function dataTimeParaString($dataTime) {
        $data = self::dataParaString(substr($dataTime, 0, 10));
        return $data; //. ' ' . substr($dataTime, 10);
    }

    public static function mesExtenco($num) {
        $meses = array(1 => "Janeiro", 2 => "Fevereiro", 3 => "Março", 4 => "Abril", 5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto", 9 => "Setembro", 10 => "Outubro", 11 => "Novembro", 12 => "Dezembro");
        return $meses[$num];
    }

    public static function trataMascara($campo) {
        if (strlen($campo) > 0) {
            $campo = str_replace(".", "", $campo);
            $campo = str_replace("(", "", $campo);
            $campo = str_replace(")", "", $campo);
            $campo = str_replace(" ", "", $campo);
            $campo = str_replace(" ", "", $campo);
            $campo = str_replace(" ", "", $campo);
            $campo = str_replace(" ", "", $campo);
            $campo = str_replace(" ", "", $campo);
            $campo = str_replace(" ", "", $campo);
            $campo = str_replace("/", "", $campo);
            $campo = str_replace("-", "", $campo);
        }
        return $campo;
    }

}
