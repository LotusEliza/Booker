<?php
/**
 * Created by PhpStorm.
 * User: lotus
 * Date: 2/9/19
 * Time: 11:53 AM
 */

namespace application\controllers;

use application\core\Controller;
use application\lib\Db;


class Main extends Controller
{

    public function indexAction() {
        $this->view->render('Main page');
    }

}