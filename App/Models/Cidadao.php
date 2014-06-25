<?php

namespace App\Models;

/**
 * Description of Cidadao
 *
 * @author gilmario
 */
class Cidadao {

    private $cpf;
    private $nome;
    private $logradouro;
    private $cep;
    private $numero;
    private $complemento;
    private $bairro;
    private $cidade;
    private $uf;
    private $email;
    private $telefonePrincipal;
    private $telefoneSecundario;
    private $situacao;
    private $codigoContribuinteSam;
    private $dataCriacao;
    private $horaCriacao;

    function __construct() {

    }

    public function getCpf() {
        return $this->cpf;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getLogradouro() {
        return $this->logradouro;
    }

    public function getCep() {
        return $this->cep;
    }

    public function getNumero() {
        return $this->numero;
    }

    public function getComplemento() {
        return $this->complemento;
    }

    public function getBairro() {
        return $this->bairro;
    }

    public function getCidade() {
        return $this->cidade;
    }

    public function getUf() {
        return $this->uf;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getTelefonePrincipal() {
        return $this->telefonePrincipal;
    }

    public function getTelefoneSecundario() {
        return $this->telefoneSecundario;
    }

    public function getSituacao() {
        return $this->situacao;
    }

    public function getCodigoContribuinteSam() {
        return $this->codigoContribuinteSam;
    }

    public function getDataCriacao() {
        return $this->dataCriacao;
    }

    public function setCpf($cpf) {
        $this->cpf = $cpf;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setLogradouro($logradouro) {
        $this->logradouro = $logradouro;
    }

    public function setCep($cep) {
        $this->cep = $cep;
    }

    public function setNumero($numero) {
        $this->numero = $numero;
    }

    public function setComplemento($complemento) {
        $this->complemento = $complemento;
    }

    public function setBairro($bairro) {
        $this->bairro = $bairro;
    }

    public function setCidade($cidade) {
        $this->cidade = $cidade;
    }

    public function setUf($uf) {
        $this->uf = $uf;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setTelefonePrincipal($telefonePrincipal) {
        $this->telefonePrincipal = $telefonePrincipal;
    }

    public function setTelefoneSecundario($telefoneSecundario) {
        $this->telefoneSecundario = $telefoneSecundario;
    }

    public function setSituacao($situacao) {
        $this->situacao = $situacao;
    }

    public function setCodigoContribuinteSam($codigoContribuinteSam) {
        $this->codigoContribuinteSam = $codigoContribuinteSam;
    }

    public function setDataCriacao($dataCriacao) {
        $this->dataCriacao = $dataCriacao;
    }

    public function getHoraCriacao() {
        return $this->horaCriacao;
    }

    public function setHoraCriacao($horaCriacao) {
        $this->horaCriacao = $horaCriacao;
    }

    public function getHash() {
        return md5($this->cpf . $this->nome . $this->email);
    }

}
