<?php
    namespace app\Controller;
    class ajax{

        private $start;
        private $end;
        private $busData = '';
        private $trainData = '';
        private $flightData = '';
        private $shipData = '';
        private $centerCity = '';

        function bus(){
            $mod = M('bus');
            $this->busData = $mod->get_bus($this->start,$this->end,"price");
        }

        function trains(){        
            $trains_obj= M('TrainsPrice');
            $this->trainData = $trains_obj->Search_trains($this->start,$this->end);
        }

        function flight(){
            $flight_obj= M('flight');
            $this->flightData = $flight_obj->searchFlight($this->start,$this->end);
        }

        function ship(){
            $mod = M('ship');
            $this->shipData = $mod->pan($this->start,$this->end);
        }

        function getStation(){
            $mod = M('station');
            $data = $mod->get_station();
            $size = count($data);
            $value = "";
            foreach($data as $key) {
                $value .= $key['stations'];
            }
            p($value);
        }

        function getCenterCity(){
            if(!empty($_GET['start']) && !empty($_GET['end'])){
                $this->start = $_GET['start'];
                $this->end = $_GET['end'];
                $this->bus();
                $this->trains();
                $this->flight();
                if(empty($this->busData) && empty($this->trainData) && empty($this->flightData) && empty($this->shipData)){
                    $mod = M('centerCity');
                    $this->centerCity = $mod->getCenterCity();
                    echo $this->centerCity;
                }else{
                    echo "true";
                }
            }
        }

        function recommend(){
            if(is_array($this->trainData)){
                $train_M=M('TrainsPrice');
                $trainData_Price=$train_M-> PriceUpSort($this->trainData);
                $trainData_Time=$train_M-> TimeUpSort($this->trainData);
                $trainDatas=[];
                array_push($trainDatas,$trainData_Price);
                array_push($trainDatas,$trainData_Time);
            }
            $mod = M('recommend');
            $this->recommend = $mod->getRecommend($this->busData,$trainDatas,$this->flightData,$this->shipData);
        }

        function show(){
            if(!empty($_GET['start']) && !empty($_GET['end'])){
                if(!empty($_GET['fristCity'])){
                    $this->end = $_GET['fristCity'];
                    $this->start = $_GET['start'];
                }
                if(!empty($_GET['secondCity'])){
                    $this->start = $_GET['secondCity'];
                    $this->end = $_GET['end'];
                }
                $this->bus();
                $this->trains();
                $this->flight();
                $this->ship();
                $this->recommend();                
            }
        }

        function chooseFristCity(){
            $mod = M('chooseCity');
            $data = [];
            array_push($data,$mod->getFristCity());
            $this->show();
            // p($this->busData);
            array_push($data,$this->recommend);
            // echo $data[0];
            header('Content-Type: application/json; charset=utf8'); 
            echo json_encode($data,JSON_UNESCAPED_UNICODE);
        }

        function chooseSecondCity(){
            $mod = M('chooseCity');
            $data = [];
            array_push($data,$mod->getSecondCity());
            $this->show();
            array_push($data,$this->recommend);
            header('Content-Type: application/json; charset=utf8'); 
            echo json_encode($data,JSON_UNESCAPED_UNICODE);
        }

    }
?>