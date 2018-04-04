<?php
    namespace app\Controller;
    class index{

        function index(){
            $view = V('index');
            $view->display('index/index','');
        }
    }