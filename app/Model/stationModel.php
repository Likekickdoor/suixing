<?php
    namespace app\Model;
    use lib\core\DB;
    class station{

        private $data;

        function get_station(){
            $this->data = DB::findAll("all_station","state=1");
            return $this->data;
        }
    }
?>