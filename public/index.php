<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * //RewriteRule ^{.*}$ index.php?key=$1
 */
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', '/home/gilmario/error_log.txt');
error_reporting(E_ALL);

require_once '../vendor/autoload.php';

session_start();
$init = new App\init();
