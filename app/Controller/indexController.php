<?php
    namespace app\Controller;
    class index{

        function index(){
            $view = V('index');
            $view->display('index/index','');
        }

        function get_station(){
            $mod = M('station');
            $data = $mod->get_station();
            foreach ($data as $key) {
                echo ($key['name'].",");
            }
        }
    }