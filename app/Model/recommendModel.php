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
            array_push($this->data,$price);
            // array_push($this->data,$time);
            return $this->data;                
        }

        function getPriceRecommend(){
            $priceAll = [];
            if(is_array($this->busData)){
                $busPrice = $this->busData[0];
                array_push($priceAll,$busPrice['price']);
                // p($busPrice);
            }
            if(is_array($this->trainData)){
                $trainPrice = $this->trainData[0][0];
                if(array_key_exists("bSeat",$trainPrice)){
                    array_push($priceAll,$trainPrice['bSeat']);
                }
                else if(array_key_exists("hardSeat",$trainPrice)){
                    array_push($priceAll,$trainPrice['hardSeat']);
                }
                // p($trainPrice);
            }
            if(is_array($this->flightData)){
                $flightPrice = $this->flightData[0];
                array_push($priceAll,$flightPrice['valuation']);
                // p($flightPrice);
            }
            if(is_array($this->shipData)){
                $shipPrice = $this->shipData[0];
                array_push($priceAll,$shipPrice[5]);
                // p($shipPrice);
            }
            sort($priceAll);
            if(is_array($this->busData) && $priceAll[0] == $busPrice['price'])           
                //$busPrice['sign'] = 2;
                return $busPrice;
            if(is_array($this->trainData)){
                if(array_key_exists("bSeat",$trainPrice) && $priceAll[0] == $trainPrice['bSeat'])
                //$trainPrice['sign'] = 1;    
                return $trainPrice;
                if(array_key_exists("hardSeat",$trainPrice) && $priceAll[0] == $trainPrice['hardSeat'])
                //$trainPrice['sign'] = 1;    
                return $trainPrice;
            }
            if(is_array($this->flightData) && $priceAll[0] == $filghtPrice['valuation']){
            //$filghtPrice['sign'] = 3;    
            return $filghtPrice;
            }
            if(is_array($this->shipData) && $priceAll[0] == $shipPrice[5]){
            //$shipPrice['sign'] = 4;    
            return $shipPrice;
           }
            if(is_array($this->busData)){
                $busPrice = $this->busData[0];
                array_push($priceAll,$busPrice['price']);
                // p($busPrice);
            }
            if(is_array($this->trainData)){
                $trainPrice = $this->trainData[0][0];
                if(array_key_exists("bSeat",$trainPrice)){
                    array_push($priceAll,$trainPrice['bSeat']);
                }
                else if(array_key_exists("hardSeat",$trainPrice)){
                    array_push($priceAll,$trainPrice['hardSeat']);
                }
                // p($trainPrice);
            }
            if(is_array($this->flightData)){
                $flightPrice = $this->flightData[0];
                array_push($priceAll,$flightPrice['valuation']);
                // p($flightPrice);
            }
            if(is_array($this->shipData)){
                $shipPrice = $this->shipData[0];
                array_push($priceAll,$shipPrice[5]);
                // p($shipPrice);
            }
            sort($priceAll);
            if(is_array($this->busData) && $priceAll[0] == $busPrice['price'])
                //$busPrice['sign'] = 2;
                return $busPrice;
            if(is_array($this->trainData)){
                if(array_key_exists("bSeat",$trainPrice) && $priceAll[0] == $trainPrice['bSeat'])
                //$trainPrice['sign'] = 1;
                return $trainPrice;
                if(array_key_exists("hardSeat",$trainPrice) && $priceAll[0] == $trainPrice['hardSeat'])
                //$trainPrice['sign'] = 1;
                return $trainPrice;
            }
            if(is_array($this->flightData) && $priceAll[0] == $filghtPrice['valuation']){
            //$filghtPrice['sign'] = 3;
            return $filghtPrice;
            }
            if(is_array($this->shipData) && $priceAll[0] == $shipPrice[5]){
            //$shipPrice['sign'] = 4;
            return $shipPrice;
           }
       }

	   function getTimeRecommend(){
            $timeAll = [];
            if(is_array($this->busData)){
                $busTime = $this->busData[0];
                array_push($timeAll,$busTime['time']);
                // p($busPrice);
            }
            if(is_array($this->trainData)){
                $trainTime = $this->trainData[0][0];
                array_push($timeAll,($trainTime['BrunTime']-$trainTime['ArunTime']));
                // p($trainPrice);
            }
            if(is_array($this->flightData)){
                $flightPrice = $this->flightData[0];
                array_push($timeAll,$flightTime['valuation']);
                // p($flightPrice);
            }
            if(is_array($this->shipData)){
                $shipTime = $this->shipData[0];
                array_push($timeAll,$shipTime[5]);
                // p($shipPrice);
            }
            sort($timeAll);
            if(is_array($this->busData) && $timeeAll[0] == $busTime['price'])
                return $busTime;
            if(is_array($this->trainData)){
                if(array_key_exists("bSeat",$trainTime) && $timeAll[0] == $trainTime['bSeat'])
                    return $trainTime;
                if(array_key_exists("hardSeat",$trainTime) && $timeAll[0] == $trainTime['hardSeat'])
                    return $trainTime;
            }
            if(is_array($this->flightData) && $timeAll[0] == $filghtTime['valuation'])
                return $filghtTime;
            if(is_array($this->shipData) && $timeAll[0] == $shipTime[5])
                return $shipTime;
            }

    }
?>
