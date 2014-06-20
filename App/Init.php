<?php

/**
 * Description of init
 *
 * @author gilmario
 */

namespace App;

use App\Init\Bootstrap;

class Init extends Bootstrap {

    function __construct() {
        parent::__construct();
    }

    public static function getDb() {
        $db = new \PDO("firebird:dbname=10.100.0.11:DBDSI1.GDB", 'SYSDBA', 'masterkey');
        $db->setAttribute(\PDO::ATTR_AUTOCOMMIT, false);
        return $db;
    }

}
