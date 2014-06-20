<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controllers;

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

    public function scripts() {
        echo $this->script;
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

    protected function criaJSONObject($classe, $objeto) {
        $json = "{";
        $metodos = get_class_methods($classe);
        foreach ($metodos as $key => $value) {
            $r = strpos($value, "get");
            if (strpos($value, "get") !== false) {
                $var = lcfirst(str_replace("get", "", $value));
                $json .= "\"{$var}\" : \"{$objeto->$value()}\",";
            }
        }
        $json = substr($json, 0, -1);
        $json .= "}";
        echo utf8_encode($json);
    }

}
