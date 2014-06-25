<?php

namespace jaspion\Init;

/**
 * Description of Bootstrap
 *
 * @author gilmario
 */
abstract class Bootstrap {
    
    protected static $sistema;
    protected static $globais;

    public function __construct() {
        $this->tempoSessao();
        $fileconfig = file_get_contents("../App/Config/parametros.json");
        $parametros = json_decode($fileconfig);
        
        self::$sistema = $parametros->sistema;
        foreach (self::$sistema as $sistema) {
            define('DIR_ROOT', $sistema->diretorioRaiz);
            self::$globais = isset($sistema->varGlobais[0]->nome) ? $sistema->varGlobais : null; 
        }
        
        $this->run($this->getUrl());
    }

    protected function getUrl() {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    protected function run($url) {
        $array = explode("/", $url);
        $controle = $array[2];
        $acao = "index";
        $parametro = null;
        if ($controle == "") {
            $controle = "index";
        }
        if (count($array) > 3) {
            $acao = $array[3];
        }
        if (count($array) > 4) {
            $parametro = $array[4];
        }
        if ($acao == "") {
            $acao = "index";
        }
        $this->executaAcao($controle, $acao, $parametro);
    }

    private function executaAcao($controle, $acao, $parametro = null) {
        if ($this->trataAcao($acao)) {
            $class = "App\\Controllers\\" . ucfirst($controle) . "Controller";
            if (!class_exists($class) || !method_exists($class, $acao)) {
                $this->erro404();
            } else {
                $this->executar($class, $acao, $parametro);
            }
        }
    }

    private function executar($controle, $acao, $parametro = null) {
        if ($this->exigeSeguranca($controle, $acao)) {
            if ($this->logado()) {
                $this->chamaAcao($controle, $acao, $parametro);
            } else {
                $controller = new \App\Controllers\UsuarioController();
                $controller->mensagem("Sua sessão foi encerrada.");
                $controller->login();
            }
        } else {
            $this->chamaAcao($controle, $acao, $parametro);
        }
    }

    private function chamaAcao($controle, $acao, $parametro = null) {
        $controller = new $controle();
        if ($parametro !== null) {
            $controller->$acao($parametro);
        } else {
            $controller->$acao();
        }
    }

    private function trataAcao($acao) {
        if ($acao === "logout" || $acao === "meucadastro") {
            return true;
        } else {
            return $this->seguranca();
        }
    }

    private function seguranca() {
        try {
            if (isset($_SESSION['senha']) && isset($_SESSION['usuario'])) {
                return $this->verificaCadastro();
            } else {
                return true;
            }
        } catch (Exception $ex) {
            $this->erro500($ex);
        }
    }

    public function index() {
        $index = new \App\Controllers\IndexController();
        $index->index();
    }

    private function verificaCadastro() {
        if ($_SESSION['senha'] === 'S') {
            $controle = new \App\Controllers\UsuarioController();
            $controle->mensagem("Você deve mudar sua senha para poder utilizar o sistema.");
            $controle->meucadastro();
            return false;
        } else {
            return true;
        }
    }

    private function erro404() {
        $index = new \App\Controllers\IndexController();
        $index->erro404();
    }

    private function erro500($ex = null) {
        $index = new \App\Controllers\IndexController();
        $index->erro500($ex);
    }

    private function exigeSeguranca($class, $acao) {
        $classe = new \ReflectionClass($class);
        $classeComent = $classe->getDocComment();
        if (strpos($classeComent, "@secured") !== false) {
            return true;
        } else {
            $metode = new \ReflectionMethod($class, $acao);
            $comentario = $metode->getDocComment();
            return strpos($comentario, "@secured") !== false;
        }
    }

    private function logado() {
        return isset($_SESSION['cpf']);
    }

    private function tempoSessao() {
        if (isset($_SESSION["TEMPO"])) {
            if ($_SESSION["TEMPO"] < (time() - 900)) {
                session_unset();
            }
        }
        $_SESSION["TEMPO"] = time();
    }
    
    public static function getSistema(){
        return self::$sistema;
    } 
    public static function getGlobais(){
        return self::$globais;
    }

}
