<?php

namespace jaspion\Container;

use jaspion\Util\AnotacaoUtil;

/**
 * Description of ModelContainer
 *
 * @author gilmario
 */
abstract class ModelContainer {

    private $modelo;
    private $arrayAnotacoes;

    public abstract function getTable();

    public function __construct($modelo) {
        $this->modelo = $modelo;
        $this->arrayAnotacoes = AnotacaoUtil::gerarArraydeAnotacaoAtributo($this->modelo);
    }

    public function getFormName($atributo) {
        return $this->arrayAnotacoes[$atributo]["formName"];
    }

    /*
     * Metodo que retorna o valor da anotação @columnDb do atributo informado
     */

    public function getBancoName($atributo) {
        return $this->arrayAnotacoes[$atributo]["columnDb"];
    }

    /*
     * Metodo que gera um array de associativo, tendo como chave o nome da
     * anotação @columnDb do atributo e como valor o valor do atributo,
     * para que seja enviado ao DAO do modelo
     */

    public function getBanco($entidade) {
        foreach ($this->arrayAnotacoes as $campo => $anotacoes) {
            $valor = $anotacoes['columnDb'];
            if (!is_null($valor)) {
                $get = 'get' . ucfirst($campo);
                $dados[$valor] = $entidade->$get();
            }
        }
        return array_filter($dados);
    }

    /*
     * Metodo recebe uma resultado de uma query do banco e
     * seta esse valores no seu respectivo atributo
     */

    public function popularBanco(Array $dados) {
        return $this->popularEntidade($dados, "columnDb");
    }

    /**
     *
     * @param array $dados
     * @param type $anotacao
     * @return type Receber um arrayio de dados e popular a entidade
     */
    private function popularEntidade(Array $dados, $anotacao) {
        $entidade = new $this->modelo();
        foreach ($this->arrayAnotacoes as $campo => $anotacoes) {
            $valor = (key_exists($anotacao, $anotacoes) ? $anotacoes[$anotacao] : "");
            $tipo = (key_exists('tipo', $anotacoes) ? $anotacoes['tipo'] : "");
            if (!is_null($valor) && key_exists($valor, $dados)) {
                $set = 'set' . ucfirst($campo);
                $entidade->$set($this->resolveTipo($tipo, $dados[$valor]));
            }
        }
        return $entidade;
    }

    /*
     * Metodo recebe os valores de um formulario e
     * seta esse valores no seu respectivo atributo
     */

    public function popularForm(Array $dados) {
        return $this->popularEntidade($dados, "formName");
    }

    private function resolveTipo($tipo, $valor) {
        switch ($tipo) {
            case 'int' :
                return\jaspion\Util\Converter::stringParaInteger($valor);
            case 'float':
                return \jaspion\Util\Converter::stringParaDouble($valor);
            case 'boolean':
                return boolval($valor);
            case 'date':
                return \jaspion\Util\Converter::stringParaData($valor);
            case 'datetime':
                return \jaspion\Util\Converter::stringParaDataTime($valor);
            default :return $valor;
        }
    }

}
