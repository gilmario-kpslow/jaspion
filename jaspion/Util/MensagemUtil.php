<?php

namespace jaspion\Util;

/**
 * Description of MensagemUtil
 *
 * @author gilmario
 */
class MensagemUtil {

    private $mensagem;

    public static function __construct() {
        $fileconfig = file_get_contents("../App/Config/mensagens.json");
        $parametros = json_decode($fileconfig);
        $this->mensagem = $parametros->mensagem->mensagens;
    }

    public function mensagem($nome) {
        if (isset($this->mensagem->$nome)) {
            return $this->mensagem->$nome;
        } else {
            return "Mensagem não localizada";
        }
    }

}
