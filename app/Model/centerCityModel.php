<?php
    namespace app\Model;
    use lib\core\DB;
    error_reporting(0);
    class centerCity{

        private $data = [];
        private $start;
        private $end;

        function set(){
            set_time_limit(0);
            $result = DB::findAll("city","state=1");
            foreach ($result as $key) {
                $city = $key['city'];
                DB::find("center_city","place='{$city}'");
                if(DB::$rowcount > 0)
                    continue;
                $city_id = DB::find("city","city like '%{$city}%'")['id'];
                //汽车
                $bus_data = $this->get_bus($city,$city_id);
                //飞机
                $flight_data = $this->get_flight($city);
                //火车
                $train_data = $this->get_train($city);
                //轮船
                $ship_data = $this->get_ship($city);
                // var_dump(($bus_data));
                // var_dump($train_data);
                // var_dump($flight_data);
                // var_dump($ship_data);
                // die;
                DB::insert("center_city","place,bus,train,flight,ship","'{$city}','{$bus_data}','{$train_data}','{$flight_data}','{$ship_data}'");
                // die;
            }
        }

        private function get_bus($city,$city_id){
            $bus_city = DB::query("select b.name from line a,station b where a.start_station_id={$city_id} and b.id=a.end_station_id");
            $bus_data = "";
            if($bus_city != NULL){
                $bus_city = $bus_city->fetchall();
                for ($i=0; $i < count($bus_city); $i++) { 
                    for($j=$i+1;$j < count($bus_city);$j++){
                        if(empty($bus_city[$i]) || empty($bus_city[$j]))
                            continue;
                        if($bus_city[$i]['name'] == $bus_city[$j]['name']){
                            unset($bus_city[$j]);
                        }
                    }
                    if(!empty($bus_city[$i]))
                        $bus_data .= $bus_city[$i]['name'].',';
                }
                $bus_data = substr($bus_data,0,strlen($bus_data) - 1);
            }
            return $bus_data;
        }

        private function get_flight($city){
            $flight_city = str_replace(array("市","区","省","特别行政区"),"",$city);
            $s_cityFrom = DB::query("SELECT id FROM city where city like '%$flight_city%'");
            $s_cityFrom = $s_cityFrom->fetchall();
            $flight_data = "";
            foreach($s_cityFrom as $value){
                $s_cityTo = DB::query("SELECT r_to_id from route_table where r_from_id=".$value['id']);
                $s_cityTo = $s_cityTo->fetchall();
                foreach($s_cityTo as $v){
                    $_cityToName = DB::query("SELECT city from city where id=".$v['r_to_id']);
                    $_cityToName = $_cityToName->fetchall();
                    // var_dump($_cityToName);
                    $flight_data .= $_cityToName[0]['city'].',';
                }
            }
            $flight_data = substr($flight_data,0,strlen($flight_data) - 1);
            return $flight_data;
        }

        private function get_train($city){
            $result = DB::query("SELECT  DISTINCT Res_city.stationName AS cityName
            FROM `station_stop` AS Res_city
            WHERE Res_city.trainNo IN(
              
            SELECT DISTINCT  First.trainNo
            FROM `station_stop` AS First
            WHERE First.stationName LIKE '{$city}%'
            
            ) AND  Res_city.stationName NOT LIKE '{$city}%'");
            $train_data = "";
            foreach ($result as $value) {
                $train_data .= $value['cityName'].',';
            }
            $train_data = substr($train_data,0,strlen($train_data) - 1);
            return $train_data;
        }

        private function get_ship($city){
            $condition = "name = '{$city}'";
            $con = DB::findAlls("id","station",$condition);
            foreach ($con as $value) {
            $sss = $value['id'];      //station表中对应的name的id
            // echo $sss;
            // echo "<br/>";
            $condition = "city_id = '{$sss}'";
            $con1 = DB::findAlls("name","ferry_id",$condition);
            foreach ($con1 as $value1) {
              $www = $value1['name'];    //ferry_id表中
              // echo $www;
              // echo "<br/>";
              $condi = "start = '{$www}'";
              $con2 = DB::findAlls("ferry_id",'details',$condi);
              foreach ($con2 as $value2) {
                $rr = $value2['ferry_id'];
                $condition1 = "ferry_id = '{$rr}'";
                $con2 = DB::findAlls("end","details",$condition1);
                foreach ($con2 as $value3) {
                  $ttt = $value3["end"];
                  $condition2 = "start = '{$ttt}'";
                  $con3 = DB::findAlls("ferry_id","details",$condition2);
                  foreach ($con3 as $value4){
                    $uuuu = $value4['ferry_id'];
                    $condition4 = "id = '{$uuuu}'";
                    $con4 = DB::findAlls("city_id","ferry_id",$condition4);
                    foreach ($con4 as $value5) {
                      $rrr =  $value5['city_id'];
                      $condition3 = "id = '{$rrr}'";
                      $con5 = DB::findAlls("name","station",$condition3);
                      foreach ($con5 as $value6) {
                        $value7[] = $value6['name'];
                      }
                    }
                  }
                }
              }
            }
            }
            $ship_data = array_unique($value7);
            $ship = "";
            foreach ($ship_data as $value) {
                $ship .= $value.',';
            }
            $ship = str_replace($city.',','',$ship);
            $ship = substr($ship,0,strlen($ship)-1);
            return $ship;//返回二维数组
        }
                

        function init(){
            $this->start = $_GET['start'];
            $this->end = $_GET['end'];
        }

        function getCenterCity(){
            $this->init();
            $bus = [];
            $train = [];
            $flight = [];
            $ship = [];
            $result = DB::find("center_city","place like '%{$this->start}%'");
            // var_dump($result);die;
            if($result != NULL){
                $place = DB::findAll("center_city","bus like '%{$this->end}%' or train like '%{$this->end}%' or flight like '%{$this->end}%' or ship like '%{$this->end}%'","place");
                $all_places = "a";
                $all_places .= $result['bus'].$result['train'].$result['flight'].$result['ship'];
                // var_dump($all_places);die;
                foreach ($place as $value) {
                    // var_dump($value);
                    if(strpos($all_places,$value['place']) != false)
                        $citys[] = $value['place'];
                }
                return $citys;
            }else{
                $station_id = DB::find("station","name='{$this->start}'")['id'];
                // p($station_id);die;
                $result = DB::findAll("line a,city b","a.start_station_id={$station_id} and a.end_station_id=b.id","distinct b.city");
                // $result = DB::findAll("line","start_station_id={$station_id}");
                // var_dump($result);die;
                $place = DB::findAll("center_city","bus like '%{$this->end}%' or train like '%{$this->end}%' or flight like '%{$this->end}%' or ship like '%{$this->end}%'","place");
                $all_places = "";
                foreach ($place as $value) {
                    $all_places .= $value['place'];
                }
                // var_dump($all_places);die;
                foreach ($result as $value) {
                    if(strpos($all_places,$value['city']) != false)
                        $citys[] = $value['city'];
                }
                // var_dump($place);die;
                // var_dump($citys);die;
                return $citys;
            }
        }
    }
?>