<?php
    namespace app\Model;
    use lib\core\DB;

    class recommend{

        private $busData = '';
        private $trainData = '';
        private $flightData = '';
        private $shipData = '';
        private $recommendData;
        private $data = [];

        function init($busData = "",$trainData = "",$flightData = "",$shipData = ""){
            $this->busData = $busData;
            $this->trainData = $trainData;
            $this->flightData = $flightData;
            $this->shipData = $shipData;
        }

        function getRecommend($busData = "",$trainData = "",$flightData = "",$shipData = ""){
            $this->init($busData,$trainData,$flightData,$shipData);
            $price = $this->getPriceRecommend();
            $time = $this->getTimeRecommend();
            if(!empty($price))
                array_push($this->data,$price);
            if(array_keys($price)[0] != array_keys($time)[0])
                if(!empty($time))
                    array_push($this->data,$time);
            // var_dump($this->data);
            // var_dump();
            // die;
            return $this->data;                
        }

        function getPriceRecommend(){
            $priceAll = [];
            if(!empty($this->busData)){
                $busPrice = $this->busData[0];
                array_push($priceAll,$busPrice['price']);
                // p($busPrice);
            }
            if(!empty($this->trainData)){
                $trainPrice = $this->trainData[0][0];
                if(array_key_exists("bSeat",$trainPrice)){
                    array_push($priceAll,$trainPrice['bSeat']);
                }
                else if(array_key_exists("hardSeat",$trainPrice)){
                    array_push($priceAll,$trainPrice['hardSeat']);
                }
                // p($trainPrice);
            }
            if(!empty($this->flightData)){
                $flightPrice = $this->flightData[0];
                array_push($priceAll,$flightPrice['valuation']);
                // p($flightPrice);
            }
            if(!empty($this->shipData)){
                $shipPrice = $this->shipData[0];
                array_push($priceAll,$shipPrice[5]);
                // p($shipPrice);
            }
            sort($priceAll);
            if(!empty($this->busData) && $priceAll[0] == $busPrice['price']) 
                return $busPrice;
            if(!empty($this->trainData)){
                if(array_key_exists("bSeat",$trainPrice) && $priceAll[0] == $trainPrice['bSeat'])
                return $trainPrice;
                if(array_key_exists("hardSeat",$trainPrice) && $priceAll[0] == $trainPrice['hardSeat'])
                return $trainPrice;
            }
            if(!empty($this->flightData) && $priceAll[0] == $filghtPrice['valuation']){
            return $filghtPrice;
            }
            if(!empty($this->shipData) && $priceAll[0] == $shipPrice[5]){
            return $shipPrice;
            }
       }

	   function getTimeRecommend(){
            $timeAll = [];
            $time1 = "";
            $time2 = "";
            if(!empty($this->busData)){
                $busTime = $this->busData[0];
                $time1 = $busTime['time'];
                $temp = explode("时",$busTime['time']);
                $min = $temp[0] * 60;
                $min += str_replace("分",'',$temp[1]);
                $busTime['time'] = $min;
                array_push($timeAll,$busTime['time']);
                // p($busTime['time']);
            }
            if(!empty($this->trainData)){
                $trainTime = $this->trainData[0][0];
                array_push($timeAll,($trainTime['BrunTime']-$trainTime['ArunTime']));
                // p($trainTime['BrunTime']-$trainTime['ArunTime']);
            }
            if(!empty($this->flightData)){
                $flightTime = $this->flightData[0];
                $time2 = $flightTime['f_flightTime'];
                $temp = explode("小时",$flightTime['f_flightTime']);
                $min = $temp[0] * 60;
                $min += str_replace("分",'',$temp[1]);
                $flightTime['f_flightTime'] = $min;
                array_push($timeAll,$flightTime['f_flightTime']);
                // p($flightTime['f_flightTime']);
            }
            if(!empty($this->shipData)){
                $shipTime = $this->shipData[3];
                array_push($timeAll,$shipTime[5]);
                // p($shipTime);
            }
            sort($timeAll);
            // p($timeAll);
            // die;
            if(!empty($this->busData) && $timeeAll[0] == $busTime['price']){
                $busTime['time'] = $time1;
                return $busTime;
            }
            if(!empty($this->trainData)){
                if(array_key_exists("bSeat",$trainTime) && $timeAll[0] == $trainTime['bSeat'])
                    return $trainTime;
                if(array_key_exists("hardSeat",$trainTime) && $timeAll[0] == $trainTime['hardSeat'])
                    return $trainTime;
            }
            if(!empty($this->flightData) && $timeAll[0] == $flightTime['f_flightTime']){
                $flightTime['f_flightTime'] = str_replace(array("小","钟"),"",$time2);
                return $flightTime;
            }
            if(!empty($this->shipData) && $timeAll[0] == $shipTime[5])
                return $shipTime;
        }

    }
?>
