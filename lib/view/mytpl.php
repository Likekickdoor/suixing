<?php
    namespace lib\view;
    class mytpl{

        public $format;
        public $path;

        public function __construct($config){
            $this->format = $config['FORMAT'];
            $this->path = $config['PATH'];
        }

        public function display($file,$data){
            $file = "$this->path\\$file.$this->format";
            require_once($file);
        }
    }
?>