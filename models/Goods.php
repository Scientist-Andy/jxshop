<?php
namespace models;

class Goods extends Model {

    // 这个模型对应的表
    protected $table = "goods";
    // 允许接受的字段
    protected $fillable = [ 'goods_name','logo','is_on_sale','description','cat1_id','cat2_id','cat3_id','brand_id' ];

    protected function _before_write(){

        $this->_before_logo();

        if(isset($_GET['id'])){
            $ol = $this->findOne($_GET['id']);
            @unlink(ROOT. 'public' .$ol['logo']);
        }


        $uploader = \libs\Uploader::make();
        $logo = "/uploads/".$uploader->upload('logo','goods');

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

    protected function _after_write(){

        // 处理商品属性
        $stmt = $this->_db->prepare("INSERT INTO goods_attribute(attr_name,attr_value,goods_id) VALUES(?,?,?)");
        foreach($_POST['attr_name'] as $k => $v){
            $stmt->execute([
                $v,
                $_POST['attr_value'][$k],
                $this->data['id'],
            ]);
        }

        // 批量上传商品图片

        $stmt = $this->_db->prepare("INSERT INTO goods_image(goods_id,path) VALUES(?,?)");

        $_tmp = [];
        foreach($_FILES['image']['name'] as $k => $v){
            $_tmp['name'] = $v;
            $_tmp['type'] = $_FILES['image']['type'][$k];
            $_tmp['tmp_name'] = $_FILES['image']['tmp_name'][$k];
            $_tmp['error'] = $_FILES['image']['error'][$k];
            $_tmp['size'] = $_FILES['image']['size'][$k];

            $_FILES['tmp'] = $_tmp;

            $uploader = \libs\Uploader::make();
            $path = "/uploads/".$uploader->upload('tmp','goods');

            $stmt->execute([
                $this->data['id'],
                $path,
            ]);

            

        }





        // SKU
        $stmt = $this->_db->prepare("INSERT INTO goods_sku(goods_id,sku_name,stock,price) VALUES(?,?,?,?)");

        foreach($_POST['sku_name'] as $k => $V){
            $stmt->execute([
                $this->data['id'],
                $v,
                $_POST['stock'][$k],
                $_POST['price'][$k],
            ]);
        }

    }


}