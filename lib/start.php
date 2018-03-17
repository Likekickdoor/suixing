<?php
    namespace lib;
    foreach($paths as $path){
        require_once($path);
    }
    class start{

        public static $con;
        public static $med;
        public static $config;

        public static function init_db($config){
            core\DB::init($config);
        }

        public static function init_con(){
            $conArr = array('index');
            self::$con = (isset($_GET['con']) and in_array($_GET['con'],$conArr)) ? $_GET['con'] : 'index';
        }

        public static function init_med(){
            $medArr = array('index','bus');
            self::$med = (isset($_GET['med']) and in_array($_GET['med'],$medArr)) ? $_GET['med'] : 'index';
        }

        public static function init_view($config){
            core\view::init($config);
        }

        public static function init_log($config){
            core\log::init($config);
        }

        public static function run($config){
            self::$config = $config;
            self::init_db($config['dbconfig']);
            self::init_con();
            self::init_med();
            self::init_view($config['viewconfig']);
            self::init_log($config['logconfig']);
        }
    }
?>