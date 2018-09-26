<?php
namespace models;

// 所有模型的父模型

class Model{

    protected $_db;

    // 由子类设置 表名
    protected $table;
    // 由子类设置 表单数据
    protected $data;

    public function __construct(){
        $this->_db = \libs\Db::make();
    }

    public function insert(){

    // exec() 方法
        // 获取key并生成一个新数组
        // $keys = array_keys($this->data);
        // $keys = implode(',',$keys);

        // $values = array_values($this->data);
        // $values = implode("','",$values);
    
    // prepare() 预处理方法
        $keys = [];
        $values = [];
        $token = [];

        foreach($this->data as $k => $v){
            $keys[] = $k;
            $values[] = $v;
            $token[] = "?";
        }

        $keys = implode(",",$keys);
        $token = implode(",",$token);      

        $sql = "INSERT INTO {$this->table}($keys) VALUES($token)";

        $stmt = $this->_db->prepare($sql);
        return $stmt->execute($values);
    }

    public function update(){

    }

    public function delete(){

    }

    public function fetchAll(){

    }

    public function findOne(){

    }

    public function fill($data){

        // 过滤白名单
        foreach($data as $k => $v){
            if(!in_array($k,$this->fillable))
                unset($data[$k]);
        }


        $this->data = $data;
    }
}