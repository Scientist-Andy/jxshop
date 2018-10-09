<?php
namespace models;

class Admin extends Model {

    // 这个模型对应的表
    protected $table = "admin";
    // 允许接受的字段
    protected $fillable = [ 'username','password' ];

}