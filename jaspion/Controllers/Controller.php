<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace jaspion\Controllers;

/**
 * Description of Action
 *
 * @author gilmario
 */
class Controller {

    protected $view;
    protected $action;
    protected $mobile;
    protected $script = "";

    function __construct() {
        $this->view = new \stdClass();
        $this->getGlobais();
        $this->view->mensagem = "";
        $this->mobile = strstr($_SERVER['HTTP_USER_AGENT'], 'Mobile');
    }

    public function render($action, $layout = true) {
        $this->action = $action;
        if ($layout && file_exists("../App/Views/layout.phtml") && !$this->mobile) {
            include_once '../App/Views/layout.phtml';
        } elseif ($layout && file_exists("../App/Views/mobilayout.phtml") && $this->mobile) {
            include_once '../App/Views/mobilayout.phtml';
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
        return DIR_ROOT . '/resources/css/' . $filename . '.css';
    }

    public function js($filename) {
        return DIR_ROOT . '/resources/js/' . $filename . '.js';
    }

    public function img($filename) {
        return DIR_ROOT . '/resources/images/' . $filename;
    }

    public function scripts() {
        echo $this->script;
    }

    public function getGlobais() {
        $global = \jaspion\Init\Bootstrap::getGlobais();
        if (!is_null($global)) {
            foreach ($global as $globais) {
                $this->criarGlobais($globais->nome, $globais->valor, $globais->use);
            }
        }
    }

    public function criarGlobais($nome, $valor, $use = null) {
        if ($use != null) {
            $objeto = new $use();
            $this->view->$nome = $objeto->$valor();
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
            case 0:return $this->view->mensagem = "<div id='alerta' class='alert alert-success' style='text-align:center;'><button type='button' class='close' data-dismiss='alert'>×</button>" . $men . "</div>";
            case 1: return $this->view->mensagem = "<div id='alerta' class='alert alert-danger' style='text-align:center;'> <button type='button' class='close' data-dismiss='alert'>×</button>" . $men . "</div>";
            case 2:return $this->view->mensagem = "<div id='alerta' class='alert alert-warning' style='text-align:center;'> <button type='button' class='close' data-dismiss='alert'>×</button>" . $men . "</div>";
            default :return $this->view->mensagem = "<div id='alerta' class='alert alert-info' style='text-align:center;'><button type='button' class='close' data-dismiss='alert'>×</button>" . $men . "</div>";
        }
    }

}
