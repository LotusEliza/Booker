<?php
/**
 * Created by PhpStorm.
 * User: lotus
 * Date: 2/12/19
 * Time: 12:37 PM
 */

namespace application\models;


use application\core\Model;

class Room extends Model
{

    public function showRooms()
    {
        $result = $this->db->row('SELECT * FROM rooms');
        return $result;
    }

}