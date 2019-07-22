<?php
/**
 * Created by PhpStorm.
 * User: lotus
 * Date: 2/12/19
 * Time: 12:37 PM
 */

namespace application\models;
use application\core\Model;


class User extends Model
{

    public function showUsers(){
        $result = $this->db->row("SELECT u.id, r.name as role, 
                                u.email, u.username FROM users u LEFT JOIN roles r ON u.id_role=r.id WHERE status = 1");
        if($result){
            return $result;
        }else{
            return false;
        }
    }

    public function updateUser($array){
        $result = $this->db->query('UPDATE users 
        SET username=:username, email=:email
        WHERE id = :id', $array);
        $count = $result->rowCount();
        if($count){
            return true;
        }else{
            return false;
        }
    }

    public function findUser($array){
        $result = $this->db->row("SELECT * FROM users WHERE username = '".$array['username']."' OR email = '".$array['email']."'");
        if($result){
            return $result;
        }else{
            return false;
        }
    }

    public function deleteUser($array){
        $result = $this->db->query("Update users SET status = 0 WHERE id = :id ", $array);
        $this->db->query("DELETE FROM events WHERE id_user = :id  AND time_start >= NOW()", $array);
        $count = $result->rowCount();
        if($count){
            return true;
        }else{
            return false;
        }
    }

    public function createUser($user){
        $result = $this->db->query("INSERT INTO users 
        (username, password, email, id_role) VALUES (:username, :password, :email, :id_role)", $user);
        $count = $result->rowCount();
        if($count){
            return true;
        }else{
            return false;
        }
    }

}