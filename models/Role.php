<?php
namespace models;

class Role extends Model {

    // 这个模型对应的表
    protected $table = "role";
    // 允许接受的字段
    protected $fillable = [ 'role_name' ];

    protected function _after_write(){
        $stmt = $this->_db->prepare("INSERT INTO role_privilege(pri_id,role_id) VALUES(?,?)");
        foreach($_POST['pri_id'] as $v){
            $stmt->execute([
                $v,
                $this->data['id'],
            ]);
        }
    }

    protected function _before_delete(){
        $stmt = $this->_db->prepare('DELETE FROM role_privilege where role_id=?');
        $stmt->execute([
            $_GET['id']
        ]);
    }

}