<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace jaspion\Interfaces\Security;
use jaspion\Interfaces\Filtro\Filtro;
/**
 *
 * @author allan
 */
interface SecurityControllerInterface extends Filtro {
    //put your code here
    public function logarAction($msg = null, $controle = null, $acao = null, $parametro = null);
    
    public function loginAction();
    
    public function logoutAction();
}
