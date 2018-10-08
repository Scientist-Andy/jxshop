<?php
namespace models;

class Brand extends Model {

    // 这个模型对应的表
    protected $table = "brand";
    // 允许接受的字段
    protected $fillable = [ 'brand_name','logo' ];

    protected function _before_write(){

        $this->_before_logo();

        if(isset($_GET['id'])){
            $ol = $this->findOne($_GET['id']);
            @unlink(ROOT. 'public' .$ol['logo']);
        }


        $uploader = \libs\Uploader::make();
        $logo = "/uploads/".$uploader->upload('logo','brand');

        $this->data['logo'] = $logo;
    }


    protected function _before_delete(){
        $this->_before_logo();
    }

    protected function _before_logo(){
        
        if(isset($_GET['id'])){
            $ol = $this->findOne($_GET['id']);
            @unlink(ROOT. 'public' .$ol['logo']);
        }

    }
}