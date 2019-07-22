<?php
/**
 * Created by PhpStorm.
 * User: lotus
 * Date: 2/12/19
 * Time: 12:31 PM
 */

namespace application\controllers;

use application\core\Controller;


class Room extends Controller
{

    /**
     * Get all rooms, method - Get
     * @return  $var | success OR error
     */
    public function showAction()
    {
        if ($this->jwtValid()) {
            $result = $this->model->showRooms();
            if ($result) {
                $var = ['rooms' => $result];
                $this->response(200, FOUND, $var, $this->format);
            } else {
                $this->response(404, NOT_FOUND, NULL, $this->format);
            }
        }else{
            $this->response(400, ERROR_EMPTY_JWT, NULL, $this->format);
        }
    }

}