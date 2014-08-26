<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace jaspion\Interfaces\Security;

/**
 * Description of UserInterface
 *
 * @author allan
 */
interface UserInterface {

    public function getRole();

    public function gerarToken();

    public function getToken();
}
