<?php
namespace models;

class Blog extends Model{

    // 设置表名
    protected $table = 'blogs';
    // 设置白名单
    protected $fillable = ['title','content','is_show'];

    public function update(){

    }

    public function delete(){

    }

    public function search(){

    }
}