<?php
namespace models;

use PDO;

// 所有模型的父模型

class Model{

    protected $_db;

    // 由子类设置 表名
    protected $table;
    // 由子类设置 表单数据
    protected $data;


    // 钩子函数
    protected function _before_write(){}
    protected function _after_write(){}
    protected function _before_delete(){}
    protected function _after_delete(){}
    
    public function __construct(){
        $this->_db = \libs\Db::make();
    }

    public function insert(){

        $this->_before_write();

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
        $stmt->execute($values);
        $this->data['id'] = $this->_db->lastInsertId();

        $this->_after_write();
    }

    public function update($id){

        $this->_before_write();

        $set = [];
        $token = [];


        foreach($this->data as $k => $v){
            $set[] = "$k=?";
            $values[] = $v;
            $token[] = "?";
        }

        $set = implode(",",$set);
        $values[] = $id;

        $sql = "UPDATE {$this->table} SET $set WHERE id=?";

        $stmt = $this->_db->prepare($sql);
        $stmt->execute($values);

        $this->_after_write();
        
    }

    public function delete($id){

        $this->_before_delete();

        $stmt = $this->_db->prepare("DELETE FROM {$this->table} WHERE id=?");
        $stmt->execute([$id]);

        $this->_after_delete();
    }

    public function fetchAll($options = []){

        $_option = [
            'fields' => '*',
            'where' => 1,
            'order_by' => 'id',
            'order_way' => 'desc',
            'per_page' => 20,
            'join'=>'',
            'groupby'=>'',
        ];

        // 合并用户配置
        if($options){
            $_option = array_merge($_option,$options);
        }

        // ===== 翻页 =====
        $page = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1;
        $offset = ($page-1)*$_option['per_page'];

        $sql = "SELECT {$_option['fields']}
                FROM {$this->table}
                {$_option['join']}
                WHERE {$_option['where']}
                {$_option['groupby']}
                ORDER BY {$_option['order_by']} {$_option['order_way']}
                LIMIT $offset,{$_option['per_page']}";


        $stmt = $this->_db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll( PDO::FETCH_ASSOC );

        // ===== 总记录数 =====
        $stmt = $this->_db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE {$_option['where']}");
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_COLUMN);
        $pageCount = ceil($count/$_option['per_page']);

        $page_str = "";
        if($pageCount>1){
            for($i=1;$i<$pageCount;$i++){
                $page_str .= '<a href="?page='.$i.'">'.$i.'</a>';
            }
        }

        return [
            'data'=>$data,
            'page'=>$page_str,
        ];

    }

    public function findOne($id){

        $stmt = $this->_db->prepare("SELECT * FROM {$this->table} WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch( PDO::FETCH_ASSOC );

    }

    public function fill($data){
        // 过滤白名单
        foreach($data as $k => $v){
            if(!in_array($k, $this->fillable))
                unset($data[$k]);
        }
        $this->data = $data;
    }


    public function tree(){
        // 先取出所有的数据
        $data = $this->fetchAll();
        // 递归重新排序
        $ret = $this->_tree($data['data']);
        return $ret;
    }

    protected function _tree($data,$parent_id=0,$level=0){
        // 定义一个数组保存排序好之后的数据
        static $_ret = [];
        foreach($data as $v){
            if($v['parent_id'] == $parent_id){
                $v['level'] = $level;
                $_ret[] = $v;
                $this->_tree($data,$v['id'],$level+1);
            }
        }

        return $_ret;
    }



}