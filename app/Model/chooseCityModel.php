<?php
    namespace app\Model;
    use lib\core\DB;

    class chooseCity{

        private $data = "";

        function getFristCity(){
            $start = $_GET['start'];
            $fristCity = $_GET['fristCity'];
            $end = $_GET['end'];
            $result = DB::find("center_city","place='{$fristCity}'");
            if(DB::$rowcount == 0)
                return "false";
            $secondCitys = explode(',',$result['it_to']);
            $result = DB::find("center_city","place='{$end}'");
            if(DB::$rowcount == 0)
                return "false";
            $to_end = $result['to_it'];
            foreach ($secondCitys as $key) {
                if(strstr($to_end,$key))
                    $this->data .= $key.",";
            }
            $this->data = substr($this->data,0,strlen($this->data)-1);
            return $this->data;
        }

        function getSecondCity(){
            $start = $_GET['start'];
            $secondCity = $_GET['secondCity'];
            $end = $_GET['end'];
            $result = DB::find("center_city","place='{$secondCity}'");
            if(DB::$rowcount == 0)
                return "false";
            $fristCitys = explode(',',$result['to_it']);
            $result = DB::find("center_city","place='{$start}'");
            if(DB::$rowcount == 0)
                return "false";
            $start_to = $result['it_to'];
            foreach ($fristCitys as $key) {
                if(strstr($start_to,$key))
                    $this->data .= $key.",";
            }
            $this->data = substr($this->data,0,strlen($this->data)-1);
            return $this->data;
        }
    }
?>