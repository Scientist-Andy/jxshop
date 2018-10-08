<?php
namespace libs;

class Uploader{
    private function __construct(){}
        
    private function __clone(){}
    
    private static $_obj = null;

    public static function make(){
        if(self::$_obj === null){

            self::$_obj = new self;
        }

        return self::$_obj;
    }


    private $_root = ROOT . 'public/uploads/';  //图片保存的一级目录
    private $_ext = ['image/jpeg','image/jpg','image/ejpeg','image/png','image/gif','image/bmp'];
    private $_maxSize = 1024*1024*1.8;  //最大允许上传的尺寸

    private $_file; //保存用户上传的信息
    private $_subDir;


    public function upload($name, $subdir){

        // 把图片信息保存在属性上
        $this->_file = $_FILES[$name];
        $this->_subDir = $subdir;
        if(!$this->_checkType()){
            die('图片类型不正确');
        }
        if(!$this->_checkSize()){
            die('图片尺寸不正确');
        }


        // 创建目录
        $dir = $this->_makeDir($subdir);

        // 生成唯一的名字
        $name = $this->_makeName();

        // 移动图片
        move_uploaded_file($this->_file['tmp_name'],$this->_root.$dir.$name);

        //返回二级目录开始的路径
        return $dir.$name;

    }



    private function _makeDir($subdir){
        $dir = $subdir . '/' . date('Ymd');
        
        if(!is_dir($this->_root . $dir))
            mkdir($this->_root . $dir, 0777,TRUE);

        return $dir.'/';
    }


    private function _makeName(){
        
        // 生成唯一的名字
        $name = md5( time() . rand(1,9999) );   // 32位字符串
        $ext = strrchr($this->_file['name'], '.');

        // 补上扩展名
        return $name . $ext;
    }

    private function _checkType(){
        return in_array($this->_file['type'],$this->_ext);
    }

    private function _checkSize(){
        return $this->_file['size'] < $this->_maxSize;
    }
}   