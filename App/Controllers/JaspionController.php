<?php

namespace App\Controllers;

use jaspion\Controllers\Controller;

/**
 * Description of JaspionController
 *
 * @author gilmario
 */
class JaspionController extends Controller {

    public function indexAction() {
        $this->render("index");
    }

}
