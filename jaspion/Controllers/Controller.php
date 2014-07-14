<?php

namespace jaspion\Controllers;

use jaspion\Util\MensagemUtil;

/**
 * Description of Action
 *
 * @author gilmario
 */
class Controller {

    protected $view;
    protected $action;
    protected $mobile;
    private $script;
    private $mensagemService;

    function __construct() {
        $this->script = '';
        $this->view = new \stdClass();
        $this->getGlobais();
        $this->view->mensagem = "";
        $this->mobile = strstr($_SERVER['HTTP_USER_AGENT'], 'Mobile');
        $this->mensagemService = new MensagemUtil();
    }

    public function render($action, $layout = "layout") {
        $this->action = $action;
        if ($layout && file_exists("../App/Views/" . $layout . ".phtml") && !$this->mobile) {
            include_once '../App/Views/' . $layout . '.phtml';
        } else {
            $this->content();
        }
    }

    public function content() {
        $atual = get_class($this);
        $singleClassName = strtolower(str_replace("Controller", "", str_replace("App\\Controllers\\", "", $atual)));
        include_once '../App/Views/' . $singleClassName . "/" . $this->action . '.phtml';
    }

    public function css($filename) {
        return '<link href="' . DIR_ROOT . '/resources/css/' . $filename . '.css" rel="stylesheet"/>';
    }

    public function js($filename) {
        return '<script src="' . DIR_ROOT . "/resources/js/" . $filename . '.js"" type="text/javascript"></script>';
    }

    public function img($filename) {
        return DIR_ROOT . '/resources/images/' . $filename;
    }

    public function link($link) {
        return DIR_ROOT . $link;
    }

    public function scripts() {
        echo $this->script;
    }

    public function addScript($script) {
        if ($this->script == '') {
            $this->script = $this->js($script);
        } else {
            $this->script .= "\r\n" . $this->js($script);
        }
    }

    public function getGlobais() {
        $global = \jaspion\Init\Jaspion::getGlobais();
        if (!is_null($global)) {
            foreach ($global as $globais) {
                $this->criarGlobais($globais->nome, isset($globais->valor) ? $globais->valor : null, $globais->use);
            }
        }
    }

    public function criarGlobais($nome, $valor = null, $use = null) {
        if (!is_null($use) && !is_null($valor)) {
            $objeto = new $use();
            $this->view->$nome = $objeto->$valor();
        } else if (!is_null($use) && is_null($valor)) {
            $objeto = new $use();
            $this->view->$nome = $objeto;
        } else {
            $this->view->$nome = $valor;
        }
    }

    /**
     *
     * @param type $men
     * @param type $tipo (def)
     */
//    public function mensagem($men, $tipo = null) {
//        switch ($tipo) {
//            case 0:return $this->view->mensagem = "<div id='alerta' class='alert alert-success' style='text-align:center;'><button type='button' class='close' data-dismiss='alert'>×</button><span class='glyphicon glyphicon-exclamation-sign'></span> " . $men . "</div>";
//            case 1: return $this->view->mensagem = "<div id='alerta' class='alert alert-danger' style='text-align:center;'> <button type='button' class='close' data-dismiss='alert'>×</button><span class='glyphicon glyphicon-remove-sign'></span>  " . $men . "</div>";
//            case 2:return $this->view->mensagem = "<div id='alerta' class='alert alert-warning' style='text-align:center;'> <button type='button' class='close' data-dismiss='alert'>×</button><span class='glyphicon glyphicon-warning-sign'></span> " . $men . "</div>";
//            default :return $this->view->mensagem = "<div id='alerta' class='alert alert-info' style='text-align:center;'><button type='button' class='close' data-dismiss='alert'>×</button><span class='glyphicon glyphicon-info-sign'></span> " . $men . "</div>";
//        }
//    }
    /**
     *
     * @param type $nome
     * @param type $tipo (def)
     */
    public function mensagemCreate($nome, $tipo = null) {
        $men = $this->mensagemService->mensagem($nome);
        switch ($tipo) {
            case 0:return $this->view->mensagem = "<div id='alerta' class='alert alert-success' style='text-align:center;'><button type='button' class='close' data-dismiss='alert'>×</button><span class='glyphicon glyphicon-exclamation-sign'></span> " . $men . "</div>";
            case 1: return $this->view->mensagem = "<div id='alerta' class='alert alert-danger' style='text-align:center;'> <button type='button' class='close' data-dismiss='alert'>×</button><span class='glyphicon glyphicon-remove-sign'></span>  " . $men . "</div>";
            case 2:return $this->view->mensagem = "<div id='alerta' class='alert alert-warning' style='text-align:center;'> <button type='button' class='close' data-dismiss='alert'>×</button><span class='glyphicon glyphicon-warning-sign'></span> " . $men . "</div>";
            default :return $this->view->mensagem = "<div id='alerta' class='alert alert-info' style='text-align:center;'><button type='button' class='close' data-dismiss='alert'>×</button><span class='glyphicon glyphicon-info-sign'></span> " . $men . "</div>";
        }
    }

}
