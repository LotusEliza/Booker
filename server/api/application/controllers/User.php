<?php
/**
 * Created by PhpStorm.
 * User: lotus
 * Date: 6/23/19
 * Time: 1:47 PM
 */

namespace application\controllers;

use application\core\Controller;
use \Firebase\JWT\JWT;


class User extends Controller
{

    public $loginUserName;

    /**
     * Get all users, method - POST
     * @return $var| array | success OR error
     */
    public function showAction(){
        if ($this->jwtValid()) {
            $result = $this->model->showUsers();
            if ($result) {
                $var = ['users' => $result];
                $this->response(200, FOUND, $var, $this->format);
            } else {
                $this->response(404, NOT_FOUND, NULL, $this->format);
            }
        }else{
            $this->response(400, ERROR_EMPTY_JWT, NULL, $this->format);
        }
    }

    /**
     * Create User, method - POST
     * @return | success OR error
     */
    public function createUserAction(){
        $data = json_decode(file_get_contents("php://input"));
        $array = (array)$data;
        foreach ($array as $key => $val) {
            $array[$key] = $this->testInput($val);
        }

        $array['email'];
        if (!$this->emptyVal($array)) {
            if(!preg_match(PATTERN,$array['email'])){
                $this->response(400, ERROR_EMAIL, NULL, $this->format);
            }else{
                if (!$this->model->findUser($array)) {
                    $array['password'] = $this->bcrypt($array['password']);
                    $result = $this->model->createUser($array);
                    if ($result) {
                        $this->response(201, USER_CREATED, NULL, $this->format);
                    } else {
                        $this->response(503, ERROR_CREATE_USER, NULL, $this->format);
                    }
                } else {
                    $this->response(409, EXIST, NULL, $this->format);
                }
            }
        } else {
            $this->response(400, ERROR_EMPTY, NULL, $this->format);
        }
    }

    /**
     * Update user, method - POST
     * @return | success OR error
     */
    public function updateUserAction(){
        if ($this->jwtValid()) {
            $data = json_decode(file_get_contents("php://input"));
            $array =  (array) $data;
            foreach ($array as $key=>$val){
                $array[$key] = $this->testInput($val);
            }
            if(!$this->emptyVal($array)) {
                    unset($array['jwt']);
                if(!preg_match(PATTERN,$array['email'])){
                    $this->response(400, ERROR_EMAIL, NULL, $this->format);
                }else{
                    $result = $this->model->updateUser($array);
                    if($result){
                        $this->response(200,SUCCESS,NULL,$this->format);
                    }else{
                        $this->response(503,ERROR_UPDATE,NULL,$this->format);
                    }
                }
            }
            else{
                $this->response(400, ERROR_EMPTY, NULL, $this->format);
            }
        } else {
            $this->response(400, ERROR_EMPTY_JWT, NULL, $this->format);

        }
    }

    /**
     * Login, method - POST
     * @return | success OR error
     */
    public function loginAction(){
        $data = json_decode(file_get_contents("php://input"));
        $array =  (array) $data;
        foreach ($array as $key=>$val){
            $array[$key] = $this->testInput($val);
        }
        if(!$this->emptyVal($array)){
            if($user = $this->model->findUser($array)) {
                $validPassword = $this->verify($array['password'], $user[0]['password']);
                if ($validPassword) {
                    $jwt = $this->createToken();
                    $this->response(200, SUCCESS, ['jwt'=>$jwt, 'id'=>$user[0]['id'], 'id_role'=>$user[0]['id_role']], $this->format);
                }else{
                    $this->response(409, ERROR_PASS, ERROR_PASS, $this->format);
                }
            }else{
                $this->response(409,NO_USER,NULL,$this->format);
            }
        }else{
            $this->response(400, ERROR_EMPTY, NULL, $this->format);
        }
    }

    /**
     * Delete user, method - POST
     * @return | success OR error
     */
    public function deleteAction(){
        if ($this->jwtValid()) {
            $data = json_decode(file_get_contents("php://input"));
            $array = (array)$data;
            foreach ($array as $key => $val) {
                $array[$key] = $this->testInput($val);
            }
            if (!$this->emptyVal($array)) {
                unset($array['jwt']);
                $result = $this->model->deleteUser($array);
//                $result2 = $this->model
                if ($result) {
                    $this->response(200, SUCCESS, NULL, $this->format);
                } else {
                    $this->response(503, ERROR, NULL, $this->format);
                }
            }else{
                $this->response(400, ERROR_EMPTY, NULL, $this->format);
            }
        }else{
            $this->response(400, ERROR_EMPTY_JWT, NULL, $this->format);
        }
    }

    /**
     * Create token
     * @return $jwt | string
     */
    protected function createToken()
    {
        $secret_key = SECRET_KEY;
        $issuer_claim = URL;
        $audience_claim = "THE_AUDIENCE";
        $issuedat_claim = time(); // issued at
        $notbefore_claim = $issuedat_claim + 1; //not before in seconds
        $expire_claim = $issuedat_claim + 1000000000000; // expire time in seconds
        $token = array(
            "iss" => $issuer_claim,
            "aud" => $audience_claim,
            "iat" => $issuedat_claim,
            "nbf" => $notbefore_claim,
            "exp" => $expire_claim,
            "data" => array(
                "useName" => $this->loginUserName,
            ));
        JWT::$leeway = 5;
        $jwt = JWT::encode($token, $secret_key);
        return $jwt;
    }

    public function bcrypt($password){
        return $passwordHashed = password_hash($password, PASSWORD_BCRYPT);
    }

    public function verify($password, $userPassword){
        return $validPassword = password_verify($password, $userPassword);
    }

}