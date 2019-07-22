<?php
/**
 * Created by PhpStorm.
 * User: lotus
 * Date: 2/8/19
 * Time: 8:37 PM
 */

namespace application\core;

class Router
{

    protected $routes = [];
    protected $params = [];
    protected $var = [];
    protected $format = [];

    function __construct()
    {
        $arr = require 'application/config/routes.php';
        foreach ($arr as $key => $val){
            $this->add($key, $val);
        }
    }

    public function add($route, $params){
        $route = '#^'.$route.'$#';
        $this->routes[$route] = $params;
    }

    public function match() {
        $url = trim($_SERVER['REQUEST_URI'], '/');
        $prefix = '~user3/booker/server/api/';
        $url = substr($url, strlen($prefix));

        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                $this->params = $params;
                if(isset($matches[1])){
                    if(!is_numeric($matches[1])){
                        $this->format = $matches[1];
                    }elseif(is_numeric($matches[1])){
                        $this->var = $matches[1];
                    }
                }
                if(isset($matches[2]) && !is_numeric($matches[2])) {
                    $this->format = $matches[2];
                }
                return true;
            }
         }
        return false;
    }


    public function run(){
        if ($this->match()) {
            $path = 'application\controllers\\'.ucfirst($this->params['controller']);
            if (class_exists($path)) {
                $action = $this->params['action'].'Action';
                if (method_exists($path, $action)) {
                    $controller = new $path($this->params, $this->var, $this->format);
                    $controller->$action();
                } else {
                    echo json_encode("No $action found");
                }
            } else {
                echo json_encode("No $path found");
            }
        } else {
            echo json_encode(NO_MATCH);
        }
    }

}