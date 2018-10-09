<?php
namespace controllers;;

use \models\Role;

class RoleController{
    // 列表页
    public function index(){
        $model = new Role;
        $data = $model->fetchAll([
            'fields' => 'a.id,a.role_name,GROUP_CONCAT(c.pri_name) pri_list',
            'join'=>'a LEFT JOIN role_privilege b ON a.id=b.role_id LEFT JOIN privilege c ON b.pri_id=c.id',
            'groupby'=>'GROUP BY a.id',
            'per_page'=>20,
        ]);
        // var_dump($data);die;
        view('role/index',$data);
    }

    // 显示添加的表单
    public function create(){

        $priModel = new \models\Privilege;
        $data = $priModel->tree();


        view('role/create',$data);
    }

    // 处理添加表单
    public function insert(){

        $model = new Role;
        $model->fill($_POST);
        $model->insert();
        redirect('/role/index');

    }

    // 显示修改表单
    public function edit(){

        $model = new Role;
        $data = $model->findOne($_GET['id']);
        view('role/edit',[
            'data'=>$data,    
        ]);
    }

    // 修改表单的方法
    public function update(){
        $model = new Role;
        $model->fill($_POST);
        $model->update($_GET['id']);
        redirect('/role/index');
    }

    // 删除
    public function delete(){
        $model = new Role;
        $model->delete($_GET['id']);
        redirect('/role/index');
    }
}