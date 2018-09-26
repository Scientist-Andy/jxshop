<?php
namespace controllers;;
class BlogController{
    // 列表页
    public function index(){
        view('blog/index');
    }

    // 显示添加的表单
    public function create(){
        view('blog/create');
    }

    // 处理添加表单
    public function insert(){

        $blog = new \models\Blog;

        $blog->fill($_POST);
        $blog->insert();

    }

    // 显示修改表单
    public function edit(){
        view('blog/edit');
    }

    // 删除
    public function delete(){

    }
}