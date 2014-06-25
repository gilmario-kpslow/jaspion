<?php

namespace App\Controllers;

use jaspion\Controllers\Controller;

class IndexController extends Controller {

    public function indexAction() {
        $this->render('index');
    }

    public function erro404() {
        $this->render("erro404");
    }

    public function erro500($ex = null) {
        $this->render("erro500");
    }

}
