<?php
    namespace lib\core;
    class view{

        public static $config;
        public static $class;

        public static function init($config){
            self::$config = $config;
            $name = '\lib\view\\'.$config['TEMPLET'];
            self::$class = new $name($config);
        }

        public static function display($file,$data){
            $path = self::$config['PATH'].'\\'.$file.'.'.self::$config['FORMAT'];
            if(!is_file($path)){
                dump('模板文件不存在');
                die;
            }
            self::$class->display($file,$data);
        }
    }
?>