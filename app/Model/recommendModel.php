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
                return $busPrice;
            if(is_array($this->trainData)){
                if(array_key_exists("bSeat",$trainPrice) && $priceAll[0] == $trainPrice['bSeat'])
                    return $trainPrice;
                if(array_key_exists("hardSeat",$trainPrice) && $priceAll[0] == $trainPrice['hardSeat'])
                    return $trainPrice;
            }
            if(is_array($this->flightData) && $priceAll[0] == $filghtPrice['valuation'])
                return $filghtPrice;
            if(is_array($this->shipData) && $priceAll[0] == $shipPrice[5])
                return $shipPrice;
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
                return $busPrice;
            if(is_array($this->trainData)){
                if(array_key_exists("bSeat",$trainPrice) && $priceAll[0] == $trainPrice['bSeat'])
                    return $trainPrice;
                if(array_key_exists("hardSeat",$trainPrice) && $priceAll[0] == $trainPrice['hardSeat'])
                    return $trainPrice;
            }
            if(is_array($this->flightData) && $priceAll[0] == $filghtPrice['valuation'])
                return $filghtPrice;
            if(is_array($this->shipData) && $priceAll[0] == $shipPrice[5])
                return $shipPrice;
        }

    }
?>