<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

/**
 * Description of Usuario
 *
 * @author gilmario
 */
class Usuario {

    private $cpf;
    private $senha;
    private $padrao;
    private $dataCriacao;

    function __construct() {

    }

    public function getCpf() {
        return $this->cpf;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function getDataCriacao() {
        return $this->dataCriacao;
    }

    public function setCpf($cpf) {
        $this->cpf = $cpf;
    }

    public function setSenha($senha) {
        $this->senha = md5($senha);
    }

    public function setDataCriacao($dataCriacao) {
        $this->dataCriacao = $dataCriacao;
    }

    public function getPadrao() {
        return $this->padrao;
    }

    public function setPadrao($padrao) {
        $this->padrao = $padrao;
    }

}
