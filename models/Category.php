<?php
namespace models;

class Category extends Model {

    // 这个模型对应的表
    protected $table = "category";
    // 允许接受的字段
    protected $fillable = [ 'cat_name','parent_id','path' ];
    
    public function getCat($parent_id = 0){
        return $this->fetchAll([
            "where" => "parent_id=$parent_id"
        ]);
    }

}