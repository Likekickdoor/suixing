<?php
    //视图类
    namespace lib\core;
    class view{

        public static $config;
        public static $class;
        //初始化，获取配置
        public static function init($config){
            self::$config = $config;
        }
        //把数据显示到模板文件
        public static function display($file,$data){
<<<<<<< HEAD
            $path = self::$config['PATH'].'/'.$file.'.'.self::$config['FORMAT'];
             
	     if(!is_file($path)){
=======
            $path = self::$config['PATH'].'//'.$file.'.'.self::$config['FORMAT'];
            if(!is_file($path)){
>>>>>>> 14b5cd170ebe1a05a8ee36c0c4e3e5bf80c674a0
                p('模板文件不存在');
                die;
            }
            require_once($path);
        }
    }
?>
