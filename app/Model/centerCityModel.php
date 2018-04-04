<?php
    namespace app\Model;
    use lib\core\DB;
    class centerCity{

        private $data;
        private $start;
        private $end;

        function init(){
            $this->start = $_GET['start'];
            $this->end = $_GET['end'];
        }

        function getCenterCity(){
            $this->init();
            
            //$this->data = DB::findAll("city a,city_to_station b,station c,city d,city_to_station e,station f","a.city='{$this->start}' and a.id=b.city_id and b.station_id=c.id and c.name=d.city and d.id=e.city_id and e.station_id=f.id");
            $result = DB::query("select e.city_id from city a,city_to_station b,station c,city d,city_to_station e,station f where a.city='{$this->start}' and a.id=b.city_id and b.station_id=c.id and c.name=d.city and d.id=e.city_id and e.station_id=f.id and f.name='{$this->end}'");
            $this->data = $result->fetchall();
            // $result = DB::query("select c.name from city a,city_to_station b,station c where a.city='{$this->start}' and a.id=b.city_id and b.station_id=c.id");
            // while($row = $result->fetch()){
            //     $temp = DB::query("select c.name from city a,city_to_station b,station c where a.city like '%{$row['name']}%' and a.id=b.city_id and b.station_id=c.id and c.name like '%{$this->end}%'");
            //     $show = $temp->fetchall();
            //     p($row['name']);
            //     if($show)
            //     p($show);
            // }
            // die;
            return $this->data;
        }
    }
?>