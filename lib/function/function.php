<?php
    function C($name,$method){
        require_once('app/Controller/'.$name.'Controller.php');
        $controller = 'app\Controller\\'.$name;
        $obj = new $controller();
        $obj->$method();
    }

    function M($name){
        require_once('app/Model/'.$name.'Model.php');
        $model = 'app\Model\\'.$name;
        $obj = new $model();
        return $obj;
    }

    function V($name){
        require_once('app/View/'.$name.'View.php');
        $view = 'app\View\\'.$name;
        $obj = new $view();
        return $obj;
    }

    function p($var){
        print_r($var);
        print_r('<br>');
    }
?>