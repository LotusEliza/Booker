<?php
/**
 * Created by PhpStorm.
 * User: lotus
 * Date: 2/10/19
 * Time: 5:42 PM
 */

namespace application\lib;

use PDO;
use PDOException;


class Db {

    protected $db;

    public function __construct()
    {
        $config = require 'application/config/db.php';
        try{
            $this->db = new PDO('mysql:host='.$config['host'].';dbname='.$config['name'].'', $config['user'], $config['password']);
        }catch(PDOException $exception){
            echo ERROR_CONNECT . $exception->getMessage();
        }
    }

    public function query($sql, $params = []){
        $stmt = $this->db->prepare($sql);
        if(!empty($params)){
            foreach ($params as $key => $val){
                $stmt->bindValue(':'.$key, $val);
            }
        }
        $stmt->execute();
        return $stmt;
    }

    public function lastId(){
       return $id = $this->db->lastInsertId();
    }

    public function row($sql, $params = []){
        $result = $this->query($sql, $params);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function column($sql, $params = []){
        $result = $this->query($sql, $params);
        return $result->fetchColumn();
    }

}