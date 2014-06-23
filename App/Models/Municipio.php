<?php

namespace App\Models;

/**
 * Description of Municipio
 *
 * @author gilmario
 */
class Municipio {

    private $codigo;
    private $nome;
    private $uf;

    public function getCodigo() {
        return $this->codigo;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getUf() {
        return $this->uf;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setUf($uf) {
        $this->uf = $uf;
    }

}
