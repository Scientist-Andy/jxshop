<?php

namespace libs;

class Db{

    private $_pdo;
    private static $_obj = null;
    private function __clone(){}

    private function __construct(){
        $this->_pdo = new \PDO('mysql:host=localhost;dbname=jxshop','root','123');
        $this->_pdo->exec('SET NAMES utf8');
    }
    
    public static function make(){
        if(self::$_obj === null){
            self::$_obj = new self;
        }
        return self::$_obj;
    }

    public function prepare($sql){
        return $this->_pdo->prepare($sql);
    }

    public function exec($sql){
        return $this->_pdo->exec($sql);
    }

}