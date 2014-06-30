<?php

namespace jaspion\Interfaces\Filtro;

/**
 * Description of Filtro
 *
 * @author gilmario
 */
interface Filtro {

    /**
     * A Função deve retonar Verdadeiro / null ou Falso
     * se retonar falso deve parar no filtro e ser retonada para outro lugar
     */
    public function filtrar();

    /**
     * Caso a execução do processo barre no filtro essa função será chamada
     */
    public function erro();
}
