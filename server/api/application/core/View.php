<?php
/**
 * Created by PhpStorm.
 * User: lotus
 * Date: 2/9/19
 * Time: 3:37 PM
 */

namespace application\core;


class View
{
    public $path;
    public $route;

    public function __construct($route)
    {
        $this->route = $route;
        $this->path = $route['controller'] . '/' . $route['action'];
    }

    public function render($title, $vars = [])
    {
        extract($vars);
        $path = 'application/views/'.$this->path.'.php';
        if(file_exists($path)){
            //copy view to buffer
            ob_start();
            require $path;
        }else{
            echo "View cant be find: ".$this->path;
        }
    }

}