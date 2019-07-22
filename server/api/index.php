<?php
/**
 * Created by PhpStorm.
 * User: lotus
 * Date: 2/8/19
 * Time: 8:31 PM
 */
require 'application/lib/Dev.php';
require 'application/config/config.php';
require "vendor/autoload.php";

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

use application\core\Router;

spl_autoload_register(function ($class) {
    $path = str_replace('\\', '/', $class . ".php");
    if(file_exists($path)){
        require $path;
    }
});

session_start();

$router = new Router;
$router->run();