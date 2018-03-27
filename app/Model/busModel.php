<?php
    namespace app\Model;
    use lib\core\DB;
    class bus{
        private $show;
        private $start;
        private $end;
        private $start_station_id;
        private $end_station_id;
        
        function init(){
            $this->start = $_GET['start'];
            $this->end = $_GET['end'];
            if(strpos($this->start,'市') or strpos($this->start,'县'))
                $this->start = substr($this->start,0,strlen($this->start) - 3);
            if(strpos($this->end,'市') or strpos($this->end,'县'))
                $this->end = substr($this->end,0,strlen($this->end) - 3);
            $this->get_station_id();
        }
        //获取输出信息
        function print_line($lines){
            foreach ($lines as $line) {
                $this->show .= $line['start_time'].','.$line['arrive_time'].','.$line['time'].','.$line['start_station_name'].','.$line['end_station_name'].','.$line['type'].','.$line['price'].';';
            }
        }
        //获取站点id
        function get_station_id(){
            $this->start_station_id = DB::find("station","name='{$this->start}'")['id'];
            $this->end_station_id = DB::find("station","name='{$this->end}'")['id'];
        }
        //获取线路信息
        function get_bus($order){
            if(!isset($_GET['start']) || !isset($_GET['end'])){
                return 0;
            }
            $this->init();
            $lines = DB::findAll("line","start_station_id={$this->start_station_id} and end_station_id={$this->end_station_id} order by $order");
            if($lines != NULL){
                $this->print_line();
            }else{
                $this->get_interchange($order);
            }
            return $this->show;
        }
        //获取转乘信息
        function get_interchange($order){
            //获取城市id
            $start_city = DB::find("city","city='{$this->start}'");
            if($start_city != NULL){
                $start_city_id = $start_city['id'];
            }
            $end_city = DB::find("city","city='{$this->start}'");
            if($end_city != NULL){
                $end_city_id = $end_city['id'];
            }
            if($start_city != NULL and $end_city != NULL){
                $this->city_to_city($start_city_id,$end_city_id,$order);
            }else if($start_city != NULL and $end_city == NULL){
                $this->city_to_station($start_city_id,$order);
            }else if($start_city == NULL and $end_city != NULL){
                $this->station_to_city($end_city_id,$order);
            }else if($start_city == NULL and $end_city == NULL){
                $this->station_to_station($order);
            }
        }
        //城市到城市
        function city_to_city($start_city_id,$end_city_id,$order){
            p("city_to_city");
        }
        //城市到小站
        function city_to_station($start_city_id,$order){
            p("city_to_station");
        }
        //小站到城市
        function station_to_city($end_city_id,$order){
            p("station_to_city");
        }
        //小站到小站
        function station_to_station($order){
            $center_citys = DB::findAll("city_to_station","station_id={$this->start_station_id}");
            foreach ($center_citys as $center_city) {
                $frist = DB::findAll("line","start_station_id={$this->start_station_id} and end_station_id={$center_city['city_id']} order by $order");
                if($frist == NULL){
                    continue;
                }
                $second = DB::findAll("line","start_station_id={$center_city['city_id']} and end_station_id={$this->end_station_id} order by $order");
                if($second == NULL){
                    continue;
                }
                $this->print_line($frist);
                $this->print_line($second);
                break;
            }
        }
    }
?>