<?php

namespace jaspion\DAO;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Conexao
 *
 * @author allan
 */
class Conexao {

    public static function getDb($identificador) {
        $fileconfig = file_get_contents("../App/Config/conexoes.json");
        $parametros = json_decode($fileconfig);

        foreach ($parametros->conexoes as $parametro) {
            if ($parametro->identificador == $identificador) {
                define('PDO_DRIVE', $parametro->driver);
                define('PDO_HOST', $parametro->host);
                define('PDO_DBNAME', $parametro->dbname);
                define('PDO_USERNAME', $parametro->username);
                define('PDO_PASSWORD', $parametro->password);
                break;
            }
        }
        $db = new \PDO(PDO_DRIVE . ":host=" . PDO_HOST . ";dbname=" . PDO_DBNAME, PDO_USERNAME, PDO_PASSWORD);
        $db->setAttribute(\PDO::ATTR_AUTOCOMMIT, false);
        return $db;
    }

}
