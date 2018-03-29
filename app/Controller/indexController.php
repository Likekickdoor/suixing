<?php
    namespace app\Controller;
    class index{

        function index(){
            $view = V('index');
            $view->display('index/index','');
        }

        // function bus(){
        //     $mod = M('bus');
        //     $data = $mod->get_bus("price");
        //     $view = V('bus');
        //     $view->show_bus('bus',$data);
        // }
    }
?>