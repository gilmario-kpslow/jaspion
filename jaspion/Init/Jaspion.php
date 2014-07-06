<?php

namespace jaspion\Init;

/**
 * Description of Jaspion
 *
 * @author gilmario
 */
class Jaspion {

    protected static $sistema;
    protected static $globais;
    private $filtros = array();

    public function __construct() {
        $fileconfig = file_get_contents("../App/Config/parametros.json");
        $parametros = json_decode($fileconfig);
        $this->criarFiltros();
        self::$sistema = $parametros->sistema[0];
        define('DIR_ROOT', self::$sistema->diretorioRaiz);
        self::$globais = isset(self::$sistema->varGlobais[0]->nome) ? self::$sistema->varGlobais : null;
        $this->run($this->getUrl());
    }

    protected function getUrl() {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    protected function run($url) {
        $array = explode("/", $url);
        $controle = count($array) > 2 ? ($array[2] != '' ? $array[2] : 'index') : "index";
        $acao = count($array) > 3 ? $array[3] : "index";
        $parametro = null;
        if (count($array) > 4) {
            $parametro = $this->carregaParametros($array);
        }
        $this->prepararController($controle, $acao, $parametro);
    }

    private function carregaParametros($arr) {
        $result = array();
        for ($i = 4; $i < count($arr); $i++) {
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
        if (count($this->filtros) > 0) {
            foreach ($this->filtros->filtro as $filtroName) {
                $anotationClasse = $filtroName->regra[0]->anotationClass;
                $anotationMetodo = $filtroName->regra[0]->anotationMethod;
                $inClasse = false;
                if ($anotationClasse == '*') {
                    $inClasse = true;
                } else if ($anotationClasse != '') {
                    $arrClas = \jaspion\Util\AnotacaoUtil::gerarArraydeAnotacaoClasse($controle);
                    $inClasse = in_array($anotationClasse, $arrClas);
                } if ($anotationMetodo != '') {
                    $arrMet = \jaspion\Util\AnotacaoUtil::gerarArraydeAnotacaoMetodo($controle, $acao);
                    $inClasse = in_array($anotationMetodo, $arrMet);
                }
                if ($inClasse) {
                    $filtroN = $filtroName->classe;
                    $filtro = new $filtroN();
                    $this->aplicaFiltro($filtro, $controle, $acao, $parametro);
                }
            }
            $this->executarMetodoController($controle, $acao, $parametro);
        } else {
            $this->executarMetodoController($controle, $acao, $parametro);
        }
    }

    private function aplicaFiltro($filtro, $controle, $acao, $parametro = null) {
        if (!$filtro->filtrar($controle, $acao, $parametro)) {
            $filtro->erro($controle, $acao, $parametro);
            exit();
        }
    }

    private function executarMetodoController($controle, $acao, $parametro = null) {
        $controller = new $controle();
        if ($parametro !== null) {
            if (count($parametro) == 1) {
                $controller->$acao($parametro[0]);
            }
        } else {
            $controller->$acao();
        }
    }

    private function erro404() {
        $indexClasse = self::$sistema->baseController[0]->classe;
        $erro404 = self::$sistema->baseController[0]->erro404;
        $index = new $indexClasse();
        $index->$erro404();
    }

    private function erro500($ex = null) {
        $indexClasse = self::$sistema->baseController[0]->classe;
        $erro500 = self::$sistema->baseController[0]->erro500;
        $index = new $indexClasse();
        $index->$erro500();
    }

    public static function getSistema() {
        return self::$sistema;
    }

    public static function getGlobais() {
        return self::$globais;
    }

    /**
     * Inicializar os filtros a partir da con figuração App/Config/filtros
     */
    private function criarFiltros() {
        $filtros = file_get_contents("../App/Config/filtros.json");
        $this->filtros = json_decode($filtros);
    }

}
