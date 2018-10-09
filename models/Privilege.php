<?php
namespace models;

class Privilege extends Model {

    // 这个模型对应的表
    protected $table = "privilege";
    // 允许接受的字段
    protected $fillable = [ 'pri_name','url_path','parent_id' ];

}