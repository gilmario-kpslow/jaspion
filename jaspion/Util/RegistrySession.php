<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace jaspion\Util;

/**
 * Description of RegistrySession
 *
 * @author allan
 */
class RegistrySession {
    
    /**
     * @deprecated since version 1
     */
    public function __set($name, $value) {
        $_SESSION[$name] = serialize($value);
    }

    /**
     * @deprecated since version 1
     */
    public function __get($name) {
        if (isset($_SESSION[$name])) {
            return unserialize($_SESSION[$name]);
        } else {
            return false;
        }
    }

    public function unregisty() {
        session_destroy();
    }

    public function setSessao($k, $v) {
        $_SESSION[$k] = serialize($v);
    }

    public function getSessao($k) {
        if (isset($_SESSION[$name])) {
            return unserialize($_SESSION[$name]);
        } else {
            return false;
        }
    }

    public function unSetRegistry($name) {
        unset($_SESSION[$name]);
    }

}
