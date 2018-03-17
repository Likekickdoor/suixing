<?php
    namespace lib\core\drive\log;
    class mysql{
        
        public $db_name;

        public function __construct($config){
            $this->db_name = $config['DB_NAME'];
        }

        public function log($message){
            \lib\core\DB::insert($this->db_name,'host,message',"'{$_SERVER['HTTP_HOST']}','$message'");
        }
    }
?>