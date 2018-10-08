<?php
namespace controllers;

class CodeController{

    // 生成代码
    public function make(){




        // 1 接收参数
        $tableName = $_GET['name'];

        

        // 取出表中所有字段信息
        $sql = "SHOW FULL FIELDS FROM $tableName";
        $db = \libs\Db::make();
        // 预处理
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $fields = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // 收集所有字段的白名单
        $fillable = [];
        foreach($fields as $v){
            if($v['Field']=='id' || $v['Field']=='created_at')
                continue;
            $fillable[] = $v['Field'];
        }
        $fillable = implode("','",$fillable);




        // 2 生成控制器
        $cname = ucfirst($tableName).'Controller';
        $mname = ucfirst($tableName);
        // == 加载模版 ==
        ob_start();
        include(ROOT.'templates/controller.php');
        $str = ob_get_clean();
        file_put_contents(ROOT.'controllers/'.$cname.'.php',"<?php\r\n".$str);

        // 3 生成模型
        $mname = ucfirst($tableName);
        ob_start();
        include(ROOT.'templates/model.php');
        $str = ob_get_clean();
        file_put_contents(ROOT.'models/'.$mname.'.php',"<?php\r\n".$str);


        // 4 生成视图文件
        @mkdir(ROOT.'views/'.$tableName,0777);

        // create.html
        ob_start();
        include(ROOT.'templates/create.html');
        $str = ob_get_clean();
        file_put_contents(ROOT.'views/'.$tableName.'/create.html',$str);
        // edit.html
        ob_start();
        include(ROOT.'templates/edit.html');
        $str = ob_get_clean();
        file_put_contents(ROOT.'views/'.$tableName.'/edit.html',$str);
        // index.html
        ob_start();
        include(ROOT.'templates/index.html');
        $str = ob_get_clean();
        file_put_contents(ROOT.'views/'.$tableName.'/index.html',$str);
    }
}