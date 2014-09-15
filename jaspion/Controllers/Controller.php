<?php

namespace jaspion\Controllers;

use jaspion\Util\MensagemUtil;

/**
 * Description of Action
 *
 * @author gilmario
 */
class Controller {

    /**
     *
     * @var Guardar variáveis que vão sobre a pagina
     */
    protected $view;
    protected $action;
    private $script;
    private $_css;
    private $mensagemService;

    function __construct() {
        $this->script = '';
        $this->view = new \stdClass();
        $this->getGlobais();
        $this->view->mensagem = "";
        $this->mensagemService = new MensagemUtil();
    }

    /**
     * Renderizar uma página dentro do layout
     * @param $action
     * @param $layout
     */
    public function render($action, $layout = "layout") {
        $this->mensagemSessao();
        $this->action = $action;
        if ($layout && file_exists("../App/Views/" . $layout . ".phtml")) {
            include_once '../App/Views/' . $layout . '.phtml';
        } else {
            $this->content();
        }
    }

    /**
     * Renderizar uma pagina sem layout
     * @param $action
     */
    public function simpleRender($action) {
        $this->action = $action;
        $this->content();
    }

    /**
     * Carregar o conteudo da view pela página
     */
    public function content() {
        $atual = get_class($this);
        $singleClassName = strtolower(str_replace("Controller", "", str_replace("App\\Controllers\\", "", $atual)));
        $singleClassName = str_replace("\\", "/", $singleClassName);
        include_once '../App/Views/' . $singleClassName . "/" . $this->action . '.phtml';
    }

    /*
     * implementada para ser usada com 1 ou 2 parametro
     * caso 1 parametro : adicionar css que esta no resouces/css
     * caso 2 parametro : adicionar css que esta no na pasta que corresponde ao primero parametro
     */

    public function css() {
        switch (func_num_args()) {
            case 2:
                return '<link href="' . DIR_ROOT . '/resources/' . func_get_arg(0) . '/css/' . func_get_arg(1) . '.css" rel="stylesheet"/>';
            default:
                return '<link href="' . DIR_ROOT . '/resources/css/' . func_get_arg(0) . '.css" rel="stylesheet"/>';
        }
    }

    /*
     * implementada para ser usada com 1 ou 2 parametro
     * caso 1 parametro : adicionar js que esta no resouces/js
     * caso 2 parametro : adicionar js que esta no na pasta que corresponde ao primero parametro
     */

    public function js() {
        switch (func_num_args()) {
            case 2:
                return '<script src="' . DIR_ROOT . '/resources/' . func_get_arg(0) . '/js/' . func_get_arg(1) . '.js" type="text/javascript"></script>';
            default:
                return '<script src="' . DIR_ROOT . '/resources/js/' . func_get_arg(0) . '.js" type="text/javascript"></script>';
        }
    }

    /*
     * implementada para ser usada com 1 ou 2 parametro
     * caso 1 parametro : adicionar imagem que esta no resouces/images
     * caso 2 parametro : adicionar imagem que esta no na pasta que corresponde ao primero parametro
     */

    public function img() {
        switch (func_num_args()) {
            case 2:
                return DIR_ROOT . '/resources/' . func_get_arg(0) . '/images/' . func_get_arg(1);
            default:
                return DIR_ROOT . '/resources/images/' . func_get_arg(0);
        }
    }

    public function download() {
        switch (func_num_args()) {
            case 2:
                return DIR_ROOT . '/resources/' . func_get_arg(0) . '/downloads/' . func_get_arg(1);
            default:
                return DIR_ROOT . '/resources/downloads/' . func_get_arg(0);
        }
    }

    public function link($link) {
        return DIR_ROOT . $link;
    }

    public function scripts() {
        echo $this->script;
    }

    public function cssBlock() {
        echo $this->_css;
    }

    public function addScript($script) {
        if ($this->script == '') {
            $this->script = $this->js($script);
        } else {
            $this->script .= "\r\n" . $this->js($script);
        }
    }

    public function addAdd($css) {
        switch (func_num_args()) {
            case 2:
                if ($this->_css == '') {
                    $this->_css = $this->css(func_get_arg(0), func_get_arg(1));
                } else {
                    $this->_css .= "\r\n" . $this->css(func_get_arg(0), func_get_arg(1));
                }
                break;

            default :
                if ($this->_css == '') {
                    $this->_css = $this->css(func_get_arg(0));
                } else {
                    $this->_css .= "\r\n" . $this->css(func_get_arg(0));
                }
                break;
        }
    }

    public function getGlobais() {
        $global = \jaspion\Init\Jaspion::getGlobais();
        if (!is_null($global)) {
            foreach ($global as $globais) {
                $this->criarGlobais($globais->nome, isset($globais->valor) ? $globais->valor : null, isset($globais->use) ? $globais->use : null);
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
    public function mensagem($men, $tipo = null) {
        switch ($tipo) {
            case 0:return $this->view->mensagem = "<div id='alerta' class='alert alert-success' style='text-align:center;'><button type='button' class='close' data-dismiss='alert'>×</button><span class='glyphicon glyphicon-exclamation-sign'></span> " . $men . "</div>";
            case 1: return $this->view->mensagem = "<div id='alerta' class='alert alert-danger' style='text-align:center;'> <button type='button' class='close' data-dismiss='alert'>×</button><span class='glyphicon glyphicon-remove-sign'></span>  " . $men . "</div>";
            case 2:return $this->view->mensagem = "<div id='alerta' class='alert alert-warning' style='text-align:center;'> <button type='button' class='close' data-dismiss='alert'>×</button><span class='glyphicon glyphicon-warning-sign'></span> " . $men . "</div>";
            default :return $this->view->mensagem = "<div id='alerta' class='alert alert-info' style='text-align:center;'><button type='button' class='close' data-dismiss='alert'>×</button><span class='glyphicon glyphicon-info-sign'></span> " . $men . "</div>";
        }
    }

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

    public function mensagemSessao() {
        $session = new \jaspion\Util\RegistrySession();
        if ($session->success) {
            $this->view->mensagem = "<div id='alerta' class='alert alert-success' style='text-align:center;'><button type='button' class='close' data-dismiss='alert'>×</button><span class='glyphicon glyphicon-exclamation-sign'></span> " . $session->success . "</div>";
            $session->unSetRegistry('success');
        }

        if ($session->danger) {
            $this->view->mensagem = "<div id='alerta' class='alert alert-danger' style='text-align:center;'> <button type='button' class='close' data-dismiss='alert'>×</button><span class='glyphicon glyphicon-remove-sign'></span>  " . $session->danger . "</div>";
            $session->unSetRegistry('danger');
        }

        if ($session->warning) {
            $this->view->mensagem = "<div id='alerta' class='alert alert-warning' style='text-align:center;'> <button type='button' class='close' data-dismiss='alert'>×</button><span class='glyphicon glyphicon-warning-sign'></span> " . $session->warning . "</div>";
            $session->unSetRegistry('warning');
        }
        if ($session->info) {
            $this->view->mensagem = "<div id='alerta' class='alert alert-info' style='text-align:center;'><button type='button' class='close' data-dismiss='alert'>×</button><span class='glyphicon glyphicon-info-sign'></span> " . $session->info . "</div>";
            $session->unSetRegistry('info');
        }
    }

    public function mensagemCreateSessao($men, $tipo = null) {
        $session = new \jaspion\Util\RegistrySession();
        switch ($tipo) {
            case 0:return $session->success = $men;
            case 1: return $session->danger = $men;
            case 2:return $session->warning = $men;
            default :return $session->info = $men;
        }
    }

}
