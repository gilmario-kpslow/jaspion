<?php

//DOCUMENTOS_CREDITO

namespace App\Models;

/**
 * Description of DocumentoCredito
 *
 * @author gilmario
 */
class DocumentoCredito {

    /**
     *
     * @var type Cpf do contribuinte
     */
    private $cpf;

    /**
     *
     * @var type CNPJ do emissor da nota
     */
    private $cnpj;

    /**
     *
     * @var type Codigo do modelo do documento
     */
    private $codmoddoc;

    /**
     *
     * @var type Serie da nota - 1 - para | online 2 - para convertida
     */
    private $serie;

    /**
     *
     * @var type numero da nota
     */
    private $numero;

    /**
     *
     * @var type Valor do imposto
     */
    private $valoriss;

    /**
     *
     * @var type Valor do credito
     */
    private $valorcredito;

    /**
     * saber se o valor creditado já foi utilizado
     * @var type Booleano S/N
     */
    private $creditado;

    /**
     * saber se iss da nota foi pago pelo emisssor
     * @var type Booleano S/N
     */
    private $pago;

    /**
     *
     * @var Date
     */
    private $dataEmissao;

    function __construct() {

    }

    public function getCpf() {
        return $this->cpf;
    }

    public function getCnpj() {
        return $this->cnpj;
    }

    public function getCodmoddoc() {
        return $this->codmoddoc;
    }

    public function getSerie() {
        return $this->serie;
    }

    public function getNumero() {
        return $this->numero;
    }

    public function getValoriss() {
        return $this->valoriss;
    }

    public function getValorcredito() {
        return $this->valorcredito;
    }

    public function getCreditado() {
        return $this->creditado;
    }

    public function setCpf($cpf) {
        $this->cpf = $cpf;
    }

    public function setCnpj($cnpj) {
        $this->cnpj = $cnpj;
    }

    public function setCodmoddoc($codmoddoc) {
        $this->codmoddoc = $codmoddoc;
    }

    public function setSerie($serie) {
        $this->serie = $serie;
    }

    public function setNumero($numero) {
        $this->numero = $numero;
    }

    public function setValoriss($valoriss) {
        $this->valoriss = $valoriss;
    }

    public function setValorcredito($valorcredito) {
        $this->valorcredito = $valorcredito;
    }

    public function setCreditado($creditado) {
        $this->creditado = $creditado;
    }

    public function getPago() {
        return $this->pago;
    }

    public function getDataEmissao() {
        return $this->dataEmissao;
    }

    public function setPago($pago) {
        $this->pago = $pago;
    }

    public function setDataEmissao($dataEmissao) {
        $this->dataEmissao = $dataEmissao;
    }

}
