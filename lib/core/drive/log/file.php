<?php
    namespace lib\core\drive\log;
    class file{
        
        public $path;

        public function __construct($config){
            $this->path = $config['PATH'].'/'.date('Y-m-d');
        }

        public function log($message,$file = 'log.php'){
            if(!is_dir($this->path)){
                mkdir($this->path,'0777',true);
            }
            $file = '/'.date('H').'-'.$file;
            $message = array('user' => $_SERVER['HTTP_HOST'],'date' => date('Y-m-d H:i:s'),'message' => $message);
            file_put_contents($this->path.$file,str_replace('\\','',json_encode($message)).PHP_EOL,FILE_APPEND);
        }
    }
?>