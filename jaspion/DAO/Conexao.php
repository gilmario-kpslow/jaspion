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
    
    protected static $pdo;

    public static function getDb($identificador) {
        $fileconfig = file_get_contents("../App/Config/conexoes.json");
        $parametros = json_decode($fileconfig);
        if(!isset(self::$pdo[$identificador]) || is_null(self::$pdo[$identificador])){
            foreach ($parametros->conexoes as $parametro) {
                if ($parametro->identificador == $identificador) {
                    $drive =  $parametro->driver;
                    $host = $parametro->host;
                    $dbname = $parametro->dbname;
                    $username = $parametro->username;
                    $password = $parametro->password;
                    break;
                }
            }
            self::$pdo[$identificador] = new \PDO($drive . ":host=" . $host . ";dbname=" . $dbname, $username, $password);
        }
        return self::$pdo[$identificador];
    }

}
