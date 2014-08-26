<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace jaspion\Interfaces\Security;

/**
 * Description of UserProviders
 *
 * @author allan
 */
interface UserProviders {
    //put your code here
    
    //checa se e um usuario
    function getUser($username, $password);
    
}
