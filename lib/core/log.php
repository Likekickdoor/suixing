<?php
    namespace lib\core;
    class log{

        public static $config;
        public static $class;

        public static function init($config){
            if(!isset($config)){
                die('找不到log配置文件');
            }
            if($config['IS_START'] == 'false')  //判断是否开启日志
                return 0;
            self::$config = $config;
            self::log('visit');
        }

        public static function log($message){
            \lib\core\DB::insert($this->db_name,'host,message',"'{$_SERVER['HTTP_HOST']}','$message'");
        }
    }
?>