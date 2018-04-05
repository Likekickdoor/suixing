<?php
    namespace app\Model;
    use lib\core\DB;
    class centerCity{

        private $data = [];
        private $start;
        private $end;

        function init(){
            $this->start = $_GET['start'];
            $this->end = $_GET['end'];
        }

        function getCenterCity(){
            $this->init();
            $result = DB::find("city","city like '%{$this->start}%'");
            if(DB::$rowcount > 0){
                DB::find("city","city like '%{$this->end}%'");
                if(DB::$rowcount > 0){
                    $this->city_to_city();
                }else{
                    $this->city_to_station();
                }
            }else{
                $result = DB::find("city","city like '{$this->end}'");
                if($result > 0){
                    $this->station_to_city();
                }else{
                    $this->station_to_station();
                }
            }
            return $this->data;
        }

        function city_to_city(){
            $result = DB::query("select d.city from city a,city_to_station b,station c,city d where a.city like '%{$this->start}%' and a.id=b.city_id and b.station_id=c.id and c.name=d.city");
            array_push($this->data,$result->fetchall());
            $result = DB::query("select d.city from city a,city_to_station b,station c,city d where a.city like '%{$this->end}%' and a.id=b.city_id and b.station_id=c.id and c.name=d.city");
            array_push($this->data,$result->fetchall());
        }

        function city_to_station(){
            $result = DB::query("select d.city from city a,city_to_station b,station c,city d where a.city like '%{$this->start}%' and a.id=b.city_id and b.station_id=c.id and c.name=d.city");
            array_push($this->data,$result->fetchall());
            $result = DB::query("select c.city from station a,city_to_station b,city c where a.name='{$this->end}' and a.id=b.station_id and b.city_id=c.id");
            array_push($this->data,$result->fetchall());
        }

        function station_to_city(){
            $result = DB::query("select c.city from station a,city_to_station b,city c where a.name='{$this->start}' and a.id=b.station_id and b.city_id=c.id");
            array_push($this->data,$result->fetchall());
            $result = DB::query("select d.city from city a,city_to_station b,station c,city d where a.city like '%{$this->end}%' and a.id=b.city_id and b.station_id=c.id and c.name=d.city");
            array_push($this->data,$result->fetchall());
        }

        function station_to_station(){
            $result = DB::query("select c.city from station a,city_to_station b,city c where a.name='{$this->start}' and a.id=b.station_id and b.city_id=c.id");
            array_push($this->data,$result->fetchall());
            $result = DB::query("select c.city from station a,city_to_station b,city c where a.name='{$this->end}' and a.id=b.station_id and b.city_id=c.id");
            array_push($this->data,$result->fetchall());
        }
    }
?>