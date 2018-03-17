<?php
    namespace app\Controller;
    class index{

        function index(){

            $view = V('index');
            $view->display('index','






                    欢迎使用MyFrame






            ');
        }

        function bus(){
            $mod = M('bus');
            if(!isset($_GET['start']) || !isset($_GET['end'])){
                $this->index();
                return 0;
            }
            $data = $mod->get_bus($_GET['start'],$_GET['end'],'2018-03-15');
            $view = V('bus');
            $view->show_bus('bus',$data);
        }
    }
?>