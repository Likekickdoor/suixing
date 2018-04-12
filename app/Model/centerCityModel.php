<?php
    namespace app\Model;
    use lib\core\DB;
    class centerCity{

        private $data = "";
        private $start;
        private $end;

        function set(){
            set_time_limit(0);
            // $result = DB::findAll("city","state=1");
            // foreach ($result as $key) {
            //     $city = $key['city'];
            //     DB::find("center_city","place='{$city}'");
            //     if(DB::$rowcount > 0)
            //         continue;
            //     $temp = DB::query("select d.city from city a,city_to_station b,station c,city d where a.city like '%{$city}%' and a.id=b.city_id and b.station_id=c.id and c.name=d.city");
            //     $it_to = $temp->fetchall();
            //     $data1 = "";
            //     $size = count($it_to);
            //     for ($i = 0;$i<$size;$i++) {
            //         if(stristr($data1,$it_to[$i]['city']) == false)
            //             if($i == $size - 1)
            //                 $data1 .= $it_to[$i]['city'];
            //             else  $data1 .= $it_to[$i]['city'].",";
            //     }
            //     $temp = DB::query("select c.city from station a,city_to_station b,city c where a.name='{$city}' and a.id=b.station_id and b.city_id=c.id");
            //     $to_it = $temp->fetchall();
            //     $size = count($to_it);
            //     $data2 = "";
            //     for ($i = 0;$i<$size;$i++) {
            //         if(!stristr($data2,$to_it[$i]['city']))
            //             if($i == $size - 1)
            //                 $data2 .= $to_it[$i]['city'];
            //             else  $data2 .= $to_it[$i]['city'].",";
            //     }
            //     DB::insert("center_city","place,it_to,to_it","'{$city}','{$data1}','{$data2}'");
            //     p($city);
            //     p($data1);
            //     p($data2);
            //     p("");
            //     // die;
            // }
            // die;
            $result = DB::findAll("station","state=1");
            foreach ($result as $key) {
                $station = $key['name'];
                DB::find("center_city","place='{$station}'");
                if(DB::$rowcount > 0)
                    continue;
                DB::find("city","city like '%{$station}%'");
                if(DB::$rowcount > 0)
                    continue;
                $temp = DB::query("select c.city from station a,city_to_station b,city c where a.name='{$station}' and a.id=b.station_id and b.city_id=c.id");
                $to_it = $temp->fetchall();
                $size = count($to_it);
                $data2 = "";
                for($i=0;$i<$size;$i++){
                    if(!stristr($data2,$to_it[$i]['city']))
                        if($i == $size - 1)
                            $data2 .= $to_it[$i]['city'];
                        else  $data2 .= $to_it[$i]['city'].",";
                }
                $temp = DB::query("select c.city from station a,line b,city c where a.name='{$station}' and a.id=b.start_station_id and b.end_station_id=c.id");
                $it_to = $temp->fetchall();
                $size = count($it_to);
                $data1 = "";
                for($i=0;$i<$size;$i++){
                    if(!stristr($data1,$it_to[$i]['city']))
                        if($i == $size - 1)
                            $data1 .= $it_to[$i]['city'];
                        else  $data1 .= $it_to[$i]['city'].",";
                }
                p($station);
                p($data1);
                p($data2);
                p("");
                DB::insert("center_city","place,it_to,to_it","'{$station}','{$data1}','{$data2}'");
                // die;
            }
        }

        function init(){
            $this->start = $_GET['start'];
            $this->end = $_GET['end'];
        }

        function getCenterCity(){
            $this->init();
            // $result = DB::find("city","city like '%{$this->start}%'");
            // if(DB::$rowcount > 0){
            //     DB::find("city","city like '%{$this->end}%'");
            //     if(DB::$rowcount > 0){
            //         $this->city_to_city();
            //     }else{
            //         $this->city_to_station();
            //     }
            // }else{
            //     $result = DB::find("city","city like '{$this->end}'");
            //     if($result > 0){
            //         $this->station_to_city();
            //     }else{
            //         $this->station_to_station();
            //     }
            // }
            $result = DB::find("center_city","place like '%{$this->start}%'");
            $data1 = $result['it_to'].$result['to_it'];
            $result = DB::find("center_city","place like '%{$this->end}%'");
            $data2 = $result['it_to'].$result['to_it'];
            $this->data = $data1."|".$data2;
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