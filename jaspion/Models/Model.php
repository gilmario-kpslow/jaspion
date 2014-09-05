<?php

namespace jaspion\Models;

/*
 * Anotações possíveis para atributos
 * @columnDb para informar o nome do Coluna no danco de dados
 * @formName para informar o nome do atributo name do input no formulario
 */

use jaspion\Util\AnotacaoUtil;

/**
 * Description of Atributo
 *
 * @author allan
 */
abstract class Model {
    /*
     * Metodo que retorna o valor da anotação @formName do atributo informado
     */

    public function getFormName($atributo) {
        $arrayAnotacoes = AnotacaoUtil::gerarArraydeAnotacaoAtributo($this);
        return $arrayAnotacoes[$atributo]["formName"];
    }

    /*
     * Metodo que retorna o valor da anotação @columnDb do atributo informado
     */

    public function getBancoName($atributo) {
        $arrayAnotacoes = AnotacaoUtil::gerarArraydeAnotacaoAtributo($this);
        return $arrayAnotacoes[$atributo]["columnDb"];
    }

    /*
     * Metodo que gera um array de associativo, tendo como chave o nome da
     * anotação @columnDb do atributo e como valor o valor do atributo,
     * para que seja enviado ao DAO do modelo
     */

    public function setBanco() {
        $arrayAnotacoes = AnotacaoUtil::gerarArraydeAnotacaoAtributo($this);
        if (!is_null($arrayAnotacoes)) {
            foreach ($arrayAnotacoes as $campo => $anotacoes) {
                foreach ($anotacoes as $anotacao => $valor) {
                    if ($anotacao == 'columnDb') {
                        $dados[$valor] = $this->$campo;
                    }
                }
            }
            return array_filter($dados);
        } else {
            return null;
        }
    }

    /*
     * Metodo recebe uma resultado de uma query do banco e
     * seta esse valores no seu respectivo atributo
     */

    public function popularBanco(Array $dados) {
        $arrayAnotacoes = AnotacaoUtil::gerarArraydeAnotacaoAtributo($this);
        foreach ($arrayAnotacoes as $campo => $anotacoes) {
            $valor = $anotacoes['columnDb'];
            if (!is_null($valor) && key_exists($valor, $dados)) {
                $set = 'set' . ucfirst($campo);
                $this->$set($dados[$valor]);
            }
        }
    }

    /*
     * Metodo recebe os valores de um formulario e
     * seta esse valores no seu respectivo atributo
     */

    public function popularForm(Array $dados) {
        $arrayAnotacoes = AnotacaoUtil::gerarArraydeAnotacaoAtributo($this);
        if (!is_null($arrayAnotacoes)) {
            foreach ($arrayAnotacoes as $campo => $anotacoes) {
                $valor = (key_exists('formName', $anotacoes) ? $anotacoes['formName'] : "");
                $tipo = (key_exists('tipo', $anotacoes) ? $anotacoes['tipo'] : "");
                if (!is_null($valor) && key_exists($valor, $dados)) {
                    $set = 'set' . ucfirst($campo);
                    switch ($tipo) {
                        case 'int' :
                            $this->$set(\jaspion\Util\Converter::stringParaInteger($dados[$valor]));
                            break;
                        case 'float':
                            $this->$set(\jaspion\Util\Converter::stringParaDouble($dados[$valor]));
                            break;
                        case 'date':
                            $this->$set(\jaspion\Util\Converter::stringParaData($dados[$valor]));
                            break;
                        case 'datetime':
                            $this->$set(\jaspion\Util\Converter::stringParaDataTime($dados[$valor]));
                            break;
                        default :$this->$set($dados[$valor]);
                            break;
                    }
                }
            }
        }
    }

}
