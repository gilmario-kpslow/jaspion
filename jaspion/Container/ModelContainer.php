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
        $entidade = new $this->modelo();
        foreach ($this->arrayAnotacoes as $campo => $anotacoes) {
            $valor = $anotacoes['columnDb'];
            if (!is_null($valor) && key_exists($valor, $dados)) {
                $set = 'set' . ucfirst($campo);
                $entidade->$set($dados[$valor]);
            }
        }
        return $entidade;
    }

    /*
     * Metodo recebe os valores de um formulario e
     * seta esse valores no seu respectivo atributo
     */

    public function popularForm(Array $dados) {
        $entidade = new $this->modelo();
        foreach ($this->arrayAnotacoes as $campo => $anotacoes) {
            $valor = (key_exists('formName', $anotacoes) ? $anotacoes['formName'] : "");
            $tipo = (key_exists('tipo', $anotacoes) ? $anotacoes['tipo'] : "");
            $entidade = $this->resolveTipo($entidade, $campo, $tipo, $dados, $valor);
        }
        return $entidade;
    }

    /**
     * Resolver o tipo de valor passado para a entidade
     *
     * @param  $entidade
     * @param  $campo
     * @param  $tipo
     * @param  $dados
     * @param  $valor
     * @return
     */
    private function resolveTipo($entidade, $campo, $tipo, $dados, $valor) {
        if (!is_null($valor) && key_exists($valor, $dados)) {
            $set = 'set' . ucfirst($campo);
            switch ($tipo) {
                case 'int' :
                    $entidade->$set(\jaspion\Util\Converter::stringParaInteger($dados[$valor]));
                    break;
                case 'float':
                    $entidade->$set(\jaspion\Util\Converter::stringParaDouble($dados[$valor]));
                    break;
                case 'date':
                    $entidade->$set(\jaspion\Util\Converter::stringParaData($dados[$valor]));
                    break;
                case 'datetime':
                    $entidade->$set(\jaspion\Util\Converter::stringParaDataTime($dados[$valor]));
                    break;
                default :$entidade->$set($dados[$valor]);
                    break;
            }
        }
        return $entidade;
    }

}
