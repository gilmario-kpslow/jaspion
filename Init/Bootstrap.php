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
        $controle = count($array) > 3 ? $array[2] : "jaspion";
        $acao = count($array) > 3 ? $array[3] : "index";
        $parametro = null;
        if (count($array) > 4) {
            $parametro = $this->carregaParametros($array);
        }
        $this->prepararController($controle, $acao, $parametro);
    }

    private function carregaParametros($arr) {
        $result = array();
        for ($i = 3; $i <= count($arr); $i++) {
            $result[] = $arr[$i];
        }
        return $result;
    }

    private function prepararController($controle, $acao, $parametro = null) {
        $class = "\App\\Controllers\\" . ucfirst($controle) . "Controller";
        $metodo = $acao . "Action";
        if (!class_exists($class) || !method_exists($class, $metodo)) {
            $this->erro404();
        } else {
            $this->verificarFiltros($class, $metodo, $parametro);
        }
    }

    private function verificarFiltros($controle, $acao, $parametro = null) {
        $this->executarMetodoController($controle, $acao, $parametro);
    }

    private function executarMetodoController($controle, $acao, $parametro = null) {
        $controller = new $controle();
        if ($parametro !== null) {
            $controller->$acao($parametro);
        } else {
            $controller->$acao();
        }
    }

    public function index() {
        $index = new \JaspionController();
        $index->index();
    }

    private function erro404() {
        $index = new \App\Controllers\IndexController();
        $index->erro404();
    }

    private function erro500($ex = null) {
        $index = new \App\Controllers\IndexController();
        $index->erro500($ex);
    }

    public static function getSistema() {
        return self::$sistema;
    }

    public static function getGlobais() {
        return self::$globais;
    }

}
