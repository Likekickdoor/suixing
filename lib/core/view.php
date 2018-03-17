<?php
    namespace lib\core;
    class view{

        public static $config;
        public static $class;

        public static function init($config){
            self::$config = $config;
        }

        public static function display($file,$data){
            $path = self::$config['PATH'].'\\'.$file.'.'.self::$config['FORMAT'];
            if(!is_file($path)){
                dump('模板文件不存在');
                die;
            }
            $file = "$this->path\\$file.$this->format";
            require_once($file);
        }
    }
?>