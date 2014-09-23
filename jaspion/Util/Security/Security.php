<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace jaspion\Util\Security;

/**
 * Description of Security
 *
 * @author allan
 */
class Security {

    private $userProvider;
    private $parametros;
    private static $session;
    private static $security;
    private static $user_name;

    public function __construct() {
        if (file_exists("../App/Config/security.json")) {
            $fileconfig = file_get_contents("../App/Config/security.json");
        } else {
            $fileconfig = file_get_contents("../vendor/jaspion/jaspion/Config/Security/security.json");
        }

        $this->parametros = json_decode($fileconfig);
    }

    public function setUser($username, $password) {
        $pdoClass = $this->parametros->security->userProviders;
        $this->userProvider = new $pdoClass();
        $user = $this->userProvider->getUser($username, $password);
        if (is_object($user)) {
            $user_token = $this->parametros->security->user_token;
            $user_name = $this->parametros->security->user;
            $this->getSession()->$user_token = $user->getToken();
            $this->getSession()->$user_name = $user;
            return true;
        } else {
            return false;
        }
    }

    public function getUser() {
        $user_name = $this->parametros->security->user;
        return $this->isUser() ? $this->getSession()->$user_name : false;
    }

    public function alterUser($user) {
        $user_token = $this->parametros->security->user_token;
        $user_name = $this->parametros->security->user;
        $this->getSession()->$user_token = $user->getToken();
        $this->getSession()->$user_name = $user;
    }

    public function isGranted($role) {
        if ($this->isUser()) {
            $roleUser = $this->getUser()->getRole();
            return strstr($this->parametros->security->hierarquiaRoles->$roleUser, $role) === false ? false : true;
        } else {
            return false;
        }
    }

    public function isUser() {
        $user_token = $this->parametros->security->user_token;
        $user_name = $this->parametros->security->user;
        $user = $this->getSession()->$user_name;
        return $user ? ($this->getSession()->$user_token == $user->getToken()) : false;
    }

    public function getSession() {
        if (!isset(self::$session)) {
            self::$session = new \jaspion\Util\RegistrySession();
        }
        return self::$session;
    }

    public static function getSecurity() {
        if (!isset(self::$security)) {
            self::$security = new self;
        }
        return self::$security;
    }

}
